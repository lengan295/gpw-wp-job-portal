<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALEmployerController {
    private $_msgkey;
    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('employer')->getMessagekey();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'controlpanel');
        $addonmissing = WPJOBPORTALrequest::getLayout('addonmissing', null, 0);
        if (self::canaddfile($layout)) {
            $empflag  = wpjobportal::$_config->getConfigurationByConfigName('disable_employer');
            $guestflag = false;
            $visitorallowed = wpjobportal::$_config->getConfigurationByConfigName('visitorview_emp_conrolpanel');
            $isouruser = WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser();
            $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();

            if($isguest == true && $visitorallowed == true){
                $guestflag = true;
            }
            if($isguest == false && $isouruser == false && $visitorallowed == true){
                $guestflag = true;
            }
            $hide_error_message = 0; // to handle the case of showing two separate error layouts for same user/case
            switch ($layout) {
                case 'employer_report':
                    break;
                case 'controlpanel':
                    try {
                        if($addonmissing == 1){
                            wpjobportal::$_error_flag_message_for=18;
                            throw new Exception(WPJOBPORTALLayout::setMessageFor(18 , '' ,'',1));
                            $hide_error_message = 1;
                        }
                        if (wpjobportal::$_common->wpjp_isadmin() || (WPJOBPORTALincluder::getObjectClass('user')->isemployer() && $empflag == 1 || $guestflag == true)) {
                            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
                            wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('emcontrolpanel');
                            WPJOBPORTALincluder::getJSModel('employer')->getLatestResumeIdNew($uid);
                            WPJOBPORTALincluder::getJSModel('employer')->getEmployerinfo($uid);
                            if(in_array('credits', wpjobportal::$_active_addons)){
                                WPJOBPORTALincluder::getJSModel('employer')->getDataForDashboard($uid);
                            }
                           WPJOBPORTALincluder::getJSModel('employer')->getGraphDataNew($uid);
                           // handle shortcode options to manage section visiblity
                           WPJOBPORTALincluder::getJSModel('employer')->handleShortCodeOptions();
                       } else {
                                if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                                    $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobseeker', 'wpjobportallt'=>'controlpanel'));
                                    $linktext = esc_html(__('Go Back To Home','wp-job-portal'));
                                    wpjobportal::$_error_flag_message_for = 2;
                                    throw new Exception(WPJOBPORTALLayout::setMessageFor(2,$link,$linktext,1));
                                    $hide_error_message = 1;
                                } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                    $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('employer', $layout, 1);
                                    $linktext = esc_html(__('Login','wp-job-portal'));
                                   wpjobportal::$_error_flag_message_for = 1;
                                    throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));
                                    $hide_error_message = 1;
                                } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                    $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal'));
                                    $linktext = esc_html(__('Select role','wp-job-portal'));
                                    wpjobportal::$_error_flag_message_for = 9;
                                    throw new Exception(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1));
                                    $hide_error_message = 1;
                                }
                                if(isset($link) && isset($linktext)){
                                    wpjobportal::$_error_flag_message_for_link = $link;
                                    wpjobportal::$_error_flag_message_for_link_text = $linktext;
                                }
                            }
                        } catch (Exception $ex) {
                             wpjobportal::$_error_flag = true;
                             wpjobportal::$_error_flag_message = $ex->getMessage();
                             $hide_error_message = 1;
                        }
                    break;
                default:
                    return;
                }
            if ($empflag == 0 && $hide_error_message == 0) {
                WPJOBPORTALLayout::setMessageFor(5);
                wpjobportal::$_error_flag = true;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'employer');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            if(is_numeric($module)){
                $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'employer');
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

$WPJOBPORTALEmployerController = new WPJOBPORTALEmployerController();
?>
