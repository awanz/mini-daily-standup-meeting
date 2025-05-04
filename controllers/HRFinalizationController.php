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
                eif.id, u.fullname, r.name as role_name, eif.status, eif.file, eif.is_survey, u2.fullname, u2.id, u.date_start, u.date_end, eif.created_at, eif.certificate
            FROM 
                employee_intership_finalizations eif
            LEFT JOIN users u on u.id = eif.user_id
            LEFT JOIN users u2 on u2.id = eif.approval_id
            LEFT JOIN roles r on r.id = u.role_id
            WHERE r.name is not null
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
                    eif.id,
                    eif.user_id,
                    r.role_code
                FROM 
                    employee_intership_finalizations eif
                LEFT JOIN users u on u.id = eif.user_id
                LEFT JOIN roles r on r.id = u.role_id
                WHERE eif.status = "REQUEST"
                and eif.id = '.$this->db->escape($_POST['id']).'
                ;
            ';
            $finalization = $this->db->raw($finalizationQuery)->fetch_object();

            if (empty($finalization->role_code)) {
                throw new Exception("Role Code belum di Atur", 1);
            }
            
            if (empty($finalization)) {
                throw new Exception("Pengajuan penyelesaian magang tidak ditemukan, tidak ada yang direvise", 1);
            }

            // generate certificate number
            $certificateYear = date('Y');
            $certificateMonth = $this->numberToRoman(date('n'));
            $certificateNumber = 1;
            $getCertificatenumber = $this->db->raw('
                SELECT 
                    *
                FROM 
                    employee_intership_finalizations
                WHERE certificate_year = '.$certificateYear.'
                ORDER BY certificate_number desc
                LIMIT 1
                ;
            ')->fetch_object();

            if (!empty($getCertificatenumber)) {
                $certificateNumber = $getCertificatenumber->certificate_number + 1;
            }
            $certificate = str_pad($certificateNumber, 4, '0', STR_PAD_LEFT).'/KKI/'.$finalization->role_code.'-ITS/'.$certificateMonth.'/'.$certificateYear;


            // update Finalization
            $data = [
                "status" => 'APPROVED',
                "updated_by" => $this->db->escape($this->user->id),
                "approval_id" => $this->db->escape($this->user->id),
                "approval_date" => date('Y-m-d'),
                "certificate" => $certificate,
                "certificate_number" => $certificateNumber,
                "certificate_year" => $certificateYear
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
            $body = '<p>Dengan ini kami menginformasikan bahwa pengajuan <b>penyelesaian magang</b> dari Saudara/i <b>'.$user->fullname.'</b> telah diterima. Kami mengucapkan terima kasih yang sebesar-besarnya atas kontribusi, dedikasi, dan kerja samanya selama ini.</p><br>

                <p>Kami mendoakan yang terbaik untuk langkah karier selanjutnya. Semoga sukses dan bahagia selalu menyertai di setiap perjalanan baru yang akan ditempuh.</p><br>
                <p>Selamat jalan dan sampai jumpa, semoga kita bisa bertemu lagi di kesempatan yang lebih baik.</p>

                <a href="https://kawankerja.id/daily/certificate/'.base64_encode($certificate).'" target="_BLANK">Download Sertifikat</a>

                <i>Jika terdapat kesalah pada sertifikat, segera laporkan.</i>

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
                    eif.id, eif.status, eif.approval_date, u.fullname, r.name as role_name, u.date_start, u.date_end, eif.certificate
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
    
    public function certificateSend()
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
                    eif.id, eif.user_id, eif.status, eif.certificate, DATE_FORMAT(eif.approval_date, "%D %M, %Y") as approval_date, u.fullname, r.name as role_name, DATE_FORMAT(u.date_start, "%D %M, %Y") as date_start, DATE_FORMAT(u.date_end, "%D %M, %Y") as date_end
                FROM employee_intership_finalizations eif
                LEFT JOIN users u on eif.user_id = u.id
                LEFT JOIN roles r on u.role_id = r.id
                WHERE eif.id = '.$id.'
                LIMIT 1
                ;
            ';
            $finalization = $this->db->raw($finalizationQuery)->fetch_object();
            if (empty($finalization)) {
                throw new Exception("Pengajuan sertifikat tidak ditemukan", 1);
            }
            // $this->dd($finalization);
            $user = $this->db->getBy("users", "id", $finalization->user_id)->fetch_object();
            if (empty($user)) {
                throw new Exception("User tidak ditemukan", 1);
            }
            // $this->dd($user);

            $subject = "Sertifikat Magang";
            $body = '
                <p>Dear Peserta Magang.</p>
                <p>Terima kasih telah menyelesaikan program magang di <b>PT Kawan Kerja Indonesia</b>.</p>

                <p>Dengan ini kami lampirkan sertifikat magang atas partisipasi Anda.</p>
                
                <p>Kami menghargai dedikasi dan kontribusi Anda selama mengikuti program ini. Semoga pengalaman ini menjadi bekal berharga dalam perjalanan karier Anda ke depan.</p>
                <p>Silakan unduh sertifikat Anda pada link dibawah ini. Jika terdapat pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.</p>
                
                [<a href="https://kawankerja.id/daily/certificate/'.base64_encode($finalization->certificate).'" target="_BLANK">Download Sertifikat</a>]<br>
                <small><i>*Jika terdapat kesalahan penulisan pada sertifikat, segera laporkan.</i></small>

                <p><b>Salam hangat, <br>
                PT KAWAN KERJA INDONESIA<b>
                <p>';

            // kirim email
            $sendEmail = $this->sendEmailCustom($user->email, $user->fullname, $subject, $body);

            $this->setMessage($sendEmail['message'], 'SUCCESS');
            $this->redirect('hr/certificate-print');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/certificate-print');
        }
        
    }
}
