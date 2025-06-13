<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALEncoder {

    private $securekey, $iv;

    function __construct($textkey) {
        if($textkey != ''){
            $this->securekey = hash('sha256', $textkey, TRUE);
        }else{
            $this->securekey = '';
        }

        $this->iv = mcrypt_create_iv(32);
    }

    function encrypt($input) {
        $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv);
        return wpjobportalphplib::wpJP_safe_encoding($output);

    }

    function decrypt($input) {
        $input = wpjobportalphplib::wpJP_safe_decoding($input);
        return wpjobportalphplib::wpJP_trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->securekey, $input, MCRYPT_MODE_ECB, $this->iv));
    }

}

?>
