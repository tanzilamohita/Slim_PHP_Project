<?php
    $app->group('/dashboard',  function () use ($app) {
        $app->group('/line', function () use ($app) {

		$app->get('/create', function () use ($app) {
			$app->view->setData('form_action', "dashboard/line/confirm");
			$app->view->setData('page_title', "Create line");
			$app->render(new u('line.create'));
		});

		// Display Confirm      
		$app->post('/confirm', function () use ($app) {

			$POST                     = $app->request()->post();
			$line                     = new line;
			$line->form_post_value    = $POST;
			$line->save();

		   if ($line->fails()) {
				$app->view->setData('error', "1");
				$app->view->setData('msg', $line->getError());
				$app->render(new u('msg'));
			}
			else {
				$app->view->setData('success', "1");
				$app->view->setData('msg', "Successfully Line Created");
				$app->render(new u('msg'));
			}
		});




		// Save Form Data

		$app->post('/store', function () use ($app) {

			$app->render(new u('line.store'));
		});




		//Show
		$app->get('/view', function () use ($app) {
			$getLineList=new line();
			$cols=$getLineList->getLineList();
			$app->view->setData('rows',$cols );
			
			$app->render(new u('line.view'));
		});

		//Edit Data

		$app->get('/edit/:id', function ($id) use ($app) {
			$app->view->setData('form_action', BASEURL . "dashboard/line/edit");
			$app->render(new u('line.create'));
			$app->render(new u('line.edit'));
		});

		//Delete
		$app->get('/delete/:id', function ($id) use ($app) {
			
		});
	});
});
?>