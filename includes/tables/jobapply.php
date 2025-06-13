<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobapplyTable extends WPJOBPORTALtable {

    public $id = '';
    public $jobid = '';
    public $uid = '';
    public $cvid = '';
    public $coverletterid = '';
    public $apply_date = '';
    public $resumeview = '';
    public $action_status = '';
    public $status = '';
    public $price = '';
    public $comments = '';
    public $userpackageid = '';
    public $serverstatus = '';
    public $socialprofileid = '';
    public $serverid = '';
    public $socialapplied = '';
    public $apply_message = '';
    public $quick_apply = '';
    public function check() {
        if ($this->jobid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('jobapply', 'id'); // tablename, primarykey
    }

}

?>
