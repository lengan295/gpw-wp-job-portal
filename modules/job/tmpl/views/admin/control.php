<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* 
*/
$dateformat = wpjobportal::$_configuration['date_format'];
?>
<?php
$html = '';
 switch ($layout) {
 	case 'control':
        $config_array = wpjobportal::$_data['config'];
        $featuredflag = true;
        $dateformat = wpjobportal::$_configuration['date_format'];
        $curdate = date_i18n('Y-m-d');
        $featuredexpiry = date_i18n('Y-m-d', strtotime($job->endfeatureddate));
        if ($job->isfeaturedjob == 1 && $featuredexpiry >= $curdate) {
            $featuredflag = false;
        }
 		?>
        <div id="for_ajax_only_<?php echo esc_attr($job->id); ?>">
            <div id="item-actions" class="wpjobportal-jobs-action-wrp">
                <a class="wpjobportal-jobs-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_job&wpjobportallt=formjob&wpjobportalid='.$job->id)); ?>" title="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Edit', 'wp-job-portal')); ?>
                </a>
                <a class="wpjobportal-jobs-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_job&action=wpjobportaltask&task=remove&callfrom=1&wpjobportal-cb[]='.$job->id),'wpjobportal_job_nonce')); ?>" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Delete', 'wp-job-portal')); ?>
                </a>
                <a class="wpjobportal-jobs-act-btn" href="<?php echo esc_attr(wp_nonce_url(admin_url('admin.php?page=wpjobportal_job&action=wpjobportaltask&callfrom=1&task=jobenforcedelete&jobid='.$job->id),'wpjobportal_job_nonce')); ?>" onclick='return confirmdelete("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('force delete', 'wp-job-portal')) ?>">
                    <?php echo esc_html(__('Force Delete', 'wp-job-portal')) ?>
                </a>
                <?php do_action('wpjobportal_addons_admin_feature_for_job',wpjobportal::$_data['config'],$job,$featuredflag); ?>
                <?php do_action('wp_jobportal_addons_copyjob_credit_for_job',$job) ?>
                <a class="wpjobportal-jobs-act-btn wpjobportal-jobs-apply-res" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_jobapply&wpjobportallt=jobappliedresume&jobid='.$job->id)); ?>" title="<?php echo esc_html(__('Applied Resume', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Applied Resume', 'wp-job-portal')) . " (" . esc_html($job->totalresume) . ")" ?>
                </a>
            </div>
        </div>
        <?php
        break;
 	case 'que-control':

        $class_color = '';
        $arr = array();
        if ($job->status == 0) {
            if ($class_color == '') {
                ?>
            <?php } ?>
            <?php
            $class_color = 'q-self';
            $arr['self'] = 1;
        }
        if ($job->isfeaturedjob == 0) {
            if ($class_color == '') {
                ?>
            <?php } ?>
            <?php
            $class_color = 'q-feature';
            $arr['feature'] = 1;
        }
        $total = count($arr);
        if ($total == 3) {
            $objid = 4; //for all
        } elseif ($total != 1) {
            if (isset($arr['self']) && isset($arr['gold'])) {
                $objid = 1; // for job&gold
            } elseif (isset($arr['self']) && isset($arr['feature'])) {
                $objid = 2; //for job&feature
            } else {
                $objid = 3; //for gold&feature
            }
        }

        $html.='<div class="wpjobportal-jobs-action-wrp">';
                    $total = count($arr);
                    if ($total == 3) {
                        $objid = 4; //for all
                    }
                    if ($total == 1) {
                        if (isset($arr['self'])) {
                           
                            $html.='<a class="wpjobportal-jobs-act-btn" href="admin.php?page=wpjobportal_job&task=approveQueueJob&id='.$job->id.'&action=wpjobportaltask&_wpnonce='.wp_create_nonce('wpjobportal_job_nonce').'" title='. esc_html(__('approve', 'wp-job-portal')).'>
                                '. esc_html(__('Job Approve', 'wp-job-portal')).'
                            </a>';
                        }
                        if (isset($arr['feature']) && in_array('featuredjob', wpjobportal::$_active_addons)) {
                           
                            $html.='<a class="wpjobportal-jobs-act-btn" href="admin.php?page=wpjobportal_job&task=approveQueueFeaturedJob&id='.$job->id.'&action=wpjobportaltask&_wpnonce='.wp_create_nonce('wpjobportal_job_nonce').'" title='. esc_html(__('approve', 'wp-job-portal')).'>
                                '. esc_html(__('Feature Approve', 'wp-job-portal')).'
                            </a>';
                        }
                    } else {
                        $html.='
                        <div class="wpjobportal-jobs-act-btn jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover="approveActionPopup('. $job->id.');">
                            '. esc_html(__('Approve', 'wp-job-portal')).'';
                        $html.='</div>';
                    } // End approve
                    if ($total == 1) {
                        if (isset($arr['self'])) {
                            $html.='<a class="wpjobportal-jobs-act-btn" href="admin.php?page=wpjobportal_job&task=rejectQueueJob&id='. $job->id.'&action=wpjobportaltask&_wpnonce='.wp_create_nonce('wpjobportal_job_nonce').'" title='.  esc_html(__('reject', 'wp-job-portal')).'>
                                '.  esc_html(__('Job Reject', 'wp-job-portal')).'
                            </a>';
                        }
                        if (isset($arr['feature']) && in_array('featuredjob', wpjobportal::$_active_addons)) {
                            $html.='<a class="wpjobportal-jobs-act-btn" href="admin.php?page=wpjobportal_job&task=rejectQueueFeaturedJob&id='. $job->id.'&action=wpjobportaltask&_wpnonce='.wp_create_nonce('wpjobportal_job_nonce').'" title='.  esc_html(__('reject', 'wp-job-portal')).'>
                                '.  esc_html(__('Feature Reject', 'wp-job-portal')).'
                            </a>';
                        }
                    } else {
                        $html.='<div class="wpjobportal-jobs-act-btn jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover="rejectActionPopup('. $job->id.');">
                                '. esc_html(__('Reject', 'wp-job-portal')).'';
                            $html.='</div>';
                    }//End Reject 
                    $html.='
                    <a class="wpjobportal-jobs-act-btn" href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_job&wpjobportallt=formjob&wpjobportalid='.$job->id)).' title='.  esc_html(__('edit', 'wp-job-portal')).'>
                        '. esc_html(__('Edit', 'wp-job-portal')).'
                    </a>
                    <a class="wpjobportal-jobs-act-btn" href='. wp_nonce_url(admin_url('admin.php?page=wpjobportal_job&task=remove&wpjobportal-cb[]='.$job->id),'wpjobportal_job_nonce').'&action=wpjobportaltask&callfrom=2 onclick="return confirm(\''. esc_html(__('Are you sure to delete','wp-job-portal')) . ' ?'.'\');" title='.esc_html(__('delete', 'wp-job-portal')).'>
                        '.esc_html(__('Delete', 'wp-job-portal')).'
                    </a>
                    <a class="wpjobportal-jobs-act-btn" href='. wp_nonce_url(admin_url('admin.php?page=wpjobportal_job&task=jobenforcedelete&jobid='.$job->id),'wpjobportal_job_nonce') .'&action=wpjobportaltask&callfrom=2 onclick="return confirmdelete(\''. esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).'?'.'\');" title='. esc_html(__('force delete', 'wp-job-portal')).'>
                        '. esc_html(__('Force Delete', 'wp-job-portal')).'
                    </a>
            </div>  ';
        break;
 }

 echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
?>
