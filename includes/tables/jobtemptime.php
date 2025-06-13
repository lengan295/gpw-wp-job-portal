<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobtemptimeTable extends WPJOBPORTALtable {

    public $id = '';
    public $lastcalltime = '';
    public $expiretime = '';
    public $is_request = '';

    function __construct() {
        parent::__construct('jobtemptime', 'id'); // tablename, primarykey
    }

}

?>