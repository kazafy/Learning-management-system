<?php
/**
 * Created by PhpStorm.
 * User: kazafy
 * Date: 24/02/17
 * Time: 11:05 ص
 */
require_once 'model/User.php';
require_once 'controller/LoginController.php';
require_once 'controller/RegisterController.php';
require_once 'controller/MainController.php';
require_once 'controller/AdminController.php';


function route($regex, $cb) {
    $regex = str_replace('/', '\/', $regex);
    $is_match = preg_match('/^' . ($regex) . '$/', $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE);
    if ($is_match) { $cb($matches); }
}

//route('/blog/api/(.*)', function($matches){
//    \controller\CountersConntroller::getUpdatedCounters($matches[1][0]);
//    exit();
//});

//echo  " lol ";

route('/lms/api/login', function($matches){
    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Headers:origin,X-Request-With,Content-Type,Accept,");

//    $loginController = new \controller\LoginController();
//    $loginController->loginHandler();
     $user = new \model\User();
     $user->setUsername("kazafy");
    $user->setId(1);
    $user->setImg("ddd");
    $user->setEmail("dd");
    $user->setPassword("d");
    $user->setPhone("58");
    $user->setType(1);
//     echo $user;
    echo json_encode($user);
    exit;
});

route('/lms/', function($matches){
    include "index.php";
});
route('/lms/login/(.*)', function($matches){
    $loginController = new \controller\LoginController();
    $loginController->loginHandler();
    exit;
});

route('/lms/register/(.*)', function($matches){
    $registerController = new \controller\RegisterController();
    $registerController->registerHandler();
    exit;
});

route('/lms/admin/user/list/', function($matches){
    checkAdmin();
    $adminController = new \controller\AdminController();
    $adminController->showUsers();
    exit;
});

route('/lms/admin/user/delete/(.*)', function($matches){
    checkAdmin();
    checkNumber($matches[1][0]);
    $adminController = new \controller\AdminController();
    $adminController->deleteUser((int)$matches[1][0]);
    exit;
});
route('/lms/admin/user/update/(.*)', function($matches){
    checkAdmin();
    checkNumber($matches[1][0]);
    $adminController = new \controller\AdminController();
    $adminController->updateUser((int)$matches[1][0]);
    exit;
});
route('/lms/admin/post/delete/(.*)', function($matches){
    checkAdmin();
    checkNumber($matches[1][0]);
    $mainController =new \controller\MainController();
    $mainController->deletePost($matches[1][0]);

    exit;
});


route('/lms/admin/post/add/', function($matches){
    checkLogin();
    $mainController =new \controller\MainController();
    $mainController->addPost();
    exit;
});

route('/lms/admin/post/update/(.*)', function($matches){
    checkLogin();
    checkNumber($matches[1][0]);
    $mainController =new \controller\MainController();
    $mainController->updatePost($matches[1][0]);
    exit;
});
route('/lms/admin/post/list/', function($matches){

});

route('/lms/admin/post/(.*)', function($matches){
//    checkAuth();
    $mainController = new \controller\MainController();
    checkNumber($matches[1][0]);
    $mainController->postPreviewHandeler((int)$matches[1][0]);
    exit;
});



route('/lms/admin/(.*)', function($matches){
    checkAdmin();
});


route('/lms/home/(.*)', function($matches){
    $mainController = new \controller\MainController();
    $mainController->mainHandler();
    exit;
});


route('/lms/error', function($matches){
    include "view/errorpage.php";

});



function checkAdmin(){
    session_start();
    if(!isset($_SESSION['user'])) {
        header("Location: http://localhost/lms/login/");
    }
    else{
        $user = $_SESSION['user'];
        if($user->getType()!=0){
            echo "you dont have permition";
            exit();
        }
    }
}

function checkLogin(){
    session_start();
    if(!isset($_SESSION['user'])) {
        header("Location: http://localhost/lms/login/");
    }
    else{
        $user = $_SESSION['user'];
    }
}

function checkNumber($number){

    if( ! is_numeric($number)){
        header("Location: http://localhost/lms/error");
    }
}


route('/lms/logout/', function($matches){
    session_start();
    echo  "logout";
    session_destroy();
    unset($_COOKIE['user']);
    setcookie('user', null, -1, '/');
    header("Location: http://localhost/lms/home/");
    exit;
});
?>