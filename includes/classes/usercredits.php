<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALUserCredits {

    function doAction($actionname) {
        $nonce = WPJOBPORTALrequest::getVar('js_nonce');
        if (! wp_verify_nonce( $nonce, 'wp-job-portal-nonce') ) {
            die( 'Security check Failed' );
        }
        $result = false;
        $id = WPJOBPORTALRequest::getVar('id');
        $payment = WPJOBPORTALRequest::getVar('payment');
        $actionid = WPJOBPORTALRequest::getVar('actiona');
        switch ($actionname) {
            case 'featured_company':
            $result = apply_filters('wp_job_portal_addons_company_credit_featurecompany',$id,$actionid,false,$payment);
                break;
            case 'featured_job':
                $result = apply_filters('wpjobportal_addons_admin_feature_credit_popupaction',$id,$actionid,false,$payment);
                break;
            case 'featured_resume':
                $result = apply_filters('wpjobportal_resume_feauture_action_bottom',$id,$actionid,false,$payment);
                break;
            case 'copy_job': // here we use the add job just for the copy job functionality
                $result = apply_filters('wp_job_portal_addon_action_credit_copyjob',$id,$actionid,false);
                break;
        }
        return $result;
    }

    function getUserCreditsDetailForAction($actionname) {
        $nonce = WPJOBPORTALrequest::getVar('js_nonce');
        if (! wp_verify_nonce( $nonce, 'wp-job-portal-nonce') ) {
            die( 'Security check Failed' );
        }
        $isadmin = WPJOBPORTALrequest::getVar('isadmin');
        $id = WPJOBPORTALrequest::getVar('id');
        $themecall = WPJOBPORTALrequest::getVar('themecall');
        $wpjobportal_pageid = WPJOBPORTALrequest::getVar('wpjobportal_pageid');
        if($isadmin != 1){
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
            if ($uid == 0)
                return false;
            $result = false; // by default action is not default if case is not found
            switch ($actionname) {
                case 'featured_company':
                    $result = $this->getValidate($uid, 'featuredcompany', 'canAddFeaturedCompany');
                    break;
                case 'featured_job':
                    $result = $this->getValidate($uid, 'featuredjob', 'canAddFeaturedJob');
                    break;
                case 'featured_resume':
                    $result = $this->getValidate($uid, 'featureresume', 'canAddFeaturedResume');
                    break;
                case 'view_company_contact_detail':
                    $result = true; // always set to true b/c if company contact detail is allowed then this button show contact detail not be shown
                    break;
                case 'view_resume_contact_detail':
                    $result = true; // always set to true b/c if company contact detail is allowed then this button show contact detail not be shown
                    break;
                case 'resume_save_search':
                    if(in_array('resumesearch', wpjobportal::$_active_addons)){
                        $result = apply_filters('wpjobportal_addons_admin_resume_save_search',false);  // always set to true b/c if company contact detail is allowed then this button show contact detail not be shown
                    }
                    break;
                case 'add_department':
                    $result = $this->getValidate($uid, 'departments', 'canAddDepartment');
                    break;
                case 'add_job':
                case 'copy_job':
                    $result = $this->getValidate($uid, 'job', 'canAddJob',$id);
                    break;
                case 'add_company':
                    $result = $this->getValidate($uid, 'company', 'canAddCompany');
                    break;
                case 'add_resume':
                    $result = $this->getValidate($uid, 'resume', 'canAddResume');
                    break;
                case 'add_job_alert':
                   $result = $this->getValidate($uid, 'jobalert', 'canAddJobAlert');
                    break;
                case 'job_apply':
                    $result = $this->getValidate($uid, 'jobapply', 'canApplyOnJob',$id);
                    break;
            }
        }else{
            $result = true;
        }
        if ($result === true) {
            if($isadmin == 1){
                $html = WPJOBPORTALincluder::getObjectClass('popup')->getPopupForAdmin($actionname,$themecall,$wpjobportal_pageid);
            }else{
                $html = WPJOBPORTALincluder::getObjectClass('popup')->getPopupFor($actionname,$themecall,$wpjobportal_pageid);
            }
        } else {
            $html = WPJOBPORTALincluder::getObjectClass('popup')->getErrorPopupFor($actionname,$wpjobportal_pageid,$themecall,$result);// fourth parameter ($result) is to manager already applied on a job case.
        }
        return $html;
    }

    private function getValidate($uid, $model, $function,$id=0) {
        if (!is_numeric($uid))
            return false;
        if($id == 0){
            $result = WPJOBPORTALincluder::getJSModel($model)->$function($uid);
        }else{// to handle job appply case
            $result = WPJOBPORTALincluder::getJSModel($model)->$function($id,$uid);
        }
        return $result;
    }
}

?>
