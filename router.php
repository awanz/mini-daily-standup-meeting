<?php

function handleRoute($uri)
{
    if (strpos($uri, SUB_PATH) === 0) {
        $uri = substr($uri, strlen(SUB_PATH));
    }

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
        '/project/member/:id' => [
            'GET' => ['ProjectController', 'member'],
        ],
        '/project/add-member/:id' => [
            'GET' => ['ProjectController', 'addMember'],
            'POST' => ['ProjectController', 'createMember'],
        ],
        '/project/nonactive-member/:id' => [
            'GET' => ['ProjectController', 'nonactiveMember'],
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
        '/role/member/:id' => [
            'GET' => ['RoleController', 'member'],
        ],


        '/warnings' => [
            'GET' => ['WarningController', 'index'],
        ],
        '/warnings/appeal/:id' => [
            'GET' => ['WarningController', 'appeal'],
            'POST' => ['WarningController', 'appealProcess'],
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
    ];

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    // Handle dynamic routes
    foreach ($routes as $routePattern => $methods) {
        $pattern = preg_replace('#:([\w]+)#', '(?P<$1>[^/]+)', $routePattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            if (isset($methods[$requestMethod])) {
                [$controllerName, $methodName] = $methods[$requestMethod];

                $controllerFile = __DIR__ . "/controllers/$controllerName.php";

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

    $_SESSION['flash_message_alert'] = [
        'status' => 'FAILED',
        'message' => 'Halaman ' . $uri . ' tidak ditemukan.',
    ];

    header("Location: ". BASE_URL, false, 301);
    exit();
}
