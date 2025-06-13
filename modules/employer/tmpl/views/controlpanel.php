<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
switch ($layouts) {
	case 'logo':

		$profile=isset(wpjobportal::$_data[0]['company']['emp_profile']) ? wpjobportal::$_data
			[0]['company']['emp_profile'] : null;
		$comp_name=isset(wpjobportal::$_data[0]['companies'][0]) ? wpjobportal::$_data[0]['companies'][0] : null;
		$img = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
		if (isset($profile) && $profile->photo != '' ) {
			$wpdir = wp_upload_dir();
			$data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
			$img = $wpdir['baseurl'] . '/' . $data_directory . '/data/profile/profile_' . wpjobportal::$_data[0]['company']['emp_profile']->uid . '/profile/' . wpjobportal::$_data[0]['company']['emp_profile']->photo;
		}
		$field_ordering = wpjobportalincluder::getJSModel('fieldordering')->getFieldsOrderingforView(4);
    	if(!empty($field_ordering) && isset($field_ordering['photo'])){
			echo '
				<div class="wjportal-user-logo">
	        	 	<img src='. esc_url($img) .' alt="'.esc_html(__("User image",'wp-job-portal')).'" title="'.esc_html(__("User image",'wp-job-portal')).'" class="wjportal-user-logo-image" />
	        	</div>
	        ';
	    }
        if(isset($profile->first_name) && $profile->first_name != ''){
			echo '<div class="wjportal-user-name">
					'.  esc_html(wpjobportal::wpjobportal_getVariableValue($profile->first_name)).' '.esc_html(wpjobportal::wpjobportal_getVariableValue($profile->last_name)) .'
				</div>';
           	echo '<div class="wjportal-user-tagline">
           		'. esc_html(wpjobportal::wpjobportal_getVariableValue($profile->emailaddress)).'
           		</div>
        	';
        }
	break;
}
?>