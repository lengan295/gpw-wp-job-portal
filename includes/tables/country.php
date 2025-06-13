<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALcountryTable extends WPJOBPORTALtable {

    public $id = '';
    public $name = '';
    public $internationalname = '';
    public $localname = '';
    public $shortCountry = '';
    public $continentID = '';
    public $dialCode = '';
    public $enabled = '';
    public $serverid = '';

    function __construct() {
        parent::__construct('countries', 'id'); // tablename, primarykey
    }

}

?>
