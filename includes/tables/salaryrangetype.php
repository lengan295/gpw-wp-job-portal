<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALsalaryrangetypeTable extends WPJOBPORTALtable {

    public $id = '';
    public $title = '';
    public $status = '';
    public $isdefault = '';
    public $ordering = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('salaryrangetypes', 'id'); // tablename, primarykey
    }

}

?>