<?php
require 'vendor/autoload.php';
require 'library/redbean/rb.php';
require 'library/u.php';
require 'config/server.php';
require 'config/config.php';





$authcheck = function() use ($app) {
if (!isset($_SESSION['username'])) {
            $app->redirect(BASEURL.'/login');
            exit;
        }
        };



	$app->get('/', function ()  use ($app) {
             echo "hi how are you";
	});

/*********************************Dashboard*************************************************/
	$app->get('/dashboard',$authcheck, function ()  use ($app) {
	 $app->view->setData('username', $_SESSION["username"]);
	$app->render(new u('Dashboard.view'));
             	});


$app->get('/dashboard/html',$authcheck, function ()  use ($app) {
	 $app->view->setData('username', $_SESSION["username"]);
	$app->render(new u('Dashboard.html'));
             	});

$app->get('/dashboard/home',$authcheck, function ()  use ($app) {
	 $app->view->setData('username', $_SESSION["username"]);
	$app->render(new u('Dashboard.home'));
             	});
$app->get('/dashboard/css',$authcheck, function ()  use ($app) {
	 $app->view->setData('username', $_SESSION["username"]);
	$app->render(new u('Dashboard.css'));
             	});
$app->get('/dashboard/javascript',$authcheck, function ()  use ($app) {
	 $app->view->setData('username', $_SESSION["username"]);
	$app->render(new u('Dashboard.javascript'));
             	});
$app->get('/dashboard/fullstack',$authcheck, function ()  use ($app) {
	 $app->view->setData('username', $_SESSION["username"]);
	$app->render(new u('Dashboard.fullstack'));
             	});


/*********************************************Logout /************************************/
	$app->get('/logout', function ()  use ($app) {
        $_SESSION=array();
         return $app->redirect(BASEURL."/login");

	});
/*********************************************Logout /************************************/
	$app->get('/insert', function ()  use ($app) {
             $users = R::dispense("users");
             $users->username = 'tanzila';
             $users->password = 'abcd';
             $id = R::store($users);
             echo $id;
             exit;
	});



    $app->get('/login', function ()  use ($app) {
    	     $app->view->setData('baseurl', BASEURL);
    	     $app->view->setData('title', "Login");
    	     $app->view->setData('h2', "Full Stack");
    	     $app->view->setData('dntshowmenu', "Welcome");
             $app->render(new u('login.index'));
	});

	//when people click the login submit uttion the fowloing function shoild fired,.

$app->post('/login', function () use ($app) {
                $user_name   = $app->request()->post('user_name');
                $user_pass   = $app->request()->post('user_pass');
                $user_button = $app->request()->post('user_button');

                if ($user_button == "Login")
                   {
                    $dbMatchRows  = R::findOne('users', 'username=?', array($user_name));
                    if($dbMatchRows != NULL){
                        $dbUserPass = $dbMatchRows->password;
                    if ($user_pass == $dbUserPass )

                        $_SESSION['username']=$user_name;
                        return $app->redirect(BASEURL . "/dashboard");

                        }
                       else{
                       	$app->view->setData('error', "Invalid username & password");
                        return $app->render(new u('login.index'));
                    }

                }

            });










/**************************************Enable Login Module*******************/
/*	$enable_module=array('login','admin','bank','client','clientreseller','line','message','vendor','vendorgateway','user');
	foreach($enable_module as $location){
	include "module/$location/$location"."_index.php";

	}
	*/

/**/


 //$currentRoute = $app->request()->getPathInfo();

$app->run();
