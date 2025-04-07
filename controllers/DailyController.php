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
                    p.status, 
                    MAX(CASE 
                        WHEN pu.status = "ACTIVED" THEN "ACTIVED"
                        WHEN pu.status = "NONACTIVED" THEN "NONACTIVED"
                        ELSE NULL
                    END) AS pu_status,
                    p.type,
                    (SELECT COUNT(pu_sub.user_id) 
                    FROM project_users pu_sub 
                    WHERE pu_sub.project_id = p.id) AS total_users,
                    p.is_priority,
                    p.is_a1
                FROM 
                    projects p
                LEFT JOIN 
                    project_users pu ON p.id = pu.project_id
                WHERE 
                    pu.user_id = '.$this->user->id.'
                    AND p.deleted_at IS NULL
                GROUP BY 
                    p.id, p.name, p.status, p.type, p.is_priority, p.is_a1
                ORDER BY 
                    FIELD(MAX(CASE 
                        WHEN pu.status = "ACTIVED" THEN "ACTIVED"
                        WHEN pu.status = "NONACTIVED" THEN "NONACTIVED"
                        ELSE NULL
                    END), "ACTIVED", NULL, "NONACTIVED"), 
                    p.name;

            ')->fetch_all();
            $queryMeeting = null;

            if ($access == 'ADMIN') {
                $queryMeeting = '
                    SELECT 
                        m.id,
                        m.title,
                        m.date,
                        m.time_start,
                        m.time_end,
                        m.type
                    FROM 
                        meetings m
                    LEFT JOIN meeting_attendances ma ON ma.meeting_id = m.id
                    LEFT JOIN users u ON ma.user_id = u.id
                    LEFT JOIN roles r ON r.id = u.role_id
                    WHERE (m.title <> "" OR m.title is not null)
                    GROUP BY
                        m.id,
                        m.title,
                        m.date,
                        m.time_start,
                        m.time_end,
                        m.type
                    ORDER BY m.date desc LIMIT 250;
                ';
            }else{
                $queryMeeting = '
                    SELECT 
                        m.id,
                        m.title,
                        m.date,
                        m.time_start,
                        m.time_end,
                        m.type
                    FROM 
                        meetings m
                    LEFT JOIN meeting_attendances ma ON ma.meeting_id = m.id
                    LEFT JOIN users u ON ma.user_id = u.id
                    LEFT JOIN roles r ON r.id = u.role_id
                    WHERE ma.user_id = '.$this->user->id.'
                    AND (m.title <> "" OR m.title is not null)
                    GROUP BY
                        m.id,
                        m.title,
                        m.date,
                        m.time_start,
                        m.time_end,
                        m.type
                    ORDER BY m.date desc;
                ';
            }

            
            $meetings = $this->db->raw($queryMeeting)->fetch_all();
            // $this->dd($meetingAttendance);
            
            $alert = $this->getMessage();
            $this->render('profile/home', [
                'alert' => $alert,
                'projects' => $projects,
                'meetings' => $meetings,
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
            $dateGet = $this->db->escape($data['date']);
        }
        
        $today = date('Y-m-d');
        if (strtotime($dateGet) < strtotime(date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d'))))) || strtotime($dateGet) > strtotime($today)) {
            $this->setMessage('Hanya bisa daily hari ini dan 30 hari kebelakang!');
            $this->redirect('home');
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
            $dateGet = $this->db->escape($_POST['date_activity']);
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
              $query = "SELECT d.id, d.user_id, d.date_activity, d.yesterday, d.today, d.problem, d.created_at, u.email, u.fullname, u.id as user_id FROM dailys d INNER JOIN users u ON d.user_id = u.id WHERE u.is_active = 1 and d.email = '".$getEmail."';";
            }else{
              $query = 'SELECT d.id, d.user_id, d.date_activity, d.yesterday, d.today, d.problem, d.created_at, u.email, u.fullname, u.id as user_id FROM dailys d INNER JOIN users u ON d.user_id = u.id WHERE u.is_active = 1 ORDER BY created_at DESC limit 500;';
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

}
