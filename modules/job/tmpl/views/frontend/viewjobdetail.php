<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
?>
<div class="wjportal-main-wrapper wjportal-clearfix">
    <div class="wjportal-view-job-page-wrapper" >
        <div class="wjportal-view-job-page-job-info-wraper wjportal-view-job-page-job-info-wraper-with-apply-form " >
            <?php
                WPJOBPORTALincluder::getTemplate('job/views/frontend/jobtitle', array(
                    'job'       =>  $job ,
                    'jobfields'  =>  $jobfields
                ));
            ?>
        </div>
    </div>
</div>
