<?php
require_once 'EmailController.php';

class HRFinalizationController extends EmailController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $finalizationQuery = '
            SELECT 
                eif.id, u.fullname, r.name as role_name, eif.status, eif.file, eif.is_survey, u2.fullname, u2.id, u.date_start, u.date_end
            FROM 
                employee_intership_finalizations eif
            LEFT JOIN users u on u.id = eif.user_id
            LEFT JOIN users u2 on u2.id = eif.approval_id
            LEFT JOIN roles r on r.id = u.role_id
            ORDER BY eif.created_at DESC
            LIMIT 500
            ;
        ';
        $finalizations = $this->db->raw($finalizationQuery)->fetch_all();

        $alert = $this->getMessage();
        $this->render('human-resource/intership-finalization/index', [
            'alert' => $alert,
            'finalizations' => $finalizations
        ]);
    }
    
    public function approveFinalization($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        try {
            $finalizationQuery = '
                SELECT 
                    *
                FROM 
                    employee_intership_finalizations
                WHERE status = "REQUEST"
                and id = '.$this->db->escape($_POST['id']).'
                ;
            ';
            $finalization = $this->db->raw($finalizationQuery)->fetch_object();
            
            if (empty($finalization)) {
                throw new Exception("Pengajuan penyelesaian magang tidak ditemukan, tidak ada yang direvise", 1);
            }
            // update Finalization
            $data = [
                "status" => 'APPROVED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d'),
            ];
            $update = $this->db->update("employee_intership_finalizations", $data, 'id', $finalization->id);

            // update user
            $data = [
                "is_active" => 0,
                "status" => 'DONE',
                "updated_by" => $this->db->escape($this->user->id),
            ];
            $update = $this->db->update("users", $data, 'id', $finalization->user_id);
            $user = $this->db->getBy("users", "id", $finalization->user_id)->fetch_object();

            $subject = "Ucapan Perpisahan & Terima Kasih";
            $body = '<p>Dengan ini kami menginformasikan bahwa pengajuan <b>penyelesaian magang</b> dari Saudara/i <b>'.$user->fullname.'</b> telah diterima. Kami mengucapkan terima kasih yang sebesar-besarnya atas kontribusi, dedikasi, dan kerja samanya selama ini.</p>

                <p>Kami mendoakan yang terbaik untuk langkah karier selanjutnya. Semoga sukses dan bahagia selalu menyertai di setiap perjalanan baru yang akan ditempuh.</p>

                <p>Selamat jalan dan sampai jumpa, semoga kita bisa bertemu lagi di kesempatan yang lebih baik.</p>

                <p><b>Salam hangat, <br>
                PT KAWAN KERJA INDONESIA<b>
                <p>';

            // kirim email
            $sendEmail = $this->sendEmailCustom($user->email, $user->fullname, $subject, $body);

            $this->setMessage('Pengajuan penyelesaian magang berhasil diterima. '.$sendEmail['message'], 'SUCCESS');
            $this->redirect('hr/finalizations');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/finalizations');
        }
        
    }
    
    public function reviseFinalization($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        try {
            $finalizationQuery = '
                SELECT 
                    *
                FROM 
                    employee_intership_finalizations
                WHERE status = "REQUEST"
                and id = '.$this->db->escape($_POST['id']).'
                ;
            ';
            $finalization = $this->db->raw($finalizationQuery)->fetch_object();
            
            if (empty($finalization)) {
                throw new Exception("Pengajuan penyelesaian magang tidak ditemukan, tidak ada yang direvise", 1);
            }
            $data = [
                "status" => 'REVISED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d'),
            ];
            $update = $this->db->update("employee_intership_finalizations", $data, 'id', $finalization->id);
            $this->setMessage('Pengajuan penyelesaian magang berhasil direvise.', 'SUCCESS');
            $this->redirect('hr/finalizations');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/finalizations');
        }
        
    }
    
    public function cancelFinalization($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        try {
            $finalizationQuery = '
                SELECT 
                    *
                FROM 
                    employee_intership_finalizations
                WHERE status = "REQUEST"
                and id = '.$this->db->escape($_POST['id']).'
                ;
            ';
            $finalization = $this->db->raw($finalizationQuery)->fetch_object();
            
            if (empty($finalization)) {
                throw new Exception("Pengajuan penyelesaian magang tidak ditemukan, tidak ada yang dibatalkan", 1);
            }
            $data = [
                "status" => 'CANCELED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d'),
            ];
            $update = $this->db->update("employee_intership_finalizations", $data, 'id', $finalization->id);
            $this->setMessage('Pengajuan penyelesaian magang berhasil dibatalkan.', 'SUCCESS');
            $this->redirect('hr/finalizations');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/finalizations');
        }
        
    }
}
