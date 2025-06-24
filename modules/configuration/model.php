<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALconfigurationModel {

    var $_data_directory = null;
    var $_comp_editor = null;
    var $_job_editor = null;
    var $_defaultcountry = null;
    var $_config = null;

    function __construct() {

    }

    function getConfiguration() {
        do_action('wpjobportal_load_wp_plugin_file');
        // check for plugin using plugin name
        if (is_plugin_active('wp-job-portal/wp-job-portal.php')) {
            $query = "SELECT config.* FROM `" . wpjobportal::$_db->prefix . "wj_portal_config` AS config WHERE configfor = 'default'";
            $config = wpjobportaldb::get_results($query);
            foreach ($config as $conf) {
                wpjobportal::$_configuration[$conf->configname] = $conf->configvalue;
            }
            wpjobportal::$_configuration['config_count'] = COUNT($config);
        }
    }

    function getConfigurationsForForm() {
        $query = "SELECT config.* FROM `" . wpjobportal::$_db->prefix . "wj_portal_config` AS config";
        $config = wpjobportaldb::get_results($query);
        foreach ($config as $conf) {
            wpjobportal::$_data[0][$conf->configname] = $conf->configvalue;
        }
        wpjobportal::$_data[0]['config_count'] = COUNT($config);
    }



    function storeConfig($data) {
        if (empty($data))
            return false;
        if (!current_user_can('manage_options')) { //only admin can change it.
            return false;
        }

        if ($data['isgeneralbuttonsubmit'] == 1) {
            if (!isset($data['employer_share_fb_like']))
                $data['employer_share_fb_like'] = 0;
            if (!isset($data['employer_share_fb_share']))
                $data['employer_share_fb_share'] = 0;
            if (!isset($data['employer_share_fb_comments']))
                $data['employer_share_fb_comments'] = 0;
            if (!isset($data['employer_share_google_like']))
                $data['employer_share_google_like'] = 0;
            if (!isset($data['employer_share_google_share']))
                $data['employer_share_google_share'] = 0;
            if (!isset($data['employer_share_blog_share']))
                $data['employer_share_blog_share'] = 0;
            if (!isset($data['employer_share_friendfeed_share']))
                $data['employer_share_friendfeed_share'] = 0;
            if (!isset($data['employer_share_linkedin_share']))
                $data['employer_share_linkedin_share'] = 0;
            if (!isset($data['employer_share_digg_share']))
                $data['employer_share_digg_share'] = 0;
            if (!isset($data['employer_share_twitter_share']))
                $data['employer_share_twitter_share'] = 0;
            if (!isset($data['employer_share_myspace_share']))
                $data['employer_share_myspace_share'] = 0;
            if (!isset($data['employer_share_yahoo_share']))
                $data['employer_share_yahoo_share'] = 0;

        }
        $data = wpjobportal::wpjobportal_sanitizeData($data);
    	$data['offline_text'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes(WPJOBPORTALrequest::getVar('offline_text','post','','',1))));

        $error = false;
        //DB class limitations
        foreach ($data as $key => $value) {
            if ($key == 'default_image') { // ignore saving default image from here
                continue;
            }
			if ($key == 'data_directory') {
				$data_directory = $value;
				if(empty($data_directory)){
					WPJOBPORTALMessages::setLayoutMessage(esc_html(__('Data directory can not empty.', 'wp-job-portal')), 'error',$this->getMessagekey());
					continue;
				}
				if(wpjobportalphplib::wpJP_strpos($data_directory, '/') !== false){
					WPJOBPORTALMessages::setLayoutMessage(esc_html(__('Data directory is not proper.', 'wp-job-portal')), 'error',$this->getMessagekey());
					continue;
				}
				$path = WPJOBPORTAL_PLUGIN_PATH.'/'.$data_directory;
                if ( ! function_exists( 'WP_Filesystem' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                }
                global $wp_filesystem;
                if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
                    $creds = request_filesystem_credentials( site_url() );
                    wp_filesystem( $creds );
                }

				if ( ! $wp_filesystem->exists($path)) {
				   $wp_filesystem->mkdir($path, 0755);
				}
				if( ! $wp_filesystem->is_writable($path)){
					WPJOBPORTALMessages::setLayoutMessage(esc_html(__('Data directory is not writable.', 'wp-job-portal')), 'error',$this->getMessagekey());
					continue;
				}
			}
            $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config` SET `configvalue` = '".esc_sql($value)."' WHERE `configname`= '" . esc_sql($key) . "'";
            if (false === wpjobportaldb::query($query)) {
                $error = true;
            }
        }

        // upload deault image code
        // removing file
        if(isset($data['remove_default_image']) && $data['remove_default_image'] == 1){
            $this->deletedefaultImageModel();
        }
        // uploading (attaching) file
        if(isset($_FILES['default_image'])){// min field issue
            if ($_FILES['default_image']['size'] > 0) {
                // if(!isset($data['remove_default_image'])){
                //     $this->deletedefaultImageModel();
                // }
                $res = WPJOBPORTALincluder::getObjectClass('uploads')->uploadDeafultImage();
                if ($res == 6){
                    $msg = WPJOBPORTALMessages::getMessage(WPJOBPORTAL_FILE_TYPE_ERROR, '');
                    WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
                }
                if($res == 5){
                    $msg = WPJOBPORTALMessages::getMessage(WPJOBPORTAL_FILE_SIZE_ERROR, '');
                    WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->getMessagekey());
                }
            }
        }
        if ($error)
            return WPJOBPORTAL_SAVE_ERROR;
        else
            return WPJOBPORTAL_SAVED;
    }

    function storeAutoUpdateConfig() {

        if (!current_user_can('manage_options')) { //only admin can change it.
            return false;
        }
        $configvalue = WPJOBPORTALrequest::getVar('wpjobportal_addons_auto_update','','');

        if (!is_numeric($configvalue)) { //can only have numric value
            return false;
        }

        $error = false;
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config` SET `configvalue` = ".esc_sql($configvalue)." WHERE `configname`= 'wpjobportal_addons_auto_update'";
        if (false === wpjobportaldb::query($query)) {
            $error = true;
        }

        if ($error)
            return WPJOBPORTAL_SAVE_ERROR;
        else
            return WPJOBPORTAL_SAVED;
    }

    // remove default image file and configuration value
    private function deletedefaultImageModel(){
        $data_directory = wpjobportal::$_config->getConfigValue('data_directory');
        $wpdir = wp_upload_dir();
        $path = $wpdir['basedir'] . '/' . $data_directory . '/data/default_image/';
        $files = glob($path . '/*.*');
        array_map('wp_delete_file', $files);    // delete all file in the direcoty
        $query = "UPDATE `".wpjobportal::$_db->prefix."wj_portal_config` SET configvalue = '' WHERE configname = 'default_image'";
        wpjobportal::$_db->query($query);
        return true;
    }

    function getConfigByFor($configfor) {
        if (!$configfor)
            return;
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_config` WHERE configfor = '" . esc_sql($configfor) . "'";
        $config = wpjobportaldb::get_results($query);
        $configs = array();
        foreach ($config as $conf) {
            $configs[$conf->configname] = $conf->configvalue;
        }
        return $configs;
    }

    function getCountConfig() {

        $query = "SELECT COUNT(*) FROM `" . wpjobportal::$_db->prefix . "wj_portal_config`";
        $result = wpjobportaldb::get_var($query);
        return $result;
    }

    function getConfigValue($configname) {
        $query = "SELECT configvalue FROM `" . wpjobportal::$_db->prefix . "wj_portal_config` WHERE configname = '" . esc_sql($configname) . "'";
        //return wpjobportaldb::get_var($query);
		return wpjobportal::$_db->get_var($query);
    }

    function getConfigurationByConfigForMultiple($configfor){
        $query = "SELECT configname,configvalue
                  FROM `".wpjobportal::$_db->prefix."wj_portal_config` WHERE configfor IN (".$configfor.")";
        $result = wpjobportaldb::get_results($query);
        $config_array =  array();
        //to make configuration in to an array with key as index
        foreach ($result as $config ) {
           $config_array[$config->configname] = $config->configvalue;
        }
        return $config_array;
    }

    function getConfigurationByConfigName($configname){
        $query = "SELECT configvalue
                  FROM `".wpjobportal::$_db->prefix."wj_portal_config` WHERE configname ='" . esc_sql($configname) . "'";
        $result = wpjobportaldb::get_var($query);
        return $result;

    }

    function checkCronKey($passkey) {

        $query = "SELECT COUNT(configvalue) FROM `".wpjobportal::$_db->prefix."wj_portal_config` WHERE configname = 'cron_job_alert_key' AND configvalue = '" . esc_sql($passkey) . "'";
        $key = wpjobportaldb::get_var($query);
        if ($key == 1)
            return true;
        else
            return false;
    }

    function getLoginRegisterRedirectLink($defaulUrl,$redirectType) {
        if ($redirectType == 'register') {
            $val = wpjobportal::$_configuration['set_register_redirect_link'];
            $link = wpjobportal::$_configuration['register_redirect_link'];
            $wpDefaultPage = wp_registration_url();
        } else if ($redirectType == 'login') {
            $val = wpjobportal::$_configuration['set_login_redirect_link'];
            $link = wpjobportal::$_configuration['login_redirect_link'];
            $wpDefaultPage = wp_login_url();
        }
        $redirectval = $val;
        $redirectlink = esc_url($link);// to handle improper urls showing error
        if ($redirectval == 3){
            $hreflink = $wpDefaultPage;
        }
        else if($redirectval == 2 && $redirectlink != ""){
            $hreflink = $redirectlink;
        }else{
            $hreflink = $defaulUrl;
        }
        return $hreflink;
    }
    function getMessagekey(){
        $key = 'configuration';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }



    function getConfigSideMenu(){
        $html = '<ul id="wpjobportaladmin-menu-links" class="tree config-accordion accordion wpjobportaladmin-sidebar-menu "  data-widget="tree">
            <li class="treeview" id="gen_setting">
                <a class="js-icon-left" href="#" title="'. esc_html(__('general setting' , 'wp-job-portal')) .'">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/control_panel/dashboard/admin-left-menu/config.png" .'"/>
                    <span class="wpjobportal_text wpjobportal-parent">'. esc_html(__("General Settings" , 'wp-job-portal')) .'</span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#site_setting" class="jslm_text">'. esc_html(__("Site Settings",'wp-job-portal')) .'</a></li>';
                    if(in_array('message', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#message" class="jslm_text">'.  esc_html(__("Messages" , 'wp-job-portal')) .'</a></li>';
                    }
                    $html .= '<li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#defaul_setting" class="jslm_text">'.  esc_html(__("Default Settings" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#categories" class="jslm_text">'.  esc_html(__("Categories" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#email" class="jslm_text">'.  esc_html(__("Email" , 'wp-job-portal')) .'</a></li>';
                    if(in_array('addressdata', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#googlemapadsense" class="jslm_text">'.  esc_html(__("Map" , 'wp-job-portal')) .'</a></li>';
                    }
                    $html .= '<li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#offline" class="jslm_text">'.  esc_html(__("Offline" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#terms" class="jslm_text">'.  esc_html(__("Term And Conditions" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting#url-settings" class="jslm_text">'.  esc_html(__("URL Settings" , 'wp-job-portal')) .'</a></li>
                </ul>
            </li>
            <li class="treeview" id="emp_setting">
                <a class="js-icon-left" href="#" title="'. esc_html(__('employer' , 'wp-job-portal')) .'">
                    <img src="'.  esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/employer.png" .'"/>
                    <span class="jslm_text wpjobportal-parent ">'.  esc_html(__("Employer" , 'wp-job-portal')) .'</span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsemployer&wpjpconfigid=emp_general_setting#emp_generalsetting" class="jslm_text">'.  esc_html(__("General Settings",'wp-job-portal')) .'</a></li>';
                    if(in_array('addressdata', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsemployer&wpjpconfigid=emp_general_setting#emp_listresume" class="jslm_text">'.  esc_html(__("Search Resume" , 'wp-job-portal')) .'</a></li> ';
                    }
                    $html .= '<li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsemployer&wpjpconfigid=emp_general_setting#email" class="jslm_text">'.  esc_html(__("Email" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsemployer&wpjpconfigid=emp_general_setting#emp_auto_approve" class="jslm_text">'.  esc_html(__("Auto Approve" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsemployer&wpjpconfigid=emp_general_setting#emp_company" class="jslm_text">'.  esc_html(__("Company" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsemployer&wpjpconfigid=emp_general_setting#emp_memberlinks" class="jslm_text">'.  esc_html(__("Members Links" , 'wp-job-portal')) .'</a></li>
                </ul>
            </li>
            <li class="treeview" id="js_setting">
                <a class="js-icon-left" href="#" title="'. esc_html(__('job seeker' , 'wp-job-portal')) .'">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/joseeker.png" .'"/>
                    <span class="jslm_text wpjobportal-parent">'. esc_html(__("Job Seeker" , 'wp-job-portal')) .'</span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsjobseeker&wpjpconfigid=jobseeker_general_setting#js_generalsetting" class="jslm_text">'.  esc_html(__("General Settings",'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsjobseeker&wpjpconfigid=jobseeker_general_setting#js_resume_setting" class="jslm_text">'.  esc_html(__("Resume Settings" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurationsjobseeker&wpjpconfigid=jobseeker_general_setting#js_memberlinks" class="jslm_text">'.  esc_html(__("Members Links" , 'wp-job-portal')) .'</a></li>
                </ul>
            </li>
            <li class="treeview" id="apply_setting">
                <a class="js-icon-left" href="#" title="'. esc_html(__('Job Apply' , 'wp-job-portal')) .'">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/apply-config.png" .'"/>
                    <span class="jslm_text wpjobportal-parent">'. esc_html(__("Job Apply settings" , 'wp-job-portal')) .'</span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=job_apply#quick_apply" class="jslm_text">'.  esc_html(__("Quick Apply settings",'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=job_apply#job_apply_settings" class="jslm_text">'.  esc_html(__("Job Apply settings",'wp-job-portal')) .'</a></li>
                </ul>
            </li>


            <li class="treeview" id="ai_setting">
                <a class="js-icon-left" href="#" title="'. esc_html(__('AI Settings' , 'wp-job-portal')) .'">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/ai-addons.png" .'"/>
                    <span class="jslm_text wpjobportal-parent">'. esc_html(__("AI settings" , 'wp-job-portal')) .'</span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu"> ';

                    if(in_array('aijobsearch', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=ai_settings#aijobsearch" class="jslm_text">'.  esc_html(__("AI Job Search",'wp-job-portal')) .'</a></li>';
                    } else {
                        $plugininfo = checkWPJPPluginInfo('wp-job-portal-aijobsearch/wp-job-portal-aijobsearch.php');
                        if($plugininfo['availability'] == "1"){
                            $text = $plugininfo['text'];
                            $url = "plugins.php?s=wp-job-portal-aijobsearch&plugin_status=inactive";
                        }elseif($plugininfo['availability'] == "0"){
                            $text = $plugininfo['text'];
                            $url = "https://wpjobportal.com/product/social-share/";
                        }
                        $html .= '<li class="disabled-menu">
                                    <span class="wpjobportaladmin-text">'. esc_html(__('AI Job Search' , 'wp-job-portal')).'</span>
                                    <a href="'. esc_url($url).'" class="jslm_text">'. esc_html($text).'</a>
                                 </li>';
                    }

                    if(in_array('aisuggestedjobs', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=ai_settings#aisuggestedjobs" class="jslm_text">'.  esc_html(__("AI Suggested Jobs",'wp-job-portal')) .'</a></li>';
                    } else {
                        $plugininfo = checkWPJPPluginInfo('wp-job-portal-aisuggestedjobs/wp-job-portal-aisuggestedjobs.php');
                        if($plugininfo['availability'] == "1"){
                            $text = $plugininfo['text'];
                            $url = "plugins.php?s=wp-job-portal-aisuggestedjobs&plugin_status=inactive";
                        }elseif($plugininfo['availability'] == "0"){
                            $text = $plugininfo['text'];
                            $url = "https://wpjobportal.com/product/social-share/";
                        }
                        $html .= '<li class="disabled-menu">
                                    <span class="wpjobportaladmin-text">'. esc_html(__('AI Suggested Jobs' , 'wp-job-portal')).'</span>
                                    <a href="'. esc_url($url).'" class="jslm_text">'. esc_html($text).'</a>
                                 </li>';
                    }

                    if(in_array('airesumesearch', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=ai_settings#airesumesearch" class="jslm_text">'.  esc_html(__("AI Resume Search",'wp-job-portal')) .'</a></li>';
                    } else {
                        $plugininfo = checkWPJPPluginInfo('wp-job-portal-airesumesearch/wp-job-portal-airesumesearch.php');
                        if($plugininfo['availability'] == "1"){
                            $text = $plugininfo['text'];
                            $url = "plugins.php?s=wp-job-portal-airesumesearch&plugin_status=inactive";
                        }elseif($plugininfo['availability'] == "0"){
                            $text = $plugininfo['text'];
                            $url = "https://wpjobportal.com/product/social-share/";
                        }
                        $html .= '<li class="disabled-menu">
                                    <span class="wpjobportaladmin-text">'. esc_html(__('AI Resume Search' , 'wp-job-portal')).'</span>
                                    <a href="'. esc_url($url).'" class="jslm_text">'. esc_html($text).'</a>
                                 </li>';
                    }

                    if(in_array('aisuggestedresumes', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=ai_settings#aisuggestedresumes" class="jslm_text">'.  esc_html(__("AI Suggested Resumes",'wp-job-portal')) .'</a></li>';
                    } else {
                        $plugininfo = checkWPJPPluginInfo('wp-job-portal-aisuggestedresumes/wp-job-portal-aisuggestedresumes.php');
                        if($plugininfo['availability'] == "1"){
                            $text = $plugininfo['text'];
                            $url = "plugins.php?s=wp-job-portal-aisuggestedresumes&plugin_status=inactive";
                        }elseif($plugininfo['availability'] == "0"){
                            $text = $plugininfo['text'];
                            $url = "https://wpjobportal.com/product/social-share/";
                        }
                        $html .= '<li class="disabled-menu">
                                    <span class="wpjobportaladmin-text">'. esc_html(__('AI Suggested Resumes' , 'wp-job-portal')).'</span>
                                    <a href="'. esc_url($url).'" class="jslm_text">'. esc_html($text).'</a>
                                 </li>';
                    }

                $html .= '
                </ul>
            </li>


            <li class="treeview" id="vis_setting">
                <a class="js-icon-left" href="#" title="'. esc_html(__('visitor setting' , 'wp-job-portal')) .'">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/user.png" .'"/>
                    <span class="jslm_text wpjobportal-parent">'. esc_html(__("Visitor Settings" , 'wp-job-portal')) .'</span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=visitor_setting#captcha_setting" class="jslm_text">'.  esc_html(__("Captcha Settings",'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=visitor_setting#visitor_setting_employer_side" class="jslm_text">'.  esc_html(__("Employer Settings" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=visitor_setting#js_visitor" class="jslm_text">'.  esc_html(__("Jobseeker Settings" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=visitor_setting#emp_visitorlinks" class="jslm_text">'.  esc_html(__("Employer Links" , 'wp-job-portal')) .'</a></li>
                    <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=visitor_setting#js_memberlinks" class="jslm_text">'.  esc_html(__("Jobseeker Links" , 'wp-job-portal')) .'</a></li>
                </ul>
            </li>

            ';
            if(in_array('credits', wpjobportal::$_active_addons)){
                 $html .= '<li class="treeview" id="pack_setting">
                    <a class="js-icon-left" href="#" title="'. esc_html(__('package setting' , 'wp-job-portal')) .'">
                        <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/package.png" .'"/>
                        <span class="jslm_text wpjobportal-parent">'. esc_html(__("Package Settings" , 'wp-job-portal')) .'</span>
                    </a>
                    <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=package_setting#package" class="jslm_text">'.  esc_html(__("Free Packages",'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=package_setting#paid_submission" class="jslm_text">'.  esc_html(__("Paid Submissions" , 'wp-job-portal')) .'</a></li>
                    </ul>
                </li>';
            } else {
                $plugininfo = checkWPJPPluginInfo('wp-job-portal-credits/wp-job-portal-credits.php');
                if($plugininfo['availability'] == "1"){
                    $text = $plugininfo['text'];
                    $url = "plugins.php?s=wp-job-portal-credits&plugin_status=inactive";
                }elseif($plugininfo['availability'] == "0"){
                    $text = $plugininfo['text'];
                    $url = "https://wpjobportal.com/product/credit-system/";
                }
                $html .= '<li class="disabled-menu">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/package-grey.png" .'"/>
                    <span class="wpjobportaladmin-text">'. esc_html(__('Package Settings' , 'wp-job-portal')).'</span>
                    <a href="'. esc_url($url).'" class="wpjobportaladmin-install-btn" title="'. esc_attr($text).'">'. esc_html($text).'</a>
               </li>';
            }
            $html .= '<li class="treeview" id="social_setting">
                <a class="js-icon-left" href="#" title="'. esc_html(__('social apps' , 'wp-job-portal')) .'">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/social_share.png" .'"/>
                    <span class="jslm_text wpjobportal-parent">'. esc_html(__(" Social Apps" , 'wp-job-portal')) .'</span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">';
                    if(in_array('socialshare', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=social_share#socialsharing" class="jslm_text">'.  esc_html(__("Social Links",'wp-job-portal')) .'</a></li>';
                    } else {
                        $plugininfo = checkWPJPPluginInfo('wp-job-portal-socialshare/wp-job-portal-socialshare.php');
                        if($plugininfo['availability'] == "1"){
                            $text = $plugininfo['text'];
                            $url = "plugins.php?s=wp-job-portal-socialshare&plugin_status=inactive";
                        }elseif($plugininfo['availability'] == "0"){
                            $text = $plugininfo['text'];
                            $url = "https://wpjobportal.com/product/social-share/";
                        }
                        $html .= '<li class="disabled-menu">
                                    <span class="wpjobportaladmin-text">'. esc_html(__('Social Share' , 'wp-job-portal')).'</span>
                                    <a href="'. esc_url($url).'" class="jslm_text">'. esc_html($text).'</a>
                                 </li>';
                    }
                    if(in_array('sociallogin', wpjobportal::$_active_addons)){
                        $html .= '<li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=social_share#facebook" class="jslm_text">'.  esc_html(__("Facebook" , 'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=social_share#linkedin" class="jslm_text">'.  esc_html(__("Linkedin" , 'wp-job-portal')) .'</a></li>';
                    } else {
                        $plugininfo = checkWPJPPluginInfo('wp-job-portal-sociallogin/wp-job-portal-sociallogin.php');
                        if($plugininfo['availability'] == "1"){
                            $text = $plugininfo['text'];
                            $url = "plugins.php?s=wp-job-portal-sociallogin&plugin_status=inactive";
                        }elseif($plugininfo['availability'] == "0"){
                            $text = $plugininfo['text'];
                            $url = "https://wpjobportal.com/product/social-login/";
                        }
                        $html .= '<li class="disabled-menu">
                                    <span class="wpjobportaladmin-text">'. esc_html(__('Social Login' , 'wp-job-portal')).'</span>
                                    <a href="'. esc_url($url).'" class="jslm_text">'. esc_html($text).'</a>
                                 </li>';
                    }
                $html .= '</ul>
            </li>';
            if(in_array('rssfeedback', wpjobportal::$_active_addons)){
                $html .= '<li class="treeview" id="rs_setting">
                    <a class="js-icon-left" href="#" title="'. esc_html(__('rss' , 'wp-job-portal')) .'">
                        <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/rss.png" .'"/>
                        <span class="jslm_text wpjobportal-parent">'. esc_html(__("RSS" , 'wp-job-portal')) .'</span>
                    </a>
                    <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=rss_setting#rssjob" class="jslm_text">'.  esc_html(__("Job Settings",'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=rss_setting#rssresume" class="jslm_text">'.  esc_html(__("Resume Settings" , 'wp-job-portal')) .'</a></li>
                    </ul>
                </li>';
            } else {
                $plugininfo = checkWPJPPluginInfo('wp-job-portal-rssfeedback/wp-job-portal-rssfeedback.php');
                if($plugininfo['availability'] == "1"){
                    $text = $plugininfo['text'];
                    $url = "plugins.php?s=wp-job-portal-rssfeedback&plugin_status=inactive";
                }elseif($plugininfo['availability'] == "0"){
                    $text = $plugininfo['text'];
                    $url = "https://wpjobportal.com/product/rss-2/";
                }
                $html .= '<li class="disabled-menu">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/package-grey.png" .'"/>
                    <span class="wpjobportaladmin-text">'. esc_html(__('RSS' , 'wp-job-portal')).'</span>
                    <a href="'. esc_url($url).'" class="wpjobportaladmin-install-btn" title="'. esc_attr($text).'">'. esc_html($text).'</a>
               </li>';
            }
            $html .= '<li class="treeview" id="lr_setting">
                    <a class="js-icon-left" href="#" title="'. esc_html(__('login/register' , 'wp-job-portal')) .'">
                        <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/login.png" .'"/>
                        <span class="jslm_text wpjobportal-parent">'. esc_html(__(" Login/Register" , 'wp-job-portal')) .'</span>
                    </a>
                    <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=login_register#login" class="jslm_text">'.  esc_html(__("Login",'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=login_register#register" class="jslm_text">'.  esc_html(__("Register" , 'wp-job-portal')) .'</a></li>
                    </ul>
                </li>';
            if(in_array('credits', wpjobportal::$_active_addons)){
                $html .= '<li class="treeview" id="pm_setting">
                    <a class="js-icon-left" href="#" title="'. esc_html(__('payment method' , 'wp-job-portal')) .'">
                        <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/payment.png" .'"/>
                        <span class="jslm_text wpjobportal-parent">'. esc_html(__("Payment Method" , 'wp-job-portal')) .'</span>
                    </a>
                    <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_paymentmethodconfiguration&wpjpconfigid=pay_setting#paypal" class="jslm_text">'.  esc_html(__("PayPal",'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_paymentmethodconfiguration&wpjpconfigid=pay_setting#stripe" class="jslm_text">'.  esc_html(__("Stripe" , 'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_paymentmethodconfiguration&wpjpconfigid=pay_setting#others" class="jslm_text">'.  esc_html(__("Woocommerce" , 'wp-job-portal')) .'</a></li>
                    </ul>
                </li>';
            }
            else{
                $plugininfo = checkWPJPPluginInfo('wp-job-portal-credits/wp-job-portal-credits.php');
                if($plugininfo['availability'] == "1"){
                    $text = $plugininfo['text'];
                    $url = "plugins.php?s=wp-job-portal-credits&plugin_status=inactive";
                }elseif($plugininfo['availability'] == "0"){
                    $text = $plugininfo['text'];
                    $url = "https://wpjobportal.com/product/credit-system/";
                }
                $html .= '<li class="disabled-menu">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/payment_grey.png" .'"/>
                    <span class="wpjobportaladmin-text">'. esc_html(__('Payment Method' , 'wp-job-portal')).'</span>
                    <a href="'. esc_url($url).'" class="wpjobportaladmin-install-btn" title="'. esc_attr($text).'">'. esc_html($text).'</a>
                </li>';
            }
            if(in_array('cronjob', wpjobportal::$_active_addons)){
                $html .= '<li class="treeview" id="cj_setting">
                    <a class="js-icon-left" href="#" title="'. esc_html(__('cron job' , 'wp-job-portal')) .'">
                        <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/cron_job.png" .'"/>
                        <span class="jslm_text wpjobportal-parent">'. esc_html(__("Cron Job" , 'wp-job-portal')) .'</span>
                    </a>
                    <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_cronjob&wpjobportallt=cronjob&wpjpconfigid=cron_setting#webcrown" class="jslm_text">'.  esc_html(__("Webcrown.org",'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_cronjob&wpjobportallt=cronjob&wpjpconfigid=cron_setting#wget" class="jslm_text">'.  esc_html(__("Wget" , 'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_cronjob&wpjobportallt=cronjob&wpjpconfigid=cron_setting#curl" class="jslm_text">'.  esc_html(__("Curl" , 'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_cronjob&wpjobportallt=cronjob&wpjpconfigid=cron_setting#phpscript" class="jslm_text">'.  esc_html(__("Php Script" , 'wp-job-portal')) .'</a></li>
                        <li class="wpjobportal-child"><a href="admin.php?page=wpjobportal_cronjob&wpjobportallt=cronjob&wpjpconfigid=cron_setting#url" class="jslm_text">'.  esc_html(__("Website" , 'wp-job-portal')) .'</a></li>
                    </ul>
                </li>';
            }else{
                $plugininfo = checkWPJPPluginInfo('wp-job-portal-cronjob/wp-job-portal-cronjob.php');
                if($plugininfo['availability'] == "1"){
                    $text = $plugininfo['text'];
                    $url = "plugins.php?s=wp-job-portal-cronjob&plugin_status=inactive";
                }elseif($plugininfo['availability'] == "0"){
                    $text = $plugininfo['text'];
                    $url = "https://wpjobportal.com/product/cron-job-copy/";
                }
                $html .= '<li class="disabled-menu">
                    <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/config/cron_job_grey.png" .'"/>
                    <span class="wpjobportaladmin-text">'. esc_html(__('Cron Job' , 'wp-job-portal')).'</span>
                    <a href="'. esc_url($url).'" class="wpjobportaladmin-install-btn" title="'. esc_attr($text).'">'. esc_html($text).'</a>
                </li>';
             }
        $html .= '</ul>';
        return $html;
    }

    // update single configuration from overview page
    function storeConfigurationSingle() {
        // nonce check
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (!wp_verify_nonce($nonce, 'wpjobportal_configuration_nonce')) {
            die('Security check Failed');
        }

        // onlyy admin can use this fucntion
        if (!current_user_can('manage_options')) {
            return false;
        }

        $config_name = WPJOBPORTALrequest::getVar('config_name', '', '');
        $config_value = WPJOBPORTALrequest::getVar('config_value', '', '');

        if($config_name == ''){
            return false;
        }

        // not sure about this if code
        // if($config_value == ''){
        //     return false;
        // }


        // List of allowed configurations to avoud issues
        $allowed_configs = array(
            'companyautoapprove',
        );

        if (!in_array($config_name, $allowed_configs)) {
            return false;
        }

        $error = false;
        $query = "UPDATE `" . wpjobportal::$_db->prefix . "wj_portal_config`
                  SET `configvalue` = '".esc_sql($config_value)."'
                  WHERE `configname` = '".esc_sql($config_name)."'";
                  echo var_dump(wpjobportaldb::query($query));
                  exit;
        if (wpjobportaldb::query($query)) {
            $error = true;
        }
        return $error;
    }
}

?>
