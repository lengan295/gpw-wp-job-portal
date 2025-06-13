<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class wpjobportaldb {

    function __construct() {
        
    }

    public static function get_var($query) {
        $result = wpjobportal::$_db->get_var($query);
        if (wpjobportal::$_db->last_error != null) {
            WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError();
        }
        return $result;
    }

    function setQuery($query){
        $this->_query = $this->parseQuery($query);
    }

    public static function get_row($query) {
        $result = wpjobportal::$_db->get_row($query);
        if (wpjobportal::$_db->last_error != null) {
            WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError();
        }
        return $result;
    }

    public static function get_results($query) {
        $result = wpjobportal::$_db->get_results($query);
        if (wpjobportal::$_db->last_error != null) {
            WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError();
        }
        return $result;
    }

    private function parseQuery($query){
        $query = wpjobportalphplib::wpJP_str_replace('#__', $this->_db->prefix, $query);
        return $query;
    }

    public static function query($query) {
        $result = true;
        wpjobportal::$_db->query($query);
        if (wpjobportal::$_db->last_error != null) {
            WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError();
            $result = false;
        }
        return $result;
    }

}

?>
