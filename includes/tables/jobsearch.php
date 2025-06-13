<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobsearchTable extends WPJOBPORTALtable {

    public $id = '';
    public $uid = '';
    public $searchname = '';
    public $searchparams = '';
    public $created = '';
    public $status = '';
    public $params = '';

    function __construct() {
        parent::__construct('jobsearches', 'id'); // tablename, primarykey
    }

}

?>