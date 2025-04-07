<?php
require_once 'EmailController.php';

class WarningController extends EmailController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        
        // $warnings = $this->db->getAll("view_user_warnings")->fetch_all();

        $queryWarning = '
            SELECT 
                vuw.*
            FROM 
                view_user_warnings vuw
            where vuw.fullname is not null
            limit 500;
        ';
        $warnings = $this->db->raw($queryWarning)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('warning/index', [
            'alert' => $alert,
            'warnings' => $warnings
        ]);
    }

    public function appeal($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $warning = $this->db->getBy("view_user_warnings", "id", $id)->fetch_object();
        $user = $this->db->getBy("users", "id", $warning->user_id)->fetch_object();

        // $this->dd($user);
        
        if (empty($warning)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('warnings');
        }

        $alert = $this->getMessage();
        $this->render('warning/appeal', [
            'alert' => $alert,
            'warning' => $warning,
            'user' => $user,
        ]);
        
    }
    
    public function appealProcess($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $warning = $this->db->getBy("warnings", "id", $id)->fetch_object();

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $add_time = trim($_POST['add_time']);
            $note = trim($_POST['note']);
            $note = $note . '\n +' . $add_time . ' pekan waktu kontrak.';
            
            try {
                $data = [
                    "is_appeal" => 1,
                    "note" => $note,
                ];
                $update = $this->db->update("warnings", $data, 'id', $id);

                $user = $this->db->getBy("users", "id", $warning->user_id)->fetch_object();
                $data = [
                    "is_active" => 1,
                    "date_end" => date('Y-m-d H:i:s', strtotime('+'.$add_time.' weeks', strtotime($user->date_end)))
                ];
                $updateUser = $this->db->update("users", $data, 'id', $user->id);

                $this->setMessage('Banding berhasil!', 'SUCCESS');
                $this->redirect('warnings/appeal/'.$id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('warnings/appeal/'.$id);
            }
        }
        
    }

    public function add($data)
    {
        
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $users = $this->db->raw('
            SELECT u.id, fullname, r.name as role FROM users u
            join roles r on u.role_id = r.id
            where access <> "ADMIN" and is_active = 1;
        ')->fetch_all();

        $alert = $this->getMessage();
        $this->render('warning/create', [
            'alert' => $alert,
            'users' => $users,
        ]);
        
    }

    public function addProcess()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $user_id = $this->db->escape($_POST['user_id']);
        $type = $this->db->escape($_POST['type']);
        $reason = $this->db->escape($_POST['reason']);


        $result = $this->warningCustom($user_id, $type, $reason);
        $this->setMessage($result, 'SUCCESS');
        $this->redirect('warnings/add');
    }
}
