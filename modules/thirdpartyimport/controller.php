<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALthirdpartyimportController {
    private $_msgkey;
    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('thirdpartyimport')->getMessagekey();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'thirdpartyimport');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_importdata':
                    die("NOT ALLOWED !!");
                    $selected_plugin = WPJOBPORTALrequest::getVar('selected_plugin', '', 0);
                    wpjobportal::$_data['count_for'] = $selected_plugin;
                    if($selected_plugin != 0){
                        // prepare data for selected plugin
                        WPJOBPORTALincluder::getJSModel('thirdpartyimport')->getJobManagerDataStats($selected_plugin);
                    }
                    // no plugin selected
                    break;
                case 'admin_importresult':
                    die("NOT ALLOWED !!");
                    break;
                default:
                    return;
            }
            $module = 'page';
            $module = WPJOBPORTALrequest::getVar($module, null, 'thirdpartyimport');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($layout, $module);
        }
    }

    function canaddfile($layout) {
        $nonce_value = WPJOBPORTALrequest::getVar('wpjobportal_nonce');
        if ( wp_verify_nonce( $nonce_value, 'wpjobportal_nonce') ) {
            if (isset($_POST['form_request']) && $_POST['form_request'] == 'wpjobportal')
                return false;
            elseif (isset($_GET['action']) && $_GET['action'] == 'wpjobportaltask')
                return false;
            else{
                if(!is_admin() && strpos($layout, 'admin_') === 0){
                    return false;
                }
                return true;
            }
        }
    }


    function importjobmanagerdata() {
        die("NOT ALLOWED !!");
        // $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        // if (! wp_verify_nonce( $nonce, 'wpjobportal_job_manager_import_nonce') ) {
        //      die( 'Security check Failed' );
        // }

        if (!current_user_can('manage_options')) { //only admin can change it.
            return false;
        }
        $selected_plugin = WPJOBPORTALrequest::getVar('selected_plugin', '', 0);
        if($selected_plugin == 1){
            $result = WPJOBPORTALincluder::getJSModel('thirdpartyimport')->importJobManagerData();
            $msg = WPJOBPORTALMessages::getMessage($result, "thirdpartyimport");
            WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        }
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_thirdpartyimport&wpjobportallt=importresult"));
        wp_redirect($url);
        die();
    }

    function getjobmanagerdatastats() {
        die("NOT ALLOWED !!");
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_manager_import_nonce') ) {
            die( 'Security check Failed' );
        }

        if (!current_user_can('manage_options')) { //only admin can change it.
            return false;
        }
        $result = WPJOBPORTALincluder::getJSModel('thirdpartyimport')->getJobManagerDataStats();
        echo var_dump($result);
        die('in thirdpartyimport controller');
        $msg = WPJOBPORTALMessages::getMessage($result, "thirdpartyimport");
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_thirdpartyimport&wpjobportallt=importresult"));
        wp_redirect($url);
        die();
    }
}

$WPJOBPORTALthirdpartyimportController = new WPJOBPORTALthirdpartyimportController();
?>
