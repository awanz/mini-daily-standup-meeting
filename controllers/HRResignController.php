<?php
require_once 'EmailController.php';

class HRResignController extends EmailController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $resignQuery = '
            SELECT 
                er.id, u.fullname, r.name as role_name, er.status, er.file_resign, er.reason, u2.fullname, u2.id
            FROM 
                employee_resigns er
            LEFT JOIN users u on u.id = er.user_id
            LEFT JOIN users u2 on u2.id = er.approval_id
            LEFT JOIN roles r on r.id = u.role_id
            ORDER BY er.created_at DESC
            LIMIT 500
            ;
        ';
        $resigns = $this->db->raw($resignQuery)->fetch_all();

        $alert = $this->getMessage();
        $this->render('human-resource/resign/index', [
            'alert' => $alert,
            'resigns' => $resigns
        ]);
    }
    
    public function approveResign($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        
        try {
            $resignQuery = '
                SELECT 
                    *
                FROM 
                    employee_resigns
                WHERE status = "REQUEST"
                and id = '.$this->db->escape($_POST['id']).'
                ;
            ';
            $resign = $this->db->raw($resignQuery)->fetch_object();
            
            if (empty($resign)) {
                throw new Exception("Pengajuan resign tidak ditemukan, tidak ada yang direvise", 1);
            }
            // update resign
            $data = [
                "status" => 'APPROVED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d'),
            ];
            
            $update = $this->db->update("employee_resigns", $data, 'id', $resign->id);

            // update user
            $data = [
                "is_active" => 0,
                "status" => 'RESIGN',
                "updated_by" => $this->db->escape($this->user->id),
            ];
            $update = $this->db->update("users", $data, 'id', $resign->user_id);
            $user = $this->db->getBy("users", "id", $resign->user_id)->fetch_object();

            $subject = "Ucapan Perpisahan & Terima Kasih";
            $body = '<p>Dengan ini kami menginformasikan bahwa pengajuan <b>pengunduran diri</b> dari Saudara/i <b>'.$user->fullname.'</b> telah diterima. Kami mengucapkan terima kasih yang sebesar-besarnya atas kontribusi, dedikasi, dan kerja samanya selama ini.</p>

                <p>Kami mendoakan yang terbaik untuk langkah karier selanjutnya. Semoga sukses dan bahagia selalu menyertai di setiap perjalanan baru yang akan ditempuh.</p>

                <p>Selamat jalan dan sampai jumpa, semoga kita bisa bertemu lagi di kesempatan yang lebih baik.</p>

                <p><b>Salam hangat, <br>
                PT KAWAN KERJA INDONESIA<b>
                <p>';
            // kirim email
            $sendEmail = $this->sendEmailCustom($user->email, $user->fullname, $subject, $body);

            $this->setMessage('Pengajuan resign berhasil diterima. '.$sendEmail['message'], 'SUCCESS');
            $this->redirect('hr/resigns');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/resigns');
        }
        
    }
    
    public function reviseResign($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        try {
            $resignQuery = '
                SELECT 
                    *
                FROM 
                    employee_resigns
                WHERE status = "REQUEST"
                and id = '.$this->db->escape($_POST['id']).'
                ;
            ';
            $resign = $this->db->raw($resignQuery)->fetch_object();
            
            if (empty($resign)) {
                throw new Exception("Pengajuan resign tidak ditemukan, tidak ada yang direvise", 1);
            }
            $data = [
                "status" => 'REVISED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d'),
            ];
            $update = $this->db->update("employee_resigns", $data, 'id', $resign->id);
            $this->setMessage('Pengajuan resign berhasil direvise.', 'SUCCESS');
            $this->redirect('hr/resigns');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/resigns');
        }
        
    }
    
    public function cancelResign($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        try {
            $resignQuery = '
                SELECT 
                    *
                FROM 
                    employee_resigns
                WHERE status = "REQUEST"
                and id = '.$this->db->escape($_POST['id']).'
                ;
            ';
            $resign = $this->db->raw($resignQuery)->fetch_object();
            
            if (empty($resign)) {
                throw new Exception("Pengajuan resign tidak ditemukan, tidak ada yang dibatalkan", 1);
            }
            $data = [
                "status" => 'CANCELED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d'),
            ];
            $update = $this->db->update("employee_resigns", $data, 'id', $resign->id);
            $this->setMessage('Pengajuan resign berhasil dibatalkan.', 'SUCCESS');
            $this->redirect('hr/resigns');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/resigns');
        }
        
    }
}
