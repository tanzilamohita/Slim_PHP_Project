<?php
    $app->group('/dashboard', $authcheck, function () use ($app) {
        $app->group('/payment', function () use ($app)
        {

            //[Start add payment operation]
            $app->group('/add', function () use ($app)
            {


                        $app->get('/flexiload/:id', function ($customer_id) use ($app)
                        {
                            $app->view->setData('form_action', BASEURL . "dashboard/payment/add/flexiload/confirm");
                            $app->view->setData('customer_id', $customer_id);
                            $app->render(new u('payment.flexiload.request'));


                        });

                $app->post('flexiload/confirm/', function () use ($app) {

                    $app->view->setData('form_action', BASEURL . "dashboard/payment/add/flexiload/confirm/process");
                    $app->view->setData('form_title', "Are you confirm?");
                    $app->view->setData('payment_amount', $app->request()->post('payment_amount'));
                    $app->view->setData('payment_memo', $app->request()->post('payment_memo'));
                    $app->view->setData('customer_id', $app->request()->post('customer_id'));
                    $app->render(new u('payment.flexiload.request_confirm'));
                });


                $app->get('/flexiload/confirm/process', function () use ($app)
                {



                    $payment                  = new payment;
                    $payment->who_is_paying   = L::getSessionValue('usid');
                    $payment->who_is_paying   = $app->request()->post();
                    $payment->payment_type    = 'add';
                    $payment->save();

                    if ($payment->fails())
                    {
                        $app->view->setData('error', 1);
                        $app->view->setData('msg', $payment->getError());
                        $app->render(new u('msg'));
                    }
                    else
                    {
                        $app->view->setData('success', 1);
                        $app->view->setData('msg', "Succesfully Payment  Accepted.");
                        $app->render(new u('msg'));
                    }



                });

            //Flexiload End








            });
            //[End add payment operation]

            //Start return payment operation
            $app->group('/return', function () use ($app)
            {

                //[Start add payment operation]


                    $app->get('/flexiload/:id', function ($customer_id) use ($app)
                    {
                        $app->view->setData('form_action', BASEURL . "dashboard/payment/add/flexiload/confirm");
                        $app->view->setData('customer_id', $customer_id);
                        $app->render(new u('payment.flexiload.request'));


                    });

                    $app->post('flexiload/confirm/', function () use ($app) {

                        $app->view->setData('form_action', BASEURL . "dashboard/payment/add/flexiload/confirm/process");
                        $app->view->setData('form_title', "Are you confirm?");
                        $app->view->setData('payment_amount', $app->request()->post('payment_amount'));
                        $app->view->setData('payment_memo', $app->request()->post('payment_memo'));
                        $app->view->setData('customer_id', $app->request()->post('customer_id'));
                        $app->render(new u('payment.flexiload.request_confirm'));
                    });


                    $app->get('/flexiload/confirm/process', function () use ($app)
                    {



                        $payment                  = new payment;
                        $payment->who_is_paying   = L::getSessionValue('usid');
                        $payment->who_is_paying   = $app->request()->post();
                        $payment->payment_type    = 'return';
                        $payment->save();

                        if ($payment->fails())
                        {
                            $app->view->setData('error', 1);
                            $app->view->setData('msg', $payment->getError());
                            $app->render(new u('msg'));
                        }
                        else
                        {
                            $app->view->setData('success', 1);
                            $app->view->setData('msg', "Succesfully Payment  Returned.");
                            $app->render(new u('msg'));
                        }



                    });

                    //Flexiload End


                });
            //End return payment operation

        });
        
        
    });



?>