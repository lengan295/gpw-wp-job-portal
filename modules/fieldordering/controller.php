<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALfieldorderingController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('fieldordering')->getMessagekey();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'fieldsordering');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_fieldsordering':
                    $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
                    wpjobportal::$_data['fieldfor'] = $fieldfor;
                    WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsOrdering($fieldfor);
                    break;
                case 'admin_searchfields':
                    $fieldfor = WPJOBPORTALrequest::getVar('ff','',2);
                    wpjobportal::$_data['fieldfor'] = $fieldfor;
                    WPJOBPORTALincluder::getJSModel('fieldordering')->getSearchFieldsOrdering($fieldfor);
                    break;

                case 'admin_formuserfield':
                    $id = WPJOBPORTALrequest::getVar('wpjobportalid');
                    $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
                    if (empty($fieldfor)){
                        $fieldfor = wpjobportal::$_data['fieldfor'];
                    }else{
                        wpjobportal::$_data['fieldfor'] = $fieldfor;
                    }
                    wpjobportal::$_data[0]['fieldfor'] = $fieldfor;
                    WPJOBPORTALincluder::getJSModel('fieldordering')->getUserFieldbyId($id, $fieldfor);
                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'fieldordering');
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

    function fieldrequired() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->fieldsRequiredOrNot($ids, 1); // required
        $msg = WPJOBPORTALMessages::getMessage($result, 'fieldordering');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldnotrequired() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->fieldsRequiredOrNot($ids, 0); // notrequired
        $msg = WPJOBPORTALMessages::getMessage($result, 'fieldordering');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldpublished() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->fieldsPublishedOrNot($ids, 1);
        $msg = WPJOBPORTALMessages::getMessage($result, 'record');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function fieldunpublished() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->fieldsPublishedOrNot($ids, 0);
        $msg = WPJOBPORTALMessages::getMessage($result, 'record');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function visitorfieldpublished() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->visitorFieldsPublishedOrNot($ids, 1);
        $msg = WPJOBPORTALMessages::getMessage($result, 'record');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function visitorfieldunpublished() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->visitorFieldsPublishedOrNot($ids, 0);
        $msg = WPJOBPORTALMessages::getMessage($result, 'record');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    // function fieldorderingup() { // not called anywere
    //     $pagenum = WPJOBPORTALrequest::getVar('pagenum');
    //     $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
    //     $id = WPJOBPORTALrequest::getVar('fieldid');
    //     $result = WPJOBPORTALincluder::getJSModel('fieldordering')->fieldOrderingUp($id);
    //     $msg = WPJOBPORTALMessages::getMessage($result, 'fieldordering');
    //     WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
    //     $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
    //     if ($pagenum)
    //         $url .= "&pagenum=" . $pagenum;
    //     wp_redirect($url);
    //     die();
    // }

    // function fieldorderingdown() { // not called anywere
    //     $pagenum = WPJOBPORTALrequest::getVar('pagenum');
    //     $fieldfor = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
    //     $id = WPJOBPORTALrequest::getVar('fieldid');
    //     $result = WPJOBPORTALincluder::getJSModel('fieldordering')->fieldOrderingDown($id);
    //     $msg = WPJOBPORTALMessages::getMessage($result, 'fieldordering');
    //     WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
    //     $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
    //     if ($pagenum)
    //         $url .= "&pagenum=" . $pagenum;
    //     wp_redirect($url);
    //     die();
    // }

    function saveuserfield() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $fieldfor = WPJOBPORTALrequest::getVar('fieldfor','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        if($fieldfor == ''){
            $fieldfor = $data['fieldfor'];
        }
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->storeUserField($data);
        if ($result === WPJOBPORTAL_SAVE_ERROR || $result === false) {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_fieldordering&wpjobportallt=formuserfield&ff=" . esc_attr($fieldfor)));
        } else
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_fieldordering&ff=" . esc_attr($fieldfor)));
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function savesearchfieldordering() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $fieldfor = WPJOBPORTALrequest::getVar('fieldfor','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        if($fieldfor == ''){
            $fieldfor = $data['fieldfor'];
        }
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->storeSearchFieldOrdering($data);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_fieldordering&wpjobportallt=searchfields&fieldfor=" . esc_attr($fieldfor)."&ff=" . esc_attr($fieldfor)));
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function savesearchfieldorderingFromForm() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $fieldfor = WPJOBPORTALrequest::getVar('fieldfor','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        if($fieldfor == ''){
            $fieldfor = $data['fieldfor'];
        }
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->storeSearchFieldOrderingByForm($data);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_fieldordering&wpjobportallt=searchfields&fieldfor=" . $fieldfor."&ff=" . $fieldfor));
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $id = WPJOBPORTALrequest::getVar('fieldid');
        $is_section_headline = WPJOBPORTALrequest::getVar('is_section_headline','',0);
        $ff = WPJOBPORTALrequest::getVar('ff','',1);// 1 is to make sure the page does not show warnings in case of parameter drop
        $result = WPJOBPORTALincluder::getJSModel('fieldordering')->deleteUserField($id,$is_section_headline);
        $msg = WPJOBPORTALMessages::getMessage($result, 'fieldordering');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_fieldordering&ff=".esc_attr($ff)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$WPJOBPORTALfieldorderingController = new WPJOBPORTALfieldorderingController();
?>
