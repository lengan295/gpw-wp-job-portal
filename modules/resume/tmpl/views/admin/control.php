<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
?>
<?php

switch ($control) {
    case 'resume':
        $featuredflag = true;
        $dateformat = wpjobportal::$_configuration['date_format'];
        $curdate = date_i18n('Y-m-d');
        $featuredexpiry = date_i18n('Y-m-d', strtotime($resume->endfeatureddate));
        if ($resume->isfeaturedresume == 1 && $featuredexpiry >= $curdate) {
            $featuredflag = false;
        }
        ?>

        <div id="item-actions" class="wpjobportal-resume-action-wrp">
            <?php 
                $config_array = wpjobportal::$_data['config'];
             ?>
            <a class="wpjobportal-resume-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_resume&task=removeresume&wpjobportal-cb[]='.esc_attr($resume->id).'&action=wpjobportaltask&callfrom=1'),'wpjobportal_resume_nonce')) ;?>" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
                <?php echo esc_html(__('Delete', 'wp-job-portal')); ?>
            </a>
            <a class="wpjobportal-resume-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_resume&task=resumeEnforceDelete&action=wpjobportaltask&resumeid='.esc_attr($resume->id).'&callfrom=1'),'wpjobportal_resume_nonce')) ;?>" onclick='return confirmdelete("<?php echo esc_html(__('Are you sure to force delete', 'wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('enforce delete', 'wp-job-portal')) ?>">
                <?php echo esc_html(__('Enforce Delete', 'wp-job-portal')) ?>
            </a>
            <?php do_action('wpjobportal_addons_feature_for_resume',$config_array,$resume,$featuredflag); ?>
            <a class="wpjobportal-resume-act-btn" href="admin.php?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid=<?php echo esc_attr($resume->id); ?>" title="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
                <?php echo esc_html(__('Edit', 'wp-job-portal')); ?>
            </a>
            <a class="wpjobportal-resume-act-btn" href="admin.php?page=wpjobportal_resume&wpjobportallt=viewresume&wpjobportalid=<?php echo esc_attr($resume->id); ?>" title="<?php echo esc_html(__('view', 'wp-job-portal')); ?>">
                <?php echo esc_html(__('View', 'wp-job-portal')); ?>
            </a>
        </div>
        <?php
    break;
    case 'resumeque':
        $dateformat = wpjobportal::$_configuration['date_format'];
        ?>
        <div class="wpjobportal-resume-action-wrp">
            <a class="wpjobportal-resume-act-btn" href="admin.php?page=wpjobportal_resume&wpjobportallt=viewresume&wpjobportalid=<?php echo esc_attr($resume->id); ?>" title="<?php echo esc_html(__('view', 'wp-job-portal')); ?>">
                <?php echo esc_html(__('View', 'wp-job-portal')); ?>
            </a>                  
            <a class="wpjobportal-resume-act-btn" href="admin.php?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid=<?php echo esc_attr($resume->id); ?>" title="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
                <?php echo esc_html(__('Edit', 'wp-job-portal')); ?>
            </a>
            <?php
                $total = count($arr);
                if ($total == 3) {
                    $objid = 4; //for all
                } elseif ($total != 1) {
                }
                if ($total == 1) {
                    if (isset($arr['self'])) {
                        ?>
                        <a class="wpjobportal-resume-act-btn" href="admin.php?page=wpjobportal_resume&task=approveQueueResume&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>" title="<?php echo esc_html(__('approve', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Approve', 'wp-job-portal')); ?>
                        </a>
                    <?php
                    }
                    if (isset($arr['feature']) && in_array('featureresume', wpjobportal::$_active_addons)) {
                        ?>
                        <a class="wpjobportal-resume-act-btn" href="admin.php?page=wpjobportal_resume&task=approveQueueFeatureResume&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>" title="<?php echo esc_html(__('feature approve', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Feature Approve', 'wp-job-portal')); ?>
                        </a>
                    <?php
                    }
                } /*else {
                    ?>
                    <div class="wpjobportal-resume-act-btn jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover='approveActionPopup("<?php echo esc_js($resume->id); ?>");'>
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/publish-icon.png">
                        <?php echo esc_html(__('Approve', 'wp-job-portal')); ?>
                        <div id="wpjobportal-queue-actionsbtn" class="jobsqueueapprove_<?php echo esc_attr($resume->id); ?>">
                            <?php if (isset($arr['self'])) { ?>
                                <a id="wpjobportal-act-row" class="wpjobportal-act-row" href="admin.php?page=wpjobportal_resume&task=approveQueueResume&id=<?php echo esc_url($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>"><img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/comapny-logo.png"><?php echo esc_html(__("Resume Approve", 'wp-job-portal')); ?></a>
                            <?php } ?>
                            <a id="wpjobportal-act-row-all" class="wpjobportal-act-row-all" href="admin.php?page=wpjobportal_resume&task=approveQueueAllResumes&objid=<?php echo esc_url($objid); ?>&id=<?php echo esc_url($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>">
                                <img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/select-all.png">
                                <?php echo esc_html(__("All Approve", 'wp-job-portal')); ?>
                            </a>
                        </div>
                    </div>
                    <?php
                } // End approve */
                if ($total == 1) {
                    if (isset($arr['self'])) {
                        ?>
                        <a class="wpjobportal-resume-act-btn" href="admin.php?page=wpjobportal_resume&task=rejectQueueResume&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>" title="<?php echo esc_html(__('reject', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Reject', 'wp-job-portal')); ?>
                        </a>
                    <?php
                    }
                    if (isset($arr['feature']) && in_array('featureresume', wpjobportal::$_active_addons)) {
                        ?>
                        <a class="wpjobportal-resume-act-btn" href="admin.php?page=wpjobportal_resume&task=rejectQueueFeatureResume&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>" title="<?php echo esc_html(__('feature reject', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Feature Reject', 'wp-job-portal')); ?>
                        </a>
                    <?php
                    }
                } /*else {
                    ?>
                    <div class="wpjobportal-resume-act-btn jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover='rejectActionPopup("<?php echo esc_attr($resume->id); ?>");'><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/reject-s.png">  <?php echo esc_html(__('Reject', 'wp-job-portal')); ?>
                        <div id="wpjobportal-queue-actionsbtn" class="jobsqueuereject_<?php echo esc_attr($resume->id); ?>">
                            <?php if (isset($arr['self'])) { ?>
                                <a id="wpjobportal-act-row" class="wpjobportal-act-row" href="admin.php?page=wpjobportal_resume&task=rejectQueueResume&id=<?php echo esc_url($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>">
                                    <img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/comapny-logo.png">
                                    <?php echo esc_html(__("Resume Reject", 'wp-job-portal')); ?>
                                </a>
                            <?php
                            } ?>
                            <a id="wpjobportal-act-row-all" class="wpjobportal-act-row-all" href="admin.php?page=resume&task=rejectQueueAllResumes&objid=<?php echo esc_url($objid); ?>&id=<?php echo esc_url($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>">
                                <img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/select-all.png">
                                <?php echo esc_html(__("All Reject", 'wp-job-portal')); ?>
                            </a>
                        </div>
                    </div>
            <?php }//End Reject */ ?>
            <a class="wpjobportal-resume-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_resume&task=removeresume&wpjobportal-cb[]='.esc_attr($resume->id)),'wpjobportal_resume_nonce')); ?>&action=wpjobportaltask&callfrom=2" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
                <?php echo esc_html(__('Delete', 'wp-job-portal')); ?>
            </a>
            <a class="wpjobportal-resume-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_resume&task=resumeEnforceDelete&resumeid='.esc_attr($resume->id)),'wpjobportal_resume_nonce')); ?>&action=wpjobportaltask&callfrom=2" onclick='return confirmdelete("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).'?'; ?>");'  title="<?php echo esc_html(__('force delete', 'wp-job-portal')); ?>">
                <?php echo esc_html(__('Force Delete', 'wp-job-portal')); ?>
            </a>
        </div>
        <?php
    break;
    case 'jobapply':
        $class = 'wpjobportal-resume-act-btn';
        ?>
         <div id="item-actions" class="wpjobportal-resume-action-wrp">
            <a id="view-resume" class="wpjobportal-resume-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid='.esc_attr($data->appid))); ?>" title="<?php echo esc_html(__('view profile', 'wp-job-portal')); ?>">
                <?php echo esc_html(__('View Profile', 'wp-job-portal')); ?>
            </a>
            <?php 
                do_action('wpjobportal_addons_resume_bottom_action_appliedresume',$data,$class);
                do_action('wpjobportal_addons_resume_bottom_action_appliedresume_exc',wpjobportal::$_data['jobid'],$data);
            ?>
        </div>
        <?php
        break;
}
