<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class wpjobportaladmin {

    function __construct() {
        add_action('admin_menu', array($this, 'mainmenu'));
    }

    function mainmenu() {
        add_menu_page(esc_html(__('Control Panel', 'wp-job-portal')), // Page title
                esc_html(__('WP Job Portal', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal', //menu slug
                array($this, 'showAdminPage'), // function name
                plugins_url('wp-job-portal/includes/images/admin_wpjobportal1.png'),26
        );

        add_submenu_page('wpjobportal', // parent slug
                esc_html(__('Dashboard', 'wp-job-portal')), // Page title
                esc_html(__('Dashboard', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal', //menu slug
                array($this, 'showAdminPage') // function name
        );

        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Theme', 'wp-job-portal')), // Page title
                esc_html(__('Theme', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_theme', //menu slug
                array($this, 'showAdminPage') // function name
        );

        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('pdf', 'wp-job-portal')), // Page title
                esc_html(__('pdf', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_pdf', //menu slug
                array($this, 'showAdminPage') // function name
        );

        add_submenu_page('wpjobportal', // parent slug
                esc_html(__('Jobs', 'wp-job-portal')), // Page title
                esc_html(__('Jobs', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_job', //menu slug
                array($this, 'showAdminPage') // function name
        );

        add_submenu_page('wpjobportal', // parent slug
                esc_html(__('Resume', 'wp-job-portal')), // Page title
                esc_html(__('Resume', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_resume', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal', // parent slug
                esc_html(__('Companies', 'wp-job-portal')), // Page title
                esc_html(__('Companies', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_company', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal', // parent slug
                esc_html(__('Configurations', 'wp-job-portal')), // Page title
                esc_html(__('Configurations', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_configuration', //menu slug
                array($this, 'showAdminPage') // function name
        );

        if(in_array('cronjob', wpjobportal::$_active_addons)){
            add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Cron Job', 'wp-job-portal')), // Page title
                esc_html(__('Cron Job', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_cronjob', //menu slug
                array($this, 'showAdminPage') // function name
            );
        }else{
            $this->addMissingAddonPage('cronjob');
        }


        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Departments', 'wp-job-portal')), // Page title
                esc_html(__('Departments', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_departments', //menu slug
                array($this, 'showAdminPage') // function name
        );

        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Cover Letters', 'wp-job-portal')), // Page title
                esc_html(__('Cover Letters', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_coverletter', //menu slug
                array($this, 'showAdminPage') // function name
        );

        if(in_array('credits',wpjobportal::$_active_addons)){
            add_submenu_page('wpjobportal_hide', // parent slug
                    esc_html(__('Packages', 'wp-job-portal')), // Page title
                    esc_html(__('Packages', 'wp-job-portal')), // menu title
                    'wpjobportal', // capability
                    'wpjobportal_package', //menu slug
                    array($this, 'showAdminPage') // function name
            );
        }else{
            $this->addMissingAddonPage('credits');
        }

        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Reports', 'wp-job-portal')), // Page title
                esc_html(__('Reports', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_report', //menu slug
                array($this, 'showAdminPage') // function name
        );
        # Reports Addon
    if(in_array('reports', wpjobportal::$_active_addons)){
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Jobseeker/Employer Reports', 'wp-job-portal')), // Page title
                esc_html(__('Jobseeker/Employer Reports', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_reports', //menu slug
                array($this, 'showAdminPage') // function name
        );
    }else{
     $this->addMissingAddonPage('reports');
    }

        if(in_array('message', wpjobportal::$_active_addons)){
            add_submenu_page('wpjobportal_hide', // parent slug
                    esc_html(__('Messages', 'wp-job-portal')), // Page title
                    esc_html(__('Message', 'wp-job-portal')), // menu title
                    'wpjobportal', // capability
                    'wpjobportal_message', //menu slug
                    array($this, 'showAdminPage') // function name
            );
        }else{
            $this->addMissingAddonPage('message');
        }
        if(in_array('folder', wpjobportal::$_active_addons)){
            add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Folder', 'wp-job-portal')), // Page title
                esc_html(__('Folder', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_folder', //menu slug
                array($this, 'showAdminPage') // function name
            );
        }else{
            $this->addMissingAddonPage('folder');
        }
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Categories', 'wp-job-portal')), // Page title
                esc_html(__('Categories', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_category', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Salary Range', 'wp-job-portal')), // Page title
                esc_html(__('Salary Range', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_salaryrange', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Users', 'wp-job-portal')), // Page title
                esc_html(__('Users', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_user', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Email Templates', 'wp-job-portal')), // Page title
                esc_html(__('Email Templates', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_emailtemplate', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Pro Installer', 'wp-job-portal')), // Page title
                esc_html(__('Pro Installer', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_proinstaller', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Email Templates Options', 'wp-job-portal')), // Page title
                esc_html(__('Email Templates Options', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_emailtemplatestatus', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Countries', 'wp-job-portal')), // Page title
                esc_html(__('Countries', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_country', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Career Level', 'wp-job-portal')), // Page title
                esc_html(__('Career Levels', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_careerlevel', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Cities', 'wp-job-portal')), // Page title
                esc_html(__('Cities', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_city', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Companies', 'wp-job-portal')), // Page title
                esc_html(__('Companies', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_company', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Currency', 'wp-job-portal')), // Page title
                esc_html(__('Currency', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_currency', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Custom Fields', 'wp-job-portal')), // Page title
                esc_html(__('Custom Fields', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_customfield', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Employer Packages', 'wp-job-portal')), // Page title
                esc_html(__('Employer Packages', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal&wpjobportallt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Experience', 'wp-job-portal')), // Page title
                esc_html(__('Experience', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_experience', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Field Ordering', 'wp-job-portal')), // Page title
                esc_html(__('Field Ordering', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_fieldordering', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Highest Education', 'wp-job-portal')), // Page title
                esc_html(__('Highest Education', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_highesteducation', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Job Alert', 'wp-job-portal')), // Page title
                esc_html(__('Job Alert', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal&wpjobportallt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Job Apply', 'wp-job-portal')), // Page title
                esc_html(__('Job Apply', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_jobapply', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Job Seeker Packages', 'wp-job-portal')), // Page title
                esc_html(__('Job Seeker Packages', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal&wpjobportallt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Job Status', 'wp-job-portal')), // Page title
                esc_html(__('Job Status', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_jobstatus', //menu slug
                array($this, 'showAdminPage') // function name
        );

       if(in_array('jobalert', wpjobportal::$_active_addons)){
            add_submenu_page('wpjobportal_hide', // parent slug
                    esc_html(__('WP Job Alert', 'wp-job-portal')), // Page title
                    esc_html(__('WP Job Alert', 'wp-job-portal')), // menu title
                    'wpjobportal', // capability
                    'wpjobportal_jobalert', //menu slug
                    array($this, 'showAdminPage') // function name
            );
       }else{
        $this->addMissingAddonPage('jobalert');
       }

        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Job Types', 'wp-job-portal')), // Page title
                esc_html(__('Job Types', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_jobtype', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Messages', 'wp-job-portal')), // Page title
                esc_html(__('Messages', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal&wpjobportallt=profeatures', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Payment History', 'wp-job-portal')), // Page title
                esc_html(__('Payment History', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_paymenthistory', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Payment Method Configuration', 'wp-job-portal')), // Page title
                esc_html(__('Payment Method Configuration', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_paymenthistorymethodconfiguration', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Payment Method Configuration', 'wp-job-portal')), // Page title
                esc_html(__('Payment Method Configuration', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_paymentmethodconfiguration', //menu slug
                array($this, 'showAdminPage') // function name
        );

        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Invoices', 'wp-job-portal')), // Page title
                esc_html(__('Invoices', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_invoice', //menu slug
                array($this, 'showAdminPage') // function name
        );

        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Salary Range Types', 'wp-job-portal')), // Page title
                esc_html(__('Salary Range Types', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_salaryrangetype', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('States', 'wp-job-portal')), // Page title
                esc_html(__('States', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_state', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('System Errors', 'wp-job-portal')), // Page title
                esc_html(__('System Errors', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_systemerror', //menu slug
                array($this, 'showAdminPage') // function name
        );
        // add_submenu_page('wpjobportal_hide', // parent slug
        //         esc_html(__('Cover letter', 'wp-job-portal')), // Page title
        //         esc_html(__('Cover letter', 'wp-job-portal')), // menu title
        //         'wpjobportal', // capability
        //         'wpjobportal_coverletter', //menu slug
        //         array($this, 'showAdminPage') // function name
        // );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Users', 'wp-job-portal')), // Page title
                esc_html(__('Users', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_user', //menu slug
                array($this, 'showAdminPage') // function name
        );

       if(in_array('addressdata',wpjobportal::$_active_addons)){
            add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Address Data', 'wp-job-portal')), // Page title
                esc_html(__('Address Data', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_addressdata', //menu slug
                array($this, 'showAdminPage') // function name
        );
       }else{
        $this->addMissingAddonPage('addressdata');
       }

        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Activity Log', 'wp-job-portal')), // Page title
                esc_html(__('Activity Log', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_activitylog', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('WP Job Portal', 'wp-job-portal')), // Page title
                esc_html(__('WP Job Portal', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_common', //menu slug
                array($this, 'showAdminPage') // function name
        );
        if(in_array('credits', wpjobportal::$_active_addons)){
            add_submenu_page('wpjobportal_hide', // parent slug
                    esc_html(__('Purchase History', 'wp-job-portal')), // Page title
                    esc_html(__('Purchase History', 'wp-job-portal')), // menu title
                    'wpjobportal', // capability
                    'wpjobportal_purchasehistory', //menu slug
                    array($this, 'showAdminPage') // function name
            );
        }else{
            $this->addMissingAddonPage('credits');
        }
        /* add_submenu_page('wpjobportal', // parent slug
                esc_html(__('Translations', 'wp-job-portal')), // Page title
                esc_html(__('Translations', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal&wpjobportallt=translations', //menu slug
                array($this, 'showAdminPage') // function name
        ); */
        add_submenu_page('wpjobportal', // parent slug
               esc_html(__('Shortcodes', 'wp-job-portal')), // Page title
               esc_html(__('Shortcodes', 'wp-job-portal')), // menu title
               'wpjobportal', // capability
               'wpjobportal&wpjobportallt=shortcodes', //menu slug
               array($this, 'showAdminPage') // function name
       );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('System Errors', 'wp-job-portal')), // Page title
                esc_html(__('System Errors', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_systemerror', //menu slug
                array($this, 'showAdminPage') // function name
        );
//Specifying Addons
        if(in_array('tag', wpjobportal::$_active_addons)){
            add_submenu_page('wpjobportal_hide', // parent slug
                    esc_html(__('Tags', 'wp-job-portal')), // Page title
                    esc_html(__('Tags', 'wp-job-portal')), // menu title
                    'wpjobportal', // capability
                    'wpjobportal_tag', //menu slug
                    array($this, 'showAdminPage') // function name
            );
         }else{
            $this->addMissingAddonPage('tags');
        }
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('WP Job Portal Settings', 'wp-job-portal')), // Page title
                esc_html(__('WP Job Portal Settings', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_postinstallation', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('WP Job Portal Slug', 'wp-job-portal')), // Page title
                esc_html(__('WP Job Portal Slug', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_slug', //menu slug
                array($this, 'showAdminPage') // function name
        );
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('WP Job Portal Import Data', 'wp-job-portal')), // Page title
                esc_html(__('WP Job Portal Import Data', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_thirdpartyimport', //menu slug
                array($this, 'showAdminPage') // function name
        );

        add_submenu_page('wpjobportal', // parent slug
                esc_html(__('Install Addons', 'wp-job-portal')), // Page title
                esc_html(__('Install Addons', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                'wpjobportal_premiumplugin', //menu slug
                array($this, 'showAdminPage') // function name
        );
    }

  static  function showAdminPage() {
        wpjobportal::wpjobportal_addStyleSheets();
        $page = WPJOBPORTALrequest::getVar('page');
        $page = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $page);
        WPJOBPORTALincluder::include_file($page);
    }

    function addMissingAddonPage($module_name){
        add_submenu_page('wpjobportal_hide', // parent slug
                esc_html(__('Premium Addon', 'wp-job-portal')), // Page title
                esc_html(__('Premium Addon', 'wp-job-portal')), // menu title
                'wpjobportal', // capability
                $module_name, //menu slug
                array($this, 'showMissingAddonPage') // function name
        );
    }

}

$wpjobportalAdmin = new wpjobportaladmin();
?>
