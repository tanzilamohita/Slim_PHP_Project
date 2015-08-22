<?php

class vendor extends L {
    
    

    public $form_post_value;
    public $validated_data;
    
    
    
    public function save() 
	{
		if($this->is_validated()){
			
			if($this->isDublicateVendor($this->validated_data['user_name'])){
				$this->setError('Duplicate vendor username, Please choose another');
			}
			elseif($this->isNotStrongPassword($this->validated_data['user_password'])){
			 $this->setError('Please choose strong password');   
			}
			elseif($this->validated_data['user_password'] != $this->validated_data['user_confirm_password']){
				$this->setError('Password is not matched');
			}
			else{
			
				
				$vendor= R::dispense("user");

				$vendor->fullname      	= $this->validated_data['vendor_title'];
				$vendor->basecurrency   = $this->validated_data['user_currency'];
				$vendor->username 		= $this->validated_data['user_name'];
				$vendor->password    	= md5(SALT.$this->validated_data['user_password']);
				$vendor->contactperson  = $this->validated_data['vendor_contact_person'];
				$vendor->mobile        	= $this->validated_data['user_mobile'];
				$vendor->email          = $this->validated_data['user_email'];
				$vendor->vendorwebsite  = $this->validated_data['vendor_website'];
				$vendor->note        	= $this->validated_data['user_notes'];
				$vendor->type  			= 2;
				$vendor->createdate  	= CURRENT_DTT;
				$vendor->updatedate  	= CURRENT_DTT;
				$vendor->createby 		= 1001;
				$vendor->isactive  		= 1;
				$id = R::store($vendor);
				
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
            'vendor_title' 			=> 'required',			
            'user_currency' 		=> 'required',
			'user_name' 			=> 'required',
            'user_password' 		=> 'required',
            'user_confirm_password' => 'required',
            'vendor_contact_person' => 'required',
            'user_mobile' 			=> 'required',
    
        );
        
        $filters = array(
            'vendor_title' 				=> 'trim|sanitize_string',
            'user_currency' 	 		=> 'trim|sanitize_string',
            'user_name' 				=> 'trim|sanitize_string',
			'user_password'   			=> 'trim',
			'user_confirm_password' 	=> 'trim',
			'vendor_contact_person' 	=> 'trim',
			'user_mobile' 				=> 'trim',
			'user_email' 				=> 'trim|sanitize_email',
			'vendor_website' 			=> 'trim',
			'user_notes' 	   			=> 'noise_words'
        );
		
		$validated_data = $gump->run($_POST);
		if($validated_data === false) {
			$this->error = $gump->get_readable_errors(true);
		} else {
			$this->validated_data=$validated_data;
				return TRUE;
			
		}


	}
	
	/*
		*Get vendor List
		*
	*/
	
		public function getVendorList()
		{
			
            $rows = R::getAll("SELECT *FROM user where type= 2 and isactive = 1 order by id DESC limit 200 ");
			foreach($rows as $row){
			$row['currency'] = L::getBaseCurrencyFromValue($row['currency']);
		    $data[]=$row;
			}
			return $data;
		}
}