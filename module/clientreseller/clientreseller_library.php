<?php

class clientreseller extends L {
    
    

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
					
						
						$clientreseller= R::dispense("user");

						$clientreseller->fullname    	= $this->validated_data['reseller_user_id'];
						$clientreseller->linetitle    	= $this->validated_data['reseller_line_id'];						
						$clientreseller->currencyrate 	= $this->validated_data['reseller_currency_conversion_rate'];
						$clientreseller->username    	= $this->validated_data['reseller_cpanel_user_name'];
						$clientreseller->password    	= md5(SALT.$this->validated_data['reseller_cpanel_password']);
						$clientreseller->resellerlevel  = $this->validated_data['reseller_level'];					
						$clientreseller->note        	= $this->validated_data['reseller_notes'];
						$clientreseller->type  			= 4;						
						$clientreseller->createdate  	= CURRENT_DTT;
						$clientreseller->updatedate  	= CURRENT_DTT;
						$clientreseller->createby 		= 1001;
						$clientreseller->isactive  		= 1;
						$clientresellerid = R::store($clientreseller);
						
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
            'reseller_user_id' 					=> 'required',
            'reseller_line_id' 					=> 'required',
            'reseller_currency_conversion_rate' => 'required',
            'reseller_cpanel_user_name' 		=> 'required' 
        );
        
        $filters = array(
            'reseller_user_id' 	         		=> 'trim|sanitize_string',
            'reseller_line_id'		     		=> 'trim|sanitize_string',
            'reseller_cpanel_password' 	 		=> 'trim|sanitize_string',
			'reseller_cpanel_user_name'  		=> 'trim',
			'reseller_currency_conversion_rate'	=> 'trim',
			'reseller_level' 	    			=> 'trim',			
			'reseller_notes' 	    			=> 'noise_words'
			
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
		*Get clientreseller List
		*
	*/
	
		public function getClientResellerList()
		{
            $rows = R::getAll("SELECT * FROM user where type = 4 and isactive = 1 order by id DESC limit 200 ");
			foreach($rows as $row){
			//$row['accesslevel'] = L::getAccessLevelFromId($row['accesslevel']);
		    $data[]=$row;
			}
			return $data;
		}
		
		public function getClientName(){
			return R::getAll("select * from user where type=3");
		}
		
		public function getLineName(){
			return R::getAll("select * from line");
		}
		
		public function getOptionValueWithLinkAndLine(){
		$rows= R::getAll("SELECT  * FROM  line");
		foreach($rows as $row){
			$data  .="clientinfo['$row[linetitle]'] = '$row[vsrlink]';\n";
		}
		return $data;
	}
}