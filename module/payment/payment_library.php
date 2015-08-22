<?php

class addPaymentToFlexiAccount extends L {
    
    
    public  $fails;
    public  $postarray;
    public  $comments;
    private $who_is_getting_paid;
    public  $who_is_paying;
    public  $payment_type;


    
    
    
    public function save() {
        
        
        $rules = array(
             'customer_id'          => 'required|'
            ,'payment_amount'       => 'required|numeric'
            ,'payment_memo'         => 'required'
           // ,'payment_status'       => 'required|numeric'
        );
        
        $filters = array(
            'payment_memo'      => 'trim|sanitize_string'
            ,'payment_amount'    => 'trim|sanitize_string|numeric'
            ,'customer_id'       => 'trim|numeric'
            
        );
        
        $postValues                 = $gump->filter($this->$postarray, $filters);
        $validated                  = $gump->validate($gump->filter($postValues, $filters), $rules);
        $this->payment_amount       = $postValues['payment_amount'];
        $this->payment_memo         = $postValues['payment_memo'];
        $this->who_is_getting_paid  = $postValues['customer_id'];



        if ($validated === TRUE) 
        {
            R::begin();
        
        
             try {
                 /*Payment Add*/
                 $payment = R::findOne('user',' id = ? and created_by=? ',array($this->who_is_getting_paid,$this->who_is_paying));
                 $current_balance   = $payment->balance2;



                 $reseller_balance            = R:: dispense("reseller_balance");
                 $reseller_balance->user_id   = $this->who_is_getting_paid;
                 $reseller_balance->amount    = $this->$payment_amount;
                 $reseller_balance->load_by   = $this->who_is_paying;
                 $reseller_balance->note      = $this->comments;
                 $reseller_balance->ip        = $_SERVER["REMOTE_ADDR"];
                 $reseller_balance->updated   = CURRENT_DTT;





                 if($this->payment_type=="add")
                 {
                     $payment->balance2 = $payment->balance2+$this->payment_amount;
                     $payment->return_payment=0; //add
                     R::exec("CALL preparestatement($this->who_is_paying,$this->payment_amount,$current_balance,$this->who_is_getting_paid,'debit','$this->payment_memo')");


                 }
                 elseif($this->payment_type=="return")
                 {
                     $payment->balance2 = $payment->balance2-$this->payment_amount;
                     $payment->return_payment=1; //return
                     R::exec("CALL preparestatement($this->who_is_paying,$this->payment_amount,$current_balance,$this->who_is_getting_paid,'credit','$this->payment_memo')");

                 }

                 R::save($payment);
                 R::save($reseller_balance);
                 R::commit();
                }


            catch (Exception $e) {
                R::rollback();
                $this->setError("" . $e->getMessage());
            }
            
         }

        else
        {
          $this->setError($gump->get_readable_errors(true));
            
        }
        
        if ($this->getError() == "") { $this->fails = FALSE;} else { $this->fails = TRUE; }

    }
    

    
    
    
    
}