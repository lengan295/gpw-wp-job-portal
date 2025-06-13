<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALslugController {
    private $_msgkey;
    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('slug')->getMessagekey();        
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'slug');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_slug':
                    WPJOBPORTALincluder::getJSModel('slug')->getSlug();
                    break;
                default:
                    return;
            }
            $module = 'page';
            $module = WPJOBPORTALrequest::getVar($module, null, 'slug');
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

    function saveSlug() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_slug_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $result = WPJOBPORTALincluder::getJSModel('slug')->storeSlug($data);
        if($data['pagenum'] > 0){
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_slug&pagenum=".esc_attr($data['pagenum'])));
        }else{
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_slug"));
        }

        $msg = WPJOBPORTALMessages::getMessage($result, 'slug');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function saveprefix() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_slug_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $result = WPJOBPORTALincluder::getJSModel('slug')->savePrefix($data);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_slug"));
        $msg = WPJOBPORTALMessages::getMessage($result, 'prefix');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function savehomeprefix() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_slug_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $result = WPJOBPORTALincluder::getJSModel('slug')->saveHomePrefix($data);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_slug"));
        $msg = WPJOBPORTALMessages::getMessage($result, 'prefix');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }

    function resetallslugs() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_slug_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $result = WPJOBPORTALincluder::getJSModel('slug')->resetAllSlugs();
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_slug"));
        $msg = WPJOBPORTALMessages::getMessage($result, 'slug');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        exit;
    }
}

$WPJOBPORTALslugController = new WPJOBPORTALslugController();
?>
