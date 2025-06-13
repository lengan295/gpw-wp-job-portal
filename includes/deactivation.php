<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALdeactivation {

    static function wpjobportal_deactivate() {
        wp_clear_scheduled_hook('wpjobportal_cronjobs_action');
        $id = wpjobportal::wpjobportal_getPageid();
        if(is_numeric($id)){
            wpjobportal::$_db->get_var("UPDATE `" . wpjobportal::$_db->prefix . "posts` SET post_status = 'draft' WHERE ID =".esc_sql($id));
        }

        //Delete capabilities
        $role = get_role( 'administrator' );
        $role->remove_cap( 'wpjobportal' );
        $role->remove_cap( 'wpjobportal_jobs' );
    }

     static function wpjobportal_tables_to_drop() {
        global $wpdb;
        $tables = array(
            $wpdb->prefix."wj_portal_careerlevels",
            $wpdb->prefix."wj_portal_categories",
            $wpdb->prefix."wj_portal_cities",
            $wpdb->prefix."wj_portal_companies",
            $wpdb->prefix."wj_portal_companycities",
            $wpdb->prefix."wj_portal_config",
            $wpdb->prefix."wj_portal_countries",
            $wpdb->prefix."wj_portal_currencies",
            $wpdb->prefix."wj_portal_emailtemplates",
            $wpdb->prefix."wj_portal_fieldsordering",
            $wpdb->prefix."wj_portal_heighesteducation",
            $wpdb->prefix."wj_portal_jobapply",
            $wpdb->prefix."wj_portal_jobcities",
            $wpdb->prefix."wj_portal_jobs",
            $wpdb->prefix."wj_portal_jobstatus",
            $wpdb->prefix."wj_portal_jobs_temp",
            $wpdb->prefix."wj_portal_jobs_temp_time",
            $wpdb->prefix."wj_portal_jobtypes",
            $wpdb->prefix."wj_portal_resume",
            $wpdb->prefix."wj_portal_resumeaddresses",
            $wpdb->prefix."wj_portal_resumeemployers",
            $wpdb->prefix."wj_portal_resumefiles",
            $wpdb->prefix."wj_portal_resumeinstitutes",
            $wpdb->prefix."wj_portal_resumelanguages",
            $wpdb->prefix."wj_portal_salaryrangetypes",
            $wpdb->prefix."wj_portal_states",
            $wpdb->prefix."wj_portal_subcategories",
            $wpdb->prefix."wj_portal_activitylog",
            $wpdb->prefix."wj_portal_emailtemplates_config",
            $wpdb->prefix."wj_portal_employer_view_resume",
            $wpdb->prefix."wj_portal_jobseeker_view_company",
            $wpdb->prefix."wj_portal_system_errors",
            $wpdb->prefix."wj_portal_users",
            $wpdb->prefix."wj_portal_slug",
            $wpdb->prefix."wj_portal_jswjsessiondata",
        );
        return $tables;
    }

}

?>
