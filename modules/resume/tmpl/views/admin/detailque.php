<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
?>
<?php
	$html='<div id="wpjobportal-top-comp-left" class=" js_circle">';
  	WPJOBPORTALincluder::getTemplate('resume/views/admin/logo',array(
    	'resume' => $resume,
    	'resumeque' => $html
    ));
   	WPJOBPORTALincluder::getTemplate('resume/views/admin/que-title',array(
	   	'resume' => $resume,
	   	'control' => $control
   	));
?>