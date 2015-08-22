<?php
    $app->group('/dashboard', function () use ($app) {
    $app->group('/client', function () use ($app) {
 /* *********************************Create Client Form *************************************************/
 

            $app->get('/create', function () use ($app) {
                $app->view->setData('form_action', "dashboard/client/confirm");
                $app->view->setData('page_title', "Create client");
                $app->render(new u('client.create'));
            });

  /*********************************Add Balance Form*************************************************/          
            $app->get('/add_balance', function () use ($app) {
                $POST          = array();
                $client        = new client($POST);
                $clientnames   = $client->getClientName();
                $linenames     = $client->getLineName();
                $resellernames = $client->getClientResellerName();
                $app->view->setData('clientresellers', $resellernames);
                $app->view->setData('clients', $clientnames);
                $app->view->setData('lines', $linenames);
                $app->view->setData('clientarray', $client->getOptionValueWithClientName());
                $app->view->setData('linearray', $client->getOptionValueWithLinkAndLine());
                $app->view->setData('form_action', "dashboard/client/confirm_add_balance");
                $app->view->setData('page_title', "Add Balance");
                $app->render(new u('client.add_balance'));
            });
    /*****************************************Client Create Confirm Data Processing *********************************************/

            $app->post('/confirm', function () use ($app) {

                $POST                    = $app->request()->post();
                $client                  = new client($POST);
                $client->form_post_value = $POST;
                $client->save();

                if ($client->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $client->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully Client Added");
                    $app->render(new u('msg'));
                }
            });







            //Show
            $app->get('/view', function () use ($app) {
                $getclientList = new client();
                // $dues = $getclientList->sumDueLimit();
                // $app->view->setData('sumdues',$dues );

                $cols = $getclientList->getClientList();
                $app->view->setData('rows', $cols);

                $app->render(new u('client.view'));
            });

            
            
            
/******************Add Balance Data Processing **************************************************/
              $app->post('/confirm_add_balance', function () use ($app) {
                $POST                    = $app->request()->post();
                
                $client                  = new client($POST);
                $client->saveAddBalance();

                if ($client->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $client->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully Balance Added");
                    $app->render(new u('msg'));
                }
                  
             });

            
/******************Data Processing **************************************************/            

            $app->get('/edit/:id', function ($id) use ($app) {
                $app->view->setData('form_action', BASEURL . "dashboard/client/edit");
                $app->render(new u('client.create'));
                $app->render(new u('client.edit'));
            });

     /*****************************ADD BALANCE************************************************ */

            $app->get('/add_balance', function () use ($app) {
                $app->render(new u('client.add_balance'));
            });

            /*             * *******************************view_added_balance********************************************* */


            $app->get('/view_added_balance', function () use ($app) {
                $app->render(new u('client.view_added_balance'));
            });


            /*             * *******************************received_payment********************************************* */


            $app->get('/received_payment', function () use ($app) {
                $app->render(new u('client.received_payment'));
            });

            /*             * *******************************view_received_payments********************************************* */


            $app->get('/view_received_payments', function () use ($app) {
                $app->render(new u('client.view_received_payments'));
            });

            /*             * *******************************balance********************************************* */


            $app->get('/balance', function () use ($app) {
                $app->render(new u('client.balance'));
            });
            /*             * ************************************************************************************ */
        });
    });
?>