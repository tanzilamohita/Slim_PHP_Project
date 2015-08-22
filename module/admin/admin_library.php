<?php

class admin extends L {
    
    

    public $form_post_value;
    public $validated_data;
    public $validated_datas;
	private $validation_status;
    
    
  /************************************* Save or Insert Operation ******************************************     */  
    public function save(){
		
		
		$gump = new GUMP\GUMP();
		$POST = $gump->sanitize($this->form_post_value); // You don't have to sanitize, but it's safest to do so.

		$gump->validation_rules(array(
			'user_full_name' 		=> 'required',
			'user_name' 			=> 'required',
			'user_password' 		=> 'required',
			'user_confirm_password' => 'required',
			'user_permission' 		=> 'required',
			'user_currency' 		=> 'required',
			'user_mobile' 			=> 'required|max_len,11|min_len,11',
			'user_email' 			=> 'required|valid_email',	
			
		));

		$gump->filter_rules(array(
			'user_full_name' 		 => 'trim|sanitize_string',
			'user_name' 			 => 'trim',
			'user_password' 		 => 'trim|sanitize_string',
			'user_confirm_password'  => 'trim',
			'user_permission' 		 => 'trim',
			'user_currency' 		 => 'trim',
			'user_mobile' 			 => 'trim',
			'user_email' 			 => 'trim|sanitize_email',
			'user_notes' 			 => 'noise_words',
			                        
			
		));

		$validated_data = $gump->run($POST);

		// if($validated_data === false) {
			// echo $gump->get_readable_errors(true);
		// } else {
			// //print_r($validated_data); // validation successful
		// }
				
		// exit;
		
			// $is_valid = GUMP\GUMP::is_valid($this->form_post_value, array(
				// 'user_full_name' 		=> 'required',
				// 'user_name' 			=> 'required',
				// 'user_password' 		=> 'required',
				// 'user_confirm_password' => 'required',
				// 'user_permission' 		=> 'required',
				// 'user_currency' 		=> 'required',
				// 'user_mobile' 			=> 'required|max_len,11|min_len,11',
				// 'user_email' 			=> 'required|valid_email',
				// 'user_notes' 			=> 'required|max_len,500'	
			// ));

			if($is_valid === true){
				
					if($this->isDublicateUser($this->validated_data['user_name'])){
						$this->setError('Duplicate username,Please choose another');
					}
					elseif($this->isNotStrongPassword($this->validated_data['user_password'])){
					 $this->setError('Please choose strong password');   
					}
					else if($this->validated_data['user_password'] != $this->validated_data['user_confirm_password']){
						$this->setError('Confirm password is not matched');
					}
					else{
					
						
						$admin= R::dispense("user");

						$admin->fullname       	= $this->validated_data['user_full_name'];
						$admin->username    	= $this->validated_data['user_name'];
						$admin->password    	= md5(SALT.$this->validated_data['user_password']);
						$admin->accesslevel 	= $this->validated_data['user_permission'];
						$admin->basecurrency    = $this->validated_data['user_currency'];
						$admin->type  			= 1;						
						$admin->email       	= $this->validated_data['user_email'];
						$admin->phone       	= $this->validated_data['user_mobile'];
						$admin->note        	= $this->validated_data['user_notes'];
						$admin->createdate  	= CURRENT_DTT;
						$admin->updatedate  	= CURRENT_DTT;
						$admin->createby 		= 1001;
						$admin->isactive  		= 1;
						$id = R::store($admin);
						
					}
					
				
			}
					else{
						$this->setError($is_valid[0]);
					}

			   
				
		  
				
				
			//if ($this->getError() == "") {$this->fails = FALSE;} else {$this->fails = TRUE;}
				
        
        
    }
    
	
	
	public function update(){

			if($this->is_validated_edit()){
				
				//print_r($this->validated_datas);
				$adminid =	$this->validated_datas['user_id'];
				//$admin= R::dispense("admininfo");
				$admin = R::load('admininfo', $adminid);

				$admin->fullname       	= $this->validated_datas['user_full_name'];
				//$admin->username    	= $this->validated_datas['user_name'];
				//$admin->password    	= md5(SALT.$this->validsated_data['user_password']);
				$admin->accesslevel 	= $this->validated_datas['user_permission'];
				$admin->currency    	= $this->validated_datas['user_currency'];
				$admin->email       	= $this->validated_datas['user_email'];
				$admin->phone       	= $this->validated_datas['user_mobile'];
				$admin->note        	= $this->validated_datas['user_notes'];
				//$admin->createdate  	= CURRENT_DTT;
				$admin->updatedate  	= CURRENT_DTT;
				$admin->createby 		= 1001;
				//$admin->isactive  		= 1;
				R::store($admin);
				
			}

			   
				
		  
				
				
				if ($this->getError() == "") {$this->fails = FALSE;} else {$this->fails = TRUE;}
				
        
        
    }
	
	
	
	
   //|alpha_numeric|max_len,10|min_len,6 
    public function fails() {
        return $this->fails;
    }
    
