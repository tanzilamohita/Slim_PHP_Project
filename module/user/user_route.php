<?php
    $app->group('/dashboard',  function () use ($app) {
        $app->group('/user', function () use ($app) {

		$app->get('/create', function () use ($app) {
			$app->view->setData('form_action', "dashboard/user/confirm");
			$app->view->setData('currencies',L::getAllCurrency());
			$app->view->setData('page_title', "Create user");
			$app->render(new u('user.create'));
		});
		
		
		// Display Confirm      
		$app->post('/confirm', function () use ($app) {

			$POST                     = $app->request()->post();
			$user                     = new user;
			$user->form_post_value    = $POST;
			$user->save();

		   if ($user->fails()) {
				$app->view->setData('error', "1");
				$app->view->setData('msg', $user->getError());
				$app->render(new u('msg'));
			}
			else {
				$app->view->setData('success', "1");
				$app->view->setData('msg', "Successfully user Created");
				$app->render(new u('msg'));
			}
		});




		// Save Form Data

		$app->post('/store', function () use ($app) {

			$app->render(new u('user.store'));
		});




		//Show
		$app->get('/view', function () use ($app) {
			$getuserList=new user();
			$cols=$getuserList->getuserList();
			$app->view->setData('rows',$cols );
			
			$app->render(new u('user.view'));
		});

		//Edit Data

		$app->get('/edit/:id', function ($id) use ($app) {
			$app->view->setData('form_action', BASEURL . "dashboard/user/edit");
			$app->render(new u('user.create'));
			$app->render(new u('user.edit'));
		});

		//Delete
		$app->get('/delete/:id', function ($id) use ($app) {
			
		});
	});
});
?>