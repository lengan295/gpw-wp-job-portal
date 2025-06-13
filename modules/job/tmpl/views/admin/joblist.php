<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param js-job optional  
*/
?>
<div id="job_<?php echo esc_attr($job->id); ?>" class="wpjobportal-jobs-list">
	<div id="item-data">
	    <span id="selector_<?php echo esc_attr($job->id); ?>" class="selector">
	    	<input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($job->id); ?>);" class="wpjobportal-cb" id="wpjobportal-cb" name="wpjobportal-cb[]" value="<?php echo esc_attr($job->id); ?>" />
	    </span>
		<?php
			WPJOBPORTALincluder::getTemplate('job/views/admin/jobdetail',array(
				'job' => $job,
				'layout' => $layout,
				'logo' => $logo
			));
		?>
	</div>
</div>