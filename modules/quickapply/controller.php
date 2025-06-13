<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALquickapplyController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('job')->getMessagekey();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'job');
        if (self::canaddfile($layout)) {
            return;// this module does not have any layout at the moment
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'job');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            WPJOBPORTALincluder::include_file($layout, $module);
        }
    }

    function canaddfile($layout) {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'wpjobportal')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'wpjobportaltask')
            return false;
        else{
                if(!is_admin() && strpos($layout, 'admin_') === 0){
                    return false;
                }
                return true;
            }
    }

    function addtoquickapply() {

        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if(! wp_verify_nonce( $nonce, 'copy-job')) {
            die( 'Security check Failed' );
        }
        $id = WPJOBPORTALrequest::getVar('wpjobportalid');
        $action = "job";
        $result = WPJOBPORTALincluder::getJSModel('quickapply')->quickapply($id,$action);

        if($result == WPJOBPORTAL_SAVED){
           //WPJOBPORTALmessages::setLayoutMessage(__("Job copy successfully",'wp-job-portal'),'updated',$this->_msgkey);
        }else{
            WPJOBPORTALmessages::setLayoutMessage(__("There was some problem performing action",'wp-job-portal'),'error',$this->_msgkey);
        }
        if(wpjobportal::$_common->wpjp_isadmin()){
            $url = admin_url("admin.php?page=wpjobportal_job&wpjobportal=jobs");
        }else{
            $url = array('wpjobportalme'=>'job','wpjobportallt'=>'myjobs');
            $url = wpjobportal::wpjobportal_makeUrl($url);
        }
        wp_redirect($url);
        die();
    }

    function quickapplyonjob() {

        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if(! wp_verify_nonce( $nonce, 'wpjobportal_quick_apply_nonce')) {
            die( 'Security check Failed' );
        }

        $result = WPJOBPORTALincluder::getJSModel('quickapply')->quickApplyOnJob();
        $jobid = WPJOBPORTALrequest::getVar('jobid');
        $page_id = WPJOBPORTALrequest::getVar('wpjobportalpageid');
        if($result == WPJOBPORTAL_SAVED){
           WPJOBPORTALmessages::setLayoutMessage(__("Successfully applied on job",'wp-job-portal'),'updated','job');
        }else{
            WPJOBPORTALmessages::setLayoutMessage(__("There was some problem performing action",'wp-job-portal'),'error','job');
        }

        $url = array('wpjobportalme'=>'job','wpjobportallt'=>'viewjob','wpjobportalid'=>$jobid,'wpjobportalpageid'=>$page_id);
        $url = wpjobportal::wpjobportal_makeUrl($url);

        wp_redirect(esc_url_raw($url));
        die();
    }

}

$WPJOBPORTALquickapplyController = new WPJOBPORTALquickapplyController();
?>
