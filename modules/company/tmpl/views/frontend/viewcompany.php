<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
 * @param job      job object - optional
*/
?>
<?php
	WPJOBPORTALincluder::getTemplate('company/views/frontend/viewcompanydetail',array(
		'config_array' => $config_array,
		'data_class' => $data_class,
		'module' => $module,
		'config_array' => $config_array	
	));

?>