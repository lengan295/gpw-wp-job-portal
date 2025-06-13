<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALwpjobportalController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('wpjobportal')->getMessagekey();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'controlpanel');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_controlpanel':
                    include_once WPJOBPORTAL_PLUGIN_PATH . 'includes/updates/updates.php';
                    WPJOBPORTALupdates::checkUpdates();
                    WPJOBPORTALincluder::getJSModel('wpjobportal')->getAdminControlPanelData();
                    break;
                case 'admin_wpjobportalstats':
                    WPJOBPORTALincluder::getJSModel('wpjobportal')->getwpjobportalStats();
                    break;
                case 'login':
                    if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                        $url = WPJOBPORTALrequest::getVar('wpjobportalredirecturl');
                        if(isset($url)){
                            wpjobportal::$_data[0]['redirect_url'] = wpjobportalphplib::wpJP_safe_decoding($url);
                        }else{
                            wpjobportal::$_data[0]['redirect_url'] = home_url();
                        }
                    }else{
                        $finalurl = wp_logout_url(home_url());
                        if(isset($_COOKIE['wpjobportal-socialmedia']) && !empty($_COOKIE['wpjobportal-socialmedia'])){
                            $finalurl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'sociallogin', 'task'=>'socialogout', 'action'=>'wpjobportaltask',  'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        wpjobportal::$_error_flag = true;
                        if(class_exists('job_manager_Messages')){
                            job_manager_Messages::alreadyLoggedIn($finalurl);
                        }elseif(class_exists('job_hub_Messages')){
                            job_hub_Messages::alreadyLoggedIn($finalurl);
                        }else{
                            WPJOBPORTALLayout::getUserAlreadyLoggedin($finalurl);
                        }
                    }
                    break;
                case 'admin_addonstatus': // to avoid default case
                case 'admin_shortcodes': // to avoid default case
                case 'admin_help': // to avoid default case
                case 'admin_pageseo': // to avoid default case
                    break;

                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'wpjobportal');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            if($layout=="thankyou"){
                if($module=="" || $module!="wpjobportal") $module="wpjobportal";
            }
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

    function saveordering(){
        $post = WPJOBPORTALrequest::get('post');
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;

        WPJOBPORTALincluder::getJSModel('wpjobportal')->storeOrderingFromPage($post);
        if($post['ordering_for'] == 'fieldordering'){
            $fieldfor = WPJOBPORTALrequest::getVar('fieldfor');
            if($fieldfor == ''){
                $fieldfor = wpjobportal::$_data['fieldfor'];
            }
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=".esc_attr($fieldfor)));
        }
        wp_redirect($url);
        exit;
    }

    function savedocumenttitleoptions() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_document_title_nonce') ) {
             die( 'Security check Failed' );
        }
        if (!current_user_can('manage_options')) { //only admin can change it.
            return false;
        }
        $data = WPJOBPORTALrequest::get('post');
        $result = WPJOBPORTALincluder::getJSModel('wpjobportal')->saveDocumentTitleOptions($data);
        echo var_dump($result);
        $msg = WPJOBPORTALMessages::getMessage($result, "wpjobportal");
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal&wpjobportallt=pageseo"));
        wp_redirect($url);
        die();
    }


}

$WPJOBPORTALwpjobportalController = new WPJOBPORTALwpjobportalController();
?>
