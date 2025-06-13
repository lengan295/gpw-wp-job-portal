<?php
//deleteUserPhoto
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALJobseekerModel {

    function getConfigurationForControlPanel() {
        // configuration for layout
        $config =  wpjobportal::$_config->getConfigByFor('jscontrolpanel');
        $config['show_applied_resume_status'] = wpjobportal::$_config->getConfigurationByConfigName('show_applied_resume_status');
        wpjobportal::$_data['configs'] = $config;
    }

    function getMessagekey(){
        $key = 'jobseeker';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

    function getResumeStatusByUid($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT jobapply.action_status, job.title, job.city ,resume.application_title, resume.photo,job.id AS jobid
                    , resume.email_address,jobcat.cat_title,resume.id,jobapply.apply_date As jobapply,company.id AS companyid,company.logofilename AS companylogo
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS resume
                    JOIN " . wpjobportal::$_db->prefix . "wj_portal_jobapply AS jobapply ON jobapply.cvid = resume.id
                    JOIN " . wpjobportal::$_db->prefix . "wj_portal_jobs AS job ON job.id = jobapply.jobid
                    ".wpjobportal::$_company_job_table_join." JOIN " . wpjobportal::$_db->prefix . "wj_portal_companies AS company ON company.id = job.companyid
                    LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_categories AS jobcat ON jobcat.id = resume.job_category
                    WHERE resume.uid = ". esc_sql($uid)." GROUP BY jobapply.id LIMIT 0,5";

        wpjobportal::$_data[0]['resume'] = wpjobportaldb::get_results($query);

        $query = "SELECT resume.id as resumeid ,count(*) as resumeno
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS resume
                    WHERE `uid`=".esc_sql($uid)."
                    GROUP BY resume.id  ORDER BY resume.id ASC LIMIT 0,1 ";// ASC to change to same resume shown in my resume listing in case of missing mutlti resume addon
        wpjobportal::$_data[0]['resume']['info'] = wpjobportaldb::get_results($query);
    }

    // tried using the above getResumeStatusByUid function
    //but setting data in "wpjobportal::$_data[0]['resume']" causes listings to break
    // listings have foreach on "wpjobportal::$_data[0]"
    function getResumeInfoForJobSeekerLeftMenu($uid){
        if (!is_numeric($uid))
            return false;
        $query = "SELECT resume.id as resumeid, resume.application_title as application_title
                    FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS resume
                    WHERE `uid`='".esc_sql($uid)."'
                    ORDER BY resume.id ASC ";
        wpjobportal::$_data['resume_info_menu'] = wpjobportaldb::get_row($query);
     }

    function getLatestJobs() {
        $query = "SELECT DISTINCT job.id AS jobid,job.tags AS jobtags,job.title,job.created,job.city,job.currency,
        CONCAT(job.alias,'-',job.id) AS jobaliasid,job.noofjobs,job.endfeatureddate,
        job.isfeaturedjob,job.status,job.startpublishing,job.stoppublishing,cat.cat_title,company.id AS companyid,company.name AS companyname,company.logofilename, jobtype.title AS jobtypetitle,job.id AS id,
        job.params,CONCAT(company.alias,'-',company.id) AS companyaliasid,LOWER(jobtype.title) AS jobtypetit,
        job.salarymax,job.salarymin,job.salarytype,job.description,srtype.title AS srangetypetitle,jobtype.color AS jobtypecolor
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
        ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS srtype ON srtype.id = job.salaryduration
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity ON jobcity.jobid = job.id
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = jobcity.cityid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.countryid = city.countryid
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
        WHERE job.status = 1 AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()
                    ORDER BY job.created DESC
                    LIMIT 0,4";
        $data = wpjobportaldb::get_results($query);
        foreach ($data as $job) {
            $job->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($job->city);
        }
        wpjobportal::$_data[0]['latestjobs'] = $data;
        wpjobportal::$_data['fields'] = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(2);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('job');
    }

    function getJobsAppliedRecently($uid){
        if (!is_numeric($uid))
            return false;
        $query = "SELECT job.id AS jobid,job.city,job.title,job.noofjobs,CONCAT(job.alias,'-',job.id) AS jobaliasid ,CONCAT(company.alias,'-',companyid) AS companyaliasid, job.serverid,
                 jobapply.action_status AS resumestatus,jobapply.apply_date,jobapply.status AS applystatus,job.currency,
                 company.id AS companyid, company.name AS companyname,company.logofilename,category.cat_title,
                 jobtype.title AS jobtypetitle, jobstatus.title AS jobstatustitle,resume.id AS resumeid,resume.salaryfixed as salary,resume.application_title,job.params,job.created,LOWER(jobtype.title) AS jobtype
                ,jobapply.id AS id,resume.first_name,resume.last_name,job.salarymin,job.salarymax,job.salarytype,
                                salaryrangetype.title AS srangetypetitle,job.endfeatureddate,job.isfeaturedjob,job.status,job.startpublishing,job.stoppublishing,jobtype.color AS jobtypecolor,jobapply.coverletterid,job.description
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply
                 JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON job.id = jobapply.jobid
                 JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume ON resume.id = jobapply.cvid
                 ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                 LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = job.jobcategory
                 LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                 LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobstatus` AS jobstatus ON jobstatus.id = job.jobstatus
                 LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryduration
                 WHERE jobapply.uid = ". esc_sql($uid);
        $query.= " ORDER BY resume.id ";
        $query.=" LIMIT  0,4";
        $results = wpjobportaldb::get_results($query);
        $data = array();
        foreach ($results AS $d) {
            $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
            if(in_array('coverletter', wpjobportal::$_active_addons)){
                $d->coverlettertitle = WPJOBPORTALincluder::getJSModel('coverletter')->getCoverLetterTitleFromID($d->coverletterid);
            }
            $data[] = $d;
        }
        wpjobportal::$_data[0]['appliedjobs'] = $data;
        wpjobportal::$_data['fields'] = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(2);
        wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('job');
        return;
    }

    function getUserinfo($uid){
        if (!is_numeric($uid))
            return false;
        $query = "SELECT * FROM `".wpjobportal::$_db->prefix."wj_portal_users` as users
        WHERE `id`=".esc_sql($uid);
        $data = wpjobportaldb::get_results($query);
        wpjobportal::$_data['userprofile'] = $data;
         $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs`";
        wpjobportal::$_data['totaljobs'] = wpjobportal::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies`";
        wpjobportal::$_data['totalcompanies'] = wpjobportal::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` where uid = ".esc_sql($uid)." AND status = 1";
        wpjobportal::$_data['totalresume'] = wpjobportal::$_db->get_var($query);
        if(!in_array('multiresume', wpjobportal::$_active_addons) && wpjobportal::$_data['totalresume'] > 1){
            wpjobportal::$_data['totalresume'] = 1;
        }
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` where status = 1 and uid=".esc_sql($uid);
        wpjobportal::$_data['totaljobapply'] = wpjobportal::$_db->get_var($query);
        if(in_array('shortlist', wpjobportal::$_active_addons)){
            // modified the below code to make sure that the jobs that are shown on shortlisted job listing are counted for dashboard stat
            $query23 = "SELECT COUNT(shortlist.id)
                            FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobshortlist` AS shortlist
                            JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON  job.id = shortlist.jobid
                            WHERE shortlist.status = 1  AND shortlist.uid =". esc_sql($uid)."
                            AND DATE(job.startpublishing) <= CURDATE() AND DATE(job.stoppublishing) >= CURDATE()";
            wpjobportal::$_data['totalshorlistjob'] = wpjobportal::$_db->get_var($query23);
        }
        $curdate = gmdate('Y-m-d');
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE DATE(startpublishing) <= '".esc_sql($curdate)."' AND DATE(stoppublishing) >= '".esc_sql($curdate)."' AND status = 1";
        wpjobportal::$_data['totalactivejobs'] = wpjobportal::$_db->get_var($query);
        $newindays = wpjobportal::$_config->getConfigurationByConfigName('newdays');
        if ($newindays == 0) {
            $newindays = 7;
        }
        $time = strtotime($curdate . ' -' . $newindays . ' days');
        $lastdate = gmdate("Y-m-d", $time);
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE DATE(created) >= DATE('".esc_sql($lastdate)."') AND DATE(created) <= DATE('".esc_sql($curdate)."')";
        wpjobportal::$_data['totalnewjobs'] = wpjobportal::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE DATE(created) >= DATE('".esc_sql($lastdate)."') AND DATE(created) <= DATE('".esc_sql($curdate)."')";
        wpjobportal::$_data['totalnewcompanies'] = wpjobportal::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE DATE(created) >= DATE('".esc_sql($lastdate)."') AND DATE(created) <= DATE('".esc_sql($curdate)."')";
        wpjobportal::$_data['totalnewresume'] = wpjobportal::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE DATE(apply_date) >= DATE('".esc_sql($lastdate)."') AND DATE(apply_date) <= DATE('".esc_sql($curdate)."')";
        wpjobportal::$_data['totalnewjobapply'] = wpjobportal::$_db->get_var($query);

    }

    function getJobsekerResumeTitle($uid){
        if(!is_numeric($uid))
            return false;
        $query="SELECT application_title as tite FROM `".wpjobportal::$_db->prefix."wj_portal_resume`
        WHERE `uid`=".esc_sql($uid)." ORDER BY ID DESC LIMIT 0,1";
        $data=wpjobportaldb::get_var($query);
        wpjobportal::$_data['application_title'] = $data;
    }

    function getGraphDataNew($uid=''){

        $query = "SELECT * FROM `".wpjobportal::$_db->prefix."wj_portal_jobtypes`
         WHERE `id`>0 LIMIT 0,3";
         $data = wpjobportaldb::get_results($query);
         wpjobportal::$_data['jobtype'] = $data;
         $html = "['" . esc_html(__('Dates', 'wp-job-portal')) . "'";
          foreach (wpjobportal::$_data['jobtype'] as $key ) {
            $html .= ",'". wpjobportal::wpjobportal_getVariableValue($key->title)."'";
            $jobtype[] = $key->id;
        }
        $query = "SELECT count(job.id) AS job,MONTH(job.created) AS MONTH, YEAR(job.created) AS YEAR ,type.id AS jobtype
                    FROM `".wpjobportal::$_db->prefix."wj_portal_jobs` AS job
                    RIGHT JOIN `".wpjobportal::$_db->prefix."wj_portal_jobtypes` AS type ON job.jobtype=type.id  ";
        $query .= " GROUP by MONTH(job.created),YEAR(job.created),type.id
                    ORDER BY YEAR(job.created),MONTH(job.created) ASC";
        $result = wpjobportaldb::get_results($query);
        wpjobportal::$_data[0]['title'] = $result;
        $prev_workstations = '';
        foreach (wpjobportal::$_data['0']['title'] as $parent) {
            $crm_workstations = $parent->jobtype;
            if (($crm_workstations !='') && ($crm_workstations != $prev_workstations)){
                $prev_workstations = $crm_workstations;
               $crm_workstations;
            }
            // php 8 issue md_strlen function
            if($parent->MONTH != ''){
                if(wpjobportalphplib::wpJP_strlen($parent->MONTH) <= 1){
                    $parent->MONTH='0'.$parent->MONTH;
                }
            }
            wpjobportal::$_data['datachart'][$crm_workstations][$parent->YEAR][$parent->MONTH]=$parent->job;
        }
        $html.="]";
         wpjobportal::$_data['stack_chart_horizontal']['title'] = $html;
         wpjobportal::$_data['stack_chart_horizontal']['data']='';
         ///////*****TO Show All Month Till Last Month ****////////
         for ($i=0; $i<=11; $i++) {
            $Date = gmdate('Y-m', strtotime("-$i month"));
            $Time = wpjobportalphplib::wpJP_explode('-',$Date);
            $Month = $Time[1];
            $Year = $Time[0];
            $dateObj = DateTime::createFromFormat('!m', $Month);
            $monthName = $dateObj->format('M');
             $MonthName=$monthName.'-'.wpjobportalphplib::wpJP_substr($Year,-2);
            /////******Passing Data To Graph*********//////////
            $FullTime = wpjobportal::$_data['jobtype'][0]->id;
            $PartTime = wpjobportal::$_data['jobtype'][1]->id;
            $internship = wpjobportal::$_data['jobtype'][2]->id;
            wpjobportal::$_data['stack_chart_horizontal']['data'] .= "['" . $MonthName . "',";
            $FullTimeData = isset(wpjobportal::$_data['datachart'][$FullTime][$Year][$Month]) ? wpjobportal::$_data['datachart'][$FullTime][$Year][$Month] : 0;
            $ParTimeData = isset(wpjobportal::$_data['datachart'][$PartTime][$Year][$Month]) ? wpjobportal::$_data['datachart'][$PartTime][$Year][$Month] : 0;
            $internshipData = isset(wpjobportal::$_data['datachart'][$internship][$Year][$Month]) ? wpjobportal::$_data['datachart'][$internship][$Year][$Month] : 0;
            wpjobportal::$_data['stack_chart_horizontal']['data'] .=  $FullTimeData.",".$ParTimeData.",".$internshipData."]";
            if($i!=12){
             wpjobportal::$_data['stack_chart_horizontal']['data'] .= ',';
            }
        }
        return ;
    }

    function handleShortCodeOptions(){
        //handle attirbute for hide profile section on dashboard
        $hide_profile_section = WPJOBPORTALrequest::getVar('hide_profile_section', 'shortcode_option', false);
        if($hide_profile_section && $hide_profile_section != ''){
            wpjobportal::$_data['shortcode_option_hide_profile_section'] = 1;
        }

        //handle attirbute for hide profile section on dashboard
        $hide_graph = WPJOBPORTALrequest::getVar('hide_graph', 'shortcode_option', false);
        if($hide_graph && $hide_graph != ''){
            wpjobportal::$_data['shortcode_option_hide_graph'] = 1;
        }

        //handle attirbute for hide profile section on dashboard
        $hide_newest_jobs = WPJOBPORTALrequest::getVar('hide_newest_jobs', 'shortcode_option', false);
        if($hide_newest_jobs && $hide_newest_jobs != ''){
            wpjobportal::$_data['shortcode_option_hide_newest_jobs'] = 1;
        }

        //handle attirbute for hide profile section on dashboard
        $hide_job_applies = WPJOBPORTALrequest::getVar('hide_job_applies', 'shortcode_option', false);
        if($hide_job_applies && $hide_job_applies != ''){
            wpjobportal::$_data['shortcode_option_hide_job_applies'] = 1;
        }

        //handle attirbute for hide profile section on dashboard
        $hide_stat_boxes = WPJOBPORTALrequest::getVar('hide_stat_boxes', 'shortcode_option', false);
        if($hide_stat_boxes && $hide_stat_boxes != ''){
            wpjobportal::$_data['shortcode_option_hide_stat_boxes'] = 1;
        }

        //handle attirbute for hide profile section on dashboard
        $hide_invoices = WPJOBPORTALrequest::getVar('hide_invoices', 'shortcode_option', false);
        if($hide_invoices && $hide_invoices != ''){
            wpjobportal::$_data['shortcode_option_hide_invoices'] = 1;
        }

    }

}

?>
