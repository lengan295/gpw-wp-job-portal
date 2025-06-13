<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALActivitylogTable extends WPJOBPORTALtable {

    public $id = '';
    public $description = '';
    public $referencefor = '';
    public $referenceid = '';
    public $uid = '';
    public $created = '';

    function __construct() {
        parent::__construct('activitylog', 'id'); // tablename, primarykey
    }

}

?>