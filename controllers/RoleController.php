<?php
require_once 'BaseController.php';

class RoleController extends BaseController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        // echo "sadsa";die();
        $query = '
            SELECT 
                r.id, 
                r.name,
                r.description,
                r.url_group_wa,
                (
                    SELECT 
                        COUNT(u.id) 
                    FROM 
                        users u
                    WHERE 
                        u.role_id = r.id 
                        AND u.is_active = 1
                ) AS total_users,
                ROW_NUMBER() OVER (ORDER BY `r`.`name`) AS `no`
            FROM 
                roles r
            WHERE 
                r.deleted_at IS NULL
            ORDER BY r.name asc;
        ';
        // $roles = $this->db->getAllClean("roles")->fetch_all();
        // $this->dd($query);
        $roles = $this->db->raw($query)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('role/index', [
            'alert' => $alert,
            'roles' => $roles
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
        $this->render('role/add', [
            'alert' => $alert
        ]);
        
    }

    public function create()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $url_group_wa = trim($_POST['url_group_wa']);
            
            try {
                $data = [
                    "name" => $name,
                    "description" => $description,
                    "url_group_wa" => $url_group_wa,
                ];
                
                $insertData = $this->db->insert("roles", $data);
                $this->setMessage('Simpan role berhasil!', 'SUCCESS');
                $this->redirect('role/add');
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('role/add');
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
        $role = $this->db->getBy("roles", "id", $id)->fetch_object();
        
        if (empty($role)) {
            $this->setMessage('Data tidak ada!');
            $this->redirect('role');
        }
        
        $alert = $this->getMessage();
        $this->render('role/edit', [
            'alert' => $alert,
            'role' => $role,
        ]);
        
    }
    
    public function update($data)
    {
        
        $isAdmin = $this->isAdmin();
        // $this->dd($isAdmin);

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $url_group_wa = trim($_POST['url_group_wa']);
            
            try {
                $data = [
                    "name" => $name,
                    "description" => $description,
                    "url_group_wa" => $url_group_wa,
                ];
                
                $update = $this->db->update("roles", $data, 'id', $id);
                $this->setMessage('Simpan user berhasil!', 'SUCCESS');
                $this->redirect('role/edit/'.$id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('role/edit/'.$id);
            }
        }
        
    }
    
    public function delete($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('user');
        }

        $id = $this->db->escape($data['id']);
        $role = $this->db->getBy("roles", "id", $id)->fetch_object();
        
        if (empty($role)) {
            $this->setMessage('Data yang ingin di delete tidak ada!');
            $this->redirect('role');
        }

        try {
            $data = [
                "deleted_at" => date('Y-m-d H:i:s'),
                "updated_by" => $this->user->id,
            ];
            $update = $this->db->update("roles", $data, 'id', $id);
            $this->setMessage('Data berhasil di delete', 'SUCCESS');
            $this->redirect('role');
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('role');
        }
    }

    public function detail($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);
        $role = $this->db->getBy("roles", "id", $id)->fetch_object();
        
        if (empty($role)) {
            $this->setMessage('Role yang dipilih tidak ada');
            $this->redirect('role');
        }

        $query = "
        SELECT 
            vud.id,
            vud.fullname,
            vud.email,
            vud.phone,
            vud.date_start,
            vud.date_end,
            vud.total_daily,
            vud.role,
            vud.is_finish,
            vud.last_login_at,
            GROUP_CONCAT(p.name SEPARATOR ', ') AS join_project
        FROM 
            view_user_daily vud
        LEFT JOIN 
            project_users pu ON vud.id = pu.user_id
        LEFT JOIN 
            projects p ON pu.project_id = p.id
        WHERE 
            vud.role = '" .$role->name. "'
        GROUP BY 
            vud.id,
            vud.fullname,
            vud.email,
            vud.phone,
            vud.date_start,
            vud.date_end,
            vud.total_daily,
            vud.role,
            vud.is_finish,
            vud.last_login_at
        ORDER BY 
            vud.fullname ASC
        LIMIT 200;
        ";
        // $this->dd($query);
        $users = $this->db->raw($query)->fetch_all();
        if (empty($users)) {
            $this->setMessage('Role belum memiliki anggota, silahkan tambah terlebih dahulu.');
            $this->redirect('role');
        }

        $queryMeetings = '
            SELECT 
                m.id, m.title, m.description, m.date, m.time_start, m.time_end
            FROM meetings m 
            WHERE m.role_id = '.$id.'
            ORDER BY m.time_start DESC
            LIMIT 50;
        ';

        $meetings = $this->db->raw($queryMeetings)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('role/detail', [
            'alert' => $alert,
            'users' => $users,
            'role' => $role,
            'meetings' => $meetings,
        ]);
        
    }

    public function attendance($data)
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $role_id = $this->db->escape($data['role_id']);
        $role = $this->db->getBy("roles", "id", $role_id)->fetch_object();

        $queryRoleUser = '
            SELECT 
                u.id, u.fullname, r.name
            FROM 
                roles r
            LEFT JOIN users u
            ON r.id = u.role_id
            WHERE 
                r.id = '.$role_id.'
            ORDER BY u.fullname asc;
        ';
        $roleUsers = $this->db->raw($queryRoleUser)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('role/attendance', [
            'alert' => $alert,
            'role_id' => $role_id,
            'role' => $role,
            'roleUsers' => $roleUsers,
        ]);
        
    }

    public function attendanceDetail($data)
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $meeting_id = $this->db->escape($data['meeting_id']);
        $meeting = $this->db->getBy("meetings", "id", $meeting_id)->fetch_object();
        if (empty($meeting)) {
            throw new Exception("Meeting $meeting_id tidak ditemukan", 1);
        }
        $role = $this->db->getBy("roles", "id", $meeting->role_id)->fetch_object();

        $queryMeetingAttendance = '
            SELECT 
                u.fullname, r.name, ma.status, ma.note
            FROM 
                meeting_attendances ma
            LEFT JOIN meetings m ON ma.meeting_id = m.id
            LEFT JOIN users u ON ma.user_id = u.id
            LEFT JOIN roles r ON r.id = u.role_id
            WHERE ma.meeting_id = '.$meeting->id.'
            ORDER BY u.fullname asc;
        ';
        $meetingAttendance = $this->db->raw($queryMeetingAttendance)->fetch_all();
        
        $alert = $this->getMessage();
        $this->render('role/attendance-detail', [
            'alert' => $alert,
            'meeting' => $meeting,
            'meetingAttendance' => $meetingAttendance,
            'role' => $role,
        ]);
        
    }
    
    public function createAttendance($data)
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $role_id = $this->db->escape($data['role_id']);
        $role = $this->db->getBy("roles", "id", $role_id)->fetch_object();

        $user_ids = $_POST['user_ids'];
        $attendances = $_POST['attendances'];
        $notes = $_POST['notes'];
        $timeStart = $_POST['time_start'];
        $date = $date = date("Y-m-d", strtotime($timeStart));
        $duration = $_POST['duration'];
        $description = $_POST['description'];

        if (empty($timeStart)) {
            $this->setMessage('Tanggal mulai belum dipilih!');
            $this->redirect('role/meeting-attendance/'.$role_id);
        }

        $combined = array_map(function ($user_id, $attendance, $note) {
            return [
                "user_id" => $user_id,
                "status" => $attendance,
                "note" => $note
            ];
        }, $user_ids, $attendances, $notes);        

        $this->db->beginTransaction();
        try {
            $dataMeeting = [
                "title" => 'Meeting Role ' . $role->name,
                "role_id" => $role_id,
                "type" => 'MEETING_Role',
                "created_by" => $this->user->id,
                "date" => $date,
                "time_start" => $timeStart,
                "description" => $description,
            ];
            if ($duration > 0) {
                $timeStartDate = new DateTime($timeStart);
                $timeStartDate->modify('+'.$duration.' minutes');
                $dataMeeting['time_end'] = $timeStartDate->format('H:i');
            }
            $insertMeeting = $this->db->insert("meetings", $dataMeeting);
            // $this->dd($insertMeeting);
            $insertParticipant = null;
            foreach ($combined as $elm) {
                if ($elm['status'] == 'NONE') {
                    continue;
                }
                $elm['meeting_id'] = $insertMeeting['last_id'];
                $elm['created_by'] = $this->user->id;
                $insertParticipant = $this->db->insert("meeting_attendances", $elm);
            }
            if (empty($insertParticipant)) {
                throw new Exception("Tidak ada peserta yang ikut meeting", 1);
            }
            $this->db->commit();
            $this->setMessage("Data kehadiran <b>".$dataMeeting['title']."</b> berhasil disimpan", 'SUCCESS');
            $this->redirect('role/detail/'.$role_id);
        } catch (\Throwable $th) {
            $this->db->rollback();
            $this->setMessage($th->getMessage());
            $this->redirect('role/meeting-attendance/'.$role_id);
        }
    }

    public function deleteAttendance($data)
    {
        
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $meeting_id = $this->db->escape($data['meeting_id']);
        $meeting = $this->db->getBy("meetings", "id", $meeting_id)->fetch_object();

        try {
            if (empty($meeting)) {
                throw new Exception("Meeting tidak ditemukan", 1);
            }
    
            if ((time() - strtotime($meeting->created_at)) >= 3 * 24 * 60 * 60){
                throw new Exception("Absensi sudah tidak dapat dihapus, hubungi admin", 1);
            }
            $this->db->beginTransaction();
            $this->db->delete("meetings", 'id', $meeting_id);
            $this->db->delete("meeting_attendances", 'meeting_id', $meeting_id);
            
            $this->db->commit();
            $this->setMessage("Data absensi <b>$meeting->title</b> berhasil di delete", 'SUCCESS');
            $this->redirect('role/detail/'.$meeting->role_id);
        } catch (\Throwable $th) {
            $this->db->rollback();
            $this->setMessage($th->getMessage());
            $this->redirect('role/meeting-attendance-detail/'.$meeting->id);
        }
    }
}
