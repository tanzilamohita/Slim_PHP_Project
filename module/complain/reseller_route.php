<?php



$app->group('/dashboard',$authcheck, function () use ($app) {
	$app->group('/reseller', function () use ($app) 
	{
	

	//	Index , 

	$app->get('/index/', function ()  use ($app) 
		{

		$app->render(new u('reseller.list'));

		});




    // Display Form   
	$app->get('/create', function ()  use ($app) 
		{

	    $app->view->setData('form_action',BASEURL."dashboard/reseller/confirm");
	    $app->view->setData('form_title',"Create Reseller");
		$app->render(new u('reseller.create'));

		});
	



    // Display Confirm      
	$app->post('/confirm', function ()  use ($app) 
		{

		$posts    		= $app->request()->post();
        $reseller 		= new reseller;
        $reseller->val  = $posts;
        $reseller->save();

        if($reseller->fails()){
        	 $app->view->setData('error',"1");
             $app->view->setData('msg', $this->getError());
         	 $app->render(new u('msg'));
        }
        else{
         	$app->view->setData('success',"1");
		  	$app->view->setData('msg',"Succesfully User Created");
		  	$app->render(new u('msg'));
        }	
        }


		
	    
		});
	



	// Save Form Data

	$app->post('/store', function ()  use ($app) 
		{

		$app->render(new u('reseller.store'));

		});




   //Show
$app->post('/show/:id', function ($id)  use ($app) 
		{

		$app->render(new u('reseller.show'));

		});

	//Edit Data

$app->get('/edit/:id', function ($id)  use ($app) 
		{
	    $app->view->setData('form_action',BASEURL."dashboard/reseller/edit");
		$app->render(new u('reseller.create'));
		$app->render(new u('reseller.edit'));

		});
	
    //Delete
	$app->get('/delete/:id', function ($id)  use ($app) 
		{

		

		});
	


	




		});
	});



?>