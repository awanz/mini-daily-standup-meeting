<?php
require_once 'EmailController.php';

class ContractExtend extends EmailController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $contractExtendQuery = "
            SELECT 
                ce.id, ce.created_at, u.fullname, r.name as role_name, ce.duration, ce.description, ce.status, ce.approval_id, u2.fullname as approval_name
            FROM contract_extend ce
            LEFT JOIN users u2 on ce.approval_id = u2.id
            LEFT JOIN users u on ce.user_id = u.id
            LEFT JOIN roles r on u.role_id = r.id
            and u.fullname is not null
            ORDER BY ce.created_at DESC
            LIMIT 500
            ;
        ";
        $contractExtend = $this->db->raw($contractExtendQuery);

        $alert = $this->getMessage();
        $this->render('human-resource/contract-extend/index', [
            'alert' => $alert,
            'contractExtend' => $contractExtend
        ]);
    }
    
    public function approveContractExtend($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        try {
            $contractExtend = $this->db->raw('
                SELECT 
                    *
                FROM 
                    contract_extend
                WHERE status = "REQUEST"
                and id = '.$this->db->escape($_POST['id']).'
                ;
            ')->fetch_object();
            
            if (empty($contractExtend)) {
                throw new Exception("Pengajuan perpanjangan magang tidak ditemukan, tidak ada yang diapprove", 1);
            }

            $data = [
                "status" => 'APPROVED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d')
            ];
            $update = $this->db->update("contract_extend", $data, 'id', $contractExtend->id);

            // update user
            $user = $this->db->raw('
                SELECT 
                    date_end
                FROM 
                    users
                WHERE id = '.$this->db->escape($contractExtend->user_id).'
                ;
            ')->fetch_object();
            $originalDateEnd = new DateTime($user->date_end);
            $now = new DateTime();

            if ($originalDateEnd > $now) {
                $dateEnd = $originalDateEnd->modify('+' . $contractExtend->duration . ' month')->format('Y-m-d');
            } else {
                $dateEnd = $now->modify('+' . $contractExtend->duration . ' month')->format('Y-m-d');
            }
            $data = [
                "is_active" => 1,
                "status" => 'ACTIVE',
                "updated_by" => $this->db->escape($this->user->id),
                "date_end" => $dateEnd
            ];
            $update = $this->db->update("users", $data, 'id', $contractExtend->user_id);
            $user = $this->db->getBy("users", "id", $contractExtend->user_id)->fetch_object();

            $subject = "Perpanjangan Magang";
            $body = '<p>Dengan ini kami menginformasikan bahwa pengajuan <b>perpanjangan magang</b> dari Saudara/i <b>'.$user->fullname.'</b> telah diterima.</p>

                <p>Sehubungan dengan adanya permohonan perpanjangan masa magang yang bersangkutan, kami menyatakan menyetujui perpanjangan masa magang tersebut sampai tanggal '.$dateEnd.'.</p>
                <p>Selama masa perpanjangan, yang bersangkutan tetap wajib menaati peraturan dan tata tertib yang berlaku di perusahaan.</p>
                <p>Demikian surat ini dibuat untuk digunakan sebagaimana mestinya.</p>
                <p>
                    <b>Salam hangat, <br>
                    PT KAWAN KERJA INDONESIA<b>
                <p>';

            // kirim email
            $sendEmail = $this->sendEmailCustom($user->email, $user->fullname, $subject, $body);

            $this->setMessage('Pengajuan perpanjangan magang berhasil diterima. '.$sendEmail['message'], 'SUCCESS');
            $this->redirect('hr/contract-extend');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/contract-extend');
        }
        
    }
    
    public function reviseContractExtend()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        try {
            $contractExtend = $this->db->raw('
                SELECT 
                    *
                FROM 
                    contract_extend
                WHERE status = "REQUEST"
                and id = '.$this->db->escape($_POST['id']).'
                ;
            ')->fetch_object();
            
            if (empty($contractExtend)) {
                throw new Exception("Pengajuan perpanjangan magang tidak ditemukan, tidak ada yang direvise", 1);
            }
            $data = [
                "status" => 'REVISED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d'),
            ];
            $update = $this->db->update("contract_extend", $data, 'id', $contractExtend->id);
            $this->setMessage('Pengajuan perpanjangan magang berhasil direvise.', 'SUCCESS');
            $this->redirect('hr/contract-extend');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/contract-extend');
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
    
    public function certificateList()
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
                    eif.id, eif.status, eif.approval_date, u.fullname, r.name as role_name, u.date_start, u.date_end
                FROM employee_intership_finalizations eif
                LEFT JOIN users u on eif.user_id = u.id
                LEFT JOIN roles r on u.role_id = r.id
                WHERE eif.status = "APPROVED"
                and r.name is not null
                ORDER BY eif.created_at DESC
                ;
            ';
            $finalizations = $this->db->raw($finalizationQuery);
            // $this->dd($finalizations);
            $alert = $this->getMessage();
            $this->render('human-resource/intership-finalization/certificate', [
                'alert' => $alert,
                'finalizations' => $finalizations
            ]);
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/finalizations');
        }
        
    }
    
    public function certificatePrint()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();
        // $id = $_POST['id'];
        $id = htmlspecialchars(strip_tags($_POST['id']), ENT_QUOTES, 'UTF-8');
        // $this->dd($id);

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        try {
            $finalizationQuery = '
                SELECT 
                    eif.id, eif.status, eif.certificate, DATE_FORMAT(eif.approval_date, "%D %M, %Y") as approval_date, u.fullname, r.name as role_name, DATE_FORMAT(u.date_start, "%D %M, %Y") as date_start, DATE_FORMAT(u.date_end, "%D %M, %Y") as date_end
                FROM employee_intership_finalizations eif
                LEFT JOIN users u on eif.user_id = u.id
                LEFT JOIN roles r on u.role_id = r.id
                WHERE eif.id = '.$id.'
                LIMIT 1
                ;
            ';
            $finalization = $this->db->raw($finalizationQuery)->fetch_object();
            // $this->dd($finalization);
            $this->generateCertificate($finalization->fullname, $finalization->role_name, $finalization->certificate, $finalization->approval_date, $finalization->date_start, $finalization->date_end);
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/certificate-print');
        }
        
    }
}
