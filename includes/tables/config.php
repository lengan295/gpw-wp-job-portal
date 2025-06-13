<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALconfigTable extends WPJOBPORTALtable {

    public $configname = '';
    public $configvalue = '';
    public $configfor = '';

    function __construct() {
        parent::__construct('config', 'configname'); // tablename, primarykey
    }

}

?>