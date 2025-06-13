<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALactivitylogModel {

    var $_siteurl = null;

    function __construct() {

        $this->_siteurl = site_url();
    }

    function storeActivity($flag, $tablename, $columns, $id = null) {
        if ($id == null) {
            $id = $columns['id'];
        }
        if (!is_numeric($id))
            return false;

        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $uid = ($uid != null) ? $uid : 0;
        $text = $this->getActivityDescription($flag, $tablename, $uid, $columns, $id);

        if ($text == false) {
            return;
        }
        $desc = $text[1];
        $name = $text[0];
        $created = gmdate("Y-m-d H:i:s");

        $data = array();
        $data['description'] = $desc;
        $data['referencefor'] = $name;
        $data['referenceid'] = $id;
        $data['uid'] = $uid;
        $data['created'] = $created;

        wpjobportal::$_db->insert(wpjobportal::$_db->prefix.'wj_portal_activitylog',$data);
        return WPJOBPORTAL_SAVED;
    }

    function storeActivityLogForActionDelete($text, $id) {
        if (!is_numeric($id))
            return false;
        if ($text == false)
            return;
        $name = $text[0];
        $desc = $text[1];
        $uid = $text[2];
        $uid = $uid != null ? $uid : 0;
        $created = gmdate("Y-m-d H:i:s");

        $data = array();
        $data['description'] = $desc;
        $data['referencefor'] = $name;
        $data['referenceid'] = $id;
        $data['uid'] = $uid;
        $data['created'] = $created;


        wpjobportal::$_db->insert(wpjobportal::$_db->prefix.'wj_portal_activitylog',$data);
        return WPJOBPORTAL_SAVED;
    }

    function sorting() {
        $pagenum = WPJOBPORTALrequest::getVar('pagenum');
        wpjobportal::$_data['sorton'] = wpjobportal::$_search['jobs']['sorton'];
        wpjobportal::$_data['sortby'] = wpjobportal::$_search['jobs']['sortby'];

        switch (wpjobportal::$_data['sorton']) {
            case 1: // created
                wpjobportal::$_data['sorting'] = ' act.id ';
                break;
            case 2: // company name
                wpjobportal::$_data['sorting'] = ' u.first_name ';
                break;
            case 3: // category
                wpjobportal::$_data['sorting'] = ' act.referencefor ';
                break;
            case 4: // location
            default: // location
                wpjobportal::$_data['sorting'] = ' act.created ';
                break;
        }
        if (wpjobportal::$_data['sortby'] == 1) {
            wpjobportal::$_data['sorting'] .= ' ASC ';
        } else {
            wpjobportal::$_data['sorting'] .= ' DESC ';
        }
        wpjobportal::$_data['combosort'] = wpjobportal::$_data['sorton'];
    }

    function getAllActivities() {
        $this->sorting();

        $data = WPJOBPORTALrequest::getVar('filter');

        $string = '';
        $comma = '';
        if (isset($data['age'])) {
            $string .= $comma . '"ages"';
            $comma = ',';
        }
        if (isset($data['job'])) {
            $string .= $comma . '"jobs"';
            $comma = ',';
        }
        if (isset($data['coverletter'])) {
            $string .= $comma . '"coverletters"';
            $comma = ',';
        }
        if (isset($data['careerlevel'])) {
            $string .= $comma . '"careerlevels"';
            $comma = ',';
        }
        if (isset($data['city'])) {
            $string .= $comma . '"cities"';
            $comma = ',';
        }
        if (isset($data['state'])) {
            $string .= $comma . '"states"';
            $comma = ',';
        }
        if (isset($data['country'])) {
            $string .= $comma . '"countries"';
            $comma = ',';
        }
        if (isset($data['category'])) {
            $string .= $comma . '"categories"';
            $comma = ',';
        }
        if (isset($data['currency'])) {
            $string .= $comma . '"currencies"';
            $comma = ',';
        }
        if (isset($data['customfield'])) {
            $string .= $comma . '"userfields"';
            $comma = ',';
        }
        if (isset($data['emailtemplate'])) {
            $string .= $comma . '"emailtemplates"';
            $comma = ',';
        }
        if (isset($data['experience'])) {
            $string .= $comma . '"experiences"';
            $comma = ',';
        }
        if (isset($data['highesteducation'])) {
            $string .= $comma . '"heighesteducation"';
            $comma = ',';
        }
        if (isset($data['company'])) {
            $string .= $comma . '"companies"';
            $comma = ',';
        }
        if (isset($data['jobstatus'])) {
            $string .= $comma . '"jobstatus"';
            $comma = ',';
        }
        if (isset($data['jobtype'])) {
            $string .= $comma . '"jobtypes"';
            $comma = ',';
        }
        if (isset($data['salaryrangetype'])) {
            $string .= $comma . '"salaryrangetypes"';
            $comma = ',';
        }
        if (isset($data['salaryrange'])) {
            $string .= $comma . '"salaryrange"';
            $comma = ',';
        }
        if (isset($data['shift'])) {
            $string .= $comma . '"shifts"';
            $comma = ',';
        }
        if (isset($data['resume'])) {
            $string .= $comma . '"resume"';
            $comma = ',';
        }
        if (isset($data['resumesearches'])) {
            $string .= $comma . '"resumesearches"';
            $comma = ',';
        }
        if (isset($data['jobsearch'])) {
            $string .= $comma . '"jobsearches"';
            $comma = ',';
        }

        if (isset($data['jobapply'])) {
            $string .= $comma . '"jobapply"';
            $comma = ',';
        }

        if (isset($data['department'])) {
            $string .= $comma . '"department"';
            $comma = ',';
        }

        $inquery = " ";

        $searchsubmit = WPJOBPORTALrequest::getVar('searchsubmit');
        if(!empty($searchsubmit) AND $searchsubmit == 1){
            // $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config`
            //     set configvalue = '".$string."' WHERE configname = 'activity_log_filter'";
            // wpjobportal::$_db->query($query);
            update_option('wp_job_portal_activity_log_filter',wp_json_encode($string));
        }

        //$activity_log_filter = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('activity_log_filter');
        $activity_log_filter = '';
        $option_value = get_option('wp_job_portal_activity_log_filter');
        if($option_value !=''){
            $activity_log_filter = json_decode($option_value);
        }


        if ($string != '') {
            $inquery = "WHERE act.referencefor IN (".$string.") ";
        } else if ($activity_log_filter != null) {

            $data = array();
            $string = $activity_log_filter;
            $inquery = "WHERE act.referencefor IN (".$string.") ";
            //showing check boxes checked
            $array = wpjobportalphplib::wpJP_explode(',', $string);
            foreach ($array as $var) {
                switch ($var) {
                    case '"ages"':
                        $data['age'] = 1;
                        break;
                    case '"careerlevels"':
                        $data['careerlevel'] = 1;
                        break;
                    case '"coverletters"':
                        $data['coverletter'] = 1;
                        break;
                    case '"currencies"':
                        $data['currency'] = 1;
                        break;
                    case '"experiences"':
                        $data['experience'] = 1;
                        break;
                    case '"heighesteducation"':
                        $data['highesteducation'] = 1;
                        break;
                    case '"jobs"':
                        $data['job'] = 1;
                        break;
                    case '"jobstatus"':
                        $data['jobstatus'] = 1;
                        break;
                    case '"jobtypes"':
                        $data['jobtype'] = 1;
                        break;
                    case '"salaryrangetypes"':
                        $data['salaryrangetype'] = 1;
                        break;
                    case '"userfields"':
                        $data['customfield'] = 1;
                        break;
                    case '"shifts"':
                        $data['shift'] = 1;
                        break;
                    case '"emailtemplates"':
                        $data['emailtemplate'] = 1;
                        break;
                    case '"companies"':
                        $data['company'] = 1;
                        break;
                    case '"countries"':
                        $data['country'] = 1;
                        break;
                    case '"states"':
                        $data['state'] = 1;
                        break;
                    case '"department"':
                        $data['department'] = 1;
                        break;
                    case '"cities"':
                        $data['city'] = 1;
                        break;
                    case '"resume"':
                        $data['resume'] = 1;
                        break;
                    case '"jobsearches"':
                        $data['jobsearch'] = 1;
                        break;
                    case '"resumesearches"':
                        $data['resumesearches'] = 1;
                        break;
                    case '"categories"':
                        $data['category'] = 1;
                        break;
                    case '"salaryrange"':
                        $data['salaryrange'] = 1;
                        break;
                    case '"jobapply"':
                        $data['jobapply'] = 1;
                        break;
                }
            }
        }

        wpjobportal::$_data['filter']['age'] = isset($data['age']) ? 1 : 0;
        wpjobportal::$_data['filter']['job'] = isset($data['job']) ? 1 : 0;
        wpjobportal::$_data['filter']['company'] = isset($data['company']) ? 1 : 0;
        wpjobportal::$_data['filter']['careerlevel'] = isset($data['careerlevel']) ? 1 : 0;
        wpjobportal::$_data['filter']['city'] = isset($data['city']) ? 1 : 0;
        wpjobportal::$_data['filter']['state'] = isset($data['state']) ? 1 : 0;
        wpjobportal::$_data['filter']['country'] = isset($data['country']) ? 1 : 0;
        wpjobportal::$_data['filter']['category'] = isset($data['category']) ? 1 : 0;
        wpjobportal::$_data['filter']['currency'] = isset($data['currency']) ? 1 : 0;
        wpjobportal::$_data['filter']['customfield'] = isset($data['customfield']) ? 1 : 0;
        wpjobportal::$_data['filter']['emailtemplate'] = isset($data['emailtemplate']) ? 1 : 0;
        wpjobportal::$_data['filter']['experience'] = isset($data['experience']) ? 1 : 0;
        wpjobportal::$_data['filter']['highesteducation'] = isset($data['highesteducation']) ? 1 : 0;
        wpjobportal::$_data['filter']['coverletter'] = isset($data['coverletter']) ? 1 : 0;
        wpjobportal::$_data['filter']['jobstatus'] = isset($data['jobstatus']) ? 1 : 0;
        wpjobportal::$_data['filter']['jobtype'] = isset($data['jobtype']) ? 1 : 0;
        wpjobportal::$_data['filter']['salaryrangetype'] = isset($data['salaryrangetype']) ? 1 : 0;
        wpjobportal::$_data['filter']['salaryrange'] = isset($data['salaryrange']) ? 1 : 0;
        wpjobportal::$_data['filter']['shift'] = isset($data['shift']) ? 1 : 0;
        wpjobportal::$_data['filter']['department'] = isset($data['department']) ? 1 : 0;
        wpjobportal::$_data['filter']['resume'] = isset($data['resume']) ? 1 : 0;
        wpjobportal::$_data['filter']['resumesearches'] = isset($data['resumesearches']) ? 1 : 0;
        wpjobportal::$_data['filter']['jobsearch'] = isset($data['jobsearch']) ? 1 : 0;
        wpjobportal::$_data['filter']['jobapply'] = isset($data['jobapply']) ? 1 : 0;

        $query = "SELECT COUNT(act.id)
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_activitylog` AS act
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_users` AS u ON u.id = act.uid " . $inquery;
        $total = wpjobportaldb::get_var($query);
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        $query = "SELECT act.description,act.created,act.id,act.referencefor,u.first_name,u.last_name
        FROM `" . wpjobportal::$_db->prefix . "wj_portal_activitylog` AS act
        LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_users` AS u ON u.id = act.uid " . $inquery;
        $query .= "ORDER BY " . wpjobportal::$_data['sorting'];
        $query .=" LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;
        $result = wpjobportal::$_db->get_results($query);

        wpjobportal::$_data[0] = $result;
        return;
    }

    function getEntityNameOrTitle($id, $text, $tablename) {
        if (!is_numeric($id))
            return false;
        if ($text == '' OR $tablename == '')
            return false;

        if(!strstr($tablename, "wj_portal_") ){
            return false;
        }
        switch ($text) {
            case 'title':
            case 'templatefor':
            case 'name':
            case "CONCAT(first_name, ' ', last_name) AS Name":
            case 'searchname':
            case 'cat_title':
            case 'rangestart':
                $query = "SELECT $text FROM `$tablename` WHERE id = " . esc_sql($id);
                $result = wpjobportal::$_db->get_var($query);
                return $result;
            break;

        }
        return false;

    }

    function getJobTitleFromid($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT title FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE id =" . esc_sql($id);
        $result = wpjobportal::$_db->get_var($query);
        return $result;
    }

    function getReusmeTitleFromid($id) {
        if (!is_numeric($id))
            return false;
        $query = "SELECT CONCAT(first_name, ' ', last_name) AS Name FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE id = " . esc_sql($id);
        $result = wpjobportal::$_db->get_var($query);
        return $result;
    }

    function getEntityNameOrTitleForJobApply($id, $tablename) {
        if (!is_numeric($id))
            return false;
        if ($tablename == '')
            return false;
        $query = "SELECT cvid,jobid FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE id = " . esc_sql($id);
        $result = wpjobportal::$_db->get_row($query);
        $data = array();
        $data[0] = $result->jobid;
        $data[1] = $this->getJobTitleFromid($result->jobid);
        $data[2] = $result->cvid;
        $data[3] = $this->getReusmeTitleFromid($result->cvid);
        return $data;
    }

    function getActivityDescription($flag, $tablename, $uid, $columns, $id) {
        $array = wpjobportalphplib::wpJP_explode('_', $tablename);
        if (!is_numeric($uid))
            return false;

        $name = $array[count($array) - 1];
        $target = "_blank";
        switch ($name) {
            //all the tables which have title as column
            case 'ages':
                $entityname = esc_html(__('Age', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_age&wpjobportallt=formages&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'careerlevels':
                $entityname = esc_html(__('Career Level', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_careerlevel&wpjobportallt=formcareerlevels&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'coverletters':
                $entityname = esc_html(__('Cover Letter', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_coverletter&wpjobportallt=formcoverletter&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'currencies':
                $entityname = esc_html(__('Currency', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_currency&wpjobportallt=formcurrency&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'experiences':
                $entityname = esc_html(__('Experience', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_experience&wpjobportallt=formexperience&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'heighesteducation':
                $entityname = esc_html(__('Education', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_highesteducation&wpjobportallt=formhighesteducation&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'jobs':
                $entityname = esc_html(__('Job', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_job&wpjobportallt=formjob&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'jobstatus':
                $entityname = esc_html(__('Job Status', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_jobstatus&wpjobportallt=formjobstatus&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'jobtypes':
                $entityname = esc_html(__('Job Type', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_jobtype&wpjobportallt=formjobtype&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'salaryrangetypes':
                $entityname = esc_html(__('Salary Range Type', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_salaryrangetype&wpjobportallt=formsalaryrangetype&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'userfields':
                $entityname = esc_html(__('Salary Range Type', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_customfield&wpjobportallt=formcustomfield&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'shifts':
                $entityname = esc_html(__('Shift', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['title'] : $this->getEntityNameOrTitle($id, 'title', $tablename);
                $path = "?page=wpjobportal_shift&wpjobportallt=formshift&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'emailtemplates':
                $entityname = esc_html(__('Email Template', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['templatefor'] : $this->getEntityNameOrTitle($id, 'templatefor', $tablename);
                $path = "?page=wpjobportal_emailtemplate&wpjobportallt=formemailtemplte&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            //tables that have name as column
            case 'companies':
                $entityname = esc_html(__('Company', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['name'] : $this->getEntityNameOrTitle($id, 'name', $tablename);
                $path = "?page=wpjobportal_company&wpjobportallt=formcompany&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'countries':
                $entityname = esc_html(__('Country', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['name'] : $this->getEntityNameOrTitle($id, 'name', $tablename);
                $path = "?page=wpjobportal_country&wpjobportallt=formcountry&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'folders':
                $entityname = esc_html(__('Folder', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['name'] : $this->getEntityNameOrTitle($id, 'name', $tablename);
                $path = "?page=wpjobportal_folder&wpjobportallt=formfolder&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'states':
                $entityname = esc_html(__('Department', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['name'] : $this->getEntityNameOrTitle($id, 'name', $tablename);
                $path = "?page=wpjobportal_state&wpjobportallt=formstate&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'departments':
                $entityname = esc_html(__('Department', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['name'] : $this->getEntityNameOrTitle($id, 'name', $tablename);
                $path = "?page=wpjobportal_departments&wpjobportallt=formdepartment&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'cities':
                $entityname = esc_html(__('City', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['name'] : $this->getEntityNameOrTitle($id, 'name', $tablename);
                $path = "?page=wpjobportal_city&wpjobportallt=formcity&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            //speceial case
            case 'resume':
                $entityname = esc_html(__('Resume', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, "CONCAT(first_name, ' ', last_name) AS Name", wpjobportal::$_db->prefix.'wj_portal_resume');
                $path = "?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'jobsearches':
                $entityname = esc_html(__('Job Search', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['searchname'] : $this->getEntityNameOrTitle($id, 'searchname', $tablename);
                $path = "?page=wpjobportal_jobsearch";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'resumesearches':
                $entityname = esc_html(__('Resume Search', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['searchname'] : $this->getEntityNameOrTitle($id, 'searchname', $tablename);
                $path = "?page=wpjobportal_resumesearch";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'categories':
                $entityname = esc_html(__('Category', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['cat_title'] : $this->getEntityNameOrTitle($id, 'cat_title', $tablename);
                $path = "?page=wpjobportal_category&wpjobportallt=formcategory&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'salaryrange':
                $entityname = esc_html(__('Salary Range', 'wp-job-portal'));
                $linktext = $flag == 1 ? $columns['rangestart'] : $this->getEntityNameOrTitle($id, 'rangestart', $tablename);
                $path = "?page=wpjobportal_salaryrange&wpjobportallt=formsalaryrange&wpjobportalid=$id";
                $html = "<a href=" . esc_url($path) . " target='".esc_attr($target)."''><strong>" . esc_html($linktext) . "</strong></a>";
                break;
            case 'jobapply':
                $entityname = esc_html(__('Applied for job', 'wp-job-portal'));
                $data = $this->getEntityNameOrTitleForJobApply($id, $tablename);

                $path1 = "?page=wpjobportal_job&wpjobportallt=formjob&wpjobportalid=$data[0]";
                $path2 = "?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid=$data[2]";
                $html = " ( <a href=" . esc_url($path1) . " target='".esc_attr($target)."''><strong>" . $data[1] . "</strong></a> ) ";
                $html .= esc_html(__('With Resume', 'wp-job-portal'));
                $html .= " ( <a href=" . esc_url($path2) . " target='".esc_attr($target)."''><strong>" . $data[3] . "</strong></a> ) ";
                break;
            default:
                return false;
                break;
        }
        $username = $this->getNameFromUid($uid);
        $path2 = esc_url_raw(admin_url('admin.php?page=wpjobportal_user&wpjobportallt=userdetail&id='.esc_attr($uid)));
        if(current_user_can('manage_options')){
            $html2 = esc_html(__('Administrator','wp-job-portal'));
        }else{
            $html2 = "<a href=" . $path2 . " target='".esc_attr($target)."''><strong>" . esc_html($username) . "</strong></a>";
        }
        $entityaction = $flag == 1 ? esc_html(__("added a new", "wp-job-portal")) : esc_html(__("Edited a existing", "wp-job-portal"));
        $result = array();
        $result[0] = $name;
        if ($name == 'jobapply') {
            $result[1] = "$html2" . "  " . $entityname . " " . $html;
        } elseif ($name == 'jobshortlist') {
            $result[1] = "$html2" . "  " . $entityname . " " . $html;
        } else {
            $result[1] = "$html2" . " " . $entityaction . " " . $entityname . " ( " . $html . " )";
        }
        return $result;
    }

    function getNameFromUid($uid) {
        if (!is_numeric($uid))
            return false;
        if ($uid == 0) {
            return "guest";
        }
        $query = "SELECT first_name,last_name FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE id = " . esc_sql($uid);
        $result = wpjobportal::$_db->get_row($query);
        $name = $result->first_name . ' ' . $result->last_name;
        return $name;
    }

    function getDeleteActionDataToStore($tablename, $id) {
        $array = wpjobportalphplib::wpJP_explode('_', $tablename);
        $name = $array[count($array) - 1];
        switch ($name) {
            //all the tables which have title as column
            case 'ages':
                $entityname = esc_html(__('Age', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'careerlevels':
                $entityname = esc_html(__('Career Level', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'coverletters':
                $entityname = esc_html(__('Cover Letter', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'currencies':
                $entityname = esc_html(__('Currency', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'experiences':
                $entityname = esc_html(__('Experience', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'heighesteducation':
                $entityname = esc_html(__('Education', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'jobs':
                $entityname = esc_html(__('Job', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'jobstatus':
                $entityname = esc_html(__('Job Status', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'jobtypes':
                $entityname = esc_html(__('Job Type', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'salaryrangetypes':
                $entityname = esc_html(__('Salary Range Type', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'userfields':
                $entityname = esc_html(__('Salary Range Type', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'shifts':
                $entityname = esc_html(__('Shift', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'title', $tablename);
                break;
            case 'emailtemplates':
                $entityname = esc_html(__('Email Template', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'templatefor', $tablename);
                break;
            //tables that have name as column
            case 'companies':
                $entityname = esc_html(__('Company', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'name', $tablename);
                break;
            case 'countries':
                $entityname = esc_html(__('Country', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'name', $tablename);
                break;
            case 'states':
                $entityname = esc_html(__('State', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'name', $tablename);
                break;
            case 'departments':
                $entityname = esc_html(__('Department', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'name', $tablename);
                break;
            case 'cities':
                $entityname = esc_html(__('City', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'name', $tablename);
                break;
            //speceial case
            case 'resume':
                $entityname = esc_html(__('Resume', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, "CONCAT(first_name, ' ', last_name) AS Name", $tablename);
                break;
            case 'jobsearches':
                $entityname = esc_html(__('Job Search', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'searchname', $tablename);
                break;
            case 'resumesearches':
                $entityname = esc_html(__('Resume Search', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'searchname', $tablename);
                break;
            case 'categories':
                $entityname = esc_html(__('Category', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'cat_title', $tablename);
                break;
            case 'salaryrange':
                $entityname = esc_html(__('Salary Range', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitle($id, 'rangestart', $tablename);
                break;
            case 'jobapply':
                $entityname = esc_html(__('Applied for job', 'wp-job-portal'));
                $linktext = $this->getEntityNameOrTitleForJobApply($id, $tablename);
                break;
            default:
                return false;
                break;
        }
        $target = "_blank";
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $username = $this->getNameFromUid($uid);
        $path2 = esc_url_raw(admin_url('admin.php?page=wpjobportal_user&wpjobportallt=userdetail&id='.esc_attr($uid)));
        $html2 = "<a href=" . $path2 . " target='".esc_attr($target)."''><strong>" . $username . "</strong></a>";
        $entityaction = esc_html(__("Deleted a", "wp-job-portal"));
        $result = array();
        $result[0] = $name;
        $result[1] = "$html2" . " " . $entityaction . " " . $entityname . " ( " . esc_html($linktext) . " )";
        $result[2] = $uid;

        return $result;
    }

    function getMessagekey(){
        $key = 'activitylog';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
