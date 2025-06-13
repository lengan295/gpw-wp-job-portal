<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALEmailtemplateModel {

    function sendMail($mailfor, $action, $id,$mailextradata=array()) {
        if (!is_numeric($mailfor))
            return false;
        if (!is_numeric($action))
            return false;
        if ($id != null)
            if (!is_numeric($id))
                return false;
        $config_array = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('email');
        $pageid = WPJOBPORTAL::wpjobportal_getPageid();
        $siteTitle = wpjobportal::$_config->getConfigValue('title');
        switch ($mailfor) {
            case 1: // Mail For Company
                switch ($action) {
                    case 1: // Add New Company
                        $record = $this->getRecordByTablenameAndId('wj_portal_companies', $id,15);
                        if($record == '' || empty($record)){
                            return;
                        }
                        $link = null;
                        $checkstatus = null;
                        $Email = $record->companyuseremail;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $status = $record->status;
                        // if(in_array('multicompany', wpjobportal::$_active_addons)){
                        //     $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'mycompanies', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        // }else{
                        //     $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'mycompanies', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        // }
                        // view company button on email will take to view company layout instead of my comapnies layout
                        $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        if ($status == 0) {
                            $checkstatus = esc_html(__('Pending', 'wp-job-portal'));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                        }
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                        }

                        if ($status == 3) {
                            $checkstatus = esc_html(__('Pending Due to Payment', 'wp-job-portal'));
                        }
                        $Companyname = $record->companyname;

                        // to show admininstrator or guest name and email when creating new comapny
                        if($record->username == ''){
                            if(wpjobportal::$_common->wpjp_isadmin()){
                                $record->username = 'Administrator';
                            }else{
                                $record->username = 'Guest';
                            }
                        }
                        if($record->useremail == ''){
                            $record->useremail = $record->companyuseremail;
                        }

                        $matcharray = array(
                            '{COMPANY_NAME}' => $Companyname,
                            '{COMPANY_LINK}' => $link,
                            '{COMPANY_STATUS}' => $checkstatus,
                            '{EMPLOYER_NAME}' => $record->username,
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $record->useremail,
                            '{CURRENT_YEAR}' => gmdate('Y')
                        );
                        $template = $this->getTemplateForEmail('company-new');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_company');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New Company mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 2); //2 action for add company hock
                        }
                        $link = esc_url_raw(admin_url("admin.php?page=wpjobportal_company"));
                        $matcharray['{COMPANY_LINK}'] = $link;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New Company mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 1); //1 action for add company hock
                        }
                        break;
                    case 2: // Delete Company

                        $matcharray = array(
                            '{COMPANY_NAME}' => $mailextradata['companyname'],
                            '{EMAIL}' => $mailextradata['contactemail'],
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle
                        );
                        $Email = $mailextradata['contactemail'];
                        $template = $this->getTemplateForEmail('company-delete');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('delete_company');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Delete Company mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 3); // 3 action for company delete hock
                        }
                        break;
                    case 3: // Company approve OR compnay Reject
                        $record = $this->getRecordByTablenameAndId('wj_portal_companies', $id,15);
                        $Username = '';
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->companyuseremail;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $Companyname = $record->companyname;
                        // to show admininstrator or guest name and email when creating new comapny
                        if($Username == ''){
                            if(wpjobportal::$_common->wpjp_isadmin()){
                                $Username = 'Administrator';
                            }else{
                                $Username = 'Guest';
                            }
                        }

                        $status = $record->status;
                        $checkstatus = null;
                        $link = null;
                        if(in_array('multicompany', wpjobportal::$_active_addons)){
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'mycompanies', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }else{
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'mycompanies', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                        }
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        $matcharray = array(
                            '{COMPANY_NAME}' => $Companyname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{COMPANY_LINK}' => $link,
                            '{COMPANY_STATUS}' => $checkstatus,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{EMAIL}' => $Email,
                            '{SITETITLE}' => $siteTitle
                        );
                        $template = $this->getTemplateForEmail('company-status');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('company_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Company approve or reject mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for compnay status hock
                        }
                        break;
                    case 5: // Company approve OR reject for featured
                        $record = $this->getRecordByTablenameAndId('wj_portal_companies', $id,17);
                        if($record == ''){
                            break;
                        }
                        $Username = '';
                        if ($Username == '') {
                            $Username = $record->username;
                        }

                        // to show admininstrator or guest name and email when creating new comapny
                        if($Username == ''){
                            if(wpjobportal::$_common->wpjp_isadmin()){
                                $Username = 'Administrator';
                            }else{
                                $Username = 'Guest';
                            }
                        }

                        $Email = $record->companyuseremail;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $Companyname = $record->companyname;
                        $featuredcompany = $record->featuredcompany;
                        $link = null;
                        $checkfeaturedcompany = null;
                        if(in_array('multicompany', wpjobportal::$_active_addons)){
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'mycompanies', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())) . ">" . esc_html(__('Company Detail', 'wp-job-portal')) . "</a>";
                        }else{
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'mycompanies', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())) . ">" . esc_html(__('Company Detail', 'wp-job-portal')) . "</a>";
                        }
                        if ($featuredcompany == -1) {
                            $checkfeaturedcompany = esc_html(__('rejected for featured', 'wp-job-portal'));
                        }
                        if ($featuredcompany == 1) {
                            $checkfeaturedcompany = esc_html(__('approved for featured', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($featuredcompany == 2) {
                            $checkfeaturedcompany = esc_html(__('removed for featured', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($featuredcompany == 0) {
                            $checkfeaturedcompany = esc_html(__('pending for featured', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())) ;
                        }
                        $matcharray = array(
                            '{COMPANY_NAME}' => $Companyname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{COMPANY_LINK}' => $link,
                            '{COMPANY_STATUS}' => $checkfeaturedcompany,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{EMAIL}' => $Email,
                            '{SITETITLE}' => $siteTitle
                        );
                        $template = $this->getTemplateForEmail('company-status');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('company_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;

                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        //  Featured Company mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 6); // 6 action for company featured hock
                        }
                        break;
                }
                break;
            case 2: // Mail For Job
                switch ($action) {
                    case 1: // Add New Job
                        $record = $this->getRecordByTablenameAndId('wj_portal_jobs', $id,19);
			             if($record == '' || empty($record)){
                            break;
                        }
                        $userid = isset($record->id) ? $record->id : '';
                        $Username = isset($record->username) ? $record->username : '';
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $status = $record->status;
                        $companyname = $record->companyname;
                        $checkstatus = null;
                        $link = null;
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())) ;
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                        }
                        if ($status == 0) {
                            $checkstatus = esc_html(__('Pending', 'wp-job-portal'));
                        }

                        if ($status == 3) {
                            $checkstatus = esc_html(__('Pending Due To Payment', 'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $checkstatus,
                            '{COMPANY_NAME}' => $companyname,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $record->useremail
                        );
                        $template = $this->getTemplateForEmail('job-new');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_job');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New Job mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 7); // 7 action for add job hock
                        }
                        $link =  esc_url_raw(admin_url("admin.php?page=wpjobportal_job"));
                        $matcharray['{JOB_LINK}'] = $link;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New Job mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for add job hock
                        }
                        break;
                    case 2: // Job Delete
                        $matcharray = array(
                            '{JOB_TITLE}' => $mailextradata['jobtitle'],
                            '{EMPLOYER_NAME}' => isset($mailextradata['user']) ? $mailextradata['user'] : '',
                            '{COMPANY_NAME}' => $mailextradata['companyname'],
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $mailextradata['useremail']
                        );
                        $Email = $mailextradata['useremail'];
                        $template = $this->getTemplateForEmail('job-delete');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('delete_job');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // job Delete mail to User
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 10); // 10 for job delete
                        }
                        break;
                    case 3: // job approve OR reject
                        $record = $this->getRecordByTablenameAndId('wj_portal_jobs', $id ,19);
                        $Username = isset($record->username) ? $record->username : '';
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $status = $record->status;
                        $featuredjob = $record->featuredjob;
                        $link = null;
                        $checkstatus = null;
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())) ;
                        }
                        $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                        }
                        if ($status == 2) {
                            $checkstatus = esc_html(__('Removed', 'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $checkstatus,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Email
                        );
                        $template = $this->getTemplateForEmail('job-status');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('job_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // job Approve mail to User
                        if ($getEmailStatus->employer == 1 && $record->uid !=0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 11); // 11 action for job gold hock
                        }
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                            $link = null;
                        }
                        if ($status == 2) {
                            $checkstatus = esc_html(__('Removed', 'wp-job-portal'));
                            $link = null;
                        }
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job Approve mail to visitor
                        if ($getEmailStatus->employer_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 12); // 12 action for job gold hock
                        }
                        break;
                    case 5: // Job approve OR reject for featured
                        $record = $this->getRecordByTablenameAndId('wj_portal_jobs', $id ,21);
                        if($record == ''){
                            break;
                        }
                        if(!isset($record->visname)){ // to handle log error
                            $record->visname = '';
                        }
                        $Username = isset($record->username) ? $record->username : $record->visname;
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $featuredjob = $record->featuredjob;
                        $link = null;
                        $checkstatus = null;
                        $checkfeaturedjob = null;
                        $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        if ($featuredjob == -1) {
                            $checkfeaturedjob = esc_html(__('rejected for featured', 'wp-job-portal'));
                        }
                        if ($featuredjob == 1) {
                            $checkfeaturedjob = esc_html(__('approved for featured', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($featuredjob == 2) {
                            $checkfeaturedjob = esc_html(__('removed for featured', 'wp-job-portal'));
                        }
                        if ($featuredjob == 0) {
                            $checkfeaturedjob = esc_html(__('pending for featured', 'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $Username,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $checkfeaturedjob,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Email
                        );
                        $template = $this->getTemplateForEmail('job-status');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('job_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // job featured mail to User
                        if ($getEmailStatus->employer == 1 && $record->uid !=0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 15); // 15 action for job gold hock
                        }
                        $matcharray['{JOB_LINK}'] = $link;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job featured mail to visitor
                        if ($getEmailStatus->employer_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 16); // 16 action for job gold hock
                        }
                        break;
                    case 6: // Add New visitor Job
                        $record = $this->getRecordByTablenameAndId('wj_portal_jobs', $id);
                        $visusername = $record->visname ? $record->visname : '';
                        $jobname = $record->jobtitle;
                        $Email = $record->useremail;
                        $companyname = $record->companyname;
                        $status = $record->status;
                        $checkstatus = null;
                        $link = null;
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                            $link = "<strong>" . esc_html(__('Due to rejection of job you do not have permission to see job detail', 'wp-job-portal')) . "</strong>";
                        }
                        if ($status == 0) {
                            $checkstatus = esc_html(__('Pending', 'wp-job-portal'));
                            $link = "<strong>" . esc_html(__('Due to pending status of job you do not have permission to see job detail', 'wp-job-portal')) . "</strong>";
                        }
                        $matcharray = array(
                            '{JOB_TITLE}' => $jobname,
                            '{EMPLOYER_NAME}' => $visusername,
                            '{JOB_LINK}' => $link,
                            '{JOB_STATUS}' => $status,
                            '{COMPANY_NAME}' => $companyname,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Email
                        );
                        $template = $this->getTemplateForEmail('job-new-vis');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_job');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New visitor Job mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for add job hock
                        }
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                            $link = "<strong>" . esc_html(__('Due to rejection of job you do not have permission to see job detail', 'wp-job-portal')) . "</strong>";
                        }
                        if ($status == 0) {
                            $checkstatus = esc_html(__('Pending', 'wp-job-portal'));
                            $link = "<strong>" . esc_html(__('Due to pending status of job you do not have permission to see job detail', 'wp-job-portal')) . "</strong>";
                        }
                        $matcharray['{JOB_LINK}'] = $link;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $matcharray['{EMAIL}'] = $Email;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New visitor Job mail to visitor
                        if ($getEmailStatus->employer_visitor == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 9); // 9 action for add job hock
                        }
                        break;
                }
                break;

            case 3: // Mail For Resume
                switch ($action) {
                    case 1: // Add New Resume
                        $record = $this->getRecordByTablenameAndId('wj_portal_resume', $id,1);
                        if($record == '' || empty($record)){
                            return;
                        }
                        $Username = $record->firstname . '' . $record->lastname;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = isset($record->useremailfromresume) ? $record->useremailfromresume : '';
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $resumename = $record->resumetitle;
                        $status = $record->resumestatus;
                        $link = null;
                        $checkstatus = null;
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if(in_array('multiresume', wpjobportal::$_active_addons)){
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multiresume', 'wpjobportallt'=>'myresumes', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }else{
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                        }
                        if ($status == 0) {
                            $checkstatus = esc_html(__('Pending', 'wp-job-portal'));
                        }

                        if ($status == 3) {
                            $checkstatus = esc_html(__('Pending Due to Payment', 'wp-job-portal'));
                       }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{RESUME_STATUS}' => $checkstatus,
                            '{RESUME_LINK}' => $link,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Email
                        );
                        $template = $this->getTemplateForEmail('resume-new');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_resume');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New resume mail to User
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        $link =  esc_url_raw(admin_url("admin.php?page=wpjobportal_resume"));
                        $matcharray['{RESUME_LINK}'] = $link;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $matcharray['{EMAIL}'] = $Email;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New resume mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        break;
                    case 2: // Resume Approve or Reject
                        $record = $this->getRecordByTablenameAndId('wj_portal_resume', $id,1);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->firstname . '' . $record->lastname;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->useremailfromresume;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $resumename = $record->resumetitle;
                        $status = $record->resumestatus;
                        $link = null;
                        $checkstatus = null;
                        if(in_array('multiresume', wpjobportal::$_active_addons)){
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multiresume', 'wpjobportallt'=>'myresumes', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }else{
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{RESUME_LINK}' => $link,
                            '{RESUME_STATUS}' => $checkstatus,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Email
                        );
                        $template = $this->getTemplateForEmail('resume-status');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('resume_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // resume Approve mail to jobseeker
                        if ($getEmailStatus->jobseeker == 1 && $record->uid != 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', $action);
                        }
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                            $link = null;
                        }
                        if ($status == 2) {
                            $checkstatus = esc_html(__('Removed', 'wp-job-portal'));
                            $link = null;
                        }
                        if ($status == 0) {
                            $checkstatus = esc_html(__('Pending', 'wp-job-portal'));
                            $link = null;
                        }
                        $matcharray['{RESUME_LINK}'] = $link;
                        $matcharray['{RESUME_STATUS}'] = $checkstatus;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $matcharray['{EMAIL}'] = $Email;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job Approve mail to visitor
                        if ($getEmailStatus->jobseeker_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 12); // 12 action for job gold hock
                        }

                        break;
                    case 4: // resume approve OR reject for featured
                        $record = $this->getRecordByTablenameAndId('wj_portal_resume', $id,3);
                        if($record == '' || empty($record)){
                            break;
                        }
                        $Username = $record->firstname . '' . $record->lastname;
                        if ($Username == '') {
                            $Username = $record->username;
                        }
                        $Email = $record->useremailfromresume;
                        if ($Email == '') {
                            $Email = $record->useremail;
                        }
                        $resumename = $record->resumetitle;
                        $status = $record->resumestatus;
                        $featuredresume = $record->featuredresume;
                        $link = null;
                        $checkfeaturedresume = null;
                        if(in_array('multiresume', wpjobportal::$_active_addons)){
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multiresume', 'wpjobportallt'=>'myresumes', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }else{
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())) ;
                        }
                        if ($featuredresume == -1) {
                            $checkfeaturedresume = esc_html(__('rejected for featured', 'wp-job-portal'));
                        }
                        if ($featuredresume == 1) {
                            $checkfeaturedresume = esc_html(__('approved for featured', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($featuredresume == 0) {
                            $checkfeaturedresume = esc_html(__('pending for featured', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($featuredresume == 2) {
                            $checkfeaturedresume = esc_html(__('removed for featured', 'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $Username,
                            '{RESUME_LINK}' => $link,
                            '{RESUME_STATUS}' => $checkfeaturedresume,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Email
                        );
                        $template = $this->getTemplateForEmail('resume-status');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('resume_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // resume Approve mail to Jobseeker
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        if ($featuredresume == 1) {
                            $checkfeaturedresume = esc_html(__('approved for featured', 'wp-job-portal'));
                        }
                        if ($featuredresume == -1) {
                            $checkfeaturedresume = esc_html(__('rejected for featured', 'wp-job-portal'));
                        }
                        if ($featuredresume == 2) {
                            $checkfeaturedresume = esc_html(__('removed for featured', 'wp-job-portal'));
                        }
                        if ($featuredresume == 0) {
                            $checkfeaturedresume = esc_html(__('pending for featured', 'wp-job-portal'));
                            $link = null;
                        }
                        $matcharray['{RESUME_LINK}'] = $link;
                        $matcharray['{RESUME_STATUS}'] = $checkfeaturedresume;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $matcharray['{EMAIL}'] = $Email;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // job Approve mail to visitor
                        if ($getEmailStatus->jobseeker_visitor == 1 && $record->uid == 0) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 12); // 12 action for job gold hock
                        }
                        break;
                    case 5: //Add new visitor resume
                        $record = $this->getRecordByTablenameAndId('wj_portal_resume', $id);
                        $visusername = $record->firstname . '' . $record->lastname;
                        $Email = $record->useremailfromresume;
                        $resumename = $record->resumetitle;
                        $status = $record->status;
                        if ($status == 1) {
                            $checkstatus = esc_html(__('Approved', 'wp-job-portal'));
                            $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        }
                        if ($status == -1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                            $link = "<strong>" . esc_html(__('Due to rejection of resume you do not have permission to see resume detail', 'wp-job-portal')) . "</strong>";
                        }
                        if ($status == 0) {
                            $checkstatus = esc_html(__('Pending', 'wp-job-portal'));
                            $link = "<strong>" . esc_html(__('Due to pending status of resume you do not have permission to see resume detail', 'wp-job-portal')) . "</strong>";
                        }
                        $matcharray = array(
                            '{RESUME_TITLE}' => $resumename,
                            '{JOBSEEKER_NAME}' => $visusername,
                            '{RESUME_STATUS}' => $checkstatus,
                            '{RESUME_LINK}' => $link,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Email
                        );
                        $template = $this->getTemplateForEmail('resume-new-vis');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_resume_visitor');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);

                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New visitor resume mail to User
                        if ($getEmailStatus->jobseeker_visitor == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 7); // 7 action for add job hock
                        }
                        $link =  esc_url_raw(admin_url("admin.php?page=wpjobportal_resume"));
                        $matcharray['{RESUME_LINK}'] = $link;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $matcharray['{EMAIL}'] = $Email;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // Add New visitor resume mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for add job hock
                        }

                    break;
                case 6://delete resume
                    $Email = $mailextradata['useremail'];
                    $matcharray = array(
                        '{RESUME_TITLE}' => $mailextradata['resumetitle'],
                        '{JOBSEEKER_NAME}' => $mailextradata['jobseekername'],
                        '{CURRENT_YEAR}' => gmdate('Y'),
                        '{SITETITLE}' => $siteTitle,
                        '{EMAIL}' => $Email
                    );
                    $template = $this->getTemplateForEmail('resume-delete');
                    $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('resume-delete');
                    $msgSubject = $template->subject;
                    $msgBody = $template->body;
                    $this->replaceMatches($msgSubject, $matcharray);
                    $this->replaceMatches($msgBody, $matcharray);
                    $senderEmail = $config_array['mailfromaddress'];
                    $senderName = $config_array['mailfromname'];
                    // Delete resume mail to User
                    if ($getEmailStatus->jobseeker == 1) {
                        $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 3); // 3 action for company delete hock
                    }
                break;
                }
            break;
            case 4: // mail for purchase Pakages pack
                switch ($action) {
                    case 1: //purchase package
                        $record = $this->getRecordByTablenameAndId('wj_portal_userpackages', $id);
                        if(!$record){
                            return false;
                        }
                        $username = $record->username;
                        $packagename = $record->packagename;
                        $receiveremail = $record->useremailaddress;
                        $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchasehistory', 'wpjobportallt'=>'purchasehistory')).">".esc_html(__('Package Detail','wp-job-portal'));
                        if($record->isfree){
                            $packageprice = esc_html(__("Free",'wp-job-portal'));
                        }else{
                            $overrideConfig = array('decimal_places'=>'fit_to_currency');
                            $packageprice = wpjobportal::$_common->getFancyPrice($record->price,$record->currencyid,$overrideConfig);
                        }
                        if($record->status==1){
                            $status =  esc_html(__("Publish",'wp-job-portal'));
                        }else if($record->status==0){
                            $status =  esc_html(__("Pending",'wp-job-portal'));
                        }else if($record->status==-1){
                            $status =  esc_html(__("Rejected",'wp-job-portal'));
                        }else{
                            $status = esc_html(__("Unknown",'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{USER_NAME}' => $username,
                            '{PACKAGE_TITLE}' => $packagename,
                            '{PACKAGE_PRICE}' => $packageprice,
                            '{PACKAGE_LINK}' => $link,
                            '{PUBLISH_STATUS}' => $status,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $receiveremail
                        );
                        $template = $this->getTemplateForEmail('package-purchase');
                        $emailstatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('package_purchase');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // purchase package mail to User/agency
                        if( ($record->userid ? $emailstatus->employer : $emailstatus->jobseeker) == 1 ){
                            $this->sendEmail($receiveremail, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        // purchase package  mail to admin
                        $template = $this->getTemplateForEmail('package-purchase-admin');
                        $link = esc_url_raw(admin_url("admin.php?page=wpjobportal_purchasehistory"));
                        $matcharray['{PACKAGE_LINK}']= $link;
                        $matcharray['{CURRENT_YEAR}'] = gmdate('Y');
                        $matcharray['{SITETITLE}'] = $siteTitle;
                        $matcharray['{EMAIL}'] = $receiveremail;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        if($emailstatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        break;
                    case 2: // package status change
                        $record = $this->getRecordByTablenameAndId('wj_portal_userpackages', $id);
                        if(!$record){
                            return false;
                        }
                        $username = $record->username;
                        $packagename = $record->packagename;
                        $receiveremail = $record->useremailaddress;
                        $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchasehistory', 'wpjobportallt'=>'purchasehistory')).">".esc_html(__('Package Detail','wp-job-portal'));
                        if($record->isfree){
                            $packageprice = esc_html(__("Free",'wp-job-portal'));
                        }else{
                            $overrideConfig = array('decimal_places'=>'fit_to_currency');
                            $packageprice = WPJOBPORTALincluder::getJSModel('common')->getFancyPrice($record->price,$record->currencyid,$overrideConfig);
                        }
                        if($record->status==1){
                            $status =  esc_html(__("Publish",'wp-job-portal'));
                        }else{
                            $status =  esc_html(__("Unpublish",'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{USER_NAME}' => $username,
                            '{PACKAGE_TITLE}' => $packagename,
                            '{PACKAGE_PRICE}' => $packageprice,
                            '{PACKAGE_LINK}' => $link,
                            '{PUBLISH_STATUS}' => $status,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $receiveremail
                        );
                        $template = $this->getTemplateForEmail('package-status');
                        $emailstatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('package_status');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // package status changed  mail to User/agency
                        if( ($record->userid ? $emailstatus->agency : $emailstatus->user) == 1 ){
                            $this->sendEmail($receiveremail, $msgSubject, $msgBody, $senderEmail, $senderName, '');
                        }
                        break;
                }
                break;
            case 5: //Email for Apply job
                switch ($action) {
                    case 1:// jobapply email to employer and jobseeker
                        $record = $this->getRecordByTablenameAndId('wj_portal_jobapply', $id);
                        $employername = $record->companycontactname;
                        if ($employername == '') {
                            $employername = $record->username;
                        }
                        $Emailtoemployer = $record->companycontactemail;
                        if ($Emailtoemployer == '') {
                            $Emailtoemployer = $record->useremailforemployer;
                        }
                        $Emailtojobseekr = $record->resumeemail;
                        if ($Emailtojobseekr == '') {
                            $Emailtojobseekr == $record->useremailforjobseeker;
                        }
                        $companyname = $record->companyname;
                        $resumename = $record->resumetitle;
                        $jobtitle = $record->jobtitle;
                        $resumeappliedstatus = $record->resumestatus;
                        $resumetitle = $record->resumetitle;
                        $jobseekername = $record->firstname . '' . $record->lastname;
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = esc_html(__('Inbox', 'wp-job-portal'));
                        }
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = esc_html(__('Spam', 'wp-job-portal'));
                        }
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = esc_html(__('Hired', 'wp-job-portal'));
                        }
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = esc_html(__('Rejected', 'wp-job-portal'));
                        }
                        if ($resumeappliedstatus == 1) {
                            $checkstatus = esc_html(__('Short listed', 'wp-job-portal'));
                        }
                        $resumedata = null;
                        $matcharray = array(
                            '{JOBSEEKER_NAME}' => $jobseekername,
                            '{EMPLOYER_NAME}' => $employername,
                            '{RESUME_APPLIED_STATUS}' => $checkstatus,
                            '{RESUME_TITLE}' => $resumename,
                            '{JOB_TITLE}' => $jobtitle,
                            '{RESUME_DATA}' => $resumedata,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Emailtoemployer
                        );
                        $template = $this->getTemplateForEmail('jobapply-employer');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus($template->id);
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // Add New Job mail to employer
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Emailtoemployer, $msgSubject, $msgBody, $senderEmail, $senderName, '', 7); // 7 action for add job hock
                        }
                        $template = $this->getTemplateForEmail('jobapply-jobseeker');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus($template->id);
                        $matcharray = array(
                            '{JOBSEEKER_NAME}' => $jobseekername,
                            '{RESUME_APPLIED_STATUS}' => $checkstatus,
                            '{RESUME_TITLE}' => $resumename,
                            '{COMPANY_NAME}' => $companyname,
                            '{JOB_TITLE}' => $jobtitle,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{SITETITLE}' => $siteTitle,
                            '{EMAIL}' => $Emailtojobseekr
                        );
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        // jobapply mail to jobseeker
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Emailtojobseekr, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for add job hock
                        }
                        break;
                }

                break;
            case 6: //employer OR jobseeker resgistration
                switch ($action) {
                    case 1: //for employer registration
                        $record = $this->getRecordByTablenameAndId('users', $id);
                        $link = null;
                        $checkuserrole = null;
                        $Username = $record->username;
                        $Email = $record->useremail;
                        $userrole = $record->userrole;
                        $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalpageid'=>WPJOBPORTALRequest::getVar('wpjobportalpageid')));
                        if ($userrole == 1) {
                            $checkuserrole = esc_html(__('Employer', 'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{USER_ROLE}' => $checkuserrole,
                            '{USER_NAME}' => $Username,
                            '{CONTROL_PANEL_LINK}' => $link,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{EMAIL}' => $Email,
                            '{SITETITLE}' => $siteTitle
                        );
                        $template = $this->getTemplateForEmail('employer-new');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_employer');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // New employer registration mail to user
                        if ($getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        $link = esc_url_raw(admin_url("admin.php?page=wpjobportal"));
                        $matcharray['{CONTROL_PANEL_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // New employer registration mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                            $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        break;
                    case 2: //for jobseeker registration
                        $record = $this->getRecordByTablenameAndId('users', $id);
                        $link = null;
                        $checkuserrole = null;
                        $Username = $record->username;
                        $Email = $record->useremail;
                        $userrole = $record->userrole;
                        $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        if ($userrole == 2) {
                            $checkuserrole = esc_html(__('Job seeker', 'wp-job-portal'));
                        }
                        $matcharray = array(
                            '{USER_ROLE}' => $checkuserrole,
                            '{USER_NAME}' => $Username,
                            '{CONTROL_PANEL_LINK}' => $link,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{EMAIL}' => $Email,
                            '{SITETITLE}' => $siteTitle
                        );
                        $template = $this->getTemplateForEmail('jobseeker-new');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('add_new_jobseeker');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // New jobseeker registration mail to user
                        if ($getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        }
                        $link =  esc_url_raw(admin_url("admin.php?page=wpjobportal"));
                        $matcharray['{CONTROL_PANEL_LINK}'] = $link;
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // New jobseeker registration mail to admin
                        if ($getEmailStatus->admin == 1) {
                            $adminEmailid = $config_array['adminemailaddress'];
                        }
                        break;
                }

                break;

            case 7: //employer OR jobseeker Send message
                switch ($action) {
                    case 1: //Send Messaege
                                /*
                    stdClass Object
                    (
                        [message] =>
                    ffff
                        [sendby] => 3
                        [created] => 2024-09-05 13:06:19
                        [jobseekerid] => 2
                        [employerid] => 3
                        [id] => 2
                        [status] => 1
                        [replytoid] => 1
                        [jobid] => 1
                        [resumeid] => 1
                        [jobseeker_data] => stdClass Object
                            (
                                [username] => jobseeker jobseeker
                                [useremail] => jobseeker@tretrt.jo
                                [userrole] => 2
                            )
                        [employer_data] => stdClass Object
                            (
                                [username] => employer employer
                                [useremail] => employer@emigal.com
                                [userrole] => 1
                            )

                    )*/
                        $record = $this->getRecordByTablenameAndId('wj_portal_messages', $id);
                        if(empty($record)){// to avoid notices in case of no data
                            return;
                        }
                        $ref_data = '';
                        if(is_numeric($record->jobid) && $record->jobid > 0){ // if message is about job apply then data will have jobid
                            $ref_data = WPJOBPORTALincluder::getJSModel('job')->getJobTitleById($record->jobid);
                            //$ref_data .= '&nbsp;('. __('Job' ,'wp-job-portal') .')';
                        }elseif(is_numeric($record->resumeid) && $record->resumeid > 0){ // if message is about job apply then data will have jobid
                            $ref_data = WPJOBPORTALincluder::getJSModel('resume')->getResumeTitleById($record->resumeid);
                            //$ref_data .= '&nbsp;('. __('Resume','wp-job-portal').')';
                        }
                        // define values for sender and recipient
                        //(since we are using same email template for job seekr and employer notification so using "sendbyid" to identify sender and reicpient)
                        $send_email_employer = 0;
                        $send_email_jobseeker = 0;
                        $Email = ''; // handling log error
                        if($record->sendby == $record->jobseekerid){// jobseeker sent message
                            $recipient_name = $record->employer_data->username;
                            $sender_name = $record->jobseeker_data->username;
                            $Email = $record->employer_data->useremail;
                            $sender_role = __('Job Seeker','wp-job-portal');
                            $send_email_employer = 1;
                        }elseif($record->sendby == $record->employerid){// employer sent message
                            $recipient_name = $record->jobseeker_data->username;
                            $sender_name = $record->employer_data->username;
                            $Email = $record->jobseeker_data->useremail;
                            $sender_role = __('Employer','wp-job-portal');
                            $send_email_jobseeker = 1;
                        }
                        if($Email == ""){
                            if(current_user_can('manage_options')){ // admin
                                $recipient_name = $record->jobseeker_data->username;
                                $sender_name = $record->employer_data->username;
                                $Email = $record->jobseeker_data->useremail;
                                $sender_role = __('Admin','wp-job-portal');
                                $send_email_jobseeker = 1;
                            }
                        }

                        $message = $record->message;
                        $message_link_id = $record->replytoid;
                        if(is_numeric($message_link_id) && $message_link_id > 0){
                        }else{
                            $message_link_id = $record->id;
                        }
                        $link =  wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'message', 'wpjobportallt'=>'sendmessage', 'wpjobportalid'=>$message_link_id, 'wpjobportalpageid'=> wpjobportal::wpjobportal_getPageid() ));

                        $matcharray = array(
                            '{RECIPIENT_NAME}' => $recipient_name,
                            '{SENDER_NAME}' => $sender_name,
                            '{SENDER_USER_ROLE}' => $sender_role,
                            '{MESSAGE_TEXT}' => $message,
                            '{ENTITY_INFO}' => $ref_data,
                            '{MESSAGE_LINK}' => $link,
                            '{CURRENT_YEAR}' => gmdate('Y'),
                            '{EMAIL}' => $Email,
                            '{SITETITLE}' => $siteTitle
                        );
                        $template = $this->getTemplateForEmail('new-message');
                        $getEmailStatus = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getEmailTemplateStatus('new_message');
                        $msgSubject = $template->subject;
                        $msgBody = $template->body;
                        $this->replaceMatches($msgSubject, $matcharray);
                        $this->replaceMatches($msgBody, $matcharray);
                        $senderEmail = $config_array['mailfromaddress'];
                        $senderName = $config_array['mailfromname'];
                        // send email

                        // echo var_dump($msgSubject);
                        // echo var_dump($msgBody);

                        // echo '<pre>';print_r($matcharray);echo '</pre>';
                        // die('email model 1237 ');

                        // sending email for employer
                        if ($send_email_employer == 1 && $getEmailStatus->employer == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for send message
                        }

                        // sending email for jobseeker
                        if ($send_email_jobseeker == 1 && $getEmailStatus->jobseeker == 1) {
                            $this->sendEmail($Email, $msgSubject, $msgBody, $senderEmail, $senderName, '', 8); // 8 action for send message
                        }



                        // $link = esc_url_raw(admin_url("admin.php?page=wpjobportal"));
                        // $matcharray['{CONTROL_PANEL_LINK}'] = $link;
                        // $msgSubject = $template->subject;
                        // $msgBody = $template->body;
                        // $this->replaceMatches($msgSubject, $matcharray);
                        // $this->replaceMatches($msgBody, $matcharray);
                        // $senderEmail = $config_array['mailfromaddress'];
                        // $senderName = $config_array['mailfromname'];
                        // // New employer registration mail to admin
                        // if ($getEmailStatus->admin == 1) {
                        //     $adminEmailid = $config_array['adminemailaddress'];
                        //     $this->sendEmail($adminEmailid, $msgSubject, $msgBody, $senderEmail, $senderName, '', 4); // 4 action for job gold hock
                        // }
                        break;
                }

                break;
        }
    }

    function getTemplate($tempfor) {

        switch ($tempfor) {
            case 'd-cm' : $tempatefor = 'company-delete';
                break;
            case 'ew-obv' : $tempatefor = 'job-new-vis';
                break;
            case 'em-n' : $tempatefor = 'employer-new';
                break;
            case 'obs-n' : $tempatefor = 'jobseeker-new';
                break;
            case 'ob-d' : $tempatefor = 'job-delete';
                break;
            case 'obse-ps' : $tempatefor = 'jobseeker-purcahse-package-status';
                break;
            case 'js-jap' : $tempatefor = 'jobapply-jobseeker';
                break;
            case 'em-jap' : $tempatefor = 'jobapply-employer';
                break;
            case 'ew-cm' : $tempatefor = 'company-new';
                break;
            case 'cm-sts' : $tempatefor = 'company-status';
                break;
            case 'cm-rj' : $tempatefor = 'company-rejecting';
                break;
            case 'ew-ob' : $tempatefor = 'job-new';
                break;
            case 'ob-sts' : $tempatefor = 'job-Status';
                break;
            case 'ob-rj' : $tempatefor = 'job-rejecting';
                break;
            case 'ap-rs' : $tempatefor = 'applied-resume_status';
                break;
            case 'ew-rm' : $tempatefor = 'resume-new';
                break;
            case 'ew-rmv' : $tempatefor = 'resume-new-vis';
                break;
            case 'rm-sts' : $tempatefor = 'resume-status';
                break;
            case 'ew-ms' : $tempatefor = 'message-email';
                break;
            case 'rm-rj' : $tempatefor = 'resume-rejecting';
                break;
            case 'ob-pe' : $tempatefor = 'jobseeker-package-expire';
                break;
            case 'em-pe' : $tempatefor = 'employer-package-expire';
                break;
            case 'em-pc' : $tempatefor = 'employer-purchase-credit-pack';
                break;
            case 'obs-pc' : $tempatefor = 'jobseeker-purchase-credit-pack';
                break;
            case 'ms-sy' : $tempatefor = 'message-email';
                break;
            case 'jb-at' : $tempatefor = 'job-alert';
                break;
            case 'jb-at-vis' : $tempatefor = 'job-alert-visitor';
                break;
            case 'jb-to-fri' : $tempatefor = 'job-to-friend';
                break;
            case 'd-rs' : $tempatefor = 'resume-delete';
                break;
            case 'ad-jap' : $tempatefor = 'jobapply-jobapply';
                break;
            case 'ap-jap' : $tempatefor = 'applied-resume_status';
                break;
            case 'ew-pk-ad': $tempatefor = 'package-purchase-admin';
                break;
            case 'ew-pk': $tempatefor = 'package-purchase';
                break;
            case 'st-pk': $tempatefor = 'package-status';
                break;
            case 'new-msg': $tempatefor = 'new-message';
                break;
            default :
                $tempatefor = 'company-new';
                break;
        }
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` WHERE templatefor = '".esc_sql($tempatefor)."'";
        wpjobportal::$_data[0] = wpjobportaldb::get_row($query);
        return;
    }

    function storeEmailTemplate($data) {
        if (empty($data))
            return false;
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        $data['body'] = wpautop(wptexturize(wpjobportalphplib::wpJP_stripslashes(WPJOBPORTALrequest::getVar('body','post','','',1))));;
        $row = WPJOBPORTALincluder::getJSTable('emailtemplate');
        if (!$row->bind($data)) {
            return WPJOBPORTAL_SAVE_ERROR;
        }
        if (!$row->store()) {
            return WPJOBPORTAL_SAVE_ERROR;
        }

        return WPJOBPORTAL_SAVED;
    }

    function sendMailtoVisitor($jobid) {
        if ($jobid)
            if ((is_numeric($jobid) == false) || ($jobid == 0) || ($jobid == ''))
                return false;

        $templatefor = 'job-new-vis';

        $query = "SELECT template.* FROM `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` AS template	WHERE template.templatefor = '" . esc_sql($templatefor)."'";

        $template = wpjobportaldb::get_row($query);
        $msgSubject = $template->subject;
        $msgBody = $template->body;
        $jobquery = "SELECT job.id AS id,job.title, job.jobstatus,job.jobid AS jobid, company.name AS companyname, cat.cat_title AS cattitle,job.sendemail,company.contactemail, CONCAT(user.first_name,' ',user.last_name) AS contactname
                              FROM `" . wpjobportal::$_db->prefix . "wj_portal_jobs` AS job
                              ".wpjobportal::$_company_job_table_join." JOIN `" . wpjobportal::$_db->prefix . "wj_portal_companies` AS company ON company.id = job.companyid
                              LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user ON user.id = company.uid
                              LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_categories` AS cat ON cat.id = job.jobcategory
                              WHERE job.id = " . esc_sql($jobid);
        $jobuser = wpjobportaldb::get_row($jobquery);
        if ($jobuser->jobstatus == 1) {

            $CompanyName = $jobuser->companyname;
            $JobCategory = $jobuser->cattitle;
            $ContactName = $jobuser->contactname;
            $JobTitle = $jobuser->title;
            if ($jobuser->jobstatus == 1)
                $JobStatus = esc_html(__('Approved', 'wp-job-portal'));
            else
                $JobStatus = esc_html(__('Waiting for approval', 'wp-job-portal'));
            $EmployerEmail = $jobuser->contactemail;
            $ContactName = $jobuser->contactname;
            if($ContactName == ''){
                $ContactName = esc_html(__('Visitor', 'wp-job-portal'));
            }
            $siteTitle = wpjobportal::$_config->getConfigValue('title');
			$joblink = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob', 'wpjobportalid'=>$jobid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
            $msgSubject = wpjobportalphplib::wpJP_str_replace('{COMPANY_NAME}', $CompanyName, $msgSubject);
            $msgSubject = wpjobportalphplib::wpJP_str_replace('{CONTACT_NAME}', $ContactName, $msgSubject);
            $msgSubject = wpjobportalphplib::wpJP_str_replace('{JOB_CATEGORY}', $JobCategory, $msgSubject);
            $msgSubject = wpjobportalphplib::wpJP_str_replace('{JOB_TITLE}', $JobTitle, $msgSubject);
            $msgSubject = wpjobportalphplib::wpJP_str_replace('{JOB_STATUS}', $JobStatus, $msgSubject);
            $msgSubject = wpjobportalphplib::wpJP_str_replace('{EMPLOYER_NAME}', $ContactName, $msgSubject);
            $msgSubject = wpjobportalphplib::wpJP_str_replace('{JOB_LINK}', $joblink, $msgSubject);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{SITETITLE}', $siteTitle, $msgSubject);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{COMPANY_NAME}', $CompanyName, $msgBody);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{CONTACT_NAME}', $ContactName, $msgBody);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{JOB_CATEGORY}', $JobCategory, $msgBody);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{JOB_TITLE}', $JobTitle, $msgBody);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{JOB_STATUS}', $JobStatus, $msgBody);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{EMPLOYER_NAME}', $ContactName, $msgBody);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{JOB_LINK}', $joblink, $msgBody);
            $msgBody = wpjobportalphplib::wpJP_str_replace('{SITETITLE}', $siteTitle, $msgBody);
            //$msgBody = wpjobportalphplib::wpJP_str_replace('{EMAIL}', $Email, $msgBody); // handling log error
            $msgBody = wpjobportalphplib::wpJP_str_replace('{CURRENT_YEAR}', gmdate('Y'), $msgBody);

            $config = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('visitor');
            if ($config['visitor_can_edit_job'] == 1) {
                $path = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'employer', 'wpjobportallt'=>'addjob', 'email'=>$jobuser->contactemail, 'jobid'=>$jobuser->jobid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                $path = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'employer', 'wpjobportallt'=>'addjob', 'wpjobportalid'=>$jobuser->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                $text = '<br><a href="' . $path . '" target="_blank" >' . esc_html(__('click here to edit job', 'wp-job-portal')) . '</a>';
                $msgBody .= $text;
            }

            $emailconfig = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('email');
            $senderName = $emailconfig['mailfromname'];
            $senderEmail = $emailconfig['mailfromaddress'];

            $recevierEmail = $EmployerEmail;

            WPJOBPORTALincluder::getJSModel('common')->sendEmail($recevierEmail, $msgSubject, $msgBody, $senderEmail, $senderName );
        }
    }

    function getTemplateForEmail($templatefor) {
        $query = "SELECT * FROM `" . wpjobportal::$_db->prefix . "wj_portal_emailtemplates` WHERE templatefor = '" . esc_sql($templatefor) . "'";
        $template = wpjobportal::$_db->get_row($query);
        if (wpjobportal::$_db->last_error != null) {
            WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError();
        }
        return $template;
    }

    function replaceMatches(&$string, $matcharray) {
        foreach ($matcharray AS $find => $replace) {
            $string = wpjobportalphplib::wpJP_str_replace($find, $replace, $string);
        }
    }
    function wpjobportal_set_html_content_type() {
        return 'text/html';
    }

    function sendEmail($recevierEmail, $subject, $body, $senderEmail, $senderName, $attachments = '') {
        if (!$senderName)
            $senderName = wpjobportal::$_configuration['title'];
        $headers = 'From: ' . $senderName . ' <' . $senderEmail . '>' . "\r\n";
        add_filter('wp_mail_content_type', array($this,'wpjobportal_set_html_content_type'));
        $body = wpjobportalphplib::wpJP_preg_replace('/\r?\n|\r/', '<br/>', $body);
        $body = wpjobportalphplib::wpJP_str_replace(array("\r\n", "\r", "\n"), "<br/>", $body);
        $body = nl2br($body);
        wp_mail($recevierEmail, $subject, $body, $headers, $attachments);
    }

    function getRecordByTablenameAndId($tablename, $id,$actionid = null) {
        if (!is_numeric($id))
            return false;
        $query = null;
        switch ($tablename) {
            case 'wj_portal_companies':

                $query = 'SELECT company.name AS companyname,CONCAT(user.first_name," ",user.last_name) AS username,user.emailaddress AS useremail
                            , company.status AS status,company.contactemail AS companyuseremail,company.isfeaturedcompany AS featuredcompany
                            FROM `' . wpjobportal::$_db->prefix . 'wj_portal_companies` AS company
                            LEFT JOIN `' . wpjobportal::$_db->prefix . 'wj_portal_users` AS user ON user.id = company.uid
                            WHERE company.id = ' . esc_sql($id);
                break;
            case 'wj_portal_jobs':
                $decisionalquery = 'SELECT uid FROM `' . wpjobportal::$_db->prefix . 'wj_portal_jobs` AS job WHERE id=' . esc_sql($id);
                $decisionalrecord = wpjobportal::$_db->get_row($decisionalquery);
                //query for get visitor jobs
                if ($decisionalrecord->uid == 0) {
                    $query = 'SELECT job.title AS jobtitle,company.name AS companyname,job.status AS status
                                ,company.contactemail AS useremail,company.uid, job.isfeaturedjob AS featuredjob,job.params
                            FROM `' . wpjobportal::$_db->prefix . 'wj_portal_jobs` AS job
                            '.wpjobportal::$_company_job_table_join.' JOIN `' . wpjobportal::$_db->prefix . 'wj_portal_companies` AS company ON job.companyid = company.id
                            WHERE job.id = ' . esc_sql($id);
                }
                //query for get jobs
                else {
                    $query = 'SELECT user.id AS id,job.title AS jobtitle,company.name AS companyname, CONCAT(user.first_name," ",user.last_name) AS username,job.status AS status
                    ,company.contactemail AS useremail ,company.uid, job.isfeaturedjob AS featuredjob,job.params
                            FROM `' . wpjobportal::$_db->prefix . 'wj_portal_jobs` AS job
                            '.wpjobportal::$_company_job_table_join.' JOIN `' . wpjobportal::$_db->prefix . 'wj_portal_companies` AS company ON job.companyid = company.id
                            JOIN `' . wpjobportal::$_db->prefix . 'wj_portal_users` AS user ON user.id = job.uid
                            WHERE job.id = ' . esc_sql($id);
                }
                //echo $query;die;
                break;
            case 'wj_portal_resume':
                $decisionalquery = 'SELECT uid FROM `' . wpjobportal::$_db->prefix . 'wj_portal_resume` AS rs WHERE id=' . esc_sql($id);
                $decisionalrecord = wpjobportal::$_db->get_row($decisionalquery);
                if ($decisionalrecord->uid == 0) {
                    //query for visitor resume
                    $query = 'SELECT rs.application_title AS resumetitle,rs.email_address AS useremail,rs.status AS resumestatus,  rs.first_name AS firstname, rs.last_name AS lastname,rs.uid, rs.isfeaturedresume AS featuredresume,rs.params
                            FROM `' . wpjobportal::$_db->prefix . 'wj_portal_resume` AS rs
                            WHERE rs.id = ' . esc_sql($id);
                }
                //query for resume
                $query = 'SELECT rs.application_title AS resumetitle, CONCAT(user.first_name," ",user.last_name) AS username,rs.email_address AS useremailfromresume, rs.isfeaturedresume AS featuredresume,rs.params
                        ,rs.first_name AS firstname, rs.last_name AS lastname, rs.email_address AS useremail,rs.status AS resumestatus,rs.uid
                            FROM `' . wpjobportal::$_db->prefix . 'wj_portal_resume` AS rs
                            JOIN `' . wpjobportal::$_db->prefix . 'wj_portal_users` AS user ON user.id = rs.uid
                            WHERE rs.id = ' . esc_sql($id);
                break;
            case 'users':
                $query = 'SELECT CONCAT(u.first_name," ",u.last_name) AS username, u.emailaddress AS useremail, u.roleid AS userrole
                            FROM `' . wpjobportal::$_db->prefix . 'wj_portal_users` AS u
                            WHERE u.id = ' . esc_sql($id);
                break;
            case 'wj_portal_userpackages':
                $query = "SELECT package.title AS packagename,invoice.amount AS price,package.isfree,invoice.currencyid,upak.status,user.first_name as username,user.emailaddress AS useremailaddress,user.id AS userid
                FROM `" . wpjobportal::$_db->prefix . "wj_portal_userpackages` AS upak
                JOIN `" . wpjobportal::$_db->prefix . "wj_portal_packages` AS package ON package.id = upak.packageid
                JOIN `" . wpjobportal::$_db->prefix . "wj_portal_users` AS user ON upak.uid = user.id
                LEFT JOIN `" . wpjobportal::$_db->prefix . "wj_portal_invoices` AS invoice ON invoice.recordid = upak.id
                WHERE upak.id = ".esc_sql($id);
                break;
            case 'wj_portal_jobapply':
                $query = 'SELECT rs.first_name AS firstname,rs.last_name AS lastname, jobap.action_status AS resumestatus , jobap.status AS jobapplystatus,rs.email_address AS resumeemail,job.title AS jobtitle,com.contactemail AS companycontactemail,com.name AS companyname, rs.application_title AS resumetitle, CONCAT(uforemployer.first_name," ",uforemployer.last_name) AS username, uforemployer.emailaddress AS useremailforemployer,uforjobseeker.emailaddress AS useremailforjobseeker,job.params
                            FROM ' . wpjobportal::$_db->prefix . 'wj_portal_jobapply AS jobap
                            JOIN ' . wpjobportal::$_db->prefix . 'wj_portal_jobs AS job ON jobap.jobid = job.id
                            '.wpjobportal::$_company_job_table_join.' JOIN ' . wpjobportal::$_db->prefix . 'wj_portal_companies AS com ON job.companyid = com.id
                            JOIN ' . wpjobportal::$_db->prefix . 'wj_portal_resume AS rs ON rs.id = jobap.cvid
                            JOIN ' . wpjobportal::$_db->prefix . 'wj_portal_users AS uforemployer ON uforemployer.id = com.uid
                            JOIN ' . wpjobportal::$_db->prefix . 'wj_portal_users AS uforjobseeker ON uforjobseeker.id = jobap.uid
                            WHERE jobap.id =' . esc_sql($id);
                break;
            case 'wj_portal_messages':
                $query = "SELECT message.message, message.sendby,message.created,message.jobseekerid
                        ,message.employerid,message.id,message.status,message.replytoid, message.jobid, message.resumeid
                        FROM `" . wpjobportal::$_db->prefix . "wj_portal_messages` AS message
                        WHERE message.id = " . esc_sql($id);
                $data = wpjobportaldb::get_row($query);

                if(!empty($data)){
                    $jobseeker_data = new stdClass();
                    if(isset($data->jobseekerid) && $data->jobseekerid > 0){
                        $query = 'SELECT CONCAT(u.first_name," ",u.last_name) AS username, u.emailaddress AS useremail, u.roleid AS userrole
                                    FROM `' . wpjobportal::$_db->prefix . 'wj_portal_users` AS u
                                    WHERE u.id = ' . esc_sql($data->jobseekerid);
                                    $jobseeker_data = wpjobportaldb::get_row($query);
                    }
                    $employer_data = new stdClass();
                    if(isset($data->employerid) && is_numeric($data->employerid) && $data->employerid > 0){
                        $query = 'SELECT CONCAT(u.first_name," ",u.last_name) AS username, u.emailaddress AS useremail, u.roleid AS userrole
                                    FROM `' . wpjobportal::$_db->prefix . 'wj_portal_users` AS u
                                    WHERE u.id = ' . esc_sql($data->employerid);
                                    $employer_data = wpjobportaldb::get_row($query);
                    }
                    $data->jobseeker_data = $jobseeker_data;
                    $data->employer_data = $employer_data;

                }
                return $data;

                break;
        }
        if ($query != null) {
            $record = wpjobportal::$_db->get_row($query);
            return $record;
        }
        return false;
    }
    function getMessagekey(){
        $key = 'emailtemplate';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }


}

?>
