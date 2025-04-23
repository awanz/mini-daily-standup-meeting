<?php
$file = __DIR__ . '/../libraries/plates/plates-autoload.php';
$fileMySQL = __DIR__ . '/../libraries/database/mysql.php';

if (!file_exists($file)) {
    die("File not found: $file");
}

if (!file_exists($fileMySQL)) {
    die("File not found: $fileMySQL");
}

require_once $file;
require_once $fileMySQL;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libraries/PHPMailer/Exception.php';
require_once __DIR__ . '/../libraries/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libraries/PHPMailer/SMTP.php';

use League\Plates\Engine;

class BaseController
{
    private $templates;
    public $db;
    public $user;

    public function __construct()
    {
        $this->checkLogged();
        $this->templates = new Engine(__DIR__ . '/../views');
        $this->templates->addData([
            'isSuperAdmin' => $this->isSuperAdmin(),
            'isAdmin' => $this->isAdmin(),
            'isHR' => $this->isHR(),
            'isProjectManager' => $this->isProjectManager(),
            'isUser' => $this->isUser(),
            'dataUser' => $this->user,
            'siteTitle' => 'Kawan Kerja',
        ]);
        $this->db = new MySQLBase(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
    }

    public function render(string $view, array $data = [])
    {
        echo $this->templates->render($view, $data);
    }

    public function checkLogged(){
        // print_r($_SESSION);die();
        if (isset($_SESSION['user'])) {
            $this->user = $_SESSION['user'];
        }
        if (!$this->user) {
            $this->redirect('login');
        }
    }
    
    public function isAdmin(){
        // print_r($this->user);die();
        $isAdmin = false;
        if ($this->user && $this->user->access == 'ADMIN' || $this->user && $this->user->access == 'SUPERADMIN') {
            $isAdmin = true;
        }

        return $isAdmin;
    }
    
    public function isSuperAdmin(){
        $isSuperAdmin = false;
        if ($this->user && $this->user->access == 'SUPERADMIN') {
            $isSuperAdmin = true;
        }

        return $isSuperAdmin;
    }
    
    public function isProjectManager(){
        // print_r($this->user);die();
        $isProjectManager = false;
        if ($this->user && ($this->user->role_name == 'Project Manager IT' || $this->user->role_name == 'Product Owner')) {
            $isProjectManager = true;
        }

        return $isProjectManager;
    }

    public function isHR(){
        // print_r($this->user);die();
        $isHR = false;
        if ($this->user && ($this->user->role_name == 'HR Generalist' || $this->user->role_name == 'HR Recruiter' || $this->user->role_name == 'HR Personalia')) {
            $isHR = true;
        }

        return $isHR;
    }
    
    public function isUser(){
        // print_r($this->user);die();
        $isUser = false;
        if ($this->user && $this->user->access == 'USER') {
            $isUser = true;
        }

        return $isUser;
    }
    
    public function dd($data = null){
        echo "<pre>";
        print_r($data);
        die();
    }

    public function urlencode($string) {
        $string = str_replace('-', '___MINUS___', $string);
        $string = str_replace('/', '___SLASH___', $string);
    
        $string = urlencode($string);
    
        $string = str_replace('___MINUS___', '-', $string);
        $string = str_replace('___SLASH___', '/', $string);
    
        return $string;
    }

    public function redirect(string $path)
    {
        if (!empty($path)) {
            $pathEncode = BASE_URL . '/' . $this->urlencode($path);
            // $this->dd($pathEncode);
            header("Location: " . $pathEncode, false, 301);
            exit();
        }
        header("Location: ". BASE_URL, false, 301);
        exit();
    }
    
    public function setMessage($message, $status = 'FAILED', $key = 'alert')
    {
        return $_SESSION['flash_message_'.$key] = [
            'status' => $status,
            'message' => $message,
        ];
    }
    
    public function getMessage($key = 'alert')
    {
        $result = null;
        if (isset($_SESSION['flash_message_'.$key])) {
            $result = $_SESSION['flash_message_'.$key];
            unset($_SESSION['flash_message_'.$key]);
        }
        return $result;
    }

    public function turnstileVerify($data = null){
        $ch = curl_init();
        if (defined('TURNSTILE_SSL_DISABLE')) {
            if (TURNSTILE_SSL_DISABLE == 'true') {
                // disable if deploy server
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                // disable if deploy server
            }
        }
        
        curl_setopt($ch, CURLOPT_URL, TURNSTILE_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => TURNSTILE_SECRET_KEY,
            'response' => $data,
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            die('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);
        // print_r($response);

        // $data = json_decode($response);

        $responseData = json_decode($response, true);
        // print_r($responseData); die();
        return $responseData['success'];
    }

    public function sendEmail($receiver, $fullname, $subject, $body){
        
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP_DEBUG;
            $mail->isSMTP();
            
            $mail->Host       = SMTP_HOST;
            $mail->Port       = SMTP_PORT;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;

            $mail->SMTPSecure = 'tls';
            
            $mail->setFrom(SMTP_USERNAME, SMTP_FULLNAME);
            $mail->addAddress($receiver, $fullname);
            $mail->AddCC($receiver);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            // $result = $mail->send();
            // print_r($result);die();
            if ($mail->send()) {
                return $resultFinal = [
                    'status' => true,
                    'message' => 'Email ke <b>'. $fullname .' ('.$receiver.')</b> berhasil di kirim',
                ];
            } else {
                return $resultFinal = [
                    'status' => false,
                    'message' => 'Email ke <b>'. $fullname .' ('.$receiver.')</b> gagal di kirim',
                ];
            }
        } catch (Exception $e) {
            return $resultFinal = [
                'status' => false,
                'message' => $mail->ErrorInfo,
            ];
        }
        
    }

    function uploadToGoogleDrive($filePath, $fileName, $accessToken) {
        $fileContent = file_get_contents($filePath);
    
        $metadata = [
            'name' => $fileName,
            'parents' => [$GOOGLE_CREDENTIAL_FOLDER_ID] // ID folder tujuan di Google Drive
        ];
    
        $boundary = uniqid();
        $delimiter = "--" . $boundary;
        $closeDelimiter = "--" . $boundary . "--";
    
        $body = implode("\r\n", [
            $delimiter,
            'Content-Type: application/json; charset=UTF-8',
            '',
            json_encode($metadata),
            $delimiter,
            'Content-Type: ' . mime_content_type($filePath),
            '',
            $fileContent,
            $closeDelimiter
        ]);
    
        $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: multipart/related; boundary=$boundary",
            "Content-Length: " . strlen($body)
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        return json_decode($response, true);
    }
}
