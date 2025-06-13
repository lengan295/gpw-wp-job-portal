<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALquickapplyTable extends WPJOBPORTALtable {

    public $id = '';
    //public $uid = '';

    public $jobid = '';
    public $full_name = '';
    public $email = '';
    public $phone = '';
    public $message = '';
    public $resume = '';
    public $params = '';

    //public $status = '';
    public $created = '';
    // public $serverstatus = '';
    // public $serverid = '';

    public function check() {
        // if ($this->companyid == '') {
        //     return false;
        // }

        return true;
    }

    function __construct() {
        parent::__construct('quickapplies', 'id'); // tablename, primarykey
    }

}

?>