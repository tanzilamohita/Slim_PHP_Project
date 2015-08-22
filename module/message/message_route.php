<?php
    $app->group('/dashboard',  function () use ($app) {
        $app->group('/message', function () use ($app) {




            $app->get('/create', function () use ($app) {
		$app->view->setData('form_action', BASEURL . "dashboard/message/confirm");
                $app->view->setData('page_title', "Create message");
                $app->render(new u('message.create'));
            });





            // Display Confirm      
            $app->post('/confirm', function () use ($app) {

                $POST                      = $app->request()->post();
                $message                     = new message;
                $message->form_post_value    = $POST;
                $message->save();

               if ($message->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $message->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Succesfully User Created");
                    $app->render(new u('msg'));
                }
            });




            // Save Form Data

            $app->post('/store', function () use ($app) {

                $app->render(new u('message.store'));
            });




/******************************************View************************************************/
            $app->get('/view/', function () use ($app) {

                $app->render(new u('message.view'));
            });
/******************************************View************************************************/
   $app->get('/view_client_msg/', function () use ($app) {

                $app->render(new u('message.view_client_msg'));
            });
/******************************************View************************************************/

            $app->get('/edit/:id', function ($id) use ($app) {
                $app->view->setData('form_action', BASEURL . "dashboard/message/edit");
                $app->render(new u('message.create'));
                $app->render(new u('message.edit'));
            });

            //Delete
            $app->get('/delete/:id', function ($id) use ($app) {
                
            });
        });
    });
?>