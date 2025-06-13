<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobModel {
        public $class_prefix = '';

        function __construct(){
            if(wpjobportal::$theme_chk == 1){
                $this->class_prefix = 'jsjb-jm';
            }elseif(wpjobportal::$theme_chk == 2){
                $this->class_prefix = 'jsjb-jh';
            }
        }

        function setListStyleSession(){
            $listingstyle = WPJOBPORTALrequest::getVar('styleid');
            if(wpjobportal::$theme_chk == 1){
                update_option( 'jsjb_jm_listing_style', $listingstyle );
            }else{
                update_option( 'jsjb_jh_listing_style', $listingstyle );
            }

            return $listingstyle;
        }

        function getNewestJobsForMap_Widget($noofjobs) {
            if(!isset($noofjobs))
                $noofjobs = 0;
            if( ! is_numeric($noofjobs))
                $noofjobs = 0;
            if($noofjobs > 100)
                $noofjobs = 100;
            if($noofjobs < 0)
                $noofjobs = 0;

            $id = "job.id AS id";
            $alias = ",CONCAT(job.alias,'-',job.id) AS aliasid ";
            $companyaliasid = ", CONCAT(company.alias,'-',company.id) AS companyaliasid ";

            $query = "SELECT job.id,job.title, job.jobcategory, job.created, cat.cat_title
                , job.city, job.latitude, job.longitude
                , company.id AS companyid, company.name AS companyname,company.logofilename AS companylogo, jobtype.title AS jobtypetitle
                $alias $companyaliasid

                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()
                ORDER BY created DESC LIMIT " . esc_sql($noofjobs);

            $result = wpjobportaldb::get_results($query);

            foreach ($result AS $job) {
                if(!is_numeric($job->id)){
                    continue;
                }
                if (empty($job->latitude) || empty($job->longitude)) {
                    $query = "SELECT city.name AS cityname, country.name AS countryname
                                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS job
                                JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = job.cityid
                                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                                WHERE job.jobid = " . esc_sql($job->id);
                    $job->multicity = wpjobportaldb::get_results($query);
                }
            }
            $jobs = $result;
            foreach ($jobs AS $job) {
                $job->joblink = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$job->aliasid));
            }
            $result = $jobs;
            return $result;
    }
    function getjobType($jobid){

    }

    function getJobInfo($jobid,$uid){
        if (!is_numeric($jobid))
            return false;
    $query = "SELECT DISTINCT job.id AS jobid,job.tags AS jobtags,job.title,job.created,job.city,
        CONCAT(job.alias,'-',job.id) AS jobaliasid,job.noofjobs,job.isfeaturedjob,job.status,
        cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, jobtype.title AS jobtypetitle,job.endfeatureddate,job.startpublishing,job.stoppublishing,
        job.params,CONCAT(company.alias,'-',company.id) AS companyaliasid,LOWER(jobtype.title) AS jobtypetit,
        job.salarymax,job.salarymin,job.salarytype,srtype.title AS srangetypetitle,jobtype.color AS jobtypecolor,job.currency
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS srtype ON srtype.id = job.salaryduration
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity ON jobcity.jobid = job.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = jobcity.cityid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.countryid = city.countryid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
        WHERE job.id =". esc_sql($jobid);
        $results = wpjobportal::$_db->get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
             $data[] = $d;
        }
        wpjobportal::$_data['jobinfo'] = $data;
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(2);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('job');
        return;
    }

    function getJobsByTypes_Widget($showalltypes, $haverecordss, $maximumrecords) {
        if ((!is_numeric($showalltypes)) || ( !is_numeric($haverecordss)) || ( !is_numeric($maximumrecords)))
            return false;

        $haverecords = '';
        $maxlimit = '';
        if ($haverecordss == 1) {
            $haverecords = " HAVING totaljobs > 0 ";
        }

        if ($maximumrecords >= 0) {
            $maxlimit = " LIMIT $maximumrecords";
        }

        if ($showalltypes == 1) {
            $haverecords = '';
            $maxlimit = '';
        }

        $inquery = " (SELECT COUNT(jobs.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS jobs
                        WHERE jobtype.id = jobs.jobtype AND jobs.status = 1
                        AND DATE(jobs.startpublishing) <= CURDATE() AND DATE(jobs.stoppublishing) >= CURDATE() ) as totaljobs";
        $query = "SELECT DISTINCT jobtype.id, jobtype.title AS objtitle , CONCAT(jobtype.alias, '-' , jobtype.id) AS aliasid , ";
        $query .= $inquery;
        $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON jobtype.id = job.jobcategory
                    WHERE jobtype.isactive = 1 ";
        $query .= esc_sql($haverecords)." ORDER BY objtitle ".esc_sql($maxlimit);
        $results = wpjobportaldb::get_results($query);
        return $results;
    }

    function getJobsBycategory_Widget($showallcats, $haverecordss, $maximumrecords) {
        if ((!is_numeric($showallcats)) || ( !is_numeric($haverecordss)) || ( !is_numeric($maximumrecords)))
            return false;

        $haverecords = '';
        $maxlimit = '';
        if ($haverecordss == 1) {
            $haverecords = " HAVING totaljobs > 0 ";
        }

        if ($maximumrecords >= 0) {
            $maxlimit = " LIMIT " . $maximumrecords;
        }

        if ($showallcats == 1) {
            $haverecords = '';
            $maxlimit = '';
        }

        $inquery = " (SELECT COUNT(jobs.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS jobs
                        WHERE cat.id = jobs.jobcategory AND jobs.status = 1
                        AND DATE(jobs.startpublishing) <= CURDATE() AND DATE(jobs.stoppublishing) >= CURDATE() ) as totaljobs";
        $query = "SELECT DISTINCT cat.id, cat.cat_title AS objtitle , CONCAT(cat.alias,'-',cat.id) AS aliasid,";
        $query .= $inquery;
        $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON cat.id = job.jobcategory
                    WHERE cat.isactive = 1 ";
        $query .= esc_sql($haverecords)." ORDER BY objtitle ".esc_sql($maxlimit);


        $results = wpjobportaldb::get_results($query);
        return $results;
    }

    function getJobsBylocation_Widget($showjobsby, $showonlyrecordhavejobs, $maximumrecords) {
        if ((!is_numeric($showjobsby)) || ( !is_numeric($showonlyrecordhavejobs)) || ( !is_numeric($maximumrecords)))
            return false;

        if ($maximumrecords > 100)
            $maximumrecords = 100;
        elseif ($maximumrecords < 0)
            $maximumrecords = 20;

        $haverecords = "";
        if ($showonlyrecordhavejobs == 1) {
            $haverecords = " HAVING totaljobs > 0 ";
        }

        if ($showjobsby == 1) {
            $query = "SELECT city.id AS locationid, city.name AS locationname, COUNT(job.id) AS totaljobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing >= CURDATE() )
                    WHERE country.enabled = 1
                    GROUP BY locationid ".esc_sql($haverecords)." ORDER BY totaljobs DESC , locationname ASC LIMIT " . esc_sql($maximumrecords);
        } elseif ($showjobsby == 2) {
            $query = "SELECT state.id AS locationid, state.name AS locationname, COUNT(job.id) AS totaljobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON state.id = city.stateid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing >= CURDATE() )
                    WHERE country.enabled = 1
                    GROUP BY locationid ".esc_sql($haverecords)." ORDER BY totaljobs DESC, cityname ASC LIMIT " . esc_sql($maximumrecords);
        } elseif ($showjobsby == 3) {
            $query = "SELECT country.id AS locationid, country.name AS locationname,COUNT(job.id) AS totaljobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON country.id = city.countryid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS mcity ON mcity.cityid = city.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON (job.id = mcity.jobid AND job.status =1 AND job.stoppublishing >= CURDATE() )
                    WHERE country.enabled = 1
                    GROUP BY locationid ".esc_sql($haverecords)." ORDER BY totaljobs DESC, locationname ASC LIMIT " . esc_sql($maximumrecords);
        } else {
            return '';
        }

        $results = wpjobportaldb::get_results($query);
        return $results;
    }

    function getJobs_Widget($typeofjobs, $noofjobs) {
        if ((!is_numeric($typeofjobs)) || ( !is_numeric($noofjobs)))
            return '';
        $col = '';
        if ($typeofjobs == 1) { // newest jobs
            $inquery = " WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() ORDER BY job.created DESC LIMIT " . esc_sql($noofjobs);
        } elseif ($typeofjobs == 2) { //top jobs
            $inquery = " WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() ORDER BY job.hits DESC LIMIT " . esc_sql($noofjobs);
        } elseif ($typeofjobs == 3) { // hot jobs
            $col = ' COUNT(ja.jobid) as totalapply , ';
            $inquery = " JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS ja ON ja.jobid = job.id WHERE job.status = 1 AND job.startpublishing <= CURDATE() AND job.stoppublishing >= CURDATE() GROUP BY ja.jobid ORDER BY totalapply DESC LIMIT " . esc_sql($noofjobs);
        }  elseif ($typeofjobs == 5) { // featured jobs
            $inquery = " WHERE job.status = 1 AND DATE(job.endfeatureddate) > CURDATE() AND job.isfeaturedjob = 1 AND job.startpublishing <= CURDATE() AND job.stoppublishing >= CURDATE() ORDER BY job.created DESC LIMIT " . esc_sql($noofjobs);
        } else {
            return '';
        }
        $query = "SELECT $col job.id AS jobid,job.title,job.created,job.city,CONCAT(job.alias,'-',job.id) AS jobaliasid,job.currency,
                 cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, CONCAT(company.alias,'-',company.id) AS companyaliasid,
                 jobtype.title AS jobtypetitle,job.salarymax,job.salarymin,job.salarytype,salarytype.title AS srangetypetitle
                 FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                 ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                 LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salarytype ON salarytype.id = job.salaryduration
                 LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
                 LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype";
        $query .= $inquery;
        $results = wpjobportaldb::get_results($query);
        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
        }
        return $results;
    }

    function getTopJobs() {

        $result = array();
        $query = "SELECT job.id,job.title AS jobtitle,company.name AS companyname,cat.cat_title AS cattile,job.stoppublishing,
        salaryfrom.rangestart AS salaryfrom, salaryto.rangestart AS salaryto
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrange` AS salaryfrom ON job.salaryrangefrom = salaryfrom.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrange` AS salaryto ON job.salaryrangeto = salaryto.id
        ORDER BY job.created desc LIMIT 5";

        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);

        return;
    }

    function approveQueueJobModel($id) {
        if (is_numeric($id) == false) return false;

        $row = WPJOBPORTALincluder::getJSTable('job');
        if($row->load($id)){
            $row->columns['status'] = 1;
            $startpublishing = strtotime($row->startpublishing);
            $stoppublishing = strtotime($row->stoppublishing);
            $datediff = $stoppublishing - $startpublishing;
            $diff_days = floor($datediff/(60*60*24));
            $row->columns['startpublishing'] = gmdate('Y-m-d H:i:s');
            $row->columns['stoppublishing'] = gmdate('Y-m-d H:i:s',strtotime(" +$diff_days days"));
            if(!$row->store()){
                return WPJOBPORTAL_APPROVE_ERROR;
            }
        }else{
            return WPJOBPORTAL_APPROVE_ERROR;
        }
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2, 3, $id); // 2 for job,3 for Approve or reject Job
        return WPJOBPORTAL_APPROVED;
    }

    function rejectQueueJobModel($id) {
        if (is_numeric($id) == false)
            return false;

        $row = WPJOBPORTALincluder::getJSTable('job');
        if (!$row->update(array('id' => $id , 'status' => -1))) {
            return WPJOBPORTAL_REJECT_ERROR;
        }

       $company_approve_email = WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(1, -1, $id);
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2, 3, $id); // 2 for job,3 for reject or approve  Job
        return WPJOBPORTAL_REJECTED;
    }

    function rejectQueueFeaturedJobModel($id) {
        if (is_numeric($id) == false)
            return false;

        //8 featured job reject
        $row = WPJOBPORTALincluder::getJSTable('job');
        if (!$row->update(array( 'id' => $id , 'isfeaturedjob' => -1))) {
            return WPJOBPORTAL_REJECT_ERROR;
        }
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2, 5, $id); // 2 for job,5 for reject or approve featured job
        return WPJOBPORTAL_REJECTED;
    }

    function approveQueueFeaturedJobModel($id) {
        if (is_numeric($id) == false) return false;

        $row = WPJOBPORTALincluder::getJSTable('job');
        if($row->load($id)){
            $row->columns['isfeaturedjob'] = 1;
            $startfeatureddate = strtotime($row->startfeatureddate);
            $endfeatureddate = strtotime($row->endfeatureddate);
            $datediff = $endfeatureddate - $startfeatureddate;
            $diff_days = floor($datediff/(60*60*24));
            $row->columns['startfeatureddate'] = gmdate('Y-m-d H:i:s');
            $row->columns['endfeatureddate'] = gmdate('Y-m-d H:i:s',strtotime(" +$diff_days days"));
            if(!$row->store()){
                return WPJOBPORTAL_APPROVE_ERROR;
            }
        }else{
            return WPJOBPORTAL_APPROVE_ERROR;
        }
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2, 5, $id); // 2 for job,5 for reject or approve featured job
        return WPJOBPORTAL_APPROVED;
    }

    function approveQueueAllJobsModel($id, $actionid) {
        /*
         * *  1 for comp&gold
         * *  2 for comp&feature
         * *  3 for gold&feature
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;
        switch ($actionid) {
            case '1':
                $result = $this->approveQueueJobModel($id);
                break;
            case '2':
                $result = $this->approveQueueJobModel($id);
                $result = $this->approveQueueFeaturedJobModel($id);
                break;
            case '3':
                $result = $this->approveQueueFeaturedJobModel($id);
                break;
            case '4':
                $result = $this->approveQueueFeaturedJobModel($id);
                $result = $this->approveQueueJobModel($id);
                break;
        }
        return $result;
    }

    function rejectQueueAllJobsModel($id, $actionid) {
        /*
         * *  1 for comp&gold
         * *  2 for comp&feature
         * *  3 for gold&feature
         * *  4 for All
         */
        if (!is_numeric($id))
            return false;
        switch ($actionid) {
            case '1':
                $result = $this->rejectQueueJobModel($id);
                //$result = $this->rejectQueueGoldJobModel($id);
                break;
            case '2':
                $result = $this->rejectQueueJobModel($id);
                $result = $this->rejectQueueFeaturedJobModel($id);
                break;
            case '3':
                //$result = $this->rejectQueueGoldJobModel($id);
                $result = $this->rejectQueueFeaturedJobModel($id);
                break;
            case '4':
                //$result = $this->rejectQueueGoldJobModel($id);
                $result = $this->rejectQueueFeaturedJobModel($id);
                $result = $this->rejectQueueJobModel($id);
                break;
        }
        return $result;
    }

    function getMultiCityData($jobid) {
        if (!is_numeric($jobid))
            return false;

        $query = "SELECT mjob.*,city.id AS cityid,city.name AS cityname ,state.name AS statename,country.name AS countryname
                FROM " . wpjobportal::$_db->prefix . "wj_portal_jobcities AS mjob
                LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_cities AS city on mjob.cityid=city.id
                LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_states AS state on city.stateid=state.id
                LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_countries AS country on city.countryid=country.id
                WHERE mjob.jobid=" . esc_sql($jobid);

        $data = wpjobportaldb::get_results($query);
        if (is_array($data) AND ! empty($data)) {
            $i = 0;
            $multicitydata = "";
            foreach ($data AS $multicity) {
                $last_index = count($data) - 1;
                if ($i == $last_index)
                    $multicitydata.=$multicity->cityname;
                else
                    $multicitydata.=$multicity->cityname . " ,";
                $i++;
            }
            if ($multicitydata != "") {
                $mc = esc_html(__('JS multi city', 'wp-job-portal'));
                $multicity = (wpjobportalphplib::wpJP_strlen($multicitydata) > 35) ? $mc . wpjobportalphplib::wpJP_substr($multicitydata, 0, 35) . '...' : $multicitydata;
                return $multicity;
            } else
                return;
        }
    }
    // joomla code
    // function getSearchOptions() {
    //     $searchjobconfig = wpjobportal::$_config->getConfigByFor('searchjob');

    //     $searchoptions = array();
    //     $companies = WPJOBPORTALincluder::getJSModel('company')->getAllCompaniesForSearchForCombo(esc_html(__('JS search all', 'wp-job-portal')));
    //     $job_type = WPJOBPORTALincluder::getJSModel('jobtype')->getJobType(esc_html(__('JS_SEARCH_ALL', 'wp-job-portal')));
    //     $jobstatus = WPJOBPORTALincluder::getJSModel('jobstatus')->getJobStatus(esc_html(__('JS_SEARCH_ALL', 'wp-job-portal')));
    //     $heighesteducation = WPJOBPORTALincluder::getJSModel('highesteducation')->getHeighestEducation(esc_html(__('JS search all', 'wp-job-portal')));
    //     $job_categories = WPJOBPORTALincluder::getJSModel('category')->getCategoriesForCombo(esc_html(__('JS search all', 'wp-job-portal')), '');
    //     $job_salaryrange = WPJOBPORTALincluder::getJSModel('salaryrange')->getJobSalaryRangeForCombo(esc_html(__('JS search all', 'wp-job-portal')), '');
    //     $shift = WPJOBPORTALincluder::getJSModel('shift')->getShift(esc_html(__('JS search all', 'wp-job-portal')));
    //     $countries = WPJOBPORTALincluder::getJSModel('country')->getCountriesForCombo('');

    //     if (!isset($this->_config)) {
    //         $this->_config = wpjobportal::$_config->getConfig();
    //     }
    //     $searchoptions['country'] = WPJOBPORTALformfield::select('select.genericList', $countries, 'country', 'class="inputbox required" ' . 'onChange="dochange(\'state\', this.value)"', 'value', 'text', '');
    //     if (isset($states[1]))
    //         if ($states[1] != '')
    //             $searchoptions['state'] = WPJOBPORTALformfield::select('select.genericList', $states, 'state', 'class="inputbox" ' . 'onChange="dochange(\'city\', this.value)"', 'value', 'text', '');
    //     if (isset($cities[1]))
    //         if ($cities[1] != '')
    //             $searchoptions['city'] = WPJOBPORTALformfield::select('select.genericList', $cities, 'city', 'class="inputbox" ' . '', 'value', 'text', '');
    //     $searchoptions['companies'] = WPJOBPORTALformfield::select('select.genericList', $companies, 'company', 'class="inputbox" ' . '', 'value', 'text', '');
    //     $searchoptions['jobcategory'] = WPJOBPORTALformfield::select('select.genericList', $job_categories, 'jobcategory', 'class="inputbox" ' . '', 'value', 'text', '');
    //     $searchoptions['jobsalaryrange'] = WPJOBPORTALformfield::select('select.genericList', $job_salaryrange, 'jobsalaryrange', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', '');
    //     $searchoptions['salaryrangefrom'] = WPJOBPORTALformfield::select('select.genericList', WPJOBPORTALincluder::getJSModel('salaryrange')->getSalaryRangeForCombo(esc_html(__('JS From', 'wp-job-portal'))), 'salaryrangefrom', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', '');
    //     $searchoptions['salaryrangeto'] = WPJOBPORTALformfield::select('select.genericList', WPJOBPORTALincluder::getJSModel('salaryrange')->getSalaryRangeForCombo(esc_html(__('JS To', 'wp-job-portal'))), 'salaryrangeto', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', '');
    //     $searchoptions['salaryrangetypes'] = WPJOBPORTALformfield::select('select.genericList', WPJOBPORTALincluder::getJSModel('salaryrangetype')->getSalaryRangeTypes(''), 'salaryrangetype', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', 2);
    //     $searchoptions['jobstatus'] = WPJOBPORTALformfield::select('select.genericList', $jobstatus, 'jobstatus', 'class="inputbox" ' . '', 'value', 'text', '');
    //     $searchoptions['jobtype'] = WPJOBPORTALformfield::select('select.genericList', $job_type, 'jobtype', 'class="inputbox" ' . '', 'value', 'text', '');
    //     $searchoptions['heighestfinisheducation'] = WPJOBPORTALformfield::select('select.genericList', $heighesteducation, 'heighestfinisheducation', 'class="inputbox" ' . '', 'value', 'text', '');
    //     $searchoptions['shift'] = WPJOBPORTALformfield::select('select.genericList', $shift, 'shift', 'class="inputbox" ' . '', 'value', 'text', '');
    //     $searchoptions['currency'] = WPJOBPORTALformfield::select('select.genericList', WPJOBPORTALincluder::getJSModel('currency')->getCurrency(), 'currency', 'class="inputbox" ' . 'style="width:150px;"', 'value', 'text', '');
    //     $result = array();
    //     $result[0] = $searchoptions;
    //     $result[1] = $searchjobconfig;
    //     return $result;
    // }

    function getJobbyIdForView($job_id) {
        ////Start's WOrking From There FRIDAY//31..2020
        if (is_numeric($job_id) == false) return false;
        global $job_portal_theme_options;
        $query = "SELECT job.*,company.url AS companyurl,company.logofilename,company.city AS compcity,company.isfeaturedcompany,cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle, company.id As companyid, company.alias AS companyalias
            ,(SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE jobid = job.id) AS totalapply
                , jobstatus.title AS jobstatustitle";
                 if(in_array('departments', wpjobportal::$_active_addons)){
                    $query .= ", department.name AS departmentname";
                }
                $query .= " , salarytype.id AS salarytypeid
                , education.title AS educationtitle
                ,LOWER(jobtype.title) AS jobtypetit,careerlevel.title AS careerleveltitle,salarytype.title AS srangetypetitle,jobtype.color AS jobtypecolor,company.contactemail AS companyemail ";
                if(in_array('shortlist', wpjobportal::$_active_addons)){
                    $query .= " ,jobshort.jobid AS isshort ";
                }

        $query .= " FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply  ON jobapply.jobid = job.id
                    ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id ";
                    if(in_array('departments', wpjobportal::$_active_addons)){
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_departments` AS department ON job.departmentid = department.id";
                    }

        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salarytype ON salarytype.id = job.salaryduration
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_heighesteducation` AS education ON job.educationid = education.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_careerlevels` AS careerlevel ON careerlevel.id = job.careerlevel ";
        if(in_array('shortlist', wpjobportal::$_active_addons)){
            $query .= "LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobshortlist` AS jobshort ON jobshort.jobid = job.id
        ";
        }
        $query .= " WHERE  job.id = " . esc_sql($job_id) ."";
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
        $job = wpjobportal::$_data[0];
        wpjobportal::$_data[0]->multicity = WPJOBPORTALincluder::getJSModel('wpjobportal')->getMultiCityDataForView($job_id, 1);
        wpjobportal::$_data[0]->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView(wpjobportal::$_data[0]->compcity);
        wpjobportal::$_data[2] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(2);
        $string = "'company', 'jobapply','social'";
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigurationByConfigForMultiple($string);
        $theme = wp_get_theme();

        $layout = WPJOBPORTALrequest::getVar('wpjobportallt');
        if($layout == 'viewjob'){
            if(wpjobportal::$_data[0] != '' && wpjobportal::$_data[0]->metakeywords != '' ){
                $_SESSION['m_keywords'] = wpjobportal::$_data[0]->metakeywords;
            }
        }

        if(wpjobportal::$theme_chk != 0){
            // Related Jobs data
            $max = $job_portal_theme_options['maximum_relatedjobs'];
            if(!is_numeric($max)){
                $max = 5;
            }
            $finaljobs = array();
            $relatedjobs=array();
            //var_dump($job_portal_theme_options['relatedjob_criteria_sorter']['enabled']);
            foreach($job_portal_theme_options['relatedjob_criteria_sorter']['enabled'] AS $key => $value){
                $inquery = '';
                switch($key){
                    case 'type':
                        if(wpjobportal::$_data[0]->jobtype != '' && is_numeric(wpjobportal::$_data[0]->jobtype)){
                            $inquery = ' job.jobtype = ' . esc_sql(wpjobportal::$_data[0]->jobtype);
                        }
                    break;
                    case 'category':
                        if(wpjobportal::$_data[0]->jobcategory != '' && is_numeric(wpjobportal::$_data[0]->jobcategory)){
                            $inquery = ' job.jobcategory = ' . esc_sql(wpjobportal::$_data[0]->jobcategory);
                        }
                    break;
                    case 'location':
                        if(wpjobportal::$_data[0]->city != ''){
                            $inquery = ' job.city IN (' . esc_sql(wpjobportal::$_data[0]->city) .')';
                        }
                    break;
                }
                if(!empty($inquery)){
                    $query = "SELECT job.id,job.title,job.alias,job.created,job.city AS jobcity,company.id AS companyid,company.url AS companyurl,company.logofilename,company.city AS compcity,company.isfeaturedcompany,cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle
                            ,job.salarytype,job.salarymin,job.salarymax,salarytype.title AS salarydurationtitle,job.currency
                            , jobstatus.title AS jobstatustitle,job.created
                            ,LOWER(jobtype.title) AS jobtypetit,job.isfeaturedjob,job.startfeatureddate,job.endfeatureddate
                            ,jobtype.color AS jobtypecolor,job.params

                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes`  AS jobtype ON job.jobtype = jobtype.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salarytype ON job.salaryduration = salarytype.id

                    WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()
                    AND ".$inquery." AND job.id != ".esc_sql($job_id)." LIMIT ".esc_sql($max);
                    $result = wpjobportaldb::get_results($query);
                    $relatedjobs = array_merge($relatedjobs, $result);
                    $relatedjobs = array_map('unserialize', array_unique(array_map('serialize', $relatedjobs)));
                    if(COUNT($relatedjobs) >= $max){
                        break;
                    }
                }
            }
            if(!empty($relatedjobs)){
                foreach ($relatedjobs AS $d) {
                    $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->jobcity);
                    $finaljobs[] = $d;
                }
            }
            wpjobportal::$_data['relatedjobs'] = $finaljobs;
        }
        //update the job view counter
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_jobs` SET hits = hits + 1 WHERE id = " . esc_sql($job_id);
        wpjobportal::$_db->query($query);
        wpjobportal::$_data['submission_type'] =  wpjobportal::$_config->getConfigValue('submission_type');
       return;
    }

    function getPackagePopupJobView(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-package-popup-job-view') ) {
            die( 'Security check Failed' );
        }
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $jobapplyid = WPJOBPORTALrequest::getVar('wpjobportalid');
        $subtype = wpjobportal::$_config->getConfigValue('submission_type');
        if( $subtype != 3 ){
            return false;
        }
        $userpackages = array();
        $userpackage = apply_filters('wpjobportal_addons_credit_get_Packages_user',false,$uid,'jobapply');
        if(is_array($userpackage)){
            foreach($userpackage as $package){
                if($package->jobapply == -1 || $package->remjobapply > 0){ //-1 = unlimited
                    $userpackages[] = $package;
                }
            }
        }
        $addonclass = '';
        if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled()){
            $addonclass = ' wjportal-elegant-addon-packages-popup ';
        }
        if (wpjobportal::$theme_chk == 1) {
            $content = '
            <div id="wpj-jp-popup-background" style="display: none;"></div>
            <div id="package-popup" class="wpj-jp-popup-wrp wpj-jp-packages-popup">
                <div class="wpj-jp-popup-cnt-wrp">
                    <i class="fas fa-times wpj-jp-popup-close-icon" data-dismiss="modal"></i>
                    <h3 class="wpj-jp-popup-heading">
                        '.esc_html(__("Select Package",'wp-job-portal')).'
                        <div class="wpj-jp-popup-desc">
                            '.esc_html(__("Please select a package first",'wp-job-portal')).'
                        </div>
                    </h3>
                    <div class="wpj-jp-popup-contentarea">
                        <div class="wpj-jp-packages-wrp">';
                            if(count($userpackages) == 0  || empty($userpackages)){
                                $content .= WPJOBPORTALmessages::showMessage(esc_html(__("You do not have any Job Apply remaining",'wp-job-portal')),'error',1);
                            } else {
                                foreach($userpackages as $package){
                                    $content .= '
                                        <div class="wpj-jp-pkg-item" id="package-div-'.esc_attr($package->id).'" >
                                            <div class="wpj-jp-pkg-item-top">
                                                <h4 class="wpj-jp-pkg-item-title">
                                                    '.esc_html(wpjobportal::wpjobportal_getVariableValue( $package->title)).'
                                                </h4>
                                            </div>
                                            <div class="wpj-jp-pkg-item-mid">
                                                <div class="wpj-jp-pkg-item-row">
                                                    <span class="wpj-jp-pkg-item-tit">
                                                        '.esc_html(__("Job Apply",'wp-job-portal')).' :
                                                    </span>
                                                    <span class="wpj-jp-pkg-item-val">
                                                        '.($package->jobapply==-1 ? esc_html(__("Unlimited",'wp-job-portal')) : esc_attr($package->jobapply)).'
                                                    </span>
                                                </div>
                                                <div class="wpj-jp-pkg-item-row">
                                                    <span class="wpj-jp-pkg-item-tit">
                                                        '.esc_html(__("Remaining",'wp-job-portal')).' :
                                                    </span>
                                                    <span class="wpj-jp-pkg-item-val">
                                                        '.($package->jobapply==-1 ? esc_html(__("Unlimited",'wp-job-portal')) : esc_attr($package->remjobapply)).'
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="wpj-jp-pkg-item-btm">
                                                <a href="#" class="wpj-jp-outline-btn wpj-jp-block-btn" onclick="selectPackage('.esc_attr($package->id).');" title="'.esc_attr(__("Select package",'wp-job-portal')).'">
                                                    '.esc_html(__("Select Package",'wp-job-portal')).'
                                                </a>
                                            </div>
                                        </div>
                                    ';
                                }
                            }
                        $content .= '</div>
                        <div class="wpj-jp-popup-msgs" id="wjportal-package-message"> </div>
                    </div>';
                    if(count($userpackages) != 0  && !empty($userpackages)){
                        $content .= '
                        <div class="wpj-jp-visitor-msg-btn-wrp">
                            <input type="hidden" id="wpjobportal_packageid" name="wpjobportal_packageid">
                            <input type="submit" rel="button" id="jsre_featured_button" class="wpj-jp-visitor-msg-btn" onclick="getApplyNowByJobid('. esc_attr($jobapplyid) .','.wpjobportal::wpjobportal_getPageid().','.$package->id.')" value="'.esc_attr(__('Apply On This Job','wp-job-portal')).'"  data-dismiss="modal" disabled/>
                        </div>';
                    }
                    $content .= '
                </div>
            </div>';
        } else {
            $content = '
            <div id="wjportal-popup-background" style="display: none;"></div>
            <div id="package-popup" class="wjportal-popup-wrp wjportal-packages-popup '.$addonclass.'">
                <div class="wjportal-popup-cnt">
                    <img id="wjportal-popup-close-btn" alt="popup cross" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/popup-close.png">
                    <div class="wjportal-popup-title">
                        '.esc_html(__("Select Package",'wp-job-portal')).'
                        <div class="wjportal-popup-title3">
                            '.esc_html(__("Please select a package first",'wp-job-portal')).'
                        </div>
                    </div>
                    <div class="wjportal-popup-contentarea">
                        <div class="wjportal-packages-wrp">';
                            if(count($userpackages) == 0  || empty($userpackages)){
                                $content .= WPJOBPORTALmessages::showMessage(esc_html(__("You do not have any Job Apply remaining",'wp-job-portal')),'error',1);
                            } else {
                                foreach($userpackages as $package){
                                    $content .= '
                                        <div class="wjportal-pkg-item" id="package-div-'.esc_attr($package->id).'" >
                                            <div class="wjportal-pkg-item-top">
                                                <div class="wjportal-pkg-item-title">
                                                    '.$package->title.'
                                                </div>
                                            </div>
                                            <div class="wjportal-pkg-item-btm">
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.esc_html(__("Job Apply",'wp-job-portal')).' :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.($package->jobapply==-1 ? esc_html(__("Unlimited",'wp-job-portal')) : esc_attr($package->jobapply)).'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-row">
                                                    <span class="wjportal-pkg-item-tit">
                                                        '.esc_html(__("Remaining",'wp-job-portal')).' :
                                                    </span>
                                                    <span class="wjportal-pkg-item-val">
                                                        '.($package->jobapply==-1 ? esc_html(__("Unlimited",'wp-job-portal')) : esc_attr($package->remjobapply)).'
                                                    </span>
                                                </div>
                                                <div class="wjportal-pkg-item-btn-row">
                                                    <a href="#" class="wjportal-pkg-item-btn" onclick="selectPackage('.esc_attr($package->id).');">
                                                        '.esc_html(__("Select Package",'wp-job-portal')).'
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                                }
                                /*$content .= '<div class="wjportal-pkg-help-txt">
                                        '.esc_html(__("Click on package to select one",'wp-job-portal')).'
                                        </div>';*/
                            }
                        $content .= '</div>
                        <div class="wjportal-popup-msgs" id="wjportal-package-message"> </div>
                    </div>
                    <div class="wjportal-visitor-msg-btn-wrp">
                        <input type="hidden" id="wpjobportal_packageid" name="wpjobportal_packageid">
                        <input type="submit" selected_pack="0" rel="button" id="jsre_featured_button" class="wjportal-visitor-msg-btn disabled" onclick="hidePackagePopupForJobApply()" value="'.esc_html(__('Apply On This Job','wp-job-portal')).'"  data-dismiss="modal" disabled/>
                    </div>
                </div>
            </div>';
        }
        echo wp_kses($content, WPJOBPORTAL_ALLOWED_TAGS);
        exit();
    }

    function checkAlreadyAppliedJob($jobid, $uid) {
        if (!is_numeric($jobid))
            return false;
        if (!is_numeric($uid))
            return false;
        unset($result);
        $query = "SELECT COUNT(id) as no,status FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE jobid = " . esc_sql($jobid) . " AND uid = " . esc_sql($uid);
        $result = wpjobportal::$_db->get_row($query);
        return $result;
    }

    function getJobTitleById($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT job.title FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job WHERE job.id = " . esc_sql($id);
        $jobname = wpjobportal::$_db->get_var($query);
        return $jobname;
    }

    function getJobsExpiryStatus($jobid) {
        if (!is_numeric($jobid))
            return false;
        $curdate = date_i18n('Y-m-d');
        $query = "SELECT job.id
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        WHERE job.status = 1 AND DATE(job.stoppublishing) >= DATE('" . esc_sql($curdate) . "')
        AND job.id =" . esc_sql($jobid);
        $result = wpjobportal::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }


    function getJobPay($jobid){
        if (!is_numeric($jobid))
            return false;
        $curdate = date_i18n('Y-m-d');
        $query = "SELECT job.id
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        WHERE job.status = 3
        AND job.id =" . esc_sql($jobid);
        $result = wpjobportal::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }

    }

    function getJobbyId($jobid) {
        if ($jobid) {
            if (!is_numeric($jobid))
                return false;
            $query = "SELECT job.* ,cat.cat_title
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                WHERE job.id = " . esc_sql($jobid);
            wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
        }
        if (isset(wpjobportal::$_data[0])) {
            wpjobportal::$_data[0]->multicity = wpjobportal::$_common->getMultiSelectEdit($jobid, 1);
            wpjobportal::$_data[0]->jobtags = wpjobportal::$_common->makeFilterdOrEditedTagsToReturn( wpjobportal::$_data[0]->tags );
        }
       wpjobportal::$_data[2] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(2); // job fields
    }

    function getJobForApply($jobid) {
        $data = array();
        if ($jobid) {
            if (!is_numeric($jobid))
                return false;
            $query = "SELECT job.* ,cat.cat_title, jobtype.title AS jobtypetitle, company.name AS companyname,company.logofilename ,company.id AS companyid,salaryrangetype.title AS salaryrangetype,jobtype.color AS jobtypecolor,salaryrangetype.title AS srangetypetitle
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryduration
                WHERE job.id = " . esc_sql($jobid);
            $data = wpjobportaldb::get_row($query);
        }
        return $data;
        if (isset(wpjobportal::$_data[0])) {
            wpjobportal::$_data[0]->multicity = wpjobportal::$_common->getMultiSelectEdit($jobid, 1);
            wpjobportal::$_data[0]->jobtags = wpjobportal::$_common->makeFilterdOrEditedTagsToReturn( wpjobportal::$_data[0]->tags );
        }
       wpjobportal::$_data[2] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(2); // job fields
    }

    function sorting() {
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        wpjobportal::$_data['sorton'] = isset(wpjobportal::$_search['jobs']['sorton']) ? wpjobportal::$_search['jobs']['sorton'] : 6;
        wpjobportal::$_data['sortby'] = isset(wpjobportal::$_search['jobs']['sortby']) ? wpjobportal::$_search['jobs']['sortby'] : 2;
        switch (wpjobportal::$_data['sorton']) {
            case 6: // created
                wpjobportal::$_data['sorting'] = ' job.created ';
                break;
            case 2: // company name
                wpjobportal::$_data['sorting'] = ' company.name ';
                break;
            case 3: // category
                wpjobportal::$_data['sorting'] = ' cat.cat_title ';
                break;
            case 5: // location
                wpjobportal::$_data['sorting'] = ' city.name ';
                break;
            case 7: // status
                wpjobportal::$_data['sorting'] = ' job.jobstatus ';
                break;
            case 1: // job title
                wpjobportal::$_data['sorting'] = ' job.title ';
                break;
            case 4: // job type
                wpjobportal::$_data['sorting'] = ' jobtype.title ';
                break;
            case 8:
                wpjobportal::$_data['sorting'] = ' job.salarymax ';
                break;
        }
        if (wpjobportal::$_data['sortby'] == 1) {
            wpjobportal::$_data['sorting'] .= ' ASC ';
        } else {
            wpjobportal::$_data['sorting'] .= ' DESC ';
        }
        wpjobportal::$_data['combosort'] = wpjobportal::$_data['sorton'];
        //die(wpjobportal::$_data['combosort']);
    }

    function checkLinks($name) {
        if(isset(wpjobportal::$_data['fields'])){
            foreach (wpjobportal::$_data['fields'] as $field) {
                $array =  array();
                $array[0] = 0;
                switch ($field->field) {
                    case $name:
                    if($field->showonlisting == 1){
                        $array[0] = 1;
                        $array[1] =  $field->fieldtitle;
                    }
                    return $array;
                    break;
                }
            }
            return $array;
        }else{
            return '';
        }
    }

    function getAllJobs() {
        //die('abc');
        $this->sorting();
        //filters
        $searchtitle = wpjobportal::$_search['jobs']['searchtitle'];
        $searchcompany = wpjobportal::$_search['jobs']['searchcompany'];
        $searchjobcategory = wpjobportal::$_search['jobs']['searchjobcategory'];
        $searchjobtype = wpjobportal::$_search['jobs']['searchjobtype'];
        $status = wpjobportal::$_search['jobs']['status'];
        $featured = wpjobportal::$_search['jobs']['featured'];
        $datestart = wpjobportal::$_search['jobs']['datestart'];
        $dateend = wpjobportal::$_search['jobs']['dateend'];
        $location = wpjobportal::$_search['jobs']['location'];

        wpjobportal::$_data['filter']['searchtitle'] = $searchtitle;
        wpjobportal::$_data['filter']['searchcompany'] = $searchcompany;
        wpjobportal::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        wpjobportal::$_data['filter']['searchjobtype'] = $searchjobtype;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['featured'] = $featured;
        wpjobportal::$_data['filter']['datestart'] = $datestart;
        wpjobportal::$_data['filter']['dateend'] = $dateend;
        wpjobportal::$_data['filter']['location'] = $location;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($status)
            if (is_numeric($status) == false)
                return false;

        $this->checkCall();
        $curdate = gmdate('Y-m-d');
        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(job.title) LIKE '%" . esc_sql($searchtitle) . "%'";
        if ($searchcompany)
            $inquery .= " AND LOWER(company.name) LIKE '%" . esc_sql($searchcompany) . "%'";
        if ($searchjobcategory && is_numeric($searchjobcategory))
            $inquery .= " AND job.jobcategory = " . esc_sql($searchjobcategory);
        if ($searchjobtype && is_numeric($searchjobtype))
            $inquery .= " AND job.jobtype = " . esc_sql($searchjobtype);
        if ($dateend != null){
            $dateend = gmdate('Y-m-d',strtotime($dateend));
            $inquery .= " AND DATE(job.created) <= '" . esc_sql($dateend) . "'";
        }
        if ($datestart != null){
            $datestart = gmdate('Y-m-d',strtotime($datestart));
            $inquery .= " AND DATE(job.created) >= '" . esc_sql($datestart) . "'";
        }
        if ($status != null && is_numeric($status))
            $inquery .= " AND job.status = " . esc_sql($status);
        if ($featured != null)
            $inquery .= " AND job.isfeaturedjob = 1 AND DATE(job.startfeatureddate) <= '".esc_sql($curdate)."' AND DATE(job.endfeatureddate) >= '".esc_sql($curdate)."'";
        if ($location != null)
            $inquery .= " AND city.name LIKE '%" . esc_sql($location) . "%'";

        $query = "SELECT COUNT(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT cityid FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryduration
                WHERE job.status != 0 ";
        $query.=$inquery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle, company.name AS companyname ,company.logofilename AS logo ,company.id AS companyid,salaryrangetype.title AS salaryrangetype,jobtype.color AS jobtypecolor,
                ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE jobid = job.id AND status = 1) AS totalresume
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryduration
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT cityid FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                WHERE job.status != 0";
        $query.=$inquery;
        $query.= " ORDER BY" . wpjobportal::$_data['sorting'];
        $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;

        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(2);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('job');

        return;
    }

    function getAllUnapprovedJobs() {
        $this->sorting();
        //filters
        $searchtitle = wpjobportal::$_search['jobs']['searchtitle'];
        $searchcompany = wpjobportal::$_search['jobs']['searchcompany'];
        $searchjobcategory = wpjobportal::$_search['jobs']['searchjobcategory'];
        $searchjobtype = wpjobportal::$_search['jobs']['searchjobtype'];
        $status = wpjobportal::$_search['jobs']['status'];
        $featured = wpjobportal::$_search['jobs']['featured'];
        $datestart = wpjobportal::$_search['jobs']['datestart'];
        $dateend = wpjobportal::$_search['jobs']['dateend'];
        $location = wpjobportal::$_search['jobs']['location'];

        wpjobportal::$_data['filter']['searchtitle'] = $searchtitle;
        wpjobportal::$_data['filter']['searchcompany'] = $searchcompany;
        wpjobportal::$_data['filter']['searchjobcategory'] = $searchjobcategory;
        wpjobportal::$_data['filter']['searchjobtype'] = $searchjobtype;
        wpjobportal::$_data['filter']['status'] = $status;
        wpjobportal::$_data['filter']['featured'] = $featured;
        wpjobportal::$_data['filter']['datestart'] = $datestart;
        wpjobportal::$_data['filter']['dateend'] = $dateend;
        wpjobportal::$_data['filter']['location'] = $location;

        if ($searchjobcategory)
            if (is_numeric($searchjobcategory) == false)
                return false;
        if ($searchjobtype)
            if (is_numeric($searchjobtype) == false)
                return false;
        if ($status)
            if (is_numeric($status) == false)
                return false;

        $this->checkCall();

        $inquery = "";
        if ($searchtitle)
            $inquery .= " AND LOWER(job.title) LIKE '%" . esc_sql($searchtitle) . "%'";
        if ($searchcompany)
            $inquery .= " AND LOWER(company.name) LIKE '%" . esc_sql($searchcompany) . "%'";
        if ($searchjobcategory && is_numeric($searchjobcategory))
            $inquery .= " AND job.jobcategory = " . esc_sql($searchjobcategory);
        if ($searchjobtype && is_numeric($searchjobtype))
            $inquery .= " AND job.jobtype = " . esc_sql($searchjobtype);
        if ($dateend != null){
            $dateend = gmdate('Y-m-d',strtotime($dateend));
            $inquery .= " AND DATE(job.created) <= '" . esc_sql($dateend) . "'";
        }
        if ($datestart != null){
            $datestart = gmdate('Y-m-d',strtotime($datestart));
            $inquery .= " AND DATE(job.created) >= '" . esc_sql($datestart) . "'";
        }
        if ($status != null && is_numeric($status))
            $inquery .= " AND job.status = " . esc_sql($status);
        if ($featured != null)
            $inquery .= " AND job.isfeaturedjob = 1";
        if ($location != null)
            $inquery .= " AND city.name LIKE '%" . esc_sql($location) . "%'";

        $query = "SELECT COUNT(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT cityid FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                 WHERE (job.status = 0";
        if(in_array('featuredjob', wpjobportal::$_active_addons)){
            $query .= " OR isfeaturedjob = 0)";
        }else{
            $query .= ")";
        }
        $query.=$inquery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        // company is must for job so changed it join from left join
        $query = "SELECT job.*, cat.cat_title, jobtype.title AS jobtypetitle,company.logofilename AS logofilename, company.name AS companyname ,salaryrangetype.title AS salaryrangetype,jobtype.color AS jobtypecolor,job.currency,
                ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE jobid = job.id) AS totalresume
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT cityid FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` WHERE jobid = job.id ORDER BY id DESC LIMIT 1)
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryduration
                WHERE (job.status = 0";
        if(in_array('featuredjob', wpjobportal::$_active_addons)){
            $query .= " OR isfeaturedjob = 0)";
        }else{
            $query .= ")";
        }
        $query.= $inquery;
        $query.= " ORDER BY" . wpjobportal::$_data['sorting'];
        $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(2);

        return;
    }

    function storeJob($data) {
       if (empty($data))
            return false;
        $isnew  = isset($data['id']) && ((int)$data['id']) ? 0 : 1;
        $user =WPJOBPORTALincluder::getObjectClass('user');
        $row = WPJOBPORTALincluder::getJSTable('job');
        if(isset($data['companyid'])){
            $data['uid'] = WPJOBPORTALincluder::getJSModel('company')->getUidByCompanyId($data['companyid']);
        }else{
            $data['uid'] = false;
        }

        // need to reheck this case
        if($data['companyid'] !=''){
            if(!wpjobportal::$_common->wpjp_isadmin()){ // maiking sure crrent record is not added/updated by admin
                if(!WPJOBPORTALincluder::getJSModel('company')->getIfCompanyOwner($data['companyid'])){ // check if current user owns the company for which the job is being created
                    return WPJOBPORTAL_SAVE_ERROR; // if current user does not own the company he is posting job for return false.
                }
            }
        }

        if($data['uid'] == false){
            $data['uid'] = '';
        }
        $dateformat = wpjobportal::$_configuration['date_format'];
        $ignore_stop_pulbishing_verification = 0;
        if ($data['id'] == '') {
            if(in_array('credits', wpjobportal::$_active_addons)){
                $submission_type   = wpjobportal::$_config->getConfigValue('submission_type');
                if($submission_type == 1){
                    #Per listing --Free job Expiry date
                    $expiry = wpjobportal::$_config->getConfigValue('jobexpiry_days_free');
                    if(isset($data['stoppublishing']) && empty($data['stoppublishing'])){
                        $data['stoppublishing'] = gmdate($dateformat,strtotime($data['startpublishing'].'+'.$expiry.' days') );
                    }
                    if (!wpjobportal::$_common->wpjp_isadmin()) {
                        $data['status'] = wpjobportal::$_config->getConfigurationByConfigName('jobautoapprove');
                    }
                }elseif ($submission_type == 2) {
                    #Per listing --Free job Expiry date
                    $expiry = wpjobportal::$_config->getConfigValue('jobexpiry_days_perlisting');
                    if(isset($data['stoppublishing']) && empty($data['stoppublishing'])){
                        $data['stoppublishing'] = gmdate($dateformat,strtotime($data['startpublishing'].'+'.$expiry.' days') );
                    }
                    if (!wpjobportal::$_common->wpjp_isadmin()) {
                        // in case of per listing submission mode
                        $price_check = WPJOBPORTALincluder::getJSModel('credits')->checkIfPriceDefinedForAction('add_job');
                        if($price_check == 1){ // if price is defined then status 3
                            $data['status'] = 3;
                        }else{ // if price not defined then status set to auto approve configuration
                            $data['status'] = wpjobportal::$_config->getConfigValue('jobautoapprove');
                        }
                    }else{
                        $data['status'] = 1;
                    }
                }elseif ($submission_type == 3) {
                    $upakid = WPJOBPORTALrequest::getVar('upakid',null,0);
                    if ($data['payment'] == 0 && wpjobportal::$_common->wpjp_isadmin()) {
                        $upakid = WPJOBPORTALrequest::getVar('upakid',null,0);
                        $data['userpackageid'] = $upakid;
                    } else {
                        if(!wpjobportal::$_common->wpjp_isadmin()){
                            $package = WPJOBPORTALincluder::getJSModel('purchasehistory')->getUserPackageById($upakid,$user->uid(),'remjob');
                        }elseif (wpjobportal::$_common->wpjp_isadmin()) {
                            $package = WPJOBPORTALincluder::getJSModel('purchasehistory')->getUserPackageById($upakid,$data['uid'],'remjob');
                        }
                        if( !$package ){
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                        if( $package->expired ){
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                        //if Department are not unlimited & there is no remaining left
                        if( $package->job!=-1 && !$package->remjob ){ //-1 = unlimited
                            return WPJOBPORTAL_SAVE_ERROR;
                        }
                    }
                    #user packae id--
                    if (!wpjobportal::$_common->wpjp_isadmin()) {
                        $data['status'] = wpjobportal::$_config->getConfigValue('jobautoapprove');
                    }
                    $data['userpackageid'] = $upakid;
                    if(isset($package) && !empty($package)){
                        $expiry = $package->jobtime.''.$package->jobtimeunit;
                    }else{
                        $expiry = "30 days"; // in case of undefined add job for 30 days
                    }
                    $curdate = date_i18n('Y-m-d');
                    $data['stoppublishing'] = gmdate($dateformat,strtotime($curdate.'+'.$expiry));
                }
            }else{
                if(isset($data['draft']) && $data['draft'] == 1 ){
                    $data['status'] = 4;
                }else{
                    if(!wpjobportal::$_common->wpjp_isadmin()){
                        $data['status'] = wpjobportal::$_config->getConfigurationByConfigName('jobautoapprove');
                    }
                }
            }
        }else{ // edit
            if(!wpjobportal::$_common->wpjp_isadmin()){ // checking if is admin
                // verify that can current user is editing his owned entity
                $id = $data['id'];
                if(! $this->getIfJobOwner($id)){
                    // if current entity being edited is not owned by current user dont allow to procced further
                    return false;
                }
                //handle stop publishing date in edit case
                if(in_array('credits', wpjobportal::$_active_addons)){ // if package system
                    $submission_type   = wpjobportal::$_config->getConfigValue('submission_type');
                    if($submission_type == 3){// if membership mode is selected
                        unset($data['stoppublishing']); // making sure the value does not update
                        $ignore_stop_pulbishing_verification = 1;
                    }
                }
            }
       }
        #Free,per-listing,Package --Adjust
        if($ignore_stop_pulbishing_verification == 0){ // to handle edit in case in membership mode
            if(isset($data['stoppublishing'])){
                $data['stoppublishing'] = gmdate('Y-m-d H:i:s', strtotime($data['stoppublishing']));
            }else{ // if stop publishing date
                $expiry = "2 years";
                $curdate = date_i18n('Y-m-d');
                $data['stoppublishing'] = gmdate('Y-m-d H:i:s',strtotime($curdate.'+'.$expiry));
            }
        }

        $data['jobapplylink'] = isset($data['jobapplylink']) ? 1 : 0;
        if (!empty($data['alias']))
            $jobalias = wpjobportal::$_common->removeSpecialCharacter($data['alias']);
        else
            $jobalias = wpjobportal::$_common->removeSpecialCharacter($data['title']);

        $jobalias = wpjobportalphplib::wpJP_strtolower(wpjobportalphplib::wpJP_str_replace(' ', '-', $jobalias));
        $jobalias = wpjobportalphplib::wpJP_strtolower(wpjobportalphplib::wpJP_str_replace('_', '-', $jobalias));
        $data['alias'] = $jobalias;
        if( isset($data['salarytype']) && $data['salarytype'] == WPJOBPORTAL_SALARY_FIXED){ // min field issue
            $data['salarymin'] = $data['salaryfixed'];
            $data['salarymax'] = $data['salaryfixed'];
        }
        // Uid must be the same as the company owner id

        if ($data['id'] == '') {
            $data['jobid'] = $this->getJobId();
            $data['created'] = date_i18n("Y-m-d H:i:s");
            $data['startpublishing'] = date_i18n("Y-m-d H:i:s");
        } else {
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        }

        #Currency For Job
        $data['currency'] = wpjobportal::$_config->getConfigValue('job_currency');

        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data['description'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes(WPJOBPORTALrequest::getVar('description','post','','',1))));
        if(isset($data['qualifications'])){
            $data['qualifications'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes(sanitize_textarea_field(WPJOBPORTALrequest::getVar('qualifications','post','','',1)))));
        }
        if(isset($data['qualifications'])){
            $data['prefferdskills'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes(sanitize_textarea_field(WPJOBPORTALrequest::getVar('prefferdskills','post','','',1)))));
        }
        if(isset($data['qualifications'])){
            $data['agreement'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes(sanitize_textarea_field(WPJOBPORTALrequest::getVar('agreement','post','','',1)))));
        }
          // commented this code to make storing customfields same for all entites.
        // //custom field code start
        // $userfieldforjob = wpjobportal::$_wpjpfieldordering->getUserfieldsfor(2);
        // $params = array();
        // foreach ($userfieldforjob AS $ufobj) {
        //     $vardata = isset($data[$ufobj->field]) ? $data[$ufobj->field] : '';
        //     if($vardata != ''){
        //         /*if($ufobj->userfieldtype == 'multiple'){ // multiple field change its behave
        //             $vardata = wpjobportalphplib::wpJP_explode(',', $vardata[0]); // fixed index
        //         }*/
        //         if(is_array($vardata)){
        //             $vardata = implode(', ', $vardata);
        //         }
        //         $params[$ufobj->field] = wpjobportalphplib::wpJP_htmlspecialchars($vardata);
        //     }
        // }
        // $params = wp_json_encode($params);
        // $data['params'] = $params;
        //custom field code end
        if(!isset($data['jobapplylink'])){
            $data['jobapplylink'] = 0;
        }
        if(empty($data['uid'])){
            $data['uid'] = $user->uid();
        }

        // handle email alert for base version job apply
        if(!in_array('jobalert', wpjobportal::$_active_addons)){
            $data['sendemail'] = 1; // setting this one to make sure for base plugin the email alert is based on admin email settings
        }

// already satinzed above
        if(WPJOBPORTALincluder::getJSModel('common')->checkLanguageSpecialCase()){
           $data = wpjobportal::$_common->stripslashesFull($data);
        }

    	$description = WPJOBPORTALrequest::getVar('description','post','','',1);// sanitizing description data
        $data['description'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes($description)));
        // remove slashes with quotes.
        if (!$row->bind($data)) {
           return WPJOBPORTAL_SAVE_ERROR;
        }

        if (!$row->check()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        $jobid = $row->id;
        wpjobportal::$_data['id'] = $row->id;

        //store custom fields (same as company module)
        wpjobportal::$_wpjpcustomfield->storeCustomFields(2,$jobid,$data);
        if(in_array('credits', wpjobportal::$_active_addons)){
            if($isnew && $submission_type == 3){
                $trans = WPJOBPORTALincluder::getJSTable('transactionlog');
                $arr = array();
                if(!wpjobportal::$_common->wpjp_isadmin()){
                    $arr['uid'] = $user->uid();
                }elseif (wpjobportal::$_common->wpjp_isadmin()) {
                    $arr['uid'] = $data['uid'];
                }
                $arr['userpackageid'] = $upakid;
                $arr['recordid'] = $row->id;
                $arr['type'] = 'job';
                $arr['created'] = current_time('mysql');
                $arr['status'] = 1;
                $trans->bind($arr);
                $trans->store();
            }
        }

        if ($data['id'] == '') {
            WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2, 1, $row->id); // 2 for Job,1 for add new Job
        } else {
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        }
        if (isset($data['city']))
            $storemulticity = $this->storeMultiCitiesJob($data['city'], $row->id);
        if (isset($storemulticity) && $storemulticity == false)
            return false;

        // action hook for add job
        if(empty($data['id'])){ // changed the if to handle problem case (it will handle unset and null set value case)
            $data['id'] = $row->id;
        }
        do_action('wpjobportal_after_store_job_hook',$data);

        return WPJOBPORTAL_SAVED;
    }

    function captchaValidate($tellafriend = 0,$token_v3 = '') {
        if (!is_user_logged_in()) {
            $config_array = wpjobportal::$_config->getConfigByFor('captcha');
            if($tellafriend == 0){
                $captcha_check = $config_array['job_captcha'];
            }else{
                $captcha_check = $config_array['tell_a_friend_captcha'];
            }

            if ($captcha_check == 1) {
                if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                    if($token_v3 == ''){
                        $google_recaptcha = WPJOBPORTALrequest::getVar('g-recaptcha-response','post','');
                        if($google_recaptcha != ''){
                            $gresponse = wpjobportal::wpjobportal_sanitizeData($google_recaptcha);
                        }
                    }else{
                        $gresponse = $token_v3;
                    }
                    $resp = googleRecaptchaHTTPPost($config_array['recaptcha_privatekey'] , $gresponse);

                    if ($resp) {
                        return true;
                    } else {
                        wpjobportal::$_data['google_captchaerror'] = esc_html(__("Invalid captcha",'wp-job-portal'));
                        return false;
                    }
                } else { // own captcha
                    $captcha = new WPJOBPORTALcaptcha;
                    $result = $captcha->checkCaptchaUserForm();
                    if ($result == 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function storeMultiCitiesJob($city_id, $jobid) { // city id comma seprated
        if (!is_numeric($jobid))
            return false;

        $query = "SELECT cityid FROM " . wpjobportal::$_db->prefix . "wj_portal_jobcities WHERE jobid = " . esc_sql($jobid);

        $old_cities = wpjobportaldb::get_results($query);

        $id_array = wpjobportalphplib::wpJP_explode(",", $city_id);
        $error = array();

        foreach ($old_cities AS $oldcityid) {
            $match = false;
            foreach ($id_array AS $cityid) {
                if ($oldcityid->cityid == $cityid) {
                    $match = true;
                    break;
                }
            }
            if ($match == false) {
                $query = "DELETE FROM " . wpjobportal::$_db->prefix . "wj_portal_jobcities WHERE jobid = " . esc_sql($jobid) . " AND cityid = " . esc_sql($oldcityid->cityid);
                if (!wpjobportaldb::query($query)) {
                    $error[] = wpjobportal::$_db->last_error;
                }
            }
        }
        foreach ($id_array AS $cityid) {
            $insert = true;
            foreach ($old_cities AS $oldcityid) {
                if ($oldcityid->cityid == $cityid) {
                    $insert = false;
                    break;
                }
            }
            if ($insert) {
                $row = WPJOBPORTALincluder::getJSTable('jobcities');
                $cols['jobid'] = $jobid;
                $cols['cityid'] = $cityid;
                if (!$row->bind($cols)) {
                    $error[] = wpjobportal::$_db->last_error;
                }
                if (!$row->store()) {
                    $error[] = wpjobportal::$_db->last_error;
                }
            }
        }
        if (empty($error))
            return true;
        else
            return false;
    }

    function deleteJobs($ids) {
        if (empty($ids))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('job');
        $notdeleted = 0;

        foreach ($ids as $id) {
            if(!is_numeric($id)){
                continue;
            }
            if ($this->jobCanDelete($id) == true) {
                $mailextradata = array();
                $resultforsendmail = WPJOBPORTALincluder::getJSModel('job')->getJobInfoForEmail($id);
                if(empty($resultforsendmail)){ // to handle log errors in case of empty object or false coming from the "getJobInfoForEmail" function
                  $mailextradata['jobtitle'] = '';
                  $mailextradata['companyname'] = '';
                  $mailextradata['user'] = '';
                  $mailextradata['useremail'] = '';
                }else{
                  $mailextradata['jobtitle'] = $resultforsendmail->jobtitle;
                  $mailextradata['companyname'] = $resultforsendmail->companyname;
                  $mailextradata['user'] = $resultforsendmail->username;
                  $mailextradata['useremail'] = $resultforsendmail->useremail;
                }

                if (!$row->delete($id)) {
                    $notdeleted += 1;
                } else {
                    $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` WHERE jobid = " . esc_sql($id);
                    wpjobportaldb::query($query);
                    if(in_array('shortlist', wpjobportal::$_active_addons)){
                        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobshortlist` WHERE jobid = " . esc_sql($id);
                        wpjobportaldb::query($query);
                    }
                    WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2, 2, $id,$mailextradata); // 2 for job,2 for DELETE job
                    // action hook for delete job
                    do_action('wpjobportal_after_delete_job_hook',$id);
                }
            } else {
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            WPJOBPORTALMessages::$counter = false;
            return WPJOBPORTAL_DELETED;
        } else {
            WPJOBPORTALMessages::$counter = $notdeleted;
            return WPJOBPORTAL_DELETE_ERROR;
        }
    }

    function jobCanDelete($jobid) {
        if (!is_numeric($jobid))
            return false;
        if(!wpjobportal::$_common->wpjp_isadmin()){
            if(!$this->getIfJobOwner($jobid)){
                return false;
            }
        }
        $query = "SELECT
                    ( SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE jobid = " . esc_sql($jobid) . ")
                    AS total ";
        $total = wpjobportaldb::get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getJobInfoForEmail($jobid) {
        if ((is_numeric($jobid) == false))
            return false;
        $query = "SELECT job.title AS jobtitle, company.contactemail AS useremail,company.name AS companyname, user.first_name AS username
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid=company.id
                    JOIN `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user ON user.id = job.uid
                    WHERE job.id =" . esc_sql($jobid);
        $return_value = wpjobportaldb::get_row($query);
        return $return_value;
    }

    function jobEnforceDelete($jobid, $uid) {
        if (is_numeric($jobid) == false)
            return false;
        $serverjodid = 0;
        $inquery = "";
        $foq = "";
        $select_query = "";
        if(in_array('message', wpjobportal::$_active_addons)){
            $select_query .= ",message";
            $inquery .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_messages` AS message ON job.id=message.jobid";
        }
        if(in_array('shortlist', wpjobportal::$_active_addons)){
            $select_query .= ",jobshortlist";
            $inquery .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobshortlist` AS jobshortlist ON job.id=jobshortlist.jobid";
        }
        $query = "DELETE  job,apply,jobcity ";
        $query .= $select_query;
        $query .= "
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS apply ON job.id=apply.jobid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity ON job.id=jobcity.jobid";
                    $query .= $inquery;
                    $query .= " WHERE job.id = " . esc_sql($jobid);
        if (!wpjobportaldb::query($query)) {
            return WPJOBPORTAL_DELETE_ERROR;
        }
        // action hook for delete job
        do_action('wpjobportal_after_delete_job_hook',$jobid);
        return WPJOBPORTAL_DELETED;
    }

    function featuredJobValidation($jobid) {
        if (!is_numeric($jobid))
            return false;
        $query = "SELECT COUNT(job.id) FROM " . wpjobportal::$_db->prefix . "wj_portal_jobs  AS job
                WHERE job.isfeaturedjob=1 AND job.id = " . esc_sql($jobid);
        $result = wpjobportaldb::get_var($query);
        if ($result > 0)
            return false;
        else
            return true;
    }

    function checkCall() {
        // DB class limitations
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config` SET configvalue = configvalue+1 WHERE configname = 'wpjobportalupdatecount'";
        wpjobportaldb::query($query);
        $query = "SELECT configvalue AS wpjobportalupdatecount FROM `" . wpjobportal::$_db->prefix . "wj_portal_config` WHERE configname = 'wpjobportalupdatecount'";
        $result = wpjobportaldb::get_var($query);
        if ($result >= 100) {
            WPJOBPORTALincluder::getJSModel('wpjobportal')->getConcurrentrequestdata();
        }
    }

    function getJobId() {

        $query = "Select jobid from `" . wpjobportal::$_db->prefix . "wj_portal_jobs`";
        $match = '';
        do {

            $jobid = "";
            $length = 9;
            $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ!@#";
            // we refer to the length of $possible a few times, so let's grab it now
            $maxlength = wpjobportalphplib::wpJP_strlen($possible);
            // check for length overflow and truncate if necessary
            if ($length > $maxlength) {
                $length = $maxlength;
            }
            // set up a counter for how many characters are in the password so far
            $i = 0;
            // add random characters to $password until $length is reached
            while ($i < $length) {
                // pick a random character from the possible ones
                $char = wpjobportalphplib::wpJP_substr($possible, wp_rand(0, $maxlength - 1), 1);
                // have we already used this character in $password?

                if (!wpjobportalphplib::wpJP_strstr($jobid, $char)) {
                    if ($i == 0) {
                        if (ctype_alpha($char)) {
                            $jobid .= $char;
                            $i++;
                        }
                    } else {
                        $jobid .= $char;
                        $i++;
                    }
                }
            }

            $rows = wpjobportaldb::get_results($query);
            foreach ($rows as $row) {
                if ($jobid == $row->jobid)
                    $match = 'Y';
                else
                    $match = 'N';
            }
        }while ($match == 'Y');
        return $jobid;
    }

    function getJobSearch() {
    //Filters
        $title = WPJOBPORTALrequest::getVar('title');
        $jobcategory = WPJOBPORTALrequest::getVar('jobcategory');
        $jobsubcategory = WPJOBPORTALrequest::getVar('jobsubcategory');
        $jobtype = WPJOBPORTALrequest::getVar('jobtype');
        $jobstatus = WPJOBPORTALrequest::getVar('jobstatus');
        $salaryrangefrom = WPJOBPORTALrequest::getVar('salaryrangefrom');
        $salaryrangeto = WPJOBPORTALrequest::getVar('salaryrangeto');
        $salaryrangetype = WPJOBPORTALrequest::getVar('salaryrangetype');
        $shift = WPJOBPORTALrequest::getVar('shift');
        $durration = WPJOBPORTALrequest::getVar('durration');
        $startpublishing = WPJOBPORTALrequest::getVar('startpublishing');
        $stoppublishing = WPJOBPORTALrequest::getVar('stoppublishing');
        $company = WPJOBPORTALrequest::getVar('company');
        $city = WPJOBPORTALrequest::getVar('city');
        $zipcode = WPJOBPORTALrequest::getVar('zipcode');
        $currency = WPJOBPORTALrequest::getVar('currency');
        $longitude = WPJOBPORTALrequest::getVar('longitude');
        $latitude = WPJOBPORTALrequest::getVar('latitude');
        $radius = WPJOBPORTALrequest::getVar('radius');
        $radius_length_type = WPJOBPORTALrequest::getVar('radius_length_type');
        $keywords = WPJOBPORTALrequest::getVar('keywords');

        wpjobportal::$_data['filter']['title'] = $title;
        wpjobportal::$_data['filter']['jobcategory'] = $jobcategory;
        wpjobportal::$_data['filter']['jobsubcategory'] = $jobsubcategory;
        wpjobportal::$_data['filter']['jobtype'] = $jobtype;
        wpjobportal::$_data['filter']['jobstatus'] = $jobstatus;
        wpjobportal::$_data['filter']['salaryrangefrom'] = $salaryrangefrom;
        wpjobportal::$_data['filter']['salaryrangeto'] = $salaryrangeto;
        wpjobportal::$_data['filter']['salaryrangetype'] = $salaryrangetype;
        wpjobportal::$_data['filter']['shift'] = $shift;
        wpjobportal::$_data['filter']['durration'] = $durration;
        wpjobportal::$_data['filter']['startpublishing'] = $startpublishing;
        wpjobportal::$_data['filter']['stoppublishing'] = $stoppublishing;
        wpjobportal::$_data['filter']['company'] = $company;
        wpjobportal::$_data['filter']['city'] = $city;
        wpjobportal::$_data['filter']['zipcode'] = $zipcode;
        wpjobportal::$_data['filter']['currency'] = $currency;
        wpjobportal::$_data['filter']['longitude'] = $longitude;
        wpjobportal::$_data['filter']['latitude'] = $latitude;
        wpjobportal::$_data['filter']['radius'] = $radius;
        wpjobportal::$_data['filter']['radius_length_type'] = $radius_length_type;
        wpjobportal::$_data['filter']['keywords'] = $keywords;

        if ($jobcategory != '')
            if (is_numeric($jobcategory) == false)
                return false;
        if ($jobsubcategory != '')
            if (is_numeric($jobsubcategory) == false)
                return false;
        if ($jobtype != '')
            if (is_numeric($jobtype) == false)
                return false;
        if ($jobstatus != '')
            if (is_numeric($jobstatus) == false)
                return false;
        if ($salaryrangefrom != '')
            if (is_numeric($salaryrangefrom) == false)
                return false;
        if ($salaryrangeto != '')
            if (is_numeric($salaryrangeto) == false)
                return false;
        if ($salaryrangetype != '')
            if (is_numeric($salaryrangetype) == false)
                return false;
        if ($shift != '')
            if (is_numeric($shift) == false)
                return false;
        if ($company != '')
            if (is_numeric($company) == false)
                return false;
        if ($currency != '')
            if (is_numeric($currency) == false)
                return false;


        $dateformat = wpjobportal::$_configuration['date_format'];
        if ($startpublishing != '') {
            $startpublishing = gmdate('Y-m-d', strtotime($startpublishing));
        }
        if ($stoppublishing != '') {
            $stoppublishing = gmdate('Y-m-d', strtotime($stoppublishing));
        }

        $issalary = '';
        //for radius search
        switch ($radius_length_type) {
            case "m":$radiuslength = 6378137;
                break;
            case "km":$radiuslength = 6378.137;
                break;
            case "mile":$radiuslength = 3963.191;
                break;
            case "nacmiles":$radiuslength = 3441.596;
                break;
        }
        if ($keywords) {// For keyword Search
            $keywords = wpjobportalphplib::wpJP_explode(' ', $keywords);
            $length = count($keywords);
            if ($length <= 5) {// For Limit keywords to 5
                $i = $length;
            } else {
                $i = 5;
            }
            for ($j = 0; $j < $i; $j++) {
                $keys[] = " job.metakeywords Like '%" . esc_sql($keywords[$j]) . "%'";
            }
        }
        $selectdistance = " ";
        if ($longitude != '' && $latitude != '' && $radius != '') {
            $radiussearch = " acos((SIN( PI()* ".esc_sql($latitude)." /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* ".esc_sql($latitude)." /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* ".esc_sql($longitude)." /180)))* ".esc_sql($radiuslength)." <= ".esc_sql($radius);
            $selectdistance = " ,acos((sin(PI()*".esc_sql($latitude)."/180)*sin(PI()*job.latitude/180))+(cos(PI()*".esc_sql($latitude)."/180)*cos(PI()*job.latitude/180)*cose(PI()*job.longitude/180 - PI()*".esc_sql($longitude)."/180)))*".esc_sql($radiuslength)." AS distance ";
        }

        $wherequery = '';
        if ($title != '') {
            $title_keywords = wpjobportalphplib::wpJP_explode(' ', $title);
            $tlength = count($title_keywords);
            if ($tlength <= 5) {// For Limit keywords to 5
                $r = $tlength;
            } else {
                $r = 5;
            }
            for ($k = 0; $k < $r; $k++) {
                $t_keywords = wpjobportalphplib::wpJP_str_replace("'", "", $title_keywords[$k]);
                $titlekeys[] = " job.title LIKE '%" . esc_sql($t_keywords) . "%'";
            }
        }
        if ($jobcategory != '' && is_numeric($jobcategory))
            if ($jobcategory != '')
                $wherequery .= " AND job.jobcategory = " . esc_sql($jobcategory);
        if (isset($keys))
            $wherequery .= " AND ( " . implode(' OR ', esc_sql($keys)) . " )";
        if (isset($titlekeys))
            $wherequery .= " AND ( " . implode(' OR ', esc_sql($titlekeys)) . " )";
        if ($jobsubcategory != '' && is_numeric($jobsubcategory))
            $wherequery .= " AND job.subcategoryid = " . esc_sql($jobsubcategory);
        if ($jobtype != '' && is_numeric($jobtype))
            $wherequery .= " AND job.jobtype = " . esc_sql($jobtype);
        if ($jobstatus != '' && is_numeric($jobstatus))
            $wherequery .= " AND job.jobstatus = " . esc_sql($jobstatus);
        if ($salaryrangefrom != '') {
            $query = "SELECT salfrom.rangestart
            FROM `" . wpjobportal::$_db->prefix . "wj_portal_salaryrange` AS salfrom
            WHERE salfrom.id = " . esc_sql($salaryrangefrom);

            $rangestart_value = wpjobportaldb::get_var($query);
            $wherequery .= " AND salaryrangefrom.rangestart >= " . esc_sql($rangestart_value);
            $issalary = 1;
        }
        if ($salaryrangeto != '') {
            $query = "SELECT salto.rangestart
            FROM `" . wpjobportal::$_db->prefix . "wj_portal_salaryrange` AS salto
            WHERE salto.id = " . esc_sql($salaryrangeto);

            $rangeend_value = wpjobportaldb::get_var($query);
            $wherequery .= " AND salaryrangeto.rangeend <= " . esc_sql($rangeend_value);
            $issalary = 1;
        }
        if (($issalary != '') && ($salaryrangetype != '' && is_numeric($salaryrangetype))) {
            $wherequery .= " AND job.salaryrangetype = " . esc_sql($salaryrangetype);
        }
        if ($shift != '' && is_numeric($shift))
            $wherequery .= " AND job.shift = " . esc_sql($shift);
        if ($durration != '')
            $wherequery .= " AND job.duration LIKE '" . esc_sql($durration) . "'";
        if ($startpublishing != '')
            $wherequery .= " AND job.startpublishing >= '" . esc_sql($startpublishing) . "'";
        if ($stoppublishing != '')
            $wherequery .= " AND job.stoppublishing <= '" . esc_sql($stoppublishing) . "'";
        if ($company != '' && is_numeric($company))
            $wherequery .= " AND job.companyid = " . esc_sql($company);
        if ($city != '') {
            $city_value = wpjobportalphplib::wpJP_explode(',', $city);
            $lenght = count($city_value);
            for ($i = 0; $i < $lenght; $i++) {
                if(is_numeric($city_value[$i])){
                    if ($i == 0){
                        $wherequery .= " AND ( mjob.cityid=" . esc_sql($city_value[$i]);
                    }else{
                        $wherequery .= " OR mjob.cityid=" . esc_sql($city_value[$i]);
                    }
                }
            }
            $wherequery .= ")";
        }

        if ($zipcode != '')
            $wherequery .= " AND job.zipcode = '" . esc_sql($zipcode) . "'";
        if (isset($radiussearch) && $radiussearch != '')
            $wherequery .= " AND " . esc_sql($radiussearch);

        //Pagination
        $curdate = gmdate('Y-m-d');
        $query = "SELECT count(DISTINCT job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrange` AS salaryrangefrom ON job.salaryrangefrom = salaryrangefrom.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrange` AS salaryrangeto ON job.salaryrangeto = salaryrangeto.id";
        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS mjob ON mjob.jobid = job.id ";
        $query .= " WHERE job.status = 1 ";
        if ($startpublishing == '')
            $query .= " AND DATE(job.startpublishing) <= " . esc_sql($curdate);
        if ($stoppublishing == '')
            $query .= " AND DATE(job.stoppublishing) >= " . esc_sql($curdate);
        $query .= $wherequery;

        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        // company is must for job
        $query = "SELECT DISTINCT job.*, cat.cat_title, jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle
                , salaryrangefrom.rangestart AS salaryfrom, salaryrangeto.rangeend AS salaryend
                , company.name AS companyname, company.url
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrange` AS salaryrangefrom ON job.salaryrangefrom = salaryrangefrom.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrange` AS salaryrangeto ON job.salaryrangeto = salaryrangeto.id";
        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS mjob ON mjob.jobid = job.id ";
        $query .= " WHERE  job.status = 1 ";
        if ($startpublishing == '')
            $query .= " AND DATE(job.startpublishing) <= " . esc_sql($curdate);
        if ($stoppublishing == '')
            $query .= " AND DATE(job.stoppublishing) >= " . esc_sql($curdate);
        if ($currency != '')
            $query.= " AND currency.id = job.currencyid ";
        $query .= $wherequery;
        $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;

        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);

        foreach (wpjobportal::$_data[0] AS $searchdata) {  // for multicity select
            $multicitydata = $this->getMultiCityData($searchdata->id);
            if ($multicitydata != "")
                $searchdata->city = $multicitydata;
        }

        return;
    }

    function getMyJobs($uid) {
       if (!is_numeric($uid)) return false;
        # Sorting
        $this->sorting();
        $this->checkCall();
        # pagination
        $query = "SELECT COUNT(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                WHERE job.uid =". esc_sql($uid);
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total,'myjobs');

        # Data Query Listing
        // company is must for job
        $query = "SELECT job.endfeatureddate,job.id,job.uid,job.title,job.isfeaturedjob,job.serverid,job.noofjobs,job.city,job.status,
                CONCAT(job.alias,'-',job.id) AS jobaliasid,job.created,job.serverid,company.name AS companyname,company.id AS companyid,company.logofilename,CONCAT(company.alias,'-',company.id) AS compnayaliasid,job.salarytype,job.salarymin,job.salarymax,salaryrangetype.title AS salarydurationtitle,job.currency,
                cat.cat_title, jobtype.title AS jobtypetitle,salaryrangetype.title AS srangetypetitle,
                (SELECT count(jobapply.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply
                 WHERE jobapply.jobid = job.id and jobapply.status = 1) AS resumeapplied ,job.params,job.startpublishing,job.stoppublishing, job.description
                 ,LOWER(jobtype.title) AS jobtypetit,jobtype.color AS jobtypecolor
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON job.city = city.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryduration
                WHERE job.uid =". esc_sql($uid) ;
        # Sorting Merge In Query
        $query.= " ORDER BY" . wpjobportal::$_data['sorting'];
        $query.=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        $results = wpjobportaldb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        $results = $data;
         wpjobportal::$_data[0] = $data;
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(2);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('job');
        return;
    }

    function getJobsByCompany($cid) {
        if (!is_numeric($cid)) return false;
        
        $query = "SELECT job.endfeatureddate,job.id,job.uid,job.title,job.isfeaturedjob,job.city,job.status,
        CONCAT(job.alias,'-',job.id) AS jobaliasid,job.created,company.name AS companyname,company.id AS companyid,company.logofilename,job.salarytype,job.salarymin,job.salarymax,salaryrangetype.title AS salarydurationtitle,job.currency,
                cat.cat_title, jobtype.title AS jobtypetitle,salaryrangetype.title AS srangetypetitle ,job.startpublishing,job.stoppublishing
                 ,LOWER(jobtype.title) AS jobtypetit,jobtype.color AS jobtypecolor
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON job.city = city.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryduration
                WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE() and company.id =". esc_sql($cid) ;
        # Sorting Merge In Query
        $query.= " ORDER BY job.created DESC LIMIT 3";
        $results = wpjobportaldb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        $results = $data;
        return $data;
    }

    function getJobsByCategories() {
        $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid,category.id AS categoryid
            ,(SELECT count(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                WHERE job.status = 1 AND job.jobcategory = category.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
            FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category
            WHERE category.isactive = 1 AND category.parentid = 0 ORDER BY category.ordering ASC";
        $categories = wpjobportaldb::get_results($query);
        $config_array = wpjobportal::$_config->getConfigByFor('category');
        $subcategory_limit = 3;
        if($config_array['subcategory_limit'] != ''){ // to handle float value in configuration
            $subcategory_limit = ceil($config_array['subcategory_limit']);
        }
        foreach($categories AS $category){
            $total = 0;
            $query = "SELECT category.cat_title, CONCAT(category.alias,'-',category.id) AS aliasid,category.serverid
                ,(SELECT count(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    WHERE job.jobcategory = category.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category
                WHERE category.isactive = 1 AND category.parentid = ".esc_sql($category->categoryid)." ORDER BY category.ordering ASC ";
            $subcats = wpjobportal::$_db->get_results($query);
            $i = 0;
            foreach ($subcats as $id => $scat) {
                $total += $scat->totaljobs;
                if($subcategory_limit <= $i){
                    unset($subcats[$id]);
                }
                $i++;
            }
            $category->subcat = $subcats;
            $category->total_sub_jobs = $total;
        }


        if(wpjobportal::$_configuration['job_resume_show_all_categories'] == 0){//conifguration based
            $final_arr = array();
            foreach ($categories as $job_category) {
                if($job_category->totaljobs != 0 || $job_category->total_sub_jobs != 0){
                    $final_arr[] = $job_category;
                }
            }
            $categories = $final_arr;
        }

        wpjobportal::$_data[0] = $categories;
        wpjobportal::$_data['config'] =  wpjobportal::$_config->getConfigByFor('category');
        return;
    }

    function getJobsByTypes() {
        $query = "SELECT jobtype.title,jobtype.id, jobtype.serverid,CONCAT(jobtype.alias,'-',jobtype.id) AS alias
                ,(SELECT count(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                where job.status = 1 AND job.jobtype = jobtype.id AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE())  AS totaljobs
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype
                WHERE jobtype.isactive = 1 ORDER BY jobtype.ordering ASC"; // to show job types by the ordering set from admin side
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        wpjobportal::$_data['config'] =  wpjobportal::$_config->getConfigByFor('jobtype');
        return;
    }


    function getJobsByCities(){
        $query="SELECT city.id AS cityid, city.name AS cityname
                ,country.name AS countryname,COUNT(job.id) AS totaljobs
                FROM `". wpjobportal::$_db->prefix ."wj_portal_jobcities` AS jobc
                JOIN `". wpjobportal::$_db->prefix ."wj_portal_jobs` AS job ON jobc.jobid = job.id
                JOIN `". wpjobportal::$_db->prefix ."wj_portal_cities` AS city ON city.id = jobc.cityid
                JOIN `". wpjobportal::$_db->prefix ."wj_portal_countries` AS country ON country.id = city.countryid
                WHERE country.enabled = 1 AND job.status = 1
                AND DATE(job.stoppublishing) >= CURDATE() AND DATE(job.startpublishing) <= CURDATE() GROUP BY city.id ORDER BY cityname";
        $data = wpjobportaldb::get_results($query);
        wpjobportal::$_data[0] = $data;
        wpjobportal::$_data['config'] =  wpjobportal::$_config->getConfigByFor('jobcity');
        return;
    }


    private function makeQueryFromArray($for, $array) {
        if (empty($array))
            return false;

        if (!is_array($array) && $for != 'metakeywords' && $for != 'tags') {
            $newarray[] = $array;
            $array = $newarray;
        }
        $qa = array();
        switch ($for) {
            case 'metakeywords':
                $array = wpjobportalphplib::wpJP_explode(",", $array);
                $total = count($array);
                if ($total > 5)
                    $total = 5;
                for ($i = 0; $i < $total; $i++) {
                    $qa[] = "job.metakeywords LIKE '%" . esc_sql($array[$i]) . "%'";
                }
                break;
            case 'company':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.companyid = " . esc_sql($item);
                    }
                }
                break;
            case 'category':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` WHERE parentid = ". esc_sql($item);
                        $cats = wpjobportaldb::get_results($query);
                        $ids = [];
                        foreach ($cats as $cat) {
                            $ids[] = $cat->id;
                        }
                        $ids[] = $item;
                        $ids = implode(",",$ids);
                        $qa[] = "job.jobcategory IN(" . esc_sql($ids).")";
                    }
                }
                break;
            case 'careerlevel':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.careerlevel = " . esc_sql($item);
                    }
                }
                break;
            case 'jobtype':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.jobtype = " . esc_sql($item);
                    }
                }
                break;
            case 'jobstatus':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.jobstatus = " . esc_sql($item);
                    }
                }
                break;
            case 'education':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        $qa[] = "job.educationid = " . esc_sql($item);
                    }
                }
                break;
            case 'city':
                $a = wpjobportalphplib::wpJP_explode(',', $array[0]);
                foreach ($a as $item) {
                    if (is_numeric($item)) {
                        //$qa[] = "job.city LIKE '%" . esc_sql($item) . "%'";
                        $qa[] = "  FIND_IN_SET('" . esc_sql($item) . "', job.city) > 0 ";
                    }
                }
                break;
            case 'job_cities':
                foreach ($array as $item) {
                    if (is_numeric($item)) {
                        //$qa[] = "job.city LIKE '%" . esc_sql($item) . "%'";
                        $qa[] = "  FIND_IN_SET('" . esc_sql($item) . "', job.city) > 0 ";
                    }
                }
                break;
            case 'tags':
                $array = wpjobportalphplib::wpJP_explode(',', $array);
                foreach ($array as $item) {
                    $qa[] = "job.tags LIKE '%" . esc_sql($item) . "%'";
                }
                break;
            case 'job_tags':
                foreach ($array as $item) {
                    $qa[] = "job.tags LIKE '%" . esc_sql($item) . "%'";
                }
                break;
            case 'jobid': // new case for shortcode atttributes
                $job_id_list = implode(',', $array);
                $qa[] = "  job.id IN (" . esc_sql($job_id_list) . ") ";
                break;
            default:
                return false;
                break;
        }
        $query = implode(" OR ", $qa);
        return $query;
    }

    function isvalidJSON($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

    function getRSSJobs() {
        $job_rss = wpjobportal::$_config->getConfigurationByConfigName('job_rss');
        if ($job_rss == 1) {
            $curdate = gmdate('Y-m-d H:i:s');
            $query = "SELECT job.title,job.noofjobs,job.id, cat.cat_title,company.logofilename AS logofilename,company.id AS companyid,
                        company.name AS comp_title,jobtype.title AS jobtype,jobstatus.title AS jobstatus,CONCAT(job.alias,'-',job.id) AS jobaliasid,salarytype.title AS salarytype
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                        ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON job.jobtype = jobtype.id
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salarytype ON job.salaryrangetype = salarytype.id
                        WHERE job.status = 1 AND job.startpublishing <= '" . esc_sql($curdate) . "' AND job.stoppublishing >= '" . esc_sql($curdate) . "'";
            $query .= " ORDER BY  job.startpublishing DESC";
            $jobs = wpjobportal::$_db->get_results($query);
            return $jobs;
        }
        return false;
    }

    private function processShortcodeAttributes() {
        $inquery = "";

        /* shortcode attributes
    'companies' => '',
    'categories' => '',
    'types' => '',
    'locations' => '',
    'ids' => '',
         */

        // comapnies
        $company_list = WPJOBPORTALrequest::getVar('companies', 'shortcode_option', false);
        if ($company_list && $company_list !='' ) {
            $company_array = explode(',', $company_list);// attribute contains comma seprated list. converting it into array to use exsisting code
            $res = $this->makeQueryFromArray('company', $company_array);
            if ($res){
                $inquery .= " AND ( " . esc_sql($res ). " )";
            }
        }

        // cateogries
        $category_list = WPJOBPORTALrequest::getVar('categories', 'shortcode_option', false);
        if ($category_list && $category_list !='' ) {
            $category_array = explode(',', $category_list);// attribute contains comma seprated list. converting it into array to use exsisting code
            $res = $this->makeQueryFromArray('category', $category_array);
            if ($res){
                $inquery .= " AND ( " . esc_sql($res ). " )";
            }
        }


        // jobtypes
        $jobtype_list = WPJOBPORTALrequest::getVar('types', 'shortcode_option', false);
        if ($jobtype_list && $jobtype_list !='') {
            $jobtype_array = explode(',', $jobtype_list);// attribute contains comma seprated list. converting it into array to use exsisting code
            $res = $this->makeQueryFromArray('jobtype', $jobtype_array);
            if ($res){
                $inquery .= " AND ( " . esc_sql($res ). " )";
            }
        }

        // cities
        $cities_list = WPJOBPORTALrequest::getVar('locations', 'shortcode_option', false);
        if ($cities_list && $cities_list !='' ) {
            $cities_array = explode(',', $cities_list);// attribute contains comma seprated list. converting it into array to use exsisting code
            $res = $this->makeQueryFromArray('job_cities', $cities_array);
            if ($res){
                $inquery .= " AND ( " . $res. " )";// $res has quotes can not esc_sql it
            }
        }

        // job ids
        $job_id_list = WPJOBPORTALrequest::getVar('ids', 'shortcode_option', false);
        if ($job_id_list && $job_id_list !='' ) {
            $job_id_array = explode(',', $job_id_list);// attribute contains comma seprated list. converting it into array to use exsisting code
            $res = $this->makeQueryFromArray('jobid', $job_id_array);
            if ($res){
                $inquery .= " AND ( " . $res. " )";// $res has quotes can not esc_sql it
            }
        }


        // career levels
        $careerlevel_list = WPJOBPORTALrequest::getVar('careerlevels', 'shortcode_option', false);
        if ($careerlevel_list && $careerlevel_list !='' ) {
            $careerlevel_array = explode(',', $careerlevel_list);// attribute contains comma seprated list. converting it into array to use exsisting code
            $res = $this->makeQueryFromArray('careerlevel', $careerlevel_array);
            if ($res){
                $inquery .= " AND ( " . esc_sql($res ). " )";
            }
        }

        // job statuses
        $jobstatus_list = WPJOBPORTALrequest::getVar('jobstatuses', 'shortcode_option', false);
        if ($jobstatus_list && $jobstatus_list !='' ) {
            $jobstatus_array = explode(',', $jobstatus_list);// attribute contains comma seprated list. converting it into array to use exsisting code
            $res = $this->makeQueryFromArray('jobstatus', $jobstatus_array);
            if ($res){
                $inquery .= " AND ( " . esc_sql($res ). " )";
            }
        }


        // tags
        $tags_list = WPJOBPORTALrequest::getVar('tags', 'shortcode_option', false);
        if ($tags_list && $tags_list !='' ) {
            $tags_array = explode(',', $tags_list);// attribute contains comma seprated list. converting it into array to use exsisting code
            $res = $this->makeQueryFromArray('job_tags', $tags_array);
            if ($res){
                $inquery .= " AND ( " . $res. " )";// $res has quotes can not esc_sql it
            }
        }

        //handle attirbute for ordering
        $sorting = WPJOBPORTALrequest::getVar('sorting', 'shortcode_option', false);
        if($sorting && $sorting != ''){
            $this->makeOrderingQueryFromShortcodeAttributes($sorting);
            wpjobportal::$_data['shortcode_option_sorting'] = $sorting;
        }

        //handle attirbute for no of jobs
        $no_of_jobs = WPJOBPORTALrequest::getVar('no_of_jobs', 'shortcode_option', false);
        if($no_of_jobs && $no_of_jobs != ''){
            wpjobportal::$_data['shortcode_option_no_of_jobs'] = (int) $no_of_jobs;
        }


        // handle visibilty of data based on shortcode
        $this->handleDataVisibilityByShortcodeAttributes();

        return $inquery;
    }


    function handleDataVisibilityByShortcodeAttributes() {
        /*
            'hide_filter' => '',
            'hide_filter_job_title' => '',
            'hide_filter_job_location' => '',
        */

        //handle attirbute for hide filter on job listing
        $hide_filter = WPJOBPORTALrequest::getVar('hide_filter', 'shortcode_option', false);
        if($hide_filter && $hide_filter != ''){
            wpjobportal::$_data['shortcode_option_hide_filter'] = 1;
        }

        //handle attirbute for hide job title filter on job listing
        $hide_filter_job_title = WPJOBPORTALrequest::getVar('hide_filter_job_title', 'shortcode_option', false);
        if($hide_filter_job_title && $hide_filter_job_title != ''){
            wpjobportal::$_data['shortcode_option_hide_filter_job_title'] = 1;
        }

        //handle attirbute for hide job location filter on job listing
        $hide_filter_job_location = WPJOBPORTALrequest::getVar('hide_filter_job_location', 'shortcode_option', false);
        if($hide_filter_job_location && $hide_filter_job_location != ''){
            wpjobportal::$_data['shortcode_option_hide_filter_job_location'] = 1;
        }

        //handle attirbute for hide company logo on job listing
        $hide_company_logo = WPJOBPORTALrequest::getVar('hide_company_logo', 'shortcode_option', false);
        if($hide_company_logo && $hide_company_logo != ''){
            wpjobportal::$_data['shortcode_option_hide_company_logo'] = 1;
        }

        //handle attirbute for hide company name on job listing
        $hide_company_name = WPJOBPORTALrequest::getVar('hide_company_name', 'shortcode_option', false);
        if($hide_company_name && $hide_company_name != ''){
            wpjobportal::$_data['shortcode_option_hide_company_name'] = 1;
        }

    }

    function makeOrderingQueryFromShortcodeAttributes($sorting) {
        switch ($sorting) {
            case "title_desc":
                wpjobportal::$_ordering = "job.title DESC";
                break;
            case "title_asc":
                wpjobportal::$_ordering = "job.title ASC";
                break;
            case "category_desc":
                wpjobportal::$_ordering = "cat.cat_title DESC";
                break;
            case "category_asc":
                wpjobportal::$_ordering = "cat.cat_title ASC";
                break;
            case "jobtype_desc":
                wpjobportal::$_ordering = "jobtype.title DESC";
                break;
            case "jobtype_asc":
                wpjobportal::$_ordering = "jobtype.title ASC";
                break;
            case "jobstatus_desc":
                wpjobportal::$_ordering = "job.status DESC";
                break;
            case "jobstatus_asc":
                wpjobportal::$_ordering = "job.status ASC";
                break;
            case "company_desc":
                wpjobportal::$_ordering = "company.name DESC";
                break;
            case "company_asc":
                wpjobportal::$_ordering = "company.name ASC";
                break;
            case "salary_desc":
                wpjobportal::$_ordering = "srfrom.rangestart DESC";
                break;
            case "salary_asc":
                wpjobportal::$_ordering = "srfrom.rangestart ASC";
                break;
            case "posted_desc":
                wpjobportal::$_ordering = "job.created DESC";
                break;
            case "posted_asc":
                wpjobportal::$_ordering = "job.created ASC";
                break;
        }

        return;
    }



    private function getRefinedJobs($searchajax = null) {
        $inquery = "";
        if($searchajax == null){
            $keywords_a = isset(wpjobportal::$_search['jobs']['metakeywords']) ? wpjobportal::$_search['jobs']['metakeywords'] : '';
        }else{
            $keywords_a = isset($searchajax['metakeywords']) ? $searchajax['metakeywords'] : '';
        }
        if ($keywords_a) {
            wpjobportal::$_data['filter']['metakeywords'] = $keywords_a;
            $res = $this->makeQueryFromArray('metakeywords', $keywords_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $jobtitle = isset(wpjobportal::$_search['jobs']['jobtitle']) ? wpjobportal::$_search['jobs']['jobtitle'] : '';
        }else{
            $jobtitle = isset($searchajax['jobtitle']) ? $searchajax['jobtitle'] : '';
        }
        if ($jobtitle) {
            wpjobportal::$_data['filter']['jobtitle'] = $jobtitle;
            $inquery .= " AND job.title LIKE '%" . esc_sql($jobtitle) . "%'";
        }
        if($searchajax == null){
            $company_a = isset(wpjobportal::$_search['jobs']['company']) ? wpjobportal::$_search['jobs']['company'] : '';
        }else{
            $company_a = isset($searchajax['company']) ? $searchajax['company'] : '';
        }
        if ($company_a) {
            wpjobportal::$_data['filter']['company'] = $company_a;
            $res = $this->makeQueryFromArray('company', $company_a);
            if ($res)
                $inquery .= " AND ( " . esc_sql($res ). " )";
        }
        if($searchajax == null){
            $category_a = isset(wpjobportal::$_search['jobs']['category']) ? wpjobportal::$_search['jobs']['category'] : '';
        }else{
            $category_a = isset($searchajax['category']) ? $searchajax['category'] : '';
        }
        if ($category_a) {
            wpjobportal::$_data['filter']['category'] = $category_a;
            $res = $this->makeQueryFromArray('category', $category_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $jobtype_a = isset(wpjobportal::$_search['jobs']['jobtype']) ? wpjobportal::$_search['jobs']['jobtype'] : '';
        }else{
            $jobtype_a = isset($searchajax['jobtype']) ? $searchajax['jobtype'] : '';
        }
        if ($jobtype_a) {
            wpjobportal::$_data['filter']['jobtype'] = $jobtype_a;
            $res = $this->makeQueryFromArray('jobtype', $jobtype_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $careerlevel_a = isset(wpjobportal::$_search['jobs']['careerlevel']) ? wpjobportal::$_search['jobs']['careerlevel'] : '';
        }else{
            $careerlevel_a = isset($searchajax['careerlevel']) ? $searchajax['careerlevel'] : '';
        }
        if ($careerlevel_a) {
            wpjobportal::$_data['filter']['careerlevel'] = $careerlevel_a;
            $res = $this->makeQueryFromArray('careerlevel', $careerlevel_a);
            if ($res)
                $inquery .= " AND ( " .esc_sql( $res) . " )";
        }
        if($searchajax == null){
            $jobstatus_a = isset(wpjobportal::$_search['job']['jobstatus']) ? wpjobportal::$_search['job']['jobstatus'] : '';
        }else{
            $jobstatus_a = isset($searchajax['jobstatus']) ? $searchajax['jobstatus'] : '';
        }
        if ($jobstatus_a) {
            wpjobportal::$_data['filter']['jobstatus'] = $jobstatus_a;
            $res = $this->makeQueryFromArray('jobstatus', $jobstatus_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $symbol = isset(wpjobportal::$_search['jobs']['currencyid']) ? wpjobportal::$_search['jobs']['currencyid'] : '';
        }else{
            $symbol = isset($searchajax['currencyid']) ? $searchajax['currencyid'] : '';
        }
        if ($symbol) {
            if (is_numeric($symbol)) {
                wpjobportal::$_data['filter']['currencyid'] = $symbol;
                $inquery .= " AND job.currencyid = " . esc_sql($symbol);
            }
        }

        if($searchajax == null){
            $salarytype = isset(wpjobportal::$_search['jobs']['salarytype']) ? wpjobportal::$_search['jobs']['salarytype'] : '';
        }else{
            $salarytype = isset($searchajax['salarytype']) ? $searchajax['salarytype'] : '';
        }

        if ($salarytype) {
            if (is_numeric($salarytype)) {
                wpjobportal::$_data['filter']['salarytype'] = $salarytype;
                $inquery .= " AND job.salarytype = " . esc_sql($salarytype);
            }
        }


        ///Salary max min
        if($searchajax == null){
            $salaryfixed = isset(wpjobportal::$_search['jobs']['salaryfixed']) ? wpjobportal::$_search['jobs']['salaryfixed'] : '';
        }else{
            $salaryfixed = isset($searchajax['salaryfixed']) ? $searchajax['salarytype'] : '';
        }

        if ($salaryfixed) {
            if (is_numeric($salaryfixed)) {
                wpjobportal::$_data['filter']['salaryfixed'] = $salaryfixed;
                if ($salarytype == 2) {
                    $inquery .= " AND job.salarymax = " . esc_sql($salaryfixed);
                }
            }
        }


        if($searchajax == null){
            $salaryduration = isset(wpjobportal::$_search['jobs']['salaryduration']) ? wpjobportal::$_search['jobs']['salaryduration'] : '';
        }else{
            $salaryduration = isset($searchajax['salaryduration']) ? $searchajax['salarytype'] : '';
        }

        if ($salaryduration) {
            if (is_numeric($salaryduration)) {
                wpjobportal::$_data['filter']['salaryduration'] = $salaryduration;
                if ($salarytype == 2 || $salarytype == 3) {
                    $inquery .= " AND job.salaryduration = " . esc_sql($salaryduration);
                }
            }
        }


        if($searchajax == null){
            $salarymin = isset(wpjobportal::$_search['jobs']['salarymin']) ? wpjobportal::$_search['jobs']['salarymin'] : '';
        }else{
            $salarymin = isset($searchajax['salarymin']) ? $searchajax['salarytype'] : '';
        }

        if ($salarymin) {
            if (is_numeric($salarymin)) {
                wpjobportal::$_data['filter']['salarymin'] = $salarymin;
                if ($salarytype == 3) {
                    $inquery .= " AND job.salarymin >= " . esc_sql($salarymin);
                }
            }
        }

        if($searchajax == null){
            $salarymax = isset(wpjobportal::$_search['jobs']['salarymax']) ? wpjobportal::$_search['jobs']['salarymax'] : '';;
        }else{
            $salarymax = isset($searchajax['salarymax']) ? $searchajax['salarytype'] : '';
        }

        if ($salarymax) {
            if (is_numeric($salarymax)) {
                wpjobportal::$_data['filter']['salarymax'] = $salarymax;
                if ($salarytype == 3) {
                    $inquery .= " AND job.salarymax <= " . esc_sql($salarymax);
                }
            }
        }

        if($searchajax == null){
            $srangetype = isset(wpjobportal::$_search['jobs']['salaryrangetype']) ? wpjobportal::$_search['jobs']['salaryrangetype'] : '';;
        }else{
            $srangetype = isset($searchajax['salaryrangetype']) ? $searchajax['salaryrangetype'] : '';
        }
        if ($srangetype) {
            if (is_numeric($srangetype)) {
                wpjobportal::$_data['filter']['salaryrangetype'] = $srangetype;
                $inquery .= " AND job.salaryrangetype = " . esc_sql($srangetype);
            }
        }

        if($searchajax == null){
            $educationid_a = isset(wpjobportal::$_search['jobs']['educationid']) ? wpjobportal::$_search['jobs']['educationid'] : '';
        }else{
            $educationid_a = isset($searchajax['educationid']) ? $searchajax['educationid'] : '';
        }
        if ($educationid_a) {
            wpjobportal::$_data['filter']['educationid'] = $educationid_a;
            $res = $this->makeQueryFromArray('education', $educationid_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }

        if($searchajax == null){
            $city_a = isset(wpjobportal::$_search['jobs']['city']) ? wpjobportal::$_search['jobs']['city'] : '';
        }else{
            $city_a = isset($searchajax['city']) ? $searchajax['city'] : '';
        }

        if ($city_a) {
            wpjobportal::$_data['filter']['city_ids'] = $city_a;
            wpjobportal::$_data['filter']['city'] = wpjobportal::$_common->getCitiesForFilter($city_a);
            $res = $this->makeQueryFromArray('city', $city_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            if(in_array('tag',wpjobportal::$_active_addons)){
                $tags_a = isset(wpjobportal::$_search['jobs']['tags']) ? wpjobportal::$_search['jobs']['tags'] : '';
            }
        }else{
            if(in_array('tag',wpjobportal::$_active_addons)){
                $tags_a = isset($searchajax['tags']) ? $searchajax['tags'] : '';
            }
        }
        if(in_array('tag',wpjobportal::$_active_addons)){
            if ($tags_a) {
                wpjobportal::$_data['filter']['tags'] = $tags_a;
                $res = $this->makeQueryFromArray('tags', $tags_a);
                if ($res)
                    $inquery .= " AND ( " . $res . " )";
            }
        }
        if($searchajax == null){
            $workpermit_a = WPJOBPORTALrequest::getVar('workpermit', 'post'); // workpermit countries
        }else{
            $workpermit_a = isset($searchajax['workpermit']) ? $searchajax['workpermit'] : '';
        }
        if ($workpermit_a) {
            wpjobportal::$_data['filter']['workpermit'] = $workpermit_a;
            $res = $this->makeQueryFromArray('workpermit', $workpermit_a);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if($searchajax == null){
            $requiredtravel = WPJOBPORTALrequest::getVar('requiredtravel', 'post');
        }else{
            $requiredtravel = isset($searchajax['requiredtravel']) ? $searchajax['requiredtravel'] : '';
        }
        if ($requiredtravel) {
            if (is_numeric($requiredtravel)) {
                wpjobportal::$_data['filter']['requiredtravel'] = $requiredtravel;
                $inquery .= " AND job.requiredtravel = " . esc_sql($requiredtravel);
            }
        }
        if($searchajax == null){
            $duration = WPJOBPORTALrequest::getVar('duration', 'post');
        }else{
            $duration = isset($searchajax['duration']) ? $searchajax['duration'] : '';
        }
        if ($duration) {
            wpjobportal::$_data['filter']['duration'] = $duration;
            $inquery .= " AND job.duration LIKE '%" . esc_sql($duration) . "%'";
        }
        if($searchajax == null){
            $zipcode = WPJOBPORTALrequest::getVar('zipcode', 'post');
        }else{
            $zipcode = isset($searchajax['zipcode']) ? $searchajax['zipcode'] : '';
        }
        if ($zipcode) {
            wpjobportal::$_data['filter']['zipcode'] = $zipcode;
            $inquery .= " AND job.zipcode LIKE '%" . esc_sql($zipcode) . "%'";
        }
        //Custom field search
        //start
        $data = wpjobportal::$_wpjpcustomfield->userFieldsData(2)/*apply_filters('wpjobportal_addons_get_custom_field',false,2)*/;
        $valarray = array();
        if (!empty($data)) {
            foreach ($data as $uf) {
                if($searchajax == null){
                    //$valarray[$uf->field] = WPJOBPORTALrequest::getVar($uf->field, 'post');
                    $valarray[$uf->field] = isset(wpjobportal::$_search['jobs'][$uf->field]) ? wpjobportal::$_search['jobs'][$uf->field] : '';
                }else{
                    $valarray[$uf->field] = isset($searchajax[$uf->field]) ? $searchajax[$uf->field] : '';
                }
                if (isset($valarray[$uf->field]) && $valarray[$uf->field] != null) {
                    switch ($uf->userfieldtype) {
                        case 'text':
                        case 'email':
                            $inquery .= ' AND job.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                            break;
                        case 'combo':
                            $inquery .= ' AND job.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'depandant_field':
                            $inquery .= ' AND job.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'radio':
                            $inquery .= ' AND job.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'checkbox':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                $finalvalue .= $value.'.*';
                            }
                            $inquery .= ' AND job.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars($finalvalue) . '.*"\' ';
                            break;
                        case 'date':
                            if (isset($valarray[$uf->field]) && $valarray[$uf->field] != '') {
                                $valarray[$uf->field] = gmdate('Y-m-d H:i:s',strtotime($valarray[$uf->field]));
                            }
                            $inquery .= ' AND job.params LIKE \'%"' . esc_sql($uf->field) . '":"' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '"%\' ';
                            break;
                        case 'textarea':
                            $inquery .= ' AND job.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*' . wpjobportalphplib::wpJP_htmlspecialchars($valarray[$uf->field]) . '.*"\' ';
                            break;
                        case 'multiple':
                            $finalvalue = '';
                            foreach($valarray[$uf->field] AS $value){
                                $finalvalue .= $value.'.*';
                            }
                            $inquery .= ' AND job.params REGEXP \'"' . esc_sql($uf->field) . '":"[^"]*'.wpjobportalphplib::wpJP_htmlspecialchars($finalvalue).'"\'';
                            break;

                    }
                    wpjobportal::$_data['filter']['params'] = $valarray;
                }
            }
        }

        //end
        if($searchajax == null){
            $longitude = WPJOBPORTALrequest::getVar('longitude', 'post');
            $latitude = WPJOBPORTALrequest::getVar('latitude', 'post');
            $radius = WPJOBPORTALrequest::getVar('radius', 'post');
            $radius_length_type = WPJOBPORTALrequest::getVar('radiuslengthtype', 'post');
        }else{
            $longitude = isset($searchajax['longitude']) ? $searchajax['longitude'] : '';
            $latitude = isset($searchajax['latitude']) ? $searchajax['latitude'] : '';
            $radius = isset($searchajax['radius']) ? $searchajax['radius'] : '';
            $radius_length_type = isset($searchajax['radiuslengthtype']) ? $searchajax['radiuslengthtype'] : '';
        }
        // php 8 issue for wpjobportalphplib::wpJP_str_replace
        if($longitude !=''){
            $longitude = wpjobportalphplib::wpJP_str_replace(',', '', $longitude);
        }
        if($latitude !=''){
            $latitude = wpjobportalphplib::wpJP_str_replace(',', '', $latitude);
        }
        //for radius search
        switch ($radius_length_type) {
            case "1":$radiuslength = 6378137;
                break;
            case "2":$radiuslength = 6378.137;
                break;
            case "3":$radiuslength = 3963.191;
                break;
            case "4":$radiuslength = 3441.596;
                break;
        }
        if ($longitude != '' && $latitude != '' && $radius != '' && $radiuslength != '') {
            wpjobportal::$_data['filter']['longitude'] = $longitude;
            wpjobportal::$_data['filter']['latitude'] = $latitude;
            wpjobportal::$_data['filter']['radius'] = $radius;
            wpjobportal::$_data['filter']['radiuslengthtype'] = $radius_length_type;
            $inquery .= " AND acos((SIN( PI()* ".esc_sql($latitude)." /180 )*SIN( PI()*job.latitude/180 ))+(cos(PI()* ".esc_sql($latitude)." /180)*COS( PI()*job.latitude/180) *COS(PI()*job.longitude/180-PI()* ".esc_sql($longitude)." /180)))* ".esc_sql($radiuslength)." <= ".esc_sql($radius);
        }
        return $inquery;
    }

    function getJobs($vars,$id='',$only_featured = 0){
        $this->getOrdering();
        $inquery = '';

        if (isset($vars['search']) && $vars['search'] != null) {
            $array = wpjobportalphplib::wpJP_explode('-', $vars['search']);
            $search = $array[count($array) - 1];
            $inquery = $this->getSaveSearchForView($search);
            wpjobportal::$_data['filter']['search'] = $search;
        } elseif (empty($vars)) {
            $inquery = $this->getRefinedJobs();
        } elseif(isset($vars['searchajax'])){
            $inquery = $this->getRefinedJobs($vars);
        } else {
            if (isset($vars['company']) && is_numeric($vars['company'])) { // if action form a <link> defined in cp
                wpjobportal::$_data['filter']['company'] = $vars['company'];
                $inquery = " AND job.companyid=" . esc_sql($vars['company']);
            }
            if (isset($vars['category']) && is_numeric($vars['category'])) { // if action form a <link> defined in cp
                wpjobportal::$_data['filter']['category'] = $vars['category'];
                $wpjp_query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` WHERE parentid = ". esc_sql($vars['category']);
                $wpjp_cats = wpjobportaldb::get_results($wpjp_query);
                $wpjp_ids = [];
                foreach ($wpjp_cats as $wpjp_cat) {
                    $wpjp_ids[] = $wpjp_cat->id;
                }
                $wpjp_ids[] = $vars['category'];
                $wpjp_ids = implode(",",$wpjp_ids);
                $inquery = " AND job.jobcategory IN(".esc_sql($wpjp_ids).")";
            }
            if (isset($vars['jobtype']) && is_numeric($vars['jobtype'])) { // if action form a <link> defined in cp
                wpjobportal::$_data['filter']['jobtype'] = $vars['jobtype'];
                $inquery = " AND job.jobtype=" . esc_sql($vars['jobtype']);
            }
            if (isset($vars['tags']) && (!is_numeric($vars['tags']))) { // if action form a <link> defined in cp
                wpjobportal::$_data['filter']['tags'] = wpjobportal::$_common->makeFilterdOrEditedTagsToReturn($vars['tags']);
                wpjobportal::$_data['filter']['fromtaglink'] = $vars['tags'];
                $inquery = " AND job.tags LIKE '%" . esc_sql($vars['tags']) . "%'";
            }

            if (isset($vars['city']) && is_numeric($vars['city'])) { // if action form a <link> defined in cp
                wpjobportal::$_data['filter']['city'] = wpjobportal::$_common->getCitiesForFilter($vars['city']);
                $res = $this->makeQueryFromArray('city', $vars['city']);
                if ($res){
                    $inquery = " AND ( " . $res . " )";
                }
            }

        }
        $city = WPJOBPORTALrequest::getVar('city','GET');
        if($city && is_numeric($city)){
            //$inquery .= " AND city.id = ".esc_sql($city);
            wpjobportal::$_data['filter']['city'] = wpjobportal::$_common->getCitiesForFilter($city);
            $res = $this->makeQueryFromArray('city', $city);
            if ($res){
                $inquery = " AND ( " . $res . " )";
            }
        }

        $state = WPJOBPORTALrequest::getVar('state','GET');
        if($state && is_numeric($state)){
            $inquery .= " AND state.id = ".esc_sql($state);
        }

        $country = WPJOBPORTALrequest::getVar('country','GET');
        if($country && is_numeric($country)){
            $inquery .= " AND country.id = ".esc_sql($country);
        }
        //local vars
        $simplejobs = array();

        // featuerd job short code parameter
        $featured_flag = WPJOBPORTALrequest::getVar('show_only_featured_jobs','',0);
        if($featured_flag == 1){
            $inquery .= ' AND job.isfeaturedjob = 1 AND DATE(job.endfeatureddate) >= CURDATE() ';
        }// featuerd job short code parameter


        if($only_featured == 1){
            $inquery .= ' AND job.isfeaturedjob = 1 AND DATE(job.endfeatureddate) >= CURDATE() ';
        }


        $dont_prep_data = 0;
        // AI Job search

        $aijobsearcch = isset(wpjobportal::$_search['jobs']['aijobsearcch']) ? wpjobportal::$_search['jobs']['aijobsearcch'] : '';
        if ($aijobsearcch != '') {
            do_action('wpjobportal_addons_aijobsearch_query');
            if( !empty(wpjobportal::$_data['ai_job_data_set']) ){
                $dont_prep_data = 1;
            }
        }

        //echo '<pre>';print_r($vars);echo '</pre>';

        // AI Suggested Jobs
        if (isset($vars['aisuggestedjobs_resume']) && is_numeric($vars['aisuggestedjobs_resume'])) { // if action form a <link> defined in cp
            do_action('wpjobportal_addons_aisuggestesjobs_jobs',$vars['aisuggestedjobs_resume']);
            if( !empty(wpjobportal::$_data['ai_job_data_set']) ){
                $dont_prep_data = 1;
            }
        }


        //shortcode attribute proceesing (filter,ordering,no of jobs)
        // by detafult set these values to 0 to make sure that these sections are visble
        wpjobportal::$_data['shortcode_option_hide_filter'] = 0;
        wpjobportal::$_data['shortcode_option_hide_filter_job_title'] = 0;
        wpjobportal::$_data['shortcode_option_hide_filter_job_location'] = 0;
        wpjobportal::$_data['shortcode_option_hide_company_logo'] = 0;
        wpjobportal::$_data['shortcode_option_hide_company_name'] = 0;

        $attributes_query = $this->processShortcodeAttributes();
        if($attributes_query != ''){
            $inquery .= $attributes_query;
        }


        $noofjobs = '';
        if(!empty(wpjobportal::$_data['shortcode_option_no_of_jobs'])){
            $noofjobs = wpjobportal::$_data['shortcode_option_no_of_jobs'];
        }

        if($dont_prep_data == 0){
            $curdate = gmdate('Y-m-d');
            //Pagination
            if($noofjobs == ''){ // if no of jobs not set in shortcode then do the pagiatnion total
                $query = "SELECT COUNT(DISTINCT job.id)
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                LEFT JOIN `".wpjobportal::$_db->prefix."wj_portal_jobcities` AS jobcity ON jobcity.jobid = job.id
                LEFT JOIN `".wpjobportal::$_db->prefix."wj_portal_cities` AS city ON city.id = jobcity.cityid
                LEFT JOIN `".wpjobportal::$_db->prefix."wj_portal_states` AS state ON state.countryid = city.countryid
                LEFT JOIN `".wpjobportal::$_db->prefix."wj_portal_countries` AS country ON country.id = city.countryid
                LEFT JOIN `".wpjobportal::$_db->prefix."wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                WHERE job.status = 1 AND DATE(job.startpublishing) <= '".$curdate."' AND DATE(job.stoppublishing) >= '".$curdate."'";
                $query .= $inquery;
                $total = wpjobportaldb::get_var($query);
                wpjobportal::$_data['total'] = $total;
                wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total,'jobs');
            }
            //Data/Data
            $query = "SELECT DISTINCT job.id AS jobid,job.id AS id,job.tags AS jobtags,job.title,job.created,job.city,job.endfeatureddate,job.isfeaturedjob,job.status,job.startpublishing,job.stoppublishing,job.currency,
            CONCAT(job.alias,'-',job.id) AS jobaliasid,job.noofjobs,
            cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, jobtype.title AS jobtypetitle,
            job.params,CONCAT(company.alias,'-',company.id) AS companyaliasid,LOWER(jobtype.title) AS jobtypetit,
            job.salarymax,job.salarymin,job.salarytype,srtype.title AS srangetypetitle,jobtype.color AS jobtypecolor, job.jobapplylink, job.joblink, job.description
            FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
            ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS srtype ON srtype.id = job.salaryduration
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity ON jobcity.jobid = job.id
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = jobcity.cityid
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.countryid = city.countryid
            LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
            WHERE job.status = 1 AND DATE(job.startpublishing) <= '".$curdate."' AND DATE(job.stoppublishing) >= '".$curdate."'";
            $query .= $inquery;

            // ordering
            $query .= " ORDER BY ".wpjobportal::$_ordering;

            // limit (no of jobs)
            if($noofjobs !='' && is_numeric($noofjobs)){
                $query .= " LIMIT " . esc_sql($noofjobs);
            }else{
                $query .= " LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
            }

            $results = wpjobportaldb::get_results($query);

            $data = array();
            foreach ($results AS $d) {
                $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
                $data[] = $d;
            }
            wpjobportal::$_data[0] = $data;
        }// dont prep data
        if(wpjobportal::$theme_chk == 1){
            wpjobportal::$_data[2] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforSearch(2);
        }
        wpjobportal::$_data['fields'] = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(2);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('job');
        return;
    }

    function getIpAddress() {
        //if client use the direct ip
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }



    function canAddFeaturedJob($uid) {
        if (!is_numeric($uid))
            return false;
        $credits = WPJOBPORTALincluder::getJSModel('credits')->getMinimumCreditIDByAction('featured_job');
        $availablecredits = WPJOBPORTALincluder::getObjectClass('user')->getAvailableCredits();
        if ($credits <= $availablecredits) {
            return true;
        } else {
            return false;
        }
    }

   function canAddJob($uid,$actionname) {
       if (!is_numeric($uid))
            return false;
       if(in_array('credits', wpjobportal::$_active_addons)){
            $credits = apply_filters('wpjobportal_addons_userpackages_module_wise',false,$uid,$actionname);//
        return $credits;
       }else{
        return true;
        }
    }

    function getQuickViewByJobId() {
        $nonce = WPJOBPORTALrequest::getVar('js_nonce');
        if (! wp_verify_nonce( $nonce, 'wp-job-portal-nonce') ) {
            die( 'Security check Failed' );
        }
        $jobid = WPJOBPORTALrequest::getVar('jobid');
        if ($jobid != null && is_numeric($jobid)) {
            $query = "SELECT job.title AS jobtitle, company.name AS companyname,job.isfeaturedjob , jobtype.title AS jobtypetitle
                        , salaryrangetype.title AS salaryrangetype,company.id AS companyid, job.currency, category.cat_title AS category, job.startpublishing, jobstatus.title AS jobstatustitle, job.degreetitle, job.city, job.longitude,job.latitude, job.description, job.duration,job.jobapplylink,job.joblink
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                        ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = job.jobcategory
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` AS jobstatus ON jobstatus.id = job.jobstatus
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryrangetype
                        WHERE job.id = " . esc_sql($jobid);
            $job = wpjobportal::$_db->get_row($query);
            $title = esc_html(__('Job Information', 'wp-job-portal'));
            $content = '<div class="quickviewupper">';
            $content .= '<span class="quickviewtitle">' . $job->jobtitle . '</span>';
            $comp_name = wpjobportal::$_config->getConfigurationByConfigName('comp_name');
            if($comp_name == 1){
                $content .= '<span class="quickviewcompanytitle"><a href="'.wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyid,'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('wpjobportalpageid'))) . '">' . $job->companyname;
                $content .= '</a>';
            }

            if ($job->isfeaturedjob == 1) {
                $content .= '<span class="quickviewfeatured">' . esc_html(__('Featured', 'wp-job-portal')) . '</span>';
            }
            $content .= '</span>';
            $content .= '<span class="quickviewhalfwidth-right">' . WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($job->city) . '</span>';
            $hourago = esc_html(__('Posted', 'wp-job-portal')) . ": ";
            $startTimeStamp = strtotime($job->startpublishing);
            $endTimeStamp = strtotime("now");
            $timeDiff = abs($endTimeStamp - $startTimeStamp);
            $numberDays = $timeDiff / 86400;  // 86400 seconds in one day
            // and you might want to convert to integer
            $numberDays = intval($numberDays);
            if ($numberDays != 0 && $numberDays == 1) {
                $day_text = esc_html(__('Day', 'wp-job-portal'));
            } elseif ($numberDays > 1) {
                $day_text = esc_html(__('Days', 'wp-job-portal'));
            } elseif ($numberDays == 0) {
                $day_text = esc_html(__('Today', 'wp-job-portal'));
            }
            if ($numberDays == 0) {
                $hourago .= $day_text;
            } else {
                $hourago .= $numberDays . ' ' . $day_text . ' ' . esc_html(__('Ago', 'wp-job-portal'));
            }
            $fieldordering = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforView(2);
            $content .= '<span class="quickviewhalfwidth">' . $hourago . '</span>';
            $content .= '</div>';
            $content .= '<div class="quickviewlower">';
            $content .= '<span class="quickviewtitle">' . esc_html(__('Overview', 'wp-job-portal')) . '</span>';
            if (isset($fieldordering['jobtype'])) {
                $content .= '<div class="quickviewrow">';
                $content .= '<span class="title">' . wpjobportal::wpjobportal_getVariableValue($fieldordering['jobtype']) . ':</span>';
                $content .= wpjobportal::wpjobportal_getVariableValue($job->jobtypetitle);
                $content .= '</div>';
            }
            if (isset($fieldordering['duration'])) {
                $content .= '<div class="quickviewrow">';
                $content .= '<span class="title">' . wpjobportal::wpjobportal_getVariableValue($fieldordering['duration']) . ':</span>';
                $content .= $job->duration;
                $content .= '</div>';
            }
            if (isset($fieldordering['jobsalaryrange'])) {
                $content .= '<div class="quickviewrow">';
                $content .= '<span class="title">' . wpjobportal::wpjobportal_getVariableValue($fieldordering['jobsalaryrange']) . ':</span>';
                $content .= wpjobportal::$_common->getSalaryRangeView($job->currencysymbol, $job->salaryrangestart, $job->salaryrangeend, $job->salaryrangetype);
                $content .= '</div>';
            }
            if (isset($fieldordering['jobcategory'])) {
                $content .= '<div class="quickviewrow">';
                $content .= '<span class="title">' . wpjobportal::wpjobportal_getVariableValue($fieldordering['jobcategory']) . ':</span>';
                $content .= wpjobportal::wpjobportal_getVariableValue($job->category);
                $content .= '</div>';
            }
            if (isset($fieldordering['startpublishing'])) {
                $content .= '<div class="quickviewrow">';
                $content .= '<span class="title">' . esc_html(__('Posted', 'wp-job-portal')) . ':</span>';
                $content .= date_i18n(wpjobportal::$_configuration['date_format'], strtotime($job->startpublishing));
                $content .= '</div>';
            }
            $content .= '<span class="quickviewtitle">' . esc_html(__('Location', 'wp-job-portal')) . '</span>';
            if (isset($fieldordering['city'])) {
                $content .= '<div class="quickviewrow">';
                $content .= WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($job->city);
                $content .= '</div>';
            }
            if (isset($fieldordering['map'])) {
                $content .= '<div class="quickviewrow-without-border">';
                $content .= '<div id="map"><div id="map_container1"></div></div>';
                $content .= '<input type="hidden" name="longitude1" id="longitude1" value="' . $job->longitude . '"/>';
                $content .= '<input type="hidden" name="latitude1" id="latitude1" value="' . $job->latitude . '"/>';
                $content .= '</div>';
            }
            $content .= '<span class="quickviewtitle">' . esc_html(__('Description', 'wp-job-portal')) . '</span>';
            if (isset($fieldordering['description'])) {
                $content .= '<div class="quickviewrow-without-border1">';
                $content .= $job->description;
                $content .= '</div>';
            }
            $content .= '<div class="quickviewbutton">';
                $show_apply_form  = wpjobportal::$_config->getConfigurationByConfigName('quick_apply_for_user');
                if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                    $show_apply_form  = wpjobportal::$_config->getConfigurationByConfigName('quick_apply_for_visitor');
                }
                if($show_apply_form == 0){ // hide apply now button if quick apply is enabled
                    $config_array = wpjobportal::$_data[0]['config'] = wpjobportal::$_config->getConfigByFor('jobapply');
                    if($config_array['showapplybutton'] == 1){
                        if($job->jobapplylink == 1 && !empty($job->joblink)){
                            if(!wpjobportalphplib::wpJP_strstr('http',$job->joblink)){
                                $joblink = 'http://'.$job->joblink;
                            }else{
                                $joblink = $job->joblink;
                            }
                            $content .='    <a class="quickviewbutton" id="apply-now-btn" href= "'. $joblink.'" target="_blank" >'. esc_html(__('Apply Now','wp-job-portal')).'</a>';
                        }elseif(!empty($config_array['applybuttonredirecturl'])){
                            if(!wpjobportalphplib::wpJP_strstr('http',$config_array['applybuttonredirecturl'])){
                                $joblink = 'http://'.$config_array['applybuttonredirecturl'];
                            }else{
                                $joblink = $config_array['applybuttonredirecturl'];
                            }
                            $content .='    <a class="quickviewbutton" id="apply-now-btn" href='.$joblink.'" target="_blank" >'. esc_html(__('Apply Now','wp-job-portal')).'</a>';
                        }else{
                            $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();
                            if($isguest){
                                $visitorcanapply = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_apply_to_job');
                                if($visitorcanapply == 1){
                                    $visitor_show_login_message = wpjobportal::$_config->getConfigurationByConfigName('visitor_show_login_message');
                                    if($visitor_show_login_message == 1){
                                        $content .='<a class="quickviewbutton" id="apply-now-btn" href="#" onclick="getApplyNowByJobid('.$jobid.','.esc_js(wpjobportal::wpjobportal_getPageid()).');">'.esc_html(__('Apply Now','wp-job-portal')).'</a>';
                                    }else{
                                        $vis_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply', 'action'=>'wpjobportaltask', 'task'=>'jobapplyasvisitor', 'wpjobportalid-jobid'=>$jobid, 'wpjobportalpageid'=>esc_js(wpjobportal::wpjobportal_getPageid())));
                                        $content .='<a class="quickviewbutton" id="apply-now-btn" href="'.$vis_link.'">'.esc_html(__('Apply Now','wp-job-portal')).'</a>';
                                    }
                                }
                            }else{
                                if(WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()){
                                    $content .='<a class="quickviewbutton" id="apply-now-btn" href="#" onclick="wpjobportalPopup(\'job_apply\','.esc_js(wpjobportal::wpjobportal_getPageid()).','.$jobid.');">'.esc_html(__('Apply Now','wp-job-portal')).'</a>';
                                }else{
                                    $content .='<a class="quickviewbutton" id="apply-now-btn" href="#" onclick="getApplyNowByJobid('.$jobid.','.esc_js(wpjobportal::wpjobportal_getPageid()).');">'.esc_html(__('Apply Now','wp-job-portal')).'</a>';
                                }
                            }
                        }
                    }
                }
                $content .= '<a href="'.wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$jobid,'wpjobportalpageid'=>esc_js(wpjobportal::wpjobportal_getPageid()))). '" class="quickviewbutton">' . esc_html(__('Full Detail', 'wp-job-portal')) . '</a>
                            <a href="#" class="quickviewbutton" onclick="closePopup();">' . esc_html(__('Close', 'wp-job-portal')) . '</a>
                        </div>';
            $content .= '</div>';
        } else {
            $title = esc_html(__('No record found', 'wp-job-portal'));
            $content = '<h1>' . esc_html(__('No record found', 'wp-job-portal')) . '</h1>';
        }
        $array = array('title' => $title, 'content' => $content);
        return wp_json_encode($array);
    }
    function getDataForShortlistForTemplate() {
        $jobid = WPJOBPORTALrequest::getVar('jobid');
        $result = $this->getDataForShortlist($jobid);
        if($result == false){
            return false;
        }else{
            return wp_json_encode($result);
        }
    }
    function getShortListViewByJobIdJobManager(){
        $jobid = WPJOBPORTALrequest::getVar('jobid');
        if ($jobid != null && is_numeric($jobid)) {
            $result = $this->getDataForShortlist($jobid);
            $comment = (isset($result->comments)) ? $result->comments : '';
            $content='<div class="modal-content '.esc_attr($this->class_prefix).'-modal-wrp">
                <div class="'.esc_attr($this->class_prefix).'-modal-header">
                    <a title="close" class="close '.esc_attr($this->class_prefix).'-modal-close-icon-wrap" href="#" onclick="wpjobportalClosePopup(1);" >
                        <img id="popup_cross" alt="popup cross" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/popup-close.png">
                    </a>
                    <h3 class="'.esc_attr($this->class_prefix).'-modal-title">'.esc_html(__('Add To ShortList','wp-job-portal')).'</h3>
                </div>
                <div class="col-md-11 col-md-offset-1 '.esc_attr($this->class_prefix).'-modal-data-wrp">
                    <div class="'.esc_attr($this->class_prefix).'-modal-left-image-wrp">
                        <i class="fa fa-heart '.esc_attr($this->class_prefix).'-modal-left-image" aria-hidden="true"></i>
                    </div>
                    <div class="modal-body '.esc_attr($this->class_prefix).'-modal-body">
                        <div class="form '.esc_attr($this->class_prefix).'-modal-form-wrp">
                            <div class="col-md-12 '.esc_attr($this->class_prefix).'-modal-form-row">
                                <div class="form-group">
                                    '.WPJOBPORTALformfield::textarea('wpjobportalcomment', $comment, array('class' => 'form-control '.esc_attr($this->class_prefix).'-modal-textarea', 'placeholder' => esc_html(__('Comments', 'wp-job-portal')))).'
                                </div>
                            </div>
                            <div class="'.esc_attr($this->class_prefix).'-modal-shortlist-star-wrp">';
                                $content .= '<label class="rate" for="wpjobportalrating">' . esc_html(__('Rate', 'wp-job-portal')) . '</label>';
                                    $percent = 0;
                                    if ($result)
                                        $percent = $result->rate * 20;
                                    else
                                        $percent = 0;
                                    $stars = '';
                                    $stars = '-small';
                                    $content .="
                                        <div class=\"wpjobportal-container" . esc_attr($stars) . "\">
                                            <span id='shortlist-stars'><ul class=\"wpjobportal-stars" . esc_attr($stars) . "\" >
                                                <li id=\"rating_" . esc_attr($jobid) . "\" class=\"current-rating\" style=\"width:" . (int) $percent . "%;\"></li>
                                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . esc_attr($jobid) . "',1);\" title=\"" . esc_html(__('Very Poor', 'wp-job-portal')) . "\" class=\"one-star\">1</a></li>
                                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . esc_attr($jobid) . "',2);\" title=\"" . esc_html(__('Poor', 'wp-job-portal')) . "\" class=\"two-stars\">2</a></li>
                                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . esc_attr($jobid) . "',3);\" title=\"" . esc_html(__('Regular', 'wp-job-portal')) . "\" class=\"three-stars\">3</a></li>
                                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . esc_attr($jobid) . "',4);\" title=\"" . esc_html(__('Good', 'wp-job-portal')) . "\" class=\"four-stars\">4</a></li>
                                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . esc_attr($jobid) . "',5);\" title=\"" . esc_html(__('Very Good', 'wp-job-portal')) . "\" class=\"five-stars\">5</a></li>
                                            </ul></span>
                                        </div>";
                                $content.='</div>
                            <div class="col-md-12 '.esc_attr($this->class_prefix).'-modal-form-btn">
                                <div class="form-group">
                                    <input type="button" class="btn btn-primary btn-lg btn-block '.esc_attr($this->class_prefix).'-modal-form-btn-inpf" value="'.esc_html(__("Add To ShortList",'wp-job-portal')).'" onclick="saveJobShortlist(1);"  />
                                </div>
                            </div>
                            '.WPJOBPORTALformfield::hidden('wpjobportalid', isset($result->id) ? $result->id : '').
                            WPJOBPORTALformfield::hidden('jobid', $jobid).
                            '
                        </div>
                    </div>
                </div>
            </div>';
        }else {
            $title = esc_html(__('No record found', 'wp-job-portal'));
            $content = '<h1>' . esc_html(__('No record found', 'wp-job-portal')) . '</h1>';
        }
        $array = array('title' => "", 'content' => $content);
        return wp_json_encode($array);
    }

    function getShortListViewByJobId() {
        $nonce = WPJOBPORTALrequest::getVar('js_nonce');
        if (! wp_verify_nonce( $nonce, 'wp-job-portal-nonce') ) {
            die( 'Security check Failed' );
        }
        $jobid = WPJOBPORTALrequest::getVar('jobid');

        if ($jobid != null && is_numeric($jobid)) {
            $result = $this->getDataForShortlist($jobid);
            $title = esc_html(__('Short List Job', 'wp-job-portal'));
            $content = '<div class="commentrow">';
            $content .= '<label for="wpjobportalcomment">' . esc_html(__('Comments', 'wp-job-portal')) . '</label>';
            $comment = (isset($result->comments)) ? $result->comments : '';
            $content .= '<textarea id="wpjobportalcomment" name="wpjobportalcomment">' . $comment . '</textarea>';
            $content .= '<label class="rate" for="wpjobportalrating">' . esc_html(__('Rate', 'wp-job-portal')) . '</label>';
            $percent = 0;
            if ($result)
                $percent = $result->rate * 20;
            else
                $percent = 0;
            $stars = '';
            $stars = '-small';
            $content .="
                        <div class=\"wpjobportal-container" . $stars . "\">
                            <span id='shortlist-stars'><ul class=\"wpjobportal-stars" . $stars . "\" >
                                <li id=\"rating_" . $jobid . "\" class=\"current-rating\" style=\"width:" . (int) $percent . "%;\"></li>
                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . $jobid . "',1);\" title=\"" . esc_html(__('Very Poor', 'wp-job-portal')) . "\" class=\"one-star\">1</a></li>
                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . $jobid . "',2);\" title=\"" . esc_html(__('Poor', 'wp-job-portal')) . "\" class=\"two-stars\">2</a></li>
                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . $jobid . "',3);\" title=\"" . esc_html(__('Regular', 'wp-job-portal')) . "\" class=\"three-stars\">3</a></li>
                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . $jobid . "',4);\" title=\"" . esc_html(__('Good', 'wp-job-portal')) . "\" class=\"four-stars\">4</a></li>
                                <li><a anchor=\"anchor\" href=\"javascript:void(null)\" onclick=\"javascript:setrating('rating_" . $jobid . "',5);\" title=\"" . esc_html(__('Very Good', 'wp-job-portal')) . "\" class=\"five-stars\">5</a></li>
                            </ul></span>
                        </div>
                        ";

            $id = (isset($result->id)) ? $result->id : "";
            $content .= '<input type="hidden" name="wpjobportalid" id="wpjobportalid" value="' . $id . '">';
            $content .= '<input type="hidden" name="jobid" id="jobid" value="' . $jobid . '">';
            $content .= '<div class="quickviewlower">
                            <div class="quickviewbutton">
                                <a href="#" class="quickviewbutton" id="apply-now-btn" onclick="saveJobShortlist()">' . esc_html(__('Save', 'wp-job-portal')) . '</a>
                                <a href="#" class="quickviewbutton" onclick="closePopup();">' . esc_html(__('Close', 'wp-job-portal')) . '</a>
                            </div>
                        </div>';
            $content .= '</div>';
        }else {
            $title = esc_html(__('No record found', 'wp-job-portal'));
            $content = '<h1>' . esc_html(__('No record found', 'wp-job-portal')) . '</h1>';
        }
        $array = array('title' => $title, 'content' => $content);
        return wp_json_encode($array);
    }


   function custom_wpjobportal_cookie($cookievalue, $cookieindex) {
        $value = array();
        if (isset($_COOKIE['wp_wpjobportal_cookie'])) {
            $cookie = sanitize_key($_COOKIE['wp_wpjobportal_cookie']);
            $value = unserialize($cookie);
        }
        $value[(int) $cookieindex] = (int) $cookievalue;
        wpjobportalphplib::wpJP_setcookie('wp_wpjobportal_cookie', serialize($value), time() + 1209600, SITECOOKIEPATH, null, false, true);
    }

    function getNextJobs() {
        $searchcriteria = WPJOBPORTALrequest::getVar('ajaxsearch');
        wpjobportal::$_data['wpjobportal_pageid'] = WPJOBPORTALrequest::getVar('wpjobportal_pageid');
        $decoded = wpjobportalphplib::wpJP_safe_decoding($searchcriteria);
        $array = json_decode($decoded,true);
        //$vars = $this->getjobsvar();
        $array['searchajax'] = 1;
        $this->getJobs($array);
        $jobs = WPJOBPORTALincluder::getObjectClass('jobslist');
        $jobshtml = $jobs->printjobs(wpjobportal::$_data[0]);
        echo wp_kses($jobshtml, WPJOBPORTAL_ALLOWED_TAGS);
        exit;
    }

    function getNextTemplateJobs(){
        $searchcriteria = WPJOBPORTALrequest::getVar('ajaxsearch');
        wpjobportal::$_data['wpjobportal_pageid'] = WPJOBPORTALrequest::getVar('wpjobportal_pageid');

        $decoded = wpjobportalphplib::wpJP_safe_decoding($searchcriteria);
        $array = json_decode($decoded,true);
        //$vars = $this->getjobsvar();
        $array['searchajax'] = 1;
        $this->getJobs($array);
        $jobs = WPJOBPORTALincluder::getObjectClass('jobslist');
        $jobshtml = $jobs->printtemplatejobs(wpjobportal::$_data[0]);
        echo wp_kses($jobshtml, WPJOBPORTAL_ALLOWED_TAGS);
        exit;
    }

    function getjobsvar() {
        $vars = array();
        $id = WPJOBPORTALrequest::getVar('wpjobportalid');
        if ($id) {
            //parse id what is the meaning of it
            $array = wpjobportalphplib::wpJP_explode('_', $id);

            // this code handles the case where alias for the link enitity (company,category,city etc) contanins  "_"
            if(is_array($array)){
                $array_count = count($array);
                if($array_count > 2){ // count > 2 means there were other underscores in the urls id section
                    $array[1] = $array[($array_count - 1)];
                }
            }else{// there was a log error for array[0] in the code below
                return $vars;
            }

            if ($array[0] == 'tags') {
                unset($array[0]);
                $array = implode(' ', $array);
                $vars['tags'] = $array;
            } else {
                if(isset($array[1])){
                    $id = $array[1];
                    // $clue = $id{0} . $id{1}; Deprecated syntax

                    $clue = '';
                    if(isset($id[0])){
                        $clue = $id[0];
                    }
                    if(isset($id[1])){
                        $clue .= $id[1];
                    }

                    $id = wpjobportalphplib::wpJP_substr($id, 2);
                    switch ($clue) {
                        case '10':
                            $vars['category'] = $id;
                            break;
                        case '11':
                            $vars['jobtype'] = $id;
                            break;
                        case '12':
                            $vars['company'] = $id;
                            break;
                        case '13':
                            $vars['search'] = $id;
                            break;
                        case '14':
                            $vars['city'] = $id;
                            break;
                        case '15':
                            $vars['aisuggestedjobs_resume'] = $id;
                            break;
                    }
                }
            }
        } else {
            $id = WPJOBPORTALrequest::getVar('category', 'get');
            if ($id) {
                $vars['category'] = $this->parseid($id);
            }
            $id = WPJOBPORTALrequest::getVar('bycompany', 'get');
            if($id){
                $vars['bycompany'] = $this->parseid($id);
            }
            $id = WPJOBPORTALrequest::getVar('jobtype', 'get');
            if ($id) {
                $vars['jobtype'] = $this->parseid($id);
            }
            $id = WPJOBPORTALrequest::getVar('company', 'get');
            if ($id) {
                $vars['company'] = $this->parseid($id);
            }
            $id = WPJOBPORTALrequest::getVar('search', 'get');
            if ($id) {
                $vars['search'] = $this->parseid($id);
            }
            $id = WPJOBPORTALrequest::getVar('city', 'get');
            if ($id) {
                $vars['city'] = $this->parseid($id);
            }
            $id = WPJOBPORTALrequest::getVar('aisuggestedjobs_resume', 'get');
            if ($id) {
                $vars['aisuggestedjobs_resume'] = $this->parseid($id);
            }

            if(in_array('tag',wpjobportal::$_active_addons)){
                $id = WPJOBPORTALrequest::getVar('tags', 'get');
                if ($id) {
                    $id = wpjobportal::tagfillout($id);
                    $vars['tags'] = $id;
                }
            }
        }
        return $vars;
    }

    function parseid($value) {
        $arr = wpjobportalphplib::wpJP_explode('-', $value);
        $id = $arr[count($arr) - 1];
        return $id;
    }

    function getOrdering() {
        $sort = WPJOBPORTALrequest::getVar('sortby', '', 'posteddesc');
        wpjobportal::$_data['sortby'] = $sort;// to manager sorting on ajax loaded jobs.
        $this->getListOrdering($sort);
        $this->getListSorting($sort);
    }

    function getListOrdering($sort) {
        switch ($sort) {
            case "titledesc":
                wpjobportal::$_ordering = "job.title DESC";
                wpjobportal::$_sorton = "title";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "titleasc":
                wpjobportal::$_ordering = "job.title ASC";
                wpjobportal::$_sorton = "title";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "categorydesc":
                wpjobportal::$_ordering = "cat.cat_title DESC";
                wpjobportal::$_sorton = "category";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "categoryasc":
                wpjobportal::$_ordering = "cat.cat_title ASC";
                wpjobportal::$_sorton = "category";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "jobtypedesc":
                wpjobportal::$_ordering = "jobtype.title DESC";
                wpjobportal::$_sorton = "jobtype";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "jobtypeasc":
                wpjobportal::$_ordering = "jobtype.title ASC";
                wpjobportal::$_sorton = "jobtype";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "jobstatusdesc":
                wpjobportal::$_ordering = "job.status DESC";
                wpjobportal::$_sorton = "jobstatus";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "jobstatusasc":
                wpjobportal::$_ordering = "job.status ASC";
                wpjobportal::$_sorton = "jobstatus";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "companydesc":
                wpjobportal::$_ordering = "company.name DESC";
                wpjobportal::$_sorton = "company";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "companyasc":
                wpjobportal::$_ordering = "company.name ASC";
                wpjobportal::$_sorton = "company";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "salarydesc":
                wpjobportal::$_ordering = "srfrom.rangestart DESC";
                wpjobportal::$_sorton = "salary";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "salaryasc":
                wpjobportal::$_ordering = "srfrom.rangestart ASC";
                wpjobportal::$_sorton = "salary";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "posteddesc":
                wpjobportal::$_ordering = "job.created DESC";
                wpjobportal::$_sorton = "posted";
                wpjobportal::$_sortorder = "DESC";
                break;
            case "postedasc":
                wpjobportal::$_ordering = "job.created ASC";
                wpjobportal::$_sorton = "posted";
                wpjobportal::$_sortorder = "ASC";
                break;
            case "salary":
                wpjobportal::$_ordering = "job.salarymax DESC";
                wpjobportal::$_sorton = "salarymax";
                wpjobportal::$_sortorder = "DESC";
                wpjobportal::$_data['filter']['sortby'] = 'salary';
                break;
            case "newest":
                wpjobportal::$_ordering = "job.created DESC";
                wpjobportal::$_sorton = "created";
                wpjobportal::$_sortorder = "DESC";
                wpjobportal::$_data['filter']['sortby'] = 'newest';
                break;
            case 'newestasc':
                wpjobportal::$_ordering = "job.created ASC";
                wpjobportal::$_sorton = "newest";
                wpjobportal::$_sortorder = "ASC";
                wpjobportal::$_data['filter']['sortby'] = 'newest';
                break;
            case 'newestdesc':
                wpjobportal::$_ordering = "job.created DESC";
                wpjobportal::$_sorton = "newest";
                wpjobportal::$_sortorder = "DESC";
                wpjobportal::$_data['filter']['sortby'] = 'newest';
                break;
            default: wpjobportal::$_ordering = "job.title DESC";
        }
        return;
    }

    function getSortArg($type, $sort) {
        $mat = array();
        if (wpjobportalphplib::wpJP_preg_match("/(\w+)(asc|desc)/i", $sort, $mat)) {
            if ($type == $mat[1]) {
                return ( $mat[2] == "asc" ) ? "{$type}desc" : "{$type}asc";
            } else {
                return $type . $mat[2];
            }
        }
        return "iddesc";
    }

    function getListSorting($sort) {
        wpjobportal::$_sortlinks['title'] = $this->getSortArg("title", $sort);
        wpjobportal::$_sortlinks['category'] = $this->getSortArg("category", $sort);
        wpjobportal::$_sortlinks['jobtype'] = $this->getSortArg("jobtype", $sort);
        wpjobportal::$_sortlinks['jobstatus'] = $this->getSortArg("jobstatus", $sort);
        wpjobportal::$_sortlinks['company'] = $this->getSortArg("company", $sort);
        wpjobportal::$_sortlinks['salary'] = $this->getSortArg("salary", $sort);
        wpjobportal::$_sortlinks['posted'] = $this->getSortArg("posted", $sort);
        wpjobportal::$_sortlinks['newest'] = $this->getSortArg("newest", $sort);
        return;
    }

    function makeJobSeo($job_seo , $wpjobportalid){
        if(empty($job_seo))
            return '';

        $common = wpjobportal::$_common;
        $id = $common->parseID($wpjobportalid);
        if(! is_numeric($id)) return '';
        $result = '';
        $job_seo = wpjobportalphplib::wpJP_str_replace( ' ', '', $job_seo);
        $job_seo = wpjobportalphplib::wpJP_str_replace( '[', '', $job_seo);
        $array = wpjobportalphplib::wpJP_explode(']', $job_seo);

        $total = count($array);
        if($total > 5)
            $total = 5;

        for ($i=0; $i < $total; $i++) {
            $query = '';
            switch ($array[$i]) {
                case 'title':
                    $query = "SELECT title AS col FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE id = " . esc_sql($id);
                break;
                case 'category':
                    $query = "SELECT category.cat_title AS col
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = job.jobcategory
                        WHERE job.id = " . esc_sql($id);
                break;
                case 'company':
                    $query = "SELECT company.name AS col
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                        ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                        WHERE job.id = " . esc_sql($id);
                break;
                case 'jobtype':
                    $query = "SELECT jt.title AS col
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jt ON jt.id = job.jobtype
                        WHERE job.id = " . esc_sql($id);
                break;
                case 'location':
                    $query = "SELECT job.city AS col
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job WHERE job.id = " . esc_sql($id);
                break;
            }
            if($query != ''){
                $data = wpjobportaldb::get_row($query);
                if(isset($data->col)){
                    if($array[$i] == 'location'){
                        $cityids = wpjobportalphplib::wpJP_explode(',', $data->col);
                        $location = '';
                        for ($j=0; $j < count($cityids); $j++) {
                            if(is_numeric($cityids[$j])){
                                $query = "SELECT name FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` WHERE id = ". esc_sql($cityids[$j]);
                                $cityname = wpjobportaldb::get_row($query);
                                if(isset($cityname->name)){
                                    if($location == '')
                                        $location .= $cityname->name;
                                    else
                                        $location .= ' '.$cityname->name;
                                }
                            }
                        }
                        $location = $common->removeSpecialCharacter($location);
                        // if url encoded string is different from the orginal string dont add it to url
                        $val = $location;
                        $test_val = urlencode($val);
                        if($val != $test_val){
                            continue;
                        }
                        if($location != ''){
                            if($result == '')
                                $result .= wpjobportalphplib::wpJP_str_replace(' ', '-', $location);
                            else
                                $result .= '-'.wpjobportalphplib::wpJP_str_replace(' ', '-', $location);
                        }
                    }else{
                        $val = $common->removeSpecialCharacter($data->col);
                        // if url encoded string is different from the orginal string dont add it to url
                        $test_val = urlencode($val);
                        if($val != $test_val){
                            continue;
                        }

                        if($result == '')
                            $result .= wpjobportalphplib::wpJP_str_replace(' ', '-', $val);
                        else
                            $result .= '-'.wpjobportalphplib::wpJP_str_replace(' ', '-', $val);
                    }
                }
            }
            if($array[$i] == "sep"){
                $result .= " - ";
            }
        }
        if($result != ''){
            $result = wpjobportalphplib::wpJP_str_replace('_', '-', $result);
        }
        return $result;
    }

    function makeJobSeoDocumentTitle($job_seo , $wpjobportalid){
        if(empty($job_seo))
            return '';

        $common = wpjobportal::$_common;
        $id = $common->parseID($wpjobportalid);
        if(! is_numeric($id))
            return '';
        $result = '';

        $jobtitle = '';
        $companyname = '';
        $jobcategory = '';
        $jobtype = '';
        $joblocation = '';


        $query = "SELECT job.title AS jobtitle, company.name AS companyname, category.cat_title AS categorytitle,
                    jobtype.title AS jobtypetitle, job.city as jobcities
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                    ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = job.jobcategory
                    WHERE job.id = " . esc_sql($id);
        $data = wpjobportaldb::get_row($query);

        if(!empty($data)){
            $jobtitle = $data->jobtitle;
            $companyname = $data->companyname;
            $jobcategory = $data->categorytitle;
            $jobtype = $data->jobtypetitle;

            if($data->jobcities != ''){
                $cityids = wpjobportalphplib::wpJP_explode(',', $data->jobcities);
                for ($j=0; $j < count($cityids); $j++) {
                    if(is_numeric($cityids[$j])){
                        $query = "SELECT name FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` WHERE id = ". esc_sql($cityids[$j]);
                        $cityname = wpjobportaldb::get_row($query);
                        if(isset($cityname->name)){
                            if($joblocation == '')
                                $joblocation .= $cityname->name;
                            else
                                $joblocation .= ', '.$cityname->name;
                        }
                    }
                }
            }
            $matcharray = array(
                '[title]' => $jobtitle,
                '[companyname]' => $companyname,
                '[jobcategory]' => $jobcategory,
                '[jobtype]' => $jobtype,
                '[location]' => $joblocation,
                '[separator]' => '-',
                '[sitename]' => get_bloginfo( 'name', 'display' )
            );
            $result = $this->replaceMatches($job_seo,$matcharray);
        }
        return $result;
    }

    function replaceMatches($string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = wpjobportalphplib::wpJP_str_replace($find, $replace, $string);
        }
        return $string;
    }


    function getIfJobOwner($jobid) {
       if (!is_numeric($jobid))
            return false;
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        if(!is_numeric($uid)) return false;
        $query = "SELECT job.id
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        WHERE job.uid = ". esc_sql($uid)."
        AND job.id =" . esc_sql($jobid);
        $result = wpjobportal::$_db->get_var($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }
    function getJobUid($jobid){
        if (!is_numeric($jobid))
            return false;
        $query = "SELECT job.uid
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        WHERE job.id =" . esc_sql($jobid);
        $result = wpjobportal::$_db->get_var($query);
        return $result;
    }

    function getMessagekey(){
        $key = 'job';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }
    // fucntion for app


    function getSerachCriteriaForApp(){

        $vars = array();

        $vars['company'] = WPJOBPORTALrequest::getVar('wpjobportalapp_companyid','post');
        $vars['jobtype'] = WPJOBPORTALrequest::getVar('wpjobportalapp_jobtypeid','post');
        $vars['category'] = WPJOBPORTALrequest::getVar('wpjobportalapp_categoryid','post');
        $vars['city'] = WPJOBPORTALrequest::getVar('wpjobportalapp_cityid','post');
        $vars['carrier'] = WPJOBPORTALrequest::getVar('wpjobportalapp_carrierid','post');
        $vars['shift'] = WPJOBPORTALrequest::getVar('wpjobportalapp_shiftid','post');
        $vars['workpermit'] = WPJOBPORTALrequest::getVar('wpjobportalapp_workpermitid','post');
        $vars['jobstatus'] = WPJOBPORTALrequest::getVar('wpjobportalapp_jobstatus_id','post');
        $vars['education'] = WPJOBPORTALrequest::getVar('wpjobportalapp_educationid','post');
        $vars['jobtitle'] = WPJOBPORTALrequest::getVar('wpjobportalapp_jobtitle','post');
        $vars['duration'] = WPJOBPORTALrequest::getVar('wpjobportalapp_duration','post');
        $vars['metakeywords'] = WPJOBPORTALrequest::getVar('wpjobportalapp_metakeyword','post');
        $vars['salaryrangestart'] = WPJOBPORTALrequest::getVar('wpjobportalapp_startrangeid','post');
        $vars['salaryrangeend'] = WPJOBPORTALrequest::getVar('wpjobportalapp_endrangeid','post');
        $vars['salaryrangetype'] = WPJOBPORTALrequest::getVar('wpjobportalapp_salary_type_id','post');
        $vars['currency'] = WPJOBPORTALrequest::getVar('wpjobportalapp_currenyid','post');


        //$vars['experience'] = WPJOBPORTALrequest::getVar('wpjobportalapp_experienceid','post');
        //$vars['age'] = WPJOBPORTALrequest::getVar('wpjobportalapp_ageid','post');

        $vars['gender'] = WPJOBPORTALrequest::getVar('wpjobportalapp_genderid','post');
        //$vars['radiusid'] = WPJOBPORTALrequest::getVar('wpjobportalapp_radiusid','post');
        // lat lang missing


        $inquery  = '';
        if (isset($vars['city']) && is_numeric($vars['city']) && $vars['city'] != 0 ) {
            $return_data['filter']['city'] = $vars['city'];
            $inquery .= " AND  job.city  LIKE '%".esc_sql($vars['city'])."%'";
        }
        if (isset($vars['metakeywords'])) {
            wpjobportal::$_data['filter']['metakeywords'] = $vars['metakeywords'];
            $vars['metakeywords'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['metakeywords']),true);
            $res = $this->makeQueryFromArray('metakeywords', $vars['metakeywords']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($vars['jobtitle'])) {
            wpjobportal::$_data['filter']['jobtitle'] = $vars['jobtitle'];
            $inquery .= " AND job.title LIKE '%" . esc_sql($vars['jobtitle']) . "%'";
        }
        if (isset($vars['company'])) {
            wpjobportal::$_data['filter']['company'] = $vars['company'];
            $vars['company'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['company']),true);
            $res = $this->makeQueryFromArray('company', $vars['company']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }

        if (isset($vars['category'])) {
            wpjobportal::$_data['filter']['category'] = $vars['category'];
            $vars['category'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['category']),true);
            $res = $this->makeQueryFromArray('category', $vars['category']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }

        if (isset($vars['jobtype'])) {
            wpjobportal::$_data['filter']['jobtype'] = $vars['jobtype'];
            $vars['jobtype'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['jobtype']),true);
            $res = $this->makeQueryFromArray('jobtype', $vars['jobtype']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($vars['carrier'])) {
            wpjobportal::$_data['filter']['carrier'] = $vars['carrier'];
            $vars['carrier'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['carrier']),true);
            $res = $this->makeQueryFromArray('careerlevel', $vars['carrier']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($vars['gender'])) {
            if (is_numeric($vars['gender'])) {
                $inquery .= " AND job.gender = " . esc_sql($vars['gender']);
                wpjobportal::$_data['filter']['gender'] = $vars['gender'];
            }
        }
        if (isset($vars['jobstatus'])) {
            wpjobportal::$_data['filter']['jobstatus'] = $vars['jobstatus'];
            $vars['jobstatus'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['jobstatus']),true);
            $res = $this->makeQueryFromArray('jobstatus', $vars['jobstatus']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($vars['currency'])) {
            if (is_numeric($vars['currency'])) {
                wpjobportal::$_data['filter']['currency'] = $vars['currency'];
                $inquery .= " AND job.currencyid = " . esc_sql($vars['currency']);
            }
        }
        if (isset($vars['salaryrangestart'])) {
            if (is_numeric($vars['salaryrangestart'])) {
                wpjobportal::$_data['filter']['salaryrangestart'] = $vars['salaryrangestart'];
                $inquery .= " AND job.salaryrangefrom = " . esc_sql($vars['salaryrangestart']);
            }
        }
        if (isset($vars['salaryrangeend'])) {
            if (is_numeric($vars['salaryrangeend'])) {
                wpjobportal::$_data['filter']['salaryrangeend'] = $vars['salaryrangeend'];
                $inquery .= " AND job.salaryrangeto = " . esc_sql($vars['salaryrangeend']);
            }
        }
        if (isset($vars['salaryrangetype'])) {
            if (is_numeric($vars['salaryrangetype'])) {
                wpjobportal::$_data['filter']['srangetype'] = $vars['salaryrangetype'];
                $inquery .= " AND job.salaryrangetype = " . esc_sql($vars['salaryrangetype']);
            }
        }
        if (isset($vars['shift'])) {
            wpjobportal::$_data['filter']['shift'] = $vars['shift'];
            $vars['shift'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['shift']),true);
            $res = $this->makeQueryFromArray('shift', $vars['shift']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($vars['education'])) {
            wpjobportal::$_data['filter']['education'] = $vars['education'];
            $vars['education'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['education']),true);
            $res = $this->makeQueryFromArray('education', $vars['education']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($vars['city'])) {
            wpjobportal::$_data['filter']['city'] = $vars['city'];
            $vars['city'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['city']),true);
            $res = $this->makeQueryFromArray('city', $vars['city']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($vars['tags'])) {
            wpjobportal::$_data['filter']['tags'] = $var['tags'];
            $vars['tags'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['tags']),true);
            $res = $this->makeQueryFromArray('tags', $vars['tags']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";

        }
        if (isset($vars['workpermit'])) {
            wpjobportal::$_data['filter']['workpermit'] = $vars['workpermit'];
            $vars['workpermit'] = json_decode(wpjobportalphplib::wpJP_stripslashes($vars['workpermit']),true);
            $res = $this->makeQueryFromArray('workpermit', $vars['workpermit']);
            if ($res)
                $inquery .= " AND ( " . $res . " )";
        }
        if (isset($vars['requiredtravel'])) {
            if (is_numeric($vars['requiredtravel'])) {
                wpjobportal::$_data['filter']['requiredtravel'] = $vars['requiredtravel'];
                $inquery .= " AND job.requiredtravel = " . esc_sql($vars['requiredtravel']);
            }
        }
        if (isset($vars['duration'])) {
            wpjobportal::$_data['filter']['duration'] = $vars['duration'];
            $inquery .= " AND job.duration LIKE '%" . esc_sql($vars['duration']) . "%'";
        }
        if (isset($vars['wpjobportalapp_idfeatured']) && $vars['wpjobportalapp_idfeatured'] == 1) {
            wpjobportal::$_data['filter']['wpjobportalapp_idfeatured'] = $vars['wpjobportalapp_idfeatured'];
            $inquery .= " AND job.isfeaturedjob = " . esc_sql($vars['wpjobportalapp_idfeatured'])." AND job.endfeatureddate >= CURDATE() ";
        }
        return $inquery;
     }

    function getJobListingSortingForApp(){
        $sorton = WPJOBPORTALrequest::getVar('wpjobportalapp_sorton','post',2);
        $sortby = WPJOBPORTALrequest::getVar('wpjobportalapp_sortby','post',2);
        switch ($sorton) {
            case 1: // job title
                $sorting = ' job.title ';
                break;
            case 2: // created
                $sorting = ' job.created ';
                break;
            case 3: // company name
                $sorting = ' company.name ';
                break;
            case 4: // job type
                $sorting = ' jobtype.title ';
                break;
            case 5: // category
                $sorting = ' cat.cat_title ';
                break;
            // case 5: // location
            //     $sorting = ' city.name ';
            //     break;
            // case 7: // status
            //     $sorting = ' job.jobstatus ';
            //     break;
        }
        if ($sortby == 1) {
            $sorting .= ' ASC ';
        } else {
            $sorting .= ' DESC ';
        }
        return $sorting;
    }

    function jobDataStructuredPost($job_id){
        if(!is_numeric($job_id))
            return false;
        $query = "SELECT job.*,company.url AS companyurl,company.logofilename,company.city AS compcity,company.isfeaturedcompany,cat.cat_title , company.name as companyname, jobtype.title AS jobtypetitle
                , jobstatus.title AS jobstatustitle";
                if(in_array('departments', wpjobportal::$_active_addons)){
                    $query .= " , department.name AS departmentname";
                }
                $query .= " , salarytype.id AS salarytype,LOWER(jobtype.title) AS jobtypetit,careerlevel.title AS careerleveltitle,salarytype.title AS srangetypetitle,jobtype.color AS jobtypecolor,company.contactemail AS companyemail
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON job.companyid = company.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON job.jobcategory = cat.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON job.jobtype = jobtype.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` AS jobstatus ON job.jobstatus = jobstatus.id";
            if(in_array('departments', wpjobportal::$_active_addons)){
                $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_departments` AS department ON job.departmentid = department.id";
            }
        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salarytype ON job.salarytype = salarytype.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_heighesteducation` AS education ON job.educationid = education.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_careerlevels` AS careerlevel ON careerlevel.id = job.careerlevel
        WHERE  job.id = " . esc_sql($job_id);
        $job = wpjobportaldb::get_row($query);
        if(isset($job->id)){
            $job->multicity = WPJOBPORTALincluder::getJSModel('wpjobportal')->getMultiCityDataForView($job_id, 1);
            $job->salary = wpjobportal::$_common->getSalaryRangeView($job->salarytype, $job->salarymin, $job->salarymax, $job->srangetypetitle);
            $job->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($job->city);
        }
        return $job;
    }

    // get form submit data for frontend side
    function getFrontSideJobSearchFormData($search_userfields){
        $jsjp_search_array = array();
        // $search_userfields = WPJOBPORTALincluder::getObjectClass('customfields')->userFieldsForSearch(1);
        $jsjp_search_array['metakeywords'] = WPJOBPORTALrequest::getVar('metakeywords', 'post');
        $jsjp_search_array['jobtitle'] = WPJOBPORTALrequest::getVar('jobtitle', 'post');
        $jsjp_search_array['company'] = WPJOBPORTALrequest::getVar('company', 'post');
        $jsjp_search_array['category'] = WPJOBPORTALrequest::getVar('category', 'post');
        $jsjp_search_array['jobtype'] = WPJOBPORTALrequest::getVar('jobtype', 'post');
        $jsjp_search_array['careerlevel'] = WPJOBPORTALrequest::getVar('careerlevel', 'post');
        $jsjp_search_array['jobstatus'] = WPJOBPORTALrequest::getVar('jobstatus', 'post');
        $jsjp_search_array['currencyid'] = WPJOBPORTALrequest::getVar('currencyid', 'post');
        $jsjp_search_array['salarytype'] = WPJOBPORTALrequest::getVar('salarytype', 'post');
        $jsjp_search_array['city'] = WPJOBPORTALrequest::getVar('city', 'post');
        $jsjp_search_array['salarymin'] = WPJOBPORTALrequest::getVar('salarymin', 'post');
        $jsjp_search_array['salarymax'] = WPJOBPORTALrequest::getVar('salarymax', 'post');
        $jsjp_search_array['salaryfixed'] = WPJOBPORTALrequest::getVar('salaryfixed', 'post');
        $jsjp_search_array['salaryduration'] = WPJOBPORTALrequest::getVar('salaryduration', 'post');
        $jsjp_search_array['salaryrangetype'] = WPJOBPORTALrequest::getVar('salaryrangetype', 'post');
        $jsjp_search_array['educationid'] = WPJOBPORTALrequest::getVar('educationid', 'post');
        $jsjp_search_array['aijobsearcch'] = WPJOBPORTALrequest::getVar('aijobsearcch', 'post');
        if(in_array('tag', wpjobportal::$_active_addons)){
            $jsjp_search_array['tags'] = WPJOBPORTALrequest::getVar('tags', 'post');
        }
        $jsjp_search_array['search_from_jobs'] = 1;
        $search_userfields = WPJOBPORTALincluder::getObjectClass('customfields')->getSearchUserFieldByFieldFor(2);
        if (!empty($search_userfields)) {
            foreach ($search_userfields as $uf) {
                $jsjp_search_array[$uf->field] = WPJOBPORTALrequest::getVar($uf->field, 'post');
            }
        }
        return $jsjp_search_array;
    }

    // get form submit data for admin jobs
    function getAdminJobSearchFormData($search_userfields){
        $jsjp_search_array = array();
        $jsjp_search_array['searchtitle'] = WPJOBPORTALrequest::getVar('searchtitle');
        $jsjp_search_array['searchcompany'] = WPJOBPORTALrequest::getVar('searchcompany');
        $jsjp_search_array['searchjobcategory'] = WPJOBPORTALrequest::getVar('searchjobcategory');
        $jsjp_search_array['searchjobtype'] = WPJOBPORTALrequest::getVar('searchjobtype');
        $jsjp_search_array['status'] = WPJOBPORTALrequest::getVar('status');
        $jsjp_search_array['featured'] = WPJOBPORTALrequest::getVar('featured');
        $jsjp_search_array['datestart'] = WPJOBPORTALrequest::getVar('datestart');
        $jsjp_search_array['dateend'] = WPJOBPORTALrequest::getVar('dateend');
        $jsjp_search_array['location'] = WPJOBPORTALrequest::getVar('location');
        $jsjp_search_array['sorton'] = WPJOBPORTALrequest::getVar('sorton' , 'post', 6);
        $jsjp_search_array['sortby'] = WPJOBPORTALrequest::getVar('sortby' , 'post', 2);
        $jsjp_search_array['aijobsearcch'] = WPJOBPORTALrequest::getVar('aijobsearcch', 'post');
        $jsjp_search_array['search_from_jobs'] = 1;
        return $jsjp_search_array;
    }

    function setSearchVariableForJob($jsjp_search_array,$search_userfields){
        if(wpjobportal::$_common->wpjp_isadmin()){
            wpjobportal::$_search['jobs']['searchtitle'] = isset($jsjp_search_array['searchtitle']) ? $jsjp_search_array['searchtitle'] : '';
            wpjobportal::$_search['jobs']['searchcompany'] = isset($jsjp_search_array['searchcompany']) ? $jsjp_search_array['searchcompany'] : '';
            wpjobportal::$_search['jobs']['searchjobcategory'] = isset($jsjp_search_array['searchjobcategory']) ? $jsjp_search_array['searchjobcategory'] : '';
            wpjobportal::$_search['jobs']['searchjobtype'] = isset($jsjp_search_array['searchjobtype']) ? $jsjp_search_array['searchjobtype'] : '';
            wpjobportal::$_search['jobs']['status'] = isset($jsjp_search_array['status']) ? $jsjp_search_array['status'] : '';
            wpjobportal::$_search['jobs']['featured'] = isset($jsjp_search_array['featured']) ? $jsjp_search_array['featured'] : '';
            wpjobportal::$_search['jobs']['datestart'] = isset($jsjp_search_array['datestart']) ? $jsjp_search_array['datestart'] : '';
            wpjobportal::$_search['jobs']['dateend'] = isset($jsjp_search_array['dateend']) ? $jsjp_search_array['dateend'] : '';
            wpjobportal::$_search['jobs']['location'] = isset($jsjp_search_array['location']) ? $jsjp_search_array['location'] : '';
            wpjobportal::$_search['jobs']['sorton'] = isset($jsjp_search_array['sorton']) ? $jsjp_search_array['sorton'] : 6;
            wpjobportal::$_search['jobs']['sortby'] = isset($jsjp_search_array['sortby']) ? $jsjp_search_array['sortby'] : 2;
            wpjobportal::$_search['jobs']['aijobsearcch'] = isset($jsjp_search_array['aijobsearcch']) ? $jsjp_search_array['aijobsearcch'] : null;
        }else{
            wpjobportal::$_search['jobs']['jobtitle'] = isset($jsjp_search_array['jobtitle']) ? $jsjp_search_array['jobtitle'] : null;
            wpjobportal::$_search['jobs']['city'] = isset($jsjp_search_array['city']) ? $jsjp_search_array['city'] : null;
            wpjobportal::$_search['jobs']['company'] = isset($jsjp_search_array['company']) ? $jsjp_search_array['company'] : null;
            wpjobportal::$_search['jobs']['metakeywords'] = isset($jsjp_search_array['metakeywords']) ? $jsjp_search_array['metakeywords'] : null;
            wpjobportal::$_search['jobs']['category'] = isset($jsjp_search_array['category']) ? $jsjp_search_array['category'] : null;
            wpjobportal::$_search['jobs']['jobtype'] = isset($jsjp_search_array['jobtype']) ? $jsjp_search_array['jobtype'] : null;
            wpjobportal::$_search['jobs']['careerlevel'] = isset($jsjp_search_array['careerlevel']) ? $jsjp_search_array['careerlevel'] : null;
            wpjobportal::$_search['job']['jobstatus'] = isset($jsjp_search_array['jobstatus']) ? $jsjp_search_array['jobstatus'] : null;
            wpjobportal::$_search['jobs']['currencyid'] = isset($jsjp_search_array['currencyid']) ? $jsjp_search_array['currencyid'] : null;
            wpjobportal::$_search['jobs']['salarytype'] = isset($jsjp_search_array['salarytype']) ? $jsjp_search_array['salarytype'] : null;
            wpjobportal::$_search['jobs']['salaryfixed'] = isset($jsjp_search_array['salaryfixed']) ? $jsjp_search_array['salaryfixed'] : null;
            wpjobportal::$_search['jobs']['salaryduration'] = isset($jsjp_search_array['salaryduration']) ? $jsjp_search_array['salaryduration'] : null;
            wpjobportal::$_search['jobs']['salarymin'] = isset($jsjp_search_array['salarymin']) ? $jsjp_search_array['salarymin'] : null;
            wpjobportal::$_search['jobs']['salarymax'] = isset($jsjp_search_array['salarymax']) ? $jsjp_search_array['salarymax'] : null;
            wpjobportal::$_search['jobs']['salaryrangetype'] = isset($jsjp_search_array['salaryrangetype']) ? $jsjp_search_array['salaryrangetype'] : null;
            wpjobportal::$_search['jobs']['educationid'] = isset($jsjp_search_array['educationid']) ? $jsjp_search_array['educationid'] : null;
            wpjobportal::$_search['jobs']['aijobsearcch'] = isset($jsjp_search_array['aijobsearcch']) ? $jsjp_search_array['aijobsearcch'] : null;
            if(in_array('tag', wpjobportal::$_active_addons)){
                wpjobportal::$_search['jobs']['tags'] = isset($jsjp_search_array['tags']) ? $jsjp_search_array['tags'] : null;
            }
            $search_userfields = WPJOBPORTALincluder::getObjectClass('customfields')->getSearchUserFieldByFieldFor(2);
                if (!empty($search_userfields)) {
                    foreach ($search_userfields as $uf) {
                        wpjobportal::$_search['jobs'][$uf->field] = isset($jsjp_search_array[$uf->field]) ? $jsjp_search_array[$uf->field] : null;
                    }
                }
        }
    }

    function getCookiesSavedSearchDataJob($search_userfields){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = json_decode( wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data) , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_jobs']) && $wpjp_search_cookie_data['search_from_jobs'] == 1){
            if(wpjobportal::$_common->wpjp_isadmin()){
                // updated the code below to handle log errors in case of field disabled for search
                $jsjp_search_array['searchtitle'] = isset($wpjp_search_cookie_data['searchtitle']) ? $wpjp_search_cookie_data['searchtitle']: '';
                $jsjp_search_array['searchcompany'] = isset($wpjp_search_cookie_data['searchcompany']) ? $wpjp_search_cookie_data['searchcompany']: '';
                $jsjp_search_array['searchjobcategory'] = isset($wpjp_search_cookie_data['searchjobcategory']) ? $wpjp_search_cookie_data['searchjobcategory']: '';
                $jsjp_search_array['searchjobtype'] = isset($wpjp_search_cookie_data['searchjobtype']) ? $wpjp_search_cookie_data['searchjobtype']: '';
                $jsjp_search_array['status'] = isset($wpjp_search_cookie_data['status']) ? $wpjp_search_cookie_data['status']: '';
                $jsjp_search_array['featured'] = isset($wpjp_search_cookie_data['featured']) ? $wpjp_search_cookie_data['featured']: '';
                $jsjp_search_array['datestart'] = isset($wpjp_search_cookie_data['datestart']) ? $wpjp_search_cookie_data['datestart']: '';
                $jsjp_search_array['dateend'] = isset($wpjp_search_cookie_data['dateend']) ? $wpjp_search_cookie_data['dateend']: '';
                $jsjp_search_array['location'] = isset($wpjp_search_cookie_data['location']) ? $wpjp_search_cookie_data['location']: '';
                $jsjp_search_array['sorton'] = isset($wpjp_search_cookie_data['sorton']) ? $wpjp_search_cookie_data['sorton']: '';
                $jsjp_search_array['sortby'] = isset($wpjp_search_cookie_data['sortby']) ? $wpjp_search_cookie_data['sortby']: '';
                $jsjp_search_array['aijobsearcch'] = isset($wpjp_search_cookie_data['aijobsearcch']) ? $wpjp_search_cookie_data['aijobsearcch']: '';
            }else{
                $jsjp_search_array['metakeywords'] = isset($wpjp_search_cookie_data['metakeywords']) ? $wpjp_search_cookie_data['metakeywords']: '';
                $jsjp_search_array['jobtitle'] = isset($wpjp_search_cookie_data['jobtitle']) ? $wpjp_search_cookie_data['jobtitle']: '';
                $jsjp_search_array['company'] = isset($wpjp_search_cookie_data['company']) ? $wpjp_search_cookie_data['company']: '';
                $jsjp_search_array['category'] = isset($wpjp_search_cookie_data['category']) ? $wpjp_search_cookie_data['category']: '';
                $jsjp_search_array['jobtype'] = isset($wpjp_search_cookie_data['jobtype']) ? $wpjp_search_cookie_data['jobtype']: '';
                $jsjp_search_array['careerlevel'] = isset($wpjp_search_cookie_data['careerlevel']) ? $wpjp_search_cookie_data['careerlevel']: '';
                $jsjp_search_array['jobstatus'] = isset($wpjp_search_cookie_data['jobstatus']) ? $wpjp_search_cookie_data['jobstatus']: '';
                $jsjp_search_array['currencyid'] = isset($wpjp_search_cookie_data['currencyid']) ? $wpjp_search_cookie_data['currencyid']: '';
                $jsjp_search_array['salarytype'] = isset($wpjp_search_cookie_data['salarytype']) ? $wpjp_search_cookie_data['salarytype']: '';
                $jsjp_search_array['city'] = isset($wpjp_search_cookie_data['city']) ? $wpjp_search_cookie_data['city']: '';
                $jsjp_search_array['salarymin'] = isset($wpjp_search_cookie_data['salarymin']) ? $wpjp_search_cookie_data['salarymin']: '';
                $jsjp_search_array['salarymax'] = isset($wpjp_search_cookie_data['salarymax']) ? $wpjp_search_cookie_data['salarymax']: '';
                $jsjp_search_array['salaryfixed'] = isset($wpjp_search_cookie_data['salaryfixed']) ? $wpjp_search_cookie_data['salaryfixed']: '';
                $jsjp_search_array['salaryduration'] = isset($wpjp_search_cookie_data['salaryduration']) ? $wpjp_search_cookie_data['salaryduration']: '';
                $jsjp_search_array['salaryrangetype'] = isset($wpjp_search_cookie_data['salaryrangetype']) ? $wpjp_search_cookie_data['salaryrangetype']: '';
                $jsjp_search_array['educationid'] = isset($wpjp_search_cookie_data['educationid']) ? $wpjp_search_cookie_data['educationid']: '';
                $jsjp_search_array['aijobsearcch'] = isset($wpjp_search_cookie_data['aijobsearcch']) ? $wpjp_search_cookie_data['aijobsearcch']: '';
                $jsjp_search_array['tags'] = isset($wpjp_search_cookie_data['tags']) ? $wpjp_search_cookie_data['tags']: '';
		$search_userfields = WPJOBPORTALincluder::getObjectClass('customfields')->getSearchUserFieldByFieldFor(2);
                if (!empty($search_userfields)) {
                    foreach ($search_userfields as $uf) {
                        $jsjp_search_array[$uf->field] = $wpjp_search_cookie_data[$uf->field];
                    }
                }
            }
        }
        return $jsjp_search_array;
    }

    function getJobsForPageBuilderWidget($no_of_jobs){

        $query="SELECT COUNT(job.id)
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ";
        $query .= " WHERE job.status = 1 AND job.startpublishing <= CURDATE() AND job.stoppublishing > CURDATE() " ;
        $totaljobs = wpjobportaldb::get_var($query);
        $query = "SELECT DISTINCT job.id AS jobid,job.id AS id,job.tags AS jobtags,job.title,job.created,job.city,job.endfeatureddate,job.isfeaturedjob,job.status,job.startpublishing,job.stoppublishing,job.currency,
                CONCAT(job.alias,'-',job.id) AS jobaliasid,job.noofjobs,
                cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, jobtype.title AS jobtypetitle,
                job.params,CONCAT(company.alias,'-',company.id) AS companyaliasid,LOWER(jobtype.title) AS jobtypetit,
                job.salarymax,job.salarymin,job.salarytype,srtype.title AS srangetypetitle,jobtype.color AS jobtypecolor,job.jobapplylink ,job.joblink
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS srtype ON srtype.id = job.salaryduration
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity ON jobcity.jobid = job.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = jobcity.cityid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.countryid = city.countryid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()";
        $query .= "  ORDER BY job.created DESC LIMIT " . esc_sql($no_of_jobs);
        $results = wpjobportaldb::get_results($query);
        $latestjobs = array();
        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
            $latestjobs[] = $d;
        }
        return $latestjobs;
    }

    function jobDataStructuredPostJSON($job){
        $wpdir = wp_upload_dir();
        $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->logofilename;

        $job_json = '
        {
          "@context" : "https://schema.org/",
          "@type" : "JobPosting",
          "title" : "'. $job->title .'",
          "description" : "'. $job->description .'",
          "identifier": {
            "@type": "PropertyValue",
            "name": "'. $job->companyname .'",
            "value": "'. $job->jobid .'"
          },
          "datePosted" : "'. $job->created .'",
          "validThrough" : "'. $job->stoppublishing .'",
          "employmentType" : "'. $job->jobtypetitle .'",
          "hiringOrganization" : {
            "@type" : "Organization",
            "name" : "'. $job->companyname .'",
            "sameAs" : "https://www.facebook.com",
            "logo" : "'. $path .'"
          },
          "jobLocation": {
          "@type": "Place",
            "address": [{
            "@type": "PostalAddress",
            "streetAddress": "'. $job->multicity .'"
            },{
            "@type": "PostalAddress",
            "streetAddress": "'. $job->multicity .'"
            }]
          },
         "baseSalary": {
            "@type": "MonetaryAmount",
            "currency": "'.$job->currency.'",
            "value": {
              "@type": "QuantitativeValue",
              "value": "'.$job->salary.'",
              "unitText": "'.$job->srangetypetitle.'"
            }
          }
        }
        ';
        return $job_json;
    }

    // functino for ai addons get jobs by job ids for suggested and ai web search

    function getJobsByJobIds($job_id_list){
        if($job_id_list == ''){
            return false;
        }
        $curdate = gmdate('Y-m-d');
        //Data/Data
        $query = "SELECT DISTINCT job.id AS jobid,job.id AS id,job.tags AS jobtags,job.title,job.created,job.city,job.endfeatureddate,job.isfeaturedjob,job.status,job.startpublishing,job.stoppublishing,job.currency,
        CONCAT(job.alias,'-',job.id) AS jobaliasid,job.noofjobs,
        cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, jobtype.title AS jobtypetitle,
        job.params,CONCAT(company.alias,'-',company.id) AS companyaliasid,LOWER(jobtype.title) AS jobtypetit,
        job.salarymax,job.salarymin,job.salarytype,srtype.title AS srangetypetitle,jobtype.color AS jobtypecolor, job.jobapplylink, job.joblink, job.description
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS srtype ON srtype.id = job.salaryduration
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity ON jobcity.jobid = job.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = jobcity.cityid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.countryid = city.countryid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
        WHERE job.status = 1 AND DATE(job.startpublishing) <= '".$curdate."' AND DATE(job.stoppublishing) >= '".$curdate."'";
        $query .= " AND job.id IN (".$job_id_list.")";

        $results = wpjobportaldb::get_results($query);

        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
            $data[] = $d;
        }
        return $results;
    }

    // function to handle ai column on new/edit job
    function prepareAIStringDataForJob($data){
        if(empty($data)){
            return;
        }
        if(empty($data['id'])){
            return;
        }

        $job_ai_string = '';

        if (!empty($data['title'])) {
            $job_ai_string .= wpjobportalphplib::wpJP_trim($data['title']) . ' ';
        }
        if (!empty($data['companyid']) && is_numeric($data['companyid'])) {
            $company_name = WPJOBPORTALincluder::getJSModel('company')->getCompanynameById($data['companyid']);
            if($company_name){ // the above function may return false
                $job_ai_string .= $company_name . ' ';
            }
        }

        if (!empty($data['jobcategory']) && is_numeric($data['jobcategory'])) {
            $cat_title = WPJOBPORTALincluder::getJSModel('category')->getTitleByCategory($data['jobcategory']);
            if($cat_title){ // the above function may return false
                $job_ai_string .= $cat_title . ' ';
            }
        }

        if (!empty($data['jobtype']) && is_numeric($data['jobtype'])) {
            $jobtype_title = WPJOBPORTALincluder::getJSModel('jobtype')->getTitleByid($data['jobtype']);
            if($jobtype_title){ // the above function may return false
                $job_ai_string .= $jobtype_title . ' ';
            }
        }

        if (!empty($data['jobstatus']) && is_numeric($data['jobstatus'])) {
            $jobstatus_title = WPJOBPORTALincluder::getJSModel('jobstatus')->getTitleByid($data['jobstatus']);
            if($jobstatus_title){ // the above function may return false
                $job_ai_string .= $jobstatus_title . ' ';
            }
        }

        $salary = wpjobportal::$_common->getSalaryRangeView($data['salarytype'], $data['salarymin'], $data['salarymax'],$data['currency']);
        if($salary != ''){
            $job_ai_string .= $salary.' ';
            if(!empty($data['salaryduration'])) {
                $salaryrange_title = WPJOBPORTALincluder::getJSModel('salaryrangetype')->getTitleByid($data['salaryduration']);
                if($salaryrange_title){
                    $job_ai_string .= $salaryrange_title. ' ';
                }
            }
        }

        if (!empty($data['careerlevel']) && is_numeric($data['careerlevel'])) {
            $careerlevel_title = WPJOBPORTALincluder::getJSModel('careerlevel')->getTitleByid($data['careerlevel']);
            if($careerlevel_title){ // the above function may return false
                $job_ai_string .= $careerlevel_title . ' ';
            }
        }

        if (!empty($data['city'])) {
            $location_string = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($data['city']);
            if($location_string){ // the above function may return false
                $job_ai_string .= $location_string . ' ';
            }
        }

        if (!empty($data['duration'])) {
            $job_ai_string .= wpjobportalphplib::wpJP_trim($data['duration']) . ' ';
        }

        if (!empty($data['experience'])) {
            $job_ai_string .= wpjobportalphplib::wpJP_trim($data['experience']) . ' ';
        }


        // handle custom fields
        $custom_fields = WPJOBPORTALincluder::getObjectClass('customfields')->userFieldsData(2);
        // ignore these field types from current case
        $skip_types = ['file', 'email', 'textarea'];

        $text_area_field_values = '';

        foreach ($custom_fields as $single_field) {
            if(!in_array($single_field->userfieldtype, $skip_types)){ // check if type agaisnt array
                if (!empty($data[$single_field->field])) { // check value exsists
                    if(is_array($data[$single_field->field])){ // to handle multi select and check box case
                        $job_ai_string .= implode(',', $data[$single_field->field]) . ' ';
                    }else{
                        $job_ai_string .= $data[$single_field->field] . ' ';
                    }
                }
            }elseif($single_field->userfieldtype == 'textarea'){ // to handle description case in same loop
                if (!empty($data[$single_field->field])) { // check value exsists
                    $text_area_field_values .= $data[$single_field->field] . ' ';
                }
            }
        }

        $job_ai_string = trim($job_ai_string); // Clean trailing space

        // SECOND LEVEL FOR DESCRIPTION FIELD

        $job_ai_desc_string = $job_ai_string;

        if (!empty($data['description'])) {
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($data['description']) . ' ';
        }

        if (!empty($data['tags'])) {
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($data['tags']) . ' ';
        }

        if (!empty($data['metakeywords'])) {
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($data['metakeywords']) . ' ';
        }

        $dateformat = wpjobportal::$_configuration['date_format'];
        if (!empty($data['startpublishing'])) {
            $start_date = date_i18n($dateformat, strtotime($data['startpublishing']));
            $job_ai_desc_string .= $start_date . ' ';
        }

        $dateformat = wpjobportal::$_configuration['date_format'];
        if (!empty($data['stoppublishing'])) {
            $stop_date = date_i18n($dateformat, strtotime($data['stoppublishing']));
            $job_ai_desc_string .= $stop_date . ' ';
        }


        if (!empty($data['metadescription'])) {
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($data['metadescription']) . ' ';
        }

        if (!empty($text_area_field_values)) { // text area type field values from the above loop
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($text_area_field_values) . ' ';
        }

        // echo '</pre>';print_r($job_ai_string);echo '</pre>';
        // echo '================================================================================================================';
        // echo '</pre>';print_r($job_ai_desc_string);echo '</pre>';

        $row = WPJOBPORTALincluder::getJSTable('job');
        if ($row->update(array('id'=>$data['id'], 'aijobsearchtext' => $job_ai_string, 'aijobsearchdescription' => $job_ai_desc_string))) {
            return;
        }
        return;
    }

    // function to handle ai columns for jobs in case of importing exsistin data
    // this fucntion enusres to handle bulk cases efficecntly
    function importAIStringDataForJobs($data){
        if(empty($data)){
            return;
        }
        if(empty($data['id'])){
            return;
        }

        $job_ai_string = '';

        if (!empty($data['title'])) {
            $job_ai_string .= wpjobportalphplib::wpJP_trim($data['title']) . ' ';
        }

        // to keep record of entity titles insted of fetching from db everytime
        if(!isset(wpjobportal::$_data['ai'])){
            wpjobportal::$_data['ai'] = array();
        }
        if(!isset(wpjobportal::$_data['ai']['companies'])){
            wpjobportal::$_data['ai']['companies'] = array();
        }
        if (!empty($data['companyid']) && is_numeric($data['companyid'])) {
            if (!isset(wpjobportal::$_data['ai']['companies'][$data['companyid']])) {
                $company_name = WPJOBPORTALincluder::getJSModel('company')->getCompanynameById($data['companyid']);
                wpjobportal::$_data['ai']['companies'][$data['companyid']] = $company_name;
            }else{
                $company_name = wpjobportal::$_data['ai']['companies'][$data['companyid']];
            }
            if($company_name){ // the above function may return false
                $job_ai_string .= $company_name . ' ';
            }
        }

        if(!isset(wpjobportal::$_data['ai']['categories'])){
            wpjobportal::$_data['ai']['categories'] = array();
        }
        if (!empty($data['jobcategory']) && is_numeric($data['jobcategory'])) {
            if (!isset(wpjobportal::$_data['ai']['categories'][$data['jobcategory']])) {
                $cat_title = WPJOBPORTALincluder::getJSModel('category')->getTitleByCategory($data['jobcategory']);
                wpjobportal::$_data['ai']['categories'][$data['jobcategory']] = $cat_title;
            } else {
                $cat_title = wpjobportal::$_data['ai']['categories'][$data['jobcategory']];
            }

            if ($cat_title) {
                $job_ai_string .= $cat_title . ' ';
            }
        }

        if(!isset(wpjobportal::$_data['ai']['jobtypes'])){
            wpjobportal::$_data['ai']['jobtypes'] = array();
        }
        if (!empty($data['jobtype']) && is_numeric($data['jobtype'])) {
            if (!isset(wpjobportal::$_data['ai']['jobtypes'][$data['jobtype']])) {
                $jobtype_title = WPJOBPORTALincluder::getJSModel('jobtype')->getTitleByid($data['jobtype']);
                wpjobportal::$_data['ai']['jobtypes'][$data['jobtype']] = $jobtype_title;
            } else {
                $jobtype_title = wpjobportal::$_data['ai']['jobtypes'][$data['jobtype']];
            }

            if ($jobtype_title) {
                $job_ai_string .= $jobtype_title . ' ';
            }
        }

        if(!isset(wpjobportal::$_data['ai']['jobstatuses'])){
            wpjobportal::$_data['ai']['jobstatuses'] = array();
        }

        if (!empty($data['jobstatus']) && is_numeric($data['jobstatus'])) {
            if (!isset(wpjobportal::$_data['ai']['jobstatuses'][$data['jobstatus']])) {
                $jobstatus_title = WPJOBPORTALincluder::getJSModel('jobstatus')->getTitleByid($data['jobstatus']);
                wpjobportal::$_data['ai']['jobstatuses'][$data['jobstatus']] = $jobstatus_title;
            } else {
                $jobstatus_title = wpjobportal::$_data['ai']['jobstatuses'][$data['jobstatus']];
            }

            if ($jobstatus_title) {
                $job_ai_string .= $jobstatus_title . ' ';
            }
        }

        $salary = wpjobportal::$_common->getSalaryRangeView($data['salarytype'], $data['salarymin'], $data['salarymax'],$data['currency']);
        if(!isset(wpjobportal::$_data['ai']['salaryranges'])){
            wpjobportal::$_data['ai']['salaryranges'] = array();
        }
        if($salary != ''){
            $job_ai_string .= $salary.' ';
            if(!empty($data['salaryduration'])) {
                if (!isset(wpjobportal::$_data['ai']['salaryranges'][$data['salaryduration']])) {
                    $salaryrange_title = WPJOBPORTALincluder::getJSModel('salaryrangetype')->getTitleByid($data['salaryduration']);
                    wpjobportal::$_data['ai']['salaryranges'][$data['salaryduration']] = $salaryrange_title;
                } else {
                    $salaryrange_title = wpjobportal::$_data['ai']['salaryranges'][$data['salaryduration']];
                }
                if($salaryrange_title){
                    $job_ai_string .= $salaryrange_title. ' ';
                }
            }
        }

        if(!isset(wpjobportal::$_data['ai']['careerlevels'])){
            wpjobportal::$_data['ai']['careerlevels'] = array();
        }

        if (!empty($data['careerlevel']) && is_numeric($data['careerlevel'])) {
            if (!isset(wpjobportal::$_data['ai']['careerlevels'][$data['careerlevel']])) {
                $careerlevel_title = WPJOBPORTALincluder::getJSModel('careerlevel')->getTitleByid($data['careerlevel']);
                wpjobportal::$_data['ai']['careerlevels'][$data['careerlevel']] = $careerlevel_title;
            } else {
                $careerlevel_title = wpjobportal::$_data['ai']['careerlevels'][$data['careerlevel']];
            }

            if ($careerlevel_title) {
                $job_ai_string .= $careerlevel_title . ' ';
            }
        }

        if(!isset(wpjobportal::$_data['ai']['locations'])){
            wpjobportal::$_data['ai']['locations'] = array();
        }

        if (!empty($data['city'])) {
            if (!isset(wpjobportal::$_data['ai']['locations'][$data['city']])) {
                $location_string = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($data['city']);
                wpjobportal::$_data['ai']['locations'][$data['city']] = $location_string;
            } else {
                $location_string = wpjobportal::$_data['ai']['locations'][$data['city']];
            }

            $location_string = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($data['city']);
            if($location_string){ // the above function may return false
                $job_ai_string .= $location_string . ' ';
            }
        }

        if (!empty($data['duration'])) {
            $job_ai_string .= wpjobportalphplib::wpJP_trim($data['duration']) . ' ';
        }

        if (!empty($data['experience'])) {
            $job_ai_string .= wpjobportalphplib::wpJP_trim($data['experience']) . ' ';
        }

        // handle custom fields
        if(!isset(wpjobportal::$_data['ai']['customfields_user_2'])){
            wpjobportal::$_data['ai']['customfields_user_2'] = WPJOBPORTALincluder::getObjectClass('customfields')->userFieldsData(2);
        }

        $custom_fields = wpjobportal::$_data['ai']['customfields_user_2'];
        //$custom_fields = WPJOBPORTALincluder::getObjectClass('customfields')->userFieldsData(2);
        // ignore these field types from current case
        $skip_types = ['file', 'email', 'textarea'];

        $text_area_field_values = '';

        foreach ($custom_fields as $single_field) {
            if(!in_array($single_field->userfieldtype, $skip_types)){ // check if type agaisnt array
                if (!empty($data[$single_field->field])) { // check value exsists
                    if(is_array($data[$single_field->field])){ // to handle multi select and check box case
                        $job_ai_string .= implode(',', $data[$single_field->field]) . ' ';
                    }else{
                        $job_ai_string .= $data[$single_field->field] . ' ';
                    }
                }
            }elseif($single_field->userfieldtype == 'textarea'){ // to handle text area field for description case in same loop
                if (!empty($data[$single_field->field])) { // check value exsists
                    $text_area_field_values .= $data[$single_field->field] . ' ';
                }
            }
        }

        $job_ai_string = trim($job_ai_string); // Clean trailing space

        // SECOND LEVEL FOR DESCRIPTION FIELD

        $job_ai_desc_string = $job_ai_string;

        if (!empty($data['description'])) {
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($data['description']) . ' ';
        }

        if (!empty($data['tags'])) {
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($data['tags']) . ' ';
        }

        if (!empty($data['metakeywords'])) {
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($data['metakeywords']) . ' ';
        }

        $dateformat = wpjobportal::$_configuration['date_format'];
        if (!empty($data['startpublishing'])) {
            $start_date = date_i18n($dateformat, strtotime($data['startpublishing']));
            $job_ai_desc_string .= $start_date . ' ';
        }

        $dateformat = wpjobportal::$_configuration['date_format'];
        if (!empty($data['stoppublishing'])) {
            $stop_date = date_i18n($dateformat, strtotime($data['stoppublishing']));
            $job_ai_desc_string .= $stop_date . ' ';
        }


        if (!empty($data['metadescription'])) {
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($data['metadescription']) . ' ';
        }

        if (!empty($text_area_field_values)) { // text area type field values from the above loop
            $job_ai_desc_string .= wpjobportalphplib::wpJP_trim($text_area_field_values) . ' ';
        }

        // echo '</pre>';print_r($job_ai_string);echo '</pre>';
        // echo '================================================================================================================';
        // echo '</pre>';print_r($job_ai_desc_string);echo '</pre>';

        $row = WPJOBPORTALincluder::getJSTable('job');
        if ($row->update(array('id'=>$data['id'], 'aijobsearchtext' => $job_ai_string, 'aijobsearchdescription' => $job_ai_desc_string))) {
            return;
        }

        return;
    }

    function updateRecordsForAISearchJob(){
        $query = " SELECT job.*
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                ORDER BY job.id ASC ";
        $jobs = wpjobportaldb::get_results($query); // fetch all jobs



        foreach ($jobs as $job) { // loop over all jobs
            $job_arrray = json_decode(json_encode($job),true); // convert std class object to array
            WPJOBPORTALincluder::getJSModel('job')->importAIStringDataForJobs($job_arrray);
        }
        return;
    }


}
?>
