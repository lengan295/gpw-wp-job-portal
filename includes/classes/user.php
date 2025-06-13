<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALuser {

    private $currentuser = null;

    function __construct() {
        if (is_user_logged_in()) { // wp user logged in
            $wpuserid = get_current_user_id();
            if (!is_numeric($wpuserid))
                return false;
            $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE uid = " . esc_sql($wpuserid);
            $this->currentuser = wpjobportal::$_db->get_row($query);
        }else { // wp user is not logged in
        //sanitize_key($_SESSION['wpjobportal-socialid'])
            if (isset($_COOKIE['wpjobportal-socialid']) && !empty($_COOKIE['wpjobportal-socialid'])) { // social user is logged in
            
                $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE socialid = '" . sanitize_key($_COOKIE['wpjobportal-socialid']) . "'";
                $this->currentuser = wpjobportal::$_db->get_row($query);
            }
        }
    }

    function isguest() {
        if (isset($_COOKIE['wpjobportal-socialid']) && !empty($_COOKIE['wpjobportal-socialid'])) {
            return false;
        } elseif ($this->currentuser == null && !is_user_logged_in()) { // current user is guest
            return true;
        } else {
            return false;
        }
    }

    function getEmployerProfile(){
        //if($this->isemployer()==true){
        $id  = $this->currentuser->uid;
        $string = "users.uid";
        if (isset($_COOKIE['wpjobportal-socialid']) && !empty($_COOKIE['wpjobportal-socialid'])) { // social user is logged in
            $id  = $this->currentuser->id;
            $string = "users.id";
        }
        if(is_numeric($id)){
            $query = "SELECT users.id,users.uid,users.first_name,users.photo,users.emailaddress,users.last_name,users.socialmedia
            FROM " . wpjobportal::$_db->prefix . "wj_portal_users AS users  
            WHERE users.id != 0 AND ".esc_sql($string)."=".esc_sql($id) ."  LIMIT 1 ";
            return wpjobportal::$_db->get_row($query);
        }
        
        
        //}else{
            return false;
        //}
    }

    function getJobSeekerProfile(){
        if($this->isjobseeker()==true){
            $id  = $this->currentuser->uid;
            $string = "users.uid";
            if (isset($_COOKIE['wpjobportal-socialid']) && !empty($_COOKIE['wpjobportal-socialid'])) { // social user is logged in
                $id  = $this->currentuser->id;
                $string = "users.id";
            }
            if(is_numeric($id)){
                $query = "SELECT users.id,users.uid,users.first_name,users.photo,users.emailaddress,users.last_name,users.socialmedia
                FROM " . wpjobportal::$_db->prefix . "wj_portal_users AS users
                WHERE users.id != 0 AND ".esc_sql($string)."=".esc_sql($id) ."  LIMIT 1 ";
                return wpjobportal::$_db->get_row($query);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    function isisWPJobportalUser() {
        if (is_user_logged_in()) { // wp user logged in
            $wpuserid = get_current_user_id();
            if (!is_numeric($wpuserid))
                return false;
            $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE uid = " . esc_sql($wpuserid);
            $result = wpjobportal::$_db->get_var($query);
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            if (isset($_COOKIE['wpjobportal-socialid']) && !empty($_COOKIE['wpjobportal-socialid'])) { // social user is logged in
                $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE socialid = '" . sanitize_key($_COOKIE['wpjobportal-socialid']) . "'";
                $result = wpjobportal::$_db->get_var($query);
                if ($result > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    function isdisabled() {
        if ($this->currentuser != null && $this->currentuser->status == 0) { // current user is disabled
            return true;
        } else {
            return false;
        }
    }

    function getJobseekerLogo(){
        if($this->isjobseeker()==true){
            if(is_numeric($this->currentuser->uid)){
                $query = "SELECT resume.id,resume.photo
                FROM " . wpjobportal::$_db->prefix . "wj_portal_resume AS resume  
                WHERE resume.status != 0 AND resume.uid=".esc_sql($this->currentuser->uid)." AND resume.photo !='' LIMIT 1 ";
                return wpjobportal::$_db->get_row($query);
            }
        }
        return false;
    }
    function getEmployerLogo(){
        if($this->isemployer()==true){
            if(is_numeric($this->currentuser->uid)){
                $query = "SELECT company.id,company.name,company.logofilename
                FROM " . wpjobportal::$_db->prefix . "wj_portal_companies AS company  
                WHERE company.status != 0 AND company.uid=".esc_sql($this->currentuser->uid)." AND company.logofilename !='' LIMIT 1 ";
                return wpjobportal::$_db->get_row($query);
            }
        }
        return false;
    }
    function isemployer() {
        if ($this->currentuser == null) { // current user is guest
            return false;
        } else {
            if ($this->currentuser->roleid == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    function roleid($uid=0){
        if(is_numeric($uid)){
            $query = "SELECT `roleid` FROM `".wpjobportal::$_db->prefix."wj_portal_users` WHERE `id` = ".esc_sql($uid);
            return wpjobportaldb::get_var($query); 
        }elseif($this->currentuser != null) {
            return $this->currentuser->roleid;
        }
    }

    function isjobseeker() {
        if ($this->currentuser == null) { // current user is guest
            return false;
        } else {
            if ($this->currentuser->roleid == 2) {
                return true;
            } else {
                return false;
            }
        }
    }

    function uid() {
        if ($this->currentuser != null) {
            return $this->currentuser->id;
        }
    }

    function getWPuid() {
        if ($this->currentuser != null) {
            return $this->currentuser->uid;
        }
    }

    function emailaddress() {
        if ($this->currentuser == null) { // current user is guest
            return false;
        } else {
            return $this->currentuser->emailaddress;
        }
    }

    function fullname($uid='') {
        if($uid==''){ 
            if ($this->currentuser == null) { // current user is guest
                return false;
            } else {
                $name = $this->currentuser->first_name . ' ' . $this->currentuser->last_name;
                return $name;
            }
        }else{
            if(is_numeric($uid)){
                if(wpjobportal::$_common->wpjp_isadmin()){
                    $query = "SELECT CONCAT(first_name,' ',last_name) FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE `ID` = " . esc_sql($uid);
                    return wpjobportal::$_db->get_var($query);
                }else{
                    return '';
                }
            }else{
                return '';
            }
        }
    }

    function getAvailableCredits() {
        $isadmin = WPJOBPORTALrequest::getVar('isadmin');
        $userid = WPJOBPORTALrequest::getVar('userid');
        if($isadmin && is_numeric($userid)){
            $uid = $userid;
        }else{
            $uid = $this->uid();            
        }
        $credits = WPJOBPORTALIncluder::getJSModel('user')->getMyAvailableCredits($uid);
        return $credits;
    }

    function getAvailableCreditsForUser($uid) {
        if (!is_numeric($uid))
            return false;
        $credits = WPJOBPORTALIncluder::getJSModel('user')->getMyAvailableCredits($uid);
        return $credits;
    }

    function isWPJOBPortalUser() {
        if (is_user_logged_in()) { // wp user logged in
            $wpuserid = get_current_user_id();
            if (!is_numeric($wpuserid))
                return false;
            $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE uid = " . esc_sql($wpuserid);
            $result = wpjobportal::$_db->get_var($query);
            if ($result > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            if (isset($_COOKIE['wpjobportal-socialid']) && !empty($_COOKIE['wpjobportal-socialid'])) { // social user is logged in
                $query = "SELECT COUNT(id) FROM `" . wpjobportal::$_db->prefix . "wj_portal_users` WHERE socialid = '" . sanitize_key($_COOKIE['wpjobportal-socialid']) . "'";
                $result = wpjobportal::$_db->get_var($query);
                if ($result > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    function isSocialLogin() {
        if (isset($_COOKIE['wpjobportal-socialid']) && !empty($_COOKIE['wpjobportal-socialid'])) {
            return true;
        } else {
            return false;
        }
    }
    
    function getwpjobportaluidbyuserid($userid){
        if(!is_numeric($userid)) return false;
        $query = "SELECT id FROM `".wpjobportal::$_db->prefix."wj_portal_users` WHERE uid = ".esc_sql($userid);
        $uid = wpjobportal::$_db->get_var($query);
        return $uid;
    }

}

?>
