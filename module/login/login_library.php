<?php

    /**
     * @abstract 
     * @example path authentication(username,password) 
     */
    class login {
        public $rows;

        public function authentication($formUserName, $formUserPass) {

            $formUserName = filter_var($formUserName, FILTER_SANITIZE_STRING);
            $formUserPass = filter_var($formUserPass, FILTER_SANITIZE_STRING);
            $dbMatchRows  = R::findOne('admininfo', 'username=?', array($formUserName));
            $allowLogin   = "no";



            if ($dbMatchRows != NULL) {
                $dbUserPass       = $dbMatchRows->password;
                $dbUserStatus     = ($dbMatchRows->isactive == 1) ? 1 : 0;
                $saltFormUserPass = $this->loginSalt($formUserPass);


                if ($dbUserPass == $saltFormUserPass && $dbUserStatus == 1) {
                    $allowLogin = "yes";
                    $this->rows = $dbMatchRows;
                }
                else {
                    $allowLogin = "no";
                }
            }
            return $allowLogin;
        }
        public function loginSuccess($form_user) {


            $rows = $this->rows;

            $usid            = $rows->id;
            $usname          = $rows->username;
            $ussessuservalid = md5($usname . $_SERVER['REMOTE_ADDR'] . session_id());
            $_SESSION        = array();
            L::setSessionValue("sess_uservalid", $ussessuservalid);
            L::setSessionValue("usname", $usname);
            L::setSessionValue("usid", $usid);
        }
        private function loginSalt($value) {
            return md5($value);
        }
        public function validiateLogin() {

            return TRUE;
        }
    }
?>


