<?php
require_once 'BaseController.php';

class ProjectController extends BaseController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        // echo "sadsa";die();
        $projects = array();

        if ($isAdmin) {
            $projects = $this->db->raw('
                SELECT 
                    p.id AS project_id,
                    p.name AS project_name,
                    u.fullname AS pic_fullname,
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
                WHERE 
                    p.deleted_at IS NULL
                GROUP BY 
                    p.id
                ORDER BY 
                    p.name
                ;
            ')->fetch_all();
        }else{
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
                WHERE p.pic = '.$this->user->id.'
                    AND p.deleted_at is NULL
                GROUP BY 
                    p.id
                ORDER BY 
                    p.name
                ;
            ')->fetch_all();
        }
        
        $alert = $this->getMessage();
        $this->render('project/index', [
            'alert' => $alert,
            'projects' => $projects
        ]);
    }

    public function add()
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        $alert = $this->getMessage();
        $this->render('project/add', [
            'alert' => $alert
        ]);
        
    }

    public function create()
    {
        // print_r('dasdas');
        // die();
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $name = trim($_POST['name']);
            $status = trim($_POST['status']);
            $type = trim($_POST['type']);
            
            try {
                $data = [
                    "name" => $name,
                    "status" => $status,
                    "type" => $type,
                    "created_by" => $this->user->id,
                ];
                
                $insertData = $this->db->insert("projects", $data);
                $this->setMessage('Simpan project berhasil!', 'SUCCESS');
                $this->redirect('project/add');
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('project/add');
            }
        }
        
    }
    
    public function edit($data)
    {
        
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();

        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        if (empty($project)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('project');
        }

        $listPM = $this->db->raw('
            SELECT 
                *
            FROM 
                users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE (r.name= "Project Manager IT" OR r.name= "CEO of Products" OR u.access = "ADMIN")
            AND u.is_active = 1
            ORDER BY u.fullname
            ;
        ')->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('project/edit', [
            'alert' => $alert,
            'project' => $project,
            'listPM' => $listPM,
        ]);
        
    }
    
    public function update($data)
    {
        
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();
        if (empty($project)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('project');
        }

        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $name = trim($_POST['name']);
            $pic = null;
            if ($isProjectManager && $project->pic == $this->user->id) {
                $pic = $project->pic;
            }else{
                $pic = trim($_POST['pic']);
            }
            $status = trim($_POST['status']);
            $type = trim($_POST['type']);

            $url_group_wa = trim($_POST['url_group_wa']);
            $url_drive = trim($_POST['url_drive']);
            $url_figma = trim($_POST['url_figma']);
            $url_logo = trim($_POST['url_logo']);
            $url_repo = trim($_POST['url_repo']);
            $note = trim($_POST['note']);
            
            try {
                $data = [
                    "name" => $name,
                    "pic" => $pic,
                    "status" => $status,
                    "type" => $type,
                    "url_group_wa" => $url_group_wa,
                    "url_drive" => $url_drive,
                    "url_figma" => $url_figma,
                    "url_logo" => $url_logo,
                    "url_repo" => $url_repo,
                    "note" => $note,
                    "updated_by" => $this->user->id,
                ];

                // $this->dd($data);
                
                $update = $this->db->update("projects", $data, 'id', $id);
                $this->setMessage('Update project berhasil!', 'SUCCESS');
                $this->redirect('project/edit/'.$id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('project/edit/'.$id);
            }
        }
        
    }
    
    public function delete($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('project');
        }

        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();
        
        if (empty($project)) {
            $this->setMessage('Data yang ingin di delete tidak ada!');
            $this->redirect('project');
        }

        try {
            $data = [
                "deleted_at" => date('Y-m-d H:i:s'),
                "updated_by" => $this->user->id,
            ];
            $update = $this->db->update("projects", $data, 'id', $id);
            $this->setMessage('Data berhasil di delete', 'SUCCESS');
            $this->redirect('project');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('project');
        }
    }

    public function detail($data)
    {
        // $this->dd('dasdas');
        // $isAdmin = $this->isAdmin();
        // $isProjectManager = $this->isProjectManager();

        // if (!$isAdmin && !$isProjectManager) {
        //     $this->setMessage('Kamu tidak punya hak akses!');
        //     $this->redirect('home');
        // }

        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();

        // if ($isProjectManager && $project->pic != $this->user->id) {
        //     $this->setMessage('Kamu tidak punya hak akses!');
        //     $this->redirect('home');
        // }
        
        if (empty($project)) {
            $this->setMessage('Project yang dipilih tidak ada');
            $this->redirect('project');
        }
        $queryProjectUser = '
            SELECT 
                u.id, u.fullname, u.email, u.phone, u.date_start, u.date_end, u.total_daily, u.role, u.is_finish, pu.notes, pu.status, pu.id
            FROM 
                project_users pu
            LEFT JOIN view_user_daily u
            ON pu.user_id = u.id
            WHERE pu.project_id = '.$id.'
            ORDER BY pu.status asc, u.id asc;
        ';
        $users = $this->db->raw($queryProjectUser)->fetch_all();
        $dailys = array();

        if (count($users) > 0) {
            $arrayMapUser = array_map(function($item) {
                return (string)$item[0];
            }, $users);
            $dateGet = date('Y-m-d', strtotime(date('Y-m-d') . ' -5 days'));

            $queryDailys = '
                SELECT 
                    d.id, d.user_id, d.date_activity, d.yesterday, d.today, d.problem, d.created_at, vud.email, vud.fullname 
                FROM dailys d 
                INNER JOIN view_user_daily vud
                ON d.user_id = vud.id
            ';

            $keyFinal = null;
            foreach ($arrayMapUser as $key => $value) {
                $keyFinal .= '"' . $value . '",';
            }
            $keyFinal = rtrim($keyFinal, ',');
            $queryDailys = $queryDailys . " WHERE d.user_id IN ($keyFinal) AND d.date_activity >= '$dateGet'";
            $queryDailys = $queryDailys . " ORDER BY d.date_activity desc, vud.fullname asc";
            // $this->dd($queryDailys);
            $dailys = $this->db->raw($queryDailys)->fetch_all();
        }
    
        $queryMeetings = '
            SELECT 
                m.id, m.title, m.description, m.date, m.time_start, m.time_end
            FROM meetings m 
            WHERE m.project_id = '.$id.'
            ORDER BY m.time_start DESC
            LIMIT 50;
        ';

        $meetings = $this->db->raw($queryMeetings)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('project/detail', [
            'alert' => $alert,
            'id' => $id,
            'users' => $users,
            'dailys' => $dailys,
            'project' => $project,
            'meetings' => $meetings,
        ]);
        
    }
    
    public function addMember($data)
    {
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();

        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $queryProjectUser = '
            SELECT 
                id, user_id, project_id
            FROM 
                project_users
            WHERE status = "ACTIVED" and project_id = '.$id.'
            ORDER BY id asc;
        ';
        $projectUser = $this->db->raw($queryProjectUser)->fetch_all();
        $arrayMapUser = array_map(function($item) {
            return (string)$item[1];
        }, $projectUser);
        
        $query = "
            SELECT 
                vud.id, fullname, role
            FROM 
                view_user_daily vud
            LEFT JOIN project_users pu
            ON vud.id = pu.user_id
        ";

        if (count($arrayMapUser) > 0) {
            $keyFinal = null;
            foreach ($arrayMapUser as $key => $value) {
                $keyFinal .= '"' . $value . '",';
            }
            $keyFinal = rtrim($keyFinal, ',');
            $query = $query . " WHERE vud.id NOT IN ($keyFinal)";
        }
        $query = $query . " GROUP BY vud.id, fullname, role ORDER BY fullname asc";
        // $this->dd($query);
        $users = $this->db->raw($query)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('project/add-member', [
            'alert' => $alert,
            'id' => $id,
            'users' => $users,
        ]);
        
    }
    
    public function createMember($data)
    {
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();

        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        if (empty($project)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('project');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $user_id = trim($_POST['user_id']);
            
            try {
                $queryProjectUser = '
                    SELECT 
                        *
                    FROM 
                        project_users
                    WHERE project_id = '.$id.'
                    AND user_id = '.$user_id.'
                    LIMIT 1;
                ';
                $projectUser = $this->db->raw($queryProjectUser)->fetch_object();
                // $this->dd($projectUser);

                if ($projectUser) {
                    $data = [
                        "status" => "ACTIVED",
                    ];
                    $update = $this->db->update("project_users", $data, 'id', $projectUser->id);
                }else{
                    $data = [
                        "project_id" => $id,
                        "user_id" => $this->db->escape($user_id),
                    ];
                    $insertData = $this->db->insert("project_users", $data);
                }
                
                $this->setMessage('Tambah anggota project berhasil!', 'SUCCESS');
                $this->redirect('project/add-member/'.$id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('project/add-member/'.$id);
            }
        }
        
    }

    public function nonactiveMember($data)
    {
        
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $projectUser = $this->db->getBy("project_users", "id", $id)->fetch_object();
        $project = $this->db->getBy("projects", "id", $projectUser->project_id)->fetch_object();
        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        if (empty($projectUser)) {
            $this->setMessage('Data yang ingin di nonactive tidak ada!');
            $this->redirect('project/detail/'.$id);
        }

        try {
            $data = [
                "status" => "NONACTIVED",
            ];
            $update = $this->db->update("project_users", $data, 'id', $id);
            $this->setMessage('Data berhasil di nonactive', 'SUCCESS');
            $this->redirect('project/detail/'.$projectUser->project_id);
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('project/detail/'.$projectUser->project_id);
        }
    }
    
    public function activeMember($data)
    {
        
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $projectUser = $this->db->getBy("project_users", "id", $id)->fetch_object();
        $project = $this->db->getBy("projects", "id", $projectUser->project_id)->fetch_object();

        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        if (empty($projectUser)) {
            $this->setMessage('Data yang ingin di nonactive tidak ada!');
            $this->redirect('project/detail/'.$id);
        }

        try {
            $data = [
                "status" => "ACTIVED",
            ];
            $update = $this->db->update("project_users", $data, 'id', $id);
            $this->setMessage('Data berhasil di active', 'SUCCESS');
            $this->redirect('project/detail/'.$projectUser->project_id);
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('project/detail/'.$projectUser->project_id);
        }
    }

    public function note($data)
    {
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['project_user_id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();

        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $project_user_id = $this->db->escape($data['project_user_id']);
        $queryProjectUser = '
            SELECT 
                pu.id, pu.user_id, pu.project_id, pu.status, pu.notes, u.fullname, p.name as project_name
            FROM 
                project_users pu
            LEFT JOIN users u
            ON u.id = pu.user_id
            LEFT JOIN projects p
            ON p.id = pu.project_id
            WHERE pu.id = '.$project_user_id.'
            LIMIT 1;
        ';
        // $this->dd($queryProjectUser);
        $projectUser = $this->db->raw($queryProjectUser)->fetch_object();
        
        $alert = $this->getMessage();
        $this->render('project/note', [
            'alert' => $alert,
            'project_user_id' => $project_user_id,
            'projectUser' => $projectUser,
        ]);
        
    }

    public function updateNote($data)
    {
        
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();

        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        $project_user_id = $this->db->escape($data['project_user_id']);
        $queryProjectUser = '
            SELECT 
                pu.id, pu.user_id, pu.project_id, pu.status, pu.notes, u.fullname, p.name as project_name
            FROM 
                project_users pu
            LEFT JOIN users u
            ON u.id = pu.user_id
            LEFT JOIN projects p
            ON p.id = pu.project_id
            WHERE pu.id = '.$project_user_id.'
            LIMIT 1;
        ';
        $projectUser = $this->db->raw($queryProjectUser)->fetch_object();
        
        if (empty($projectUser)) {
            $this->setMessage('Data yang ingin diberi catatan tidak ada!');
            $this->redirect('project');
        }

        try {
            $notes = htmlspecialchars(trim($_POST['notes']));
            $data = [
                "notes" => $this->db->escape($notes),
            ];
            $update = $this->db->update("project_users", $data, 'id', $project_user_id);
            $this->setMessage('Data berhasil disimpan', 'SUCCESS');
            $this->redirect('project/detail/note/'.$project_user_id);
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('project/detail/note/'.$project_user_id);
        }
    }

    public function attendance($data)
    {
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $project_id = $this->db->escape($data['project_id']);
        $project = $this->db->getBy("projects", "id", $project_id)->fetch_object();

        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $queryProjectUser = '
            SELECT 
                u.id, u.fullname, u.role, u.is_finish, pu.project_id
            FROM 
                project_users pu
            LEFT JOIN view_user_daily u
            ON pu.user_id = u.id
            WHERE 
                pu.project_id = '.$project_id.'
                AND pu.status = "ACTIVED"
            ORDER BY u.fullname asc;
        ';
        $projectUsers = $this->db->raw($queryProjectUser)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('project/attendance', [
            'alert' => $alert,
            'project_id' => $project_id,
            'projectUsers' => $projectUsers,
        ]);
        
    }
    
    public function createAttendance($data)
    {
        $isAdmin = $this->isAdmin();
        $isProjectManager = $this->isProjectManager();

        if (!$isAdmin && !$isProjectManager) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $project_id = $this->db->escape($data['project_id']);
        $project = $this->db->getBy("projects", "id", $project_id)->fetch_object();
        if ($isProjectManager && $project->pic != $this->user->id) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $user_ids = $_POST['user_ids'];
        $attendances = $_POST['attendances'];
        $notes = $_POST['notes'];
        $timeStart = $_POST['time_start'];
        $date = $date = date("Y-m-d", strtotime($timeStart));
        $duration = $_POST['duration'];
        $description = $_POST['description'];

        if (empty($timeStart)) {
            $this->setMessage('Tanggal mulai belum dipilih!');
            $this->redirect('project/meeting-attendance/'.$project_id);
        }

        $combined = array_map(function ($user_id, $attendance, $note) {
            return [
                "user_id" => $user_id,
                "status" => $attendance,
                "note" => $note
            ];
        }, $user_ids, $attendances, $notes);        

        $this->db->beginTransaction();
        try {
            $dataMeeting = [
                "title" => 'Meeting Project ' . $project->name,
                "project_id" => $project_id,
                "type" => 'MEETING_PROJECT',
                "created_by" => $this->user->id,
                "date" => $date,
                "time_start" => $timeStart,
                "description" => $description,
            ];
            $insertMeeting = $this->db->insert("meetings", $dataMeeting);
            // $this->dd($insertMeeting);
            $insertParticipant = null;
            foreach ($combined as $elm) {
                if ($elm['status'] == 'NONE') {
                    continue;
                }
                $elm['meeting_id'] = $insertMeeting['last_id'];
                $elm['created_by'] = $this->user->id;
                $insertParticipant = $this->db->insert("meeting_attendances", $elm);
            }
            if (empty($insertParticipant)) {
                throw new Exception("Tidak ada peserta yang ikut meeting", 1);
            }
            $this->db->commit();
            $this->setMessage('Data kehadiran berhasil disimpan', 'SUCCESS');
            $this->redirect('project/detail/'.$project_id);
        } catch (\Throwable $th) {
            $this->db->rollback();
            $this->setMessage($th->getMessage());
            $this->redirect('project/meeting-attendance/'.$project_id);
        }
    }
}
