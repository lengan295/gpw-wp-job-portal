<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALemailtemplatestatusController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'emailtemplatestatus');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_emailtemplatestatus':
                    WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatusData();
                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'emailtemplatestatus');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($layout, $module);
        }
    }

    function sendEmail() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_emailstatus_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $id = WPJOBPORTALrequest::getVar('wpjobportalid');
        $action = WPJOBPORTALrequest::getVar('actionfor');
        WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->sendEmailModel($id, $action); //  for send email
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_emailtemplatestatus"));
        wp_redirect($url);
        die();
    }

    function noSendEmail() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_emailstatus_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $id = WPJOBPORTALrequest::getVar('wpjobportalid');
        $action = WPJOBPORTALrequest::getVar('actionfor');
        WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->noSendEmailModel($id, $action); //  for notsendemail
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_emailtemplatestatus"));
        wp_redirect($url);
        die();
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

}

$WPJOBPORTALEmailtemplatestatusController = new WPJOBPORTALEmailtemplatestatusController();
?>
