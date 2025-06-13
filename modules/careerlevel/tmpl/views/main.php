<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal detail
*/
?>
<?php
	WPJOBPORTALincluder::getTemplate('careerlevel/views/detail',array(
		'row' => $row,
		'i' => $i,
		 'pagenum' => $pagenum ,
		 'n' => $n ,
		  'pageid' => $pageid
	));
?>