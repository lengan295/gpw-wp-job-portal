<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALajax {

    function __construct() {
        add_action("wp_ajax_wpjobportal_ajax", array($this, "ajaxhandler")); // when user is login
        add_action("wp_ajax_nopriv_wpjobportal_ajax", array($this, "ajaxhandler")); // when user is not login
        add_action("wp_ajax_wpjobportal_ajax_popup", array($this, "ajaxhandlerpopup")); // when user is login
        add_action("wp_ajax_nopriv_wpjobportal_ajax_popup", array($this, "ajaxhandlerpopup")); // when user is not login
        add_action("wp_ajax_wpjobportal_ajax_popup_action", array($this, "ajaxhandlerpopupaction")); // when user is login
        add_action("wp_ajax_nopriv_wpjobportal_ajax_popup_action", array($this, "ajaxhandlerpopupaction")); // when user is not login
        add_action("wp_ajax_wpjobportal_loginwith_ajax", array($this, "ajaxhandlerloginwith")); // when user is login
        add_action("wp_ajax_nopriv_wpjobportal_loginwith_ajax", array($this, "ajaxhandlerloginwith")); // when user is not login
    }

    function ajaxhandler() {
        $fucntin_allowed = array('DataForDepandantFieldResume', 'DataForDepandantField', 'saveJobShortlist', 'saveJobShortlistJobManager', 'getQuickViewByJobId', 'getShortListViewByJobId', 'getShortListViewByJobIdJobPortal', 'getApplyNowByJobid', 'jobapply', 'jobapplyjobmanager', 'getTellaFriend', 'getTellaFriendJobManager', 'deletecompanylogo', 'deleteResumeLogo', 'getuserlistajax', 'getLogForUserById', 'getFieldsForComboByFieldFor', 'getSectionToFillValues', 'getUserIdByCompanyid', 'changeNotifyOfNotifications', 'changeViewOfNotifications', 'getOptionsForFieldEdit', 'listdepartments', 'saveTokenInputTag', 'makeJobCopyAjax', 'getsubcategorypopup', 'updateJobApplyResumeStatus', 'getResumeCommentSection', 'getFolderSection', 'saveToFolderResume', 'storeResumeComments', 'setResumeRatting', 'getResumeDetail', 'getEmailFields', 'jobapplyid', 'getFolderSection', 'getFolderSectionJobManager', 'saveToFolderResume', 'sendEmailToJobSeeker', 'setJobApplyRating', 'getResumeDetailJobManager', 'getEmailFieldsJobManager', 'hideTemplateBanner', 'getListTranslations', 'validateandshowdownloadfilename', 'getlanguagetranslation', 'getPacakageListByUid', 'canceljobapplyasvisitor', 'visitorapplyjob', 'removeResumeFileById', 'getResumeSectionAjax', 'deleteResumeSectionAjax', 'getOptionsForEditSlug', 'getAllRoleLessUsersAjax', 'getNextJobs', 'getNextTemplateJobs','savetokeninputcity','sendmessageresume', 'sendmailtofriend', 'getJobApplyDetailByid', 'setListStyleSession','sendmailtofriendJobManager', 'getResumeCommentSectionJobManager','getPaymentPopup','getPackagePopupForFeaturedCompany','getPackagePopupForFeaturedJob','getPackagePopupForFeaturedResume','getPackagePopupForJobAlert','getPackagePopupJobView','getPackagePopupForCopyJob','getPackagePopupForCompanyContactDetail','getPackagePopupForResumeContactDetail','gettagsbytagname','listDepartments','getPackagePopupForDepartment','deleteUserPhoto','getStripePlans','downloadandinstalladdonfromAjax','getChildForVisibleCombobox','isFieldRequired','getFieldsForComboBySection','getUserRoleBasedInfo');
        $task = WPJOBPORTALrequest::getVar('task');
        if($task != '' && in_array($task, $fucntin_allowed)){
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
			$module = str_replace("..","",$module);
			$module = str_replace("/","",$module);
            $result = WPJOBPORTALincluder::getJSModel($module)->$task();
            echo $result;
            die();
        }else{
            die('Not Allowed!');
        }
    }

    function ajaxhandlerpopup() {
        $task = WPJOBPORTALrequest::getVar('task');
        $result = WPJOBPORTALincluder::getObjectClass('usercredits')->getUserCreditsDetailForAction($task);
        echo $result;
        die();
    }

    function ajaxhandlerpopupaction() {
        $task = WPJOBPORTALrequest::getVar('task');
        $result = WPJOBPORTALincluder::getObjectClass('usercredits')->doAction($task);
        echo $result;
        die();
    }

    function ajaxhandlerloginwith() {
        $socialmedia = WPJOBPORTALrequest::getVar('socialmedia');
        $task = WPJOBPORTALrequest::getVar('task');
        switch ($socialmedia) {
            case 'facebook':
                $result = WPJOBPORTALincluder::getObjectClass('facebook')->$task();
                break;
            case 'linkedin':
                $result = WPJOBPORTALincluder::getObjectClass('linkedin')->$task();
                break;
            case 'xing':
                $result = WPJOBPORTALincluder::getObjectClass('xing')->$task();
                break;
        }
        echo $result;
        die();
    }

}

$jsajax = new WPJOBPORTALajax();
?>
