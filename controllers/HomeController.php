<?php
require_once 'BaseController.php';

class HomeController extends BaseController
{
    public function index($data = null)
    {
        // print_r($data);
        $dateGet = date('Y-m-d');
        if (isset($data['date'])) {
            $dateGet = $data['date'];
        }
        
        $alert = $this->getMessage();
        if (isset($data['date'])) {
            $dateGet = $this->db->escape($data['date']);
            $today = date('Y-m-d');
            if (strtotime($dateGet) < strtotime(date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))))) || strtotime($dateGet) > strtotime($today)) {
                $this->redirect('home');
            }
        }

        $paramDailyFInd = [
            "user_id" => $this->user->id,
            "date_activity" => $dateGet
        ];

        $resultDaily = $this->db->getByArray("dailys", $paramDailyFInd)->fetch_object();
        $this->render('index', [
            'siteKey' => TURNSTILE_SITE_KEY, 
            'date' => $dateGet,
            'alert' => $alert,
            'daily' => $resultDaily
        ]);
    }
    
    public function submitDaily()
    {
        // print_r($_POST);
        $email = $this->user->email;
        $yesterday = null;
        $today = null;
        $problem = null;
        $dateGet = htmlspecialchars(trim($_POST['date_activity']), ENT_QUOTES, 'UTF-8');
        $dateGet = $dateGet ? $dateGet : date('Y-m-d');
        $isAdmin = $this->isAdmin();

        if (!$email) {
            $this->setMessage('Session Email tidak terdaftar');
            $this->redirect('login');
        }
        
        $paramDailyFInd = [
            "user_id" => $this->user->id,
            "date_activity" => $dateGet
        ];

        $resultDaily = $this->db->getByArray("dailys", $paramDailyFInd)->fetch_object();
        if (isset($resultDaily)) {
            $yesterday = $resultDaily->yesterday;
            $today = $resultDaily->today;
            $problem = $resultDaily->problem;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            // die('xcxcxc');
            $yesterday = htmlspecialchars(trim($_POST['yesterday']), ENT_QUOTES, 'UTF-8');
            $today = htmlspecialchars(trim($_POST['today']), ENT_QUOTES, 'UTF-8');
            $problem = htmlspecialchars(trim($_POST['problem']), ENT_QUOTES, 'UTF-8');
            if (str_word_count($yesterday) > 1 && str_word_count($today) > 1 && str_word_count($problem) > 1) {
                try {
                    if (is_null($resultDaily)) {
                        $data = [
                            "user_id" => $this->db->escape($this->user->id),
                            "yesterday" => $this->db->escape($yesterday),
                            "today" => $this->db->escape($today),
                            "problem" => $this->db->escape($problem),
                            "date_activity" => $this->db->escape($dateGet),
                            "email" => $this->db->escape($email),
                        ];
                        $insert = $this->db->insert("dailys", $data);
                        $this->setMessage('Lapor daily berhasil dilakukan.', 'SUCCESS');
                        $this->redirect('home/'.$dateGet);
                    }
                    $this->setMessage('Lapor sudah pernah dilakukan, tidak dapat diulang 2 kali di hari yang sama!');
                    $this->redirect('home/'.$dateGet);
                } catch (\Throwable $th) {
                    $this->setMessage($th->getMessage());
                    $this->redirect('home/'.$dateGet);
                }
            }else{
                $this->setMessage('Semua masukan harus diisi minimal 2 kata, cek kembali.');
                $this->redirect('home/'.$dateGet);
            }
        }
        $this->setMessage('Error tidak diketahui, coba lagi');
        $this->redirect('home/'.$dateGet);
    }
    
    public function history($data)
    {
        // print_r($this->user->email);die();
        $isAccess = $this->isAdmin();
        $getEmail = null;
        if (isset($data['email'])) {
            $getEmail = $this->db->escape($data['email']);
        }
        if ($isAccess) {
            if ($getEmail) {
              $query = "SELECT d.id, d.user_id, d.date_activity, d.yesterday, d.today, d.problem, d.created_at, u.email, u.fullname FROM dailys d INNER JOIN users u ON d.user_id = u.id WHERE u.is_active = 1 and d.email = '".$getEmail."';";
            }else{
              $query = 'SELECT d.id, d.user_id, d.date_activity, d.yesterday, d.today, d.problem, d.created_at, u.email, u.fullname FROM dailys d INNER JOIN users u ON d.user_id = u.id WHERE u.is_active = 1;';
            }
            $resultDailys = $this->db->raw($query)->fetch_all();
        }else{
            $user = $this->db->getBy("users", "email", $this->user->email)->fetch_object();
            $resultDailys = $this->db->getBy("dailys", "user_id", $user->id, "date_activity DESC")->fetch_all();
        }
        $alert = $this->getMessage();
        $this->render('daily-standup-meeting/history', [
            'dailys' => $resultDailys,
            'alert' => $alert
        ]);
    }

    public function logout2()
    {
        session_destroy();
        $this->redirect('login');
    }
    
    public function delete($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses untuk melakukan delete');
            $this->redirect('history');
        }

        $id = $this->db->escape($data['id']);
        $daily = $this->db->getBy("dailys", "id", $id)->fetch_object();
        
        if (empty($daily)) {
            $this->setMessage('Data yang ingin di delete tidak ada!');
            $this->redirect('history');
        }

        try {
            $insert = $this->db->delete("dailys", 'id', $id);
            $this->setMessage('Data berhasil di delete', 'SUCCESS');
            $this->redirect('history');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('history');
        }
    }

    public function profile()
    {
        $user = $this->db->getBy("users", 'id', $this->user->id)->fetch_object();
        unset($user->password);
        $dailys = $this->db->getBy("dailys", "user_id", $user->id, "date_activity DESC")->fetch_all();
        $warnings = $this->db->getBy("warnings", "user_id", $user->id)->fetch_all();
        $roles = $this->db->getAllClean("roles")->fetch_all();
        $role = null;
        if (!empty($user->role_id)) {
            $role = $this->db->getBy("roles", 'id', $user->role_id)->fetch_object();
        }
        // echo "<pre>";
        // print_r($warnings);die();
        $alert = $this->getMessage();
        $this->render('profile/index', [
            'user' => $user,
            'dailys' => $dailys, 
            'alert' => $alert,
            'warnings' => $warnings,
            'role' => $role,
            'roles' => $roles,
        ]);
    }
}