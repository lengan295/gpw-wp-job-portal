<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALJobseekerController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'controlpanel');
        $addonmissing = WPJOBPORTALrequest::getLayout('addonmissing', null, 0);
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'jobseeker_report':
                    break;
                case 'controlpanel':
					// temporary code to avoid any resume error.
                    include_once WPJOBPORTAL_PLUGIN_PATH . 'includes/updates/updates.php';
                    WPJOBPORTALupdates::checkUpdates();
					
                    if(get_option( 'wpjobportal_apply_visitor', '' ) != '')
                        delete_option( 'wpjobportal_apply_visitor' );
                    $visitorview_js_controlpanel = wpjobportal::$_config->getConfigurationByConfigName('visitorview_js_controlpanel');
                    try {
                        if($addonmissing == 1){
                            wpjobportal::$_error_flag_message_for=18;
                            throw new Exception(WPJOBPORTALLayout::setMessageFor(18 , '' ,'',1));
                        }
                        if ($visitorview_js_controlpanel != 1) {
                            if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('jobseeker', $layout, 1);
                                $linktext = esc_html(__('Login','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=1;
                                wpjobportal::$_error_flag_message_register_for=1; // register as jobseeker
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));
                            } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal',  'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                $linktext = esc_html(__('Select role','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=1;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1));
                            }
                        }
                        if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                            $employerview_js_controlpanel = wpjobportal::$_config->getConfigurationByConfigName('employerview_js_controlpanel');
                            if ($employerview_js_controlpanel != 1){
                                wpjobportal::$_error_flag_message_for=7;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(7,null,null,1));
                            }
                        }
                        if(isset($link) && isset($linktext)){
                            wpjobportal::$_error_flag_message_for_link = $link;
                            wpjobportal::$_error_flag_message_for_link_text = $linktext;
                        }

                    } catch (Exception $ex) {
                        wpjobportal::$_error_flag = true;
                        wpjobportal::$_error_flag_message = $ex->getMessage();
                    }
                    //code for user related jobs
                    $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
                    WPJOBPORTALincluder::getJSModel('jobseeker')->getResumeStatusByUid($uid);
                    WPJOBPORTALincluder::getJSModel('jobseeker')->getConfigurationForControlPanel();
                    WPJOBPORTALincluder::getJSModel('jobseeker')->getLatestJobs();
                    WPJOBPORTALincluder::getJSModel('jobseeker')->getJobsAppliedRecently($uid);
                    WPJOBPORTALincluder::getJSModel('jobseeker')->getUserinfo($uid);
                    WPJOBPORTALincluder::getJSModel('jobseeker')->getJobsekerResumeTitle($uid);
                    WPJOBPORTALincluder::getJSModel('jobseeker')->getGraphDataNew($uid);
                    // handle shortcode options to manage section visiblity
                    WPJOBPORTALincluder::getJSModel('jobseeker')->handleShortCodeOptions();
                    if(in_array('credits', wpjobportal::$_active_addons)){
                        WPJOBPORTALincluder::getJSModel('employer')->getDataForDashboard($uid);
                    }
                    // data in this function also prepared above but casues issue on other layouts where left menu is added so changed it
                    WPJOBPORTALincluder::getJSModel('jobseeker')->getResumeInfoForJobSeekerLeftMenu($uid);
                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'jobseeker');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            if(is_numeric($module)){
                $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'jobseeker');
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

$WPJOBPORTALJobseekerController = new WPJOBPORTALJobseekerController();
?>
