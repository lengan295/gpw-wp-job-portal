<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
*
*/
$job_listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingData(2);
?>
<div class="wpjobportal-jobs-cnt-wrp">
    <div class="wpjobportal-jobs-middle-wrp">
        <div class="wpjobportal-jobs-data">
            <a class="wpjobportal-companyname" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_job&wpjobportallt=formjob&wpjobportalid='.$job->id)); ?>">
                <?php echo esc_html($job->companyname); ?>
            </a>
        </div>
        <div class="wpjobportal-jobs-data">
            <span class="wpjobportal-jobs-title">
                <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_job&wpjobportallt=formjob&wpjobportalid='.$job->id)); ?>">
                    <?php echo esc_html($job->title); ?>
                </a>
            </span>
            <?php
                if ($job->status == 0) {
                    echo '<span class="wpjobportal-item-status pending">' . esc_html(__('Pending', 'wp-job-portal')) . '</span>';
                } elseif ($job->status == 1) {
                    echo '<span class="wpjobportal-item-status approved">' . esc_html(__('Approved', 'wp-job-portal')) . '</span>';
                } elseif ($job->status == -1) {
                    echo '<span class="wpjobportal-item-status rejected">' . esc_html(__('Rejected', 'wp-job-portal')) . '</span>';
                }elseif ($job->status == 3) {
                    echo '<span class="wpjobportal-item-status rejected">' . esc_html(__('Pending Payment', 'wp-job-portal')) . '</span>';
                }

            ?>
            <?php
                $print = true;
                $startdate = date_i18n('Y-m-d',strtotime($job->startpublishing));
                $enddate = date_i18n('Y-m-d',strtotime($job->stoppublishing));
                $curdate = date_i18n('Y-m-d');
                if($startdate > $curdate){
                    $publishstatus = esc_html(__('Not publish','wp-job-portal'));
                    $publishstyle = 'background:#FEA702;color:#ffffff;border:unset;';
                }elseif($startdate <= $curdate && $enddate >= $curdate){
                    $publishstatus = esc_html(__('Publish','wp-job-portal'));
                    $publishstyle = 'background:#00A859;color:#ffffff;border:unset;';
                }else{
                    $publishstatus = esc_html(__('Expired','wp-job-portal'));
                    $publishstyle = 'background:#ED3237;color:#ffffff;border:unset;';
                }
            ?>
            <?php if($job->status == 1){ ?>
                <span class="wpjobportal-item-status" style="<?php echo esc_attr($publishstyle); ?>">
                    <?php echo esc_html($publishstatus); ?>
                </span>
            <?php } ?>
            <?php
                //$goldflag = true;
                //do_action('wp_jobportal_addons_admin_feature_lable_for_job',$job);
            ?>
        </div>
        <div class="wpjobportal-jobs-data">
            <?php if(isset($job_listing_fields['jobcategory']) && $job_listing_fields['jobcategory'] !='' ){ ?>
                <span class="wpjobportal-jobs-data-text">
                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($job->cat_title)); ?>
                </span>
            <?php } ?>

            <?php if(isset($job_listing_fields['city']) && $job_listing_fields['city'] !='' ){ ?>
                <span class="wpjobportal-jobs-data-text">
                    <?php echo esc_html(WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($job->city)); ?>
                </span>
            <?php } ?>
        </div>
    </div>
    <div class="wpjobportal-jobs-right-wrp">
        <?php if(isset($job_listing_fields['jobtype']) && $job_listing_fields['jobtype'] !='' ){ ?>
            <div class="wpjobportal-jobs-info">
                <span class="wpjobportal-jobs-type" style="background: <?php echo esc_attr($job->jobtypecolor); ?>;">
                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($job->jobtypetitle)); ?>
                </span>
            </div>
        <?php } ?>
        <?php if(isset($job_listing_fields['jobsalaryrange']) && $job_listing_fields['jobsalaryrange'] !='' ){ ?>
        <div class="wpjobportal-jobs-info">
            <div class="wpjobportal-jobs-salary">
                <?php echo esc_html(wpjobportal::$_common->getSalaryRangeView($job->salarytype, $job->salarymin, $job->salarymax,$job->currency)); ?>
                <?php if($job->salarytype==3 || $job->salarytype==2) { ?>
                    <span class="wpjobportal-salary-type"> <?php echo ' / ' .esc_html(wpjobportal::wpjobportal_getVariableValue($job->salaryrangetype)); ?></span>
                <?php }?>
            </div>
        </div>
        <?php } ?>
        <div class="wpjobportal-jobs-info">
            <?php
                $dateformat = wpjobportal::$_configuration['date_format'];
                echo esc_html(human_time_diff(strtotime($job->created) ,strtotime(date_i18n("Y-m-d H:i:s")))).' '.esc_html(__("Ago",'wp-job-portal'));
            ?>
        </div>
    </div>
</div>
