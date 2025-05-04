<?php
require_once 'BaseController.php';

class HRCandidateRequestController extends BaseController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $candidateRequestsQuery = '
            SELECT 
                cr.*, r.name as role_name, u.fullname as name_pic, u2.fullname as name_create, r.interview_question, r.job_qualification, u3.fullname as name_update,
                (SELECT COUNT(*) FROM candidates c WHERE c.candidate_request_id = cr.id) AS total_selection,
                (SELECT COUNT(*) FROM candidates c WHERE c.candidate_request_id = cr.id and c.status = "HIRED") AS total_hired
            FROM 
                candidate_requests cr
            LEFT JOIN roles r on r.id = cr.role_id
            LEFT JOIN users u on u.id = cr.pic_id
            LEFT JOIN users u2 on u2.id = cr.created_by
            LEFT JOIN users u3 on u3.id = cr.updated_by
            ORDER BY 
            CASE cr.status
                WHEN "REQUEST" THEN 1
                WHEN "OPEN" THEN 2
                WHEN "DONE" THEN 3
                WHEN "CANCEL" THEN 4
                ELSE 5
            END,
            cr.created_at DESC
            LIMIT 500
            ;
        ';
        $candidateRequests = $this->db->raw($candidateRequestsQuery);

        $alert = $this->getMessage();
        $this->render('human-resource/candidate-requests/index', [
            'alert' => $alert,
            'candidateRequests' => $candidateRequests,
        ]);
    }
    
    public function add()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $roles = $this->db->getAllClean("roles", true, "name asc")->fetch_all();

        $alert = $this->getMessage();
        $this->render('human-resource/candidate-requests/add', [
            'alert' => $alert,
            'roles' => $roles,
        ]);
    }

    public function addProcess()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            try {
                $data = [
                    "role_id" => $this->db->escape(trim($_POST['role_id'])),
                    "total" => $this->db->escape(trim($_POST['total'])),
                    "description" => $this->db->escape(trim($_POST['description'])),
                    "note" => $this->db->escape(trim($_POST['note'])),
                    "contract_date" => $this->db->escape(trim($_POST['contract_date'])),
                    "created_by" => $this->user->id,
                ];
                
                $insertData = $this->db->insert("candidate_requests", $data);
                $this->setMessage('Meminta kandidat berhasil!', 'SUCCESS');
                $this->redirect('hr/candidate-requests/add');
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('hr/candidate-requests/add');
            }
        }
        
    }

    public function detail($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $roles = $this->db->getAllClean("roles", true, "name asc")->fetch_all();

        $candidateRequestQuery = '
            SELECT 
                cr.*, r.name as role_name, u.fullname as pic_name, u2.fullname as updated_name
            FROM 
                candidate_requests cr
            LEFT JOIN roles r on r.id = cr.role_id
            LEFT JOIN users u on u.id = cr.pic_id
            LEFT JOIN users u2 on u2.id = cr.updated_by
            WHERE cr.id='.$this->db->escape(trim($data['id'])).'
            ORDER BY cr.created_at DESC
            LIMIT 1
            ;
        ';
        $candidateRequest = $this->db->raw($candidateRequestQuery)->fetch_object();

        $alert = $this->getMessage();
        $this->render('human-resource/candidate-requests/detail', [
            'alert' => $alert,
            'roles' => $roles,
            'candidateRequest' => $candidateRequest,
        ]);
    }
    
    public function edit($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $roles = $this->db->getAllClean("roles", true, "name asc")->fetch_all();

        $candidateRequestQuery = '
            SELECT 
                *
            FROM 
                candidate_requests cr
            WHERE id='.$this->db->escape(trim($data['id'])).'
            ORDER BY cr.created_at DESC
            LIMIT 1
            ;
        ';

        $candidateRequest = $this->db->raw($candidateRequestQuery)->fetch_object();
        // $this->dd($candidateRequest);
        if (empty($candidateRequest)) {
            $this->setMessage('Permintaan kandidat tidak ditemukan');
            $this->redirect('hr/candidate-requests');
        }

        $listPIC = $this->db->raw('
            SELECT 
                *
            FROM 
                users u
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE (u.access = "ADMIN" or r.name = "HR Recruiter" or r.name = "HR Generalist")
            AND u.is_active = 1
            ORDER BY u.fullname
            ;
        ')->fetch_all();

        $alert = $this->getMessage();
        $this->render('human-resource/candidate-requests/edit', [
            'alert' => $alert,
            'roles' => $roles,
            'candidateRequest' => $candidateRequest,
            'listPIC' => $listPIC,
        ]);
    }

    public function editProcess($data)
    {
        
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();
        // $this->dd($isAdmin);

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses!');
            $this->redirect('home');
        }

        $id = $this->db->escape($data['id']);

        if ($_SERVER['REQUEST_METHOD'] === "POST") {            
            try {
                $data = [
                    "role_id" => $this->db->escape(trim($_POST['role_id'])),
                    "total" => $this->db->escape(trim($_POST['total'])),
                    "description" => $this->db->escape(trim($_POST['description'])),
                    "note" => $this->db->escape(trim($_POST['note'])),
                    "pic_id" => $this->db->escape(trim($_POST['pic_id'])),
                    "contract_date" => $this->db->escape(trim($_POST['contract_date'])),
                    "status" => $this->db->escape(trim($_POST['status'])),
                    "updated_by" => $this->user->id,
                ];
                
                $update = $this->db->update("candidate_requests", $data, 'id', $id);
                $this->setMessage('Simpan permintaan kandidat berhasil!', 'SUCCESS');
                $this->redirect('hr/candidate-requests/edit/'.$id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('hr/candidate-requests/edit/'.$id);
            }
        }
        
    }

    // Candidate
    public function candidate($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        $id = htmlspecialchars(strip_tags($data['id']));
        $candidateRequest = $this->db->raw('
            SELECT 
                role_id, r.name as role_name, cr.id
            FROM 
                candidate_requests cr
            LEFT JOIN roles r on r.id = cr.role_id
            WHERE cr.id='.$id.'
            ORDER BY cr.created_at DESC
            LIMIT 1
            ;
        ')->fetch_object();
        if (!isset($candidateRequest)) {
            throw new Exception("Kandidat request tidak ditemukan", 1);
        }
        // $this->dd($candidateRequest);

        $candidateQuery = '
            SELECT 
                *
            FROM 
                candidates
            WHERE candidate_request_id = '.$id.'
            ;
        ';
        $candidates = $this->db->raw($candidateQuery);

        $alert = $this->getMessage();
        $this->render('human-resource/candidate-requests/candidate/index', [
            'alert' => $alert,
            'candidates' => $candidates,
            'candidateRequest' => $candidateRequest,
        ]);
    }

    public function candidateAdd($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();
        
        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        $id = htmlspecialchars(strip_tags($data['id']));
        if (!isset($id)) {
            throw new Exception("Id kandidat request tidak boleh kosong!", 1);
        }
        // $this->dd($id);
        $candidateRequest = $this->db->raw('
            SELECT 
                role_id, contract_date, note
            FROM 
                candidate_requests cr
            WHERE id='.$id.'
            ORDER BY cr.created_at DESC
            LIMIT 1
            ;
        ')->fetch_object();
        // $this->dd($candidateRequest);
        if (!isset($candidateRequest)) {
            throw new Exception("Kandidat request tidak ditemukan", 1);
        }
        // $this->dd($candidateRequest);

        $roles = $this->db->getAllClean("roles", true, "name asc")->fetch_all();

        $alert = $this->getMessage();
        $this->render('human-resource/candidate-requests/candidate/add', [
            'alert' => $alert,
            'roles' => $roles,
            'id' => $id,
            'candidateRequest' => $candidateRequest
        ]);
    }

    public function candidateAddProcess($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        $id = htmlspecialchars(strip_tags($data['id']));
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            try {
                // $this->dd($_POST);
                $candidateRequest = $this->db->raw('
                    SELECT 
                        id, role_id, contract_date, note
                    FROM 
                        candidate_requests cr
                    WHERE id='.$id.'
                    ORDER BY cr.created_at DESC
                    LIMIT 1
                    ;
                ')->fetch_object();
                if (!isset($candidateRequest)) {
                    throw new Exception("Kandidat request tidak ditemukan", 1);
                }
                $data = [
                    "candidate_request_id" => $candidateRequest->id,
                    "nik" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['nik'])))),
                    "fullname" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['fullname'])))),
                    "email" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['email'])))),
                    "phone" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['phone'])))),
                    "description" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['description'])))),
                    "status" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['status'])))),
                    "created_by" => $this->user->id,
                ];
                // $this->dd($data);
                
                $insertData = $this->db->insert("candidates", $data);
                $this->setMessage('Menambahkan kandidat berhasil!', 'SUCCESS');
                $this->redirect('hr/candidate-requests/candidate/add/'.$id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('hr/candidate-requests/candidate/add/'.$id);
            }
        }
        
    }

    public function candidateDeleteProcess($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        // $this->dd($data);
        $id = htmlspecialchars(strip_tags($data['id']));
        $candidate_id = htmlspecialchars(strip_tags($data['candidate_id']));
        $candidate = $this->db->getBy("candidates", "id", $candidate_id)->fetch_object();
        
        if (empty($candidate)) {
            $this->setMessage('Data yang ingin di delete tidak ada!');
            $this->redirect('hr/candidate-requests/candidate/'.$id);
        }

        try {
            $update = $this->db->delete("candidates",'id', $candidate->id);
            $this->setMessage('Data berhasil di delete', 'SUCCESS');
            $this->redirect('hr/candidate-requests/candidate/'.$id);
        } catch (\Throwable $th) {
            $this->setMessage($th->getMessage());
            $this->redirect('hr/candidate-requests/candidate/'.$id);
        }
    }

    public function candidateEdit($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();
        
        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        $id = htmlspecialchars(strip_tags($data['id']));
        $candidate_id = htmlspecialchars(strip_tags($data['candidate_id']));
        if (!isset($id)) {
            throw new Exception("Id kandidat request tidak boleh kosong!", 1);
        }
        // $this->dd($id);
        $candidateRequest = $this->db->raw('
            SELECT 
                role_id, contract_date, note
            FROM 
                candidate_requests cr
            WHERE id='.$id.'
            ORDER BY cr.created_at DESC
            LIMIT 1
            ;
        ')->fetch_object();
        if (!isset($candidateRequest)) {
            throw new Exception("Kandidat request tidak ditemukan", 1);
        }
        $candidate = $this->db->raw('
            SELECT 
                *
            FROM 
                candidates c
            WHERE id='.$candidate_id.'
            ORDER BY c.created_at DESC
            LIMIT 1
            ;
        ')->fetch_object();
        if (!isset($candidate)) {
            throw new Exception("Kandidat tidak ditemukan", 1);
        }

        $roles = $this->db->getAllClean("roles", true, "name asc")->fetch_all();

        $alert = $this->getMessage();
        $this->render('human-resource/candidate-requests/candidate/edit', [
            'alert' => $alert,
            'roles' => $roles,
            'id' => $id,
            'candidateRequest' => $candidateRequest,
            'candidate' => $candidate,
        ]);
    }

    public function candidateEditProcess($data)
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        $id = htmlspecialchars(strip_tags($data['id']));
        $candidate_id = htmlspecialchars(strip_tags($data['candidate_id']));
        
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            try {
                // $this->dd($_POST);
                $candidateRequest = $this->db->raw('
                    SELECT 
                        id, role_id, contract_date, note
                    FROM 
                        candidate_requests cr
                    WHERE id='.$id.'
                    ORDER BY cr.created_at DESC
                    LIMIT 1
                    ;
                ')->fetch_object();
                if (!isset($candidateRequest)) {
                    throw new Exception("Kandidat request tidak ditemukan", 1);
                }
                $candidate = $this->db->raw('
                    SELECT 
                        *
                    FROM 
                        candidates c
                    WHERE id='.$candidate_id.'
                    ORDER BY c.created_at DESC
                    LIMIT 1
                    ;
                ')->fetch_object();
                if (!isset($candidate)) {
                    throw new Exception("Kandidat tidak ditemukan", 1);
                }
                $data = [
                    "candidate_request_id" => $candidateRequest->id,
                    "nik" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['nik'])))),
                    "fullname" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['fullname'])))),
                    "email" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['email'])))),
                    "phone" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['phone'])))),
                    "description" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['description'])))),
                    "status" => $this->db->escape(trim(htmlspecialchars(strip_tags($_POST['status'])))),
                    "updated_by" => $this->user->id,
                ];
                // $this->dd($data);
                
                $insertData = $this->db->update("candidates", $data, 'id', $candidate_id);
                $this->setMessage('Update kandidat berhasil!', 'SUCCESS');
                $this->redirect('hr/candidate-requests/candidate/edit/'.$id.'/'.$candidate_id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('hr/candidate-requests/candidate/edit/'.$id.'/'.$candidate_id);
            }
        }
        
    }
}
