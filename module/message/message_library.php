<?php

class message extends L {
    
    

    public $form_post_value;
    public $validated_data;
    
    
    
    public function save() 
	{

			if($this->is_validated()){
				
					if($this->isDublicateUser($this->validated_data['user_name'])){
						$this->setError('Duplicate username,Please choose another');
					}
					elseif($this->isNotStrongPassword($this->validated_data['user_password'])){
					 $this->setError('Please choose strong password');   
					}
					else{
					
						print_r($this->validated_data);
						$message= R::dispense("messageinfo");
						echo $this->validated_data['user_full_name'];
						exit;
						$message->fullname       	= $this->validated_data['user_full_name'];
						$message->username    	= $this->validated_data['user_name'];
						$message->password    	= md5(SALT.$this->validated_data['user_password']);
						$message->accesslevel 	= $this->validated_data['user_permission'];
						$message->currency    	= $this->validated_data['user_currency'];
						$message->email       	= $this->validated_data['user_email'];
						$message->phone       	= $this->validated_data['user_mobile'];
						$message->note        	= $this->validated_data['user_notes'];
						$message->createdate  	= CURRENT_DTT;
						$message->updatedate  	= CURRENT_DTT;
						$message->createby 		= 1001;
						$message->isactive  		= 0;
						$id = R::store($message);
						echo $id;
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
        $rules = array(
            'user_full_name' 	=> 'required',
            'user_name' 		=> 'required',
            'user_password' 	=> 'required',
            'user_permission' 	=> 'required',
            'user_currency' 	=> 'required',
            'user_mobile' 		=> 'required|max_len,11|min_len,11',
            'user_email' 		=> 'required|valid_email',
            'user_notes' 		=> 'required|max_len,500'
        );
        
        $filters = array(
            'user_full_name' 	=> 'trim|sanitize_string',
            'user_name' 	 	=> 'trim|sanitize_string',
            'user_password' 	=> 'trim|sanitize_string',
			'user_permission'   => 'trim',
			'user_currency' 	=> 'trim',
			'user_mobile' 	    => 'trim',
			'user_email' 		=> 'trim|sanitize_email',
			'user_notes' 	    => 'noise_words'
			
        );
		
		$validated_data = $gump->run($_POST);
		if($validated_data === false) {
			$this->error = $gump->get_readable_errors(true);
		} else {
			$this->validated_data[]=$validated_data;
				return TRUE;
			
		}


			}
}