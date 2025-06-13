<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALJobController {

    private $_msgkey;

    function __construct() {

        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('job')->getMessagekey();

    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'jobs');
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $wpjpjob = WPJOBPORTALincluder::getJSModel('job');
        if (self::canaddfile($layout)) {
            $empflag  = wpjobportal::$_config->getConfigurationByConfigName('disable_employer');
            if(is_admin()){
                $empflag = true;
            }
            $string = "'jscontrolpanel','emcontrolpanel','visitor'" ;
            $config_array = wpjobportal::$_config->getConfigurationByConfigForMultiple($string);
            switch ($layout) {
                case 'myjobs':
                    try {
                        if (WPJOBPORTALincluder::getObjectClass('user')->isemployer() && $empflag == 1) {
                            $wpjpjob->getMyJobs($uid);
                        } else {
                            wpjobportal::$_common->validateEmployerArea();
                            wpjobportal::$_error_flag = true;
                            if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                $linktext = esc_html(__('Login','wp-job-portal'));
                                $link = wpjobportal::$_common->jsMakeRedirectURL('job', $layout, 1);
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
                case 'jobs':
                case 'newestjobs':
                    $flag = true;
                    $search = WPJOBPORTALrequest::getVar('issearchform', 'post');
                    $companyid = WPJOBPORTALrequest::getVar('companyid', 'get');
                    $jobtypeid = WPJOBPORTALrequest::getVar('jobtype', 'get');
                    $categoryid = WPJOBPORTALrequest::getVar('category', 'get');
                    $wpjobportalid = WPJOBPORTALrequest::getVar('wpjobportalid', 'get');
                    $wpjobportalid = wpjobportal::$_common->parseID($wpjobportalid);
                    if ($categoryid != null) {
                        if(WPJOBPORTALincluder::getObjectClass('user')->isguest() && $config_array['visitorview_js_jobcat'] != 1){
                            $flag = 2;
                        }
                        if(!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser() && $config_array['visitorview_js_jobcat'] != 1){
                            $flag = 3;
                        }
                    }elseif(WPJOBPORTALincluder::getObjectClass('user')->isguest() && $config_array['visitorview_js_newestjobs'] != 1) {
                        $flag = 2;
                    }elseif(!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser() && $config_array['visitorview_js_newestjobs'] != 1) {
                        $flag = 3;
                    } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest() && $config_array['visitorview_js_jobsearchresult'] != 1 && $search != null) {
                        $flag = 2;
                    } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser() && $config_array['visitorview_js_jobsearchresult'] != 1 && $search != null) {
                        $flag = 3;
                    }
                    if ($flag === true) {
                        $vars = $wpjpjob->getjobsvar();
                        $wpjpjob->getJobs($vars);
                        $empflag = 1;
                        wpjobportal::$_data['vars'] = $vars;
                        $issearchform = WPJOBPORTALrequest::getVar('issearchform', 'post', null);
                        if ($issearchform != null) {
                            wpjobportal::$_data['issearchform'] = $issearchform;
                        }
                    }elseif($flag === 2){
                        $link = wpjobportal::$_common->jsMakeRedirectURL('job', $layout, 1);
                        $linktext = esc_html(__('Login','wp-job-portal'));
                        wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1);
                        wpjobportal::$_error_flag_message_for=1; // user is guest
                        wpjobportal::$_error_flag_message_register_for=1; // register as jobseeker
                        wpjobportal::$_error_flag = true;
                    }elseif($flag === 3){
                        $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal'));
                        $linktext = esc_html(__('Select role','wp-job-portal'));
                        wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1);
                        wpjobportal::$_error_flag_message_for=9;
                        wpjobportal::$_error_flag = true;
                    }elseif($flag === 4){
                        wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(2 , null , null,1);
                        wpjobportal::$_error_flag_message_for=2;
                        wpjobportal::$_error_flag = true;
                    }
                    if(isset($link) && isset($linktext)){
                        wpjobportal::$_error_flag_message_for_link = $link;
                        wpjobportal::$_error_flag_message_for_link_text = $linktext;
                    }
                    $layout = 'jobs';

                    break;
                case 'viewjob':
                    $jobid = WPJOBPORTALrequest::getVar('wpjobportalid');
                    $jobid = wpjobportal::$_common->parseID($jobid);
                    # paid submission
                    $submission_type = wpjobportal::$_config->getConfigValue('submission_type');

                    $expiryflag = $wpjpjob->getJobsExpiryStatus($jobid);
                    // moved this code up to enable employer to view his own job that is not yet payment approved
                    if($wpjpjob->getJobPay($jobid)){
                        $expiryflag = false;
                    }
                    if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                        if ($wpjpjob->getIfJobOwner($jobid)) {
                            $expiryflag = true;
                        }
                    }

                    if (WPJOBPORTALincluder::getObjectClass('user')->isguest() && $config_array['visitorview_emp_viewjob'] != 1) {
                        $linktext = esc_html(__('Login','wp-job-portal'));
                        $link = wpjobportal::$_common->jsMakeRedirectURL('job', $layout, 1);
                        wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1);
                        wpjobportal::$_error_flag = true;
                        wpjobportal::$_error_flag_message_for=1;
                        wpjobportal::$_error_flag_message_register_for=1;
                    } elseif ($expiryflag == false) {
                        wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(6,null,null,1);
                        wpjobportal::$_error_flag_message_for=6;
                        wpjobportal::$_error_flag = true;
                    } else {
                        # Submission Type for User pakeg
                        if($submission_type == 3){
                            $check = WPJOBPORTALincluder::getJSModel('jobapply')->canAddJobApply($jobid,$uid);
                        }
                        $wpjpjob->getJobbyIdForView($jobid);
                        $empflag = 1;
                    }
                    if(isset($link) && isset($linktext)){
                        wpjobportal::$_error_flag_message_for_link=$link;
                        wpjobportal::$_error_flag_message_for_link_text=$linktext;
                    }
                    break;
                case 'jobsbycategories':
                    try {
                        if (WPJOBPORTALincluder::getObjectClass('user')->isguest() && $config_array['visitorview_js_jobcat'] != 1) {
                            $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('company', $layout, 1);
                            $linktext = esc_html(__('Login','wp-job-portal'));
                            wpjobportal::$_error_flag_message_for=1;
                            wpjobportal::$_error_flag_message_register_for=2;
                            throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                        } elseif ((WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) || ($config_array['visitorview_js_jobcat'] == 1)) {
                            $wpjpjob->getJobsByCategories();
                            $empflag = 1;
                        } else {
                            wpjobportal::$_common->validateEmployerArea();
                            wpjobportal::$_error_flag = true;
                            $link = wpjobportal::$_common->jsMakeRedirectURL('job', $layout, 1);
                            $linktext = esc_html(__('Login','wp-job-portal'));
                            wpjobportal::$_error_flag_message_for=1; // user is guest;
                            wpjobportal::$_error_flag_message_register_for=1;
                            if(isset($link) && isset($linktext)){
                                wpjobportal::$_error_flag_message_for_link=$link;
                                wpjobportal::$_error_flag_message_for_link_text=$linktext;
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
                case 'jobsbytypes':
                    $wpjpjob->getJobsByTypes();
                    $empflag = 1;
                    break;
                case 'jobsbycities':
                    $wpjpjob->getJobsByCities();
                    $empflag = 1;
                    break;
                case 'admin_jobs':
                    $wpjpjob->getAllJobs();
                    break;
               case 'addjob':
               case 'admin_formjob':
                    try {
                        if (wpjobportal::$_common->wpjp_isadmin() || (WPJOBPORTALincluder::getObjectClass('user')->isemployer() && $empflag == 1)) {
                            $id = WPJOBPORTALrequest::getVar('wpjobportalid');
                            if($id == '' && !wpjobportal::$_common->wpjp_isadmin()){
                                $actionname = 'job';
                                if(in_array('credits',wpjobportal::$_active_addons)){
                                        # Filter Package For Controller
                                        $data = json_decode(apply_filters('wpjobportal_addons_available_package',false,'job','job','canAddJob'));
                                        $check = $data->check;
                                        if($check == true){
                                            if(isset($data->layout) && $data->layout == "packageselection" ){
                                                $layout = $data->layout;
                                                $module = 'package';
                                            }
                                       }else{
                                            wpjobportal::$_common->getBuyErrMsg();
                                       }
                                    }else{
                                    $check = true;
                                }
                                if(!in_array('multicompany',wpjobportal::$_active_addons)){
                                    $company = WPJOBPORTALincluder::getJSModel('company')->getSingleCompanyByUid($uid);
                                }
                            }else{
                                if(!wpjobportal::$_common->wpjp_isadmin()){
                                    $check = $wpjpjob->getIfJobOwner($id);// owner check
                                    if(!in_array('multicompany',wpjobportal::$_active_addons)){
                                        $company = WPJOBPORTALincluder::getJSModel('company')->getSingleCompanyByUid($uid);
                                    }
                                }
                            }
                            if (wpjobportal::$_common->wpjp_isadmin() || $check == true) {
                                $wpjpjob->getJobbyId($id);
                            }elseif($id != ''){// $id != ''  means this is not new entity case
                                wpjobportal::$_error_flag_message_for = 10; //edit form for a deleted job should show no record found. "4" shows message that not enough credits
                                throw new Exception( WPJOBPORTALLayout::setMessageFor(10,null,null,1));// was showing a log error

                            }else {
                                wpjobportal::$_common->getBuyErrMsg();
                            }
                        } else {
                            if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                                wpjobportal::$_error_flag_message_for=2;
                                throw new Exception( WPJOBPORTALLayout::setMessageFor(2,null,null,1));

                            } elseif ((WPJOBPORTALincluder::getObjectClass('user')->isguest() || !WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) && $config_array['visitor_can_post_job'] == 1 && in_array('visitorcanaddjob', wpjobportal::$_active_addons)) {
                                $visitor_add_job = 0;
                                // visitor add job is not supposed to be dependent on credits addon
                                //if(in_array('credits', wpjobportal::$_active_addons)){
                                    if($config_array['visitor_can_post_job'] == 1 && in_array('visitorcanaddjob', wpjobportal::$_active_addons)){
                                        $visitor_add_job = 1;
                                    }else{
                                        $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('job', $layout, 1);
                                        $linktext = esc_html(__('Login','wp-job-portal'));
                                        wpjobportal::$_error_flag_message_for=1;
                                        wpjobportal::$_error_flag_message_register_for=2;
                                        throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));
                                    }
                                //}
                                if($visitor_add_job == 1) {
                                    $layout = 'visitoraddjob';
                                    $module = "visitorcanaddjob";
                                    $id = WPJOBPORTALrequest::getVar('wpjobportalid');
                                    WPJOBPORTALincluder::getJSModel('company')->getCompanybyId($id);
                                    if (isset(wpjobportal::$_data[0])) {
                                        wpjobportal::$_data[4] = wpjobportal::$_data[0]; //company data
                                    }
                                    //wpjobportal::$_data[5] = wpjobportal::$_data[2]; //company fields ordering
                                    $wpjpjob->getJobbyId($id);
                                    if (isset(wpjobportal::$_data[0])) {
                                        wpjobportal::$_data[7] = wpjobportal::$_data[0]; //job data
                                    }
                                    wpjobportal::$_data[8] = wpjobportal::$_data[2];
                                }
                            } else{
                                if(wpjobportal::$theme_chk == 1){
                                    $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('job', $layout, 1);
                                    $linktext = esc_html(__('Login','wp-job-portal'));
                                    wpjobportal::$_error_flag_message_for=1;
                                    wpjobportal::$_error_flag_message_register_for=2;
                                    throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                                }else{
                                    wpjobportal::$_common->validateEmployerArea();
                                }
                            }
                        }
                        if(isset($link) && isset($linktext)){
                            wpjobportal::$_error_flag_message_for_link=$link;
                            wpjobportal::$_error_flag_message_for_link_text=$linktext;
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
                case 'admin_jobqueue':
                    $wpjpjob->getAllUnapprovedJobs();
                    break;
                case 'admin_job_searchresult':
                    $wpjpjob->getJobSearch();
                    break;
                case 'admin_jobsearch':
                    //$wpjpjob->getSearchOptions();
                    break;
                case 'admin_view_job':
                    $id = WPJOBPORTALrequest::getVar('wpjobportalid');
                    $wpjpjob->getJobbyIdForView($jobid);
                    break;
                default:
                    return;
            }
            if (WPJOBPORTALincluder::getObjectClass('user')->isemployer() || (('myjobs' == $layout)  || ('addjob' == $layout)) ) {
                if ($empflag == 0) {
                    WPJOBPORTALLayout::setMessageFor(5);
                    wpjobportal::$_error_flag_message_for=5;
                    wpjobportal::$_error_flag = true;
                }
            }
            if(!isset($module)){
                $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
                $module = WPJOBPORTALrequest::getVar($module, null, 'job');
            }
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            if(is_numeric($module)){
                $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'job');
            }
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($layout, $module);
        }
    }

    function approveQueueJob() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
             die( 'Security check Failed' );
        }
        $id = WPJOBPORTALrequest::getVar('id');
        $result = WPJOBPORTALincluder::getJSModel('job')->approveQueueJobModel($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'job');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue"));
        wp_redirect($url);
        die();
    }

    function rejectQueueJob() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
             die( 'Security check Failed' );
        }
        $id = WPJOBPORTALrequest::getVar('id');
        $result = WPJOBPORTALincluder::getJSModel('job')->rejectQueueJobModel($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'job');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue"));
        wp_redirect($url);
        die();
    }

    function approveQueueFeaturedJob() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
             die( 'Security check Failed' );
        }
        $id = WPJOBPORTALrequest::getVar('id');
        $result = WPJOBPORTALincluder::getJSModel('job')->approveQueueFeaturedJobModel($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'job');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue"));
        wp_redirect($url);
        die();
    }

    function rejectQueueFeaturedJob() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
             die( 'Security check Failed' );
        }
        $id = WPJOBPORTALrequest::getVar('id');
        $result = WPJOBPORTALincluder::getJSModel('job')->rejectQueueFeaturedJobModel($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'job');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue"));
        wp_redirect($url);
        die();
    }

    // function approveQueueAllJobs() {
    //     $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
    //     if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
    //          die( 'Security check Failed' );
    //     }
    //     $id = WPJOBPORTALrequest::getVar('id');
    //     $alltype = WPJOBPORTALrequest::getVar('objid');
    //     $result = WPJOBPORTALincluder::getJSModel('job')->approveQueueAllJobsModel($id, $alltype);
    //     $msg = WPJOBPORTALMessages::getMessage($result, 'job');
    //     WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
    //     $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue"));

    //     wp_redirect($url);
    //     die();
    // }

    // function rejectQueueAllJobs() {
    //     $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
    //     if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
    //          die( 'Security check Failed' );
    //     }
    //     $id = WPJOBPORTALrequest::getVar('id');
    //     $alltype = WPJOBPORTALrequest::getVar('objid');
    //     $result = WPJOBPORTALincluder::getJSModel('job')->rejectQueueAllJobsModel($id, $alltype);
    //     $msg = WPJOBPORTALMessages::getMessage($result, 'job');
    //     WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
    //     $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue"));
    //     wp_redirect($url);
    // }

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

    function savejob() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
             die( 'Security check Failed' );
        }
        $mjob = WPJOBPORTALincluder::getJSModel('job');
        $data = WPJOBPORTALrequest::get('post');
        $result = $mjob->storeJob($data);
        $isnew = !( isset($data['id']) && (int)$data['id'] ) ? 1 : 0;
        $isqueue = WPJOBPORTALrequest::getVar('isqueue','post',0);
        $adminjoblayout = $isqueue == 1 ? 'jobqueue' : 'jobs';
        $submission_type = wpjobportal::$_config->getConfigValue('submission_type');
        $isnew = !( isset($data['id']) && (int)$data['id'] ) ? 1 : 0;
        if ($result == WPJOBPORTAL_SAVED) {
            if (wpjobportal::$_common->wpjp_isadmin()) {
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=".esc_attr($adminjoblayout)));
            } else {
                if(in_array('credits', wpjobportal::$_active_addons)){
                    if($submission_type == 2 &&   $isnew == 1 ){
                        if(wpjobportal::$_config->getConfigValue('job_currency_price_perlisting') > 0){
                            # credit to save
                            $url = apply_filters('wpjobportal_addons_credit_save_perlisting',false,wpjobportal::$_data['id'],'payjob');
                        }else{
                            $url = esc_url_raw(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')));
                        }
                    }else{
                        $url = esc_url_raw(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')));
                    }
                }else{
                    $url = esc_url_raw(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')));
                }
            }
            if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                $pageid = wpjobportal::$_config->getConfigurationByConfigName('visitor_add_job_redirect_page');
                $url = get_the_permalink($pageid);
            }
        } else {
            if (wpjobportal::$_common->wpjp_isadmin()) {
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=formjob"));
            } else {
                $url = esc_url_raw(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'addjob')));
            }
        }
        $msg = WPJOBPORTALMessages::getMessage($result, 'job');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        wp_redirect($url);
        die();
    }

    function remove() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
             die( 'Security check Failed' );
        }
        $ids = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        $data = WPJOBPORTALrequest::get('post');
        $callfrom = '';
        if (!isset($data['callfrom']) || $data['callfrom'] == null) {
            $data['callfrom'] = $callfrom = WPJOBPORTALrequest::getVar('callfrom');
        }

        $result = WPJOBPORTALincluder::getJSModel('job')->deleteJobs($ids);
        $msg = WPJOBPORTALMessages::getMessage($result, 'job');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if (wpjobportal::$_common->wpjp_isadmin()) {
            if (isset($data['callfrom']) AND $data['callfrom'] == 2) {
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue"));
            }else{
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobs"));
            }
        } else {
            $url = esc_url_raw(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')));
        }
        wp_redirect($url);
        die();
    }

    function jobenforcedelete() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_job_nonce') ) {
             die( 'Security check Failed' );
        }
        if (!current_user_can('manage_options')) { //only admin can change it.
            return false;
        }
        $jobid = WPJOBPORTALrequest::getVar('jobid');
        $callfrom = WPJOBPORTALrequest::getVar('callfrom');
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $resultforsendmail = WPJOBPORTALincluder::getJSModel('job')->getJobInfoForEmail($jobid);
        $mailextradata = array();
        if(!empty($resultforsendmail)){ // to handle log error
            $mailextradata['jobtitle'] = $resultforsendmail->jobtitle;
            $mailextradata['useremail'] = $resultforsendmail->useremail;
            // log error resolved
            $mailextradata['companyname'] = $resultforsendmail->companyname;
            $mailextradata['user'] = $resultforsendmail->username;
        }

        $result = WPJOBPORTALincluder::getJSModel('job')->jobEnforceDelete($jobid, $uid);

        $msg = WPJOBPORTALMessages::getMessage($result, 'job');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if ($callfrom == 1) {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobs"));
        } else {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue"));
        }
        if ($result == WPJOBPORTAL_DELETED) {
            WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2, 2, $jobid,$mailextradata); // 2 for job,2 for DELETE job
        }
        wp_redirect($url);
        die();
    }
}

$WPJOBPORTALJobController = new WPJOBPORTALJobController();
?>
