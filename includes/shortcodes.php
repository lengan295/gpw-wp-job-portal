<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALshortcodes {

    function __construct() {

        add_shortcode('wpjobportal_employer_controlpanel', array($this, 'show_employer_controlpanel'));
        add_shortcode('wpjobportal_jobseeker_controlpanel', array($this, 'show_jobseeker_controlpanel'));

        add_shortcode('wpjobportal_job_search', array($this, 'show_job_search'));
        add_shortcode('wpjobportal_job', array($this, 'show_job'));

        add_shortcode('wpjobportal_job_categories', array($this, 'show_job_categories'));
        add_shortcode('wpjobportal_job_types', array($this, 'show_job_types'));
        add_shortcode('wpjobportal_my_appliedjobs', array($this, 'show_my_appliedjobs'));
        add_shortcode('wpjobportal_my_companies', array($this, 'show_my_companies'));

        add_shortcode('wpjobportal_my_departments', array($this, 'show_my_departments'));
        add_shortcode('wpjobportal_my_jobs', array($this, 'show_my_jobs'));
        add_shortcode('wpjobportal_my_resumes', array($this, 'show_my_resumes'));
        add_shortcode('wpjobportal_add_company', array($this, 'show_add_company'));
        add_shortcode('wpjobportal_add_department', array($this, 'show_add_department'));
        add_shortcode('wpjobportal_add_job', array($this, 'show_add_job'));
        add_shortcode('wpjobportal_add_resume', array($this, 'show_add_resume'));
        add_shortcode('wpjobportal_employer_registration', array($this, 'show_employer_registration'));
        add_shortcode('wpjobportal_jobseeker_registration', array($this, 'show_jobseeker_registration'));
        add_shortcode('wpjobportal_registration', array($this, 'show_registration'));

        add_shortcode('wpjobportal_jobseeker_my_stats', array($this, 'show_jobseeker_my_stats'));
        add_shortcode('wpjobportal_employer_my_stats', array($this, 'show_employer_my_stats'));
        add_shortcode('wpjobportal_login_page', array($this, 'show_login_page'));
        /**
        * @param wp job portal widgets Shortcdes
        * Support Blog template
        * add_shortcodes widget
        */
        add_shortcode('wpjobportal_jobs', array($this, 'show_jobs'));
        add_shortcode('wpjobportal_resumes', array($this, 'show_resumes'));
        add_shortcode('wpjobportal_companies', array($this, 'show_companies'));
        add_shortcode('wpjobportal_searchjob', array($this, 'show_searchjob'));
        add_shortcode('wpjobportal_searchresume', array($this, 'show_searchresume'));
        add_shortcode('wpjobportal_jobbycategory', array($this, 'show_jobbycategory'));
        add_shortcode('wpjobportal_jobbytypes', array($this, 'show_jobbytypes'));
        add_shortcode('wpjobportal_jobstats', array($this, 'show_jobstats'));
        add_shortcode('wpjobportal_jobsbycities', array($this, 'show_jobsbycity'));
        add_shortcode('wpjobportal_jobsbystate', array($this, 'show_jobsbystate'));
        add_shortcode('wpjobportal_jobsbycountries', array($this, 'show_jobsbycountries'));
        add_shortcode('wpjobportal_jobsonmap', array($this, 'show_jobsonmap'));

    }

    function show_employer_controlpanel($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'employer',
            'wpjobportallt' => 'controlpanel',
        );

        $default_short_code_options = array(
            'hide_profile_section' => '',
            'hide_graph' => '',
            'hide_recent_applications' => '',
            'hide_stat_boxes' => '',
            'hide_invoices' => '',
        );

        $sanitized_args = shortcode_atts($defaults, $raw_args);
        $shortcode_options = shortcode_atts($default_short_code_options, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }

        if(isset(wpjobportal::$_data['shortcode_options']) && !empty(wpjobportal::$_data['shortcode_options'])){
            wpjobportal::$_data['shortcode_options'] += $shortcode_options;
        }else{
            wpjobportal::$_data['shortcode_options'] = $shortcode_options;
        }

        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'employer');
            $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'controlpanel');
            $employerarray = array('addcompany', 'mycompanies', 'adddepartment', 'mydepartments', 'addfolder', 'myfolders', 'addjob', 'myjobs');
            $isouruser = WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser();
            $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();
            if (in_array($layout, $employerarray) && $isouruser == false && $isguest == false) {
                WPJOBPORTALincluder::include_file('newinwpjobportal', 'common');
            } else {
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
            }
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_jobseeker_controlpanel($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'jobseeker',
            'wpjobportallt' => 'controlpanel',
        );

        $default_short_code_options = array(
            'hide_profile_section' => '',
            'hide_graph' => '',
            'hide_job_applies' => '',
            'hide_newest_jobs' => '',
            'hide_stat_boxes' => '',
            'hide_invoices' => '',
        );

        $sanitized_args = shortcode_atts($defaults, $raw_args);
        $shortcode_options = shortcode_atts($default_short_code_options, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }

        if(isset(wpjobportal::$_data['shortcode_options']) && !empty(wpjobportal::$_data['shortcode_options'])){
            wpjobportal::$_data['shortcode_options'] += $shortcode_options;
        }else{
            wpjobportal::$_data['shortcode_options'] = $shortcode_options;
        }

        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'jobseeker');
            $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'controlpanel');
            $jobseekerarray = array('myresumes','myappliedjobs');
            $isouruser = WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser();
            $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();
            if (in_array($layout, $jobseekerarray) && $isouruser == false && $isguest == false) {
                WPJOBPORTALincluder::include_file('newinwpjobportal', 'common');
            } else {
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
            }
        }
        unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_job_search($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'jobsearch',
            'wpjobportallt' => 'jobsearch',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'jobsearch');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
        unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_job($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'job',
            'wpjobportallt' => 'jobs',
            'show_only_featured_jobs' => '0',
        );

        $default_short_code_options = array(
            'no_of_jobs' => '',
            'hide_filter' => '',
            'hide_filter_job_title' => '',
            'hide_filter_job_location' => '',
            'hide_company_logo' => '',
            'hide_company_name' => '',

            'companies' => '',
            'categories' => '',
            'types' => '',
            'locations' => '',
            'ids' => '',
            'careerlevels' => '',
            'jobstatuses' => '',
            'tags' => '',
            'sorting' => '',
        );

        $sanitized_args = shortcode_atts($defaults, $raw_args);
        $shortcode_options = shortcode_atts($default_short_code_options, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }

        if(isset(wpjobportal::$_data['shortcode_options']) && !empty(wpjobportal::$_data['shortcode_options'])){
            wpjobportal::$_data['shortcode_options'] += $shortcode_options;
        }else{
            wpjobportal::$_data['shortcode_options'] = $shortcode_options;
        }

        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'job');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
        unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_job_categories($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'job',
            'wpjobportallt' => 'jobsbycategories',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'job');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_job_types($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'job',
            'wpjobportallt' => 'jobsbytypes',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'job');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_appliedjobs($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'jobapply',
            'wpjobportallt' => 'myappliedjobs',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'jobapply');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_companies($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        if(in_array('multicompany', wpjobportal::$_active_addons)){
            $mod = "multicompany";
        }else{
            $mod = "company";
        }
        $defaults = array(
            'wpjobportalme' => $mod,
            'wpjobportallt' => 'mycompanies',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'company');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }


    function show_my_departments($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'departments',
            'wpjobportallt' => 'mydepartments',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'departments');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_jobs($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'job',
            'wpjobportallt' => 'myjobs',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'job');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_my_resumes($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'resume',
            'wpjobportallt' => 'myresumes',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'resume');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_add_company($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        if(in_array('multicompany', wpjobportal::$_active_addons)){
            $mod = "multicompany";
        }else{
            $mod = "company";
        }
        $defaults = array(
            'wpjobportalme' => $mod,
            'wpjobportallt' => 'addcompany',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'company');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }



    function show_add_department($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'departments',
            'wpjobportallt' => 'adddepartment',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'departments');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_add_job($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'job',
            'wpjobportallt' => 'addjob',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'job');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_add_resume($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        if(in_array('multiresume', wpjobportal::$_active_addons)){
            $mod = "multiresume";
        }else{
            $mod = "resume";
        }
        $defaults = array(
            'wpjobportalme' => $mod,
            'wpjobportallt' => 'addresume',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'resume');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }



    function show_employer_registration($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'user',
            'wpjobportallt' => 'regemployer',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'user');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_registration($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'user',
            'wpjobportallt' => 'userregister',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'user');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
        unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_jobseeker_registration($raw_args, $content = null) {
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'user',
            'wpjobportallt' => 'regjobseeker',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'user');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_jobseeker_my_stats($raw_args, $content = null){
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'jobseeker',
            'wpjobportallt' => 'mystats',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'jobseeker');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_employer_my_stats($raw_args, $content = null){
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'employer',
            'wpjobportallt' => 'mystats',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'employer');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_login_page($raw_args, $content = null){
        //default set of parameters for the front end shortcodes
        ob_start();
        $defaults = array(
            'wpjobportalme' => 'wpjobportal',
            'wpjobportallt' => 'login',
        );
        $sanitized_args = shortcode_atts($defaults, $raw_args);
        if(isset(wpjobportal::$_data['sanitized_args']) && !empty(wpjobportal::$_data['sanitized_args'])){
            wpjobportal::$_data['sanitized_args'] += $sanitized_args;
        }else{
            wpjobportal::$_data['sanitized_args'] = $sanitized_args;
        }
        $pageid = get_the_ID();
        wpjobportal::wpjobportal_setPageID($pageid);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isdisabled()) { // handling for the user disabled
            WPJOBPORTALlayout::getUserDisabledMsg();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'wpjobportal');
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($module);
        }
                unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_searchjob($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Search job', 'wp-job-portal')),
            'showtitle' => '1',
            'jobtitle' => '1',
            'category' => '1',
            'jobtype' => '1',
            'jobstatus' => '1',
            'salaryrange' => '1',
            'shift' => '1',
            'duration' => '1',
            'startpublishing' => '1',
            'stoppublishing' => '1',
            'company' => '1',
            'address' => '1',
            'columnperrow' => '1',
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $modules_html = WPJOBPORTALincluder::getJSModel('jobsearch')->getSearchJobs_Widget($arr->title, $arr->showtitle, $arr->jobtitle, $arr->category, $arr->jobtype, $arr->jobstatus, $arr->salaryrange, $arr->shift, $arr->duration, $arr->startpublishing, $arr->stoppublishing, $arr->company, $arr->address, $arr->columnperrow);
            echo wp_kses($modules_html, WPJOBPORTAL_ALLOWED_TAGS);
        }
        unset(wpjobportal::$_data['sanitized_args']);
        $content .= ob_get_clean();
        return $content;
    }

    function show_resumes($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Resumes', 'wp-job-portal')),
            'typeofresume' => '1',
            'showtitle' => '1',
            'applicationtitle' => '1',
            'name' => '1',
            'category' => '1',
            'jobtype' => '1',
            'experience' => '1',
            'available' => '1',
            'gender' => '1',
            'nationality' => '1',
            'location' => '1',
            'posted' => '1',
            'noofresume' => '5',
            'listingstyle' => '1',
            'boxstyle' => '1',
            'fieldcolumn' => '1',
            'moduleheight' => '400',
            'resumeheight' => '250',
            'logowidth' => '150',
            'logoheight' => '90',
            'resumephoto' => '1',
            'nofresumedesktop' => '1',
            'nofresumetablet' => '1',
            'topmargin' => '10',
            'leftmargin' => '10',
            'titlecolor' => '',
            'titleborderbottom' => '',
            'backgroundcolor' => '',
            'bordercolor' => '',
            'datalabelcolor' => '',
            'datavaluecolor' => '',
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);
        $arr->subcategory = 0;

        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            //Frontend HTML starts -----------
            $mod = 'fd';
            if ($arr->typeofresume == 1) {
                $mod = 'newestresume';
            } elseif ($arr->typeofresume == 2) {
                $mod = 'topresume';
            }  elseif ($arr->typeofresume == 4) {
                $mod = 'featuredresume';
            }

            $layoutName = $mod . uniqid();

            if ($arr->typeofresume != 0) {

                $resumes = WPJOBPORTALincluder::getJSModel('resume')->getResumes_Widget($arr->typeofresume, $arr->noofresume);
                // parameters [for later use]
                $speedTest = '';
                $sliding = '';
                $consecutivesliding = '';
                $slidingdirection = '';
                $separator = '';

                $modules_html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->listModuleResumes($layoutName, $resumes, $arr->noofresume, $arr->applicationtitle, $arr->name, $arr->experience, $arr->available, $arr->gender, $arr->nationality, $arr->location, $arr->category, $arr->subcategory, $arr->jobtype, $arr->posted, $separator, $arr->moduleheight, $arr->resumeheight, $arr->topmargin, $arr->leftmargin, $arr->logowidth, $arr->logoheight, $arr->fieldcolumn, $arr->listingstyle, $arr->title, $arr->showtitle, $speedTest, $sliding, $consecutivesliding, $slidingdirection, $arr->resumephoto, $arr->nofresumedesktop, $arr->nofresumetablet, $arr->boxstyle);
                echo wp_kses($modules_html, WPJOBPORTAL_ALLOWED_TAGS);
                $classname = $layoutName;

                $color1 = $arr->titlecolor;
                $color2 = $arr->titleborderbottom;
                $color3 = $arr->backgroundcolor;
                $color4 = $arr->bordercolor;
                $color5 = $arr->datalabelcolor;
                $color6 = $arr->datavaluecolor;

                $echo_style = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->perpareStyleSheet($classname , $color1 , $color2 , $color3 , $color4 , $color5 , $color6 );
                echo wp_kses($echo_style, WPJOBPORTAL_ALLOWED_TAGS);
            }
        }

        $content .= ob_get_clean();
        return $content;
    }

    function show_companies($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Companies', 'wp-job-portal')),
            'companytype' => '1',
            'showtitle' => '1',
            'companylogo' => '1',
            'category' => '1',
            'location' => '1',
            'posted' => '1',
            'noofcompanies' => '5',
            'listingstyle' => '1',
            'boxstyle' => '1',
            'fieldcolumn' => '1',
            'moduleheight' => '400',
            'companyheight' => '250',
            'complogowidth' => '150',
            'complogoheight' => '90',
            'nofcompanies' => '1',
            'nofcompaniesrowtab' => '1',
            'topmargin' => '10',
            'leftmargin' => '10',
            'titlecolor' => '',
            'titleborderbottom' => '',
            'backgroundcolor' => '',
            'bordercolor' => '',
            'datalabelcolor' => '',
            'datavaluecolor' => '',
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);

        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $mod = 'abc';
            if ($arr->companytype == 2) {
                $mod = 'featuredcompany';
            }
            $layoutName = $mod . uniqid();

            if ($arr->companytype != 0) {

                $companies = WPJOBPORTALincluder::getJSModel('company')->getCompanies_Widget($arr->companytype, $arr->noofcompanies);
                //parameters [for later use]
                $theme = '';
                $jobwidth = '';
                $jobfloat = '';
                $speedTest = '';
                $sliding = '';
                $slidingdirection = '';
                $consecutivesliding = '';

                $modules_html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->listModuleCompanies($layoutName, $companies, $arr->noofcompanies, $arr->category, $arr->posted, $arr->listingstyle, $theme, $arr->location, $arr->moduleheight, $jobwidth, $arr->companyheight, $jobfloat, $arr->topmargin, $arr->leftmargin, $arr->companylogo, $arr->complogowidth, $arr->complogoheight, $arr->fieldcolumn, $arr->listingstyle, $arr->title, $arr->showtitle, $speedTest, $sliding, $slidingdirection, $consecutivesliding, $arr->nofcompanies, $arr->nofcompaniesrowtab, $arr->boxstyle);

                echo wp_kses($modules_html, WPJOBPORTAL_ALLOWED_TAGS);
                $classname = $layoutName;

                $color1 = $arr->titlecolor;
                $color2 = $arr->titleborderbottom;
                $color3 = $arr->backgroundcolor;
                $color4 = $arr->bordercolor;
                $color5 = $arr->datalabelcolor;
                $color6 = $arr->datavaluecolor;

                $echo_style = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->perpareStyleSheet($classname , $color1 , $color2 , $color3 , $color4 , $color5 , $color6 );
                echo wp_kses($echo_style, WPJOBPORTAL_ALLOWED_TAGS);
            }
        }
        $content .= ob_get_clean();
        return $content;
    }

    function show_searchresume($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Search Resume', 'wp-job-portal')),
            'showtitle' => '1',
            'apptitle' => '1',
            'name' => '1',
            'natinality' => '1',
            'gender' => '1',
            'iamavailable' => '1',
            'category' => '1',
            'jobtype' => '1',
            'salaryrange' => '1',
            'heighesteducation' => '1',
            'experience' => '1',
            'columnperrow' => '1',
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);

        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $modules_html = WPJOBPORTALincluder::getJSModel('resumesearch')->getSearchResume_Widget($arr->title, $arr->showtitle, $arr->apptitle, $arr->name, $arr->natinality, $arr->gender, $arr->iamavailable, $arr->category, $arr->jobtype, $arr->salaryrange, $arr->heighesteducation, $arr->columnperrow, $arr->experience);
            echo wp_kses($modules_html, WPJOBPORTAL_ALLOWED_TAGS);
        }

        $content .= ob_get_clean();
        return $content;
    }

    function show_jobbycategory($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Jobs By Categories', 'wp-job-portal')),
            'showtitle' => '1',
            'maximumrecords' => '20',
            'haverecords' => '1',
            'showallcats' => '2',
            'columnperrow' => '3',
            'titlecolor' => '',
            'backgroundcolor' => '',
            'bordercolor' => '',
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);

        wpjobportal::wpjobportal_addStyleSheets();

        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $classname = 'category' . uniqid();

            $color1 = $arr->titlecolor;
            $color2 = $arr->backgroundcolor;
            $color3 = $arr->bordercolor;

            $categories = WPJOBPORTALincluder::getJSModel('job')->getJobsBycategory_Widget($arr->showallcats, $arr->haverecords, $arr->maximumrecords);

            $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->listModuleByJobcatOrType($categories, $classname, $arr->showtitle, $arr->title, $arr->columnperrow, 2 );
            echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);

            $echo_style = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->prepareStyleForBlocks($classname, $color1, $color2, $color3);
            echo wp_kses($echo_style, WPJOBPORTAL_ALLOWED_TAGS);

        }

        $content .= ob_get_clean();
        return $content;
    }

    function show_jobbytypes($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Jobs By Types', 'wp-job-portal')),
            'showtitle' => '1',
            'maximumrecords' => '20',
            'haverecords' => '1',
            'showallcats' => '2',
            'columnperrow' => '3',
            'titlecolor' => '',
            'backgroundcolor' => '',
            'bordercolor' => '',
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);

        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $classname = 'jobtype' . uniqid();
            $color1 = $arr->titlecolor;
            $color2 = $arr->backgroundcolor;
            $color3 = $arr->bordercolor;

            $types = WPJOBPORTALincluder::getJSModel('job')->getJobsByTypes_Widget($arr->showallcats, $arr->haverecords, $arr->maximumrecords);

            $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->listModuleByJobcatOrType($types, $classname, $arr->showtitle, $arr->title, $arr->columnperrow, 1 );
            echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);

            $echo_style = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->prepareStyleForBlocks($classname, $color1, $color2, $color3);
            echo wp_kses($echo_style, WPJOBPORTAL_ALLOWED_TAGS);

        }

        $content .= ob_get_clean();
        return $content;
    }

    function show_jobsonmap($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Hot jobs', 'wp-job-portal')),
            'showtitle' => 1,
            'numberofjobs' => 20,
            'company' => 1,
            'category' => 1,
            'moduleheight' => 300,
            'mapzoom' => 10,
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);

        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $jobs = WPJOBPORTALincluder::getJSModel('job')->getNewestJobsForMap_Widget($arr->numberofjobs);
            $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->listModuleJobsForMap($jobs, $arr->title, $arr->showtitle, $arr->company, $arr->category, $arr->moduleheight, $arr->mapzoom);
            echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
        }

        $content .= ob_get_clean();
        return $content;
    }

    function show_jobstats($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Stats', 'wp-job-portal')),
            'showtitle' => '1',
            'employer' => '1',
            'jobseeker' => '1',
            'jobs' => '1',
            'companies' => '1',
            'activejobs' => '1',
            'resumes' => '1',
            'todaystats' => '1',
            'titlecolor' => '',
            'backgroundcolor' => '',
            'bordercolor' => '',
        );

        $arr = (object) shortcode_atts($defaults, $raw_args);

        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $classname = 'stats' . uniqid();
            $data = WPJOBPORTALincluder::getJSModel('common')->getJobsStats_Widget($classname, $arr->title, $arr->showtitle, $arr->employer, $arr->jobseeker, $arr->jobs, $arr->companies, $arr->activejobs, $arr->resumes, $arr->todaystats);
            $modules_html = WPJOBPORTALincluder::getJSModel('common')->listModuleJobsStats($classname, $arr->title, $arr->showtitle, $arr->employer, $arr->jobseeker, $arr->jobs, $arr->companies, $arr->activejobs, $arr->resumes, $arr->todaystats,$data);
            echo wp_kses($modules_html, WPJOBPORTAL_ALLOWED_TAGS);

            $color1 = $arr->titlecolor;
            $color2 = $arr->backgroundcolor;
            $color3 = $arr->bordercolor;

            $echo_style = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->prepareStyleForStats($classname, $color1, $color2, $color3);
            echo wp_kses($echo_style, WPJOBPORTAL_ALLOWED_TAGS);
        }

        $content .= ob_get_clean();
        return $content;
    }

    function show_jobsbycity($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Jobs by cities', 'wp-job-portal')),
            'showtitle' => '1',
            'maximumrecords' => '20',
            'haverecords' => '1',
            'columnperrow' => '3',
            'titlecolor' => '',
            'backgroundcolor' => '',
            'bordercolor' => '',
        );

        $showjobsby = 1; //for cities

        $arr = (object) shortcode_atts($defaults, $raw_args);
        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            if ($showjobsby != 0) {

                $jobs = WPJOBPORTALincluder::getJSModel('job')->getJobsBylocation_Widget($showjobsby, $arr->haverecords, $arr->maximumrecords);

                $classname = 'city' . uniqid();

                $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->listModuleLocation($jobs, $classname, $arr->showtitle, $arr->title, $arr->columnperrow, $showjobsby);

                echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);

                $color1 = $arr->titlecolor;
                $color2 = $arr->backgroundcolor;
                $color3 = $arr->bordercolor;

                $echo_style = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->prepareStyleForBlocks($classname, $color1, $color2, $color3);

                echo wp_kses($echo_style, WPJOBPORTAL_ALLOWED_TAGS);

            }
        }

        $content .= ob_get_clean();
        return $content;
    }

    function show_jobsbystate($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Jobs by state', 'wp-job-portal')),
            'showtitle' => '1',
            'maximumrecords' => '20',
            'haverecords' => '1',
            'columnperrow' => '3',
            'titlecolor' => '',
            'backgroundcolor' => '',
            'bordercolor' => '',
        );

        $showjobsby = 2; //for state

        $arr = (object) shortcode_atts($defaults, $raw_args);

        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $jobs = '';
            if ($showjobsby != 0) {

                $jobs = WPJOBPORTALincluder::getJSModel('job')->getJobsBylocation_Widget($showjobsby, $arr->haverecords, $arr->maximumrecords);

                $classname = 'state' . uniqid();

                $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->listModuleLocation($jobs, $classname, $arr->showtitle, $arr->title, $arr->columnperrow, $showjobsby);

                echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);

                $color1 = $arr->titlecolor;
                $color2 = $arr->backgroundcolor;
                $color3 = $arr->bordercolor;

                $echo_style = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->prepareStyleForBlocks($classname, $color1, $color2, $color3);

                echo wp_kses($echo_style, WPJOBPORTAL_ALLOWED_TAGS);
            }
        }

        $content .= ob_get_clean();
        return $content;
    }

    function show_jobsbycountries($raw_args, $content = null) {

        ob_start();

        $defaults = array(
            'title' => esc_html(__('Jobs by countries', 'wp-job-portal')),
            'showtitle' => '1',
            'maximumrecords' => '20',
            'haverecords' => '1',
            'columnperrow' => '3',
            'titlecolor' => '',
            'backgroundcolor' => '',
            'bordercolor' => '',
        );

        $showjobsby = 3; //for countries

        $arr = (object) shortcode_atts($defaults, $raw_args);

        wpjobportal::wpjobportal_addStyleSheets();
        $offline = wpjobportal::$_config->getConfigurationByConfigName('offline');
        if ($offline == 1) {
            WPJOBPORTALlayout::getSystemOffline();
        } else {
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            if($module != null){
                $pageid = get_the_ID();
                wpjobportal::wpjobportal_setPageID($pageid);
                wpjobportal::wpjobportal_addStyleSheets();
                wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
                WPJOBPORTALincluder::include_file($module);
                $content .= ob_get_clean();
                return $content;
            }
            $jobs = '';
            if ($showjobsby != 0) {

                $jobs = WPJOBPORTALincluder::getJSModel('job')->getJobsBylocation_Widget($showjobsby, $arr->haverecords, $arr->maximumrecords);

                $classname = 'country' . uniqid();

                $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->listModuleLocation($jobs, $classname, $arr->showtitle, $arr->title, $arr->columnperrow, $showjobsby);

                echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);

                $color1 = $arr->titlecolor;
                $color2 = $arr->backgroundcolor;
                $color3 = $arr->bordercolor;

                $echo_style = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->prepareStyleForBlocks($classname, $color1, $color2, $color3);

                echo wp_kses($echo_style, WPJOBPORTAL_ALLOWED_TAGS);
            }
        }

        $content .= ob_get_clean();
        return $content;
    }
}

