<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALCustomFieldController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('customfield')->getMessagekey();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'fieldsordering');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_searchfields':
                    $fieldfor = WPJOBPORTALrequest::getVar('ff','',2);
                    wpjobportal::$_data['fieldfor'] = $fieldfor;
                    WPJOBPORTALincluder::getJSModel('customfield')->getSearchFieldsOrdering($fieldfor);
                    break;

                case 'admin_formuserfield':
                    $id = WPJOBPORTALrequest::getVar('wpjobportalid');
                    $fieldfor = WPJOBPORTALrequest::getVar('ff');
                    if (empty($fieldfor)){
                        $fieldfor = wpjobportal::$_data['fieldfor'];
                    }else{
                        wpjobportal::$_data['fieldfor'] = $fieldfor;
                    }
                    wpjobportal::$_data[0]['fieldfor'] = $fieldfor;
                    WPJOBPORTALincluder::getJSModel('customfield')->getUserFieldbyId($id, $fieldfor);
                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'customfield');
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
        $fieldfor = WPJOBPORTALrequest::getVar('ff');
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->fieldsRequiredOrNot($ids, 1); // required
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_customfield&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
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
        $fieldfor = WPJOBPORTALrequest::getVar('ff');
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->fieldsRequiredOrNot($ids, 0); // notrequired
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_customfield&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
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
        $fieldfor = WPJOBPORTALrequest::getVar('ff');
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->fieldsPublishedOrNot($ids, 1);
        $msg = WPJOBPORTALMessages::getMessage($result, 'record');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_customfield&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
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
        $fieldfor = WPJOBPORTALrequest::getVar('ff');
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->fieldsPublishedOrNot($ids, 0);
        $msg = WPJOBPORTALMessages::getMessage($result, 'record');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_customfield&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    /*function visitorfieldpublished() {
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff');
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->visitorFieldsPublishedOrNot($ids, 1);
        $msg = WPJOBPORTALMessages::getMessage($result, 'record');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_customfield&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function visitorfieldunpublished() {
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff');
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->visitorFieldsPublishedOrNot($ids, 0);
        $msg = WPJOBPORTALMessages::getMessage($result, 'record');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_customfield&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }*/

    /*function customfieldup() {
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff');
        $id = WPJOBPORTALrequest::getVar('fieldid');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->fieldOrderingUp($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_customfield&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function customfielddown() {
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $fieldfor = WPJOBPORTALrequest::getVar('ff');
        $id = WPJOBPORTALrequest::getVar('fieldid');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->fieldOrderingDown($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url('admin.php?page=wpjobportal_customfield&wpjobportallt=fieldsordering&ff=' . esc_attr($fieldfor)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }*/

    function saveuserfield() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $fieldfor = WPJOBPORTALrequest::getVar('fieldfor');
        if($fieldfor == ''){
            $fieldfor = $data['fieldfor'];
        }
        $result = WPJOBPORTALincluder::getJSModel('customfield')->storeUserField($data);
        if ($result === WPJOBPORTAL_SAVE_ERROR || $result === false) {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_customfield&wpjobportallt=formuserfield&ff=" . esc_attr($fieldfor)));
        } else
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_customfield&ff=" . esc_attr($fieldfor)));
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    // function savesearchcustomfield() { // not called anywhere
    //     $data = WPJOBPORTALrequest::get('post');
    //     $fieldfor = WPJOBPORTALrequest::getVar('fieldfor');
    //     if($fieldfor == ''){
    //         $fieldfor = $data['fieldfor'];
    //     }
    //     $result = WPJOBPORTALincluder::getJSModel('customfield')->storeSearchFieldOrdering($data);
    //     $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_customfield&wpjobportallt=searchfields&fieldfor=" . esc_attr($fieldfor)));
    //     $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
    //     WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
    //     wp_redirect($url);
    //     die();
    // }

    // function savesearchcustomfieldFromForm() { // not called anywhere
    //     $data = WPJOBPORTALrequest::get('post');
    //     $fieldfor = WPJOBPORTALrequest::getVar('fieldfor');
    //     if($fieldfor == ''){
    //         $fieldfor = $data['fieldfor'];
    //     }
    //     $result = WPJOBPORTALincluder::getJSModel('customfield')->storeSearchFieldOrderingByForm($data);
    //     $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_customfield&wpjobportallt=searchfields&fieldfor=" . esc_attr($fieldfor)));
    //     $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
    //     WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
    //     wp_redirect($url);
    //     die();
    // }

    function remove() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        $id = WPJOBPORTALrequest::getVar('fieldid');
        $ff = WPJOBPORTALrequest::getVar('ff');
        $result = WPJOBPORTALincluder::getJSModel('customfield')->deleteUserField($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_customfield&ff=".esc_attr($ff)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function downloadcustomfile(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_field_nonce') ) {
             die( 'Security check Failed' );
        }

        $upload_for = WPJOBPORTALrequest::getVar('upload_for');// to handle different entities(company, job, resume)
        $entity_id = WPJOBPORTALrequest::getVar('entity_id');// to create path for enitity directory where the file is located
        $file_name = WPJOBPORTALrequest::getVar('file_name');// to access the file and download it

        $result = WPJOBPORTALincluder::getJSModel('customfield')->downloadCustomUploadedFile($upload_for,$file_name,$entity_id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'customfield');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_customfield&ff=".esc_attr($ff)));
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$WPJOBPORTALcustomfieldController = new WPJOBPORTALcustomfieldController();
?>
