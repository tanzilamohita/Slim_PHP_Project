<?php
    $app->group('/dashboard',  function () use ($app) {
        $app->group('/bank', function () use ($app) {

/************************************ Save/Insert ***************************************  */			
			//create for bank account
            $app->get('/create', function () use ($app) {
				
			    $app->view->setData('banks',L::getAllBank());
			    $app->view->setData('currencies',L::getAllCurrency());
				$app->view->setData('form_action', "dashboard/bank/confirm");
                $app->view->setData('page_title', "Add Bank");
                $app->render(new u('bank.create'));
            });
			
			
			
			// create for expense
			$app->get('/create_expense', function () use ($app) {
				
				$getBankFunc=new bank();
			    $cols=$getBankFunc->getBankName();
			    $app->view->setData('banks',$cols );
			    $app->view->setData('current_date',CURRENT_DT );
				$app->view->setData('bankarray',$getBankFunc->getOptionValueWithRateAndBankName() );

				$app->view->setData('form_action', "dashboard/bank/create_expense_confirm");
                $app->view->setData('page_title', "Add Expense");
				
                $app->render(new u('bank.create_expense'));
            });
			
/********************************* Confirm submitting Form ***************************** */			
			//confirm for bank account
            $app->post('/confirm', function () use ($app) {

                $POST                     = $app->request()->post();
                $bank                     = new bank;
                $bank->form_post_value    = $POST;
                $bank->save();

               if ($bank->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $bank->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully Bank Account Created");
                    $app->render(new u('msg'));
                }
            });

			// confirm for expense
			$app->post('/create_expense_confirm', function () use ($app) {

                $POST                     = $app->request()->post();
                $bank                     = new bank;
                $bank->form_post_values    = $POST;
                $bank->saveExpense();

               if ($bank->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $bank->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully Bank Expense Added");
                    $app->render(new u('msg'));
                }
            });
			
/************************************View details *************************************************/			
			//view for summary
			$app->get('/summary', function () use ($app) {
				//$app->view->setData('form_action', BASEURL . "dashboard/bank/confirm");
                $app->view->setData('page_title', "Bank Summary");
                $app->render(new u('bank.summary'));
            });
			
			//Show
            $app->get('/view', function () use ($app) {
			    $getbankList=new bank();
				$cols=$getbankList->getBankName();
				$app->view->setData('form_action', "/dashboard/bank/search");
				$app->view->setData('rows',$cols );
				
                $app->render(new u('bank.view'));
            });
			
			
			//Show expense
            $app->get('/view_expense', function () use ($app) {
			    $getbankList=new bank();
				$cols=$getbankList->getBankExpense();
				$app->view->setData('rows',$cols );
				
                $app->render(new u('bank.view'));
            });
       
/*********************************************Show Bank Detail******************************/


            $app->get('/info/:id', function ($id) use ($app) {
			        $bank = new bank(); 
				$cols=$bank->getBankInfo( $id);
				$app->view->setData('rows',$cols );
                $app->render(new u('bank.info'));
            });

/**************************************** Edit/Update Data ***********************************/
            //Edit Data

            $app->get('/edit/:id', function ($id) use ($app) {
                $app->view->setData('form_action', BASEURL . "dashboard/bank/edit");
                $app->render(new u('bank.create'));
                $app->render(new u('bank.edit'));
            });
			
/***********************************SEARCH*************************************************/

            $app->post('/search', function () use ($app) {
            $POST      = $app->request()->post();
            $SearchDb  = new SearchDb('bank','accountname',$POST);
			$app->view->setData('rows',$SearchDb->getSearchResult() );
            $app->render(new u('bank.view'));
            });
			
/***********************************Delete*************************************************/



            $app->get('/delete/:id', function ($id) use ($app) {
			$bank = new bank(); 
			$bank->updateBankStatus($id);
				if ($bank ->fails()) {
                    $app->view->setData('error', "1");
                    $app->view->setData('msg', $admin->getError());
                    $app->render(new u('msg'));
                }
                else {
                    $app->view->setData('success', "1");
                    $app->view->setData('msg', "Successfully Bank Blocked.");
                    $app->render(new u('msg'));
                }
			
				
              
            });
/*******************************************************************************************/
        });
    });
?>