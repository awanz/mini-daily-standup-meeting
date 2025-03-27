<?php
require_once 'BaseController.php';

class EmailController extends BaseController
{        
    public function credential($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $user = $this->db->getBy("users", "id", $id)->fetch_object();
        
        if (empty($user)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('user');
        }
        // $receiver, $fullname, $subject, $body

        $randomLetterss = substr($user->email, 0, 2);
        $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
        $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomLetter = $letters[mt_rand(0, strlen($letters) - 1)];
        $randomLetter2 = $letters[mt_rand(0, strlen($letters) - 1)];
        $newPasswordRaw = $randomLetter . $randomLetterss . $randomNumber . $randomLetter2;
        $newPasswordRaw = preg_replace_callback('/\./', function () use ($letters) {
            return $letters[rand(0, strlen($letters) - 1)];
        }, $newPasswordRaw);

        // print_r($newPasswordRaw);
        // die();

        $receiver   = $user->email;
        $fullname   = $user->fullname;
        $subject    = 'Credential Daily Kawan Kerja';
        $body       = '
        Hello, '.$fullname.'<br>
        Berikut credential yang bisa digunakan untuk mengakses <i><a href="https://kawankerja.id/daily">standup meeting</a></i>:
        <br><br>
        <hr>
        Email: <b>'.$receiver.'</b><br>
        Password: <b>'.$newPasswordRaw.'</b><br>
        <hr>
        <br><br>
        <b>Pastikan credential tidak diberikan kepada orang lain dan dijaga kerahasiaannya!</b><br><br>
        <p>
          Hormat kami,<br>
          <b> PT Kawan Kerja Indonesia </b>
        </p>
        ';
        $result = $this->sendEmail($receiver, $fullname, $subject, $body);
        if ($result['status']) {
            $dataUser = [
                "password" => md5(md5($newPasswordRaw)),
                "updated_by" => $this->user->id,
            ];
            $updateUser = $this->db->update("users", $dataUser, 'id', $id);
            $this->setMessage($result['message'], 'SUCCESS');
            $this->redirect('user');
        }
        $this->setMessage($result['message']);
        $this->redirect('user');
        
    }
    
    public function peringatan($data){
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $user = $this->db->getBy("view_user_daily", "id", $id)->fetch_object();
        
        if (empty($user)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('user');
        }

        if ($user->total_daily > MIN_DAILY) {
            $this->setMessage('Tidak dapat memberikan peringatan karena daily yang diisi '. $user->total_daily . '/' . MIN_DAILY);
            $this->redirect('user');
        }

        $date = new DateTime();
        $date->modify('last day of this month');
        $nextMonth = $date->format('Y-m-d');

        $receiver   = $user->email;
        $fullname   = $user->fullname;
        $subject    = 'Peringatan Ketidakaktifan (Magang) - ' . $fullname.' ('. $receiver .')';
        $body       = '
        <p>
            <strong>Kepada Yth.</strong><br>
            '.$fullname.'<br>
        </p>
        <p>
            Melalui surat elektronik ini, kami ingin menyampaikan peringatan terkait ketidakaktifan magang berdasarkan daily standup meeting anda hanya mengisi <b><u>'.$user->total_daily.'/15 hari</u></b> (akumulasi).
        </p>
        <p>
            Ketidakaktifan tanpa alasan yang sah dan pemberitahuan terlebih dahulu merupakan pelanggaran disiplin yang serius.
        </p>
        <p>
            Oleh karena itu, kami mohon Anda untuk mengisi daily standup meeting selambat-lambatnya <b>'.$nextMonth.' 18.00 WIB</b>.
        </p>
        <p>
            Jika Anda tidak mengisi daily standup meeting dalam waktu yang ditentukan, perusahaan berhak untuk mengambil tindakan disiplin selanjutnya, sesuai dengan peraturan yang berlaku.
        </p>
        <p>
            Kami harap Anda dapat memahami dan mematuhi peraturan perusahaan dengan baik.
        </p>
        <p>
            Hormat kami,<br>
            <b> PT Kawan Kerja Indonesia </b>
        </p>
        ';
        $result = $this->sendEmail($receiver, $fullname, $subject, $body);
        
        if ($result['status']) {
            try {
                $data = [
                  "user_id" => $user->id,
                  "email" => $receiver,
                  "counter" => 1,            
                ];
                $this->db->insert("warnings", $data);
            } catch (\Throwable $th) {
                $this->setMessage('Email <b>berhasil dikirim</b>, Peringatan <b>tidak berhasil disimpan</b>!', 'FAILED');
                $this->redirect('user');
            }

            $this->setMessage($result['message'], 'SUCCESS');
            $this->redirect('user');
        }
        $this->setMessage($result['message']);
        $this->redirect('user');
        
    }
    
