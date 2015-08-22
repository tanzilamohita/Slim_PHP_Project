<?php
    $app->group('/dashboard',  function () use ($app) {
        $app->group('/admin', function () use ($app) {

/***********************************************************START Add/Create*********************************/


            $app->get('/create', function () use ($app) {
				$app->view->setData('currencies',L::getAllCurrency());
				$app->view->setData('form_action', "dashboard/admin/confirm");
				$app->view->setData('page_title', "Create admin");
				$app->render(new u('admin.create'));
            });


			

            $app->post('/confirm', function () use ($app) {

                $POST                      = $app->request()->post();
                $admin                     = new admin;
                $admin->form_post_value    = $POST;
                $admin->save();

               if ($admin->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $admin->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully User Created");
                    $app->render(new u('msg'));
                }
            });


/*****************************************VIEW************************************************/         
            $app->get('/view(/:page)', function ($page=0) use ($app) {
			        $getAdminList=new admin();
				$cols=$getAdminList->getAdminList();
				$app->view->setData('form_action', "/dashboard/admin/search");
				$app->view->setData('rows',$cols );
				
                $app->render(new u('admin.view'));
            });


/*********************************************Show Admin Detail******************************/


            $app->get('/info/:id', function ($id) use ($app) {
			        $getAdminList=new admin(); 
				$cols=$getAdminList->getAdminInfo( $id);
				$app->view->setData('rows',$cols );
                                $app->render(new u('admin.info'));
            });


/****************************************Edit Operation*****************************************/



            //Show edit form with Data
            $app->get('/edit/:id', function ($id) use ($app) {
                                $admin   = new admin();
                                $rows    = $admin->getAdminInfo($id);
                                $currency= $rows[0]['currency'];
                                $accesslevel = $rows[0]['accesslevel'];
				$app->view->setData('currencies',$admin->EditAllCurrency($currency));
				$app->view->setData('user_permission',$admin->EditUserPermission($accesslevel));
				$app->view->setData('rows',$rows );
				$app->view->setData('form_action', "dashboard/admin/editconfirm");
                                $app->view->setData('page_title', "Edit Admin");
                                $app->render(new u('admin.edit'));
            });
			
			$app->post('/editconfirm/', function () use ($app) {

                $POST                      = $app->request()->post();
                $admin                     = new admin;
                $admin->form_post_values    = $POST;
                $admin->update();

               if ($admin->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $admin->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully User Updated");
                    $app->render(new u('msg'));
                }
            });

/***********************************SEARCH*************************************************/

            $app->post('/search', function () use ($app) {
            $POST      = $app->request()->post();
            $SearchDb  = new SearchDb('admininfo','username',$POST);
			$app->view->setData('rows',$SearchDb->getSearchResult() );
            $app->render(new u('admin.view'));
            });


/***********************************Delete*************************************************/



            $app->get('/delete/:id', function ($id) use ($app) {
			$getAdminList = new admin(); 
			$getAdminList->updateAdminStatus($id);
				if ($getAdminList ->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $admin->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully User Blocked.");
                    $app->render(new u('msg'));
                }
			
				
              
            });
/*******************************************************************************************/
          
          
        });
    });
?>