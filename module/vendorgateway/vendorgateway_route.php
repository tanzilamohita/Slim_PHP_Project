<?php
    $app->group('/dashboard',  function () use ($app) {
        $app->group('/vendorgateway', function () use ($app) {




            $app->get('/create', function () use ($app) {
				$getVendors = new vendorgateway();
				
				$app->view->setData('form_action', "dashboard/vendorgateway/confirm");
                $app->view->setData('page_title', "Create vendorgateway");
				$cols = $getVendors->getVendor();
				$app->view->setData('vendors',$cols );
                $app->render(new u('vendorgateway.create'));
            });
			

            $app->post('/confirm', function () use ($app) {

                $POST                      = $app->request()->post();
                $vendorgateway                     = new vendorgateway;
                $vendorgateway->form_post_value    = $POST;
                $vendorgateway->save();

               if ($vendorgateway->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $vendorgateway->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully Vendor Gateway Created");
                    $app->render(new u('msg'));
                }
            });




       




            //Show
            $app->get('/view', function () use ($app) {
			    $getvendorgatewayList=new vendorgateway();
				$cols=$getvendorgatewayList->getvendorgatewayList();
				$app->view->setData('rows',$cols );
				
                $app->render(new u('vendorgateway.view'));
            });

            //Edit Data

            $app->get('/edit/:id', function ($id) use ($app) {
                $app->view->setData('form_action', BASEURL . "dashboard/vendorgateway/edit");
                $app->render(new u('vendorgateway.create'));
                $app->render(new u('vendorgateway.edit'));
            });

            //Delete
            $app->get('/delete/:id', function ($id) use ($app) {
                
            });
        });
    });
?>