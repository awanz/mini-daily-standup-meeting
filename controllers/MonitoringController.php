<?php
require_once 'BaseController.php';

class MonitoringController extends BaseController
{
    public function index()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $picRoleQuery = '
            SELECT 
                admin.id AS admin_id,
                admin.fullname AS admin_name,
                COUNT(DISTINCT member.id) AS total_users_handled,
                GROUP_CONCAT(
                    DISTINCT CONCAT(
                        roles.name, " (", 
                        IFNULL(role_user_counts.total_per_role, 0), 
                        ")"
                    ) 
                    SEPARATOR ", "
                ) AS role_user_summary
            FROM users AS admin
            LEFT JOIN roles ON roles.pic_id = admin.id
            LEFT JOIN users AS member ON member.role_id = roles.id AND member.is_active = 1
            LEFT JOIN (
                SELECT 
                    role_id,
                    COUNT(*) AS total_per_role
                FROM users
                WHERE is_active = 1
                GROUP BY role_id
            ) AS role_user_counts ON role_user_counts.role_id = roles.id
            WHERE admin.access = "ADMIN"
                AND roles.id IS NOT NULL
                AND role_user_counts.total_per_role > 0
            GROUP BY admin.id, admin.fullname
            HAVING total_users_handled > 0
            ORDER BY total_users_handled DESC;

        ';
        $picRoles = $this->db->raw($picRoleQuery)->fetch_all();
        // $this->dd($picRoles);

        $alert = $this->getMessage();
        $this->render('monitoring/pic-role/index', [
            'alert' => $alert,
            'picRoles' => $picRoles,
        ]);
    }

}
