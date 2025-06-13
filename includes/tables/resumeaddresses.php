<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALresumeaddressesTable extends WPJOBPORTALtable {

    public $id = '';
    public $resumeid = '';
    public $address = '';
    public $address_city = '';
    public $longitude = '';
    public $latitude = '';
    public $created = '';
    public $last_modified = '';
    public $params = '';

    public function check() {
        if ($this->resumeid == '') {
            return false;
        }

        return true;
    }

    function __construct() {
        parent::__construct('resumeaddresses', 'id'); // tablename, primarykey
    }

}

?>