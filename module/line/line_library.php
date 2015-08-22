<?php

class line extends L {
    
    

    public $form_post_value;
    public $validated_data;

    /*
	*Function to save line account data
	*/
    public function save() 
	{

		if($this->is_validated()){

			//print_r($this->validated_data);
			$line= R::dispense("line");
			// echo $this->validated_data['user_full_name'];
			// exit;
			
			$line->linetitle       	= $this->validated_data['line_name'];
			$line->vsrlink    		= $this->validated_data['line_cpanel_url'];
			$line->createdate  		= CURRENT_DTT;
			$line->updatedate  		= CURRENT_DTT;
			$line->createby 		= 1001;
			$line->isactive  		= 0;
			$id = R::store($line);			
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
            'line_name' 		=> 'required',
            'line_cpanel_url' 	=> 'required',
        );
        
        $filters = array(
            'line_name' 		=> 'trim|sanitize_string',
            'line_cpanel_url' 	=> 'trim',
			
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
	 
	public function getlineList(){
		return R::getAll("select * from line");
	}	
}