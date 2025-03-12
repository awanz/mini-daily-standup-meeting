<?php
require_once 'BaseController.php';

class AbsenceController extends BaseController
{
    public function intern()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        
        $query = '
            SELECT 
                ROW_NUMBER() OVER (ORDER BY fullname) AS no,
                u.id,
                r.id,
                fullname, 
                r.name,
                date_start,
                date_end,
                email,
                phone
            FROM 
                users u
            JOIN
                roles r on r.id = u.role_id
            WHERE 
                access = "USER" AND 
                is_active = 1 AND 
                (date_end IS NULL OR date_end >= CURDATE())
            ;
        ';
        $users = $this->db->raw($query)->fetch_all();
        // $this->dd($users);
        
        $alert = $this->getMessage();
        $this->render('absence/intern', [
            'alert' => $alert,
            'users' => $users,
        ]);
    }
    
    public function volunteer()
    {
        $isAdmin = $this->isAdmin();
        $isHR = $this->isHR();

        if (!$isAdmin && !$isHR) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }
        
        $query = '
            SELECT 
                ROW_NUMBER() OVER (ORDER BY fullname) AS no,
                fullname, 
                r.name,
                date_start,
                email,
                phone
            FROM 
                users u
            JOIN
                roles r on r.id = u.role_id
            WHERE 
                access = "VOLUNTEER" AND 
                is_active = 1 AND 
                (date_end IS NULL OR date_end >= CURDATE())
            ;
        ';
        $users = $this->db->raw($query)->fetch_all();
        // $this->dd($users);
        
        $alert = $this->getMessage();
        $this->render('absence/volunteer', [
            'alert' => $alert,
            'users' => $users,
        ]);
    }
}
