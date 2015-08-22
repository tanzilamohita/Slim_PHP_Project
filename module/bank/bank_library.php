<?php

class bank extends L {
    
    

    public $form_post_value;
    public $form_post_values;
    public $validated_data;
    public $validated_datas;
    
    
	public function saveExpense() 
	{
		if($this->is_validatedExpense()){

			//print_r($this->validated_data);
			$expense= R::dispense("expense");
			// echo $this->validated_data['user_full_name'];
			// exit; 
			
			$expense->bankname       	= $this->validated_datas['transaction_bank_account_id'];
			$expense->expensetype    	= $this->validated_datas['transaction_bank_transaction_id'];
			$expense->transactiondate   = $this->validated_datas['transaction_time'];
			$expense->amount 			= $this->validated_datas['transaction_amount'];
			$expense->currencyamount    = $this->validated_datas['transaction_amount_paid'];
			$expense->note    			= $this->validated_datas['transaction_notes'];
			//$expense->image    			= $this->validated_data['account_contact_number'];
			$expense->createdate  		= CURRENT_DTT;
			$expense->updatedate  		= CURRENT_DTT;
			$expense->createby 			= 1001;
			$expense->isactive  		= 1;
			$id = R::store($expense);
			
		}
		if ($this->getError() == "") {$this->fails = FALSE;} else {$this->fails = TRUE;}

    }
	
	
    /*
	*Function to save bank account data
	*/
    public function save() 
	{

		if($this->is_validated()){

			//print_r($this->validated_data);
			$bank= R::dispense("bank");
			// echo $this->validated_data['user_full_name'];
			// exit;
			
			$bank->bankname       	= $this->validated_data['account_bank_name'];
			$bank->accountname    	= $this->validated_data['account_name'];
			$bank->accountnumber   	= $this->validated_data['account_number'];
			$bank->branchcountry 	= $this->validated_data['account_country_code'];
			$bank->address    		= $this->validated_data['account_address'];
			$bank->bankcurrency    	= $this->validated_data['account_currency'];
			$bank->contactperson    = $this->validated_data['account_contact_person'];
			$bank->contactnumber    = $this->validated_data['account_contact_number'];
			$bank->note        		= $this->validated_data['account_notes'];
			$bank->createdate  		= CURRENT_DTT;
			$bank->updatedate  		= CURRENT_DTT;
			$bank->createby 		= 1001;
			$bank->isactive  		= 1;
			$id = R::store($bank);
			
			//echo $bankid;
				
			/*
$foo = new Upload($_FILES['form_picture_field']); 
if ($foo->uploaded) {
   $foo->Process('/home/user/files/');
   if ($foo->processed) {
     echo 'original image copied';
   } else {
     echo 'error : ' . $foo->error;
   }
   // save uploaded image with a new name
   $foo->file_new_name_body = 'foo';
   $foo->Process('/home/user/files/');
   if ($foo->processed) {
     echo 'image renamed "foo" copied';
   } else {
     echo 'error : ' . $foo->error;
   }   
   // save uploaded image with a new name,
   // resized to 100px wide
   $foo->file_new_name_body = 'image_resized';
   $foo->image_resize = true;
   $foo->image_convert = gif;
   $foo->image_x = 100;
   $foo->image_ratio_y = true;
   $foo->Process('/home/user/files/');
   if ($foo->processed) {
     echo 'image renamed, resized x=100
           and converted to GIF';
     $foo->Clean();
   } else {
     echo 'error : ' . $foo->error;
   } 
}  
http://www.verot.net/php_class_upload.htm

*/
				

			
				
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
            'account_bank_name' 				=> 'required',
            'account_name' 						=> 'required',
            'account_number' 					=> 'required',
            'account_country_code' 				=> 'required',
            'account_currency' 					=> 'required',	
        );
        
        $filters = array(
            'account_bank_name' 				=> 'trim|sanitize_string',
            'account_name' 	 					=> 'trim|sanitize_string',
            'account_number' 					=> 'trim|sanitize_string',
			'account_country_code'  			=> 'trim',
			'account_address' 					=> 'trim',
			'account_currency' 	   				=> 'trim',
			'account_contact_person' 			=> 'trim',
			'account_notes' 	    			=> 'noise_words',                         
        );
		
		$validated_data = $gump->run($_POST);
		if($validated_data === false) {
			$this->error = $gump->get_readable_errors(true);
		} 
		else{
			$this->validated_data=$validated_data;
			return TRUE;
		}
	}
	 
	public  function is_validatedExpense(){
		$gump   = new  \GUMP\GUMP;
        $_POST  = $gump->sanitize($this->form_post_values); 
        $rules = array(
			'transaction_bank_account_id'		=> 'required',
			'transaction_bank_transaction_id'   => 'required',
			'transaction_time'                  => 'required',
			'transaction_amount'                => 'required',
			'transaction_amount_paid'           => 'required',		
        );
        
        $filters = array(
			'transaction_bank_account_id'		=> 'trim',
			'transaction_bank_transaction_id'   => 'trim',
			'transaction_time'                  => 'trim',
			'transaction_amount'                => 'trim',
			'transaction_amount_paid'       	=> 'trim',
			'transaction_notes'				    => 'noise_words',                          
        );
		
		$validated_datas = $gump->run($_POST);
		if($validated_datas === false) {
			$this->error = $gump->get_readable_errors(true);
		} 
		else{
			$this->validated_datas=$validated_datas;
			return TRUE;
		}
	}
	
	
	public function getBankName(){
		return R::getAll("select * from bank where isactive = 1 order by id DESC limit 500");
	}
	
	public function getBankExpense(){
		return R::getAll("select * from expense");
	}
	
	public function getOptionValueWithRateAndBankName(){
		$rows= R::getAll("SELECT  bank.bankname as bankname, currency.rate as rate
					FROM  bank , currency WHERE bank.bankcurrency = currency.code");
		foreach($rows as $row){
			$data  .="bankinfo['$row[bankname]'] = $row[rate];\n";
		}
		return $data;
	}
	
	//showing data of bankaccount by clicking in view
	public function getBankInfo($id){
		$id = (int)L::decryptData($id); 
        $rows = R::getAll("SELECT *FROM bank where id=?",array($id));
  	    foreach($rows as $row){
			$data[]=$row;
	    }
		return $data;
	}
	
	public function updateBankStatus($id){
		$data= R::load("bank",(int) L::decryptData($id));
		$data->isactive = 0;           
		if ($data->id == 0) {
		$this-setError("Bank Account Not Found");
		}
		else{
		R::store($data);
        }
                    
        if ($this->getError() == "") {$this->fails = FALSE;} else {$this->fails = TRUE;}
	}
		
		
}