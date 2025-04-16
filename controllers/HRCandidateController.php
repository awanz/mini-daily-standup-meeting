<?php
require_once 'BaseController.php';

class HRCandidateController extends BaseController
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
                cr.id, r.name as role_name, cr.total, cr.status, u.fullname, u2.fullname
            FROM 
                candidate_requests cr
            LEFT JOIN roles r on r.id = cr.role_id
            LEFT JOIN users u on u.id = cr.pic_id
            LEFT JOIN users u2 on u2.id = cr.created_by
            ORDER BY cr.created_at DESC
            LIMIT 500
            ;
        ';
        $candidateRequests = $this->db->raw($candidateRequestsQuery)->fetch_all();

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
                    "contract_date" => $this->db->escape(trim($_POST['contract_date'])),
                    "description" => $this->db->escape(trim($_POST['description'])),
                    "note" => $this->db->escape(trim($_POST['note'])),
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
                cr.*, r.name as role_name, u.fullname
            FROM 
                candidate_requests cr
            LEFT JOIN roles r on r.id = cr.role_id
            LEFT JOIN users u on u.id = cr.pic_id
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
        // $this->dd($candidateRequestQuery);
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
                $this->dd($data);
                
                $update = $this->db->update("candidate_requests", $data, 'id', $id);
                $this->setMessage('Simpan permintaan kandidat berhasil!', 'SUCCESS');
                $this->redirect('hr/candidate-requests/edit/'.$id);
            } catch (\Throwable $th) {
                $this->setMessage($th->getMessage());
                $this->redirect('hr/candidate-requests/edit/'.$id);
            }
        }
        
    }

}
