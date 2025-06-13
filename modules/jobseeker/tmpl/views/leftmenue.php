<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
?>
<?php
foreach ($layout as $key => $value) {
    switch ($value) {
        case 'jsregister':
            if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                $print = wpjobportal_jobseekercheckLinks($value);
                if ($print) {
                    $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'user', 'wpjobportallt'=>'regjobseeker'));
                    $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'register');
                    echo '<div class="wjportal-cp-list">
                            <a class="wjportal-list-anchor" href='.esc_url($lrlink).' title="'. esc_html(__('Register', 'wp-job-portal')) .'">
                                <img src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/registers.png alt="'. esc_html(__('register', 'wp-job-portal')) .'">
                                <span class="wjportal-cp-link-text">'. esc_html(__('Register', 'wp-job-portal')) .'</span>
                            </a>
                    </div>';
                   
                } 
            }else{ 
           }
        break;
        case 'myappliedjobs':
            $print = wpjobportal_jobseekercheckLinks($value);
            if ($print) {
                echo' <div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply', 'wpjobportallt'=>esc_attr($value) ))).' title="'. esc_html(__('my applied jobs', 'wp-job-portal')).'">
                            <img src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/applied-jobs.png alt="'. esc_html(__('my applied jobs', 'wp-job-portal')).'">
                            <span class="wjportal-cp-link-text">'. esc_html(__('My Applied Jobs', 'wp-job-portal')).'</span>
                        </a>
                    </div>';
            }
        break;
        case 'listjobshortlist':
            if(in_array('shortlist',wpjobportal::$_active_addons)){
                do_action('wpjobportal_addons_jobseeker_dashboard_bottom_btn_shortlist',$value);
            }
        break;
        case 'myresumes':
            $print = wpjobportal_jobseekercheckLinks($value);
                if ($print && in_array('multiresume', wpjobportal::$_active_addons)) {
                    do_action('wpjobportal_addons_multiresume_myresume',$print);
                }else{
                    if($print){
                        echo '<div class="wjportal-cp-list">
                                <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes'))).'><img src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/resume.png><span class="wjportal-cp-link-text">'. esc_html(__('My Resumes', 'wp-job-portal')).'</span></a>
                            </div>';
                    }
                }
        break;
        case 'newestjobs':
            $print = wpjobportal_jobseekercheckLinks('listnewestjobs');
            if ($print) {
                echo '<div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'newestjobs'))).' title="'. esc_html(__('newest jobs', 'wp-job-portal')).'">
                            <img src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/add-job.png alt="'. esc_html(__('newest jobs', 'wp-job-portal')).'">
                            <span class="wjportal-cp-link-text">'. esc_html(__('Newest Jobs', 'wp-job-portal')).'</span>
                        </a>
                    </div>';
            }
        break;
        case 'jobsearch':
             $print = wpjobportal_jobseekercheckLinks($value);
            if ($print) {
                echo '<div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href='.esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobsearch', 'wpjobportallt'=>esc_attr($value)))).' title="'. esc_html(__('search job', 'wp-job-portal')).'">
                            <img src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/search.png>
                            <span class="wjportal-cp-link-text">'. esc_html(__('Search Job', 'wp-job-portal')).'</span>
                        </a>
                </div>';
            }   
        break;
        case 'jsmessages':
            $print = wpjobportal_jobseekercheckLinks('jsmessages');
           do_action('wpjobportal_addons_jobseeker_dashboard_bottom_btn',$print);
            break;
        case 'mycoverletter':
            //$print = wpjobportal_jobseekercheckLinks('mycoverletter');
            do_action('wpjobportal_addons_jobseeker_dashboard_side_menue_coverletter');
            break;
        case 'empresume_rss':
            do_action('wpjobportal_addons_jobseeker_dashboard_bottom_btn_rss');
            break;
        case 'invoice':
            do_action('wpjobportal_addons_credit_cp_leftmenue_jobseeker');
            break;
        case 'jobcat':
            $print = wpjobportal_jobseekercheckLinks($value);
                if ($print) {
                    echo '<div class="wjportal-cp-list">
                            <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycategories'))).' title="'. esc_html(__('jobs by categories', 'wp-job-portal')).'">
                                <img class="wjportal-img" src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/job-category.png alt="'. esc_html(__('jobs by categories', 'wp-job-portal')).'">
                                <span class="wjportal-cp-link-text">'. esc_html(__('Jobs By Categories', 'wp-job-portal')).'</span>
                            </a>
                    </div>';
               }
        break;
        case 'listjobbytype':
            $print = wpjobportal_jobseekercheckLinks($value);
            if ($print) {
            echo '<div class="wjportal-cp-list">
                    <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbytypes'))).' title="'. esc_html(__('jobs by types', 'wp-job-portal')).'">
                    <img class="wjportal-img" src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/job-type.png alt="'. esc_html(__('jobs by types', 'wp-job-portal')).'">
                    <span class="wjportal-cp-link-text">'. esc_html(__('Jobs By Types', 'wp-job-portal')).'</span></a>
                </div>';
            }
        break;
        case 'listallcompanies':
            if(in_array('multicompany', wpjobportal::$_active_addons)){
                $print = wpjobportal_jobseekercheckLinks($value);
                if ($print) {
                echo '<div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'companies'))).' title="'. esc_html(__('Companies', 'wp-job-portal')).'">
                        <img class="wjportal-img" src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/companies.png alt="'. esc_html(__('Companies', 'wp-job-portal')).'">
                        <span class="wjportal-cp-link-text">'. esc_html(__('Companies', 'wp-job-portal')).'</span></a>
                    </div>';
                }
            }
        break;
        case 'jobsbycities':
            $print = wpjobportal_jobseekercheckLinks($value);
            if ($print) {
            echo '<div class="wjportal-cp-list">
                    <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobsbycities'))).' title="'. esc_html(__('jobs by cities', 'wp-job-portal')).'">
                    <img class="wjportal-img" src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/job-city.png alt="'. esc_html(__('jobs by cities', 'wp-job-portal')).'">
                    <span class="wjportal-cp-link-text">'. esc_html(__('Jobs By Cities', 'wp-job-portal')).'</span></a>
                </div>';
            }
        break;

        case 'formresume':
        $count = '';
        if(!empty(wpjobportal::$_data[0]['resume']['info']) && wpjobportal::$_data[0]['resume']['info']!=NULL){
           $resumeid =  wpjobportal::$_data[0]['resume']['info'][0]->resumeid;
           $count =wpjobportal::$_data[0]['resume']['info'][0]->resumeno;
        }   
            if(in_array('multiresume', wpjobportal::$_active_addons)){
                do_action('wpjobportal_addons_multiresume_addresume',$value);
            }else{
                $print = wpjobportal_jobseekercheckLinks($value);
                    if ($print) {
                        if($count>0){
                            echo '<div class="wjportal-cp-list">
                                    <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'addresume','wpjobportalid' => $resumeid))).' title="'. esc_html(__('edit resume', 'wp-job-portal')).'">
                                        <img class="wjportal-img" src='.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/add-resume.png alt="'. esc_html(__('edit resume', 'wp-job-portal')).'">
                                        <span class="wjportal-cp-link-text">'. esc_html(__('Edit Resume', 'wp-job-portal')).'</span>
                                    </a>
                            </div>';
                        }
                        else{
                            $print = wpjobportal_jobseekercheckLinks($value);
                            if ($print) {
                                   echo '<div class="wjportal-cp-list">
                                            <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'addresume'))).' title="'. esc_html(__('add resume', 'wp-job-portal')).'">
                                                <img class="wjportal-img" src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/add-resume.png alt="'. esc_html(__('add resume', 'wp-job-portal')).'">
                                                <span class="wjportal-cp-link-text">'. esc_html(__('Add Resume', 'wp-job-portal')).'</span>
                                            </a>
                                    </div>';
                                }
                           
                        }
                    }
            }
            
        break;
        case 'jobsloginlogout':
            if (wpjobportal_jobseekercheckLinks($value) ) {
                if (WPJOBPORTALincluder::getObjectClass('user')->isguest() && (!isset($_COOKIE['wpjobportal-socialmedia']) && empty($_COOKIE['wpjobportal-socialmedia']))) {
                        $thiscpurl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobseeker', 'wpjobportallt'=>'controlpanel', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        $thiscpurl = wpjobportalphplib::wpJP_safe_encoding($thiscpurl);
                        $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'wpjobportal', 'wpjobportallt'=>'login', 'wpjobportalredirecturl'=>$thiscpurl, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                        $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'login');
                    echo '<div class="wjportal-cp-list">
                            <a class="wjportal-list-anchor" href='.esc_url($lrlink).' title="'.esc_html(__('login', 'wp-job-portal')).'">
                                <img class="wjportal-img" src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/login.png alt="'.esc_html(__('login', 'wp-job-portal')).'">
                                <span class="wjportal-cp-link-text">'.esc_html(__('Login', 'wp-job-portal')).'</span>
                            </a>
                        </div>';
                } else {
                    $logout_url = wp_logout_url(get_permalink());
                    if(isset($_COOKIE['wpjobportal-socialmedia']) && !empty($_COOKIE['wpjobportal-socialmedia'])){
                        $logout_url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'sociallogin', 'task'=>'socialogout', 'action'=>'wpjobportaltask',  'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                    }
                    echo '<div class="wjportal-cp-list">
                            <a class="wjportal-list-anchor" href='. esc_url($logout_url) .' title="'. esc_html(__('logout', 'wp-job-portal')).'">
                                <img class="wjportal-img" src='.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/jobseeker/logout.png alt="'. esc_html(__('logout', 'wp-job-portal')).'">
                                <span class="wjportal-cp-link-text">'. esc_html(__('Logout', 'wp-job-portal')).'</span>
                            </a>
                        </div>';

                }
            }
        break;
        }
}
