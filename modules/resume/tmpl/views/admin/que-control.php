<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param
*/
$dateformat = wpjobportal::$_configuration['date_format'];
?>
<div id="wpjobportal-bottom-comp">
    <div id="bottomleftnew" class="resumepaddingleftqueue">
        <span class="js-created"><b><?php echo esc_html(__('Created', 'wp-job-portal')); ?></b>: <span class="color"><?php echo esc_html(date_i18n($dateformat, strtotime($resume->created))); ?></span></span>
    </div>
    <div id="bottomrightnew">
        <a class="js-bottomspan" href="admin.php?page=wpjobportal_resume&wpjobportallt=viewresume&wpjobportalid=<?php echo esc_attr($resume->id); ?>" ><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/ad-resume.png" alt="del">  <?php echo esc_html(__('View', 'wp-job-portal')); ?></a>
        <a class="js-bottomspan" href="admin.php?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid=<?php echo esc_attr($resume->id); ?>" ><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/delete-icon.png" alt="del">  <?php echo esc_html(__('Edit', 'wp-job-portal')); ?></a>
    <?php
        $total = count($arr);
        if ($total == 3) {
            $objid = 4; //for all
        } elseif ($total != 1) {
        }
        if ($total == 1) {
            if (isset($arr['self'])) {
                ?>
                <a class="js-bottomspan" href="admin.php?page=wpjobportal_resume&task=approveQueueResume&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/hired.png"><?php echo esc_html(__('Approve', 'wp-job-portal')); ?></a>
            <?php
            }
        } else {
            ?>
            <div class="js-bottomspan jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover='approveActionPopup("<?php echo esc_js($resume->id); ?>");'><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/publish-icon.png">  <?php echo esc_html(__('Approve', 'wp-job-portal')); ?>
                <div id="wpjobportal-queue-actionsbtn" class="jobsqueueapprove_<?php echo esc_attr($resume->id); ?>">
                    <?php if (isset($arr['self'])) { ?>
                        <a id="wpjobportal-act-row" class="wpjobportal-act-row" href="admin.php?page=wpjobportal_resume&task=approveQueueResume&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>"><img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/comapny-logo.png"><?php echo esc_html(__("Resume Approve", 'wp-job-portal')); ?></a>
                    <?php
                    } ?>
                    <a id="wpjobportal-act-row-all" class="wpjobportal-act-row-all" href="admin.php?page=wpjobportal_resume&task=approveQueueAllResumes&objid=<?php echo esc_attr($objid); ?>&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>"><img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/select-all.png"><?php echo esc_html(__("All Approve", 'wp-job-portal')); ?></a>
                </div>
            </div>
            <?php
        } // End approve
        if ($total == 1) {
            if (isset($arr['self'])) {
                ?>
                <a class="js-bottomspan" href="admin.php?page=wpjobportal_resume&task=rejectQueueResume&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/reject-s.png"><?php echo esc_html(__('Reject', 'wp-job-portal')); ?></a>
<?php
}
} else {
?>
            <div class="js-bottomspan jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover='rejectActionPopup("<?php echo esc_js($resume->id); ?>");'><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/reject-s.png">  <?php echo esc_html(__('Reject', 'wp-job-portal')); ?>
                <div id="wpjobportal-queue-actionsbtn" class="jobsqueuereject_<?php echo esc_attr($resume->id); ?>">
                    <?php if (isset($arr['self'])) { ?>
                        <a id="wpjobportal-act-row" class="wpjobportal-act-row" href="admin.php?page=wpjobportal_resume&task=rejectQueueResume&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>"><img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/comapny-logo.png"><?php echo esc_html(__("Resume Reject", 'wp-job-portal')); ?></a>
                    <?php
                    } ?>
                    <a id="wpjobportal-act-row-all" class="wpjobportal-act-row-all" href="admin.php?page=resume&task=rejectQueueAllResumes&objid=<?php echo esc_attr($objid); ?>&id=<?php echo esc_attr($resume->id); ?>&action=wpjobportaltask&_wpnonce=<?php echo esc_attr(wp_create_nonce('wpjobportal_resume_nonce'));?>"><img class="jobs-action-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/select-all.png"><?php echo esc_html(__("All Reject", 'wp-job-portal')); ?></a>
                </div>
            </div>
<?php }//End Reject
?>
        <a class="js-bottomspan" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_resume&task=removeresume&wpjobportal-cb[]='.esc_attr($resume->id)),'wpjobportal_resume_nonce')); ?>&action=wpjobportaltask&callfrom=2" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");'>
            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/delete-icon.png" alt="del" message="<?php echo esc_attr(WPJOBPORTALMessages::getMSelectionEMessage()); ?>">  <?php echo esc_html(__('Delete', 'wp-job-portal')); ?>
        </a>
        <a class="js-bottomspan" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_resume&task=resumeEnforceDelete&resumeid='.esc_attr($resume->id)),'wpjobportal_resume_nonce')); ?>&action=wpjobportaltask&callfrom=2" onclick='return confirmdelete("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).'?'; ?>");' >
            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/fe-forced-delete.png" alt="fdel" message="<?php echo esc_attr(WPJOBPORTALMessages::getMSelectionEMessage()); ?>">  <?php echo esc_html(__('Force Delete', 'wp-job-portal')); ?>
        </a>
    </div>
</div>
