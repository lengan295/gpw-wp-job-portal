<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param WP JOB PORTAL 
 * @param Control 
 */
$html = '';

switch ($control) {
	case 'myresumes':
        if ($myresume->status == 1 || $myresume->status == 3) {
            $config_array_res = wpjobportal::$_data['config'];
            if(in_array('multiresume', wpjobportal::$_active_addons)){
                $mod = "multiresume";
            }else{
                $mod = "resume";
            }
            ?>
            <div class="wjportal-resume-list-btm-wrp">
                <div class="wjportal-resume-action-wrp">
                    <a class="wjportal-resume-act-btn" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'addresume', 'wpjobportalid'=>$myresume->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))); ?>">
                        <?php echo esc_html(__('Edit Resume', 'wp-job-portal')); ?>
                    </a>
                    <?php if ($myresume->status != 3){ ?>
                            <?php if(in_array('multiresume', wpjobportal::$_active_addons)){ ?>
                                <a class="wjportal-resume-act-btn" href="<?php echo esc_url( wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$myresume->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))); ?>">
                            <?php echo esc_html(__('View Resume', 'wp-job-portal')); ?>
                        </a>
                        <?php    } 
                    }
                    if ($config_array_res['system_have_featured_resume'] == 1 && $featuredflag == true && $myresume->status !=3) {
                        do_action('wpjobportal_addons_feature_multiresume',$myresume);
                     } 
                    if($myresume->status == 3){
                        do_action('wpjobportal_addons_makePayment_for_department',$myresume,"payresume");
                    } ?>

                    <a class="wjportal-resume-act-btn" href="<?php echo esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'task'=>'removeresume', 'action'=>'wpjobportaltask', 'wpjobportal-cb[]'=>$myresume->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_resume_nonce')); ?>"onclick='return confirmdelete("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");'>
                        <?php echo esc_html(__('Delete Resume', 'wp-job-portal')); ?>
                    </a>
                    <?php
                    $show_suggested_jobs_button = wpjobportal::$_config->getConfigValue('show_suggested_jobs_button');
                    if($show_suggested_jobs_button == 1){ // show button for suggested jobs
                    ?>
                        <a class="wjportal-resume-act-btn wjportal-resume-act-btn-ai-suggested-jobs" href="<?php echo esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'aisuggestedjobs_resume'=> $myresume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),'wpjobportal_resume_nonce')); ?>">
                            <?php echo esc_html(__('Suggested Jobs', 'wp-job-portal')); ?>
                        </a>
                    <?php }  ?>

                </div>
            </div>
        <?php } elseif ($myresume->status == 0) { ?>
            <div class="wjportal-resume-list-btm-wrp">
                <span class="wjportal-item-act-status wjportal-waiting">
                    <?php echo esc_html(__('Waiting For Approval', 'wp-job-portal')); ?>
                </span>
            </div>
        <?php } elseif ($myresume->status == -1){ ?>
            <div class="wjportal-resume-list-btm-wrp">
                <span class="wjportal-item-act-status wjportal-rejected">
                    <?php echo esc_html(__('Rejected', 'wp-job-portal')); ?>
                </span>
            </div>
          <?php
              } 
         break;
     case 'folderresume':
            do_action('wpjobportal_addons_folderresume_control',$myresume);
         break;
         case 'jobapply': ?>
         <div class="wjportal-resume-list-btm-wrp">
        <div class="wjportal-resume-action-wrp">
            <?php
                $class = 'action-links';
                do_action('wpjobportal_addons_resume_bottom_action_appliedresume',$myresume,$class);  
                echo '
                <a class="wjportal-resume-act-btn" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'jobid'=>$myresume->id, 'wpjobportalid'=>$myresume->resumealiasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))).' title='. esc_html(__('view profile', 'wp-job-portal')) .'>
                    '. esc_html(__('View Profile', 'wp-job-portal')) .'
                </a>';
            ?>
        </div>
    </div>
        <?php
        break;
    case 'payresume':
    case 'payfeaturedresume':
        do_action('wpjobportal_addons_proceedPayment_PerListing',$myresume->resumealiasid,'resume','myresumes');
        break;


}
?>
