<?php
require_once 'BaseController.php';

class RoleController extends BaseController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        // echo "sadsa";die();
        $roles = $this->db->getAllClean("roles")->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('role/index', [
            'alert' => $alert,
            'roles' => $roles
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
        $this->render('role/add', [
            'alert' => $alert
        ]);
        
    }

    public function create()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $url_group_wa = trim($_POST['url_group_wa']);
            
            try {
                $data = [
                    "name" => $name,
                    "description" => $description,
                    "url_group_wa" => $url_group_wa,
                ];
                
                $insertData = $this->db->insert("roles", $data);
                $this->setMessage('Simpan role berhasil!', 'SUCCESS');
                $this->redirect('role/add');
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('role/add');
            }
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
        $role = $this->db->getBy("roles", "id", $id)->fetch_object();
        
        if (empty($role)) {
            $this->setMessage('Data yang ingin di delete tidak ada!');
            $this->redirect('role');
        }

        try {
            $data = [
                "deleted_at" => date('Y-m-d H:i:s'),
                "updated_by" => $this->user->id,
            ];
            $update = $this->db->update("roles", $data, 'id', $id);
            $this->setMessage('Data berhasil di delete', 'SUCCESS');
            $this->redirect('role');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('role');
        }
    }

    public function member($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('history');
        }

        $id = $this->db->escape($data['id']);
        $users = $this->db->getBy("users", "role_id", $id)->fetch_all();
        
        if (empty($users)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('role');
        }
        
        $alert = $this->getMessage();
        $this->render('role/member', [
            'alert' => $alert,
            'users' => $users
        ]);
        
    }
}