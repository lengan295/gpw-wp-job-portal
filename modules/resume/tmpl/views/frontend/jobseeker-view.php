<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
?>
<?php
	WPJOBPORTALincluder::getTemplate('resume/list-view/frontend/jobseeker-title',array(
			'myresume'	=>	$myresume,
			'percentage'=>	$percentage
	));
	WPJOBPORTALincluder::getTemplate('resume/list-view/frontend/jobsekr-perc',array(
			'myresume'	=>	$myresume,
			'percentage'=>	$percentage
	));
	WPJOBPORTALincluder::getTemplate('resume/list-view/frontend/jobsekr-controls',array(
			'myresume'	=>	$myresume
	));
?>
                
