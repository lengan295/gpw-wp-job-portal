<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
?>
<div class="right">
    <div class="js-col-xs-12 js-col-sm-12 js-col-md-12 js-fields for-rtl joblist-datafields">
        <span class="js-type" style="background: <?php echo esc_attr($job->jobtypecolor); ?>"><?php echo esc_html($job->jobtypetitle); ?></span>
    </div>
    <div class="js-col-xs-12 js-col-sm-12 js-col-md-12 js-fields for-rtl joblist-datafields">
        <span class="get-text"><b><?php echo esc_html(wpjobportal::$_common->getSalaryRangeView( $job->salarytype, $job->salarymin, $job->salarymax, $job->srangetypetitle)); ?></b></span>
    </div>
    <div class="js-col-xs-12 js-col-sm-12 js-col-md-12 js-fields for-rtl joblist-datafields" title="<?php echo esc_attr(date_i18n('d F Y h:i A',strtotime($job->created))); ?>">
        <?php echo esc_html(human_time_diff(strtotime($job->created) ,strtotime(date_i18n("Y-m-d H:i:s"))).' '.esc_html(__("ago",'wp-job-portal'))); ?>
    </div>
</div>
