<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal
*==> Main Data Admin Companies*
*/
?>
<?php
	WPJOBPORTALincluder::getTemplate('company/views/admin/logo',array(
		'company' => $company,
		'layout' => $layout,
		'wpdir' => $wpdir
	));

	WPJOBPORTALincluder::getTemplate('company/views/admin/detail',array(
		'company' => $company
	));

	WPJOBPORTALincluder::getTemplate('company/views/admin/control',array(
		'company' => $company,
		'control' => $control,
		'arr' => $arr
	));

?>