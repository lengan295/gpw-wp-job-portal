<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
if (!isset($job->id)) {
	$job->id = '';
}
?>
<div class="wjportal-jobs-list">
	<div class="wjportal-jobs-list-top-wrp object_<?php echo esc_attr($job->id);?>" data-boxid="job_<?php echo esc_attr($job->id); ?>">
		<?php
			WPJOBPORTALincluder::getTemplate('job/views/frontend/logo',array(
			    'layout' => 'toprowlogo',
			    'job' => $job
			)); 
			WPJOBPORTALincluder::getTemplate('job/views/frontend/main',array(
			    'labelflag' => $labelflag,
			    'job' => $job,
			    'control' => $control
			));
		?>
	</div>
	<?php
		WPJOBPORTALincluder::getTemplate('job/views/frontend/controls',array(
		    'job' => $job,
		    'control' => $control
		)); 
 	?>
</div>

