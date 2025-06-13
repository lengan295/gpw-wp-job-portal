<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALEmployerModel {
    // if not use than remove
    function getEmployerCpTabData($uid){
        if(!is_numeric($uid)) return;
        $query="select res.id,job.title as jobtitle,job.id AS jobid,ja.action_status AS applystatus,res.application_title AS resumetitle,
                CONCAT(res.first_name,' ',res.last_name) AS resumename ,ja.apply_date AS jobaplly ,
                res.photo
                from " . wpjobportal::$_db->prefix . "wj_portal_resume AS res
                join  " . wpjobportal::$_db->prefix . "wj_portal_jobapply AS ja on res.id=ja.cvid
                join " . wpjobportal::$_db->prefix . "wj_portal_jobs AS job on ja.jobid=job.id
                where job.uid=".esc_sql($uid)." GROUP BY job.id ORDER BY jobaplly DESC LIMIT 5 ";
        $applied_jobs = wpjobportaldb::get_results($query);
        if(!empty($applied_jobs)){
            wpjobportal::$_data[0]['cpappliedresume'] = wpjobportaldb::get_results($query);
        }
        return;
    }
    // if not use than remove
    function getNewestResumeForEmployer($guestflag) {
        if($guestflag == false){
            $query = "SELECT resume.id,resume.first_name,resume.last_name,resume.application_title,resume.email_address,category.cat_title,resume.created,jobtype.title AS jobtypetitle,resume.photo
                ,resume.status,city.name AS cityname,state.name AS statename,country.name AS countryname
                ,resume.params,resume.last_modified,LOWER(jobtype.title) AS jobtypetit
                ,'resumeaddress' AS address_city
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS category ON category.id = resume.job_category
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = resume.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON city.id = (SELECT address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = resume.id LIMIT 1)
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_states` AS state ON state.id = city.stateid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_countries` AS country ON country.id = city.countryid
                WHERE resume.status = 1 ORDER BY resume.created desc LIMIT 0,5 ";
            $results = wpjobportal::$_db->get_results($query);
            $data = array();
            foreach ($results AS $d) {
              $d->location = WPJOBPORTALincluder::getJSModel('common')->getLocationForView($d->cityname, $d->statename, $d->countryname);
                $data[] = $d;
            }
            wpjobportal::$_data[0]['newestresume'] = $data;
        }
        wpjobportal::$_data['config'] = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('emcontrolpanel');
    }
    // if not use than remove
    function getApplliedResumeBYUid($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT resume.id, resume.photo,resume.application_title , resume.email_address,resume.first_name,resume.last_name,cat.cat_title,resumeaddress.address_city,jobapply.quick_apply
                    From " . wpjobportal::$_db->prefix . "wj_portal_jobapply AS jobapply
                    JOIN " . wpjobportal::$_db->prefix . "wj_portal_jobs AS job ON job.id = jobapply.jobid
                    JOIN " . wpjobportal::$_db->prefix . "wj_portal_resume AS resume ON resume.id = jobapply.cvid
                    LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_categories AS cat ON cat.id = resume.job_category
                    LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses AS resumeaddress ON resume.id=resumeaddress.resumeid
                    WHERE job.uid = " . esc_sql($uid) . " LIMIT 0,3";
        wpjobportal::$_data[0]['appliedresume'] = wpjobportaldb::get_results($query);
    }
    // if not use than remove
   function getLatestResumeIdNew($uid){
        if(!is_numeric($uid))
            return false;
        $query = "SELECT DISTINCT apply.jobid,company.id as companyid,jobs.title as title,company.uid as userid,apply.quick_apply
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` as apply
                    JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` as jobs on apply.jobid=jobs.id
                    ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` as company ON company.id=jobs.companyid
                    WHERE company.uid='".esc_sql($uid)."' ORDER BY apply.jobid DESC limit 0,2 ";
        $result = wpjobportaldb::get_results($query);
        $jobtype = $result;
        wpjobportal::$_data[0]['jobid'] = $jobtype;
        $i=0;
        //////******To Get Job Wise Resume****///////
        foreach ($jobtype as $key) {
            if(!is_numeric($key->jobid)){
                continue;
            }
            $query = "SELECT app.uid AS jobseekerid,company.uid AS employerid,jobapply.id AS jobapplyid ,job.id,job.uid as userid
                , cat.cat_title ,jobapply.apply_date, jobapply.resumeview, jobtype.title AS jobtypetitle
                ,app.id AS appid,app.id AS id,app.first_name,app.last_name, app.email_address,app.gender,app.isfeaturedresume,app.endfeatureddate,app.params,
                app.jobtype AS jobtype
                ,app.id AS resumeid ,job.hits AS jobview,app.last_modified,app.salaryfixed AS salary,app.skills
                ,(SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE jobid = job.id) AS totalapply
                ,(SELECT address_city FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE resumeid = app.id ORDER BY created DESC LIMIT 1) AS resumecity,app.photo AS photo,app.application_title AS applicationtitle
                ,CONCAT(app.alias,'-',app.id) resumealiasid, CONCAT(job.alias,'-',job.id) AS jobaliasid
                ,( Select rinsitute.institute From`" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` AS rinsitute Where rinsitute.resumeid = app.id LIMIT 1 ) AS institute
                ,( Select rinsitute.institute_study_area From`" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` AS rinsitute Where rinsitute.resumeid = app.id LIMIT 1 ) AS institute_study_area
                ,job.companyid,jobtype.color as jobtypecolor,app.quick_apply
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply  ON jobapply.jobid = job.id
                JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS app ON app.id = jobapply.cvid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = app.job_category
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = app.jobtype
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
            WHERE jobapply.jobid = " . esc_sql($key->jobid);
            $result = wpjobportaldb::get_results($query);
            $data = array();
            foreach ($result AS $d) {
                $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->resumecity);
                $d->jobtype = WPJOBPORTALincluder::getJSModel('job')->getjobType($d->jobtype);
                $data[] = $d;
            }
            wpjobportal::$_data[0]['data'][$key->jobid] = $data;
        }
    }

    function getEmployerinfo($uid){
        if (!is_numeric($uid))
            return false;
        $query = "SELECT COUNT(*) as record,company.id,company.name,company.logofilename,CONCAT(company.alias,'-',company.id) AS aliasid,company.created,company.serverid,company.city,company.status,company.isfeaturedcompany
                 ,company.endfeatureddate,company.tagline,company.url
                FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
                WHERE company.uid = " . esc_sql($uid);
        if(wpjobportal::$theme_chk == 1){
            $query .= " ORDER BY company.created DESC LIMIT 0,5";
            // setting data to $_data[0] was causing issues
            $result_data = wpjobportaldb::get_results($query);
            $data = array();
            foreach ($result_data AS $d) {
                $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city); 
                $data[] = $d;
            }
            wpjobportal::$_data[0]['companies'] = $data;
        }else{
            $query .= " ORDER BY company.created DESC LIMIT 0,1";
            wpjobportal::$_data[0]['companies'] = wpjobportaldb::get_results($query);
        }
        wpjobportal::$_data[0]['company']['emp_profile'] = WPJOBPORTALincluder::getObjectClass('user')->getEmployerProfile();

        if(wpjobportal::$theme_chk == 1){
            wpjobportal::$_data['config'] = wpjobportal::$_config->getConfigByFor('company');
            $query = "SELECT job.endfeatureddate,job.id,job.uid,job.title,job.isfeaturedjob,job.serverid,job.noofjobs,job.city,job.status,
                CONCAT(job.alias,'-',job.id) AS jobaliasid,job.created,job.serverid,company.name AS companyname,company.id AS companyid,company.logofilename,CONCAT(company.alias,'-',company.id) AS compnayaliasid,job.salarytype,job.salarymin,job.salarymax,salaryrangetype.title AS salarydurationtitle,job.currency,
                cat.cat_title, jobtype.title AS jobtypetitle,salaryrangetype.title AS srangetypetitle,
                (SELECT count(jobapply.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply
                 WHERE jobapply.jobid = job.id and jobapply.status = 1) AS resumeapplied ,job.params,job.startpublishing,job.stoppublishing
                 ,LOWER(jobtype.title) AS jobtypetit,jobtype.color AS jobtypecolor
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
                ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city ON job.city = city.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_salaryrangetypes` AS salaryrangetype ON salaryrangetype.id = job.salaryduration
                WHERE job.uid =". esc_sql($uid) ;
                # Sorting Merge In Query
                $query.= " ORDER BY job.created DESC";
                $query.=" LIMIT 0,5";
                $results = wpjobportaldb::get_results($query);
                $data = array();
                foreach ($results AS $d) {
                    $d->location = WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($d->city);
                    $data[] = $d;
                }
                $results = $data;
                wpjobportal::$_data[0]['myjobs'] = $data;
                wpjobportal::$_data[0]['myjobs'] = $results;
            if(in_array('message', wpjobportal::$_active_addons)){
                $query = "SELECT m.id,m.created,m.serverid,m.subject AS msgsubject,m.message,m.isread,job.title AS jobtitle,company.name AS companyname,company.id AS companyid,job.id AS jobid,resume.quick_apply,
                        (SELECT count(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_messages` WHERE replytoid = m.id) + 1 AS totalmessages,
                        (SELECT count(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_messages` WHERE replytoid = m.id and isread = 0) AS unreadmessages,resume.id AS resumeid,resume.first_name,resume.last_name,resume.application_title,resume.photo,m.created
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_messages` AS m
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume ON resume.id = m.resumeid
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON job.id = m.jobid
                        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                        WHERE m.status = 1 AND m.employerid = " . esc_sql($uid) . " AND m.replytoid = 0 LIMIT 0,5";
                $messages = wpjobportaldb::get_results($query);
                wpjobportal::$_data[0]['mymessages'] = $messages;
            }

            $query = "SELECT resume.id,job.uid,job.title,job.jobtype,resume.application_title AS applicationtitle,resume.photo,resume.first_name,resume.last_name,resume.endfeatureddate,resume.isfeaturedresume,jobtype.title AS jobtypetitle,jobtype.color as jobtypecolor,resume.quick_apply
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype ON jobtype.id = job.jobtype
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply ON jobapply.jobid = job.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume ON jobapply.cvid = resume.id
                WHERE jobapply.status = 1 and job.uid =". esc_sql($uid) ;
                # Sorting Merge In Query
                $query.= " ORDER BY job.created DESC";
                $query.=" LIMIT 0,5";
            $myappliedresume = wpjobportaldb::get_results($query);
            wpjobportal::$_data[0]['myappliedresume'] = $myappliedresume;
        }
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` where uid = ".esc_sql($uid)." and status = 1";
        wpjobportal::$_data['totaljobs'] = wpjobportal::$_db->get_var($query);
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` where `uid` = ".esc_sql($uid)." AND status = 1";
        wpjobportal::$_data['totalcompanies'] = wpjobportal::$_db->get_var($query);
        if(!in_array('multicompany', wpjobportal::$_active_addons) && wpjobportal::$_data['totalcompanies'] > 1){
            wpjobportal::$_data['totalcompanies'] = 1;
        }
        $query = "SELECT count(jobapply.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS jobapply
                    JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON jobapply.jobid = job.id AND job.uid = ".esc_sql($uid)." AND jobapply.status = 1";
        wpjobportal::$_data['totaljobapply'] = wpjobportal::$_db->get_var($query);
        if(in_array('resumesearch', wpjobportal::$_active_addons)){
            $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumesearches` where status = 1  and uid =". esc_sql($uid);
            wpjobportal::$_data['totalresumesearch'] = wpjobportal::$_db->get_var($query);
        }
    }

    function getDataForDashboard($uid){
        wpjobportal::$_data[0]['invoices'] = apply_filters('wpjobportal_addons_cp_invoices_detail',false,$uid,6);
    }

    function getGraphDataNew($uid){

        $query = "SELECT * FROM `".wpjobportal::$_db->prefix."wj_portal_jobtypes`
         WHERE `id`>0 LIMIT 0,3";
         $data = wpjobportaldb::get_results($query);
         wpjobportal::$_data['jobtype']=$data;
         $html = "['" . esc_html(__('Dates', 'wp-job-portal')) . "'";
         ///Days Section In Graph
          foreach (wpjobportal::$_data['jobtype'] as $key ) {
            $html .= ",'". wpjobportal::wpjobportal_getVariableValue($key->title)."'";
            $jobtype[] = $key->id;
        }
        $query = "SELECT count(jobapply.id) AS job,MONTH(jobapply.apply_date) AS MONTH, YEAR(jobapply.apply_date) AS YEAR ,type.id AS jobtype
            FROM `".wpjobportal::$_db->prefix."wj_portal_jobapply` AS jobapply
            JOIN `".wpjobportal::$_db->prefix."wj_portal_jobs` AS job ON job.id=jobapply.jobid
            LEFT JOIN `".wpjobportal::$_db->prefix."wj_portal_jobtypes` AS type ON job.jobtype=type.id";
            if($uid){
                $query .= " AND job.uid =". esc_sql($uid);
            }
        $query .= " GROUP by MONTH(jobapply.apply_date),YEAR(jobapply.apply_date),type.id ORDER BY YEAR(jobapply.apply_date),MONTH(jobapply.apply_date) ASC";

         $result = wpjobportaldb::get_results($query);
         wpjobportal::$_data[0]['title'] = $result;
         $prev_workstations = '';
         //////************Swapping If same jobtype than dont change in array*******///////
        foreach (wpjobportal::$_data['0']['title'] as $parent) {
            $crm_workstations = $parent->jobtype;
            if (($crm_workstations !='') && ($crm_workstations != $prev_workstations)){
                $prev_workstations = $crm_workstations;
               $crm_workstations;
            }
           if(wpjobportalphplib::wpJP_strlen($parent->MONTH) <= 1){
           $parent->MONTH = '0'.$parent->MONTH;
           }
            wpjobportal::$_data['datachart'][$crm_workstations][$parent->YEAR][$parent->MONTH] = $parent->job;
        }
        $html.="]";
        wpjobportal::$_data['stack_chart_horizontal']['data']='';
        wpjobportal::$_data['stack_chart_horizontal']['title'] = $html;
         ///////*****TO Show All Month Till Last Month ****////////
         for ($i=0; $i<=11; $i++) {
                $Date = gmdate('Y-m', strtotime("-$i month"));
                $Time = wpjobportalphplib::wpJP_explode('-',$Date);
                $Month = $Time[1];
                $Year = $Time[0];
                $dateObj = DateTime::createFromFormat('!m', $Month);
                $monthName = $dateObj->format('M');
                $MonthName = $monthName.'-'.wpjobportalphplib::wpJP_substr($Year,-2);
                /////******Passing Data To Graph*********//////////
                $FullTime = isset(wpjobportal::$_data['jobtype'][0]->id) ? wpjobportal::$_data['jobtype'][0]->id : null;
                $PartTime = isset(wpjobportal::$_data['jobtype'][1]->id) ? wpjobportal::$_data['jobtype'][1]->id : null;
                $internship = isset(wpjobportal::$_data['jobtype'][2]->id) ? wpjobportal::$_data['jobtype'][2]->id : null;
                wpjobportal::$_data['stack_chart_horizontal']['data'] .= "['" . $MonthName . "',";
                $FullTimeData = isset(wpjobportal::$_data['datachart'][$FullTime][$Year][$Month]) ? wpjobportal::$_data['datachart'][$FullTime][$Year][$Month] : 0;
                $ParTimeData = isset(wpjobportal::$_data['datachart'][$PartTime][$Year][$Month]) ? wpjobportal::$_data['datachart'][$PartTime][$Year][$Month] : 0;
                $internshipData = isset(wpjobportal::$_data['datachart'][$internship][$Year][$Month]) ? wpjobportal::$_data['datachart'][$internship][$Year][$Month] : 0;
                //Data Section For Graph
                wpjobportal::$_data['stack_chart_horizontal']['data'] .= "$FullTimeData,$ParTimeData,$internshipData]";
                if($i!=12){
                wpjobportal::$_data['stack_chart_horizontal']['data'] .= ',';
                }
        }
        return ;
    }

    // function to handle add/edit company button in left menu
    function getEmployerCompanyinfo($uid){
        if (!is_numeric($uid))
            return false;
        $query = "SELECT company.id,company.name,company.logofilename,CONCAT(company.alias,'-',company.id) AS aliasid,company.created,company.serverid,company.city,company.status,company.isfeaturedcompany
                 ,company.endfeatureddate,company.tagline,company.url
                FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
                WHERE company.uid = " . esc_sql($uid);
            $query .= " ORDER BY company.created DESC LIMIT 0,1";
            $company = wpjobportaldb::get_row($query);
            return $company;
    }

    function handleShortCodeOptions(){

        /*
        hide_profile_section' => '',
                    'hide_graph' => '',
                    'hide_recent_applications' => '',
                    'hide_stat_boxes' => '',
                    'hide_invoices' => '', */

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
        $hide_recent_applications = WPJOBPORTALrequest::getVar('hide_recent_applications', 'shortcode_option', false);
        if($hide_recent_applications && $hide_recent_applications != ''){
            wpjobportal::$_data['shortcode_option_hide_recent_applications'] = 1;
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

    function getMessagekey(){
        $key = 'employer';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }

}

?>
