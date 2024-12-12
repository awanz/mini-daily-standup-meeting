<?php
require_once 'BaseController.php';

class HomeController extends BaseController
{
    public function index($data = null)
    {
        $access = $this->user->access;
        if ($access == 'ADMIN' || $access == 'VOLUNTEER') {
            $projects = $this->db->raw('
                SELECT 
                    p.id, 
                    p.name, 
                    u.fullname, 
                    p.status, 
                    p.type, 
                    p.url_drive, 
                    p.url_figma, 
                    p.url_logo, 
                    p.url_repo, 
                    p.url_group_wa,
                    COUNT(pu.user_id) AS total_users,
                    p.is_priority,
                    p.is_a1
                FROM 
                    projects p
                LEFT JOIN 
                    users u ON p.pic = u.id
                LEFT JOIN 
                    project_users pu ON p.id = pu.project_id
                WHERE pu.user_id = '.$this->user->id.'
                    AND p.deleted_at is NULL
                GROUP BY 
                    p.id
                ORDER BY 
                    p.name
                ;
            ')->fetch_all();
            // $this->dd($projects);
            $alert = $this->getMessage();
            $this->render('profile/home', [
                'alert' => $alert,
                'projects' => $projects,
            ]);
        }else{
            $this->daily($data);
        }
    }
    
    public function daily($data = null)
    {
        // print_r($data);
        $dateGet = date('Y-m-d');
        if (isset($data['date'])) {
            $dateGet = $data['date'];
        }
        
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
        $user = $this->db->getBy("users", 'id', $this->user->id)->fetch_object();

        $alert = $this->getMessage();
        $this->render('index', [
            'date' => $dateGet,
            'alert' => $alert,
            'user' => $user,
            'daily' => $resultDaily,
        ]);
    }
    
    public function submitDaily()
    {
        // print_r($_POST);
        $email = $this->user->email;
        $yesterday = null;
        $today = null;
        $problem = null;
        $isAdmin = $this->isAdmin();

        $dateGet = date('Y-m-d');
        if (isset($_POST['date_activity'])) {
            $dateGet = htmlspecialchars(trim($_POST['date_activity']), ENT_QUOTES, 'UTF-8');
        }

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
                        // $this->dd($data);
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
              $query = 'SELECT d.id, d.user_id, d.date_activity, d.yesterday, d.today, d.problem, d.created_at, u.email, u.fullname FROM dailys d INNER JOIN users u ON d.user_id = u.id WHERE u.is_active = 1 ORDER BY created_at DESC limit 200;';
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
            $this->redirect('home');
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

        $projectQuery = '
            SELECT 
                p.name, pu.status, p.status, p.type, p.url_group_wa, p.url_drive, p.url_figma, p.id
            FROM 
                project_users pu
            LEFT JOIN users u
                ON pu.user_id = u.id
            LEFT JOIN projects p
                ON pu.project_id = p.id
            WHERE pu.user_id = '.$user->id.'
            ;
        ';
        $projects = $this->db->raw($projectQuery)->fetch_all();

        $meetings = $this->db->raw('
            SELECT 
                m.title, ma.status, m.date, m.time_start, m.time_end, ma.note
            FROM 
                meeting_attendances ma
            LEFT JOIN 
                meetings m ON m.id = ma.meeting_id
            WHERE ma.user_id = '.$this->user->id.'
            ORDER BY 
                m.date desc
            ;
        ')->fetch_all();

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
            'projects' => $projects,
            'meetings' => $meetings,
        ]);
    }
    
    public function changePassword()
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
        $this->render('profile/change-password', [
            'user' => $user,
            'dailys' => $dailys, 
            'alert' => $alert,
            'warnings' => $warnings,
            'role' => $role,
            'roles' => $roles,
        ]);
    }
    
    public function changePasswordProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $user = $this->db->getBy("users", 'id', $this->user->id)->fetch_object();

            $password = md5(md5($_POST['password']));
            $new_password = md5(md5($_POST['new_password']));
            
            try {
                if ($password != $user->password) {
                    throw new Exception("Password lama tidak sama!", 1);
                }

                if ($password == $new_password) {
                    throw new Exception("Password lama dan baru sama!", 1);
                }

                $pattern = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/';

                if (!preg_match($pattern, $_POST['new_password'])) {
                    throw new Exception("Password harus minimal 6 karakter dan mengandung huruf serta angka.", 1);
                }

                $data = [
                    "password" => $this->db->escape($new_password),
                ];
                
                $update = $this->db->update("users", $data, 'id', $user->id);
                $this->setMessage('Ganti password user berhasil!', 'SUCCESS');
                $this->redirect('profile/change-password');
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('profile/change-password');
            }
        }
       
        $this->setMessage('Ganti password user gagal!', 'FAILED');
        $this->redirect('profile/change-password');
    }
}
