<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALEmailtemplateController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('emailtemplate')->getMessagekey();        
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'emailtemplate');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_emailtemplate':
                    $tempfor = WPJOBPORTALrequest::getVar('for', null, 'ew-cm');
                    WPJOBPORTALincluder::getJSModel('emailtemplate')->getTemplate($tempfor);
                    wpjobportal::$_data[1] = $tempfor;
                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'emailtemplate');
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

    function saveemailtemplate() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_email_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $templatefor = $data['templatefor'];
        $result = WPJOBPORTALincluder::getJSModel('emailtemplate')->storeEmailTemplate($data);
        $msg = WPJOBPORTALMessages::getMessage($result, 'emailtemplate');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);

        switch ($templatefor) {
            case 'company-new' : $tempfor = 'ew-cm';
                break;
            case 'company-delete' : $tempfor = 'd-cm';
                break;
            case 'company-status' : $tempfor = 'cm-sts';
                break;
            case 'company-rejecting' : $tempfor = 'cm-rj';
                break;
            case 'job-new' : $tempfor = 'ew-ob';
                break;
            case 'job-approval' : $tempfor = 'ob-ap';
                break;
            case 'job-delete' : $tempfor = 'ob-d';
                break;
            case 'resume-new' : $tempfor = 'ew-rm';
                break;
            case 'message-email' : $tempfor = 'ew-ms';
                break;
            case 'resume-approval' : $tempfor = 'rm-ap';
                break;
            case 'resume-rejecting' : $tempfor = 'rm-rj';
                break;
            case 'applied-resume_status' : $tempfor = 'ap-rs';
                break;
            case 'jobapply-jobapply' : $tempfor = 'ba-ja';
                break;
            case 'department-new' : $tempfor = 'ew-md';
                break;
            case 'employer-buypackage' : $tempfor = 'ew-rp';
                break;
            case 'jobseeker-buypackage' : $tempfor = 'ew-js';
                break;
            case 'job-alert' : $tempfor = 'jb-at';
                break;
            case 'job-alert-visitor' : $tempfor = 'jb-at-vis';
                break;
            case 'job-to-friend' : $tempfor = 'jb-to-fri';
                break;
            default : 
            	$tempfor = 'ew-cm';
                break;
        }
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_emailtemplate&for=" . esc_attr($tempfor)));
        wp_redirect($url);
        die();
    }

}

$WPJOBPORTALEmailtemplateController = new WPJOBPORTALEmailtemplateController();
?>
