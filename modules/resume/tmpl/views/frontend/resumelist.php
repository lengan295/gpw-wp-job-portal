<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param WP JOB PORTAL 
 */
$listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingDataForListing(3);

 echo '<div id="job-applied-resume" class="wjportal-resume-list"> 
		<div class="wjportal-resume-list-top-wrp object_'.esc_attr($myresume->id).'" data-boxid="resume_'.esc_attr($myresume->id).'" >';
		if(isset($listing_fields['photo'])){// show photo if its published and set visible for listing
			WPJOBPORTALincluder::getTemplate('resume/views/frontend/logo',array(
			    'myresume' => $myresume
			));
		}

			WPJOBPORTALincluder::getTemplate('resume/views/frontend/main',array(
			    'myresume' => $myresume,
			    'percentage' => $percentage,
			    'module' => $module
			));
	echo	'</div>
		';
		echo '<div id='.esc_attr($myresume->id).' ></div>
            	<div id="comments" class="wjportal-applied-job-actions-popup '.esc_attr($myresume->id).'" style="display:none" ></div>';
				WPJOBPORTALincluder::getTemplate('resume/views/frontend/control',array(
			        'control' => $control,
			        'myresume'=> $myresume,
			        'featuredflag' => true
			    ));
		echo  '</div>';
