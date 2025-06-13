<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param js-job optional 
*Template For job Detail
*/
?>
<div class="wpjobportal-jobs-list-top-wrp">
	<?php
	    //$goldflag = true;
	    do_action('wp_jobportal_addons_admin_feature_lable_for_job',$job);
	?>
	<?php
		WPJOBPORTALincluder::getTemplate('job/views/admin/logo',array(
			'layout' => $logo,
			'job' => $job 
		));

		WPJOBPORTALincluder::getTemplate('job/views/admin/title',array(
			'layout' => 'title'
			 ,'job' => $job 
		));
	?>
</div>
<div class="wpjobportal-jobs-list-btm-wrp">
	<?php
		WPJOBPORTALincluder::getTemplate('job/views/admin/control',array(
			'layout' => $layout,
			 'job' => $job 
		));
	?>
</div>