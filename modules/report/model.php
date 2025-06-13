<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALReportModel {

    function getChartColor() {
        $colors = array('#3366CC', '#DC3912', '#FF9900', '#109618', '#990099', '#B77322', '#8B0707', '#AAAA11', '#316395', '#DD4477', '#3B3EAC', '#ADD042', '#9D98CA', '#ED3237', '#585570', '#4E5A62', '#5CC6D0');
        return $colors;
    }

    function getOverallReports() {
        //Line Chart Data
        $curdate = gmdate('Y-m-d');
        $dates = '';
        $fromdate = gmdate('Y-m-d', strtotime("now -1 month"));
        $nextdate = $curdate;
        //Query to get Data
        $query = "SELECT created FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE date(created) >= '" . esc_sql($fromdate) . "' AND date(created) <= '" . esc_sql($curdate) . "'";
        $jobs = wpjobportal::$_db->get_results($query);

        $query = "SELECT created FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs`";
        wpjobportal::$_data['tot_jobs'] =  wpjobportal::$_db->get_results($query);

        $query = "SELECT created FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE date(created) >= '" . esc_sql($fromdate) . "' AND date(created) <= '" . esc_sql($curdate) . "'";
        $resume = wpjobportal::$_db->get_results($query);

         $query = "SELECT count(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE status = 1 ";
        wpjobportal::$_data['presume'] = wpjobportal::$_db->get_var($query);

        $query = "SELECT created FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE date(created) >= '" . esc_sql($fromdate) . "' AND date(created) <= '" . esc_sql($curdate) . "'";
        $companies = wpjobportal::$_db->get_results($query);

         $query = "SELECT count(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` ";
        wpjobportal::$_data['tot_comp'] = wpjobportal::$_db->get_var($query);

        $query = "SELECT apply_date FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE date(apply_date) >= '" . esc_sql($fromdate) . "' AND date(apply_date) <= '" . esc_sql($curdate) . "'";
        $appliedresume = wpjobportal::$_db->get_results($query);

        $date_jobs = array();
        $date_companies = array();
        $date_resume = array();
        $date_appliedresume = array();
        foreach ($jobs AS $job) {
            if (!isset($date_jobs[date_i18n('Y-m-d', strtotime($job->created))]))
                $date_jobs[date_i18n('Y-m-d', strtotime($job->created))] = 0;
            $date_jobs[date_i18n('Y-m-d', strtotime($job->created))] = $date_jobs[date_i18n('Y-m-d', strtotime($job->created))] + 1;
        }
        foreach ($resume AS $rs) {
            if (!isset($date_resume[date_i18n('Y-m-d', strtotime($rs->created))]))
                $date_resume[date_i18n('Y-m-d', strtotime($rs->created))] = 0;
            $date_resume[date_i18n('Y-m-d', strtotime($rs->created))] = $date_resume[date_i18n('Y-m-d', strtotime($rs->created))] + 1;
        }
        foreach ($companies AS $company) {
            if (!isset($date_companies[date_i18n('Y-m-d', strtotime($company->created))]))
                $date_companies[date_i18n('Y-m-d', strtotime($company->created))] = 0;
            $date_companies[date_i18n('Y-m-d', strtotime($company->created))] = $date_companies[date_i18n('Y-m-d', strtotime($company->created))] + 1;
        }
        foreach ($appliedresume AS $ar) {
            if (!isset($date_appliedresume[date_i18n('Y-m-d', strtotime($ar->apply_date))]))
                $date_appliedresume[date_i18n('Y-m-d', strtotime($ar->apply_date))] = 0;
            $date_appliedresume[date_i18n('Y-m-d', strtotime($ar->apply_date))] = $date_appliedresume[date_i18n('Y-m-d', strtotime($ar->apply_date))] + 1;
        }
        $job_s = 0;
        $company_s = 0;
        $resume_s = 0;
        $appliedresume_s = 0;
        $json_array = "";

        do {
            $year = date_i18n('Y', strtotime($nextdate));
            $month = date_i18n('m', strtotime($nextdate));
            $month = $month - 1; //js month are 0 based
            $day = date_i18n('d', strtotime($nextdate));
            $job_tmp = isset($date_jobs[$nextdate]) ? $date_jobs[$nextdate] : 0;
            $resume_tmp = isset($date_resume[$nextdate]) ? $date_resume[$nextdate] : 0;
            $company_tmp = isset($date_companies[$nextdate]) ? $date_companies[$nextdate] : 0;
            $appliedresume_tmp = isset($date_appliedresume[$nextdate]) ? $date_appliedresume[$nextdate] : 0;
            $json_array .= "[new Date($year,$month,$day),$job_tmp,$resume_tmp,$company_tmp,$appliedresume_tmp],";
            $job_s += $job_tmp;
            $company_s += $company_tmp;
            $resume_s += $resume_tmp;
            $appliedresume_s += $appliedresume_tmp;
            if($nextdate == $fromdate){
                break;
            }
            $nextdate = date_i18n('Y-m-d', strtotime($nextdate . " -1 days"));
        } while ($nextdate != $fromdate);

        wpjobportal::$_data['totaljobs'] = $job_s;
        wpjobportal::$_data['totalcompany'] = $company_s;
        wpjobportal::$_data['totalresume'] = $resume_s;
        wpjobportal::$_data['totalappliedresume'] = $appliedresume_s;

        wpjobportal::$_data['line_chart_json_array'] = $json_array;

        $query = "SELECT cat.cat_title,(SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE jobcategory = cat.id) AS jobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                    ORDER BY jobs DESC LIMIT 5";
        $jobs = wpjobportal::$_db->get_results($query);
        /*$query = "SELECT cat.cat_title,(SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` ) AS companies
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                    ORDER BY companies DESC LIMIT 5";*/
       // $companies = wpjobportal::$_db->get_results($query);
        $query = "SELECT cat.cat_title,(SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE job_category = cat.id) AS resumes
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat
                    ORDER BY resumes DESC LIMIT 5";
        $resume = wpjobportal::$_db->get_results($query);
        wpjobportal::$_data['catbar1'] = '';
        wpjobportal::$_data['catbar2'] = '';
        wpjobportal::$_data['catpie'] = '';
        $colors = $this->getChartColor();
        for ($i = 0; $i < 5; $i++) {
            $job = $jobs[$i];
            /*$company = $companies[$i];*/
            $resum = $resume[$i];
            wpjobportal::$_data['catbar1'] .= "['" . wpjobportalphplib::wpJP_htmlspecialchars($job->cat_title) . "', " . $job->jobs . ", '" . $colors[$i] . "', '" . esc_html(__('Jobs', 'wp-job-portal')) . "' ],";
            wpjobportal::$_data['catbar2'] .= "['" . wpjobportalphplib::wpJP_htmlspecialchars($resum->cat_title) . "', " . $resum->resumes . ", '" . $colors[$i] . "', '" . esc_html(__('Jobs', 'wp-job-portal')) . "' ],";
            /*wpjobportal::$_data['catpie'] .= "['" . $company->cat_title . "', " . $company->companies . "],";*/
        }

        $query = "SELECT city.name,(SELECT COUNT(jobid) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` WHERE cityid = city.id ) AS jobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                    ORDER BY jobs DESC LIMIT 5";
        $jobs = wpjobportal::$_db->get_results($query);
        $query = "SELECT city.name,(SELECT COUNT(companyid) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companycities` WHERE cityid = city.id) AS companies
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                    ORDER BY companies DESC LIMIT 5";
        $companies = wpjobportal::$_db->get_results($query);
        $query = "SELECT city.name,(SELECT COUNT(resumeid) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` WHERE address_city = city.id) AS resumes
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_cities` AS city
                    ORDER BY resumes DESC LIMIT 5";
        $resume = wpjobportal::$_db->get_results($query);
        wpjobportal::$_data['citybar1'] = '';
        wpjobportal::$_data['citybar2'] = '';
        wpjobportal::$_data['citypie'] = '';
        for ($i = 0; $i < 5; $i++) {
            $job = $jobs[$i];
            $company = $companies[$i];
            $resum = $resume[$i];
            wpjobportal::$_data['citybar1'] .= "['" . wpjobportalphplib::wpJP_htmlspecialchars($job->name) . "', " . $job->jobs . ", '" . $colors[$i] . "', '" . esc_html(__('Jobs', 'wp-job-portal')) . "' ],";
            wpjobportal::$_data['citybar2'] .= "['" . wpjobportalphplib::wpJP_htmlspecialchars($resum->name) . "', " . $resum->resumes . ", '" . $colors[$i] . "', '" . esc_html(__('Jobs', 'wp-job-portal')) . "' ],";
            wpjobportal::$_data['citypie'] .= "['" . wpjobportalphplib::wpJP_htmlspecialchars($company->name) . "', " . $company->companies . "],";
        }

        $query = "SELECT jobtype.title,(SELECT COUNT(jobid) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE jobtype = jobtype.id ) AS jobs
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype
                    ORDER BY jobs DESC LIMIT 5";
        $jobs = wpjobportal::$_db->get_results($query);
        $query = "SELECT jobtype.title,(SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE jobtype = jobtype.id) AS resumes
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobtypes` AS jobtype
                    ORDER BY resumes DESC LIMIT 5";
        $resume = wpjobportal::$_db->get_results($query);
        wpjobportal::$_data['jobtypebar1'] = '';
        wpjobportal::$_data['jobtypebar2'] = '';
        for ($i = 0; $i < 5; $i++) {
            if (isset($jobs[$i]) && isset($jobs[$i])) {
                $job = $jobs[$i];
                $resum = $resume[$i];
                wpjobportal::$_data['jobtypebar1'] .= "['" . wpjobportalphplib::wpJP_htmlspecialchars($job->title) . "', " . $job->jobs . ", '" . $colors[$i] . "', '" . esc_html(__('Jobs', 'wp-job-portal')) . "' ],";
                wpjobportal::$_data['jobtypebar2'] .= "['" . wpjobportalphplib::wpJP_htmlspecialchars($resum->title) . "', " . $resum->resumes . ", '" . $colors[$i] . "', '" . esc_html(__('Jobs', 'wp-job-portal')) . "' ],";
            }
        }
    }

    function getMessagekey(){
        $key = 'report';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
