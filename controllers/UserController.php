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
        $users = $this->db->getAll("view_user_daily")->fetch_all();
        
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

        $roles = $this->db->getAllClean("roles")->fetch_all();
        
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
            $email = trim($_POST['email']);
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
            $this->redirect('history');
        }

        $id = $this->db->escape($data['id']);
        $user = $this->db->getBy("users", "id", $id)->fetch_object();
        
        if (empty($user)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('user');
        }
        $roles = $this->db->getAllClean("roles")->fetch_all();
        $alert = $this->getMessage();
        $this->render('user/edit', [
            'alert' => $alert,
            'user' => $user,
            'roles' => $roles,
        ]);
        
    }
    
    public function update($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('history');
        }

        $id = $this->db->escape($data['id']);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $fullname = trim($_POST['fullname']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $date_start = trim($_POST['date_start']);
            $date_end = trim($_POST['date_end']);
            $role_id = trim($_POST['role_id']);
            $access = trim($_POST['access']);
            
            try {
                $data = [
                    "email" => $this->db->escape($email),
                    "fullname" => $this->db->escape($fullname),
                    "phone" => $this->db->escape($phone),
                    "date_start" => $this->db->escape($date_start),
                    "date_end" => $this->db->escape($date_end),
                    "role_id" => $this->db->escape($role_id),
                    "access" => $this->db->escape($access),
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
            $this->redirect('history');
        }

        $id = $this->db->escape($data['id']);
        try {
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
