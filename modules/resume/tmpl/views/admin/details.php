<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
?>
<div class="wpjobportal-resume-list-top-wrp">
	<?php
		$html='<div class="wpjobportal-resume-logo">';
		WPJOBPORTALincluder::getTemplate('resume/views/admin/logo',array(
		   	'resume'    => $resume,
		   	'resumeque' => $html
	   	));
	   	WPJOBPORTALincluder::getTemplate('resume/views/admin/title',array(
	   		'resume'=> $resume
	   	));
	?>
</div>
<div class="wpjobportal-resume-list-btm-wrp">
	<?php
	 	WPJOBPORTALincluder::getTemplate('resume/views/admin/control', array(
	 		'resume' => $resume,
	 		'control' => $control,
	 		'arr' => $arr 
		));
	?>
</div>
