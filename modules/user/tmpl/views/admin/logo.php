<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param wp job portal Logo
*/
?>
<?php
$html = '';
switch ($layout) {
	case 'userlogo':
	    $photo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
		if (isset($user->photo) && $user->photo != '') {
		    $wpdir = wp_upload_dir();
		    $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
		    $photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/profile/profile_' . esc_attr($user->uid) . '/profile/' . $user->photo;
		}
		$html.= '<div class="wpjobportal-user-logo">
                    <a href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_user&wpjobportallt=userdetail&id='.esc_attr($user->id))).'>
                    	<img src="'. esc_url($photo) .'" alt='.esc_html(__("logo","wp-job-portal")).'>
                    </a>
                </div>';
		break;
}
echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
