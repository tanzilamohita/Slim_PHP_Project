<?php



        $app->group('/login', function () use ($app) {
            /**
             * Display a listing of the resource.
             *
             * @return Response
             */
            $app->get('/index', function () use ($app) {
                $app->view->setData('form_action', BASEURL . "/login/login");
                $app->render(new u('login.index'));
            });


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
                        session_start();
                        $_SESSION['username']=$user_name;
                        return $app->redirect(BASEURL . "/dashboard");

                        }
                       else{
                       	$app->view->setData('error', "Invalid username & password");
                        return $app->render(new u('login.index'));
                    }

                }


            });



            /**
             * Show the form for creating a new resource.
             *
             * @return Response
             */
            $app->get('/forget', function () use ($app) {
                $app->render(new u('forget'));
            });


            $app->get('/logout', function () use ($app) {
                $_SESSION = array();
                $app->redirect('logout');
            });
        });

?>
