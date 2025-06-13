<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

$layout = WPJOBPORTALrequest::getVar('wpjobportallt');
if ($layout == 'printresume' || $layout == 'pdf')
    return false; // b/c we have print and pdf layouts
$module = WPJOBPORTALrequest::getVar('wpjobportalme');
if(
    ($module == 'company' && $layout == 'addcompany') ||
    ($module == 'company' && $layout == 'mycompanies') ||
    ($module == 'credits' && $layout == 'employercredits') ||
    ($module == 'creditslog' && $layout == 'employercreditslog') ||
    ($module == 'credits' && $layout == 'ratelistemployer') ||
    ($module == 'departments' && $layout == 'adddepartment') ||
    ($module == 'departments' && $layout == 'mydepartments') ||
    ($module == 'departments' && $layout == 'viewdepartment') ||
    ($module == 'folder' && $layout == 'addfolder') ||
    ($module == 'folder' && $layout == 'myfolders') ||
    ($module == 'folder' && $layout == 'viewfolder') ||
    ($module == 'folderresume' && $layout == 'folderresume') ||
    ($module == 'job' && $layout == 'addjob') ||
    ($module == 'job' && $layout == 'myjobs') ||
    ($module == 'jobapply' && $layout == 'jobappliedresume') ||
    ($module == 'employer' && $layout == 'controlpanel') ||
    ($module == 'employer' && $layout == 'mystats') ||
    ($module == 'message' && $layout == 'employermessages') ||
    ($module == 'message' && $layout == 'jobmessages') ||
    ($module == 'purchasehistory' && $layout == 'employerpurchasehistory') ||
    ($module == 'resumesearch' && $layout == 'resumesearch') ||
    ($module == 'resumesearch' && $layout == 'resumesavesearch') ||
    ($module == 'resume' && $layout == 'resumebycategory') ||
    ($module == 'resume' && $layout == 'resumes')
){
    $menu = 'employer';
}elseif(
    ($module == 'company' && $layout == 'companies') ||
    ($module == 'company' && $layout == 'viewcompany') ||
    ($module == 'job' && $layout == 'newestjobs') ||
    ($module == 'job' && $layout == 'jobs') ||
    ($module == 'job' && $layout == 'shortlistedjobs') ||
    ($module == 'job' && $layout == 'viewjob') ||
    ($module == 'wpjobportal' && $layout == 'login') ||
    ($module == 'resume' && $layout == 'viewresume') ||
    ($module == 'message' && $layout == 'sendmessage')
){
    if(WPJOBPORTALincluder::getObjectClass('user')->isEmployer()){
        $menu = 'employer';
    }else{
        $menu = 'jobseeker';
    }
}elseif(
    ($module == 'coverletter' && $layout == 'addcoverletter') ||
    ($module == 'coverletter' && $layout == 'mycoverletters') ||
    ($module == 'coverletter' && $layout == 'viewcoverletter') ||
    ($module == 'credits' && $layout == 'jobseekercredits') ||
    ($module == 'credits' && $layout == 'ratelistjobseeker') ||
    ($module == 'creditslog' && $layout == 'jobseekercreditslog') ||
    ($module == 'job' && $layout == 'jobsbycategories') ||
    ($module == 'job' && $layout == 'jobsbytypes') ||
    ($module == 'visitorcanaddjob' && $layout == 'visitoraddjob') ||
    ($module == 'jobalert' && $layout == 'jobalert') ||
    ($module == 'jobapply' && $layout == 'myappliedjobs') ||
    ($module == 'jobsearch' && $layout == 'jobsearch') ||
    ($module == 'jobsearch' && $layout == 'jobsavesearch') ||
    ($module == 'jobseeker' && $layout == 'controlpanel') ||
    ($module == 'jobseeker' && $layout == 'mystats') ||
    ($module == 'message' && $layout == 'jobseekermessages') ||
    ($module == 'purchasehistory' && $layout == 'jobseekerpurchasehistory') ||
    ($module == 'resume' && $layout == 'addresume') ||
    ($module == 'resume' && $layout == 'myresumes') ||
    ($module == 'user' && $layout == 'userregister')
){
    $menu = 'jobseeker';

}else{
    $menu = 'jobseeker';
}

$div = '';
$config_array = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('topmenu');
if ($menu == 'employer') {
    if (is_user_logged_in()) { // Login user
        if ($config_array['tmenu_emcontrolpanel'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'employer', 'wpjobportallt'=>'controlpanel')),
                'title' => esc_html(__('Control Panel', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_emnewjob'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'addjob')),
                'title' => esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_emmyjobs'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')),
                'title' => esc_html(__('My Jobs', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_emmycompanies'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'mycompanies')),
                'title' => esc_html(__('My Companies', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_emsearchresume'] == 1) {
            if(in_array('resumesearch',wpjobportal::$_active_addons)){
                $linkarray[] = array(
                    'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resumesearch', 'wpjobportallt'=>'resumesearch')),
                    'title' => esc_html(__('Resume Search', 'wp-job-portal')),
                );
            }
        }
    } else { // user is visitor
        if ($config_array['tmenu_vis_emcontrolpanel'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'employer', 'wpjobportallt'=>'controlpanel')),
                'title' => esc_html(__('Control Panel', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_vis_emnewjob'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'visitorcanaddjob', 'wpjobportallt'=>'visitoraddjob')),
                'title' => esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_vis_emmyjobs'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs')),
                'title' => esc_html(__('My Jobs', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_vis_emmycompanies'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'mycompanies')),
                'title' => esc_html(__('My Companies', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_vis_emsearchresume'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resumesearch', 'wpjobportallt'=>'resumesearch')),
                'title' => esc_html(__('Search Resume', 'wp-job-portal')),
            );
        }
    }
} else {
    if (is_user_logged_in()) {
        if ($config_array['tmenu_jscontrolpanel'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobseeker', 'wpjobportallt'=>'controlpanel')),
                'title' => esc_html(__('Control Panel', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_wpjobportalcategory'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycategories')),
                'title' => esc_html(__('Jobs By Categories', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_jssearchjob'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobsearch', 'wpjobportallt'=>'jobsearch')),
                'title' => esc_html(__('Job Search', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_jsnewestjob'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'newestjobs')),
                'title' => esc_html(__('Newest Jobs', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_jsmyresume'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'addresume')),
                'title' => esc_html(__('My Resumes', 'wp-job-portal')),
            );
        }
    } else { // user is visitor
        if ($config_array['tmenu_vis_jscontrolpanel'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobseeker', 'wpjobportallt'=>'controlpanel')),
                'title' => esc_html(__('Control Panel', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_vis_wpjobportalcategory'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycategories')),
                'title' => esc_html(__('Jobs By Categories', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_vis_jssearchjob'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobsearch', 'wpjobportallt'=>'jobsearch')),
                'title' => esc_html(__('Job Search', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_vis_jsnewestjob'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'newestjobs')),
                'title' => esc_html(__('Newest Jobs', 'wp-job-portal')),
            );
        }
        if ($config_array['tmenu_vis_jsmyresume'] == 1) {
            $linkarray[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes')),
                'title' => esc_html(__('My Resumes', 'wp-job-portal')),
            );
        }
    }
}

if (isset($linkarray)) {
    $div .= '<div id="wpjobportal-header-main-wrapper">';
    foreach ($linkarray AS $link) {
        $div .= '<a class="headerlinks" href="' . $link['link'] . '">' . $link['title'] . '</a>';
    }
    $div .= '</div>';
}
echo wp_kses($div, WPJOBPORTAL_ALLOWED_TAGS);
?>
