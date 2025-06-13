<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALwpjpsession {

    public $sessionid;
    public $sessionexpire;
    private $sessiondata;
    private $datafor;
    private $nextsessionexpire;

    function __construct( ) {
        $this->init();
    }

    function getSessionId(){
        return $this->sessionid;
    }

    function init(){
        if (isset($_COOKIE['_wpjsjp_session_'])) {
            $cookie = wpjobportalphplib::wpJP_stripslashes(wpjobportal::wpjobportal_sanitizeData($_COOKIE['_wpjsjp_session_']));
            $user_cookie = wpjobportalphplib::wpJP_explode('/', $cookie);
            $this->sessionid = wpjobportalphplib::wpJP_preg_replace("/[^A-Za-z0-9_]/", '', $user_cookie[0]);
            $this->sessionexpire = absint($user_cookie[1]);
            $this->nextsessionexpire = absint($user_cookie[2]);
            // Update options session expiration
            if (time() > $this->nextsessionexpire) {
                $this->jsjp_set_cookies_expiration();
            }
        } else {
            $sessionid = $this->jsjp_generate_id();
            $this->sessionid = $sessionid . get_option( '_wpjsjp_session_', 0 );
            $this->jsjp_set_cookies_expiration();
        }
        $this->jshd_set_user_cookies();
        return $this->sessionid;
    }

    private function jsjp_set_cookies_expiration(){
        $this->sessionexpire = time() + (int)(30*60);
        $this->nextsessionexpire = time() + (int)(60*60);
    }

    private function jsjp_generate_id(){
        do_action('wpjobportal_load_phpass');

        $hash = new PasswordHash( 16, false );

        return wpjobportalphplib::wpJP_md5( $hash->get_random_bytes( 32 ) );
    }

    private function jshd_set_user_cookies(){
        wpjobportalphplib::wpJP_setcookie( '_wpjsjp_session_', $this->sessionid . '/' . $this->sessionexpire . '/' . $this->nextsessionexpire , $this->sessionexpire, COOKIEPATH, COOKIE_DOMAIN);
        $count = get_option( '_wpjsjp_session_', 0 );
        update_option( '_wpjsjp_session_', ++$count);
    }

}

?>
