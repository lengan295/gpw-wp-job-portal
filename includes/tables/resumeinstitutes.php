<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALresumeinstitutesTable extends WPJOBPORTALtable {

    public $id = '';
    public $resumeid = '';
    public $institute = '';
    public $institute_certificate_name = '';
    public $institute_study_area = '';
    public $created = '';
    public $last_modified = '';
    public $fromdate = '';
    public $todate = '';
    public $params = '';

    public function check() {
        if ($this->resumeid == '') {
            return false;
        }
        return true;
    }

    function __construct() {
        parent::__construct('resumeinstitutes', 'id'); // tablename, primarykey
    }

}

?>