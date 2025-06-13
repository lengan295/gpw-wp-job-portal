<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param module 		module name - optional
 */
if (!isset($module)) {
	// if module name is not passed than pick from url
	$module = WPJOBPORTALrequest::getVar('wpjobportalme');
}


/*
show module wise flash messages
*/
if ($module) {
	$model = WPJOBPORTALincluder::getJSModel($module);
	if ($model) {
		$msgkey = $model->getMessagekey();
		WPJOBPORTALMessages::getLayoutMessage($msgkey);
	}
}


/*
show breadcrumbs
*/


/*
show menu for jobseeker and employer
*/
//include_once(WPJOBPORTAL_PLUGIN_PATH . 'includes/header.php');



/*
if there is any error, show error and return from page
*/
if (wpjobportal::$_error_flag != null &&  wpjobportal::$_error_flag_message != null) {
	echo wp_kses_post(wpjobportal::$_error_flag_message);
    return false;
}