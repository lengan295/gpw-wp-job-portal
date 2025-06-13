<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal Optional
*/
?>
<?php
WPJOBPORTALincluder::getTemplate('country/views/detail',array(
	'row' => $row ,
	'pagenum' => $pagenum ,
	'pageid' => $pageid,
	'published' => $published
));
?>