    public function pemecatan($data){
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $user = $this->db->getBy("view_user_daily", "id", $id)->fetch_object();
        $warning = $this->db->getOneBy("warnings", "user_id", $id, 'created_at', 'DESC')->fetch_object();
        
        if (empty($user)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('user');
        }

        if ($user->total_daily > MIN_DAILY) {
            $this->setMessage('Tidak dapat memberikan pemecatan karena daily yang diisi '. $user->total_daily . '/' . MIN_DAILY);
            $this->redirect('user');
        }
        
        if (!$warning) {
            $this->setMessage('Tidak dapat memberikan pemecatan karena belum diberikan peringatan');
            $this->redirect('user');
        }

        $date = new DateTime();
        $date->modify('last day of this month');
        $nextMonth = $date->format('Y-m-d');

        $receiver   = $user->email;
        $fullname   = $user->fullname;
        $timestamp_milis = round(microtime(true) * 1000);
        $subject    = 'Peringatan Ketidakaktifan (Magang) - ' . $fullname.' ('. $receiver .') ['.$timestamp_milis.']';
        $body       = '
        <p>
            <strong>Kepada Yth.</strong><br>
            '.$fullname.'<br>
        </p>
        <p>
            Dengan hormat,<br><br>
            Melalui surat ini, kami sampaikan bahwa Anda akan menerima Surat Peringatan (SP) Kedua terkait dengan ketidakaktifan Anda dalam kegiatan magang di PT Kawan Kerja Indonesia yang sebelumnya Anda telah menerima SP Pertama.
        </p>
        <p>
            SP Kedua ini merupakan peringatan terakhir yang kami berikan. Oleh karena itu, dengan berat hati kami terpaksa mengambil tindakan tegas berupa pemutusan hubungan kerja (PHK) sebagai peserta magang di PT Kawan Kerja Indonesia
        </p>
        <p>
            Kami harap Anda dapat memahami keputusan ini.
        </p>
        <p>
            Hormat kami,<br>
            <b> PT Kawan Kerja Indonesia </b>
        </p>
        ';
        $result = $this->sendEmail($receiver, $fullname, $subject, $body);
        
        if ($result['status']) {
            try {
                $data = [
                  "user_id" => $user->id,
                  "email" => $receiver,
                  "counter" => 2,            
                ];
                $this->db->insert("warnings", $data);
                $dataUser = [
                    "is_active" => 0,
                    "updated_by" => $this->user->id,
                ];
                $update = $this->db->update("users", $dataUser, 'id', $id);
            } catch (\Throwable $th) {
                $this->setMessage('Email <b>berhasil dikirim</b>, Peringatan <b>tidak berhasil disimpan</b>!', 'FAILED');
                $this->redirect('user');
            }

            $this->setMessage($result['message'], 'SUCCESS');
            $this->redirect('user');
        }
        $this->setMessage($result['message']);
        $this->redirect('user');
        
    }
    
    public function warningCustom($user_id, $type, $reason){
        
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('warnings');
        }

        $user = $this->db->getBy("view_user_daily", "id", $user_id)->fetch_object();
        
        if (empty($user)) {
            $this->setMessage('Data user tidak ada!');
            $this->redirect('warnings');
        }
        
        $warning = $this->db->getOneBy("warnings", "user_id", $user_id, 'created_at', 'DESC')->fetch_object();
        if (!$warning && $type == 2) {
            $this->setMessage('Tidak dapat memberikan pemecatan karena belum diberikan peringatan');
            $this->redirect('warnings/add');
        }

        $date = new DateTime();
        $date->modify('last day of this month');
        $nextMonth = $date->format('Y-m-d');

        $receiver   = $user->email;
        $fullname   = $user->fullname;
        $timestamp_milis = round(microtime(true) * 1000);
        $subject    = 'Peringatan '.$type.' - '.$fullname.' ('. $receiver .') ['.$timestamp_milis.']';
        $closeBody = '<p>
            Sesuai dengan kebijakan perusahaan, kami mengingatkan Anda untuk segera melakukan perbaikan dalam menjalankan tugas dan tanggung jawab sesuai dengan aturan yang berlaku. Jika dalam waktu 7 hari tidak ada perubahan atau pelanggaran serupa kembali terjadi, maka perusahaan dapat mengambil tindakan lebih lanjut sesuai dengan ketentuan yang berlaku.
        </p>';
        if ($type == 2) {
            $closeBody = '<p>
            Email ini merupakan peringatan terakhir yang kami berikan. Oleh karena itu, dengan berat hati kami terpaksa mengambil tindakan tegas berupa pemutusan hubungan kerja (PHK) sebagai peserta magang di PT Kawan Kerja Indonesia
        </p>';
        }
        $body       = '
        <p>
            <strong>Kepada Yth.</strong><br>
            '.$fullname.'<br>
        </p>
        <p>
            Dengan ini, kami ingin menyampaikan Surat Peringatan '.$type.' kepada Anda berdasarkan evaluasi terhadap kinerja dan disiplin kerja di <b>PT Kawan Kerja Indonesia</b>.
        </p>
        <p>
            Adapun alasan dikeluarkannya surat peringatan ini adalah sebagai berikut:
        </p>
        <p>
            <b>'.$reason.'.</b>
        </p>
        '.$closeBody.'
        <p>
            Demikian surat ini disampaikan untuk dapat diperhatikan dan dipatuhi.
        </p>
        <p>
            Hormat kami,<br>
            <b> PT Kawan Kerja Indonesia </b>
        </p>
        ';
        $result = $this->sendEmail($receiver, $fullname, $subject, $body);
        
        if ($result['status']) {
            try {
                $data = [
                  "user_id" => $user->id,
                  "email" => $receiver,
                  "counter" => $type,            
                ];
                $this->db->insert("warnings", $data);
                if ($type == 2) {
                    $dataUser = [
                        "is_active" => 0,
                        "updated_by" => $this->user->id,
                    ];
                    $update = $this->db->update("users", $dataUser, 'id', $user->id);
                }
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
            return $result['message'];
        }
        return $result['message'];
    }
}
