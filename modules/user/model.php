<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALUserModel {

    function jsGetPrefix(){
        global $wpdb;
        if(is_multisite()) {
            $prefix = $wpdb->base_prefix;
        }else{
            $prefix = wpjobportal::$_db->prefix;
        }
        return $prefix;
    }

       function getMyAvailableCredits($uid) {
            if (!is_numeric($uid))
            return false;
        $query = "SELECT purchase.purchasecredit AS credits,purchase.expireindays,purchase.created
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_purchasehistory` AS purchase
                    WHERE purchase.uid = ". esc_sql($uid)." AND purchase.transactionverified = 1 ORDER BY purchase.id ASC";
        $credits = wpjobportal::$_db->get_results($query);
        $totalcredits = 0;
        $expireindays = 7;
        $lastpurchase = '';
        foreach ($credits AS $credit) {
            $totalcredits += $credit->credits;
            $expireindays = $credit->expireindays;
            $lastpurchase = $credit->created;
        }
        if($expireindays > 7900) // php max limit
            $expireindays = 7900;

        $lastpurchasedate = gmdate('Y-m-d', strtotime($lastpurchase));
        $expirydate = gmdate('Y-m-d', strtotime($lastpurchasedate . " + $expireindays days"));
        $curdate = gmdate('Y-m-d');
        if ($expirydate > $curdate) { // credits are valid
            $query = "SELECT credits FROM `" . wpjobportal::$_db->prefix . "wj_portal_credits_log` WHERE uid = ". esc_sql($uid);
            $creditslog = wpjobportal::$_db->get_results($query);
            $totalusecredits = 0;
            foreach ($creditslog AS $log) {
                $totalusecredits += $log->credits;
            }
            $available = $totalcredits - $totalusecredits;
            return $available;
        } else { // credits are expired
            return 0;
        }
    }

    function getAllUsers() {

        //Filters
        $searchname = wpjobportal::$_search['user']['searchname'];
        $searchusername = wpjobportal::$_search['user']['searchusername'];
        $searchrole = wpjobportal::$_search['user']['searchrole'];
        $searchcompany = wpjobportal::$_search['user']['searchcompany'];
        $searchresume = wpjobportal::$_search['user']['searchresume'];

        wpjobportal::$_data['filter']['searchname'] = $searchname;
        wpjobportal::$_data['filter']['searchusername'] = $searchusername;
        wpjobportal::$_data['filter']['searchrole'] = $searchrole;
        wpjobportal::$_data['filter']['searchcompany'] = $searchcompany;
        wpjobportal::$_data['filter']['searchresume'] = $searchresume;

        $clause = " WHERE ";
        $inquery = '';
        if ($searchname) {
            $inquery .= esc_sql($clause) . "(LOWER(a.first_name) LIKE '%" . esc_sql($searchname) . "%' OR LOWER(a.last_name) LIKE '%" . esc_sql($searchname) . "%')";
            $clause = " AND ";
        }
        if ($searchusername) {
            $inquery .= esc_sql($clause) . " LOWER(u.user_login) LIKE '%" . esc_sql($searchusername) . "%'";
            $clause = " AND ";
        }
        $company_join = '';
        if ($searchcompany) {
            $inquery .= esc_sql($clause) . " LOWER(company.name) LIKE '%" . esc_sql($searchcompany) . "%'";
            $clause = " AND ";
            $company_join = 'LEFT JOIN ' . wpjobportal::$_db->prefix . 'wj_portal_companies AS company ON company.uid = a.id ';

        }
        $resume_join = '';
        if ($searchresume) {
            $inquery .= esc_sql($clause) . " ( LOWER(resume.first_name) LIKE '%" . esc_sql($searchresume) . "%'
                        OR LOWER(resume.last_name) LIKE '%" . esc_sql($searchresume) . "%')";
            $clause = " AND ";
            $resume_join = 'LEFT JOIN ' . wpjobportal::$_db->prefix . 'wj_portal_resume AS resume ON resume.uid = a.id ';
        }
        if ($searchrole){
            if (is_numeric($searchrole))
                $inquery .= esc_sql($clause) . "a.roleid = " . esc_sql($searchrole);
        }
        //Pagination
        $query = 'SELECT a.id '
                . ' FROM `' . wpjobportal::$_db->prefix . 'wj_portal_users` AS a'
                . ' LEFT JOIN `' . $this->jsGetPrefix() . 'users` AS u ON u.id = a.uid ';
        $query .= $company_join;
        $query .= $resume_join;
        $query .= $inquery;
        $query .= " GROUP BY a.id ";
        $total = wpjobportaldb::get_results($query);
        $total = count($total);
        wpjobportal::$_data['total'] = $total;
        wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

        //Data
        $query = 'SELECT a.*,u.user_login,u.id AS wpuid'
                . ' FROM ' . wpjobportal::$_db->prefix . 'wj_portal_users AS a'
                . ' LEFT JOIN ' . $this->jsGetPrefix() . 'users AS u ON u.id = a.uid ';
        $query .= $company_join;
        $query .= $resume_join;
        $query .= $inquery;
        $query .= ' GROUP BY a.id LIMIT ' . WPJOBPORTALpagination::$_offset . ',' . WPJOBPORTALpagination::$_limit;

        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        return;
    }


    function getUserRoleBasedInfo() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_user_nonce') ) {
            die( 'Security check Failed' );
        }
        $uid = WPJOBPORTALrequest::getVar('uid');
        if(!is_numeric($uid)){
            return false;
        }
        $roleid = WPJOBPORTALrequest::getVar('roleid');
        if(!is_numeric($roleid)){
            return false;
        }


        //Data
        $data = '';
        if($roleid == 1){
            $query = 'SELECT company.name AS display_value
                    FROM ' . wpjobportal::$_db->prefix . 'wj_portal_companies AS company WHERE company.uid = '.esc_sql($uid);
            $label = __('Company', 'wp-job-portal');
            $data = wpjobportaldb::get_var($query);
        }elseif($roleid == 2){
            $query = 'SELECT  resume.application_title AS application_title, CONCAT(resume.first_name," ",resume.last_name) AS name
                    FROM ' . wpjobportal::$_db->prefix . 'wj_portal_resume AS resume WHERE resume.uid = '.esc_sql($uid);
            $label = __('Resume', 'wp-job-portal');
            $data_row = wpjobportaldb::get_row($query);
            if(!empty($data_row)){ // to handle the case of user application title field is not published.
                if(isset($data_row->application_title) && $data_row->application_title != ''){
                    $data = $data_row->application_title;
                }else{
                    $label = __('Name', 'wp-job-portal');
                    $data = $data_row->name;
                }
            }
        }

        if($data !=''){
            $return_html = '
                            <div class="wpjobportal-user-data-text">
                                <span class="wpjobportal-user-data-title">
                                    '.esc_html($label) . ':
                                </span>
                                <span class="wpjobportal-user-data-value">
                                    '.esc_html($data).'
                                </span>
                            </div>';
            return wp_json_encode($return_html);
        }else{
            return false;
        }

    }

    function enforceDeleteUser($uid) {
        if (!is_numeric($uid))
            return false;

        $roleid = $this->getUserRoleByUid($uid);

        if (!is_numeric($roleid)) {
            // this user has no role
            // what to do then ?
        } else {

            $wp_uid = $this->getWPuidByOuruid($uid);

            if ($this->enforceDeleteOurUser($uid, $roleid)) {

                do_action('wpjobportal_load_wp_users');

                if (wp_delete_user($wp_uid))
                    return WPJOBPORTAL_DELETED;
                else {
                    return WPJOBPORTAL_DELETE_ERROR;
                }
            } else {
                return WPJOBPORTAL_DELETE_ERROR;
            }
        }
    }

   function enforceDeleteOurUser($uid, $roleid) {
        if (!is_numeric($uid))
            return false;
        $query = '';

        if ($roleid == 1) { // employer
            $query = "DELETE u, job,comp";
            if(in_array('departments', wpjobportal::$_active_addons)){
                $query .= ",dep ";
            }
            if(in_array('folder', wpjobportal::$_active_addons)){
                $query .= ",folder,folder_resumes ";
            }
            if(in_array('message', wpjobportal::$_active_addons)){
                $query .= ",message ";
            }

            if(in_array('credits', wpjobportal::$_active_addons)){
                $query .= ",upackage,tlog,subs,invoice ";
            }

            $query .= " ,jobcity,compcity
                        FROM
                        `" . wpjobportal::$_db->prefix . "wj_portal_users` AS u
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job ON job.uid = u.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobcities` AS jobcity ON jobcity.jobid = job.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS comp ON comp.uid = u.id";
                if(in_array('departments', wpjobportal::$_active_addons)){
                    $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_departments` AS dep ON dep.companyid = comp.id";
                }
                if(in_array('folder', wpjobportal::$_active_addons)){
                    $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_folders` AS folder ON folder.uid = u.id";
                    $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_folderresumes` AS folder_resumes ON folder_resumes.uid = u.id";
                }
                if(in_array('message', wpjobportal::$_active_addons)){
                    $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_messages` AS message ON message.employerid = u.id";
                }
                if(in_array('credits', wpjobportal::$_active_addons)){
                    $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_userpackages` AS upackage ON upackage.uid = u.id";
                    $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_transactionlog` AS tlog ON tlog.uid = u.id";
                    $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_subscriptions` AS subs ON subs.uid = u.id";
                    $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_invoices` AS invoice ON invoice.uid = u.id";
                }

                $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companycities` AS compcity ON compcity.companyid = comp.id
                WHERE u.id = " . esc_sql($uid);
        }

        if ($roleid == 2) { // seeker
                $query = "DELETE u,resume , ra, re,rf,ri,rl,ja ";
                    if(in_array('resumesearch', wpjobportal::$_active_addons)){
                        $query .= " ,rs ";
                    }
                    if(in_array('message', wpjobportal::$_active_addons)){
                        $query .= " ,message ";
                    }
                    if(in_array('credits', wpjobportal::$_active_addons)){
                        $query .= ",upackage,tlog,subs,invoice ";
                    }
                    if(in_array('jobalert', wpjobportal::$_active_addons)){
                        $query .= " ,jobalert,acity ";
                    }
                $query .= "
                    FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS u
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume ON resume.uid = u.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeaddresses` AS ra ON ra.resumeid = resume.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeemployers` AS re ON re.resumeid = resume.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumefiles` AS rf ON rf.resumeid = resume.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumeinstitutes` AS ri ON ri.resumeid = resume.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumelanguages` AS rl ON rl.resumeid = resume.id
                    LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` AS ja ON ja.uid = u.id ";
                    if(in_array('resumesearch', wpjobportal::$_active_addons)){
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_resumesearches` AS rs ON rs.uid = u.id";
                    }
                    if(in_array('message', wpjobportal::$_active_addons)){
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_messages` AS message ON message.jobseekerid = u.id ";
                    }
                    if(in_array('credits', wpjobportal::$_active_addons)){
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_userpackages` AS upackage ON upackage.uid = u.id";
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_transactionlog` AS tlog ON tlog.uid = u.id";
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_subscriptions` AS subs ON subs.uid = u.id";
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_invoices` AS invoice ON invoice.uid = u.id";
                    }
                    if(in_array('jobalert', wpjobportal::$_active_addons)){
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobalertsetting` AS jobalert ON jobalert.uid = u.id ";
                        $query .= " LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_jobalertcities` AS acity ON acity.alertid = jobalert.id ";
                    }
                    $query .= " WHERE u.id = " . esc_sql($uid);
        }

        if($query != ''){
            if (wpjobportaldb::query($query)) {
                return true;
            } else {
                return false;
            }
        }
    }

    function getUserRoleByUid($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "SELECT roleid FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE id = " . esc_sql($uid);
        $result = wpjobportaldb::get_var($query);
        return $result;
    }

    function getUserRoleByWPUid($wpuid) {
        if (!is_numeric($wpuid))
            return false;
        $query = "SELECT roleid FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE uid = " . esc_sql($wpuid);
        $result = wpjobportaldb::get_var($query);
        return $result;
    }

     function deleteUserPhoto() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-user-photo') ) {
            die( 'Security check Failed' );
        }        $cid = WPJOBPORTALrequest::getVar('userid');
        if(!is_numeric($cid)){
            return false;
        }
        WPJOBPORTALincluder::getObjectClass('uploads')->removeUserPhoto($cid);
        return true;
    }

    function getUserIDByWPUid($wpuid) {
        if (!is_numeric($wpuid))
            return false;
        $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE uid = " . esc_sql($wpuid);
        $result = wpjobportaldb::get_var($query);
        return $result;
    }

    function getWPuidByOuruid($our_uid) {
        if (!is_numeric($our_uid))
            return false;
        $query = "SELECT uid AS wpuid FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE id = " . esc_sql($our_uid);
        $result = wpjobportaldb::get_var($query);
        return $result;
    }

    function changeUserStatus($userid){
        if(!is_numeric($userid)) return false;
        $row = WPJOBPORTALincluder::getJSTable('users');
        if($row->load($userid)){
            $row->columns['status'] = 1 - $row->status;
            if($row->store()){
                if($row->columns['status'] == 1){
                    return WPJOBPORTAL_ENABLED;
                }else{
                    return WPJOBPORTAL_DISABLED;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function assignUserRole($data){

        if(empty($data))
            return false;
        if(! is_numeric($data['uid']))
            return false;
        if(! is_numeric($data['roleid']))
            return false;

        $arr = array();
        $arr['uid'] = $data['uid'];
        $arr['roleid'] = $data['roleid'];
        $arr['first_name'] = $data['payer_firstname'];
        $arr['emailaddress'] = $data['payer_emailadress'];
        $arr['status'] = 1;
        $arr['created'] = gmdate("Y-m-d H:i:s");
        $arr = wpjobportal::wpjobportal_sanitizeData($arr);
        $row = WPJOBPORTALincluder::getJSTable('users');
        if (!$row->bind($arr)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->check()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        return WPJOBPORTAL_SAVED;
    }

    function deleteUser($uid) {
        if (!is_numeric($uid))
            return false;
        $roleid = $this->getUserRoleByUid($uid);
        if (!is_numeric($roleid)) {
            // this user has no role
            // what to do then ?
        } else {
            if ($this->userCanDelete($uid, $roleid)) {
                $wp_uid = $this->getWPuidByOuruid($uid);

                if ($this->deleteOurUser($uid)) {
                    do_action('wpjobportal_load_wp_users');
                    if (wp_delete_user($wp_uid)) {
                        return WPJOBPORTAL_DELETED;
                    } else {
                        return WPJOBPORTAL_DELETE_ERROR;
                    }
                } else {
                    return WPJOBPORTAL_DELETE_ERROR;
                }
            } else {
                return WPJOBPORTAL_IN_USE;
            }
        }
    }

    function userCanDelete($uid, $roleid) {
        if (!is_numeric($uid))
            return false;
        if ($roleid == 1) { // employer
            $query = "SELECT
                    (SELECT COUNT(job.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job WHERE job.uid = ".esc_sql($uid)." )
                +   (SELECT COUNT(comp.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS comp WHERE comp.uid = ".esc_sql($uid)." )";
                if(in_array('departments', wpjobportal::$_active_addons)){
                    $query .= " +   (SELECT COUNT(dep.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_departments` AS dep WHERE dep.uid = ".esc_sql($uid)." )";
                }
                $query .= " AS total
            ";
        }

        if ($roleid == 2) { // seeker
            $query = "SELECT
                    (SELECT COUNT(resume.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` AS resume WHERE resume.uid = ".esc_sql($uid)." )

                AS total
            ";
        }

        $result = wpjobportaldb::get_var($query);
        if ($result > 0)
            return false;
        else
            return true;
    }

    function deleteOurUser($uid) {
        if (!is_numeric($uid))
            return false;
        $query = "DELETE FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE id = " . esc_sql($uid);
        if (wpjobportaldb::query($query)) {
            return true;
        } else {
            return false;
        }
    }

    // function getUserStats() {
    //     //Filters
    //     $searchname = WPJOBPORTALrequest::getVar('searchname');
    //     $searchusername = WPJOBPORTALrequest::getVar('searchusername');
    //     $formsearch = WPJOBPORTALrequest::getVar('WPJOBPORTAL_form_search', 'post');
    //     if ($formsearch == 'WPJOBPORTAL_SEARCH') {
    //         $_SESSION['WPJOBPORTAL_SEARCH']['searchname'] = $searchname;
    //         $_SESSION['WPJOBPORTAL_SEARCH']['searchusername'] = $searchusername;
    //     }
    //     if (WPJOBPORTALrequest::getVar('pagenum', 'get', null) != null) {
    //         $searchname = (isset($_SESSION['WPJOBPORTAL_SEARCH']['searchname']) && $_SESSION['WPJOBPORTAL_SEARCH']['searchname'] != '') ? sanitize_key($_SESSION['WPJOBPORTAL_SEARCH']['searchname']) : null;
    //         $searchusername = (isset($_SESSION['WPJOBPORTAL_SEARCH']['searchusername']) && $_SESSION['WPJOBPORTAL_SEARCH']['searchusername'] != '') ? sanitize_key($_SESSION['WPJOBPORTAL_SEARCH']['searchusername']) : null;
    //     } elseif ($formsearch !== 'WPJOBPORTAL_SEARCH') {
    //         unset($_SESSION['WPJOBPORTAL_SEARCH']);
    //     }
    //     wpjobportal::$_data['filter']['searchname'] = $searchname;
    //     wpjobportal::$_data['filter']['searchusername'] = $searchusername;

    //     $clause = " WHERE ";
    //     $inquery = "";
    //     if ($searchname) {
    //         $inquery .= esc_sql($clause) . " (LOWER(a.first_name) LIKE '%" . esc_sql($searchname) . "%' OR LOWER(a.last_name) LIKE '%" . esc_sql($searchname) . "%')";
    //         $clause = 'AND';
    //     }
    //     if ($searchusername)
    //         $inquery .= esc_sql($clause) . " LOWER(a.user_login) LIKE '%" . esc_sql($searchusername) . "%'";

    //     //Pagination
    //     $query = "SELECT COUNT(a.ID) FROM " . $this->jsGetPrefix() . "users AS a";
    //     $query.=$inquery;

    //     $total = wpjobportaldb::get_var($query);
    //     wpjobportal::$_data['total'] = $total;
    //     wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

    //     //Data
    //     $query = "SELECT a.id AS id, CONCAT(a.first_name,' ',a.last_name) AS name, u.user_login AS username
    //             ,(SELECT name FROM " . wpjobportal::$_db->prefix . "wj_portal_companies WHERE uid=a.id limit 1 ) AS companyname
    //             ,(SELECT CONCAT(first_name,' ',last_name) FROM " . wpjobportal::$_db->prefix . "wj_portal_resume WHERE uid=a.id limit 1 ) AS resumename
    //             ,(SELECT count(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_companies WHERE uid=a.id ) AS companies
    //             ,(SELECT count(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_jobs WHERE uid=a.id ) AS jobs
    //             ,(SELECT count(id) FROM " . wpjobportal::$_db->prefix . "wj_portal_resume WHERE uid=a.id ) AS resumes
    //             FROM " . wpjobportal::$_db->prefix . "wj_portal_users AS a
    //             LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = a.uid";
    //     $query.=$inquery;
    //     $query .= ' GROUP BY a.id LIMIT ' . WPJOBPORTALpagination::$_offset . ',' . WPJOBPORTALpagination::$_limit;
    //     wpjobportal::$_data[0] = wpjobportaldb::get_results($query);

    //     return;
    // }

    // function getUserStatsCompanies($companyuid) {
    //     if (is_numeric($companyuid) == false)
    //         return false;

    //     //Pagination
    //     $query = "SELECT COUNT(company.id)
    //               FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company
	   //            WHERE company.uid = " . esc_sql($companyuid);
    //     $total = wpjobportaldb::get_var($query);
    //     wpjobportal::$_data['total'] = $total;
    //     wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

    //     //Data
    //     $query = "SELECT company.*,cat.cat_title"
    //             . " FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company"
    //             . " LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_categories AS cat ON cat.id=company.category"
    //             . " LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_cities AS city ON city.id=company.city"
    //             . " LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_countries AS country ON country.id=city.countryid
		  //         WHERE company.uid = " . esc_sql($companyuid);
    //     $query .= " ORDER BY company.name LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;

    //     wpjobportal::$_data[0] = wpjobportaldb::get_results($query);

    //     return;
    // }

    function getWPRoleNameById($id) {
        $rolename = "";
        if ($id) {
            $user = new WP_User($id);
            $rolename = $user->roles[0];
        }
        return $rolename;
    }

    // function getUserStatsJobs($jobuid) {
    //     if (is_numeric($jobuid) == false)
    //         return false;

    //     //Pagination
    //     $query = "SELECT COUNT(job.id)
    //             FROM " . wpjobportal::$_db->prefix . "wj_portal_jobs AS job WHERE job.uid = " . esc_sql($jobuid);

    //     $total = wpjobportaldb::get_var($query);
    //     wpjobportal::$_data['total'] = $total;
    //     wpjobportal::$_data[1] = WPJOBPORTALpagination::getPagination($total);

    //     //Data
    //     $query = "SELECT job.*,company.name AS companyname,cat.cat_title,jobtype.title AS jobtypetitle"
    //             . " FROM " . wpjobportal::$_db->prefix . "wj_portal_jobs AS job"
    //             . " LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_companies AS company ON company.id=job.companyid"
    //             . " LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_categories AS cat ON cat.id=job.jobcategory"
    //             . " LEFT JOIN " . wpjobportal::$_db->prefix . "wj_portal_jobtypes AS jobtype ON jobtype.id=job.jobtype
		  //  WHERE job.uid = " . esc_sql($jobuid);
    //     $query .= " ORDER BY job.title LIMIT " . WPJOBPORTALpagination::$_offset . "," . WPJOBPORTALpagination::$_limit;

    //     wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
    //     return;
    // }

    function getuserlistajax() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-user-list-ajax') ) {
            die( 'Security check Failed' );
        }
        $userlimit = WPJOBPORTALrequest::getVar('userlimit', null, 0);
        $maxrecorded = 3;
        //Filters
        $uname = WPJOBPORTALrequest::getVar('uname');
        $name = WPJOBPORTALrequest::getVar('name');
        $email = WPJOBPORTALrequest::getVar('email');
        $listfor = WPJOBPORTALrequest::getVar('listfor');

        wpjobportal::$_data['filter']['name'] = $name;
        wpjobportal::$_data['filter']['uname'] = $uname;
        wpjobportal::$_data['filter']['email'] = $email;

        $inquery = "";

        if ($name != null) {
            $inquery .= " AND ( user.first_name LIKE '%" . esc_sql($name) . "%' OR user.last_name LIKE '%" . esc_sql($name) . "%' ) ";
        }
        if ($uname != null) {
            $inquery .= " AND  u.user_login LIKE  '%" . esc_sql($uname) . "%' ";
        }
        if ($email != null)
            $inquery .= " AND user.emailaddress LIKE '%" . esc_sql($email) . "%' ";

        if ($listfor == 1) {
            $status = "WHERE 1 = 1 "; //to get all users
        } else {
            $status = "WHERE user.roleid =1 ";
        }


        $query = "SELECT COUNT(user.id)
                FROM " . wpjobportal::$_db->prefix . "wj_portal_users AS user
                LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = user.uid
                $status ";
        $query .= $inquery;
        $total = wpjobportaldb::get_var($query);
        $limit = $userlimit * $maxrecorded;
        if ($limit >= $total) {
            $limit = 0;
        }

        //Data
        $query = "SELECT user.id AS userid,user.first_name,user.last_name,user.emailaddress
                    ,u.user_login
                FROM " . wpjobportal::$_db->prefix . "wj_portal_users AS user
                LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = user.uid
                $status ";
        $query .= $inquery;
        $query .= " ORDER BY user.id LIMIT $limit, $maxrecorded";
        $users = wpjobportaldb::get_results($query);

        $html = $this->makeUserList($users, $total, $maxrecorded, $userlimit);
        return $html;
    }

    function getAllRoleLessUsersAjax() {

        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-all-role-less-users-ajax') ) {
            die( 'Security check Failed' );
        }
        $userlimit = WPJOBPORTALrequest::getVar('userlimit', null, 0);
        $maxrecorded = 3;
        //Filters

        $name = WPJOBPORTALrequest::getVar('name');
        $uname = WPJOBPORTALrequest::getVar('uname');
        $email = WPJOBPORTALrequest::getVar('email');

        wpjobportal::$_data['filter']['name'] = $name;
        wpjobportal::$_data['filter']['uname'] = $uname;
        wpjobportal::$_data['filter']['email'] = $email;

        $inquery = "";

        if ($uname != null) {
            $inquery .= " AND ( user.user_login LIKE '%" . esc_sql($uname) . "%' ) ";
        }

        if ($name != null) {
            $inquery .= " AND ( user.display_name LIKE '%" . esc_sql($name) . "%' ) ";
        }

        if ($email != null) {
            $inquery .= " AND ( user.user_email LIKE '%" . esc_sql($email) . "%' ) ";
        }

        $query = "SELECT COUNT( user.ID ) AS total
                    FROM `" . $this->jsGetPrefix() . "users` AS user
                    WHERE NOT EXISTS( SELECT jsuser.id FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS jsuser WHERE user.ID = jsuser.uid) AND
                    NOT EXISTS(SELECT umeta_id FROM `".wpjobportal::$_db->prefix."usermeta` WHERE meta_value LIKE '%administrator%' AND user_id = user.id)";
        $query .= $inquery;
        $query .= " GROUP BY user.ID";
        $total = wpjobportaldb::get_var($query);

        $limit = $userlimit * $maxrecorded;
        if ($limit >= $total) {
            $limit = 0;
        }

        // Data
        $query = "SELECT DISTINCT user.ID AS userid, user.user_login , user.user_email AS emailaddress, user.display_name AS name
                    FROM `" . $this->jsGetPrefix() . "users` AS user
                    WHERE NOT EXISTS( SELECT jsuser.id FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS jsuser WHERE user.ID = jsuser.uid) AND
                    NOT EXISTS(SELECT umeta_id FROM `".wpjobportal::$_db->prefix."usermeta` WHERE meta_value LIKE '%administrator%' AND user_id = user.ID)";

        $query .= $inquery;
        $query .= " ORDER BY user.ID ASC LIMIT $limit, $maxrecorded";
        $users = wpjobportaldb::get_results($query);

        $html = $this->makeUserList($users, $total, $maxrecorded, $userlimit , true);
        return $html;
    }

    function makeUserList($users, $total, $maxrecorded, $userlimit , $assignrole = false) {
        $html = '';
        if (!empty($users)) {
            if (is_array($users)) {

                $html .= '
                    <div id="records">';

                $html .='
                <div id="user-list-header" class="popup-table">
                    <div class="user-list-header-col user-id">' . esc_html(__('ID', 'wp-job-portal')) . '</div>
                    <div class="user-list-header-col user-name">' . esc_html(__('Name', 'wp-job-portal')) . '</div>
                    <div class="user-list-header-col user-name-n">' . esc_html(__('User Name', 'wp-job-portal')) . '</div>
                    <div class="user-list-header-col user-email">' . esc_html(__('Email Address', 'wp-job-portal')) . '</div>

                </div>
                <div class="user-records-wrapper" >';

                    foreach ($users AS $user) {
                        if($assignrole){
                            $username = $user->name;
                        }else{
                            $username = $user->first_name . ' ' . $user->last_name;
                        }
                        $html .='
                            <div class="user-records-row" >
                                <div class="user-list-body-col user-id">
                                    ' . $user->userid . '
                                </div>
                                <div class="user-list-body-col user-name">
                                    <a href="#" class="userpopup-link js-userpopup-link" data-id=' . $user->userid . ' data-name="' . $username . '" data-email="' . $user->emailaddress . '" >' . $username . '</a>
                                </div>
                                <div class="user-list-body-col user-name-n">
                                    ' . $user->user_login . '
                                </div>
                                <div class="user-list-body-col user-email">
                                    ' . $user->emailaddress . '
                                </div>
                            </div>';
                    }
                $html .='</div>';
            }
            $num_of_pages = ceil($total / $maxrecorded);
            $num_of_pages = ($num_of_pages > 0) ? ceil($num_of_pages) : floor($num_of_pages);
            if ($num_of_pages > 0) {
                $page_html = '';
                $prev = $userlimit;
                if ($prev > 0) {
                    $page_html .= '<a class="wpjobportaladmin-userlink" href="#" onclick="updateuserlist(' . ($prev - 1) . ');">' . esc_html(__('Previous', 'wp-job-portal')) . '</a>';
                }
                for ($i = 0; $i < $num_of_pages; $i++) {
                    if ($i == $userlimit)
                        $page_html .= '<span class="wpjobportaladmin-userlink selected" >' . ($i + 1) . '</span>';
                    else
                        $page_html .= '<a class="wpjobportaladmin-userlink" href="#" onclick="updateuserlist(' . $i . ');">' . ($i + 1) . '</a>';
                }
                $next = $userlimit + 1;
                if ($next < $num_of_pages) {
                    $page_html .= '<a class="wpjobportaladmin-userlink" href="#" onclick="updateuserlist(' . $next . ');">' . esc_html(__('Next', 'wp-job-portal')) . '</a>';
                }
                if ($page_html != '') {
                    $html .= '<div class="wpjobportaladmin-userpages">' . $page_html . '</div>';
                }
            }
        } else {
            $html = WPJOBPORTALlayout::getAdminPopupNoRecordFound();
        }
        $html .= '</div>';
        return $html;
    }

    function checkUserBySocialID($socialid) {
        $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE socialid = '" . esc_sql($socialid) . "'";
        $result = wpjobportal::$_db->get_var($query);
        return $result;
    }
    
    function getAppliedCountProfileID($socialprofileid,$jobid) {
        $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` WHERE socialprofileid = '" . esc_sql($socialprofileid) . "' AND jobid ='".esc_sql($jobid)."'";
        $result = wpjobportal::$_db->get_var($query);
        return $result;
    }

    function getSocialProfileID($socialid) {
        $query = "SELECT id FROM `" . wpjobportal::$_db->prefix . "wj_portal_socialprofiles` WHERE socialid = '" . esc_sql($socialid) . "'";
        $result = wpjobportal::$_db->get_var($query);
        return $result;
    }

    function getUserData($id){
        if (!is_numeric($id))
            return false;
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE id = " . esc_sql($id) ;
        wpjobportal::$_data[0] = wpjobportal::$_db->get_row($query);
        if(!empty(wpjobportal::$_data[0]) && isset(wpjobportal::$_data[0]->roleid)){// roleid not set error in log
            //employer
            if(wpjobportal::$_data[0]->roleid == 1){
                $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` WHERE uid=".esc_sql($id);
                wpjobportal::$_data['jobs'] = wpjobportal::$_db->get_var($query);

                $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_companies` WHERE uid=".esc_sql($id);
                wpjobportal::$_data['companies'] = wpjobportal::$_db->get_var($query);
                if(in_array('departments', wpjobportal::$_active_addons)){
                    $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_departments` WHERE uid=".esc_sql($id);
                    wpjobportal::$_data['department'] = wpjobportal::$_db->get_var($query);
                }

                $query = "SELECT COUNT(jobapply.id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply` as jobapply
                JOIN ".wpjobportal::$_db->prefix."wj_portal_jobs AS job ON job.id = jobapply.jobid  WHERE job.uid=".esc_sql($id);
                wpjobportal::$_data['jobapply'] = wpjobportal::$_db->get_var($query);
            }elseif(wpjobportal::$_data[0]->roleid == 2){
                //jobseeker
                $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_resume` WHERE uid=".esc_sql($id);
                wpjobportal::$_data['resume'] = wpjobportal::$_db->get_var($query);

                $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobapply`  WHERE uid=".esc_sql($id);
                wpjobportal::$_data['jobapply'] = wpjobportal::$_db->get_var($query);
            }
        }
        return ;
    }

    function getChangeRolebyId($c_id) {
        if (is_numeric($c_id) == false)
            return false;
        $query = "SELECT a.*,a.created AS dated,u.user_login,u.id AS wpuid"
                . " FROM " . wpjobportal::$_db->prefix . "wj_portal_users AS a"
                . " LEFT JOIN " . $this->jsGetPrefix() . "users AS u ON u.id = a.uid"
                . " WHERE a.id = " . esc_sql($c_id);
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
        return;
    }

    function storeUserRole($data) {
        if (empty($data))
            return false;
        $row = WPJOBPORTALincluder::getJSTable('users');
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->check()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        return WPJOBPORTAL_SAVED;
    }

    function getUserIdByCompanyid(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-user-id-by-company-id') ) {
            die( 'Security check Failed' );
        }
        $companyid = WPJOBPORTALrequest::getVar('companyid');
        if(!is_numeric($companyid)) return false;
        $query = "SELECT uid FROM `".wpjobportal::$_db->prefix."wj_portal_companies` WHERE id = ".esc_sql($companyid);
        $companyid = wpjobportal::$_db->get_var($query);
        return $companyid;
    }

    function getUserDetailsById($u_id){
        if (is_numeric($u_id) == false)
            return false;
        $query = "SELECT user.emailaddress AS email,CONCAT(first_name,' ',last_name) AS name,user.roleid "
                . " FROM " . wpjobportal::$_db->prefix . "wj_portal_users AS user"
                . " WHERE user.id = " . esc_sql($u_id);
        return wpjobportaldb::get_row($query);
    }

    function getUserForForm($u_id){
        if(is_numeric($u_id) == false){
            return false;
        }
        $query = "SELECT CONCAT(first_name,' ',last_name) AS name,user.* FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user
        WHERE user.id = " . esc_sql($u_id);
        $res = wpjobportaldb::get_row($query);
        if(!$res){
            return false;
        }
        wpjobportal::$_data[0] = $res;
        return true;
    }

    function storeUser($data){
        if(empty($data)){
            return false;
        }
        if(!$data['id']){
            return false;
        }
        $data['first_name'] = wpjobportal::wpjobportal_sanitizeData(WPJOBPORTALrequest::getVar('wpjobportal_user_first'));
        $data['last_name'] = wpjobportal::wpjobportal_sanitizeData(WPJOBPORTALrequest::getVar('wpjobportal_user_last'));
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data = wpjobportal::$_common->stripslashesFull($data);// remove slashes with quotes.
        $data['description'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes(WPJOBPORTALrequest::getVar('description','post','','',1))));
        $row = WPJOBPORTALincluder::getJSTable('users');
        if(!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if(!$row->check()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if(!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }

        $this->storeUserPhoto(WPJOBPORTALincluder::getObjectClass('user')->getWPuid());

        WPJOBPORTALincluder::getObjectClass('customfields')->storeCustomFields(4,$row->id,$data);

        if(!$data['id']){
            WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2,1,$row->id);
        }

        if(isset($data['oldStatus']) && $data['oldStatus']!=$data['status']){
            WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(2,2,$row->id);
        }

        return WPJOBPORTAL_SAVED;
    }

    function storeUserPhoto($id){
        if(!is_numeric($id)){
            return false;
        }
        if($_FILES['photo']['size'] > 0) { // logo
            $query = "SELECT photo FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE uid = ".esc_sql($id);
            $photo = wpjobportal::$_db->get_var($query);
            if( !empty($photo) ){
               WPJOBPORTALincluder::getObjectClass('uploads')->removeUserPhoto($id);
            }
            WPJOBPORTALincluder::getObjectClass('uploads')->uploadUserPhoto($id);
        }
        return;
    }

    // End Function
    // setcookies for search form data
    //search cookies data
    function getSearchFormData(){
        $jsjp_search_array = array();
        $jsjp_search_array['searchname'] = WPJOBPORTALrequest::getVar('searchname');
        $jsjp_search_array['searchusername'] = WPJOBPORTALrequest::getVar('searchusername');
        $jsjp_search_array['searchrole'] = WPJOBPORTALrequest::getVar('searchrole');
        $jsjp_search_array['searchcompany'] = WPJOBPORTALrequest::getVar('searchcompany');
        $jsjp_search_array['searchresume'] = WPJOBPORTALrequest::getVar('searchresume');
        $jsjp_search_array['search_from_user'] = 1;
        return $jsjp_search_array;
    }

    function getSavedCookiesDataForSearch(){
        $jsjp_search_array = array();
        $wpjp_search_cookie_data = '';
        if(isset($_COOKIE['jsjp_jobportal_search_data'])){
            $wpjp_search_cookie_data = wpjobportal::wpjobportal_sanitizeData($_COOKIE['jsjp_jobportal_search_data']);
            $wpjp_search_cookie_data = wpjobportalphplib::wpJP_safe_decoding($wpjp_search_cookie_data);
            $wpjp_search_cookie_data = json_decode( $wpjp_search_cookie_data , true );
        }
        if($wpjp_search_cookie_data != '' && isset($wpjp_search_cookie_data['search_from_user']) && $wpjp_search_cookie_data['search_from_user'] == 1){
            $jsjp_search_array['searchname'] = $wpjp_search_cookie_data['searchname'];
            $jsjp_search_array['searchusername'] = $wpjp_search_cookie_data['searchusername'];
            $jsjp_search_array['searchrole'] = $wpjp_search_cookie_data['searchrole'];
            $jsjp_search_array['searchcompany'] = $wpjp_search_cookie_data['searchcompany'];
            $jsjp_search_array['searchresume'] = $wpjp_search_cookie_data['searchresume'];
        }
        return $jsjp_search_array;
    }

    function setSearchVariableForSearch($jsjp_search_array){
        wpjobportal::$_search['user']['searchname'] = isset($jsjp_search_array['searchname']) ? $jsjp_search_array['searchname'] : null;
        wpjobportal::$_search['user']['searchusername'] = isset($jsjp_search_array['searchusername']) ? $jsjp_search_array['searchusername'] : null;
        wpjobportal::$_search['user']['searchrole'] = isset($jsjp_search_array['searchrole']) ? $jsjp_search_array['searchrole'] : null;
        wpjobportal::$_search['user']['searchcompany'] = isset($jsjp_search_array['searchcompany']) ? $jsjp_search_array['searchcompany'] : null;
        wpjobportal::$_search['user']['searchresume'] = isset($jsjp_search_array['searchresume']) ? $jsjp_search_array['searchresume'] : null;
    }

    function getMessagekey(){
        $key = 'user';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
