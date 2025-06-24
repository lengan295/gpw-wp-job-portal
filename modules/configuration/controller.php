<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALConfigurationController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('configuration')->getMessagekey();        
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'configurations');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_configurations':
                case 'admin_configurationsemployer':
                case 'admin_configurationsjobseeker':
                    $wpjpconfigid = WPJOBPORTALrequest::getVar('wpjpconfigid');
                    if (isset($wpjpconfigid)) {
                        wpjobportal::$_data['wpjpconfigid'] = $wpjpconfigid;
                    } else {
                        wpjobportal::$_data['wpjpconfigid'] = 'general_setting';
                    }
                    WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationsForForm();
                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'configuration');
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

    function saveconfiguration() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_configuration_nonce') ) {
             die( 'Security check Failed' );
        }
        if (!current_user_can('manage_options')) { //only admin can change it.
            return false;
        }
        $data = WPJOBPORTALrequest::get('post');
        $layout = WPJOBPORTALrequest::getVar('wpjobportallt');
        $result = WPJOBPORTALincluder::getJSModel('configuration')->storeConfig($data);
        $msg = WPJOBPORTALMessages::getMessage($result, "configuration");
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if ($layout == 'configurationsjobseeker') {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_configuration&wpjobportallt=" . esc_attr($layout)."&wpjpconfigid=jobseeker_general_setting#js_generalsetting"));
        } elseif ($layout == 'configurationsemployer') {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_configuration&wpjobportallt=" . esc_attr($layout)."&wpjpconfigid=emp_general_setting#emp_generalsetting"));
        } else {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_configuration&wpjobportallt=" . esc_attr($layout)."&wpjpconfigid=general_setting#site_setting"));
        }
        
        wp_redirect($url);
        die();
    }

    // function to handle auto update configuration
    function saveautoupdateconfiguration() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_configuration_nonce') ) {
             die( 'Security check Failed' );
        }
        if (!current_user_can('manage_options')) { //only admin can change it.
            return false;
        }
        $result = WPJOBPORTALincluder::getJSModel('configuration')->storeAutoUpdateConfig();
        $msg = WPJOBPORTALMessages::getMessage($result, "configuration");
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal&wpjobportallt=addonstatus"));
        wp_redirect($url);
        die();
    }


}

$WPJOBPORTALConfigurationController = new WPJOBPORTALConfigurationController();
?>
