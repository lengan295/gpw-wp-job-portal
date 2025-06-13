<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
?>
<?php
foreach ($layout as $key => $value) {
    switch ($value) {
        case 'mycompanies':
        if(in_array('multicompany', wpjobportal::$_active_addons)){
            do_action('wpjobportal_addons_mystuff_dashboard_employer_upper_mycomp','mycompanies');
        }else{
            $print = wpjobportal_employercheckLinks($value);
            if ($print) {
            echo'<div class="wjportal-cp-list">
                    <a class="wjportal-list-anchor" href='.esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>$value))).' title='.esc_html(__('My companies','wp-job-portal')).'>
                            <img class="js-img" src='. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/employer/companies.png alt='.esc_html(__("My companies",'wp-job-portal')).'>
                            <span class="wjportal-cp-link-text">'. esc_html(__('My Companies', 'wp-job-portal')).'</span>
                    </a>
                </div>';
            }
        }
        break;
        case 'formcompany':
            if(in_array('multicompany', wpjobportal::$_active_addons)){
                $print = wpjobportal_employercheckLinks($value);
                do_action('wpjobportal_addons_mystuff_employer_dashboard_addcomp',$print);
            }else{
                $print = wpjobportal_employercheckLinks($value);
                if ($print) {
                    $company = isset(wpjobportal::$_data[0]['companies']) ? wpjobportal::$_data[0]['companies'] : '';
                    if(isset($company) && !empty($company)){
                        $desc = $company[0]->record > 0 ? 'Edit Company' : 'Add Company';
                        echo '<div class="wjportal-cp-list">
                                <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'addcompany','wpjobportalid'=>$company[0]->id))) .' title="'.esc_html(__('Edit company','wp-job-portal')).'">
                                    <img src='. esc_url(WPJOBPORTAL_PLUGIN_URL) .'includes/images/control_panel/employer/add-company.png>
                                    <span class="wjportal-cp-link-text">'.esc_html(wpjobportal::wpjobportal_getVariableValue($desc)).'</span>
                                </a>
                            </div>';
                    }else{
                        echo '<div class="wjportal-cp-list">
                                <a class="wjportal-list-anchor" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'addcompany'))) .' title="'.esc_html(__('Edit company','wp-job-portal')).'">
                                    <img src='. esc_url(WPJOBPORTAL_PLUGIN_URL) .'includes/images/control_panel/employer/add-company.png>
                                    <span class="wjportal-cp-link-text">'.esc_html(__('Add Company','wp-job-portal')).'</span>
                                </a>
                            </div>';
                    }
                }
            }
            break;
        case 'empmessages':
           do_action('wpjobportal_addons_mystuff_employer_dashboard_msg',$print);
        break;
        case 'myjobs':
            $print = wpjobportal_employercheckLinks('myjobs');
                if ($print) {
                    ?>
                    <div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs'))); ?>" title="<?php echo esc_html(__('My jobs', 'wp-job-portal')); ?>">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/employer/my-job.png" alt="<?php echo esc_html(__('My jobs', 'wp-job-portal')); ?>">
                            <span class="wjportal-cp-link-text"><?php echo esc_html(__('My Jobs', 'wp-job-portal')); ?></span>
                        </a>
                    </div>
                    <?php
                }
        break;
        case 'formjob':
            $print = wpjobportal_employercheckLinks('formjob');
                if ($print) {
                    ?>
                    <div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'addjob'))); ?>" title="<?php echo esc_html(__('Add job', 'wp-job-portal')); ?>">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/employer/add-job.png" alt="<?php echo esc_html(__('Add job', 'wp-job-portal')); ?>">
                            <span class="wjportal-cp-link-text"><?php echo esc_html(__('Add Job', 'wp-job-portal')); ?></span>
                        </a>
                    </div>
                <?php
                }
        break;
        case 'invoice':
            do_action('wpjobportal_addons_credit_cp_leftmenue_employeer');
        break;
        case 'formdepartment':
            do_action('wpjobportal_addons_mystuff_employer_dashboard_side_menue_dept');
        break;
        case 'resumesearch':
            if(in_array('resumesearch', wpjobportal::$_active_addons)){
                $print = wpjobportal_employercheckLinks('resumesearch');
                if ($print) { ?>
                    <div class="wjportal-cp-list">
                        <?php
                            do_action('wpjobportal_addons_mystuff_dashboard_employer_upper',$print);
                        ?>
                    </div>
                <?php
                }
            }
        break;
        case 'empresume_rss':
            $print = wpjobportal_employercheckLinks('empresume_rss');
            if(in_array('rssfeedback', wpjobportal::$_active_addons)){
                do_action('wpjobportal_addons_mystuff_employer_dashboard_side_menue',$print);
            }
        break;
        case 'newfolders':
           do_action('wpjobportal_addons_mystuff_employer_dashboard');
        break;
        case 'resumebycategory':
            $print = wpjobportal_employercheckLinks('resumebycategory');
                if ($print) {
                    ?>
                    <div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumebycategory'))); ?>" title="<?php echo esc_html(__('Resume by categories', 'wp-job-portal')); ?>">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/employer/resume-categories.png">
                            <span class="wjportal-cp-link-text"><?php echo esc_html(__('Resume By Categories', 'wp-job-portal')); ?></span>
                        </a>
                    </div>
                <?php }
        break;
        case 'my_resumesearches':
           do_action('wpjobportal_addons_mystuff_dashboard_employer_search');
        break;
        case 'emploginlogout':
            if (wpjobportal_employercheckLinks('emploginlogout')) {
                if (WPJOBPORTALincluder::getObjectClass('user')->isguest() && (!isset($_SESSION['wpjobportal-socialmedia']) && empty($_SESSION['wpjobportal-socialmedia']))) {
                    ?>
                    <div class="wjportal-cp-list">
                        <?php
                            $thiscpurl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'employer', 'wpjobportallt'=>'controlpanel'));
                            $thiscpurl = wpjobportalphplib::wpJP_safe_encoding($thiscpurl);
                            $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'wpjobportal', 'wpjobportallt'=>'login', 'wpjobportalredirecturl'=>$thiscpurl));
                            $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'login');
                        ?>
                        <a class="wjportal-list-anchor" href="<?php echo esc_url($lrlink);?>" title="<?php echo esc_html(__('Login', 'wp-job-portal')); ?>">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/jobseeker/login.png" alt="<?php echo esc_html(__('Login', 'wp-job-portal')); ?>">
                            <span class="wjportal-cp-link-text"><?php echo esc_html(__('Login', 'wp-job-portal')); ?></span>
                        </a>
                    </div>
                    <?php
                } else {
                    $logout_url = wp_logout_url(get_permalink());
                    if(isset($_COOKIE['wpjobportal-socialmedia']) && !empty($_COOKIE['wpjobportal-socialmedia'])){
                        $logout_url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'sociallogin', 'task'=>'socialogout', 'action'=>'wpjobportaltask',  'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                    }
                    ?>
                    <div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href="<?php echo esc_url($logout_url); ?>" title="<?php echo esc_html(__('Logout', 'wp-job-portal')); ?>">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/employer/logout.png" alt="<?php echo esc_html(__('Logout', 'wp-job-portal')); ?>">
                            <span class="wjportal-cp-link-text"><?php echo esc_html(__('Logout', 'wp-job-portal')); ?></span>
                        </a>
                    </div>
                <?php

            }
        }
        break;
        /*
        case 'emresumebycategory':
           $print = wpjobportal_employercheckLinks('emresumebycategory');
                if ($print) {
                    ?>
                    <div class="wjportal-cp-list">
                        <a class="wjportal-list-anchor" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumebycategory'))); ?>" title="<?php echo esc_html(__('Resumes by categories', 'wp-job-portal')); ?>">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/employer/resume-by-categories.png" alt="<?php echo esc_html(__('Resumes by categories', 'wp-job-portal')); ?>">
                            <span class="wjportal-cp-link-text"><?php echo esc_html(__('Resumes By Categories', 'wp-job-portal')); ?></span>
                        </a>
                    </div>
                <?php }
        break;
        */
        case 'empregister':
            if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                $print = wpjobportal_employercheckLinks('empregister');
                    if ($print) {
                        $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'user', 'wpjobportallt'=>'regemployer'));
                        $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'register');
                        ?>
                        <div class="wjportal-cp-list">
                            <a class="wjportal-list-anchor" href="<?php echo esc_url($lrlink); ?>" title="<?php echo esc_html(__('Register', 'wp-job-portal')); ?>">
                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/jobseeker/registers.png" alt="<?php echo esc_html(__('Register', 'wp-job-portal')); ?>">
                                <span class="wjportal-cp-link-text"><?php echo esc_html(__('Register', 'wp-job-portal')); ?></span>
                            </a>
                        </div>
                        <?php
                    }
            }
        break;
    }
}
