<?php
    $app->group('/dashboard', $authcheck, function () use ($app) {

        $app->group('/flexiload', function () use ($app) {
            /**
             * Display a listing of the resource.
             *
             * @return Response
             */
            $app->get('/index', function () use ($app) {

                $app->render(new u('flexiload.list'));
                //$app->render(new u('flexiload.index'));
            });


            /**
             * Show the form for creating a new resource.
             *
             * @return Response
             */
            $app->get('/create', function () use ($app) {
                $app->view->setData('form_action', BASEURL . "dashboard/flexiload/confirm");
                $app->render(new u('flexiload.request'));
            }
            );

            /**
             * Show the form for confirmation box
             * @return Response
             */
            $app->post('/confirm', function () use ($app) {

                $headers = $app->request->headers;


                $app->view->setData('form_action', BASEURL . "dashboard/flexiload/confirm/process");
                $app->view->setData('form_title', "Are you confirm?");
                $app->view->setData('phone', $app->request()->post('phone'));
                $app->view->setData('amount', $app->request()->post('amount'));
                $app->view->setData('type', $app->request()->post('type'));
                $app->render(new u('flexiload.request_confirm'));
            });
            /**
             * Process through api call
             * @return msg
             */
            $app->post('/confirm/process', function () use ($app) {

                $phone  = $app->request()->post('phone');
                $amount = $app->request()->post('amount');
                $type   = $app->request()->post('type');


                $make_flexiload                       = new flexiload();
                $make_flexiload->amount              = $amount;
                $make_flexiload->phone               = $phone;
                $make_flexiload->type                = $type;
                $make_flexiload->user_id             = L::getSessionValue('usid');
                $make_flexiload->user_level          = L::getSessionValue('uslevel');
                $make_flexiload->cut_balance_from_id = L::getSessionValue('cut_balance_from_id');

                $make_flexiload->comments      = "flexiload($phone)";
                $make_flexiload->balance_field = 'balance2';
                $make_flexiload->save();




                if ($make_flexiload->fails()) {
                    $app->view->setData('error', 1);
                    $app->view->setData('msg', $make_flexiload->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', 1);
                    $app->view->setData('msg', "Succesfully Flexiload Request Accepted.");
                    $app->render(new u('msg'));
                }
            });



            /**
             * Display the specified resource.
             *
             * @param  int  $id
             * @return Response
             */
            $app->map('/list/:action/:page', function ($action,$page) use ($app) {



                $page  = isset($page) ? intval($page) : 1;
                $limit = MAX_RESULT_SET;
                $start = ($page - 1) * $limit;
                $us_id = L::getSessionValue('usid');

               

                $rows      = R::getAll("select *from flexiload WHERE  user_id='$us_id' and status='$action'  order by id desc LIMIT $start, $limit");
                $numOfRows = R::count('flexiload', ' user_id=? and status=? ', array($us_id, $action));


                $pagination               = new Pagination;
                $pagination->current_page = $page;
                $pagination->numOfRows    = $numOfRows;
                $pagination->page_url     = $$page_url;
                $navigation               = $pagination->getNavigationUrl();

                $app->view->setData('navigation', $navigation);
                $app->render(new u('flexiload.list'));
                unset($rows);
            })->via('GET', 'POST');
            ;


            /**
             * Show the form for editing the specified resource.
             *
             * @param  int  $id
             * @return Response
             */
            $app->get('/edit', function () use ($app) {
                /*
                  $Data = R::findOne('flexiload','id',array($id));
                  $app->render(new u('flexiload.edit'),$Data);
                 */
            });


            /**
             * Update the specified resource in storage.
             *
             * @param  int  $id
             * @return Response
             */
            $app->post('/update', function ($id) use ($app) {
                //Write validation Code Here
            });


            /**
             * Remove the specified resource from storage.
             *
             * @param  int  $id
             * @return Response
             */
            $app->post('/delete', function ($id) use ($app) {
                $flexiload = R::findOne('flexiload', 'id=?', array($id));
                if ($flexiload) {
                    R::trash($flexiload);
                }
            });
        });
    });
?>