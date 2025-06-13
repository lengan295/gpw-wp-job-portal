<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobSearchController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();

        $this->_msgkey = WPJOBPORTALincluder::getJSModel('jobsearch')->getMessagekey();
    }

    function handleRequest() {

        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'jobsavesearch');
        if (self::canaddfile($layout)) {
            $config_array = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('jscontrolpanel');
            switch ($layout) {
                case 'jobsearch':
                    try {
                        if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker() || WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_js_jobsearch') == 1 ) {
                            WPJOBPORTALincluder::getJSModel('jobsearch')->getJobSearchOptions();
                        } else {
                            if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                                wpjobportal::$_error_flag_message_for=3;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(3,null,null,1));
                            } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('jobsearch', $layout, 1);
                                $linktext = esc_html(__('Login','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=1;
                                wpjobportal::$_error_flag_message_register_for=1;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                            } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                $linktext = esc_html(__('Select role','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=9;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1));
                            }
                            if(isset($link) && isset($linktext)){
                                wpjobportal::$_error_flag_message_for_link = $link;
                                wpjobportal::$_error_flag_message_for_link_text = $linktext;
                            }
                        }
                    } catch (Exception $ex) {
                        wpjobportal::$_error_flag = true;
                        wpjobportal::$_error_flag_message = $ex->getMessage();
                    }

                    break;
                case 'jobsavesearch':
                    try {
                        if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                        $userid = WPJOBPORTALincluder::getObjectClass('user')->uid();
                        WPJOBPORTALincluder::getJSModel('jobsearch')->getMyJobSaveSearchbyUid($userid);
                    } else {
                        if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                            wpjobportal::$_error_flag_message_for=3;
                            throw new Exception(WPJOBPORTALLayout::setMessageFor(3,null,null,1));

                        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                            $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('jobsearch', $layout, 1);
                            $linktext = esc_html(__('Login','wp-job-portal'));
                            wpjobportal::$_error_flag_message_for=1;
                            throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                        } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            $linktext = esc_html(__('Select role','wp-job-portal'));
                            wpjobportal::$_error_flag_message_for=9;
                            throw new Exception(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1));

                        }
                            if(isset($link) && isset($linktext)){
                                wpjobportal::$_error_flag_message_for_link = $link;
                                wpjobportal::$_error_flag_message_for_link_text = $linktext;
                            }
                        }
                    } catch (Exception $ex) {
                        wpjobportal::$_error_flag = true;
                        wpjobportal::$_error_flag_message = $ex->getMessage();
                    }

                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'jobsearch');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            if(is_numeric($module)){
                $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'jobsearch');
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


}

$WPJOBPORTALjobSearchController = new WPJOBPORTALjobSearchController();
?>
