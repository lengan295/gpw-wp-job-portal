<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALCompanyTable extends WPJOBPORTALtable {

    public $id = '';
    public $uid = '';
    public $name = '';
    public $alias = '';
    public $url = '';
    public $logofilename = '';
    public $logoisfile = '';
    public $logo = '';
    public $smalllogofilename = '';
    public $smalllogoisfile = '';
    public $smalllogo = '';
    public $contactemail = '';
    public $description = '';
    public $city = '';
    public $address1 = '';
    public $address2 = '';
    public $created = '';
    public $price = '';
    public $modified = '';
    public $hits = '';
    public $tagline = '';
    public $status = '';
    public $isfeaturedcompany = 2; // For the case of new company store
    public $startfeatureddate = '';
    public $endfeatureddate = '';
    public $serverstatus = '';
    public $userpackageid = '';
    public $serverid = '';
    public $params = '';
    public $twiter_link = '';
    public $linkedin_link = '';
    public $youtube_link = '';
    public $facebook_link = '';

    function __construct() {
        parent::__construct('companies', 'id'); // tablename, primarykey
    }

}

?>