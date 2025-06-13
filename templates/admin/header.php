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