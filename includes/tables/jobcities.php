<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobcitiesTable extends WPJOBPORTALtable {

    public $id = '';
    public $jobid = '';
    public $cityid = '';
    public $serverid = '';

    public function check() {
        if ($this->jobid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('jobcities', 'id'); // tablename, primarykey
    }

}

?>