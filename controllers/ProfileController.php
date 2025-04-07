<?php
require_once 'BaseController.php';

class ProfileController extends BaseController
{
    public function resign()
    {
        $resignQuery = '
            SELECT 
                *
            FROM 
                employee_resigns
            WHERE
                user_id = '.$this->user->id.'
            ORDER BY created_at DESC
            limit 1
            ;
        ';
        $resign = $this->db->raw($resignQuery)->fetch_object();
        // $this->dd($resign);

        $alert = $this->getMessage();
        $this->render('profile/resign', [
            'alert' => $alert,
            'resign' => $resign
        ]);
    }
    
    public function resignProcess($data)
    {
        try {
            $data = [
                "user_id" => $this->db->escape($this->user->id),
                "employee_id" => $this->db->escape($this->user->id),
                "status" => 'REQUEST',
                "file_resign" => $this->db->escape($_POST['file_resign']),
                "reason" => $this->db->escape($_POST['reason']),
                "created_by" => $this->db->escape($this->user->id),
            ];
            $insert = $this->db->insert("employee_resigns", $data);
            $this->setMessage('Pengajuan resign berhasil dikirim.', 'SUCCESS');
            $this->redirect('profile/resign');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('profile/resign');
        }
        
    }
    
    public function resignCancelProcess($data)
    {
        try {
            $resignQuery = '
                SELECT 
                    *
                FROM 
                    employee_resigns
                WHERE status = "REQUEST"
                and user_id = '.$this->user->id.'
                ;
            ';
            $resign = $this->db->raw($resignQuery)->fetch_object();
            // $this->dd($resign);
            if (empty($resign)) {
                throw new Exception("Pengajuan resign tidak ditemukan, pastikan sudah mengajukan", 1);
            }
            $data = [
                "status" => 'CANCELED',
                "updated_by" => $this->db->escape($this->user->id),
            ];
            $update = $this->db->update("employee_resigns", $data, 'id', $resign->id);
            $this->setMessage('Pengajuan resign berhasil dibatalkan.', 'SUCCESS');
            $this->redirect('profile/resign');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('profile/resign');
        }
        
    }

    public function intershipFinalization()
    {
        $isAdmin = $this->isAdmin();

        if ($isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $finalizationQuery = '
            SELECT 
                *
            FROM 
                employee_intership_finalizations
            WHERE
                user_id = '.$this->user->id.'
            ORDER BY created_at DESC
            limit 1
            ;
        ';
        $finalization = $this->db->raw($finalizationQuery)->fetch_object();
        // $this->dd($resign);

        $alert = $this->getMessage();
        $this->render('profile/intership-finalization', [
            'alert' => $alert,
            'finalization' => $finalization
        ]);
    }

    public function intershipFinalizationProcess($data)
    {
        $isAdmin = $this->isAdmin();

        if ($isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        try {
            if ($_POST['survey_status'] == 0) {
                throw new Exception("Isi survey terlebih dahulu, baru mengajukan penyelesaian magang", 1);
            }
            $data = [
                "user_id" => $this->db->escape($this->user->id),
                "employee_id" => $this->db->escape($this->user->id),
                "status" => 'REQUEST',
                "file" => $this->db->escape($_POST['file']),
                "is_survey" => 1,
                "created_by" => $this->db->escape($this->user->id),
            ];
            $insert = $this->db->insert("employee_intership_finalizations", $data);
            $this->setMessage('Pengajuan penyelesaian magang berhasil dikirim.', 'SUCCESS');
            $this->redirect('profile/intership-finalization');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('profile/intership-finalization');
        }
        
    }

    public function intershipFinalizationCancelProcess($data)
    {
        $isAdmin = $this->isAdmin();

        if ($isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        try {
            $finalizationQuery = '
                SELECT 
                    *
                FROM 
                    employee_intership_finalizations
                WHERE status = "REQUEST"
                and user_id = '.$this->user->id.'
                ;
            ';
            $finalization = $this->db->raw($finalizationQuery)->fetch_object();
            
            if (empty($finalization)) {
                throw new Exception("Pengajuan penyelesaian magang tidak ditemukan, pastikan sudah mengajukan", 1);
            }
            $data = [
                "status" => 'CANCELED',
                "updated_by" => $this->db->escape($this->user->id),
            ];
            $update = $this->db->update("employee_intership_finalizations", $data, 'id', $finalization->id);
            $this->setMessage('Pengajuan penyelesaian magang berhasil dibatalkan.', 'SUCCESS');
            $this->redirect('profile/intership-finalization');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('profile/intership-finalization');
        }
        
    }
}
