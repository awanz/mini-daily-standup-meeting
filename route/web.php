<?php

function handleRoute($uri)
{
    if (strpos($uri, SUB_PATH) === 0) {
        $uri = substr($uri, strlen(SUB_PATH));
    }
    // print_r($uri); die();

    $routes = [
        '/' => [
            'GET' => ['LoginController', 'index'],
        ],
        '/login' => [
            'GET' => ['LoginController', 'index'],
            'POST' => ['LoginController', 'loginProcess'],
        ],
        '/forgot-password' => [
            'GET' => ['LoginController', 'forgotPassword'],
        ],

        '/profile' => [
            'GET' => ['HomeController', 'profile'],
        ],
        '/profile/change-password' => [
            'GET' => ['HomeController', 'changePassword'],
            'POST' => ['HomeController', 'changePasswordProcess'],
        ],

        '/profile/resign' => [
            'GET' => ['ProfileController', 'resign'],
            'POST' => ['ProfileController', 'resignProcess'],
        ],
        '/profile/resign/cancel' => [
            'POST' => ['ProfileController', 'resignCancelProcess'],
        ],

        '/profile/intership-finalization' => [
            'GET' => ['ProfileController', 'intershipFinalization'],
            'POST' => ['ProfileController', 'intershipFinalizationProcess'],
        ],
        '/profile/intership-finalization/cancel' => [
            'POST' => ['ProfileController', 'intershipFinalizationCancelProcess'],
        ],

        '/home' => [
            'GET' => ['HomeController', 'index'],
            'POST' => ['HomeController', 'submitDaily'],
        ],
        '/home/:date' => [
            'GET' => ['HomeController', 'index'],
        ],
        '/history' => [
            'GET' => ['HomeController', 'history'],
        ],
        '/history/:email' => [
            'GET' => ['HomeController', 'history'],
        ],
        '/history/delete/:id' => [
            'GET' => ['HomeController', 'delete'],
        ],
        '/keluar' => [
            'GET' => ['HomeController', 'logout2'],
        ],

        '/user' => [
            'GET' => ['UserController', 'index'],
        ],
        '/user/nonactive' => [
            'GET' => ['UserController', 'listNonactive'],
        ],
        '/user/actived/:id' => [
            'GET' => ['UserController', 'actived'],
        ],
        '/user/add' => [
            'GET' => ['UserController', 'add'],
            'POST' => ['UserController', 'create'],
        ],
        '/user/edit/:id' => [
            'GET' => ['UserController', 'edit'],
            'POST' => ['UserController', 'update'],
        ],
        '/user/detail/:id' => [
            'GET' => ['UserController', 'detail'],
        ],
        '/user/delete/:id' => [
            'GET' => ['UserController', 'delete'],
        ],

        '/project' => [
            'GET' => ['ProjectController', 'index'],
        ],
        '/project/add' => [
            'GET' => ['ProjectController', 'add'],
            'POST' => ['ProjectController', 'create'],
        ],
        '/project/edit/:id' => [
            'GET' => ['ProjectController', 'edit'],
            'POST' => ['ProjectController', 'update'],
        ],
        '/project/delete/:id' => [
            'GET' => ['ProjectController', 'delete'],
        ],
        '/project/detail/:id' => [
            'GET' => ['ProjectController', 'detail'],
        ],
        '/project/detail/note/:project_user_id' => [
            'GET' => ['ProjectController', 'note'],
            'POST' => ['ProjectController', 'updateNote'],
        ],
        '/project/add-member/:id' => [
            'GET' => ['ProjectController', 'addMember'],
            'POST' => ['ProjectController', 'createMember'],
        ],
        '/project/meeting-attendance/:project_id' => [
            'GET' => ['ProjectController', 'attendance'],
            'POST' => ['ProjectController', 'createAttendance'],
        ],
        '/project/meeting-attendance/download/:project_id' => [
            'GET' => ['ProjectController', 'attendanceDownload']
        ],
        '/project/meeting-attendance-detail/:meeting_id' => [
            'GET' => ['ProjectController', 'attendanceDetail'],
        ],
        '/project/meeting-attendance-delete/:meeting_id' => [
            'GET' => ['ProjectController', 'deleteAttendance'],
        ],
        '/project/nonactive-member/:id' => [
            'GET' => ['ProjectController', 'nonactiveMember'],
        ],
        '/project/active-member/:id' => [
            'GET' => ['ProjectController', 'activeMember'],
        ],
        
        '/role' => [
            'GET' => ['RoleController', 'index'],
        ],
        '/role/add' => [
            'GET' => ['RoleController', 'add'],
            'POST' => ['RoleController', 'create'],
        ],
        '/role/edit/:id' => [
            'GET' => ['RoleController', 'edit'],
            'POST' => ['RoleController', 'update'],
        ],
        '/role/delete/:id' => [
            'GET' => ['RoleController', 'delete'],
        ],
        '/role/detail/:id' => [
            'GET' => ['RoleController', 'detail'],
        ],
        '/role/meeting-attendance/:role_id' => [
            'GET' => ['RoleController', 'attendance'],
            'POST' => ['RoleController', 'createAttendance'],
        ],
        '/role/meeting-attendance-detail/:meeting_id' => [
            'GET' => ['RoleController', 'attendanceDetail'],
        ],
        '/role/meeting-attendance-delete/:meeting_id' => [
            'GET' => ['RoleController', 'deleteAttendance'],
        ],

        '/warnings' => [
            'GET' => ['WarningController', 'index'],
        ],
        '/warnings/appeal/:id' => [
            'GET' => ['WarningController', 'appeal'],
            'POST' => ['WarningController', 'appealProcess'],
        ],
        '/warnings/add' => [
            'GET' => ['WarningController', 'add'],
            'POST' => ['WarningController', 'addProcess'],
        ],
        '/email/credential/:id' => [
            'GET' => ['EmailController', 'credential'],
        ],
        '/email/peringatan/:id' => [
            'GET' => ['EmailController', 'peringatan'],
        ],
        '/email/pemecatan/:id' => [
            'GET' => ['EmailController', 'pemecatan'],
        ],
        '/calendar' => [
            'GET' => ['CalendarController', 'index'],
        ],
        '/absences-intern' => [
            'GET' => ['AbsenceController', 'intern'],
        ],
        '/intern-finalization' => [
            'GET' => ['AbsenceController', 'internFinalization'],
        ],
        '/absences-volunteer' => [
            'GET' => ['AbsenceController', 'volunteer'],
        ],
        
        '/hr/resigns' => [
            'GET' => ['HRResignController', 'index'],
        ],
        '/hr/resigns/approve' => [
            'POST' => ['HRResignController', 'approveResign'],
        ],
        '/hr/resigns/revise' => [
            'POST' => ['HRResignController', 'reviseResign'],
        ],
        '/hr/resigns/cancel' => [
            'POST' => ['HRResignController', 'cancelResign'],
        ],

        '/hr/finalizations' => [
            'GET' => ['HRFinalizationController', 'index'],
        ],
        '/hr/finalizations/approve' => [
            'POST' => ['HRFinalizationController', 'approveFinalization'],
        ],
        '/hr/finalizations/revise' => [
            'POST' => ['HRFinalizationController', 'reviseFinalization'],
        ],
        '/hr/finalizations/cancel' => [
            'POST' => ['HRFinalizationController', 'cancelFinalization'],
        ],

        '/hr/candidate-requests' => [
            'GET' => ['HRCandidateRequestController', 'index'],
        ],
        '/hr/candidate-requests/detail/:id' => [
            'GET' => ['HRCandidateRequestController', 'detail'],
        ],
        '/hr/candidate-requests/add' => [
            'GET' => ['HRCandidateRequestController', 'add'],
            'POST' => ['HRCandidateRequestController', 'addProcess'],
        ],
        '/hr/candidate-requests/edit/:id' => [
            'GET' => ['HRCandidateRequestController', 'edit'],
            'POST' => ['HRCandidateRequestController', 'editProcess'],
        ],
        '/hr/candidate-requests/candidate/:id' => [
            'GET' => ['HRCandidateRequestController', 'candidate'],
        ],
        '/hr/candidate-requests/candidate/add/:id' => [
            'GET' => ['HRCandidateRequestController', 'candidateAdd'],
            'POST' => ['HRCandidateRequestController', 'candidateAddProcess'],
        ],
        '/hr/candidate-requests/candidate/edit/:id' => [
            'GET' => ['HRCandidateRequestController', 'candidateEdit'],
            'POST' => ['HRCandidateRequestController', 'candidateEditProcess'],
        ],
        '/hr/candidate-requests/candidate/delete/:id' => [
            'POST' => ['HRCandidateRequestController', 'candidateDeleteProcess'],
        ],

        '/monitoring/pic-role' => [
            'GET' => ['MonitoringController', 'index'],
        ],
        '/monitoring/project-manager' => [
            'GET' => ['MonitoringController', 'listProjectManager'],
        ],
        
        '/finance/kurs-dollar' => [
            'GET' => ['FinanceController', 'kursDollar'],
        ],
        '/finance/kurs-dollar/refresh' => [
            'GET' => ['FinanceController', 'kursDollarRefresh'],
        ],
    ];

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    // Handle dynamic routes
    foreach ($routes as $routePattern => $methods) {
        $pattern = preg_replace('#:([\w]+)#', '(?P<$1>[^/]+)', $routePattern);
        $pattern = '#^' . $pattern . '$#';
        // echo $pattern;

        if (preg_match($pattern, $uri, $matches)) {
            if (isset($methods[$requestMethod])) {
                [$controllerName, $methodName] = $methods[$requestMethod];

                $controllerFile = __DIR__ . "/../controllers/$controllerName.php";

                if (file_exists($controllerFile)) {
                    require_once $controllerFile;

                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();

                        if (method_exists($controller, $methodName)) {
                            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                            return $controller->$methodName($params);
                        }
                    }
                }
            } else {
                http_response_code(405);
                echo "405 - Method Not Allowed";
                return;
            }
        }
    }

    // http_response_code(404);
    // echo "Page Not Found<br>";die($uri);

    // $result = [
    //     'status' => 'FAILED',
    //     'message' => 'Halaman ' . $uri . ' tidak ditemukan.',
    // ];

    // print_r($result);

    // $_SESSION['flash_message_alert'] = $result;

    header("Location: ". BASE_URL.'/404.html', false, 301);
    exit();
}
