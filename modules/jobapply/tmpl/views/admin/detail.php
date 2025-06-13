<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param wp-job-portal optional
*/
?>
<div id="user_<?php echo 1; ?>" class="wpjobportal-resume-list wpjobportal-applied-resume-list">
    <div id="item-data">
        <div class="wpjobportal-resume-list-top-wrp">
           <?php
    		 	WPJOBPORTALincluder::getTemplate('jobapply/views/admin/main',array(
                    'data' => $data,
                    'layout' => 'logo'
                ));

                WPJOBPORTALincluder::getTemplate('jobapply/views/admin/main',array(
                    'data' => $data,
                    'layout' => 'detail'
                ));
            ?>
        </div>
        <div class="wpjobportal-resume-list-btm-wrp">
            <?php
                WPJOBPORTALincluder::getTemplate('jobapply/views/admin/control',array(
                    'data' => $data
                ));
            ?>
        </div>
    </div>
</div>
