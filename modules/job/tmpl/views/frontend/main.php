<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
if(!isset($labelflag)){
	$labelflag = '';
}
?>
<div class="wjportal-jobs-cnt-wrp">
	<?php 
		WPJOBPORTALincluder::getTemplate('job/views/frontend/title',array(
		    'layout' => 'job',
		    'job' => $job,
		    'labelflag' => $labelflag,
		    'control' => $control
		)); 
	?>
</div>