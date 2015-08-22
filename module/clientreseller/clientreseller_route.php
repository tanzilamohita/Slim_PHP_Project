<?php
    $app->group('/dashboard',  function () use ($app) {
        $app->group('/clientreseller', function () use ($app) {




            $app->get('/create', function () use ($app) {
				$client = new clientreseller();
			    $clientnames=$client->getClientName();
			    $linenames=$client->getLineName();
			    $app->view->setData('clients',$clientnames );
			    $app->view->setData('lines',$linenames );
				$app->view->setData('linearray',$client->getOptionValueWithLinkAndLine() );
				$app->view->setData('form_action', "dashboard/clientreseller/confirm");
                $app->view->setData('page_title', "Create CLient Reseller");
                $app->render(new u('clientreseller.create'));
            });
			

            $app->post('/confirm', function () use ($app) {

                $POST                      = $app->request()->post();
                $clientreseller                     = new clientreseller;
                $clientreseller->form_post_value    = $POST;
                $clientreseller->save();

               if ($clientreseller->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $clientreseller->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully Client Reseller Created");
                    $app->render(new u('msg'));
                }
            });




       




            //Show
            $app->get('/view', function () use ($app) {
			    $getclientresellerList = new clientreseller();
				$cols=$getclientresellerList->getClientResellerList();
				$app->view->setData('rows',$cols );
				
                $app->render(new u('clientreseller.view'));
            });

            //Edit Data

            $app->get('/edit/:id', function ($id) use ($app) {
                $app->view->setData('form_action', BASEURL . "dashboard/clientreseller/edit");
                $app->render(new u('clientreseller.create'));
                $app->render(new u('clientreseller.edit'));
            });

            //Delete
            $app->get('/delete/:id', function ($id) use ($app) {
                
            });
        });
    });
?>