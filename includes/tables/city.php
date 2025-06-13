<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALcityTable extends WPJOBPORTALtable {

    public $id = '';
    public $cityName = '';
    public $name = '';
    public $internationalname = '';
    public $localname = '';
    public $stateid = '';
    public $countryid = '';
    public $isedit = '';
    public $latitude = '';
    public $longitude = '';
    public $enabled = '';
    public $serverid = '';
   
    function __construct() {
        parent::__construct('cities', 'id'); // tablename, primarykey
    }

}

?>
