<?php
require_once 'BaseController.php';

class LoginController extends BaseController
{

    public function checkLogged(){
        if (isset($_SESSION['user'])) {
            $this->redirect('home');
        }
    }

    public function index()
    {
        $alert = $this->getMessage();
        $this->render('login', [
            'siteKey' => TURNSTILE_SITE_KEY,
            'alert' => $alert
        ]);
    }
    
    public function loginProcess()
    {
        $turnstileVerify = $this->turnstileVerify($_POST['cf-turnstile-response']);
        if (!$turnstileVerify) {
            $this->setMessage('Captcha gagal, coba lagi');
            $this->redirect('login');
        }

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = preg_replace('/[^a-zA-Z0-9.@]/', '', $_POST['email']);
            $password = md5(md5(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['password'])));
            $param = [
              'email' => $email,
              'password' => $password,
              'is_active' => 1
            ];
            
            $result = $this->db->getByArray("users", $param)->fetch_object();
            if (is_null($result)) {
                $this->setMessage('Email atau password salah coba lagi.');
                $this->redirect('login');
            }
            unset($result->password);
            $_SESSION['user'] = $result;
            $data = [
                "last_login_at" => date('Y-m-d H:i:s'),
            ];
            $update = $this->db->update("users", $data, 'id', $result->id);
            // print_r($update);die();
            if ($_SESSION['user']) {
                $this->redirect('home');
            }
            
        }else {
            $this->setMessage('Email atau password yang dikirim kosong');
        }

        $this->redirect('login');
    }
    
    public function forgotPassword()
    {
        $this->render('forgot-password', ['name' => 'World']);
    }
}
