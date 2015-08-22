<?php
    use \gump\GUMP as validator;

    class client extends L {
        public $form_post_value;
        public $validated_data;

        public function __construct($POST) {
            $this->form_post_value = $POST;
            validator::xss_clean($this->form_post_value);
        }
        public function save() {

            if ($this->is_validated()) {

                if ($this->isDublicateBillingUser($this->validated_data['user_name'])) {
                    $this->setError('Duplicate Username, Please choose another');
                }
                elseif ($this->isNotStrongPassword($this->validated_data['user_password'])) {
                    $this->setError('Please choose strong password');
                }
                else if ($this->validated_data['user_password'] != $this->validated_data['user_confirm_password']) {
                    $this->setError('Confirm password is not matched');
                }
                else {

                    $client = R::dispense("user");

                    $client->fullname   = $this->validated_data['user_full_name'];
                    $client->username   = $this->validated_data['user_name'];
                    $client->password   = md5(SALT . $this->validated_data['user_password']);
                    $client->duelimit   = $this->validated_data['user_credit_limit'];
                    $client->extralimit = $this->validated_data['user_extra_credit'];
                    $client->type       = 'client';
                    $client->email      = $this->validated_data['user_email'];
                    $client->mobile     = $this->validated_data['user_mobile'];
                    $client->note       = $this->validated_data['user_notes'];
                    $client->createdate = CURRENT_DTT;
                    $client->updatedate = CURRENT_DTT;
                    $client->createby   = 1001;
                    $client->isactive   = 1;
                    $clientid           = R::store($client);
                }

                if ($this->getError() == "") {
                    $this->fails = FALSE;
                }
                else {
                    $this->fails = TRUE;
                }
            }
        }
        /*         * ******************************saveAddBalance********************************************** */
        public function saveAddBalance() {

            $is_valid = validator::is_valid($this->form_post_value, array(
                            'transaction_amount' => 'required'
                            
            ));

            if ($is_valid === true) {
                $payment = new managePayment;
                $payment->setAmount($this->form_post_value['transaction_amount_paid']);
                $payment->setType("add");
                $payment->setPurpose($this->form_post_value["purpose"]);
              //  $payment->setPaidBy(L::getSessionValue(1));
                  $payment->setPaidBy(1);
                $payment->setTransactionDate($this->form_post_value['transaction_date']);
                $payment->setPurpose($this->form_post_value['purpose']);
                $payment->setPaidTo($this->form_post_value['paidto']);
                $payment->makePayment();
            }
            else {
                $this->setError($is_valid[0]);
            }

             if ($this->getError() == "") {
                    $this->fails = FALSE;
                }
                else {
                    $this->fails = TRUE;
                }

           
        }
        //|alpha_numeric|max_len,10|min_len,6 
        public function fails() {
            return $this->fails;
        }
       
        /*
         * Get client List
         *
         */
        public function getClientList() {
            $rows = R::getAll("SELECT * FROM user where type= 3 and isactive = 1 order by id DESC limit 200 ");
            foreach ($rows as $row) {
                //$row['accesslevel'] = L::getAccessLevelFromId($row['accesslevel']);
                $data[] = $row;
            }
            return $data;
        }
        public function sumDueLimit() {
            //ARRE KORSILAM>> USE KROI NAI< KETE DIBO PORE
            return $rows = R::exec("SELECT SUM(dueLimit) as due FROM client order by id DESC limit 200 ");
        }
        public function getClientName() {
            return R::getAll("select * from user where type=3");
        }
        public function getClientResellerName() {
            return R::getAll("select * from clientreseller");
        }
        public function getLineName() {
            return R::getAll("select linetitle from line");
        }
        public function getOptionValueWithClientName() {
            $rows = R::getAll("select * from clientreseller");
            $data = "";
            foreach ($rows as $row) {
                $data .="reseller['$row[username]'] = $row[currencyrate];\n";
            }
            return $data;
        }
        public function getOptionValueWithLinkAndLine() {
            $rows     = R::getAll("SELECT  * FROM  line");
            $dataline = "";
            foreach ($rows as $row) {
                $dataline .="clientinfo['$row[linetitle]'] = '$row[vsrlink]';\n";
            }
            return $dataline;
        }
    }    