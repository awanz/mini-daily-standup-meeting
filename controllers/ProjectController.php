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
            $this->redirect('history');
        }

        $id = $this->db->escape($data['id']);
        $user = $this->db->getBy("users", "id", $id)->fetch_object();
        
        if (empty($user)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('user');
        }
        
        $alert = $this->getMessage();
        $this->render('user/edit', [
            'alert' => $alert,
            'user' => $user
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
            
            try {
                $data = [
                    "email" => $email,
                    "fullname" => $fullname,
                    "phone" => $phone,
                    "date_start" => $date_start,
                    "date_end" => $date_end,
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
}
