<?php
require_once 'BaseController.php';

class CertificateController extends BaseController
{

    public function checkLogged(){}

    public function index($data)
    {
        header('X-Robots-Tag: noindex, nofollow', true);
        $id = base64_decode(htmlspecialchars(strip_tags($data['id']), ENT_QUOTES, 'UTF-8'));
        $finalizationQuery = "
            SELECT 
                eif.user_id, eif.id, eif.status, eif.certificate, DATE_FORMAT(eif.approval_date, '%D %M, %Y') as approval_date, u.fullname, r.name as role_name, DATE_FORMAT(u.date_start, '%D %M, %Y') as date_start, DATE_FORMAT(u.date_end, '%D %M, %Y') as date_end
            FROM employee_intership_finalizations eif
            LEFT JOIN users u on eif.user_id = u.id
            LEFT JOIN roles r on u.role_id = r.id
            WHERE eif.certificate = '".$id."'
            and u.fullname is not null
            LIMIT 1
            ;
        ";
        $finalization = $this->db->raw($finalizationQuery)->fetch_object();
        // $this->dd($finalization);
        if (!isset($finalization)) {
            die("Sertifikat tidak di temukan, url yang dimasukan salah.");
        }
        
        $absensiMeeting = $this->db->raw('
                SELECT 
                    u.id,
                    MAX(u.fullname) AS fullname,
                    MAX(r.name) AS role_name,
                    GROUP_CONCAT(DISTINCT p.name SEPARATOR ", ") AS projects,
                    COUNT(DISTINCT CASE WHEN ma.status = "PRESENT" AND ma.status IS NOT NULL THEN ma.id END) AS total_meeting_present,
                    COUNT(DISTINCT CASE WHEN ma.status = "ABSENT" AND ma.status IS NOT NULL THEN ma.id END) AS total_meeting_absent,
                    COUNT(DISTINCT CASE WHEN ma.status = "PERMISSION" AND ma.status IS NOT NULL THEN ma.id END) AS total_meeting_permission,
                    COUNT(DISTINCT CASE WHEN ma.status = "SICK" AND ma.status IS NOT NULL THEN ma.id END) AS total_meeting_sick
                FROM users u
                JOIN roles r ON u.role_id = r.id
                LEFT JOIN project_users pu ON u.id = pu.user_id
                LEFT JOIN projects p ON pu.project_id = p.id
                LEFT JOIN meeting_attendances ma 
                    ON u.id = ma.user_id
                LEFT JOIN meetings m 
                    ON ma.meeting_id = m.id
                WHERE u.id = '.$finalization->user_id.'
                GROUP BY u.id
                ORDER BY fullname;
        ')->fetch_object();
        
        $totaldaily = $this->db->raw('
                SELECT 
                    count(*) as total_daily
                FROM dailys
                WHERE user_id = '.$finalization->user_id.';
        ')->fetch_object();
        $alert = $this->getMessage();
        $this->render('certificate/index',[
            'id' => $id,
            'finalization' => $finalization,
            "absensiMeeting" => $absensiMeeting,
            "totaldaily" => $totaldaily
        ]);
    }
    
    public function pdf($data)
    {
        header('X-Robots-Tag: noindex, nofollow', true);
        try {
            $id = htmlspecialchars(strip_tags($data['id']), ENT_QUOTES, 'UTF-8');
            // $this->dd($id);
            if (isset($id)) {
                $id = base64_decode($id);
            }else{
                throw new Exception("Data is NULL", 1);
            }
            $finalizationQuery = "
                SELECT 
                    eif.id, eif.status, eif.certificate, DATE_FORMAT(eif.approval_date, '%D %M, %Y') as approval_date, u.fullname, r.name as role_name, DATE_FORMAT(u.date_start, '%D %M, %Y') as date_start, DATE_FORMAT(u.date_end, '%D %M, %Y') as date_end
                FROM employee_intership_finalizations eif
                LEFT JOIN users u on eif.user_id = u.id
                LEFT JOIN roles r on u.role_id = r.id
                WHERE eif.certificate = '".$id."'
                LIMIT 1
                ;
            ";
            // $this->dd($finalizationQuery);
            $finalization = $this->db->raw($finalizationQuery)->fetch_object();
            // $this->dd($finalization);
            if (isset($finalization)) {
                $this->generateCertificate($finalization->fullname, $finalization->role_name, $finalization->certificate, $finalization->approval_date, $finalization->date_start, $finalization->date_end);
            }else{
                echo "Sertifikat tidak ada";
            }
        } catch (\Throwable $th) {
            echo "Gagal generate Sertifikat";
            echo "<pre>";
            print_r($th);
        }
    }
}
