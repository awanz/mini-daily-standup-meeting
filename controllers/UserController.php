<?php
require_once 'BaseController.php';

class UserController extends BaseController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        // echo "sadsa";die();
        // $users = $this->db->getAll("view_user_daily")->fetch_all();
        $query = '
            SELECT 
                *
            FROM 
                view_user_daily vud;
        ';
        $users = $this->db->raw($query)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('user/index', [
            'alert' => $alert,
            'users' => $users,
        ]);
    }
    
    public function listNonactive()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        // echo "sadsa";die();
        $users = $this->db->getBy("users", 'is_active', 0)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('user/list-nonactive', [
            'alert' => $alert,
            'users' => $users
        ]);
    }

    public function add()
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $roles = $this->db->getAllClean("roles", true, "name asc")->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('user/add', [
            'alert' => $alert,
            'roles' => $roles,
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
            $fullname = trim($_POST['fullname']);
            $email = strtolower(trim($_POST['email']));
            $phone = trim($_POST['phone']);
            $role_id = trim($_POST['role_id']);
            $date_start = trim($_POST['date_start']);
            // $date_end = trim($_POST['date_end']);
            $date_start_obj = new DateTime($date_start);
            $date_start_obj->modify('+'.DURATION_MAGANG.' months');
            $date_end = $date_start_obj->format('Y-m-d');
            
            try {
                $data = [
                    "email" => $this->db->escape($email),
                    "fullname" => $this->db->escape($fullname),
                    "phone" => $this->db->escape($phone),
                    "role_id" => $this->db->escape($role_id),
                    "date_start" => $this->db->escape($date_start),
                    "date_end" => $this->db->escape($date_end),
                    "created_by" => $this->user->id,
                ];
                
                $insertData = $this->db->insert("users", $data);
                $this->setMessage('Simpan user berhasil!', 'SUCCESS');
                $this->redirect('user/add');
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('user/add');
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
        // $user = $this->db->getBy("users", "id", $id)->fetch_object();

        $queryUser = '
            SELECT 
                u.*,
                u2.fullname as created_name,
                u3.fullname as updated_name
            FROM 
                users u
            LEFT JOIN users u2 ON u.created_by = u2.id
            LEFT JOIN users u3 ON u.updated_by = u3.id
            WHERE u.id = '.$id.';
        ';
        $user = $this->db->raw($queryUser)->fetch_object();
        
        if (empty($user)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('user');
        }
        $roles = $this->db->getAllClean("roles", true, "name asc")->fetch_all();
        $alert = $this->getMessage();
        $this->render('user/edit', [
            'alert' => $alert,
            'user' => $user,
            'roles' => $roles,
        ]);
        
    }
    
    public function detail($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $user = $this->db->getBy("users", 'id', $id)->fetch_object();
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
                        p.name, pu.status, p.status, p.type, p.url_group_wa
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

        $alert = $this->getMessage();
        $this->render('user/detail', [
            'user' => $user,
            'dailys' => $dailys, 
            'alert' => $alert,
            'warnings' => $warnings,
            'role' => $role,
            'roles' => $roles,
            'projects' => $projects,
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
            $fullname = trim($_POST['fullname']);
            $email = strtolower(trim($_POST['email']));
            $phone = trim($_POST['phone']);
            $date_start = trim($_POST['date_start']);
            $date_end = trim($_POST['date_end']);
            $role_id = trim($_POST['role_id']);
            $access = trim($_POST['access']);
            $notes = trim($_POST['notes']);
            
            try {
                $data = [
                    "email" => $this->db->escape($email),
                    "fullname" => $this->db->escape($fullname),
                    "phone" => $this->db->escape($phone),
                    "date_start" => $this->db->escape($date_start),
                    "date_end" => $this->db->escape($date_end),
                    "role_id" => $this->db->escape($role_id),
                    "access" => $this->db->escape($access),
                    "notes" => $this->db->escape($notes),
                    "updated_by" => $this->user->id,
                ];
                
                $update = $this->db->update("users", $data, 'id', $id);
                $this->setMessage('Simpan user berhasil!', 'SUCCESS');
                $this->redirect('user/edit/'.$id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('user/edit/'.$id);
            }
        }
        
    }
    
    public function actived($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        try {

            $warningQuery = '
                    SELECT 
                        *
                    FROM 
                        warnings
                    WHERE user_id = '.$id.'
                    AND counter = 2
                    AND is_appeal is NULL
                    LIMIT 1;
                ';
            $warning = $this->db->raw($warningQuery)->fetch_object();
            if ($warning) {
                throw new Exception("Error: User sudah dikeluarkan, harus lewat proses banding", 1);
            }


            $data = [
                "is_active" => 1,
                "updated_by" => $this->user->id,
            ];
            
            $update = $this->db->update("users", $data, 'id', $id);
            $this->setMessage('User berhasil diaktifkan!', 'SUCCESS');
            $this->redirect('user/nonactive');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('user/nonactive');
        }
        
    }
    
    public function delete($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('user');
        }

        $id = $this->db->escape($data['id']);
        $user = $this->db->getBy("users", "id", $id)->fetch_object();
        
        if (empty($user)) {
            $this->setMessage('Data yang ingin di delete tidak ada!');
            $this->redirect('user');
        }

        try {
            $data = [
                "is_active" => 0,
                "updated_by" => $this->user->id,
            ];
            $update = $this->db->update("users", $data, 'id', $id);
            $this->setMessage('Data berhasil di delete', 'SUCCESS');
            $this->redirect('user');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('user');
        }
    }
}
