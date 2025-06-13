<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
    wp_enqueue_script('wpjobportal-res-tables', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/responsivetable.js');

$msgkey = WPJOBPORTALincluder::getJSModel('jobseeker')->getMessagekey();
WPJOBPORTALMessages::getLayoutMessage($msgkey);
WPJOBPORTALbreadcrumbs::getBreadcrumbs();
include_once(WPJOBPORTAL_PLUGIN_PATH . 'includes/header.php');
if (wpjobportal::$_error_flag == null) {
    ?>
    <div id="wpjobportal-wrapper">
        <div class="page_heading"><?php echo esc_html(__('Stats', 'wp-job-portal')); ?></div>
        <?php if(isset(wpjobportal::$_data[0]) AND !empty(wpjobportal::$_data[0])){ ?>

        <div class="wpjobportal-bottom-wrapper">
            <div class="js-topstats">
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="resume tprow">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/stats/total-resume.png">
                        <div class="js-headtext"><?php echo esc_html(__('Total resume','wp-job-portal')); ?></div>
                        <div class="js-count">(<?php echo esc_html(wpjobportal::$_data[0]['totalresume']); ?>)</div>
                    </div>
                </div>
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="coverletter tprow">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/stats/total-coverletter.png">
                        <div class="js-headtext"><?php echo esc_html(__('Cover letter','wp-job-portal')); ?></div>
                        <div class="js-count">(<?php echo esc_html(wpjobportal::$_data[0]['totalcoverletter']); ?>)</div>
                    </div>
                </div>
                <div class="js-mainwrp js-col-xs-12 js-col-md-4">
                    <div class="appliedjobs tprow">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/stats/applied-jobs.png">
                        <div class="js-headtext"><?php echo esc_html(__('Applied jobs','wp-job-portal')); ?></div>
                        <div class="js-count">(<?php echo esc_html(wpjobportal::$_data[0]['totalapplied']); ?>)</div>
                    </div>
                </div>
            </div>
            <table id="js-table" class="wpjobportal-first-table">
                <thead class="stats">
                    <tr>
                        <th class="title"><img class="table-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/stats/resume-stats.png"><?php echo esc_html(__('Resume','wp-job-portal'));?></th>
                        <th class="publish center"> <?php echo esc_html(__('Publish','wp-job-portal'));?> </th>
                        <th class="expired center"> <?php echo esc_html(__('Expired','wp-job-portal'));?> </th>
                    </tr>
                </thead>
                <tbody class="stats">
                </tbody>
            </table>
        </div>
        <?php
    } else{
        $msg = esc_html(__('No record found','wp-job-portal'));
        WPJOBPORTALlayout::getNoRecordFound($msg);
    }
        ?>

    </div>
<?php
}else{
    echo wp_kses_post(wpjobportal::$_error_flag_message);
} ?>
</div>
