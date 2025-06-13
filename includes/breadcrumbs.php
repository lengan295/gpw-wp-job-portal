<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALbreadcrumbs {

    static function getBreadcrumbs() {
        $cur_location = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('cur_location');
        if ($cur_location != 1)
            return false;
        if (!is_admin()) {
            $cur_user =  wpjobportalincluder::getObjectClass('user');
            if($cur_user->isjobseeker()){
                $url = "jobseeker";
            }elseif ($cur_user->isemployer()) {
                $url = "employer";
            }elseif($cur_user->isguest()){
                $url = 'jobseeker';
            }else{
                $url = "employer";
            }
            $editid = WPJOBPORTALrequest::getVar('wpjobportalid');
            $isnew = ($editid == null) ? true : false;
            $module = WPJOBPORTALrequest::getVar('wpjobportalme');
            $layout = WPJOBPORTALrequest::getVar('wpjobportallt');
            $array[] = array('link' => get_the_permalink(), 'text' => esc_html(__('Control Panel', 'wp-job-portal')));
            $staticUrl = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$url, 'wpjobportallt'=>'controlpanel')), 'text' => esc_html(__('Dashboard', 'wp-job-portal')));
            if ($layout == 'printresume' || $layout == 'pdf')
                return false; // b/c we have print and pdf layouts
            if ($module != null) {
                switch ($module) {
                    case 'company':
                    case 'multicompany':
                       // Add default module link
                        if(in_array('multicompany', wpjobportal::$_active_addons)){
                                $mod = "multicompany";

                            }else{
                                $mod = "company";
                            }
                        switch ($layout) {
                            case 'addcompany':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=> $mod, 'wpjobportallt'=>'mycompanies')), 'text' => esc_html(__('My Companies', 'wp-job-portal')));

                                $text = ($isnew) ? esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')) : esc_html(__('Edit','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal'));
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'addcompany')), 'text' => $text);
                                break;
                            case 'mycompanies':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'mycompanies')), 'text' => esc_html(__('My Companies', 'wp-job-portal')));
                                break;
                            case 'companies':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'companies')), 'text' => esc_html(__('Companies', 'wp-job-portal')));
                                break;
                            case 'featuredcompanies':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'featuredcompanies')), 'text' => esc_html(__('Featured Companies', 'wp-job-portal')));
                                break;
                            case 'viewcompany':
                                $array[] = $staticUrl;
                                if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=> $mod, 'wpjobportallt'=>'mycompanies')), 'text' => esc_html(__('My Companies', 'wp-job-portal')));
                                }
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=> $mod, 'wpjobportallt'=>'viewcompany')), 'text' => esc_html(__('Company Information', 'wp-job-portal')));
                                break;
                        }
                        break;
                    case 'departments':
                    case 'departments':
                        // Add default module link
                        switch ($layout) {
                            case 'adddepartment':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'departments', 'wpjobportallt'=>'mydepartments')), 'text' => esc_html(__('My Departments', 'wp-job-portal')));
                                $text = ($isnew) ? esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Department', 'wp-job-portal')) : esc_html(__('Edit','wp-job-portal')) .' '. esc_html(__('Department', 'wp-job-portal'));
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'departments', 'wpjobportallt'=>'adddepartment')), 'text' => $text);
                                break;
                            case 'mydepartments':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'departments', 'wpjobportallt'=>'mydepartments')), 'text' => esc_html(__('My Departments', 'wp-job-portal')));
                                break;
                            case 'viewdepartment':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'departments', 'wpjobportallt'=>'mydepartments')), 'text' => esc_html(__('My Departments', 'wp-job-portal')));
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'departments', 'wpjobportallt'=>'viewdepartment')), 'text' => esc_html(__('View Department', 'wp-job-portal')));
					 break;
                        }
                        break;
                    case 'coverletter':
                        // Add default module link
                        switch ($layout) {
                            case 'addcoverletter':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'coverletter', 'wpjobportallt'=>'mycoverletters')), 'text' => esc_html(__('My Cover Letters', 'wp-job-portal')));
                                $text = ($isnew) ? esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Cover Letter', 'wp-job-portal')) : esc_html(__('Edit','wp-job-portal')) .' '. esc_html(__('Cover Letter', 'wp-job-portal'));
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'coverletter', 'wpjobportallt'=>'addcoverletter')), 'text' => $text);
                                break;
                            case 'mycoverletters':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'coverletter', 'wpjobportallt'=>'mycoverletters')), 'text' => esc_html(__('My Cover Letters', 'wp-job-portal')));
                                break;
                            case 'viewcoverletter':
                                $array[] = $staticUrl;
                                if(WPJOBPORTALincluder::getObjectClass('user')->isjobseeker())
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'coverletter', 'wpjobportallt'=>'mycoverletters')), 'text' => esc_html(__('My Cover Letters', 'wp-job-portal')));

                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'coverletter', 'wpjobportallt'=>'viewcoverletter')), 'text' => esc_html(__('View Cover Letter', 'wp-job-portal')));
                           break;
                        }
                        break;
                    case 'job':
                        // Add default module link
                        switch ($layout) {
                            case 'addjob':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')), 'text' => esc_html(__('My Jobs', 'wp-job-portal')));
                                $text = ($isnew) ? esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')) : esc_html(__('Edit','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal'));
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'addjob')), 'text' => $text);
                                break;
                            case 'myjobs':
                                $array[] = $staticUrl;
                                /*if(!WPJOBPORTALincluder::getObjectClass('user')->isguest()){*/
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')), 'text' => esc_html(__('My Jobs', 'wp-job-portal')));
                                /*}*/
                                break;
                            case 'viewjob':
                                $array[] = $staticUrl;
                                if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')), 'text' => esc_html(__('My Jobs', 'wp-job-portal')));
                                }
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'viewjob')), 'text' => esc_html(__('View Job', 'wp-job-portal')));
                                break;
                            case 'jobsbycategories':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycategories')), 'text' => esc_html(__('Jobs By Categories', 'wp-job-portal')));
                                break;
                            case 'jobsbytypes':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycategories')), 'text' => esc_html(__('Jobs By Types', 'wp-job-portal')));
                                break;
                            case 'jobsbycities':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycities')), 'text' => esc_html(__('Jobs By Cities', 'wp-job-portal')));
                                break;
                            case 'jobs':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycategories')), 'text' => esc_html(__('Jobs', 'wp-job-portal')));
                                break;
                            case 'newestjobs':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycategories')), 'text' => esc_html(__('Newest Jobs', 'wp-job-portal')));
                                break;
                            case 'featuredjobs':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'featuredjobs')), 'text' => esc_html(__('Featured Jobs', 'wp-job-portal')));
                                break;
                        }
                            break;
                        case 'shortlist':
                            switch ($layout) {
                                case 'shortlistedjobs':
                                    $array[] = $staticUrl;
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'shortlistedjobs')), 'text' => esc_html(__('Short Listed Jobs', 'wp-job-portal')));
                                    break;
                            }
                        break;
                        case 'visitorcanaddjob':
                            switch ($layout) {
                                case 'visitoraddjob':
                                $array[] = $staticUrl;
                                $text = ($isnew) ? esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')) : esc_html(__('Edit','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal'));
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'addjob')), 'text' => $text);
                                break;

                            }
                            break;
                        case 'message':
                        // Add default module link
                        switch ($layout) {
                            case 'employermessages':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'message', 'wpjobportallt'=>'employermessages')), 'text' => esc_html(__('Messages', 'wp-job-portal')));
                                break;
                            case 'jobseekermessages':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'message', 'wpjobportallt'=>'jobseekermessages')), 'text' => esc_html(__('Job Seeker Messages', 'wp-job-portal')));
                                break;
                            case 'jobmessages':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'message', 'wpjobportallt'=>'employermessages')), 'text' => esc_html(__('Messages', 'wp-job-portal')));
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'message', 'wpjobportallt'=>'jobmessages')), 'text' => esc_html(__('Job Messages', 'wp-job-portal')));
                                break;
                            case 'sendmessage':
                                $array[] = $staticUrl;
                                if (wpjobportalincluder::getObjectClass('user')->isemployer()) {
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'message', 'wpjobportallt'=>'employermessages')), 'text' => esc_html(__('Messages', 'wp-job-portal')));
                                } else {
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'message', 'wpjobportallt'=>'jobseekermessages')), 'text' => esc_html(__('Messages', 'wp-job-portal')));
                                }
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'message', 'wpjobportallt'=>'sendmessage')), 'text' => esc_html(__('Send Message', 'wp-job-portal')));
                                break;
                        }
                        break;
                    case 'resumesearch':
                        // Add default module link
                        switch ($layout) {
                            case 'resumesearch':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resumesearch', 'wpjobportallt'=>'resumesearch')), 'text' => esc_html(__('Resume Search', 'wp-job-portal')));
                                break;
                            case 'resumesavesearch':
                                $array[] = $staticUrl;
                                $array[] = array('link' => get_the_permalink(), 'text' => esc_html(__('Saved Searches', 'wp-job-portal')));
                                break;
                            case 'resumes':
                                $array[] = $staticUrl;
                                $array[] = array('link' => get_the_permalink(), 'text' => esc_html(__('Resume List', 'wp-job-portal')));
                                break;
                        }
                        break;
                case 'purchasehistory':
                        // Add default module link
                switch ($layout) {
                        case 'employerpurchasehistory':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchasehistory', 'wpjobportallt'=>'employerpurchasehistory')), 'text' => esc_html(__('Purchase History', 'wp-job-portal')));
                            break;
                        case 'jobseekerpurchasehistory':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchasehistory', 'wpjobportallt'=>'jobseekerpurchasehistory')), 'text' => esc_html(__('Purchase History', 'wp-job-portal')));
                            break;
                        case 'mysubscriptions':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchasehistory', 'wpjobportallt'=>'mysubscriptions')), 'text' => esc_html(__('My Subscription', 'wp-job-portal')));
                            break;
                        case 'purchasehistory':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'purchasehistory', 'wpjobportallt'=>'purchasehistory')), 'text' => esc_html(__('My  Packages', 'wp-job-portal')));
                            break;
                        case 'paydepartment':
                            $array[] = $staticUrl;
                           $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'departments', 'wpjobportallt'=>'mydepartments')), 'text' => "My Department");
                                   $array[] = array('text'=>'Select Payment');


                            break;
                        case 'payjobapply':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply', 'wpjobportallt'=>'myappliedjobs')), 'text' => "My Applied Jobs");
                                   $array[] = array('text'=>'Select Payment');
                            break;
                        case 'paycompany':
                        case 'payfeaturedcompany':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'mycompanies')), 'text' => "My Company");
                                   $array[] = array('text'=>'Select Payment');
                             break;

                        case 'payjob':
                        case 'payfeaturedjob':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')), 'text' => "My Job");
                                   $array[] = array('text'=>'Select Payment');
                             break;
                         case 'payresumesearch':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resumesearch', 'wpjobportallt'=>'resumesavesearch')), 'text' => "My Resume Search");
                                   $array[] = array('text'=>'Select Payment');
                            break;
                        case 'payresume':
                        case 'payfeaturedresume':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multiresume', 'wpjobportallt'=>'myresumes')), 'text' => "My Resume ");
                                   $array[] = array('text'=>'Select Payment');
                            break;
                    }
                break;
                case 'package':
                    switch ($layout) {
                        case 'packages':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'package', 'wpjobportallt'=>'packages')), 'text' => esc_html(__('Package', 'wp-job-portal')));
                            break;
                    }

                    break;
                case 'invoice':
                    switch ($layout) {
                        case 'myinvoices':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'invoice', 'wpjobportallt'=>'myinvoices')), 'text' => esc_html(__('My Invoices', 'wp-job-portal')));
                            break;
                    }

                    break;
                case 'folder':
                    // Add default module link
                    switch ($layout) {
                        case 'addfolder':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'folder', 'wpjobportallt'=>'myfolders')), 'text' => esc_html(__('My Folders', 'wp-job-portal')));
                            $text = ($isnew) ? esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Folder', 'wp-job-portal')) : esc_html(__('Edit','wp-job-portal')) .' '. esc_html(__('Folder', 'wp-job-portal'));
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'folder', 'wpjobportallt'=>'addfolder')), 'text' => $text);
                            break;
                        case 'myfolders':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'folder', 'wpjobportallt'=>'myfolders')), 'text' => esc_html(__('My Folders', 'wp-job-portal')));
                            break;
                        case 'viewfolder':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'folder', 'wpjobportallt'=>'myfolders')), 'text' => esc_html(__('My Folders', 'wp-job-portal')));
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'folder', 'wpjobportallt'=>'viewfolder')), 'text' => esc_html(__('View Folder', 'wp-job-portal')));
                            break;
                    }
                    break;
                case 'folderresume':
                    // Add default module link
                    switch ($layout) {
                        case 'folderresume':
                            $array[] = $staticUrl;
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'folder', 'wpjobportallt'=>'myfolders')), 'text' => esc_html(__('My Folders', 'wp-job-portal')));
                            $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'folderresume', 'wpjobportallt'=>'folderresume')), 'text' => esc_html(__('Folder Resumes', 'wp-job-portal')));
                            break;
                    }
                    break;
                    case 'resume':
                    case 'multiresume':
                        if(in_array('multiresume', wpjobportal::$_active_addons)){
                            $modresume = "multiresume";
                        }else{
                            $modresume = "resume";
                        }
                        // Add default module link
                        switch ($layout) {
                            case 'addresume':
                                $text = ($isnew) ? esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Resume', 'wp-job-portal')) : esc_html(__('Edit','wp-job-portal')) .' '. esc_html(__('Resume', 'wp-job-portal'));
                                    $array[] = $staticUrl;
                                if (!WPJOBPORTALincluder::getObjectClass('user')->isguest() && in_array('multiresume', wpjobportal::$_active_addons)) {
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes')), 'text' => esc_html(__('My Resumes', 'wp-job-portal')));
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'addresume')), 'text' => $text );
                                } else {
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'addresume')), 'text' => $text );
                                }
                                break;
                            case 'myresumes':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=> $modresume, 'wpjobportallt'=>'myresumes')), 'text' => esc_html(__('My Resumes', 'wp-job-portal')));
                                break;
                            case 'resumes':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumebycategory')), 'text' => esc_html(__('Resumes', 'wp-job-portal')));
                                break;
                            case 'featuredresumes':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'featuredresumes')), 'text' => esc_html(__('Featured Resumes', 'wp-job-portal')));
                                break;
                            case 'resumebycategory':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumebycategory')), 'text' => esc_html(__('Resume By Categories', 'wp-job-portal')));
                                break;
                            case 'viewresume':
                                $array[] = $staticUrl;
                                if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
                                    $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=> $modresume, 'wpjobportallt'=>'myresumes')), 'text' => esc_html(__('My Resumes', 'wp-job-portal')));
                                }
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=> $modresume, 'wpjobportallt'=>'viewresume')), 'text' => esc_html(__('View Resume', 'wp-job-portal')));
                                break;
                        }
                        break;
                    case 'jobapply':
                        // Add default module link
                        switch ($layout) {
                            case 'myappliedjobs':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply', 'wpjobportallt'=>'myappliedjobs')), 'text' => esc_html(__('My Applied Jobs', 'wp-job-portal')));
                                break;
                            case 'jobappliedresume':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')), 'text' => esc_html(__('My Jobs', 'wp-job-portal')));
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply', 'wpjobportallt'=>'jobappliedresume')), 'text' => esc_html(__('Job Applied Resume', 'wp-job-portal')));
                                break;
                        }
                        break;
                        case 'jobalert':
                        // Add default module link
                        switch ($layout) {
                            case 'jobalert':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobalert', 'wpjobportallt'=>'jobalert')), 'text' => esc_html(__('Job Alert', 'wp-job-portal')));
                                break;
                        }
                        break;
                    case 'jobsearch':
                        // Add default module link
                        switch ($layout) {
                            case 'jobsearch':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobsearch', 'wpjobportallt'=>'jobsearch')), 'text' => esc_html(__('Job Search', 'wp-job-portal')));
                                break;
                            case 'jobsavesearch':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobsearch', 'wpjobportallt'=>'jobsavesearch')), 'text' => esc_html(__('Saved Searches', 'wp-job-portal')));
                                break;
                        }
                        break;
                    case 'jobseeker':
                        // Add default module link
                        switch ($layout) {
                            case 'controlpanel':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobseeker', 'wpjobportallt'=>'controlpanel')), 'text' => esc_html(__('Control Panel', 'wp-job-portal')));
                                break;
                        }
                        break;
                    case 'employer':
                        // Add default module link
                        switch ($layout) {
                            case 'controlpanel':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'employer', 'wpjobportallt'=>'controlpanel')), 'text' => esc_html(__('Control Panel', 'wp-job-portal')));
                                break;
                        }
                        break;
                    case 'wpjobportal':
                        // Add default module link
                        switch ($layout) {
                            case 'login':
                                $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'wpjobportal', 'wpjobportallt'=>'login'));
                                $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'login');
                                $array[] = array('link' => $lrlink, 'text' => esc_html(__('Log In', 'wp-job-portal')));
                                break;
                        }
                        break;
                    case 'user':
                        // Add default module link
                        switch ($layout) {
                            case 'regemployer':
                                $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'user', 'wpjobportallt'=>'userregister'));
                                $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'register');
                                $array[] = $staticUrl;
                                $array[] = array('link' => $lrlink, 'text' => esc_html(__('Register', 'wp-job-portal')));
                                break;
                            case 'regjobseeker':
                                $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'user', 'wpjobportallt'=>'userregister'));
                                $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'register');
                                $array[] = $staticUrl;
                                $array[] = array('link' => $lrlink, 'text' => esc_html(__('Register', 'wp-job-portal')));
                                break;
                            case 'formprofile':
                                $array[] = $staticUrl;
                                $array[] = array('link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'user', 'wpjobportallt'=>'userregister')), 'text' => esc_html(__('Edit Profile', 'wp-job-portal')));
                                break;
                        }
                        break;
                }
            }
        }

        if (isset($array)) {
            $count = count($array);
            $i = 0;
            echo '<div class="wjportal-breadcrumbs-wrp">';
            foreach ($array AS $obj) {
                if ($i == 0) {
                   // echo '<div class="wjportal-breadcrumbs-home"><a href="' . $obj['link'] . '"></a></div>';
                } else {
                    if ($i == ($count - 1)) {
                        echo '<div class="wjportal-breadcrumbs-links wjportal-breadcrumbs-lastlink">' . esc_html(wpjobportal::wpjobportal_getVariableValue($obj['text'])) . '</div>';
                    } else {
                        echo '<div class="wjportal-breadcrumbs-links wjportal-breadcrumbs-firstlinks"><a class="wjportal-breadcrumbs-link" href="' . esc_url($obj['link']) . '">' . esc_html(wpjobportal::wpjobportal_getVariableValue($obj['text'])) . '</a></div>';
                    }
                }
                $i++;
            }
            echo '</div>';
        }
    }

}

$WPJOBPORTALbreadcrumbs = new WPJOBPORTALbreadcrumbs;
?>
