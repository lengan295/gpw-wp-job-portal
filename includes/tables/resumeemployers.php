<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALresumeemployersTable extends WPJOBPORTALtable {

    public $id = '';
    public $resumeid = '';
    public $employer = '';
    public $employer_from_date = '';
    public $employer_to_date = '';
    public $employer_current_status='';
    public $employer_city = '';
    public $employer_phone = '';
    public $employer_address = '';
    public $employer_position = '';
    public $created = '';
    public $last_modified = '';
    public $params = '';
    public $serverstatus = '';
    public $serverid = '';

    public function check() {
        if ($this->resumeid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('resumeemployers', 'id'); // tablename, primarykey
    }

}

?>