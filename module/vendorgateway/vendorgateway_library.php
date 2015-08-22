<?php

class vendorgateway extends L {
    
    

    public $form_post_value;
    public $validated_data;
    
    
    
    public function save() 
	{

		if($this->is_validated()){
			$vendorgateway= R::dispense("vendorgateway");

			$vendorgateway->vendortitle     = $this->validated_data['gateway_vendor_id'];
			$vendorgateway->gatewaytitle    = $this->validated_data['gateway_name'];						
			$vendorgateway->note        	= $this->validated_data['gateway_notes'];
			$vendorgateway->createdate  	= CURRENT_DTT;
			$vendorgateway->updatedate  	= CURRENT_DTT;
			$vendorgateway->createby 		= 1001;
			$vendorgateway->isactive  		= 1;
			$id = R::store($vendorgateway);
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
        $rules 	= array(
            'gateway_vendor_id' 	=> 'required',
            'gateway_name' 			=> 'required'            
        );
        
        $filters = array(
            'gateway_vendor_id' 	=> 'trim|sanitize_string',
            'gateway_name' 	 		=> 'trim|sanitize_string',            
			'show_gateway_id' 	    => 'trim',			
			'gateway_notes' 	    => 'noise_words'
			
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
	*Get vendorgateway List
	*
	*/
	
		public function getVendorgatewayList()
		{
            $rows = R::getAll("SELECT *FROM vendorgateway order by id DESC limit 200 ");
			foreach($rows as $row){
			//$row['accesslevel'] = L::getAccessLevelFromId($row['accesslevel']);
		    $data[]=$row;
			}
			return $data;
		}
		
		public function getVendor(){
		return R::getAll("select * from vendor");
		}
}