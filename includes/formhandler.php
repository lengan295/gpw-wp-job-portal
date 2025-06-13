<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALformhandler {

    function __construct() {
        add_action('init', array($this, 'checkFormRequest'));
        add_action('init', array($this, 'checkDeleteRequest'));
    }

    /*
     * Handle Form request
     */

    function checkFormRequest() {
        $formrequest = WPJOBPORTALrequest::getVar('form_request', 'post');
        if ($formrequest == 'wpjobportal') {
            //handle the request
            $modulename = (is_admin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($modulename);
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
            $class = 'WPJOBPORTAL' . $module . "Controller";
            $task = WPJOBPORTALrequest::getVar('task');
            $obj = new $class;
            $obj->$task();
        }
    }

    /*
     * Handle Form request
     */

    function checkDeleteRequest() {
        $wpjobportal_action = WPJOBPORTALrequest::getVar('action', 'get');
        if ($wpjobportal_action == 'wpjobportaltask') {
            //handle the request
            $modulename = (is_admin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($modulename);
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
            $class = 'WPJOBPORTAL' . $module . "Controller";
            $action = WPJOBPORTALrequest::getVar('task');
            $obj = new $class;
            $obj->$action();
        }
    }

}

$formhandler = new WPJOBPORTALformhandler();
?>