	public  function is_validated(){
		 $gump   = new  \GUMP\GUMP;
         $_POST  = $gump->sanitize($this->form_post_value); 
         $rules  = array(
            'user_full_name' 		=> 'required',
            'user_name' 			=> 'required',
            'user_password' 		=> 'required',
            'user_confirm_password' => 'required',
            'user_permission' 		=> 'required',
            'user_currency' 		=> 'required',
            'user_mobile' 			=> 'required|max_len,11|min_len,11',
            'user_email' 			=> 'required|valid_email',
            'user_notes' 			=> 'required|max_len,500'
        );
      
        $filters = array(
            'user_full_name' 	    => 'trim|sanitize_string',
            'user_name' 			=> 'trim|sanitize_string',
            'user_password' 	    => 'trim|sanitize_string',
            'user_confirm_password' => 'trim|sanitize_string',
			'user_permission'		=> 'trim',
			'user_currency' 		=> 'trim',
			'user_mobile' 	        => 'trim',
			'user_email' 	        => 'trim|sanitize_email',
			'user_notes' 			=> 'noise_words'
			
        );
		
		$validated_data = $gump->run($_POST);
		if($validated_data === false) {
			$this->error = $gump->get_readable_errors(true);
		} else {
			$this->validated_data=$validated_data;
				return TRUE;
			
		}


	}
	
	public  function is_validated_edit(){
		 $gump   = new  \GUMP\GUMP;
         $_POST  = $gump->sanitize($this->form_post_values); 
        $rules = array(
            'user_full_name' 		=> 'required',
            'user_permission' 		=> 'required',
            'user_currency' 		=> 'required',
            'user_mobile' 			=> 'required|max_len,11|min_len,11',
            'user_email' 			=> 'required|valid_email',
            'user_notes' 			=> 'required|max_len,500'
        );
        
        $filters = array(
        'user_full_name' 	=> 'trim|sanitize_string',
	    'user_permission'	=> 'trim',
	    'user_currency' 	=> 'trim',
	    'user_mobile' 	    => 'trim',
	    'user_email' 	    => 'trim|sanitize_email',
	    'user_notes' 		=> 'noise_words'
			
        );
		
		$validated_datas = $gump->run($_POST);
		if($validated_datas === false) {
			$this->error = $gump->get_readable_errors(true);
		} else {
			$this->validated_datas=$validated_datas;
				return TRUE;
			
		}


	}
	
	/*
	*Get Admin List
	*
	*/
	
	public function getAdminList()
	{
		$rows = R::getAll("SELECT *FROM user where type= 1 and isactive = 1 order by id DESC limit 500 ");
			foreach($rows as $row){
			$row['accesslevel'] = L::getAccessLevelFromId($row['accesslevel']);
						$row['id'] =  L::encryptData($row['id']);
			$data[]=$row;
			}
		return $data;
	}

	public function getAdminInfo($id){
		$id = (int)L::decryptData($id); 
        $rows = R::getAll("SELECT *FROM admininfo where id=?",array($id));
  	    foreach($rows as $row){
			$row['accesslevel'] = L::getAccessLevelFromId($row['accesslevel']);
			$data[]=$row;
	    }
		return $data;
	}




	public function updateAdminStatus($id){
		$data= R::load("admininfo",(int) L::decryptData($id));
		$data->isactive = 0;           
		if ($data->id == 0) {
		$this-setError("User Not Found");
		}
		else{
		R::store($data);
        }
                    
        if ($this->getError() == "") {$this->fails = FALSE;} else {$this->fails = TRUE;}
	}




	public function EditAllCurrency($code) {
	$text="";
               
                $code=(string) $code;      
		$rows =R::getAll("select * from currency");
                foreach($rows as $row){
                           
                   if($row['code']=="$code"){        
                   $text  .="<option SELECTED  value='$row[code]'>$row[name]</option>";
                   }else{                   $text  .="<option  value='$row[code]'>$row[name]</option>";}
                }
               return $text;
            }

 
	


public function EditUserPermission($id) {
               

		$rows =array(100=>"Full",50=>"Semi",10=>"Customer Service");
		
		
               foreach($rows as $key=>$val){
                           
                   if($row['code']=="$id"){        
                   $text  .="<option SELECTED  value='$key'>$val</option>";
                   }else{                   $text  .="<option  value='$key'>$val</option>";}
                }
               return $text;
            }

               



               
}