$shortcodes = new WPJOBPORTALshortcodes();
add_action( 'init', 'js_wpjobportal_jobs_block' );

function js_wpjobportal_jobs_block(){
    if(!function_exists("register_block_type")){
        return;
    }
    wp_register_script(
        'wpjobportaljobsblock',
        esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/gutenberg/jobs.js',
        array( 'wp-blocks', 'wp-element','wp-editor' )
    );
    register_block_type( 'wpjobportal/wpjobportaljobsblock', array(
        'attributes'      => array(
            'title'    => array(
                'type'      => 'string',
                'default'   => '',
            ),
            'typeofjobs'    => array(
                'type'      => 'select',
                'default'   => '',
            ),
            'noofjobs'    => array(
                'type'      => 'string',
                'default'   => '',
            ),
            'fieldcolumn'    => array(
                'type'      => 'select',
                'default'   => '',
            ),
            'listingstyle' => array(
                'type' => 'select',
                'default' => ''
            ),
        ),
        'render_callback' => 'js_wpjobportal_jobs_block_widgets',
        'editor_script' => 'wpjobportaljobsblock',
    ) );
}

function js_wpjobportal_jobs_block_widgets($attributes, $content){
    $defaults = array(
        'wpjobportalpageid' => '0',
        'title' => esc_html(__('Newest jobs','wp-job-portal')),
        'typeofjobs' => '1',
        'noofjobs' => '5',
        'fieldcolumn' => '1',
        'listingstyle' => '0',
    );

    $sanitized_args = shortcode_atts($defaults, $attributes);
    if($sanitized_args['fieldcolumn'] == '' || $sanitized_args['fieldcolumn'] == 0){
        $sanitized_args['fieldcolumn'] = 1;
    }
    if($sanitized_args['noofjobs'] == '' || $sanitized_args['noofjobs'] == 0){
        $sanitized_args['noofjobs'] = 1;
    }
    if($sanitized_args['title'] == ''){
        $sanitized_args['title'] = 'Latest Jobs';
    }
    if($sanitized_args['wpjobportalpageid'] == '' || $sanitized_args['wpjobportalpageid'] == 0){
        $sanitized_args['wpjobportalpageid'] = wpjobportal::wpjobportal_getPageid();
    }
    if($sanitized_args['typeofjobs'] == '' || $sanitized_args['typeofjobs'] == 0){
        $sanitized_args['typeofjobs'] = 1;
    }
    if($sanitized_args['listingstyle'] == ''){
        $sanitized_args['listingstyle'] = 1;
    }

    $jobs = WPJOBPORTALincluder::getJSModel('job')->getJobs_Widget($sanitized_args['typeofjobs'], $sanitized_args['noofjobs']);
    //Frontend HTML starts -----------
    $mod = 'newestjobs';
    if ($sanitized_args['typeofjobs'] == 1) {
        $mod = 'newestjobs';
    } elseif ($sanitized_args['typeofjobs'] == 2) {
        $mod = 'topjobs';
    } elseif ($sanitized_args['typeofjobs'] == 3) {
        $mod = 'hotjobs';
    }  elseif ($sanitized_args['typeofjobs'] == 5) {
        $mod = 'featuredjobs';
    }
    $layoutName = $mod . uniqid();
    // parameeters to be use later
    $html = '';
    if ($jobs != '') {
        $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->getJOBSWidgetHTML($jobs,$sanitized_args['wpjobportalpageid'],$sanitized_args['title'],$sanitized_args['fieldcolumn'],$layoutName,$sanitized_args['listingstyle'],$sanitized_args['typeofjobs']);
    }
    wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style.css');
    if (is_rtl()) {
        wp_enqueue_style('wpjobportal-site-rtl', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/stylertl.css');
    }
    return $html;
}

add_action( 'init', 'js_wpjobportal_companies_block');

function js_wpjobportal_companies_block(){
    if(!function_exists("register_block_type")){
        return;
    }
    wp_register_script(
        'wpjobportalcompaniesblock',
        esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/gutenberg/companies.js',
        array( 'wp-blocks', 'wp-element','wp-editor' )
    );
    register_block_type( 'wpjobportal/wpjobportalcompaniesblock', array(
        'attributes'      => array(
            'title' => array(
                'type' => 'string',
                'default' => ''
            ),
            'companytype' => array(
                'type' => 'select',
                'default' => ''
            ),
            'listingstyle' => array(
                'type' => 'select',
                'default' => ''
            ),
            'fieldcolumn' => array(
                'type' => 'select',
                'default' =>''
            ),
            'noofcompanies' => array(
                'type' => 'string',
                'default' => ''
            ),
        ),
        'render_callback' => 'js_wpjobportal_companies_block_widgets',
        'editor_script' => 'wpjobportalcompaniesblock'
    ) );
}

function js_wpjobportal_companies_block_widgets($attributes, $content){
    $defaults = array(
        'wpjobportalpageid' => '0',
        'title' => esc_html(__('Companies','wp-job-portal')),
        'companytype' => '1',
        'fieldcolumn' => '1',
        'listingstyle' => '0',
        'noofcompanies' => '1',
    );
    $sanitized_args = shortcode_atts($defaults, $attributes);
    if($sanitized_args['fieldcolumn'] == '' || $sanitized_args['fieldcolumn'] == 0){
        $sanitized_args['fieldcolumn'] = 1;
    }
    if($sanitized_args['title'] == ''){
        $sanitized_args['title'] = 'Companies';
    }
    if($sanitized_args['wpjobportalpageid'] == '' || $sanitized_args['wpjobportalpageid'] == 0){
        $sanitized_args['wpjobportalpageid'] = wpjobportal::wpjobportal_getPageid();
    }
    if($sanitized_args['companytype'] == '' || $sanitized_args['companytype'] == 0){
        $sanitized_args['companytype'] = 2;
    }
    if($sanitized_args['noofcompanies'] == '' || $sanitized_args['noofcompanies'] == 0){
        $sanitized_args['noofcompanies'] = 1;
    }
    if($sanitized_args['listingstyle'] == ''){
        $sanitized_args['listingstyle'] = 1;
    }


    if ($sanitized_args['companytype'] == 2) {
        $mod = 'featuredcompany';
    }
    $layoutName = $mod . uniqid();
    $html = '';
    $companies = WPJOBPORTALincluder::getJSModel('company')->getCompanies_Widget($sanitized_args['companytype'], $sanitized_args['noofcompanies']);
    $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->getCompanies_WidgetHtml($sanitized_args['title'],$layoutName, $companies, $sanitized_args['noofcompanies'], $sanitized_args['listingstyle'],$sanitized_args['companytype'],$sanitized_args['fieldcolumn']);
    wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style.css');
    if (is_rtl()) {
        wp_enqueue_style('wpjobportal-site-rtl', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/stylertl.css');
    }
    return $html;
}

add_action( 'init', 'js_wpjobportal_resumes_block');

function js_wpjobportal_resumes_block(){
    if(!function_exists("register_block_type")){
        return;
    }
    wp_register_script(
        'wpjobportalresumesblock',
        esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/gutenberg/resumes.js',
        array( 'wp-blocks', 'wp-element','wp-editor' )
    );
    register_block_type( 'wpjobportal/wpjobportalresumesblock', array(
        'attributes'      => array(
            'title' => array(
                'type' => 'string',
                'default' => ''
            ),
            'typeofresume' => array(
                'type' => 'select',
                'default' => ''
            ),
            'listingstyle' => array(
                'type' => 'select',
                'default' => ''
            ),
            'fieldcolumn' => array(
                'type' => 'select',
                'default' =>''
            ),
            'noofresumes' => array(
                'type' => 'string',
                'default' => ''
            ),
        ),
        'render_callback' => 'js_wpjobportal_resumes_block_widgets',
        'editor_script' => 'wpjobportalresumesblock'
    ) );
}

function js_wpjobportal_resumes_block_widgets($attributes, $content){
    $defaults = array(
        'wpjobportalpageid' => '0',
        'title' => esc_html(__('Latest Resumes','wp-job-portal')),
        'typeofresume' => '1',
        'fieldcolumn' => '1',
        'listingstyle' => '0',
        'noofresumes' => '1',
    );
    $sanitized_args = shortcode_atts($defaults, $attributes);
    if($sanitized_args['fieldcolumn'] == '' || $sanitized_args['fieldcolumn'] == 0){
        $sanitized_args['fieldcolumn'] = 1;
    }
    if($sanitized_args['title'] == ''){
        $sanitized_args['title'] = 'Latest Resumes';
    }
    if($sanitized_args['wpjobportalpageid'] == '' || $sanitized_args['wpjobportalpageid'] == 0){
        $sanitized_args['wpjobportalpageid'] = wpjobportal::wpjobportal_getPageid();
    }
    if($sanitized_args['typeofresume'] == '' || $sanitized_args['typeofresume'] == 0){
        $sanitized_args['typeofresume'] = 1;
    }
    if($sanitized_args['noofresumes'] == '' || $sanitized_args['noofresumes'] == 0){
        $sanitized_args['noofresumes'] = 1;
    }
    if($sanitized_args['listingstyle'] == ''){
        $sanitized_args['listingstyle'] = 1;
    }

    $mod = 'newestresume';
    if ($sanitized_args['typeofresume'] == 1) {
        $mod = 'newestresume';
    } elseif ($sanitized_args['typeofresume'] == 2) {
        $mod = 'topresume';
    } elseif ($sanitized_args['typeofresume'] == 4) {
        $mod = 'featuredresume';
    }
    $layoutName = $mod . uniqid();
    $html = '';
    $resumes = WPJOBPORTALincluder::getJSModel('resume')->getResumes_Widget($sanitized_args['typeofresume'], $sanitized_args['noofresumes']);
    $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->getResume_WidgetHtml($sanitized_args['title'],$layoutName, $resumes, $sanitized_args['noofresumes'], $sanitized_args['listingstyle'],$sanitized_args['typeofresume'],$sanitized_args['fieldcolumn']);
    wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style.css');
    if (is_rtl()) {
        wp_enqueue_style('wpjobportal-site-rtl', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/stylertl.css');
    }
    return $html;
}

add_action( 'init', 'js_wpjobportal_jobsearch_block');

function js_wpjobportal_jobsearch_block(){
    if(!function_exists("register_block_type")){
        return;
    }
    wp_register_script(
        'wpjobportaljobsearchblock',
        esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/gutenberg/jobsearch.js',
        array( 'wp-blocks', 'wp-element','wp-editor' )
    );
    register_block_type( 'wpjobportal/wpjobportaljobsearchblock', array(
        'attributes'      => array(
            'title' => array(
                'type' => 'string',
                'default' => 'Job Search'
            ),
            'showtitle' => array(
                'type' => 'string',
                'default' => ''
            ),
            'jobtitle' => array(
                'type' => 'select',
                'default' => ''
            ),
            'category' => array(
                'type' => 'select',
                'default' =>''
            ),
            'jobtype' => array(
                'type' => 'select',
                'default' =>''
            ),
            'jobstatus' => array(
                'type' => 'select',
                'default' =>''
            ),
            'salaryrange' => array(
                'type' => 'select',
                'default' =>''
            ),
            'duration' => array(
                'type' => 'select',
                'default' =>''
            ),
            'startpublishing' => array(
                'type' => 'select',
                'default' =>''
            ),
            'stoppublishing' => array(
                'type' => 'select',
                'default' =>''
            ),
            'company' => array(
                'type' => 'select',
                'default' =>''
            ),
            'address' => array(
                'type' => 'select',
                'default' =>''
            ),
            'columnperrow' => array(
                'type' => 'string',
                'default' => ''
            ),
        ),
        'render_callback' => 'js_wpjobportal_jobsearch_block_widgets',
        'editor_script' => 'wpjobportaljobsearchblock'
    ) );
}

function js_wpjobportal_jobsearch_block_widgets($attributes, $content){
    $defaults = array(
        'wpjobportalpageid' => '0',
        'title' => esc_html(__('Job Search','wp-job-portal')),
        'showtitle' => '1',
        'jobtitle' => '1',
        'category' => '1',
        'jobtype' => '1',
        'jobstatus' => '1',
        'salaryrange' => '1',
        'shift' => '1',
        'duration' => '1',
        'startpublishing' => '1',
        'stoppublishing' => '1',
        'company' => '1',
        'address' => '1',
        'columnperrow' => '1',
    );
    $sanitized_args = shortcode_atts($defaults, $attributes);
    if($sanitized_args['wpjobportalpageid'] == '' || $sanitized_args['wpjobportalpageid'] == 0){
        $sanitized_args['wpjobportalpageid'] = wpjobportal::wpjobportal_getPageid();
    }
    if($sanitized_args['title'] == ''){
        $sanitized_args['title'] = 'Job Search';
    }
    if($sanitized_args['showtitle'] == ''){
        $sanitized_args['showtitle'] = 1;
    }
    if($sanitized_args['jobtitle'] == ''){
        $sanitized_args['jobtitle'] = 1;
    }
    if($sanitized_args['category'] == ''){
        $sanitized_args['category'] = 1;
    }
    if($sanitized_args['jobtype'] == ''){
        $sanitized_args['jobtype'] = 1;
    }
    if($sanitized_args['jobstatus'] == ''){
        $sanitized_args['jobstatus'] = 1;
    }
    if($sanitized_args['salaryrange'] == ''){
        $sanitized_args['salaryrange'] = 1;
    }
    if($sanitized_args['shift'] == ''){
        $sanitized_args['shift'] = 1;
    }
    if($sanitized_args['duration'] == ''){
        $sanitized_args['duration'] = 1;
    }
    if($sanitized_args['startpublishing'] == ''){
        $sanitized_args['startpublishing'] = 1;
    }
    if($sanitized_args['stoppublishing'] == ''){
        $sanitized_args['stoppublishing'] = 1;
    }
    if($sanitized_args['company'] == ''){
        $sanitized_args['company'] = 1;
    }
    if($sanitized_args['address'] == ''){
        $sanitized_args['address'] = 1;
    }
    if($sanitized_args['columnperrow'] == '' || $sanitized_args['columnperrow'] == ''){
        $sanitized_args['columnperrow'] = 1;
    }

    $html = '';
    $html = WPJOBPORTALincluder::getJSModel('wpjobportalwidgets')->getSearchJobs_WidgetHTML($sanitized_args['title'], $sanitized_args['showtitle'], $sanitized_args['jobtitle'], $sanitized_args['category'], $sanitized_args['jobtype'], $sanitized_args['jobstatus'], $sanitized_args['salaryrange'], $sanitized_args['shift'], $sanitized_args['duration'], $sanitized_args['startpublishing'], $sanitized_args['stoppublishing'], $sanitized_args['company'], $sanitized_args['address'], $sanitized_args['columnperrow']);
    wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style.css');
    wp_enqueue_style('wpjobportal-tokeninput', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/tokeninput.css');
    wp_enqueue_script('wpjobportal-tokeninput', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/jquery.tokeninput.js');
    if (is_rtl()) {
        wp_enqueue_style('wpjobportal-site-rtl', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/stylertl.css');
    }
    return $html;
}
?>
