<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALemailtemplatestatusModel {

    function sendEmailModel($id, $actionfor) {
        if (empty($id))
            return false;
        if (!is_numeric($actionfor))
            return false;

        $row = WPJOBPORTALincluder::getJSTable('emailtemplateconfig');
        $value = 1;

        switch ($actionfor) {
            case 1: //updation for employer send email
                $row->update(array('id' => $id, 'employer' => $value));
                break;
            case 2: //updation for jobseeker send email
                $row->update(array('id' => $id, 'jobseeker' => $value));

                break;
            case 3: //updation for admin send email
                $row->update(array('id' => $id, 'admin' => $value));
                break;
            case 4: //updation for jobseeker visitor send email
                $row->update(array('id' => $id, 'jobseeker_visitor' => $value));
                break;
            case 5: //updation for employer visitor send email
                $row->update(array('id' => $id, 'employer_visitor' => $value));
        }
    }

    function noSendEmailModel($id, $actionfor) {
        if (empty($id))
            return false;
        if (!is_numeric($actionfor))
            return false;

        $row = WPJOBPORTALincluder::getJSTable('emailtemplateconfig');
        $value = 0;

        switch ($actionfor) {
            case 1: //updation for employer not send email
                $row->update(array('id' => $id, 'employer' => $value));
                break;
            case 2: //updation for jobseeker not send email
                $row->update(array('id' => $id, 'jobseeker' => $value));
                break;
            case 3: //updation for admin not send email
                $row->update(array('id' => $id, 'admin' => $value));
                break;
            case 4: //updation for jobseeker visitor not send email
                $row->update(array('id' => $id, 'jobseeker_visitor' => $value));
                break;
            case 5: //updation for employer visitor not send email
                $row->update(array('id' => $id, 'employer_visitor' => $value));
        }
    }

    function getLanguageForEmail($keyword) {
        switch ($keyword) {
            case 'add_new_company':
                $lanng = esc_html(__('Add','wp-job-portal')). esc_html(__('new','wp-job-portal')).esc_html(__('company', 'wp-job-portal'));
                return $lanng;
                break;
            case 'delete_company':
                $lanng = esc_html(__('Delete','wp-job-portal')) .' '. esc_html(__('company', 'wp-job-portal'));
                return $lanng;
                break;
            case 'company_status':
                $lanng = esc_html(__('Company','wp-job-portal')) .' '. esc_html(__('status', 'wp-job-portal'));
                return $lanng;
                break;
            case 'job_status':
                $lanng = esc_html(__('Job','wp-job-portal')) .' '. esc_html(__('Status', 'wp-job-portal'));
                return $lanng;
                break;
            case 'add_new_job':
                $lanng = esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('new','wp-job-portal')) .' '. esc_html(__('job', 'wp-job-portal'));
                return $lanng;
                break;
            case 'add_new_resume':
                $lanng = esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('new','wp-job-portal')) .' '. esc_html(__('resume', 'wp-job-portal'));
                return $lanng;
                break;
            case 'resume_status':
                $lanng = esc_html(__('Resume','wp-job-portal')) .' '. esc_html(__('status', 'wp-job-portal'));
                return $lanng;
                break;
            case 'employer_purchase_credits_pack':
                $lanng = esc_html(__('Employer','wp-job-portal')) .' '. esc_html(__('buy credits pack', 'wp-job-portal'));
                return $lanng;
                break;
            case 'jobseeker_package_expire':
                $lanng = esc_html(__('Job seeker','wp-job-portal')) .' '. esc_html(__('expire package', 'wp-job-portal'));
                return $lanng;
                break;
            case 'jobseeker_purchase_credits_pack':
                $lanng = esc_html(__('Job seeker','wp-job-portal')) .' '. esc_html(__('buy credits pack', 'wp-job-portal'));
                return $lanng;
                break;
            case 'employer_package_expire':
                $lanng = esc_html(__('Employer','wp-job-portal')) .' '. esc_html(__('expire package', 'wp-job-portal'));
                return $lanng;
                break;
            case 'jobapply_employer':
                $lanng = esc_html(__('Employer','wp-job-portal')) .' '. esc_html(__('job apply', 'wp-job-portal'));
                return $lanng;
                break;
            case 'jobapply_jobseeker':
                $lanng = esc_html(__('Job seeker','wp-job-portal')) .' '. esc_html(__('job apply', 'wp-job-portal'));
                return $lanng;
                break;
            case 'delete_job':
                $lanng = esc_html(__('Delete','wp-job-portal')) .' '. esc_html(__('job', 'wp-job-portal'));
                return $lanng;
                break;
            case 'add_new_employer':
                $lanng = esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('New','wp-job-portal')) .' '. esc_html(__('Employer', 'wp-job-portal'));
                return $lanng;
                break;
            case 'add_new_jobseeker':
                $lanng = esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('New','wp-job-portal')) .' '. esc_html(__('Job Seeker', 'wp-job-portal'));
                return $lanng;
                break;
            case 'add_new_resume_visitor':
                $lanng = esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('new','wp-job-portal')) .' '. esc_html(__('resume ','wp-job-portal')) .' '. esc_html(__('by visitor', 'wp-job-portal'));
                return $lanng;
                break;
            case 'add_new_job_visitor':
                $lanng = esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('new','wp-job-portal')) .' '. esc_html(__('job','wp-job-portal')) .' '. esc_html(__('by visitor', 'wp-job-portal'));
                return $lanng;
                break;
            case 'resume-delete':
                $lanng = esc_html(__('Delete','wp-job-portal')) .' '. esc_html(__('resume', 'wp-job-portal'));
                return $lanng;
                break;
            case 'jobapply_jobapply':
                $lanng = esc_html(__('job apply', 'wp-job-portal'));
                return $lanng;
                break;
            case 'applied-resume_status':
                $lanng = esc_html(__('Applied resume status change', 'wp-job-portal'));
                return $lanng;
                break;
            case 'package-purchase-admin':
                $lanng = esc_html(__('Package Purchase Admin', 'wp-job-portal'));
                return $lanng;
                break;
            case 'package_status':
                $lanng = esc_html(__('Package Status', 'wp-job-portal'));
                return $lanng;
                break;
            case 'package_purchase':
                $lanng = esc_html(__('Package Purchase', 'wp-job-portal'));
                return $lanng;
                break;
            case 'new_message':
                $lanng = esc_html(__('New Message', 'wp-job-portal'));
                return $lanng;
                break;
        }
    }

    function getEmailTemplateStatusData() {
        $query = "SELECT * FROM " . wpjobportal::$_db->prefix . "wj_portal_emailtemplates_config";
        wpjobportal::$_data[0] = wpjobportaldb::get_results($query);
        $newdata = array();
        foreach (wpjobportal::$_data[0] as $data) {
            $newdata[$data->emailfor] = array(
                'tempid' => $data->id,
                'tempname' => $data->emailfor,
                'admin' => $data->admin,
                'employer' => $data->employer,
                'jobseeker' => $data->jobseeker,
                'jobseeker_vis' => $data->jobseeker_visitor,
                'employer_vis' => $data->employer_visitor
            );
        }
        wpjobportal::$_data[0] = $newdata;
    }

    function getEmailTemplateStatus($template_name) {
        $query = "SELECT emc.admin,emc.employer,emc.jobseeker,emc.employer_visitor,emc.jobseeker_visitor
                FROM " . wpjobportal::$_db->prefix . "wj_portal_emailtemplates_config AS emc
                where  emc.emailfor = '" . esc_sql($template_name) . "'";
        $templatestatus = wpjobportaldb::get_row($query);
        return $templatestatus;
    }
    function getMessagekey(){
        $key = 'emailtemplatestatus';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
