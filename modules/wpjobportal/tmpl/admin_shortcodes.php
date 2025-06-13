<?php
    if (!defined('ABSPATH'))
        die('Restricted Access');
    wp_enqueue_script('wpjobportal-res-tables', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/responsivetable.js');
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <?php
            $msgkey = WPJOBPORTALincluder::getJSModel('wpjobportal')->getMessagekey();
            WPJOBPORTALMessages::getLayoutMessage($msgkey);
        ?>
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
                        <li><?php echo esc_html(__('Short Codes','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'wpjobportal' , 'layouts' => 'shortcodes')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0">
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Job Seeker Control Panel','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('A shortcode designed for jobseeker, providing an easy-to-use controlpanel to manage applications, resume, and preferences directly from their dashboard.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_jobseeker_controlpanel]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_jobseeker_controlpanel &nbsp;
                            <span class="shortcode-blue-color">hide_profile_section="1"&nbsp;</span>
                            <span class="shortcode-green-color">hide_graph="1"&nbsp;</span>
                            <span class="shortcode-pink-color">hide_job_applies="1"&nbsp;</span>
                            <span class="shortcode-brown-color">hide_newest_jobs="1"&nbsp;</span>
                            <span class="shortcode-blue-color">hide_stat_boxes="1"&nbsp;</span>
                            <span class="shortcode-green-color">hide_invoices="1"&nbsp;</span>
                        ]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Employer Control Panel','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__(' The Employer Control Panel is a streamlined interface that allows employers to manage job postings, track applications, communicate with candidates, and analyze hiring metrics efficiently.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_employer_controlpanel]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_employer_controlpanel&nbsp;
                            <span class="shortcode-blue-color">hide_profile_section="1"&nbsp;</span>
                            <span class="shortcode-green-color">hide_graph="1"&nbsp;</span>
                            <span class="shortcode-pink-color">hide_recent_applications="1"&nbsp;</span>
                            <span class="shortcode-brown-color">hide_stat_boxes="1"&nbsp;</span>
                            <span class="shortcode-blue-color">hide_invoices="1"&nbsp;</span>

                        ]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Job','wp-job-portal')).' '. esc_html(__('Search','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('The job search shortcode enables users to easily find job listings based on various criteria.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_job_search]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_job_search]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Jobs','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('The jobs shortcode is a feature that displays individual job listings on a website.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_job]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_job&nbsp;<span class="shortcode-blue-color">no_of_jobs="5"&nbsp;</span>
                            <span class="shortcode-green-color">hide_filter="1"&nbsp;</span>
                            <span class="shortcode-pink-color">hide_filter_job_title="1"&nbsp;</span>
                            <span class="shortcode-brown-color">hide_filter_job_location="1"&nbsp;</span>
                            <span class="shortcode-blue-color">hide_company_logo="1"&nbsp;</span>
                            <span class="shortcode-green-color">hide_company_name="1"&nbsp;</span>
                            <span class="shortcode-pink-color">companies="1,2,3"&nbsp;</span>
                            <span class="shortcode-brown-color">categories="1,2,3"&nbsp;</span>
                            <span class="shortcode-blue-color">types="1,2,3"&nbsp;</span>
                            <span class="shortcode-green-color">locations="1,2,3"&nbsp;</span>
                            <span class="shortcode-pink-color">ids="1,2,3"&nbsp;</span>
                            <span class="shortcode-brown-color">careerlevels="1,2,3"&nbsp;</span>
                            <span class="shortcode-blue-color">jobstatuses="1,2,3"&nbsp;</span>
                            <span class="shortcode-green-color">tags="tag1,tag2,tag3"&nbsp;</span>
                            <span class="shortcode-pink-color">sorting="title_desc"&nbsp;</span>
                        ]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Featured Jobs','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('The featured job shortcode highlights select job listings on a website, making them stand out to potential applicants.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_featured_jobs]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_featured_jobs]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('featuredjob', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Featured Jobs Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/featured-job/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Job Categories','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('The job categories shortcode displays a dynamic list of available categories, enabling users to easily navigate and find relevant job listings.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_job_categories]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_job_categories]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Job Types','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('The job types shortcode presents a comprehensive list of available job types, allowing users to quickly explore and find suitable job opportunities.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_job_types]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_job_types]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('My Applied Jobs','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('My applied jobs shortcode provides users with a personalized list of their job applications, allowing easy access to track application status and details.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[[wpjobportal_my_appliedjobs]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_my_appliedjobs]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('My Companies','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('My companies shortcode displays a personalized list of companies users are associated with, facilitating easy access to company information and opportunities.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_my_companies]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_my_companies]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('All Companies','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('All companies shortcode offers a curated directory of all companies, allowing users to easily browse and connect with potential employers.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_all_companies]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_all_companies&nbsp;<span class="shortcode-blue-color">hide_company_logo="1"&nbsp;</span>
                            <span class="shortcode-blue-color">hide_company_name="1"&nbsp;</span>
                            <span class="shortcode-green-color">hide_company_location="1"&nbsp;</span>
                            <span class="shortcode-pink-color">locations="1,2,3"&nbsp;</span>
                            <span class="shortcode-brown-color">employers="1,2,3"&nbsp;</span>
                            <span class="shortcode-blue-color">ids="1,2,3"&nbsp;</span>
                            <span class="shortcode-green-color">sorting="name_desc"&nbsp;</span>
                            <span class="shortcode-pink-color">no_of_companies="5"&nbsp;</span>
                        ]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('allcompanies', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal All Companies Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_premiumplugin&wpjobportallt=addonfeatures')); ?>">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Featured Companies','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('The featured companies shortcode showcases prominent companies within the portal, allowing users to explore exciting job opportunities from industry leaders.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_featured_companies]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_featured_companies]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('featuredcompany', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Featured Companies Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/featured-company/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('My Jobs','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('My jobs shortcode allows users to view and manage their saved job listings, making it easy to keep track of potential opportunities.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_my_jobs]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_my_jobs]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('My Resume','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('My resume shortcode allows users to view and manage their uploaded resumes, providing a convenient way to keep their job application materials up to date.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_my_resumes]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_my_resumes]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('All Resumes','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('All resumes shortcode allows job seekers to view and manage their uploaded resumes, ensuring they have easy access to their application materials.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_all_resumes]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_all_resumes&nbsp;<span class="shortcode-blue-color">hide_resume_photo="1"&nbsp;</span>
                            <span class="shortcode-green-color">hide_resume_location="1"&nbsp;</span>
                            <span class="shortcode-pink-color">hide_resume_salary="1"&nbsp;</span>
                            <span class="shortcode-brown-color">categories="1,2,3"&nbsp;</span>
                            <span class="shortcode-blue-color">types="1,2,3"&nbsp;</span>
                            <span class="shortcode-green-color">locations="1,2,3"&nbsp;</span>
                            <span class="shortcode-pink-color">ids="1,2,3"&nbsp;</span>
                            <span class="shortcode-brown-color">tags="tag1,tag2,tag3"&nbsp;</span>
                            <span class="shortcode-blue-color">sorting="posted_desc"&nbsp;</span>
                            <span class="shortcode-green-color">no_of_resumes="5"&nbsp;</span>
                        ]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('allresumes', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal All Resumes Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_premiumplugin&wpjobportallt=addonfeatures')); ?>">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Featured Resumes','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('The featured resumes shortcode showcases a selection of standout resumes, allowing job seekers to stand out to potential employers.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_featured_resumes]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_featured_resumes]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('featureresume', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Featured Resumes Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/featured-resume/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Add','wp-job-portal')).' '. esc_html(__('Company','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Add company shortcode allows employers to effortlessly register their organization, providing essential details to attract talent and post job openings.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_add_company]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_add_company]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Add','wp-job-portal')).' '. esc_html(__('Department','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Add department shortcode enables employers to easily create and manage specific departments within their organization, facilitating better job categorization and organization.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_add_department]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_add_department]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('departments', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Multi Departments Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/multi_departments/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Add','wp-job-portal')).' '. esc_html(__('Job','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Add jobs enables organizations to effortlessly publish job postings, helping them connect with potential applicants and fill vacancies more effectively.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_add_job]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_add_job]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Add','wp-job-portal')).' '. esc_html(__('Resume','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Add resume shortcode simplifies the process for candidates to submit their resumes, helping them showcase their qualifications and experiences to potential employers.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_add_resume]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_add_resume]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Resume','wp-job-portal')).' '. esc_html(__('Search','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Resume search shortcode provides a powerful search tool for employers to explore candidate profiles, making it easier to connect with potential hires.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_resume_search]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_resume_search]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('resumesearch', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Resume Search Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/resume-save-search/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Registration','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Registration shortcode allows individuals to easily create accounts as their selected role','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_registration]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_registration]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Employer Registration','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Employer registration shortcode streamlines the process for organizations to register, ensuring they can efficiently manage job postings and their company profiles.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_employer_registration]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_employer_registration]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Job Seeker Registration','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Job seeker registration shortcode allows individuals to easily create accounts, enabling them to apply for jobs and manage their resumes.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_jobseeker_registration]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_jobseeker_registration]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Login Page','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Login page shortcode offers a user-friendly interface for candidates and employers to securely log in and manage their profiles and postings.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_login_page]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_login_page]</span>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('Search Job','wp-job-portal')).' '. esc_html(__('Widget','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('Search job widget provides an intuitive interface for candidates to filter and browse openings, connecting them with opportunities that align with their skills.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_searchjob]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_searchjob]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('widgets', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Front-end Widgets Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/widgets/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('My Packages','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('My packages offers a personalized dashboard for users to monitor their subscription details, helping them manage job postings and access features effectively.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_mypackages]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_mypackages]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('credits', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Credits Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/credit-system/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('My Subscription','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('My subscription provides users with an overview of their active subscriptions, allowing easy management and tracking of their job posting plans.  ','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_mysubscription]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_mysubscription]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('credits', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Credits Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/credit-system/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('All Packages','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('All packages showcases a comprehensive list of available subscription plans, allowing users to compare features and select the best options for their job posting needs.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo'[wpjobportal_allpackages]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_allpackages]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('credits', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Credits Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/credit-system/" target="_blank">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="wpjobportal-shortcode-wrapper">
                <div class="wpjobportal-shortcode-image"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/shortcode.png" title="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>" alt="<?php echo esc_html(__('Shortcode','wp-job-portal')); ?>"></div>
                <div class="wpjobportal-shortcode-inner-wrapper">
                    <div class="wpjobportal-shortcode-head">
                        <?php echo esc_html(__('My Invoices','wp-job-portal')); ?>
                        <p class="wpjobportal-shortcode-text"><?php echo esc_html(__('My invoices provides a convenient way for users to view and manage their invoices, helping them stay organized and up to date with their payments.','wp-job-portal')); ?></p>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Base Shortcode','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-shortcodetext"><?php echo '[wpjobportal_myinvoices]'; ?></span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <span class="wpjobportal-shortcode-title"><?php echo esc_html(__('Shortcode With All Options','wp-job-portal')); ?></span>
                        <span class="wpjobportal-shortcode-secndshortcodetext">[wpjobportal_myinvoices]</span>
                    </div>
                    <div class="wpjobportal-shortcode-shortcodewrp">
                        <?php
                            $msg_class = "wpjobportal-notice-msg";
                            $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-warning-icon.png";
                            $show_link = 1;
                            if( in_array('credits', wpjobportal::$_active_addons) ){
                                $msg_class = "wpjobportal-info-msg";
                                $msg_image = WPJOBPORTAL_PLUGIN_URL."includes/images/import-city-notice-icon.png";
                                $show_link = 0;
                            }
                         ?>
                        <div class="wpjobportal-shortcode-notice-wrap <?php echo esc_attr($msg_class);?>" >
                            <img src="<?php echo esc_url($msg_image); ?>">
                            <p>
                                <?php echo esc_html(__('This Shortcode requires WP Job Portal Credits Addon','wp-job-portal')); ?>
                                <?php
                                if($show_link == 1){
                                    echo '('; ?>
                                    <a href="https://wpjobportal.com/product/credit-system/">
                                        <?php echo esc_html(__('Get Addon','wp-job-portal'));?>
                                    </a>
                                    <?php echo ')';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
