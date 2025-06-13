<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALquickapplyModel {
   function quickapply($jobid, $actionid) {
        if (is_numeric($jobid)) {
            $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE id = " . esc_sql($jobid);
            $job = wpjobportaldb::get_row($query);
            $data = (array) $job;
            $data['id'] = '';
            $data['title'] = $data['title'] . ' ' . __('Copy', 'wp-job-portal');
            $data['jobid'] = WPJOBPORTALincluder::getJSModel('job')->getJobId();
            $data['isjob'] = 2;
            $isadmin = WPJOBPORTALrequest::getVar('isadmin');
            $user = WPJOBPORTALincluder::getObjectClass('user');
            $dateformat = wpjobportal::$_configuration['date_format'];
            $subType = wpjobportal::$_config->getConfigValue('submission_type');
            $expiry = false; // to handle log error
            if(in_array('credits', wpjobportal::$_active_addons)){
                if($subType == 1){
                     $expiry = wpjobportal::$_config->getConfigValue('jobexpiry_days_free');
                    if(isset($data['stoppublishing']) && empty($data['stoppublishing'])){
                        $data['stoppublishing'] = date($dateformat,strtotime($data['startpublishing'].'+'.$expiry.' days') );
                    }
                    if (!wpjobportal::$_common->wpjp_isadmin()) {
                        $data['status'] = wpjobportal::$_config->getConfigurationByConfigName('jobautoapprove');
                    }
                }elseif ($subType == 2) {
                    #Per listing --Free job Expiry date
                    $expiry = wpjobportal::$_config->getConfigValue('jobexpiry_days_perlisting');
                    if(isset($data['stoppublishing']) && empty($data['stoppublishing'])){
                        $data['stoppublishing'] = date($dateformat,strtotime($data['startpublishing'].'+'.$expiry.' days') );
                    }else{
                        $data['stoppublishing'] = date($dateformat,strtotime($expiry.' days') );
                    }
                    $data['status'] = 3;
                }elseif ($subType == 3) {
                    if(!wpjobportal::$_common->wpjp_isadmin()){
                        $upakid = WPJOBPORTALrequest::getVar('wpjobportal_packageid',null,0);
                        $package = apply_filters('wpjobportal_addons_userpackages_permodule',false,$upakid,$user->uid(),'remjob');
                        if( !$package ){
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                        if( $package->expired ){
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                        //if Department are not unlimited & there is no remaining left
                        if( $package->job!=-1 && !$package->remjob ){ //-1 = unlimited
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                    }elseif (wpjobportal::$_common->wpjp_isadmin()) { // checking if admin is trying to perform action
                        $payment = WPJOBPORTALrequest::getVar('payment',null,0);
                        if ($payment == 0) { // proceed without payment option
                            $upakid = WPJOBPORTALrequest::getVar('upakid',null,0);
                            $data['userpackageid'] = $upakid;
                        } else { // if admin clicked on proceed with payment option
                            $upakid = WPJOBPORTALrequest::getVar('upakid',null,0);
                            $uid = WPJOBPORTALrequest::getVar('uid');
                            $package = apply_filters('wpjobportal_addons_userpackages_permodule',false,$upakid,$data['uid'],'remjob');
                            if( !$package ){
                                return WPJOBPORTAL_SAVE_ERROR;
                            }
                            if( $package->expired ){
                                return WPJOBPORTAL_SAVE_ERROR;
                            }
                            //if Department are not unlimited & there is no remaining left
                            if( $package->job!=-1 && !$package->remjob ){ //-1 = unlimited
                                return WPJOBPORTAL_SAVE_ERROR;
                            }
                        }
                    }
                    // if($package == ''){ // to handle log errors since we are trying to access elements from package object.
                    //     return WPJOBPORTAL_SAVE_ERROR;
                    // }
                    #user packae id--
                    $data['status'] = wpjobportal::$_config->getConfigValue('jobautoapprove');
                    $data['userpackageid'] = $upakid;

                    if(isset($package) && !empty($package)){
                        $expiry = $package->jobtime.''.$package->jobtimeunit;
                    }else{
                        $expiry = "30 days"; // in case of undefined add job for 30 days
                    }

                    //if(isset($data['stoppublishing']) && empty($data['stoppublishing'])){
                    $data['stoppublishing'] = date($dateformat,strtotime($data['startpublishing'].'+'.$expiry) );
                    // }else{
                    //     $data['stoppublishing'] = date($dateformat,strtotime($package->jobtime.''.$package->jobtimeunit));
                    // }
                    if(isset($data['price']) && !empty($data['status'])){
                        $data['price'] = '';
                    }
                }
            }
            if($expiry == false){
                $tdate1 = strtotime($data['startpublishing']);
                $tdate2 = strtotime($data['stoppublishing']);
                $seconds_diff = $tdate2 - $tdate1;
                if($seconds_diff > 86400){
                    $expiry = $seconds_diff / 86400;
                }else{
                    $expiry = 1;
                }
                $data['startpublishing'] = date_i18n("Y-m-d H:i:s");
                $data['stoppublishing'] = date($dateformat,strtotime($data['startpublishing'].'+'.round($expiry).' days') );
            }
            if(isset($data['stoppublishing'])){
                $data['stoppublishing'] = date('Y-m-d H:i:s', strtotime($data['stoppublishing']));
            }
            $data['created'] = date_i18n("Y-m-d H:i:s");
            $data['startpublishing'] = date_i18n("Y-m-d H:i:s");
            if(isset($data['isfeaturedjob'])){
                $data['isfeaturedjob'] = 2;
            }

            if(isset($data['startfeatureddate'])){
                $data['startfeatureddate'] = '';
            }

            if(isset($data['endfeatureddate'])){
                $data['endfeatureddate'] = '';
            }
            $row = WPJOBPORTALincluder::getJSTable('job');
            if (!$row->bind($data)) {
                $res = "error";
            }
            if (!$row->store()) {
                return false;
            }
            if ($row->city){
                $storemulticity = WPJOBPORTALincluder::getJSModel('job')->storeMultiCitiesJob($row->city, $row->id);
            }
            if (isset($storemulticity) && $storemulticity == false){
                return false;
            }
            if(in_array('credits', wpjobportal::$_active_addons) && $subType == 3){
                if(!wpjobportal::$_common->wpjp_isadmin()){
                    apply_filters('wpjobportal_addons_user_transactionlog',$row,'job',$upakid,$row->uid);
                }elseif(wpjobportal::$_common->wpjp_isadmin()){
                    apply_filters('wpjobportal_addons_user_transactionlog',$row,'job',$upakid,$data['uid']);
                }
            }
            WPJOBPORTALMessages::setLayoutMessage(__('Job has been copied successfully','wp-job-portal'), 'updated',WPJOBPORTALincluder::getJSModel('job')->getMessagekey());
        }
        if(in_array('credits', wpjobportal::$_active_addons)){
            return true;
        }else{
            return WPJOBPORTAL_SAVED;
        }
    }

    function makeJobCopyAjax() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'make-job-copy-ajax') ) {
            die( 'Security check Failed' );
        }
        $jobid = (int) WPJOBPORTALrequest::getVar('jobid');
        $res = "error";
        if ($jobid && is_numeric($jobid)) {
            $res = "copied";
            $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE id = " . esc_sql($jobid);
            $job = wpjobportaldb::get_row($query);
            $data = (array) $job;

            $data['id'] = '';
            $data['title'] = $data['title'] . ' ' . __('Copy', 'wp-job-portal');
            $data['jobid'] = WPJOBPORTALincluder::getJSModel('job')->getJobId();
            $data['isjob'] = 0;
            $data['status'] = 0;
            $data['startpublishing'] = date('Y-m-d H:i:s');
            $data['created'] = date("Y-m-d H:i:s");
            $row = WPJOBPORTALincluder::getJSTable('job');
            if (!$row->bind($data)) {
                $res = "error";
            }
            if (!$row->check($data)) {
                $res = "error";
            }
            if (!$row->store($data)) {
                $res = "error";
            }
            if ($data['city'])
                $storemulticity = WPJOBPORTALincluder::getJSModel('job')->storeMultiCitiesJob($data['city'], $row->id);
            if (isset($storemulticity) && $storemulticity == false)
                $res = "savecitieserror";

        }
        return $res;
    }

     function getPackagePopupForquickapply(){
            $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
            if (! wp_verify_nonce( $nonce, 'get-package-popup-for-copy-job') ) {
                die( 'Security check Failed' );
            }
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
            $jobid = WPJOBPORTALrequest::getVar('wpjobportalid');
            $subtype = wpjobportal::$_config->getConfigValue('submission_type');
            if( $subtype != 3 ){
                return false;
            }
            $userpackages = array();
            $userpackage = apply_filters('wpjobportal_addons_credit_get_Packages_user',false,$uid,'job');
            $addonclass = '';
            if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled()){
                $addonclass = ' wjportal-elegant-addon-packages-popup ';
            }
            foreach($userpackage as $package){
                if($package->job == -1 || $package->remjob > 0){ //-1 = unlimited
                    $userpackages[] = $package;
                }
            }
            if (wpjobportal::$theme_chk == 1) {
                $content = '
                <div id="wpj-jp-popup-background" style="display: none;"></div>
                <div id="package-popup" class="wpj-jp-popup-wrp wpj-jp-packages-popup">
                    <div class="wpj-jp-popup-cnt-wrp">
                        <i class="fas fa-times wpj-jp-popup-close-icon" data-dismiss="modal"></i>
                        <h3 class="wpj-jp-popup-heading">
                            '.esc_html__("Select Package",'job-portal-theme').'
                            <div class="wpj-jp-popup-desc">
                                '.esc_html__("Please select a package first",'job-portal-theme').'
                            </div>
                        </h3>
                        <div class="wpj-jp-popup-contentarea">
                            <div class="wpj-jp-packages-wrp">';
                                if(count($userpackages) == 0 || empty($userpackages)){
                                    $content .= WPJOBPORTALmessages::showMessage(esc_html__("You do not have any job remaining",'job-portal-theme'),'error',1);
                                } else {
                                    foreach($userpackages as $package){
                                        #User Package For Selection in Popup Model --Views
                                        $content .= '
                                            <div class="wpj-jp-pkg-item" id="package-div-'.$package->id.'" >
                                                <div class="wpj-jp-pkg-item-top">
                                                    <h4 class="wpj-jp-pkg-item-title">
                                                        '.wpjobportal::wpjobportal_getVariableValue( $package->title).'
                                                    </h4>
                                                </div>
                                                <div class="wpj-jp-pkg-item-mid">
                                                    <div class="wpj-jp-pkg-item-row">
                                                        <span class="wpj-jp-pkg-item-tit">
                                                            '.esc_html__("Job",'job-portal-theme').' :
                                                        </span>
                                                        <span class="wpj-jp-pkg-item-val">
                                                            '.($package->job==-1 ? esc_html__("Unlimited",'job-portal-theme') : $package->job).'
                                                        </span>
                                                    </div>
                                                    <div class="wpj-jp-pkg-item-row">
                                                        <span class="wpj-jp-pkg-item-tit">
                                                            '.esc_html__("Remaining",'job-portal-theme').' :
                                                        </span>
                                                        <span class="wpj-jp-pkg-item-val">
                                                            '.($package->job==-1 ? esc_html__("Unlimited",'job-portal-theme') : $package->remjob).'
                                                        </span>
                                                    </div>
                                                    <div class="wpj-jp-pkg-item-row">
                                                        <span class="wpj-jp-pkg-item-tit">
                                                            '.esc_html__("Expiry",'job-portal-theme').' :
                                                        </span>
                                                        <span class="wpj-jp-pkg-item-val">
                                                            '.$package->jobtime.' '.$package->jobtimeunit.'
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="wpj-jp-pkg-item-btm">
                                                    <a href="#" class="wpj-jp-outline-btn wpj-jp-block-btn" onclick="selectPackage('.$package->id.');" title="'.esc_attr__("Select package","job-portal-theme").'">
                                                        '.esc_html__("Select Package","job-portal-theme").'
                                                    </a>
                                                </div>
                                            </div>
                                        ';
                                    }
                                }
                            $content .= '</div>
                            <div class="wpj-jp-popup-msgs" id="wjportal-package-message">&nbsp;</div>
                        </div>
                        <div class="wpj-jp-visitor-msg-btn-wrp">
                            <form action="'.esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'quickapply','action'=>'wpjobportaltask','task'=>'addtoquickapply','wpjobportalid'=>$jobid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'copy-job')).'" method="post">
                                <input type="hidden" id="wpjobportal_packageid" name="wpjobportal_packageid">
                                <input type="submit" rel="button" id="jsre_featured_button" class="wpj-jp-visitor-msg-btn" value="'.esc_attr__('Add To Job','job-portal-theme').'" disabled/>
                            </form>
                        </div>
                    </div>
                </div>';
            } else {
            $content = '
            <div id="wjportal-popup-background" style="display: none;"></div>
            <div id="package-popup" class="wjportal-popup-wrp wjportal-packages-popup '.$addonclass.'">
                <div class="wjportal-popup-cnt">
                    <img id="wjportal-popup-close-btn" alt="'.__('popup close','wp-job-portal').'" title="'.__('popup close','wp-job-portal').'" src="'.WPJOBPORTAL_PLUGIN_URL.'includes/images/popup-close.png">
                    <div class="wjportal-popup-title">
                        '.__("Select Package",'wp-job-portal').'
                        <div class="wjportal-popup-title3">
                            '.__("Please select a package first",'wp-job-portal').'
                        </div>
                    </div>
                    <div class="wjportal-popup-contentarea">
                        <div class="wjportal-packages-wrp">';
                            if(count($userpackages) == 0 || empty($userpackages)){
                                $content .= WPJOBPORTALmessages::showMessage(__("You do not have any job remaining",'wp-job-portal'),'error',1);
                            } else {
                                foreach($userpackages as $package){
                                    #User Package For Selection in Popup Model --Views
                                    $content .= '
                                        <div class="wjportal-pkg-item" id="package-div-'.$package->id.'" >
                                            <div class="wjportal-pkg-item-top">
                                                <div class="wjportal-pkg-item-title">
                                                    '.$package->title.'
                                                </div>
                                            </div>
                                            <div class="wjportal-pkg-item-btm">
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.__("Job",'wp-job-portal').'. :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.($package->job==-1 ? __("Unlimited",'wp-job-portal') : $package->job).'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.__("Remaining",'wp-job-portal').'. :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.($package->job==-1 ? __("Unlimited",'wp-job-portal') : $package->remjob).'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.__("Expiry",'wp-job-portal').'. :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.$package->jobtime.' '.$package->jobtimeunit.'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-btn-row">
                                                    <a href="#" class="wjportal-pkg-item-btn" onclick="selectPackage('.$package->id.');">
                                                        '.__("Select Package","wp-job-portal").'
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                            }
                        $content .= '</div>
                        <div class="wjportal-popup-msgs" id="wjportal-package-message">&nbsp;</div>
                    </div>
                    <div class="wjportal-visitor-msg-btn-wrp">
                        <form action="'.wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'quickapply','action'=>'wpjobportaltask','task'=>'addtoquickapply','wpjobportalid'=>$jobid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'copy-job').'" method="post">
                            <input type="hidden" id="wpjobportal_packageid" name="wpjobportal_packageid">
                            <input type="submit" rel="button" id="jsre_featured_button" class="wjportal-visitor-msg-btn" value="'.__('Add To Job','wp-job-portal').'" disabled/>
                        </form>
                    </div>
                </div>
            </div>';
            }
            echo wp_kses($content, WPJOBPORTAL_ALLOWED_TAGS);
            exit();
    }


    function captchaValidate() {
        if (!is_user_logged_in()) {
            $config_array = wpjobportal::$_config->getConfigByFor('captcha');
            //$captcha_check = $config_array['job_captcha'];
            $captcha_check  = wpjobportal::$_config->getConfigurationByConfigName('quick_apply_captcha');
            if ($captcha_check == 1) {
                if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                    $google_recaptcha = WPJOBPORTALrequest::getVar('g-recaptcha-response','post','');
                    if($google_recaptcha != ''){
                        $gresponse = wpjobportal::wpjobportal_sanitizeData($google_recaptcha);
                    }
                    $resp = googleRecaptchaHTTPPost($config_array['recaptcha_privatekey'] , $gresponse);

                    if ($resp) {
                        return true;
                    } else {
                        wpjobportal::$_data['google_captchaerror'] = esc_html(__("Invalid captcha",'wp-job-portal'));
                        return false;
                    }
                } else { // own captcha
                    $captcha = new WPJOBPORTALcaptcha;
                    $result = $captcha->checkCaptchaUserForm();
                    if ($result == 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }



    function quickApplyOnJob(){
        $data = WPJOBPORTALrequest::get('post'); // form data
        if(empty($data)){
            return false;
        }

        if(!$this->captchaValidate()){
            WPJOBPORTALMessages::setLayoutMessage(__('Incorrect Captcha code', 'wp-job-portal'), 'error',WPJOBPORTALincluder::getJSModel('job')->getMessagekey());
            return false;
        }

        // captcha

        // make sure that minimum data is present
        if($data['full_name'] == '' || $data['email'] == '' || $data['jobid'] == ''){
            //return WPJOBPORTAL_SAVE_ERROR;
        }

        $resume_data = array();

        $resume_data['first_name'] = $data['full_name'];
        $resume_data['email_address'] = $data['email'];
        $resume_data['cell'] = $data['phone'];
        $resume_data['created'] = gmdate('Y-m-d H:i:s');
        $resume_data['last_modified'] = gmdate('Y-m-d H:i:s');
        $resume_data['status'] = 1;
        $resume_data['uid'] = WPJOBPORTALincluder::getObjectClass('user')->uid();
        //
        $resume_data['quick_apply'] = 1;

        $alias = wpjobportalphplib::wpJP_str_replace(' ', '-', $resume_data['first_name']);
        $alias = wpjobportalphplib::wpJP_str_replace('_', '-', $alias);

        $resume_data['alias'] = $alias;

        $resume_data = wpjobportal::wpjobportal_sanitizeData($resume_data);
        $resume_data = WPJOBPORTALincluder::getJSmodel('common')->stripslashesFull($resume_data);// remove slashes with quotes.
        $row = WPJOBPORTALincluder::getJSTable('resume');

        if (!$row->bind($resume_data)) {
            die('bind failed 393');
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            die('store failed 397');
            return WPJOBPORTAL_SAVE_ERROR;
        }

        // uploading file to resume files

        // the below code is to modify the data to use existing file upload code
        if(isset($_FILES['resumefiles']) && !empty($_FILES['resumefiles'])){
            $outputArray = array(
                'name' => array($_FILES['resumefiles']['name']),
                'type' => array($_FILES['resumefiles']['type']),
                'tmp_name' => array($_FILES['resumefiles']['tmp_name']),
                'error' => array($_FILES['resumefiles']['error']),
                'size' => array($_FILES['resumefiles']['size'])
            );
            $_FILES['resumefiles'] = $outputArray;

            WPJOBPORTALincluder::getJSmodel('resume')->uploadResume($row->id);
        }

        // setting variables here to accomodate exsisting code without change
        wpjobportal::$_data['sanitized_args']['js_nonce'] = wp_create_nonce('wp-job-portal-nonce');
        wpjobportal::$_data['sanitized_args']['jobid'] = $data['jobid'] ;
        wpjobportal::$_data['sanitized_args']['cvid'] = $row->id;// newly created resume id
        wpjobportal::$_data['sanitized_args']['quick_apply'] = 1;
        if(isset($data['message'])){
            wpjobportal::$_data['sanitized_args']['message'] = $data['message'];
        }

        // calling the job apply function with "1" for $themecall to make sure it returns a numric value for the status of job apply
        //$job_applied = WPJOBPORTALincluder::getJSmodel('jobapply')->jobapply(1);



        return $row->id; // returning resume id



        // echo var_dump($job_applied);
        // echo '<pre>';print_r(wpjobportal::$_data['sanitized_args']);echo '</pre>';
        // die('asd');

        // $data['created'] = current_time('mysql');
        // $data['status'] = 1;
        // $row = WPJOBPORTALincluder::getJSTable('quickapply');
        // echo '<pre>';print_r($row);echo '</pre>';
        // $data = wpjobportal::wpjobportal_sanitizeData($data);
        // $data = WPJOBPORTALincluder::getJSmodel('common')->stripslashesFull($data);// remove slashes with quotes.
        // if (!$row->bind($data)) {
        //     die('bind failed 375');
        //     return WPJOBPORTAL_SAVE_ERROR;
        // }
        // if (!$row->store()) {
        //     die('store failed 379');
        //     return WPJOBPORTAL_SAVE_ERROR;
        // }

        // $apply_id = $row->id;
        // //wpjobportal::$_wpjpcustomfield->storeCustomFields(5,$apply_id,$data);


        // # save company logo
        // // if(isset($data['company_logo_deleted'])){
        // //     $this->deleteCompanyLogoModel($companyid);
        // // }
        // if(isset($_FILES['resume'])){// min field issue
        //     if ($_FILES['resume']['size'] > 0) {
        //         // if(!isset($data['company_logo_deleted'])){
        //         //     $this->deleteCompanyLogoModel($companyid);
        //         // }
        //         $res = $this->uploadFile($apply_id);
        //         if ($res == 6){
        //             $msg = WPJOBPORTALMessages::getMessage(WPJOBPORTAL_FILE_TYPE_ERROR, '');
        //             WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],'job');
        //         }
        //         if($res == 5){
        //             $msg = WPJOBPORTALMessages::getMessage(WPJOBPORTAL_FILE_SIZE_ERROR, '');
        //             WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],'job');
        //         }
        //     }
        // }

        // mail is being sent from job apply code
        // if($isnew){
        //     WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, 1, $companyid);
        // }


        return WPJOBPORTAL_SAVED;
    }

    function uploadFile($id) {
        $result =  WPJOBPORTALincluder::getObjectClass('uploads')->uploadQuickApplyResume($id);
        return $result;
    }


    function getMessagekey(){
        $key = 'job';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
