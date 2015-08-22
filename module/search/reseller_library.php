<?php

class reseller extends L {
    
    
    public $fails;
    
    
    
    
    public function save() {
        
        GUMP::add_validator("unique", function($field, $input, $param = NULL) {
            $checkExistingUser = R::findOne('user', 'user=?', array(
                $input
            ));
            if ($checkExistingUser == NULL) {
                return FALSE;
            } else {
                return TRUE;
            }
        });
        
        
        GUMP::add_validator("strong", function($field, $input, $param = NULL) {
            return checkPasswordStrength($input);
        });
        
        
        
        
        
        $rules = array(
            'reseller_username' => 'required|alpha_numeric|max_len,10|min_len,6|unique',
            'reseller_password' => 'required|max_len,10|min_len,7|strong'
        );
        
        $filters = array(
            'reseller_username' => 'trim|sanitize_string',
            'reseller_password' => 'trim|sanitize_string|md5'
            
        );
        
        
        $app        = Slim::getInstance();
        $post       = $app->request()->post(); // $app - Slim main app instance
        $postValues = $gump->filter($post, $filters);
        
        $validated = $gump->validate($gump->filter($postValues, $filters), $rules);
        
        if ($validated === TRUE) {
            $createUser       = R::dispense('user');
            $createUser->user = $postValues['reseller_username'];
            $createUser->user = $postValues['reseller_password'];
        } else {
            $this->setError($gump->get_readable_errors(true));
            
        }
        
        
        if ($this->getError() == "") {
            $this->fails = FALSE;
        } else {
            $this->fails = TRUE;
        }
        
        
        
    }
    
    
    public function fails() {
        return $this->fails;
    }
    
    
    
    
    
}