<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALresumelanguagesTable extends WPJOBPORTALtable {

    public $id = '';
    public $resumeid = '';
    public $language = '';
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
        parent::__construct('resumelanguages', 'id'); // tablename, primarykey
    }

}

?>