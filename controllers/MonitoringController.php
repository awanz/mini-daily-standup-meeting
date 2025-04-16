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
    
    public function listProjectManager()
    {
        $isAdmin = $this->isAdmin();

        if (!$isAdmin) {
            $this->setMessage('Kamu tidak punya hak akses');
            $this->redirect('home');
        }

        $listsQuery = '
                SELECT 
                    u.id,
                    MAX(u.fullname) AS fullname,
                    MAX(r.name) AS role_name,
                    GROUP_CONCAT(DISTINCT p.name SEPARATOR ", ") AS projects,
                    MAX(u.last_login_at) AS last_login_at,
                    COUNT(DISTINCT CASE WHEN m.type = "MEETING_PROJECT" AND ma.status = "PRESENT" THEN m.id END) AS total_meeting_project,
                    COUNT(DISTINCT CASE WHEN m.type = "MEETING_ROLE" AND ma.status = "PRESENT" THEN m.id END) AS total_meeting_role,
                    COUNT(DISTINCT CASE WHEN ma.status = "PRESENT" THEN ma.id END) AS total_meetings_current_month,
                    COUNT(DISTINCT CASE WHEN ma.status != "PRESENT" AND ma.status IS NOT NULL THEN ma.id END) AS total_meeting_absent
                FROM users u
                JOIN roles r ON u.role_id = r.id
                LEFT JOIN project_users pu ON u.id = pu.user_id AND pu.status = "ACTIVED"
                LEFT JOIN projects p ON pu.project_id = p.id
                LEFT JOIN meeting_attendances ma 
                    ON u.id = ma.user_id 
                    AND MONTH(ma.created_at) = MONTH(CURRENT_DATE()) 
                    AND YEAR(ma.created_at) = YEAR(CURRENT_DATE())
                LEFT JOIN meetings m 
                    ON ma.meeting_id = m.id
                WHERE u.is_active = 1
                AND r.name IN ("Project Manager IT")
                GROUP BY u.id
                ORDER BY fullname;

        ';
        $lists = $this->db->raw($listsQuery);

        $alert = $this->getMessage();
        $this->render('monitoring/project-manager/index', [
            'alert' => $alert,
            'lists' => $lists,
        ]);
    }

}
