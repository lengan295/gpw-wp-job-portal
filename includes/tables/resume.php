<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALresumeTable extends WPJOBPORTALtable {

    public $id = '';
    public $uid = '';
    public $created = '';
    public $last_modified = '';
    public $published = '';
    public $hits = '';
    public $application_title = '';
    public $salaryfixed = '';
    public $keywords = '';
    public $alias = '';
    public $first_name = '';
    public $last_name = '';
    public $gender = '';
    public $email_address = '';
    public $cell = '';
    public $nationality = '';
    public $searchable = '';
    public $photo = '';
    public $job_category = '';
    public $jobtype = '';
    public $status = '';
    public $resume = '';
    public $skills = '';
    //public $job_subcategory = '';
    public $isfeaturedresume = 2; // For the case of new resume
    public $startfeatureddate = '';
    public $endfeatureddate = '';
    public $serverstatus = '';
    public $serverid = '';
    public $tags = '';
    public $params = '';
    public $price = '';
    public $userpackageid = '';
    public $quick_apply = '';

    public $airesumesearchtext = '';
    public $airesumesearchdescription = '';
    function __construct() {
        parent::__construct('resume', 'id'); // tablename, primarykey
    }

}

?>
