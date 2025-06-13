<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
?>
<?php
	switch ($layout) {
		case 'toprowlogo':
			echo '
				 <div class="wjportal-jobs-logo">
					<a href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyid))) .' >
					    <img src='. esc_url(WPJOBPORTALincluder::getJSModel('company')->getLogoUrl($job->companyid,$job->logofilename)).' alt="'.esc_html(__('Company logo','wp-job-portal')).'">
					</a>
				</div>
				';
		break;
		case 'profile':
			$img = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
			if (!empty($profile->photo)) {
		        $wpdir = wp_upload_dir();
		        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
		        $img = $wpdir['baseurl'] . '/' . $data_directory . '/data/profile/profile_' . $profile->uid . '/profile/' . $profile->photo;
        	}
        	$field_ordering = wpjobportalincluder::getJSModel('fieldordering')->getFieldsOrderingforView(4);
        	if(!empty($field_ordering) && isset($field_ordering['photo'])){
				echo '<div class="wjportal-user-logo">
			 		<img src='.esc_url($img).' class="wjportal-user-logo-image" alt="'.esc_html(__('Profile image','wp-job-portal')).'">
		 		</div>';
        	}

	 		if (isset($profile->first_name)) {
			 	echo '<div class="wjportal-user-name">
			 			'.  esc_html(isset($profile->first_name) ? esc_html($profile->first_name): '' ) .'
			 			'.  esc_html(isset($profile->last_name) ? esc_html($profile->last_name): '' ) .'
             	</div>';
         	}
         	if (isset(wpjobportal::$_data['application_title'])) {
				echo '<div class="wjportal-user-tagline">
						'.  esc_html(isset(wpjobportal::$_data['application_title'])? wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['application_title']):'' ) .'
            	</div>';
        	}
		break;
		default:
			$msg=esc_html(__('No Record Found','wp-job-portal')) ;
			echo '
			 	<div class="js-image">
					'.wp_kses(WPJOBPORTALlayout::getNoRecordFound($msg, esc_url($linkcompany)),WPJOBPORTAL_ALLOWED_TAGS).'
			 	</div>
		 	';
		break;
	}
?>

