<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
$dateformat = wpjobportal::$_configuration['date_format'];
?>
<div class="my-resume-data object_<?php echo esc_attr($myresume->id); ?>">
    <div class="my-resume-listing-img-modified-wrapper" >
<?php
	 WPJOBPORTALincluder::getTemplate('resume/list-view/frontend/myresumelogo',array(
        'myresume' =>    $myresume,
         'logo'    =>    '1'
     ));
    WPJOBPORTALincluder::getTemplate('resume/list-view/frontend/jobseeker-view',array(
        'myresume'     => $myresume,
	    'percentage'   => $percentage,
	    'status_array' => $status_array
    ));
?>
</div>

