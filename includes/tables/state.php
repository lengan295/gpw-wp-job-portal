<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALstateTable extends WPJOBPORTALtable {

    public $id = '';
    public $name = '';
    public $internationalname = '';
    public $localname = '';
    public $shortRegion = '';
    public $countryid = '';
    public $enabled = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('states', 'id'); // tablename, primarykey
    }

}

?>