<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobTable extends WPJOBPORTALtable {
    public $id = '';
    public $uid = '';
    public $companyid = '';
    public $title = '';
    public $alias = '';
    public $jobcategory = '';
    public $jobtype = '';
    public $jobstatus = '';
    public $hidesalaryrange = '';
    public $description = '';
    public $qualifications = '';
    public $prefferdskills = '';
    public $applyinfo = '';
    public $company = '';
    public $city = '';
    public $address1 = '';
    public $address2 = '';
    public $companyurl = '';
    public $contactname = '';
    public $contactphone = '';
    public $contactemail = '';
    public $showcontact = '';
    public $noofjobs = '';
    public $reference = '';
    public $duration = '';
    public $heighestfinisheducation = '';
    public $created = '';
    public $created_by = '';
    public $modified = '';
    public $modified_by = '';
    public $hits = '';
    public $experience = '';
    public $startpublishing = '';
    public $stoppublishing = '';
    public $departmentid = '';
    public $sendemail = '';
    public $metadescription = '';
    public $metakeywords = '';
    public $ordering = '';
    public $aboutjobfile = '';
    public $status = '';
    public $degreetitle = '';
    public $careerlevel = '';
    public $educationid = '';
    public $map = '';
    public $salarytype='';
    public $salarymin='';
    public $salarymax='';
    public $salaryduration='';
    public $subcategoryid = '';
    public $currency = '';
    public $jobid = '';
    public $longitude = '';
    public $latitude = '';
    public $raf_degreelevel = '';
    public $raf_education = '';
    public $raf_category = '';
    public $raf_subcategory = '';
    public $raf_location = '';
    public $isfeaturedjob = 2;
    public $serverstatus = '';
    public $serverid = '';
    public $joblink = '';
    public $jobapplylink = '';
    public $tags = '';
    public $params = '';
    public $userpackageid = '';
    public $price = '';
    // log error
    public $startfeatureddate = '';
    public $endfeatureddate = '';
    // AI strings
    public $aijobsearchtext = '';
    public $aijobsearchdescription = '';


    public function check() {
        // allow users to create job without company
        // if ($this->companyid == '') {
        //     return false;
        // }

        return true;
    }

    function __construct() {
        parent::__construct('jobs', 'id'); // tablename, primarykey
    }

}

?>
