<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALthemeController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'themes');
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        if (self::canaddfile($layout)) {
            $string = "'jscontrolpanel','emcontrolpanel'";
            $config_array = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigForMultiple($string);

            switch ($layout) {
                case 'admin_themes':
                    WPJOBPORTALincluder::getJSModel('theme')->getCurrentTheme();
                    WPJOBPORTALincluder::getJSModel('wpjobportal')->getCPJobs();
                   break;
                default:
                    return;
            }

            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'theme');
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

    static function savetheme() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_theme_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        WPJOBPORTALincluder::getJSModel('theme')->storeTheme($data);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_theme&wpjobportallt=themes"));
        wp_redirect($url);
        die();
    }
}

$WPJOBPORTALthemeController = new WPJOBPORTALthemeController();
?>
