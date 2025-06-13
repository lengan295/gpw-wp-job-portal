<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 * IF PREVIOUS ID SAME AS COMPARE TO PREVIOUS THAN SHOW SAME ELSE VARIATE
*/?>
<?php
if((WPJOBPORTALincluder::getObjectClass('user')->isemployer()) && count(wpjobportal::$_data[0]['jobid'])>0) {
    ?>
    <div id="job-applied-resume" class="wjportal-resume-list-wrp">
        <?php
            $jobtype = wpjobportal::$_data[0]['jobid'];
            foreach ($jobtype as $key=>$value) { ?>
                <div class="wjportal-resume-app-title" id="jobid<?php $value->jobid?>">
                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($value->title)); ?>
                </div>
                <?php
                foreach (wpjobportal::$_data[0]['data'][$value->jobid] AS $resume) {
                    //Job Wise LOOP Resume's
                    WPJOBPORTALincluder::getTemplate('resume/views/frontend/resumelist',array(
                        'myresume' => $resume,
                        'module' => 'dashboard',
                        'control' => '',
                        'percentage' => ''
                    ));
                }
            }
        ?>
    </div>
        <?php
} else {
    $msg = esc_html(__('No record found','wp-job-portal'));
    WPJOBPORTALlayout::getNoRecordFound($msg, '');
  }
?>

