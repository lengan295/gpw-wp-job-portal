<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALpostinstallationController {

    function __construct() {

        self::handleRequest();
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'stepone');
        if($this->canaddfile($layout)){
            switch ($layout) {
                case 'admin_stepone':
                    WPJOBPORTALincluder::getJSModel('postinstallation')->getConfigurationValues();
					WPJOBPORTALincluder::getJSModel('postinstallation')->addMissingUsers();
                break;
                case 'admin_steptwo':
                    WPJOBPORTALincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_stepthree':
                    WPJOBPORTALincluder::getJSModel('postinstallation')->getConfigurationValues();
                break;
                case 'admin_themedemodata':
                    wpjobportal::$_data['flag'] = WPJOBPORTALrequest::getVar('flag');
                break;
                case 'admin_demoimporter':
                    WPJOBPORTALincluder::getJSModel('postinstallation')->getListOfDemoVersions();
                break;
                case 'admin_quickstart':
                case 'admin_stepfour':
                break;

                default:
                    return;
            }
            $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
            $module = WPJOBPORTALrequest::getVar($module, null, 'postinstallation');
            $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($layout, $module);
        }

    }
    function canaddfile($layout) {
        $nonce_value = WPJOBPORTALrequest::getVar('wpjobportal_nonce');
        if ( wp_verify_nonce( $nonce_value, 'wpjobportal_nonce') ) {
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
    }

    function save(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_postinstallation_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $result = WPJOBPORTALincluder::getJSModel('postinstallation')->storeconfigurations($data);

        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepone"));
        if($data['step'] == 1){
            $wpjobportal_multiple_employers =  get_option( "wpjobportal_multiple_employers", 1 );
            if($wpjobportal_multiple_employers == 1){
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=steptwo"));
            }else{
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepthree"));
            }
        }
        if($data['step'] == 2){
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepthree"));
        }
        if($data['step'] == 3){
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepfour"));
        }
        wp_redirect($url);
        exit();
    }

    function savesampledata(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_postinstallation_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $data = WPJOBPORTALrequest::get('post');
        $sampledata = $data['sampledata'];
        $temp_data = 0;
        $jsmenu = 0;
        $empmenu = 0;
	$job_listing_menu=0;
        if(isset($data['temp_data'])){
            $temp_data = 1;
        }
        // notice for undeined variable
        if(isset($data['jsmenu'])){
            $jsmenu = $data['jsmenu'];
        }
        if(isset($data['empmenu'])){
            $empmenu = $data['empmenu'];
        }
        if(isset($data['job_listing_menu'])){
            $job_listing_menu = $data['job_listing_menu'];
        }

        if(wpjobportal::$theme_chk == 1){
            update_option( 'wpjobportal_jobs_sample_data', 1 ); // flag to messge that jobs data has been inserted.
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=demoimporter"));
        }else{
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal"));
        }
        $result = WPJOBPORTALincluder::getJSModel('postinstallation')->installSampleData($sampledata,$jsmenu,$empmenu,$temp_data, $job_listing_menu);
        wp_redirect($url);
        exit();
    }

    function savetemplatesampledata(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_postinstallation_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $flag = WPJOBPORTALrequest::getVar('flag');
        $result = WPJOBPORTALincluder::getJSModel('postinstallation')->installSampleDataTemplate($flag);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=themedemodata&flag=".esc_url($result)));
        wp_redirect($url);
        exit();
    }

    function importtemplatesampledata(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_postinstallation_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $flag = WPJOBPORTALrequest::getVar('flag','',0);// zero as default value to avoid problems
        if($flag == 'f'){
            $result = WPJOBPORTALincluder::getJSModel('postinstallation')->importTemplateSampleData($flag);
        }else{
            $result = 0;
        }
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=themedemodata&flag=".esc_url($result)));
        wp_redirect($url);
        exit();
    }

    function getdemocode(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_postinstallation_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $demoid = WPJOBPORTALrequest::getVar('demoid');
        $foldername = WPJOBPORTALrequest::getVar('foldername');
        $demo_overwrite = WPJOBPORTALrequest::getVar('demo_overwrite');
        $result = WPJOBPORTALincluder::getJSModel('postinstallation')->getDemo($demoid,$foldername,$demo_overwrite);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal"));
        wp_redirect($url);
        exit();
    }

    function importfreetoprotemplatedata(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_postinstallation_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        if(wpjobportal::$theme_chk == 1){// 1 for job manager
            $result = WPJOBPORTALincluder::getJSModel('postinstallation')->installFreeToProData();
        }else{
            $result = WPJOBPORTALincluder::getJSModel('postinstallation')->installFreeToProDataJobHub();
        }
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal"));
        wp_redirect($url);
        exit();
    }

    function installjobportaldemodata(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_postinstallation_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin())
            return false;
        $result = WPJOBPORTALincluder::getJSModel('postinstallation')->installSampleDataTemplateJobPortal();
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal"));
        wp_redirect($url);
        exit();
    }

}
$WPJOBPORTALpostinstallationController = new WPJOBPORTALpostinstallationController();
?>
