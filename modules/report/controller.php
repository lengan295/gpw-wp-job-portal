<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALReportController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'reports');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_overallreports':
                    WPJOBPORTALincluder::getJSModel('report')->getOverallReports();
                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'report');
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

}

$WPJOBPORTALReportController = new WPJOBPORTALReportController();
?>
