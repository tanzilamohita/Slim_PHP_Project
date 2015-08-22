<?php

class flexiload extends L {
    public $amount;
    public $phone;
    public $type;
    public $user_id;
    public $user_level;
    public $success;
    public $fails;
    public $balance_field;
    public $comments;
    public $cut_balance_from_id;
    
    
    
    
    
    public function save() {
        
        $this->amount = abs($this->amount);
        
        
        
        if ($this->phone == "") {
            $this->setError("Please Give number");
        } elseif (strlen($this->phone) < 11) {
            $this->setError("Phone number must be 11 digit");
        } elseif ($this->amount < 10 || $this->amount > 1000) {
            $this->setError("Amount not valid");
        } elseif ($this->restrictDublicateLoad($this->phone) == 1) {
            $this->setError("You can not request same number within 15 minute.");
          
          
        } else {
            
            
            R::begin();
            try {
                
                foreach ($this->cut_balance_from_id as $boss_id) {
                    $current_balance = L::getBalance($boss_id, "flexiload");
                    if ($current_balance < $this->amount) {
                        $this->setError("You do not have sufficient amount in your account");
                    } else {
                        R::exec("UPDATE user  SET $this->balance_field=$this->balance_field-$this->amount where id='$boss_id'");
                        R::exec("CALL preparestatement($boss_id,$this->amount,$current_balance,$this->user_id,'credit','$this->comments')");
                   }
                    
                    
                }
                $flexiload                 = R::dispense("flexiload");
                $flexiload->phone          = $this->phone;
                $flexiload->balance        = $this->amount;
                $flexiload->load_type      = $this->type;
                $flexiload->user_id        = $this->user_id;
                $flexiload->s_date         = CURRENT_DT;
                $flexiload->status         = 'pending';
                $flexiload->s_time         = time();
                $flexiload->submitted_date = CURRENT_DTT;
                $flexiload->operator       = $this->getOperatorName($this->phone);
                R::store($flexiload);
                R::commit();
            }
            catch (Exception $e) {
                R::rollback();
                $this->setError("" . $e->getMessage());
            }
            
            
            
            
            
            
           
            
            
            
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