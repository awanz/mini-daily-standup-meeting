<?php
require_once 'BaseController.php';

class ProjectController extends BaseController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        // echo "sadsa";die();
        $projects = $this->db->getAllClean("projects")->fetch_all();
        
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

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();
        
        if (empty($project)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('project');
        }
        
        $alert = $this->getMessage();
        $this->render('project/edit', [
            'alert' => $alert,
            'project' => $project
        ]);
        
    }
    
    public function update($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $name = trim($_POST['name']);
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

    public function member($data)
    {
        // $this->dd('dasdas');
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("projects", "id", $id)->fetch_object();
        
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
            AND pu.status = "ACTIVED"
            ORDER BY u.id asc;
        ';
        $users = $this->db->raw($queryProjectUser)->fetch_all();
        $dailys = array();

        if (count($users) > 0) {
            $arrayMapUser = array_map(function($item) {
                return (string)$item[0];
            }, $users);
            $dateGet = date('Y-m-d');

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
            $queryDailys = $queryDailys . " WHERE d.user_id IN ($keyFinal) AND d.date_activity = '$dateGet'";
            $queryDailys = $queryDailys . " ORDER BY d.created_at desc";
            // $this->dd($queryDailys);
            $dailys = $this->db->raw($queryDailys)->fetch_all();
        }
    
        
        
        $alert = $this->getMessage();
        $this->render('project/member', [
            'alert' => $alert,
            'id' => $id,
            'users' => $users,
            'dailys' => $dailys,
            'project' => $project,
        ]);
        
    }
    
    public function addMember($data)
    {
        // $this->dd('dasdas');
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
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

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        
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

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('project');
        }

        $id = $this->db->escape($data['id']);
        $project = $this->db->getBy("project_users", "id", $id)->fetch_object();
        
        if (empty($project)) {
            $this->setMessage('Data yang ingin di nonactive tidak ada!');
            $this->redirect('project/member/'.$id);
        }

        try {
            $data = [
                "status" => "NONACTIVED",
            ];
            $update = $this->db->update("project_users", $data, 'id', $id);
            $this->setMessage('Data berhasil di nonactive', 'SUCCESS');
            $this->redirect('project/member/'.$project->project_id);
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('project/member/'.$project->project_id);
        }
    }
}
