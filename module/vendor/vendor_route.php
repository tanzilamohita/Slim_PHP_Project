<?php
    $app->group('/dashboard',  function () use ($app) {
        $app->group('/vendor', function () use ($app) {




            $app->get('/create', function () use ($app) {
				$app->view->setData('currencies',L::getAllCurrency());
				$app->view->setData('form_action', "dashboard/vendor/confirm");
                $app->view->setData('page_title', "Create vendor");
                $app->render(new u('vendor.create'));
            });





            // Display Confirm      
            $app->post('/confirm', function () use ($app) {

                $POST                      = $app->request()->post();
                $vendor                     = new vendor;
                $vendor->form_post_value    = $POST;
                $vendor->save();

               if ($vendor->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $vendor->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully Vendor Created");
                    $app->render(new u('msg'));
                }
            });



			//Show
            $app->get('/view', function () use ($app) {
			    $getVendorList=new vendor();
				$cols=$getVendorList->getVendorList();
				$app->view->setData('rows',$cols );
				
                $app->render(new u('vendor.view'));
            });




            //Show
            $app->post('/show/:id', function ($id) use ($app) {

                $app->render(new u('vendor.show'));
            });

            //Edit Data

            $app->get('/edit/:id', function ($id) use ($app) {
                $app->view->setData('form_action', BASEURL . "dashboard/vendor/edit");
                $app->render(new u('vendor.create'));
                $app->render(new u('vendor.edit'));
            });

            //Delete
            $app->get('/delete/:id', function ($id) use ($app) {
                
            });
        });
    });
?>