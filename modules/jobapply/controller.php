<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALJobapplyController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();

        $this->_msgkey = WPJOBPORTALincluder::getJSModel('jobapply')->getMessagekey();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'appliedresumes');
        if (self::canaddfile($layout)) {
            switch ($layout) {
                case 'admin_appliedresumes':
                    WPJOBPORTALincluder::getJSModel('jobapply')->getAppliedResume();
                    break;
                case 'myappliedjobs':
                    try {
                        $conflag = wpjobportal::$_config->getConfigurationByConfigName('myappliedjobs');
                        if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
                            WPJOBPORTALincluder::getJSModel('jobapply')->getMyAppliedJobs($uid);
                            // to handle jobseeker left menu data
                            WPJOBPORTALincluder::getJSModel('jobseeker')->getResumeInfoForJobSeekerLeftMenu($uid);
                        } else {
                            if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                                wpjobportal::$_error_flag_message_for=3;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(3,null,null,1));

                            } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                $link = wpjobportal::$_common->jsMakeRedirectURL('jobapply', $layout, 1);
                                $linktext = esc_html(__('Login','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=1;
                                wpjobportal::$_error_flag_message_register_for=1; // register as jobseeker
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                            } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal'));
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
                        if(isset($link) && isset($linktext) && wpjobportal::$theme_chk == 1){
                            wpjobportal::$_error_flag_message_for_link=$link;
                            wpjobportal::$_error_flag_message_for_link_text=$linktext;
                        }


                    }

                    break;
                case 'jobappliedresume':
                case 'admin_jobappliedresume':
                    try {
                        if (WPJOBPORTALincluder::getObjectClass('user')->isemployer() || wpjobportal::$_common->wpjp_isadmin()) {
                            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
                            $jobid = WPJOBPORTALrequest::getVar('jobid');
                            $tab_action = WPJOBPORTALrequest::getVar('ta', null, 1);
                            WPJOBPORTALincluder::getJSModel('jobapply')->getJobAppliedResume($tab_action, $jobid, $uid);
                            wpjobportal::$_data['jobid'] = $jobid;
                            wpjobportal::$_data['tab_action_value'] = $tab_action;
                        } else {
                            if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                                wpjobportal::$_error_flag_message_for=2;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(2,null,null,1));

                            } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                $link = wpjobportal::$_common->jsMakeRedirectURL('jobapply', $layout, 1);
                                $linktext = esc_html(__('Login','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=1;
                                wpjobportal::$_error_flag_message_register_for=2; // register as employer
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                            } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal'));
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
                        if(isset($link) && isset($linktext) && wpjobportal::$theme_chk == 1){
                            wpjobportal::$_error_flag_message_for_link=$link;
                            wpjobportal::$_error_flag_message_for_link_text=$linktext;
                        }
                    }
                    break;
                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'jobapply');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            if(is_numeric($module)){
                $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'jobapply');
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


    function jobapplyasvisitor() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_apply_nonce') ) {
             die( 'Security check Failed' );
        }
        $jobid = WPJOBPORTALrequest::getVar('wpjobportalid-jobid');
        if (!is_numeric($jobid)) { // redirect to jobs page if id is not numeric
            if (wpjobportal::$theme_chk == 1) {
                $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs'));
            } else {
                $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs'));
            }
        } else {
            wpjobportalphplib::wpJP_setcookie('wpjobportal_apply_visitor' , $jobid , 0 , COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                wpjobportalphplib::wpJP_setcookie('wpjobportal_apply_visitor' , $jobid , 0 , SITECOOKIEPATH);
            }
            if (in_array('multiresume',wpjobportal::$_active_addons)) {
                $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multiresume', 'wpjobportallt'=>'addresume'));
            } else {
                $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'addresume'));
            }
        }
        wp_redirect($url);
        die();
    }

    function applyonjob() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if(! wp_verify_nonce( $nonce, 'wpjobportal_job_apply_nonce')) {
            die( 'Security check Failed' );
        }

        $result = WPJOBPORTALincluder::getJSModel('jobapply')->applyOnJob();
        $jobid = WPJOBPORTALrequest::getVar('jobid');
        $page_id = WPJOBPORTALrequest::getVar('wpjobportalpageid');
        if($result == WPJOBPORTAL_SAVE_ERROR){
            WPJOBPORTALmessages::setLayoutMessage(__("There was some problem performing action",'wp-job-portal'),'error','job');
        }elseif($result == 3){
            WPJOBPORTALmessages::setLayoutMessage(__("Make Payment To Complete The Job Apply",'wp-job-portal'),'updated','job');
        }else{
            WPJOBPORTALmessages::setLayoutMessage(__("Successfully applied on job",'wp-job-portal'),'updated','job');
        }

        $url = array('wpjobportalme'=>'job','wpjobportallt'=>'viewjob','wpjobportalid'=>$jobid,'wpjobportalpageid'=>$page_id);
        $url = wpjobportal::wpjobportal_makeUrl($url);
        if(in_array('credits', wpjobportal::$_active_addons)){ // check for credit system
            $subtype = wpjobportal::$_config->getConfigValue('submission_type');
            if( $subtype == 2 ){ // per listing mode is on
                $selected_payment_method = WPJOBPORTALrequest::getVar('selected_payment_method');
                if(isset(wpjobportal::$_data['job_apply_id'])){
                    $id = wpjobportal::$_data['job_apply_id'];
                    $paymentconfig = wpjobportal::$_wpjppaymentconfig->getPaymentConfigFor('paypal,stripe,woocommerce',true);
                    if($selected_payment_method == 1){
                        $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchase','action'=>'wpjobportaltask','task'=>'listingpaypalJobApply','wpjobportalid'=>$id,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                    }elseif($selected_payment_method == 2) {
                        $url =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchase','action'=>'wpjobportaltask','task'=>'woocommedeptrceorder','wpjobportalid'=>'job_jobapply_price_perlisting','wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid(),'moduleid'=>$id));
                    }elseif($selected_payment_method == 3) {
                        //$url =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchase','action'=>'wpjobportaltask','task'=>'woocommedeptrceorder','wpjobportalid'=>'job_jobapply_price_perlisting','wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid(),'moduleid'=>$id));
                        $url =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchasehistory','wpjobportallt'=>'payjobapply','wpjobportalid'=>$jobid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                    }
                }
            }
        }
        // echo var_dump($url);
        // die(' in job apply controller apply function ');

        wp_redirect(esc_url_raw($url));
        die();
    }

}

$WPJOBPORTALJobapplyController = new WPJOBPORTALJobapplyController();
?>
