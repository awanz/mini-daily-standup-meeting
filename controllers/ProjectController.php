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
            
            try {
                $data = [
                    "name" => $name,
                    "status" => $status,
                    "type" => $type,
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
}
