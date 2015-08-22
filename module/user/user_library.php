<?php

class user extends L {

    public $form_post_value;
    public $validated_data;
    
    
  /************************************* Save or Insert Operation ******************************************     */  
    public function save(){

			if($this->is_validated()){
				
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
						
						$user = R::dispense("user");

						$user->fullname    		= $this->validated_data['user_full_name'];
						$user->username    		= $this->validated_data['user_name'];
						$user->password    		= md5(SALT.$this->validated_data['user_password']);
						$user->type 			= $this->validated_data['user_type'];
						$user->accesslevel 		= $this->validated_data['user_permission'];
						$user->basecurrency    	= $this->validated_data['user_currency'];
						$user->email       		= $this->validated_data['user_email'];
						$user->mobile       	= $this->validated_data['user_mobile'];
						$user->contactperson    = $this->validated_data['vendor_contact_person'];
						$user->vendorwebsite    = $this->validated_data['vendor_website'];
						$user->duelimit       	= $this->validated_data['user_credit_limit'];
						$user->extralimit       = $this->validated_data['user_extra_credit'];
						$user->note        		= $this->validated_data['user_notes'];
						$user->createdate  		= CURRENT_DTT;
						$user->updatedate  		= CURRENT_DTT;
						$user->createby 		= 1001;
						$user->isactive  		= 1;
						$id = R::store($user);
						
					}
		
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
			'user_type' 			=> 'required',
            'user_permission' 		=> 'required',
            'user_currency' 		=> 'required',
			'user_credit_limit' 	=> 'required',
			'user_extra_credit' 	=> 'required',
            'user_mobile' 			=> 'required|max_len,11|min_len,11',
            'user_email' 			=> 'required|valid_email',
        );
        
        $filters = array(
            'user_full_name' 	    => 'trim|sanitize_string',
            'user_name' 			=> 'trim|sanitize_string',
            'user_password' 	    => 'trim|sanitize_string',
            'user_confirm_password' => 'trim|sanitize_string',
			'user_type'			=> 'trim',
			'user_permission'		=> 'trim',
			'user_currency' 		=> 'trim',
			'user_credit_limit' 	=> 'trim',
			'user_extra_credit' 	=> 'trim',
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
}