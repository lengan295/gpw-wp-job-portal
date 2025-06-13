<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALMessages {
    /*
     * setLayoutMessage
     * @params $message = Your message to display
     * @params $type = Messages types => 'updated','error','update-nag'
     */

    public static $counter;

    public static function setLayoutMessage($message, $type, $msgkey) {
        WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable($message,$type,'notification',$msgkey);
    }

    public static function getLayoutMessage($msgkey) {
        $frontend = (is_admin()) ? '' : 'frontend';
        $divHtml = '';
        $notificationdata = WPJOBPORTALincluder::getObjectClass('wpjpnotification')->getNotificationDatabySessionId('notification',$msgkey,true);
        if (isset($notificationdata['msg'][0]) && isset($notificationdata['type'][0])) {
            for ($i = 0; $i < COUNT($notificationdata['msg']); $i++){
                if (isset($notificationdata['msg'][$i]) && isset($notificationdata['type'][$i])) {
                    if(is_admin()){
                        $divHtml .= '<div class="frontend ' . $notificationdata['type'][$i] . '"><p>' . $notificationdata['msg'][$i] . '</p></div>';
                    }else{
                        if(wpjobportal::$theme_chk != 0){
                            if($notificationdata['type'][$i] == 'updated'){
                                $alert_class = 'success';
                                $img_name = 'job-alert-successful.png';
                            }elseif($notificationdata['type'][$i] == 'saved'){
                                $alert_class = 'success';
                                $img_name = 'job-alert-successful.png';
                            }elseif($notificationdata['type'][$i] == 'saved'){
                                        //$alert_class = 'info';
                                        //$alert_class = 'warning';
                            }elseif($notificationdata['type'][$i] == 'error'){
                                $alert_class = 'danger';
                                $img_name = 'job-alert-unsuccessful.png';
                            }
                            $divHtml .= '<div class="alert alert-' . $alert_class . '" role="alert" id="autohidealert">
                                            <img class="leftimg" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/'.$img_name.'" />
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            '. $notificationdata['msg'][$i] . '
                                        </div>';
                         }else{
                             $pu = "";
                             if($notificationdata['type'][$i] == 'updated'){
                                 $pu = 'published'; 
                             }
                            $divHtml .= '<div class="'. $frontend." ".$notificationdata['type'][$i]." ".$pu.'"><p>' . $notificationdata['msg'][$i] . '</p></div>';
                          
                            
                        }
                    }
                }
            }
        }

	    echo wp_kses($divHtml, WPJOBPORTAL_ALLOWED_TAGS);
    }

    public static function getMSelectionEMessage() { // multi selection error message
        return esc_html(__('Please first make a selection from the list', 'wp-job-portal'));
    }

    public static function getMessage($result, $entity) {
       $msg['message'] = esc_html(__('Unknown', 'wp-job-portal'));
        $msg['status'] = "updated";
        $msg1 = WPJOBPORTALMessages::getEntityName($entity);

        switch ($result) {
            case WPJOBPORTAL_INVALID_REQUEST:
                $msg['message'] = esc_html(__('Invalid request', 'wp-job-portal'));
                $msg['status'] = 'error';
                break;
            case WPJOBPORTAL_SAVED:
                $msg2 = esc_html(__('has been successfully saved', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_SAVE_ERROR:
                $msg['status'] = "error";
                $msg2 = esc_html(__('has not been saved', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_DELETED:
                $msg2 = esc_html(__('has been successfully deleted', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_NOT_EXIST:
                $msg['status'] = "error";
                $msg['message'] = esc_html(__('Record not exist', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_DELETE_ERROR:
                $msg['status'] = "error";
                $msg2 = esc_html(__('has not been deleted', 'wp-job-portal'));
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (WPJOBPORTALMessages::$counter) {
                        if(WPJOBPORTALMessages::$counter > 1){
                            $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case WPJOBPORTAL_PUBLISHED:
                $msg2 = esc_html(__('has been successfully published', 'wp-job-portal'));
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (WPJOBPORTALMessages::$counter) {
                        if(WPJOBPORTALMessages::$counter > 1){
                            $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case WPJOBPORTAL_VERIFIED:
                $msg['message'] = esc_html(__('transaction has been successfully verified', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_UN_VERIFIED:
                $msg['message'] = esc_html(__('transaction has been successfully un-verified', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_VERIFIED_ERROR:
                $msg['message'] = esc_html(__('transaction has not been successfully verified', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_NOTENOUGHCREDITS:
                $this->notEnoughCredits();
                break;
            case WPJOBPORTAL_UN_VERIFIED_ERROR:
                $msg['message'] = esc_html(__('transaction has not been successfully un-verified', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_PUBLISH_ERROR:
                $msg['status'] = "error";
                $msg2 = esc_html(__('has not been published', 'wp-job-portal'));
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (WPJOBPORTALMessages::$counter) {
                            $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case WPJOBPORTAL_UN_PUBLISHED:
                $msg2 = esc_html(__('has been successfully unpublished', 'wp-job-portal'));
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (WPJOBPORTALMessages::$counter) {
                        if(WPJOBPORTemALMessages::$counter > 1){
                            $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . $msg['message'];
                        }
                    }
                }
                break;
            case WPJOBPORTAL_UN_PUBLISH_ERROR:
                $msg['status'] = "error";
                $msg2 = esc_html(__('has not been unpublished', 'wp-job-portal'));
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (WPJOBPORTALMessages::$counter) {
                            $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case WPJOBPORTAL_REQUIRED:
                $msg['message'] = esc_html(__('Fields has been successfully required', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_REQUIRED_ERROR:
                $msg['status'] = "error";
                if (WPJOBPORTALMessages::$counter) {
                    if (WPJOBPORTALMessages::$counter == 1)
                        $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . esc_html(__('Field has not been required', 'wp-job-portal'));
                    else
                        $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . esc_html(__('Fields has not been required', 'wp-job-portal'));
                }else {
                    $msg['message'] = esc_html(__('Field has not been required', 'wp-job-portal'));
                }
                break;
            case WPJOBPORTAL_NOT_REQUIRED:
                $msg['message'] = esc_html(__('Fields has been successfully not required', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_NOT_REQUIRED_ERROR:
                $msg['status'] = "error";
                if (WPJOBPORTALMessages::$counter) {
                    if (WPJOBPORTALMessages::$counter == 1)
                        $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . esc_html(__('Field has not been not required', 'wp-job-portal'));
                    else
                        $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . esc_html(__('Fields has not been not required', 'wp-job-portal'));
                }else {
                    $msg['message'] = esc_html(__('Field has not been not required', 'wp-job-portal'));
                }
                break;
            case WPJOBPORTAL_ORDER_UP:
                $msg['message'] = esc_html(__('Field order up successfully', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_ORDER_UP_ERROR:
                $msg['status'] = "error";
                $msg['message'] = esc_html(__('Field order up error', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_ORDER_DOWN:
                $msg['message'] = esc_html(__('Field order down successfully', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_ORDER_DOWN_ERROR:
                $msg['status'] = "error";
                $msg['message'] = esc_html(__('Field order up error', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_REJECTED:
                $msg2 = esc_html(__('has been rejected', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_APPLY:
                $msg['status'] = "updated";
                $msg2 = esc_html(__('Job applied successfully', 'wp-job-portal'));
                $msg['message'] = $msg2;
                break;
            case WPJOBPORTAL_APPLY_ERROR:
                $msg2 = esc_html(__('Error in applying job', 'wp-job-portal'));
                $msg['message'] = $msg2;
                $msg['status'] = "error";
                break;
            case WPJOBPORTAL_REJECT_ERROR:
                $msg['status'] = "error";
                $msg2 = esc_html(__('has not been rejected', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_APPROVED:
                $msg2 = esc_html(__('has been approved', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_APPROVE_ERROR:
                $msg['status'] = "error";
                $msg2 = esc_html(__('has not been approved', 'wp-job-portal'));
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                    if (WPJOBPORTALMessages::$counter) {
                        $msg['message'] = WPJOBPORTALMessages::$counter . ' ' . $msg['message'];
                    }
                }
                break;
            case WPJOBPORTAL_SET_DEFAULT:
                $msg2 = esc_html(__('has been set as default', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_UNPUBLISH_DEFAULT_ERROR:
                $msg['status'] = "error";
                $msg['message'] = esc_html(__('Unpublished field cannot set default', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_SET_DEFAULT_ERROR:
                $msg['status'] = "error";
                $msg2 = esc_html(__('has not been set as default', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_STATUS_CHANGED:
                $msg2 = esc_html(__('status has been updated', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_STATUS_CHANGED_ERROR:
                $msg['status'] = "error";
                $msg2 = esc_html(__('has not been updated', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_IN_USE:
                $msg['status'] = "error";
                $msg2 = esc_html(__('is in use', 'wp-job-portal'));
                $msg3 = esc_html(__('can not deleted it', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2 . ', ' . $msg3;
                break;
            case WPJOBPORTAL_ALREADY_EXIST:
                $msg['status'] = "error";
                $msg2 = esc_html(__('already exist', 'wp-job-portal'));
                if ($msg1)
                    $msg['message'] = $msg1 . ' ' . $msg2;
                break;
            case WPJOBPORTAL_FILE_TYPE_ERROR:
                $msg['status'] = "error";
                $msg['message'] = esc_html(__('File type error', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_FILE_SIZE_ERROR:
                $msg['status'] = "error";
                $msg['message'] = esc_html(__('File size error', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_ENABLED:
                $msg['status'] = "updated";
                $msg2 = esc_html(__('has been enabled', 'wp-job-portal'));
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
            case WPJOBPORTAL_PACKAGE_ALREADY_PURCHASED:
                $msg['status'] = "error";
                $msg2 = esc_html(__('Can not buy free package more than once', 'wp-job-portal'));
                $msg['message'] = $msg2;
                break;
            case WPJOBPORTAL_DISABLED:
                $msg['status'] = "updated";
                $msg2 = esc_html(__('has been disabled', 'wp-job-portal'));
                if ($msg1) {
                    $msg['message'] = $msg1 . ' ' . $msg2;
                }
                break;
        }
        return $msg;
    }
        private function notEnoughCredits(){
            $html = '
                    <div class="jsre-error-page-message-wrapper">
                        <div class="jsre-error-page-message-image">
                            <img alt="'.esc_attr(esc_html(__('no active package','wp-job-portal'))).'" src="'.esc_url(WPJOBPORTAL_IMAGE).'/no-package.jpg'.'" />
                        </div>
                        <div class="jsre-error-page-message-text">
                            <div class="jsre-error-page-message-txt">
                                ' . esc_html(__('You do not have enough credits','wp-job-portal')) . '
                            </div>
                        </div>
                        <div class="jsre-error-page-message-btn">
                            <a title="'.esc_attr(esc_html(__('buy packages','wp-job-portal'))).'" class="jsre-error-page-message-btn-link" href="'.esc_url(wpjobportal::wpjobportal_makeUrl(array('jsreme'=>'package', 'jsrelt'=>'packages'))).'" >'. esc_html(esc_html(__('Buy Package','wp-job-portal'))) .'</a>
                        </div>
                    </div>
            ';
            echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
        }
    static function getEntityName($entity) {
        $name = "";
        $entity = wpjobportalphplib::wpJP_strtolower($entity);
        switch ($entity) {
            case WPJOBPORTAL_SALARYRANGE:$name = esc_html(__('Salary Range', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_ADDRESSDATA:$name = esc_html(__('Address Data', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_AGE:$name = esc_html(__('Age', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_CATEGORY:$name = esc_html(__('Category', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_CITY:$name = esc_html(__('City', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_COMPANY:
                    $name = esc_html(__('Company', 'wp-job-portal'));
                    if(WPJOBPORTALMessages::$counter){
                        if(WPJOBPORTALMessages::$counter >1){
                            $name = esc_html(__('Companies', 'wp-job-portal'));
                        }
                    }
                break;
            case WPJOBPORTAL_RESUME:
                $name = esc_html(__('Resume', 'wp-job-portal'));
                    if(WPJOBPORTALMessages::$counter){
                        if(WPJOBPORTALMessages::$counter >1){
                            $name = esc_html(__('Resume', 'wp-job-portal'));
                        }
                    }
                break;
            case 'company':
                    $name = esc_html(__('Company', 'wp-job-portal'));
                    if(WPJOBPORTALMessages::$counter){
                        if(WPJOBPORTALMessages::$counter >1){
                            $name = esc_html(__('Companies', 'wp-job-portal'));
                        }
                    }
                break;
            case 'featuredcompany':$name = esc_html(__('Featured company', 'wp-job-portal'));
                break;
            case 'message':$name = esc_html(__('Message', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_COUNTRY:$name = esc_html(__('Country', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_CURRENCY:$name = esc_html(__('Currency', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_CUSTOMFIELD:
            case WPJOBPORTAL_FIELDORDERING:$name = esc_html(__('Field', 'wp-job-portal'));
                break;
            case 'department':case 'departments':$name = esc_html(__('Department', 'wp-job-portal'));
                break;
            case 'coverletter':case 'coverletters':$name = esc_html(__('Cover Letter', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_EMPLOYERPACKAGES:$name = esc_html(__('Employer package', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_EXPERIENCE:$name = esc_html(__('Experience', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_HIGHESTEDUCATION:$name = esc_html(__('Highest education', 'wp-job-portal'));
                break;
            case 'job':
                $name = esc_html(__('Job', 'wp-job-portal'));
                if(WPJOBPORTALMessages::$counter){
                    if(WPJOBPORTALMessages::$counter >1){
                        $name = esc_html(__('Jobs', 'wp-job-portal'));
                    }
                }
                break;
             case 'jobtype':$name = esc_html(__('Job type', 'wp-job-portal'));
                break;
            case 'featuredjob':$name = esc_html(__('Featured job', 'wp-job-portal'));
                break;
            case 'jobalert':$name = esc_html(__('Job alert', 'wp-job-portal'));
                break;
            case 'jobstatus':$name = esc_html(__('Job Status', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_JOBTYPE:$name = esc_html(__('Job type', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_SALARYRANGE:$name = esc_html(__('Salary Range', 'wp-job-portal'));
                break;
            case 'city':$name = esc_html(__('City', 'wp-job-portal'));
            break;
            case WPJOBPORTAL_SALARYRANGETYPE:$name = esc_html(__('Salary Range Type', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_SHIFT:$name = esc_html(__('Shift', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_STATE:$name = esc_html(__('State', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_USER:$name = esc_html(__('User', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_USERROLE:$name = esc_html(__('User role', 'wp-job-portal'));
                break;
            case 'tag':$name = esc_html(__('Tag', 'wp-job-portal'));
                break;
            case 'shortlisted':$name = esc_html(__('Short listed', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_CONFIGURATION:$name = esc_html(__('Configuration', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_EMAILTEMPLATE:$name = esc_html(__('Email Template', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_JOBSAVESEARCH:$name = esc_html(__('Job Search', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_RESUMESEARCH:$name = esc_html(__('Resume Search', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_RECORD:
                $name = esc_html(__('record', 'wp-job-portal')).'('. esc_html(__('s','wp-job-portal')) .')';
                break;
            case 'record':
                $name = esc_html(__('record', 'wp-job-portal')).'('. esc_html(__('s','wp-job-portal')) .')';
                break;
            case WPJOBPORTAL_SLUG:
                    $name = esc_html(__('Slug', 'wp-job-portal')).'('. esc_html(__('s','wp-job-portal')) .')';
                break;
            case 'slug':
                $name = esc_html(__('Slug', 'wp-job-portal')).'('. esc_html(__('s','wp-job-portal')) .')';
            break;
             case 'currency':$name = esc_html(__('Currency', 'wp-job-portal'));
                break;
            case 'country':$name = esc_html(__('Country', 'wp-job-portal'));
            break;
            case 'state':$name = esc_html(__('State', 'wp-job-portal'));
                break;
            case 'prefix':
                $name = esc_html(__('Prefix', 'wp-job-portal')).'('. esc_html(__('s','wp-job-portal')) .')';
            break;
            case folder:$name = esc_html(__('Folder', 'wp-job-portal'));
                break;
            case folderresume:$name = esc_html(__('Folder Resume', 'wp-job-portal'));
                break;
            case 'resume':
                $name = esc_html(__('Resume', 'wp-job-portal'));
                if(WPJOBPORTALMessages::$counter){
                    if(WPJOBPORTALMessages::$counter >1){
                        $name = esc_html(__('Resume', 'wp-job-portal'));
                    }
                }
                break;
            case 'featuredresume':$name = esc_html(__('Featured resume', 'wp-job-portal'));
            case 'folder':$name = esc_html(__('Folder', 'wp-job-portal'));
            break;
            case 'folderresume':$name = esc_html(__('Folder Resume', 'wp-job-portal'));
                break;
            case WPJOBPORTAL_PREFIX:
                    $name = esc_html(__('Prefix', 'wp-job-portal')).'('. esc_html(__('s','wp-job-portal')) .')';
                break;
            case 'jobsavesearch':$name = esc_html(__('Job Search', 'wp-job-portal'));
            break;
        case 'resumesearch':$name = esc_html(__('Resume Search', 'wp-job-portal'));
            break;
        case 'package':$name=esc_html(__('Package','wp-job-portal'));
            break;
            case 'purchasehistory':$name=esc_html(__('Package','wp-job-portal'));
            break;
        case 'user':$name = esc_html(__('User', 'wp-job-portal'));
                break;
        case 'userrole':$name = esc_html(__('User role', 'wp-job-portal'));
            break;
         case 'configuration':$name = esc_html(__('Configuration', 'wp-job-portal'));
            break;
        case 'highesteducation':$name = esc_html(__('Highest education', 'wp-job-portal'));
                break;
        case 'category':$name = esc_html(__('Category', 'wp-job-portal'));
                break;
        case 'salaryrangetype':$name = esc_html(__('Salary Range Type', 'wp-job-portal'));
                break;
        case 'emailtemplate':$name = esc_html(__('Email Template', 'wp-job-portal'));
                break;
        case 'careerlevel':$name = esc_html(__('Career Level', 'wp-job-portal'));
                break;
        case 'employer':$name = esc_html(__('Employer', 'wp-job-portal'));
                break;
        case 'jobseeker':$name = esc_html(__('Jobseeker', 'wp-job-portal'));
                break;
        case 'invoice':$name = esc_html(__('Invoice', 'wp-job-portal'));
                break;
        case 'customfield':
            case 'fieldordering':$name = esc_html(__('Field', 'wp-job-portal'));
                break;
        case 'wpjobportal':
            $name = esc_html(__('Options', 'wp-job-portal'));
                break;
        case 'addressdata':
            $name = esc_html(__('Address data', 'wp-job-portal'));
                break;
        }
        return $name;
    }

    public static function showMessage($message,$type,$return=0) {
        $divHtml = '';
        if($type == 'updated'){
            $alert_class = 'success';
            $img_name = 'job-alert-successful.png';
        }else if($type == 'saved'){
            $alert_class = 'success';
            $img_name = 'job-alert-successful.png';
        }else if($type == 'error'){
            $alert_class = 'danger';
            $img_name = 'job-alert-unsuccessful.png';
        }
        $divHtml .= '<div class="alert alert-' . $alert_class . '" role="alert" id="autohidealert">
            <img class="leftimg" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/'.$img_name.'" />
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            '. $message . '
        </div>';
        if($return){
            return $divHtml;
        }
        echo wp_kses($divHtml, WPJOBPORTAL_ALLOWED_TAGS);
    }

}

?>
