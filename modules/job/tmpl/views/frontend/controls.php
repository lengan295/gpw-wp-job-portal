<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param WP JOB PORTAL
* @param WP Control My Jobs
* @param Feature Job - Copy Job
*/
?>
<?php
switch ($control) {
    case 'myjobs':
        $featuredexpiry = date_i18n('Y-m-d', strtotime($job->endfeatureddate));
        $print = WPJOBPORTALincluder::getJSModel('job')->checkLinks('noofjobs'); 
        $startdate = date_i18n('Y-m-d',strtotime($job->startpublishing));
        $enddate = date_i18n('Y-m-d',strtotime($job->stoppublishing));
        $curdate = date_i18n('Y-m-d');
        echo '<div class="wjportal-jobs-list-btm-wrp">
                <div class="wjportal-jobs-action-wrp">';
                    if($job->status == 1 || $job->status == 3){
                        echo '<a class="wjportal-jobs-act-btn" title ='.esc_html(__('Edit Job','wp-job-portal')).' href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'addjob', 'wpjobportalid'=>$job->id))).'>'. esc_html(__('Edit Job', 'wp-job-portal')).'</a>';
                    }

                    $config_array = wpjobportal::$_data['config'];
                    if($job->status != 3 && $job->status != 4){
                        #Feature Job--
                        do_action('wpjobportal_credit_addons_feature_job_popup',$config_array,$job,$featuredexpiry);
                    }
                    if($job->status != 4){ ?>
                    <a class="wjportal-jobs-act-btn" href="<?php echo esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'task'=>'remove', 'action'=>'wpjobportaltask', 'wpjobportal-cb[]'=>$job->id,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_job_nonce')); ?>" onclick='return confirmdelete("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");'><?php echo esc_html(__('Delete Job', 'wp-job-portal')); ?></a>
                    <?php }
                    # Copy Job --
                    do_action('wpjobportal_addons_credit_popup_copy_job',$job);
                    if($job->status != 3 && $job->status != 4 ){
                       echo '<a class="wjportal-jobs-act-btn wjportal-jobs-apply-res" title = '.esc_html(__('Resume','wp-job-portal')).' href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply', 'wpjobportallt'=>'jobappliedresume', 'jobid'=>$job->id))).'>'. esc_html(__('Resume', 'wp-job-portal')) . " (" . esc_html($job->resumeapplied) . ")".'</a>';
                    }
                   if($job->status == 0){
                        echo '
                                <span class="wjportal-item-act-status wjportal-waiting">'. esc_html(__('Waiting For Approval', 'wp-job-portal')).'</span>
                            ';
                    }elseif($job->status == -1){
                        #Rejected Job
                        echo '
                                <span class="wjportal-item-act-status wjportal-rejected">'.esc_html(__('Rejected', 'wp-job-portal')).'</span>
                            ';
                 }elseif ($job->status == 3) {
                    # job perlisting --payment
                    do_action('wpjobportal_addons_makePayment_for_department',$job,'payjob'); 
                }  

                    $show_suggested_resumes_button = wpjobportal::$_config->getConfigValue('show_suggested_resumes_button');
                    if($show_suggested_resumes_button == 1){ ?>
                        <a class="wjportal-jobs-act-btn wjportal-jobs-act-btn-ai-suggested-resumes" href="<?php echo esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumes', 'aisuggestedresumes_job'=>$job->jobaliasid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_job_nonce')); ?>"><?php echo esc_html(__('Suggested Resumes', 'wp-job-portal')); ?></a>
                    <?php
                    }

                // close action wrp
        echo '</div> 
            </div>'; /* close bottom wrp */
        break;
        case 'resumetitle':
         ?><div class="wjportal-jobs-list-btm-wrp" id="full-width-top">
            <div class="wjportal-jobs-list-resume-wrp">
                <?php
                    if(in_array('credits', wpjobportal::$_active_addons) && $job->applystatus == 3){
                       do_action('wpjobportal_addons_makePayment_for_department',$job,'payjobapply');
                    }
                    $val_lable = __('Name', 'wp-job-portal');
                    $val_value = $job->first_name . ' ' .$job->last_name;

                    if($job->application_title != ''){
                       $val_lable = __('Resume Title', 'wp-job-portal');
                       $val_value = $job->application_title;
                    }
                ?>
                <div class="wjportal-jobs-list-resume-data">
                    <span class="wjportal-jobs-list-resume-tit">
                        <?php echo esc_html($val_lable).': '; ?>
                    </span>
                    <span class="wjportal-jobs-list-resume-val">
                        <a href="<?php echo esc_url( wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume','wpjobportallt'=>'viewresume','wpjobportalid'=>$job->resumeid,'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())) );?>">
                            <?php echo esc_html($val_value);?>
                        </a>
                    </span> 
                </div>
                <!-- applied job resume status -->

                    <?php do_action('wpjobportal_addons_resume_action_jobapplied_status',$job);

                    if(in_array('coverletter', wpjobportal::$_active_addons) ){ ?>
                        <div class="wjportal-jobs-list-resume-data">
                            <span class="wjportal-jobs-list-resume-tit">
                                <?php echo esc_html(__('Cover Letter Title', 'wp-job-portal')).': '; ?>
                            </span>
                            <span class="wjportal-jobs-list-resume-val">
                                <?php
                                    echo esc_html($job->coverlettertitle);
                                ?>
                            </span>
                        </div>
                    <?php }
                    /*
                    if(isset($job->apply_message) && $job->apply_message !=''){
                    $apply_message_label = wpjobportal::$_wpjpfieldordering->getFieldTitleByFieldAndFieldfor('message',5);
                    ?>
                        <div class="wjportal-jobs-list-resume-data">
                            <span class="wjportal-jobs-list-resume-tit">
                                <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($apply_message_label)).': '; ?>
                            </span>
                            <span class="wjportal-jobs-list-resume-val">
                                <?php
                                    echo esc_html($job->apply_message);
                                ?>
                            </span>
                        </div>
                    <?php }
                    */ ?>
                </div>
            </div>
            <?php
        break;
    case 'shortlistjob': ?>
        <?php
            $applied =  WPJOBPORTALincluder::getJSmodel('jobapply')->checkAlreadyAppliedJob($job->jobid,WPJOBPORTALincluder::getObjectClass('user')->uid());
            if ($applied == true) {
                $desc = __("You have Already Applied", 'wp-job-portal');
            }else{
                $desc = __("Apply Now", 'wp-job-portal');
            }
        ?>
        <div class="wjportal-jobs-list-btm-wrp">
            <div class="wjportal-jobs-action-wrp">
                <?php $allow_tellafriend  = wpjobportal::$_config->getConfigurationByConfigName('allow_tellafriend');
                if($allow_tellafriend == 1){
                   do_action('wpjobportal_addons_tellfriend_shorlist',$job->jobid);
                 } ?>
               <a class="wjportal-jobs-act-btn" href="<?php echo  esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid(),'wpjobportalme'=>'shortlist', 'action'=>'wpjobportaltask', 'task'=>'removeshortlist', 'wpjobportalid'=>$job->slid)),'wpjobportal_shortlist_job_nonce')); ?>"><?php echo esc_html(__('Delete Job', 'wp-job-portal')); ?></a><?php
                $config_array = wpjobportal::$_data['config'];
                /*
                $show_apply_form  = wpjobportal::$_config->getConfigurationByConfigName('quick_apply_for_user');
                if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                    $show_apply_form  = wpjobportal::$_config->getConfigurationByConfigName('quick_apply_for_visitor');
                }
                if($show_apply_form == 0){ // hide apply now button if quick apply is enabled
                    if($config_array['showapplybutton'] == 1){
                        if($job->jobapplylink == 1 && !empty($job->joblink)){
                            if(!wpjobportalphplib::wpJP_strstr('http',$job->joblink)){
                                $job->joblink = 'http://'.$job->joblink;
                            } ?>
                            <a class="wjportal-jobs-act-btn" href= "<?php echo esc_url($job->joblink) ;?>" target="_blank" ><?php echo esc_html(__('Apply Now','wp-job-portal')); ?></a><?php
                        }elseif(!empty($config_array['applybuttonredirecturl'])){
                            if(!wpjobportalphplib::wpJP_strstr('http',$config_array['applybuttonredirecturl'])){
                                $joblink = 'http://'.$config_array['applybuttonredirecturl'];
                            }else{
                                $joblink = $config_array['applybuttonredirecturl'];
                            } ?>
                            <a class="wjportal-jobs-act-btn" href= "<?php echo esc_url($joblink); ?>" target="_blank" ><?php echo esc_html(__('Apply Now','wp-job-portal')); ?></a><?php
                        }else{
                            if(WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()){
                                if(in_array('credits', wpjobportal::$_active_addons)){
                                    //if($applied == true){
                                    $submission_type = wpjobportal::$_config->getConfigValue('submission_type');
                                    if($submission_type == 1){
                                        if($applied == true){?>
                                            <a class="wjportal-jobs-act-btn" href="#" onclick="wpjobportalPopup('job_apply', '<?php echo esc_js($job->jobid); ?>',<?php echo esc_js(wpjobportal::wpjobportal_getPageid());?>)"><?php echo esc_html(__('Apply Now', 'wp-job-portal')) ?></a><?php
                                        }else{
                                            echo'<a class="wjportal-job-jobapply-btn wjportal-jobs-act-btn" href="#" >'. esc_html(__("You have Already Applied",'wp-job-portal')) .' </a>';
                                        }
                                    }elseif ($submission_type == 2) {
                                        $payment = WPJOBPORTALincluder::getJSmodel('jobapply')->checkjobappllystats($job->jobid,WPJOBPORTALincluder::getObjectClass('user')->uid());
                                       // echo $payment;
                                        //echo $applied;
                                        if($payment == true && $payment == false){ ?>
                                            <a class="wjportal-jobs-act-btn" href="#" onclick="wpjobportalPopup('job_apply', '<?php echo esc_js($job->jobid); ?>',<?php echo esc_js(wpjobportal::wpjobportal_getPageid());?>)"><?php echo esc_html(__('Apply Now', 'wp-job-portal')) ?></a><?php
                                        }
                                        if($payment == false && $applied != true){
                                                $arr = array('wpjobportalme'=>'purchasehistory','wpjobportallt'=>'payjobapply','wpjobportalid'=>$job->jobid);
                                                echo '<a class="wjportal-job-act-btn" href='. esc_url(wpjobportal::wpjobportal_makeUrl($arr)).' title='. esc_attr(esc_html(__('make payment','wp-job-portal'))).'>
                                                 '. esc_html(esc_html(__('Make Payment To Apply', 'wp-job-portal'))).'
                                                 </a>';
                                        }else{
                                                echo'<a class="wjportal-job-jobapply-btn wjportal-jobs-act-btn" href="#" >'. esc_html(__("You have Already Applied",'wp-job-portal')) .' </a>';
                                        }
                                    }elseif ($submission_type == 3) {
                                        if($applied == true){

                                         echo'<a class="wjportal-job-jobapply-btn wjportal-jobs-act-btn" href="#" onclick="getPackagePopupJobView('. esc_js($job->jobid) .')">'. esc_html(__("Apply On This Job",'wp-job-portal')) .' </a>';
                                        }else{
                                            echo'<a class="wjportal-job-jobapply-btn wjportal-jobs-act-btn" href="#" >'. esc_html(__("You have Already Applied",'wp-job-portal')) .' </a>';
                                        }
                                    }
                                }else{ ?>
                                    <a class="wjportal-jobs-act-btn" href="#" onclick="getApplyNowByJobid('<?php echo esc_js($job->jobid); ?>',<?php echo esc_js(wpjobportal::wpjobportal_getPageid());?>)"><?php echo esc_html(__('Apply Now', 'wp-job-portal')) ?></a><?php
                                }
                            }else{ ?>
                                <a class="wjportal-jobs-act-btn" href="#" onclick="getApplyNowByJobid('<?php echo esc_js($job->jobid); ?>',<?php echo esc_js(wpjobportal::wpjobportal_getPageid());?>);"><?php echo esc_html($desc); ?></a><?php
                            }
                        }
                    }
                }// closing if for quick apply check
                */
                ?>
                <div class="wjportal-shortlist-stars">
                    <?php
                        if(isset($control)){
                            if($control == "shortlistjob"){
                                do_action('wpjobportal_addons_upper_lable_shortlist_rating',$job);
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    <?php   break;
    case 'payjob':
        do_action('wpjobportal_addons_proceedPayment_PerListing',$job->jobid,'job','myjobs');
        break;
    case 'payjobapply':
        do_action('wpjobportal_addons_proceedPayment_PerListing',$job->jobaliasid,'job','viewjob');
        break;
}

       
