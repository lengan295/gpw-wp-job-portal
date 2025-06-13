<?php
    if (!defined('ABSPATH'))
        die('Restricted Access');
    wp_enqueue_script('jquery-ui-tabs');
    $yesno = array((object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal'))), (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));
    $yesnosectino = array((object) array('id' => 1, 'text' => esc_html(__('Only section that have value', 'wp-job-portal'))), (object) array('id' => 0, 'text' => esc_html(__('All sections', 'wp-job-portal'))));
    $showhide = array((object) array('id' => 1, 'text' => esc_html(__('Show', 'wp-job-portal'))), (object) array('id' => 0, 'text' => esc_html(__('Hide', 'wp-job-portal'))));
    $resumealert = array((object) array('id' => '', 'text' => esc_html(__('Select Option', 'wp-job-portal'))), (object) array('id' => 1, 'text' => esc_html(__('All Fields', 'wp-job-portal'))), (object) array('id' => 2, 'text' => esc_html(__('Only filled fields', 'wp-job-portal'))));
    $msgkey = WPJOBPORTALincluder::getJSModel('configuration')->getMessagekey();
    WPJOBPORTALMessages::getLayoutMessage($msgkey);
    $theme_chk = wpjobportal::$theme_chk ;
    $search_resume = array((object) array('id' => 0, 'text' => esc_html(__('Not allowed', 'wp-job-portal'))), (object) array('id' => 1, 'text' => esc_html(__('Allowed to all (employers, job seekers and visitors)', 'wp-job-portal'))), (object) array('id' => 2, 'text' => esc_html(__('Allowed only to employers', 'wp-job-portal'))));

    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        // for the set register 
            jQuery(document).ready(function () {
                var wpjpconfigid = '". esc_js(wpjobportal::$_data["wpjpconfigid"]) ."';
                if (wpjpconfigid == 'emp_general_setting') {
                    // jQuery('#emp_general_setting').css('display','inline-block');
                    jQuery('#emp_setting').addClass('active');
                }
            });
        //end set register
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>



<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
       <!-- top bar -->
        <div id="wpjobportal-wrapper-top">
            <div id="wpjobportal-wrapper-top-left">
                <div id="wpjobportal-breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo esc_html(__('dashboard','wp-job-portal')); ?>">
                                <?php echo esc_html(__('Dashboard','wp-job-portal')); ?>
                            </a>
                        </li>
                        <li><?php echo esc_html(__('Employer Configurations','wp-job-portal')); ?></li>
                    </ul>
                </div>
            </div>
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-help-btn" class="wpjobportal-help-btn">
                    <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo esc_html(__('help','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/help.png">
                   </a>
                </div>
                <div id="wpjobportal-vers-txt">
                    <?php echo esc_html(__('Version','wp-job-portal')).': '; ?>
                    <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
                </div>
            </div>
        </div>
        <!-- top head -->
        <div id="wpjobportal-head"  class="wpjobportal-config-head">
            <h1 class="wpjobportal-head-text">
                <?php echo esc_html(__('Employer Configurations', 'wp-job-portal')); ?>
            </h1>
        </div>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="wpjobportal-config-main-wrapper">
            <form id="wpjobportal-form" class="wpjobportal-configurations" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_configuration&task=saveconfiguration")); ?>">
                <div class="wpjobportal-configurations-toggle">
                    <img alt="<?php echo esc_html(__('menu','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/admin-left-menu/menu.png" />
                    <span class="jslm_text"><?php echo esc_html(__('Select Configuration', 'wp-job-portal')); ?></span>
                </div>
                <div class="wpjobportal-left-menu wpjobportal-config-left-menu">
                    <?php echo wp_kses(WPJOBPORTALincluder::getJSModel('configuration')->getConfigSideMenu(),WPJOBPORTAL_ALLOWED_TAGS); ?>
                </div>
                <div class="wpjobportal-right-content">
                    <div id="tabs" class="tabs">
                        <!-- EMPLOYER GENERAL SETTINGS -->
                        <div id="emp_general_setting">
                            <ul>
                                <li class="ui-tabs-active">
                                <a href="#emp_generalsetting">
                                    <?php echo esc_html(__('General Settings', 'wp-job-portal')); ?>
                                </a>
                                </li>
                                <?php
                                // resume search is better choice to show hide configuration
                                if(in_array('resumesearch', wpjobportal::$_active_addons)){?>
                                <li>
                                    <a href="#emp_listresume">
                                        <?php echo esc_html(__('Search Resume', 'wp-job-portal')); ?>
                                    </a>
                                </li>
                                <?php } ?>
                                <li>
                                <a href="#email">
                                    <?php echo esc_html(__('Email', 'wp-job-portal')); ?>
                                </a>
                                </li>
                                <li>
                                <a href="#emp_auto_approve">
                                    <?php echo esc_html(__('Auto Approve', 'wp-job-portal')); ?>
                                </a>
                                </li>
                                <li>
                                <a href="#emp_company">
                                    <?php echo esc_html(__('Company', 'wp-job-portal')); ?>
                                </a>
                                </li>
                                <li>
                                <a href="#emp_memberlinks">
                                    <?php echo esc_html(__('Members Links', 'wp-job-portal')); ?>
                                </a>
                                </li>
                            </ul>
                            <div class="tabInner">
                                <!-- GENERAL SETTING -->
                                <div id="emp_generalsetting" class="wpjobportal_gen_body">
                                    <h3 class="wpjobportal-config-heading-main">
                                        <?php echo esc_html(__('General Settings', 'wp-job-portal')); ?>
                                    </h3>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Enable Employer Area', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('disable_employer', $yesno, wpjobportal::$_data[0]['disable_employer']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('If no then front end employer area is not accessable', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Allow user to register as employer', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('showemployerlink', $yesno, wpjobportal::$_data[0]['showemployerlink']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('effects on user registration', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Employer can view job seeker area', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('employerview_js_controlpanel', $yesno, wpjobportal::$_data[0]['employerview_js_controlpanel']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <?php if(in_array('featuredcompany', wpjobportal::$_active_addons)){ ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Enable','wp-job-portal')) .' '. esc_html(__('featured','wp-job-portal')) .' '. esc_html(__('company', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('system_have_featured_company', $yesno, wpjobportal::$_data[0]['system_have_featured_company']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Featured companies are allowed in plugin', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if(in_array('featuredjob', wpjobportal::$_active_addons)){?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Enable','wp-job-portal')) .' '. esc_html(__('featured','wp-job-portal')) .' '. esc_html(__('job', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('system_have_featured_job', $yesno, wpjobportal::$_data[0]['system_have_featured_job']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Featured jobs are allowed in plugin', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Company logo maximum size', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::text('company_logofilezize', wpjobportal::$_data[0]['company_logofilezize'], array('class' => 'inputbox not-full-width', 'data-validation' => 'number')),WPJOBPORTAL_ALLOWED_TAGS); ?>   KB
                                        </div>
                                    </div>
                                    <?php if(in_array('credits', wpjobportal::$_active_addons)){?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Show resume contact detail', 'wp-job-portal')).' ( '.esc_html(__('effect on credits system', 'wp-job-portal')).' )'; ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('resume_contact_detail', $yesno, wpjobportal::$_data[0]['resume_contact_detail']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('If no then credits will be taken to view contact detail', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- custome -->
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Show count in resume categories', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('categories_numberofresumes', $yesno, wpjobportal::$_data[0]['categories_numberofresumes']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- SEARCH RESUME -->
                                <?php if(in_array('resumesearch', wpjobportal::$_active_addons)){ // search resume addon is better choice for show/hide these configurations
                                    ?>
                                    <div id="emp_listresume" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Search Resume', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Allow search resume', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('allow_search_resume', $search_resume, wpjobportal::$_data[0]['allow_search_resume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Who can search resume.', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Allow save search', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('search_resume_showsave', $yesno, wpjobportal::$_data[0]['search_resume_showsave']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('User can save search criteria', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!-- EMAILS -->
                                <div id="email" class="wpjobportal_gen_body">
                                    <h3 class="wpjobportal-config-heading-main">
                                        <?php echo esc_html(__('Email Alert To Employer On Resume Apply', 'wp-job-portal')); ?>
                                    </h3>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('What to include in email', 'wp-job-portal')); ?>

                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('show_only_section_that_have_value', $yesnosectino, wpjobportal::$_data[0]['show_only_section_that_have_value']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('All sections are included in employer email content or only sections that have value','wp-job-portal')) .'.'.esc_html(__('This option is only valid if employer selected send resume data in email settings while posting job', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('What to include in email', 'wp-job-portal')); ?>

                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('employer_resume_alert_fields', $resumealert, wpjobportal::$_data[0]['employer_resume_alert_fields']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('All fields are included in employer email content or only filled fields','wp-job-portal')) .'.'.esc_html(__('This option is only valid if employer selected send resume data in email settings while posting job', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- AUTO APPROVE -->
                                <div id="emp_auto_approve" class="wpjobportal_gen_body">
                                    <h3 class="wpjobportal-config-heading-main">
                                        <?php echo esc_html(__('Auto Approve', 'wp-job-portal')); ?>
                                    </h3>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Company','wp-job-portal')) .' '. esc_html(__('auto approve', 'wp-job-portal')); ?>

                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('companyautoapprove', $yesno, wpjobportal::$_data[0]['companyautoapprove']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <?php if(in_array('featuredcompany', wpjobportal::$_active_addons)){ ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Featured','wp-job-portal')) .' '. esc_html(__('company','wp-job-portal')) .' '. esc_html(__('auto approve', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('featuredcompany_autoapprove', $yesno, wpjobportal::$_data[0]['featuredcompany_autoapprove']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Job','wp-job-portal')) .' '. esc_html(__('auto approve', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('jobautoapprove', $yesno, wpjobportal::$_data[0]['jobautoapprove']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <?php if(in_array('featuredjob', wpjobportal::$_active_addons)){?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Featured','wp-job-portal')) .' '. esc_html(__('job','wp-job-portal')) .' '. esc_html(__('auto approve', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('featuredjob_autoapprove', $yesno, wpjobportal::$_data[0]['featuredjob_autoapprove']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if(in_array('departments', wpjobportal::$_active_addons)){ ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Department','wp-job-portal')) .' '. esc_html(__('auto approve', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('department_auto_approve', $yesno, wpjobportal::$_data[0]['department_auto_approve']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if(in_array('folder', wpjobportal::$_active_addons)){ ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Folder','wp-job-portal')) .' '. esc_html(__('auto approve', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('folder_auto_approve', $yesno, wpjobportal::$_data[0]['folder_auto_approve']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!-- COMPANY SETTINGS -->
                                <div id="emp_company" class="wpjobportal_gen_body">
                                    <h3 class="wpjobportal-config-heading-main">
                                        <?php echo esc_html(__('Company Settings', 'wp-job-portal')); ?>
                                    </h3>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Company','wp-job-portal')) .' '. esc_html(__('Name', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('comp_name', $showhide, wpjobportal::$_data[0]['comp_name']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('Effects on jobs listing and view company page', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Company','wp-job-portal')) .' '. esc_html(__('Email address', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('comp_email_address', $showhide, wpjobportal::$_data[0]['comp_email_address']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('Effects on view company page', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('City', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('comp_city', $showhide, wpjobportal::$_data[0]['comp_city']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('Effects on company listing and view company page', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('View Company Jobs', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('comp_viewalljobs', $showhide, wpjobportal::$_data[0]['comp_viewalljobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('Effects on company listing and view company page', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Company URL', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('comp_show_url', $showhide, wpjobportal::$_data[0]['comp_show_url']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('Effects on view company page', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Description', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('comp_description', $showhide, wpjobportal::$_data[0]['comp_description']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            <div class="wpjobportal-config-description">
                                                <?php echo esc_html(__('Effects on view company page', 'wp-job-portal')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- MEMBERS LINKS -->
                                <div id="emp_memberlinks" class="wpjobportal_gen_body">
                                    <?php if($theme_chk == 0){ ?>
                                    <?php } else { ?>
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Employer Dashboard', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Stats Graph', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('temp_employer_dashboard_stats_graph', $showhide, wpjobportal::$_data[0]['temp_employer_dashboard_stats_graph']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Useful Links','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('temp_employer_dashboard_useful_links', $showhide, wpjobportal::$_data[0]['temp_employer_dashboard_useful_links']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Applied Resume', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('temp_employer_dashboard_applied_resume', $showhide, wpjobportal::$_data[0]['temp_employer_dashboard_applied_resume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Saved Search', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('temp_employer_dashboard_saved_search', $showhide, wpjobportal::$_data[0]['temp_employer_dashboard_saved_search']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>

                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Invoice', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('temp_employer_dashboard_purchase_history', $showhide, wpjobportal::$_data[0]['temp_employer_dashboard_purchase_history']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Newest Resume', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('temp_employer_dashboard_newest_resume', $showhide, wpjobportal::$_data[0]['temp_employer_dashboard_newest_resume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <h3 class="wpjobportal-config-heading-main">
                                        <?php echo esc_html(__('Employer Control Panel Links', 'wp-job-portal')); ?>
                                    </h3>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Profile Section', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('employer_profile_section', $showhide,wpjobportal::$_data[0]['employer_profile_section']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Employer Stat Boxes', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('employerstatboxes', $showhide,wpjobportal::$_data[0]['employerstatboxes']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>

                                    <?php if($theme_chk == 0){ ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Jobs Graph', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('jobs_graph', $showhide, wpjobportal::$_data[0]['jobs_graph']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php /*
                                    if(in_array('message',wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('User Messages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('em_cpmessage', $showhide, wpjobportal::$_data[0]['em_cpmessage']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php }  */?>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Recent Resumes Box', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('employerresumebox', $showhide,wpjobportal::$_data[0]['employerresumebox']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('My Companies', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('mycompanies', $showhide, wpjobportal::$_data[0]['mycompanies']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('formcompany', $showhide, wpjobportal::$_data[0]['formcompany']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('My Jobs', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('myjobs', $showhide, wpjobportal::$_data[0]['myjobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('formjob', $showhide, wpjobportal::$_data[0]['formjob']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    <?php if(in_array('resumesearch', wpjobportal::$_active_addons)){ ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Resume Search', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('resumesearch', $showhide, wpjobportal::$_data[0]['resumesearch']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Resume By Categories', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('resumebycategory', $showhide,wpjobportal::$_data[0]['resumebycategory']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                       <?php if(in_array('resumesearch', wpjobportal::$_active_addons)){ ?>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Saved Searches', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('my_resumesearches', $showhide, wpjobportal::$_data[0]['my_resumesearches']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                        <?php } ?>
                                    <?php /*
                                    ...this configuration is extra...
                                    <div class="wpjobportal-config-row">
                                        <div class="wpjobportal-config-title">
                                            <?php echo esc_html(__('Register', 'wp-job-portal')); ?>
                                        </div>
                                        <div class="wpjobportal-config-value">
                                            <?php echo wp_kses(WPJOBPORTALformfield::select('empregister', $showhide, wpjobportal::$_data[0]['empregister']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                        </div>
                                    </div>
                                    */ ?>
                                    <?php if(in_array('message', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Messages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('empmessages', $showhide, wpjobportal::$_data[0]['empmessages']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                                             <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Invoice', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('emppurchasehistory', $showhide, wpjobportal::$_data[0]['emppurchasehistory']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Subscriptions', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('empratelist', $showhide, wpjobportal::$_data[0]['empratelist']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Packages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('empcreditlog', $showhide, wpjobportal::$_data[0]['empcreditlog']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Packages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('empcredits', $showhide, wpjobportal::$_data[0]['empcredits']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>

                                   <?php  } ?>
                                     <?php if(in_array('departments', wpjobportal::$_active_addons)){ ?>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('My Departments', 'wp-job-portal')); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('mydepartment', $showhide, wpjobportal::$_data[0]['mydepartment']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Department', 'wp-job-portal')); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('formdepartment', $showhide, wpjobportal::$_data[0]['formdepartment']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                    <?php } ?>
                                    <?php if(in_array('folder', wpjobportal::$_active_addons)){ ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('My Folders', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('myfolders', $showhide, wpjobportal::$_data[0]['myfolders']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Folder', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('newfolders', $showhide, wpjobportal::$_data[0]['newfolders']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                       <?php if(in_array('rssfeedback', wpjobportal::$_active_addons)){ ?>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Resume RSS', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('empresume_rss', $showhide, wpjobportal::$_data[0]['empresume_rss']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('isgeneralbuttonsubmit', 0),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportallt', 'configurationsemployer'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'configuration_saveconfiguration'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_configuration_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <div class="wpjobportal-config-btn">
                    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Configuration', 'wp-job-portal')), array('class' => 'button wpjobportal-config-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
        <?php
            $inline_js_script = "
                jQuery(document).ready(function () {
                    // jQuery('#tabs').tabs();
                });
            ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        ?>

    </div>
</div>
