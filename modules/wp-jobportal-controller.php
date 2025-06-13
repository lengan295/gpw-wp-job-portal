<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALcontroller {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'wpjobportal');
       WPJOBPORTALincluder::include_file($module);
    }

}

$WPJOBPORTALcontroller = new WPJOBPORTALcontroller();
?>
