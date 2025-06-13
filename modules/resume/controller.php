<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALResumeController {

    private $_msgkey;

    function __construct() {
        self::handleRequest();
        $this->_msgkey = WPJOBPORTALincluder::getJSModel('resume')->getMessagekey();
        $model_resume  = WPJOBPORTALincluder::getJSModel('resume');
    }

    function handleRequest() {
        $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'resumes');
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        if (self::canaddfile($layout)) {
            $empflag  = wpjobportal::$_config->getConfigurationByConfigName('disable_employer');
            $string = "'jscontrolpanel','emcontrolpanel','visitor','resume'" ;
            $config_array = wpjobportal::$_config->getConfigurationByConfigForMultiple($string);
            switch ($layout) {
                case 'myresumes':
                    if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                        WPJOBPORTALincluder::getJSModel('resume')->getMyResumes($uid);
                        // to handle jobseeker left menu data
                        WPJOBPORTALincluder::getJSModel('jobseeker')->getResumeInfoForJobSeekerLeftMenu($uid);
                    } else {
                        if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                            wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(3,null,null,1);
                            wpjobportal::$_error_flag_message_for=3;
                        } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                            $link = wpjobportal::$_common->jsMakeRedirectURL('resume', $layout, 1);
                            $linktext = esc_html(__('Login','wp-job-portal'));
                            wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1);
                            wpjobportal::$_error_flag_message_for=1;
                            wpjobportal::$_error_flag_message_register_for=1; // register as jobseeker
                        } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                            $linktext = esc_html(__('Select role','wp-job-portal'));
                            wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1);
                            wpjobportal::$_error_flag_message_for=9;
                        }
                        if(isset($link) && isset($linktext)){
                            wpjobportal::$_error_flag_message_for_link = $link;
                            wpjobportal::$_error_flag_message_for_link_text = $linktext;
                        }
                        wpjobportal::$_error_flag = true;
                    }
                    break;
                 case 'resumes':
                    $vars = array();
                    $resume_view_type = WPJOBPORTALrequest::getVar('viewtype',null,1); // 1 for list view 2 for grid view
                    $resume_view_type=wpjobportalphplib::wpJP_str_replace("vt-","",$resume_view_type);
                    wpjobportal::$_data['viewtype'] = $resume_view_type;
                    if($resume_view_type==2){ // switch list to grid show save serch
                        //wpjobportal::$_data['issearchform'] = 1; casuing issues.
                        //wpjobportal::$_data['filter'] = "";
                    }
                    $id = WPJOBPORTALrequest::getVar('wpjobportalid');
                    if ($id) {
                        $array = wpjobportalphplib::wpJP_explode('_', $id);
                        if ($array[0] == 'tags') {
                            unset($array[0]);
                            $array = implode(' ', $array);
                            $vars['tags'] = $array;
                            wpjobportal::$_data['tags'] = $vars['tags'];
                        } else {
                            if(isset($array[1])){
                                $id = $array[1];
                                $clue = $id[0] . $id[1];
                                switch ($clue) {
                                    case '10': //Category
                                        $vars['category'] = wpjobportalphplib::wpJP_substr($id, 2);
                                        wpjobportal::$_data['categoryid'] = $array[0] . '-' . $vars['category'];
                                        break;
                                    case '13': //Search
                                        $id = wpjobportalphplib::wpJP_substr($id, 2);
                                        wpjobportal::$_data['searchid'] = $array[0] . '-' . $id;
                                        $vars['searchid'] = $id;
                                        break;
                                    case '14': //sorting in case of parama and no other option selected
                                        $sortby = $array[0];
                                        $id = '';
                                        break;
                                    case '15': //Search
                                        $id = wpjobportalphplib::wpJP_substr($id, 2);
                                        wpjobportal::$_data['aisuggestedresumes_job'] = $array[0] . '-' . $id;
                                        $vars['aisuggestedresumes_job'] = $id;
                                        break;
                                    default:
                                        $id = '';
                                        break;
                                }
                            }
                            // had to do this to handle a sorting in sef case
                            if(wpjobportalphplib::wpJP_strstr($id, 'asc') || wpjobportalphplib::wpJP_strstr($id, 'desc')){
                                wpjobportal::$_data['sanitized_args']['sortby'] = $id;
                            }
                        }
                    } else {
                        $searchtext = WPJOBPORTALrequest::getVar('search');
                        if ($searchtext) {
                            //parse id what is the meaning of it
                            $array = wpjobportalphplib::wpJP_explode('-', $searchtext);
                            $vars['searchid'] = $array[count($array) - 1];
                        } else {
                            $vars['searchid'] = '';
                        }
                        $id = WPJOBPORTALrequest::getVar('category', 'get');
                        if ($id) {
                            $array = wpjobportalphplib::wpJP_explode('-', $id);
                            $id = $array[count($array) - 1];
                            $vars['category'] = (int) $id;
                        }
                        $tags = WPJOBPORTALrequest::getVar('tags', 'get');
                        if ($tags) {
                            $tags = wpjobportal::tagfillout($tags);
                            $vars['tags'] = $tags;
                        }
                        $aisuggestedresumes_job = WPJOBPORTALrequest::getVar('aisuggestedresumes_job', 'get');
                        if ($aisuggestedresumes_job) {
                            $array = wpjobportalphplib::wpJP_explode('-', $aisuggestedresumes_job);
                            $aisuggestedresumes_job = $array[count($array) - 1];
                            $vars['aisuggestedresumes_job'] = (int) $aisuggestedresumes_job;
                        }
                    }
                    if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                        $empflag = 1;
                    }
                    WPJOBPORTALincluder::getJSModel('resume')->getResumes($vars);
                    break;
                case 'viewresume':
                case 'admin_viewresume':
                    //$layout = 'viewresume';
                    $resumeid = '';
                    try {
                        if (current_user_can('manage_options') || (WPJOBPORTALincluder::getObjectClass('user')->isemployer() && $empflag == 1 || wpjobportal::$_config->getConfigurationByConfigName('visitorview_emp_viewresume') == 1) || WPJOBPORTALincluder::getObjectClass('user')->isjobseeker() ) {
                            $resumeid = WPJOBPORTALrequest::getVar('wpjobportalid');
                            $socialid = WPJOBPORTALrequest::getVar('jsscid');
                            //check for the social id
                            if ((!is_numeric($resumeid) && $resumeid[0] . $resumeid[1] . $resumeid[2] == 'sc-') || $socialid != null) { // social
                                $idarray = wpjobportalphplib::wpJP_explode('-', $resumeid);
                                $profileid = $idarray[1];
                                wpjobportal::$_data['socialprofileid'] = $profileid;
                                wpjobportal::$_data['socialprofile'] = true;
                            } else {
                                $resumeowner = true;
                                $idarray = wpjobportalphplib::wpJP_explode('-', $resumeid);
                                $resumeid = $idarray[count($idarray) - 1];
                                $expiryflag = WPJOBPORTALincluder::getJSModel('resume')->getResumeExpiryStatus($resumeid);
                                if(wpjobportal::$_common->wpjp_isadmin()){
                                    $expiryflag = true;
                                }
                                if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker() && !wpjobportal::$_common->wpjp_isadmin()) {
                                    if (WPJOBPORTALincluder::getJSModel('resume')->getIfResumeOwner($resumeid)) {
                                        $expiryflag = true;
                                    }else{
                                        wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(10,null,null,1);
                                        wpjobportal::$_error_flag_message_for = 2;
                                        wpjobportal::$_error_flag = true;
                                        break;
                                    }
                                }
                                if ($expiryflag == false) {
                                    wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(6,null,null,1);
                                    wpjobportal::$_error_flag_message_for=6;
                                    wpjobportal::$_error_flag = true;
                                } else {
                                    WPJOBPORTALincluder::getJSModel('resume')->getResumeById($resumeid);
                                    $empflag = 1;
                                    wpjobportal::$_data['socialprofile'] = false;
                                }
                            }
                        } else {
                            if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                $link = wpjobportal::$_common->jsMakeRedirectURL('resume', $layout, 1);
                                $linktext = esc_html(__('Login','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=1;
                                wpjobportal::$_error_flag_message_register_for=2; // register as employer
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));
                            } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                $linktext = esc_html(__('Select role','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=9;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1));
                            }
                            if(isset($link) && isset($linktext)){
                                wpjobportal::$_error_flag_message_for_link = $link;
                                wpjobportal::$_error_flag_message_for_link_text = $linktext;
                            }
                        }
                        // was showing error in log code seems redundant
                        // $jobid = WPJOBPORTALrequest::getVar('jobid');
                        // $idarray = wpjobportalphplib::wpJP_explode('-', $jobid);
                        // $jobid = $idarray[count($idarray) - 1];
                    } catch (Exception $ex) {
                        wpjobportal::$_error_flag = true;
                        wpjobportal::$_error_flag_message=$ex->getMessage();
                    }
                break;
                case 'resumebycategory':
                    try {
                        if (wpjobportal::$_common->wpjp_isadmin() || (WPJOBPORTALincluder::getObjectClass('user')->isemployer() && $empflag == 1) || WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('visitorview_emp_resumecat') == 1 ) {
                            $resumeid = WPJOBPORTALrequest::getVar('resumeid');
                            WPJOBPORTALincluder::getJSModel('resume')->getResumeByCategory();
                        } else {
                            if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                                wpjobportal::$_error_flag_message_for=2;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(2,null,null,1));

                            } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                $link = wpjobportal::$_common->jsMakeRedirectURL('resume', $layout, 1);
                                $linktext = esc_html(__('Login','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=1;
                                wpjobportal::$_error_flag_message_register_for=2; // register as employer
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                            } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                $linktext = esc_html(__('Select role','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=9;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1));

                            }
                            if(isset($link) && isset($linktext)){
                                wpjobportal::$_error_flag_message_for_link = $link;
                                wpjobportal::$_error_flag_message_for_link_text = $linktext;
                            }
                        }
                    } catch (Exception $ex) {
                        wpjobportal::$_error_flag = true;
                        wpjobportal::$_error_flag_message=$ex->getMessage();
                    }
                    break;
                case 'admin_formresume':
                    try {
                            wpjobportal::$_error_flag_message = null;
                            $isouruser = WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser();
                            $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();
                            $guest = false;
                            if($isguest == true){
                                $guest = true;
                            }
                            if($isguest == false && $isouruser == false){
                                $guest = true;
                            }
                            // Check user is guest and is allowed to add resume
                            $guestallowed = 0;

                            if ($guest && in_array('visitorapplyjob', wpjobportal::$_active_addons)) {
                                $guestallowed = $config_array['visitor_can_add_resume'];
                            }
                            if ((WPJOBPORTALincluder::getObjectClass('user')->isjobseeker() && $config_array['formresume'] == 1)|| ($guestallowed == 1)  || wpjobportal::$_common->wpjp_isadmin()) {
                                wpjobportal::$_data['resumeid'] = WPJOBPORTALrequest::getVar('wpjobportalid');

                                if(is_numeric(wpjobportal::$_data['resumeid'])){
                                    if(!wpjobportal::$_common->wpjp_isadmin()){
                                        $check = WPJOBPORTALincluder::getJSModel('resume')->getIfResumeOwner(wpjobportal::$_data['resumeid']);
                                    }
                                }else{
                                    $check = WPJOBPORTALincluder::getJSModel('resume')->canAddResume($uid);
                                }
                                if (wpjobportal::$_common->wpjp_isadmin() || $guestallowed == 1 || $check == true) {
                                    if ($guestallowed == 1) {
                                        if (isset($_SESSION['wp-wpjobportal']) && isset($_SESSION['wp-wpjobportal']['resumeid'])) {
                                            wpjobportal::$_data['resumeid'] = sanitize_key($_SESSION['wp-wpjobportal']['resumeid']);
                                        }
                                    }
                                    // code to make sure the current resume being edidted is not quick apply resume
                                    if(isset(wpjobportal::$_data['resumeid']) && is_numeric(wpjobportal::$_data['resumeid'])){
                                        $quick_apply_check = WPJOBPORTALincluder::getJSModel('resume')->checkQuickApply(wpjobportal::$_data['resumeid']);
                                        if($quick_apply_check){// will be true if the resume is quick apply
                                            wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(10,null,null,1);
                                            wpjobportal::$_error_flag_message_for = 2;
                                            wpjobportal::$_error_flag = true;
                                            break;
                                        }
                                    }
                                    WPJOBPORTALincluder::getJSModel('resume')->getResumeById(wpjobportal::$_data['resumeid']);
                                }elseif(is_numeric(wpjobportal::$_data['resumeid'])){
                                    wpjobportal::$_error_flag_message_for= 3;
                                    throw new Exception(WPJOBPORTALLayout::setMessageFor(3,null,null,1));
                                }
                            } else {
                                if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                                    wpjobportal::$_error_flag_message_for=3;
                                    throw new Exception(WPJOBPORTALLayout::setMessageFor(3,null,null,1));

                                } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                    $link = wpjobportal::$_common->jsMakeRedirectURL('resume', $layout, 1);
                                    $linktext = esc_html(__('Login','wp-job-portal'));
                                    wpjobportal::$_error_flag_message_for=1;
                                    wpjobportal::$_error_flag_message_register_for=1; // register as jobseeker
                                    throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                                } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                    $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                    $linktext = esc_html(__('Select role','wp-job-portal'));
                                    wpjobportal::$_error_flag_message_for=9;
                                    throw new Exception(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1));

                                }
                                if(isset($link) && isset($linktext)){
                                    wpjobportal::$_error_flag_message_for_link = $link;
                                    wpjobportal::$_error_flag_message_for_link_text = $linktext;
                                }
                            }
                        } catch (Exception $ex) {
                            wpjobportal::$_error_flag = true;
                            wpjobportal::$_error_flag_message =$ex->getMessage();
                        }
                    break;
                case 'addresume':
                    wpjobportal::$_error_flag_message = null;
                    $isouruser = WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser();
                    $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();
                    $guest = false;
                    if($isguest == true){
                        $guest = true;
                    }
                    if($isguest == false && $isouruser == false){
                        $guest = true;
                    }
                    // Check user is guest and is allowed to add resume
                    $guestallowed = 0;

                    if ($guest) {
                        $guestallowed = $config_array['visitor_can_add_resume'];
                    }
                    try {
                        $visitorcanapply = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_apply_to_job');
                        if($guest && in_array('credits', wpjobportal::$_active_addons) && $visitorcanapply != 1){
                            $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('resume', $layout, 1);
                            $linktext = esc_html(__('Login','wp-job-portal'));
                            wpjobportal::$_error_flag_message_for=1;
                            throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));
                        }
                        if ((WPJOBPORTALincluder::getObjectClass('user')->isjobseeker() && $config_array['formresume'] == 1) || ($guestallowed == 1) && in_array('visitorapplyjob', wpjobportal::$_active_addons) || wpjobportal::$_common->wpjp_isadmin()) {
                        wpjobportal::$_data['resumeid'] = WPJOBPORTALrequest::getVar('wpjobportalid');

                            if(is_numeric(wpjobportal::$_data['resumeid'])){
                                if(!wpjobportal::$_common->wpjp_isadmin()){
                                    $check = WPJOBPORTALincluder::getJSModel('resume')->getIfResumeOwner(wpjobportal::$_data['resumeid']);
                                }
                            }else{
                                $actionname = "resume";
                                if(in_array('credits',wpjobportal::$_active_addons)){
                                        # Filter Package For Controller
                                    if(WPJOBPORTALincluder::getJSModel('resume')->UserCanAddResume($uid) == true){
                                        $data = json_decode(apply_filters('wpjobportal_addons_available_package',false,'resume','resume','canAddResume'));
                                        $check = $data->check;
                                    }else{
                                        wpjobportal::$_common->getMessagesForAddMore('Resume');
                                    }
                                        if($check == true){
                                            if(isset($data->layout) && $data->layout == "packageselection" ){
                                                $layout = $data->layout;
                                                $module = 'package';
                                            }
                                       }else{
                                            $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'package', 'wpjobportallt'=>'packages'));
                                            $linktext = esc_html(__('Buy Package', 'wp-job-portal'));
                                            wpjobportal::$_error_flag = true;
                                            wpjobportal::$_error_flag_message_for=15;
                                            throw new Exception(WPJOBPORTALLayout::setMessageFor(15,$link,$linktext,1));
                                       }
                                    }else{
                                        if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                                            $check =  true;
                                        }else{
                                            if(WPJOBPORTALincluder::getJSModel('resume')->canAddResume($uid) == false){
                                                wpjobportal::$_common->getMessagesForAddMore('Resume');
                                            }else{
                                                $check =  true;
                                            }
                                        }
                                }
                            }
                            if (wpjobportal::$_common->wpjp_isadmin() || $guestallowed == 1 || $check == true) {
                                if ($guestallowed == 1) {
                                    if (isset($_SESSION['wp-wpjobportal']) && isset($_SESSION['wp-wpjobportal']['resumeid'])) {
                                        wpjobportal::$_data['resumeid'] = sanitize_key($_SESSION['wp-wpjobportal']['resumeid']);
                                    }
                                }
                                // code to make sure the current resume being edidted is not quick apply resume
                                if(isset(wpjobportal::$_data['resumeid']) && is_numeric(wpjobportal::$_data['resumeid'])){
                                    $quick_apply_check = WPJOBPORTALincluder::getJSModel('resume')->checkQuickApply(wpjobportal::$_data['resumeid']);
                                    if($quick_apply_check){// will be true if the resume is quick apply
                                        wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(10,null,null,1);
                                        wpjobportal::$_error_flag_message_for = 2;
                                        wpjobportal::$_error_flag = true;
                                        break;
                                    }
                                }

                                WPJOBPORTALincluder::getJSModel('resume')->getResumeById(wpjobportal::$_data['resumeid']);
                                $empflag = 1;
                            }elseif(is_numeric(wpjobportal::$_data['resumeid'])){
                                wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(10,null,null,1);
                                wpjobportal::$_error_flag_message_for = 2;
                                wpjobportal::$_error_flag = true;
                                break;
                            }
                        } else {
                            // wpjobportal::$_common->validateEmployerArea();
                            // if(isset($link) && isset($linktext)){
                            //     wpjobportal::$_error_flag_message_for_link = $link;
                            //     wpjobportal::$_error_flag_message_for_link_text = $linktext;
                            // }
                            if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                                wpjobportal::$_error_flag_message_for=3;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(3,null,null,1));

                            } elseif (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                                $link = WPJOBPORTALincluder::getJSModel('common')->jsMakeRedirectURL('resume', $layout, 1);
                                $linktext = esc_html(__('Login','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=1;
                                wpjobportal::$_error_flag_message_register_for=1; // register as jobseeker
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(1 , $link , $linktext,1));

                            } elseif (!WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser()) {
                                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'wpjobportallt'=>'newinwpjobportal', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                $linktext = esc_html(__('Select role','wp-job-portal'));
                                wpjobportal::$_error_flag_message_for=9;
                                throw new Exception(WPJOBPORTALLayout::setMessageFor(9 , $link , $linktext,1));

                            }
                            if(isset($link) && isset($linktext)){
                                wpjobportal::$_error_flag_message_for_link = $link;
                                wpjobportal::$_error_flag_message_for_link_text = $linktext;
                            }
                        }
                    } catch (Exception $ex) {
                        wpjobportal::$_error_flag = true;
                        wpjobportal::$_error_flag_message = $ex->getMessage();
                    }
                    break;
                case 'admin_formresume':
                    $resumeid = WPJOBPORTALrequest::getVar('resumeid');
                    WPJOBPORTALincluder::getJSModel('resume')->getResumebyId($resumeid);
                    break;
                case 'admin_formresume':
                    wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(3);
                    break;
                case 'admin_formresumeuserfield':
                    $ff = WPJOBPORTALrequest::getVar('ff');
                    if ($ff == "")
                        $ff = get_option('wpjobportalformresumeuserfield_ff');
                    else
                        update_option( 'wpjobportalformresumeuserfield_ff',$ff);
                    $result = WPJOBPORTALincluder::getJSModel('resume')->getResumeUserFields($ff);
                    break;
                case 'admin_resumequeue':
                    WPJOBPORTALincluder::getJSModel('resume')->getAllUnapprovedEmpApps();
                    break;
                case 'admin_resumes':
                    if(wpjobportal::$_common->wpjp_isadmin()){
                        WPJOBPORTALincluder::getJSModel('resume')->getAllEmpApps();
                    }
                    break;
                default:
                    return;
            }
            if (WPJOBPORTALincluder::getObjectClass('user')->isemployer() || (('resumes' == $layout)  || ('resumebycategory' == $layout)) ) {
                if ($empflag == 0) {
                    WPJOBPORTALLayout::setMessageFor(5);
                    wpjobportal::$_error_flag = true;
                }
            }
            if(!isset($module)){
                $module = (wpjobportal::$_common->wpjp_isadmin()) ? 'page' : 'wpjobportalme';
                $module = WPJOBPORTALrequest::getVar($module, null, 'resume');
                $module = wpjobportalphplib::wpJP_str_replace('wpjobportal_', '', $module);
                if(is_numeric($module)){
                    $module = WPJOBPORTALrequest::getVar('wpjobportalme', null, 'resume');
                }
            }
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

    function approveQueueResume() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin()) return false;
        $id = WPJOBPORTALrequest::getVar('id');
        $result = WPJOBPORTALincluder::getJSModel('resume')->approveQueueResumeModel($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue"));
        wp_redirect($url);
        die();
    }

    function approveQueueFeatureResume() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin()) return false;
        $id = WPJOBPORTALrequest::getVar('id');
        $result = WPJOBPORTALincluder::getJSModel('resume')->approveQueueFeatureResumeModel($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue"));
        wp_redirect($url);
        die();
    }

    function rejectQueueResume() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin()) return false;
        $id = WPJOBPORTALrequest::getVar('id');
        $result = WPJOBPORTALincluder::getJSModel('resume')->rejectQueueResumeModel($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue"));
        wp_redirect($url);
        die();
    }

    function rejectQueueFeatureResume() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin()) return false;
        $id = WPJOBPORTALrequest::getVar('id');
        $result = WPJOBPORTALincluder::getJSModel('resume')->rejectQueueFeatureResumeModel($id);
        $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue"));
        wp_redirect($url);
        die();
    }


    function approveQueueAllResumes() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin()) return false;
        $id = WPJOBPORTALrequest::getVar('id');
        $alltype = WPJOBPORTALrequest::getVar('objid');
        $result = WPJOBPORTALincluder::getJSModel('resume')->approveQueueAllResumesModel($id, $alltype);
        $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue"));
        WPJOBPORTALincluder::getJSModel('emailtemplate')->sendMail(3, 2, $id); // 3 for resume,2 for Approve resume
        wp_redirect($url);
        die();
    }

    function rejectQueueAllResumes() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        if(!wpjobportal::$_common->wpjp_isadmin()) return false;
        $id = WPJOBPORTALrequest::getVar('id');
        $alltype = WPJOBPORTALrequest::getVar('objid');
        $result = WPJOBPORTALincluder::getJSModel('resume')->rejectQueueAllResumesModel($id, $alltype);
        $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue"));
        wp_redirect($url);
        die();
    }

    /* STRAT EXPORT RESUMES */

    function resumeenforcedelete() {
        if(!wpjobportal::$_common->wpjp_isadmin()) return false;
        // $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        // if (! wp_verify_nonce( $nonce, 'delete-resume') ) {
        //      die( 'Security check Failed' );
        // }
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        $resumeid = WPJOBPORTALrequest::getVar('resumeid');
        $callfrom = WPJOBPORTALrequest::getVar('callfrom');
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        $result = WPJOBPORTALincluder::getJSModel('resume')->resumeEnforceDelete($resumeid, $uid);
        $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);

        if ($callfrom == 1) {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumes"));
        } else {
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue"));
        }
        wp_redirect($url);
        die();
    }

    // function empappreject() {
    //     $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
    //     if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
    //          die( 'Security check Failed' );
    //     }
    //     $appid = WPJOBPORTALrequest::getVar('resumeid');
    //     $result = WPJOBPORTALincluder::getJSModel('resume')->empappReject($appid);
    //     $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
    //     WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
    //     wp_redirect(esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue")));
    //     die();
    // }


    function saveresume() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        try{
            //requesting parameters
            $mresume = WPJOBPORTALincluder::getJSModel('resume');
            $data = WPJOBPORTALrequest::get('post');
            if (!isset($data['sec_1']['searchable'])) {
                $data['sec_1']['searchable'] = 0;
            }
            $resumeid = (int) $data['id'];
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();

            $isnew = !$resumeid;
            if(!wpjobportal::$_common->wpjp_isadmin() && !WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                if($isnew && !$mresume->checkAlreadyadd($uid)){
                    throw new Exception(WPJOBPORTAL_SAVE_ERROR, WPJOBPORTAL_RESUME);
                }
            }
            $resumeid = $mresume->storeResume($data);
            if(!$resumeid){
                throw new Exception(WPJOBPORTAL_SAVE_ERROR, WPJOBPORTAL_RESUME);
            }

            $msg = WPJOBPORTALMessages::getMessage(WPJOBPORTAL_SAVED, WPJOBPORTAL_RESUME);
            WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
            $redirecturl = wpjobportal::wpjobportal_redirectUrl('resume.success',isset(wpjobportal::$_data['id']) ? wpjobportal::$_data['id'] : '');

            // visitor add resume redirect configuration not implimented
            if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                $pageid = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('visitor_add_resume_redirect_page');
                if(isset($pageid) && $pageid > 0){ // to hanle redict in case of not set up in configuration
                    $redirecturl = get_the_permalink($pageid);
                }else{
                    $redirecturl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobseeker', 'wpjobportallt'=>'controlpanel',"wpjobportalpageid"=>wpjobportal::wpjobportal_getPageid()));
                }
            }

        }catch(Exception $ex){
            $msg = WPJOBPORTALMessages::getMessage($ex->getMessage(), $ex->getCode());
            WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
            $redirecturl = wpjobportal::wpjobportal_redirectUrl('resume.fail');
        }
        wp_redirect($redirecturl);
        die();
    }

    function removeresume() {
        // $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        // if (! wp_verify_nonce( $nonce, 'delete-resume') ) {
        //      die( 'Security check Failed' );
        // }
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        $data = WPJOBPORTALrequest::get('post');
        $resumeid = WPJOBPORTALrequest::getVar('wpjobportal-cb');
        if (!isset($data['callfrom'])) {
            $data['callfrom'] = $callfrom = WPJOBPORTALrequest::getVar('callfrom');
        }
        $result = WPJOBPORTALincluder::getJSModel('resume')->deleteResume($resumeid);
        $msg = WPJOBPORTALMessages::getMessage($result, 'resume');
        WPJOBPORTALMessages::setLayoutMessage($msg['message'], $msg['status'],$this->_msgkey);
        if (wpjobportal::$_common->wpjp_isadmin()) {
            if ($data['callfrom'] == 1) {
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumes"));
            } elseif ($data['callfrom'] == 2) {
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue"));
            }
        } else {
            if (in_array('multiresume',wpjobportal::$_active_addons)) {
                $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multiresume', 'wpjobportallt'=>'myresumes', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
            } else {
                $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
            }
        }
        wp_redirect($url);
        die();
    }

    function getallresumefiles() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        $resumeid = WPJOBPORTALrequest::getVar('resumeid');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce'.$resumeid) ) {
             die( 'Security check Failed' );
        }
        WPJOBPORTALincluder::getJSModel('resume')->getAllResumeFiles();
    }

    function getresumefiledownloadbyid() {
        $fileid = WPJOBPORTALrequest::getVar('wpjobportalid');
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce'.$fileid) ) {
             die( 'Security check Failed' );
        }
        WPJOBPORTALincluder::getJSModel('resume')->getResumeFileDownloadById($fileid);
    }

    function addviewresumedetail() {
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_resume_nonce') ) {
             die( 'Security check Failed' );
        }
        // $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        // if (! wp_verify_nonce( $nonce, 'resume-view') ) {
        //     die( 'Security check Failed' );
        // }
        $id = WPJOBPORTALrequest::getVar('wpjobportalid');
        $pageid = WPJOBPORTALrequest::getVar('wpjobportal_pageid');
        $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
        WPJOBPORTALincluder::getJSModel('resume')->addViewContactDetail($id, $uid);
        $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume','wpjobportalid'=>$id, 'wpjobportalpageid'=>$pageid));
        wp_redirect($url);
        die();
    }
}

$WPJOBPORTALResumeController = new WPJOBPORTALResumeController();
?>
