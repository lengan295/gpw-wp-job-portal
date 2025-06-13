<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
$date_format = array((object) array('id' => 'd-m-Y', 'text' => esc_html(__('DD MM YYYY', 'wp-job-portal'))),
                (object) array('id' => 'm/d/Y', 'text' => esc_html(__('MM DD YYYY', 'wp-job-portal'))),
                (object) array('id' => 'Y-m-d', 'text' => esc_html(__('YYYY MM DD', 'wp-job-portal'))));
$yesno = array((object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal')))
                , (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="wpjobportaladmin-wrapper" class="wpjobportal-post-installation-wrp">
    <!-- top bar -->
    <div id="wpjobportal-wrapper-top">
        <div id="wpjobportal-wrapper-top-left">
            <a href="admin.php?page=wpjobportal" class="wpjobportaladmin-anchor">
                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/logo.png'; ?>"/>
            </a>
        </div>
        <div id="wpjobportal-wrapper-top-right">
            <div id="wpjobportal-vers-txt">
                <?php echo esc_html(__('Version','wp-job-portal')).': '; ?>
                <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
            </div>
        </div>
    </div>
    <!-- top head -->
    <div id="wpjobportal-head">
        <h1 class="wpjobportal-head-text">
            <?php echo esc_html(__('General Configurations', 'wp-job-portal')); ?>
        </h1>
    </div>
    <!-- content -->
    <div class="wpjobportal-post-installation">
        <div class="wpjobportal-post-menu">
            <ul class="step-1">
                <li class="zero-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=quickstart")); ?>" class="tab_icon">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quick-start.png" />
                        <?php echo esc_html(__('Quick Start','wp-job-portal')); ?>
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepone")); ?>" class="tab_icon">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings.png" />
                        <?php echo esc_html(__('General','wp-job-portal')); ?>
                    </a>
                </li>
                <?php $wpjobportal_multiple_employers =  get_option( "wpjobportal_multiple_employers", 1 );
                if($wpjobportal_multiple_employers == 1){ ?>
                    <li class="second-part">
                        <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=steptwo")); ?>" class="tab_icon">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/employers.png" />
                            <?php echo esc_html(__('Employer','wp-job-portal')); ?>
                        </a>
                    </li>
                <?php }?>
                <li class="third-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepthree")); ?>" class="tab_icon">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/jobseeker.png" />
                        <?php echo esc_html(__('Job Seeker','wp-job-portal')); ?>
                    </a>
                </li>
                <li class="fourth-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepfour")); ?>" class="tab_icon">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/sample-data.png" />
                        <?php echo esc_html(__('Sample Data','wp-job-portal')); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="wpjobportal-post-data">
            <div class="wpjobportal-post-heading">
                <?php echo esc_html(__('General Settings','wp-job-portal'));?>
            </div>
            <form id="wpjobportal-form-ins" class="wpjobportal-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&task=save&action=wpjobportaltask")); ?>">
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Title','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('title',wpjobportal::$_data[0]['title'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <?php /*<div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('System slug','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('system_slug',wpjobportal::$_data[0]['system_slug'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div> */?>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Default page','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('default_pageid', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(),wpjobportal::$_data[0]['default_pageid'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Select WP Job Portal default page, on action system will redirect on selected page.','wp-job-portal'));?>
                        </div>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('If not select default page, email links and support icon might not work.','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Data directory','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('data_directory',wpjobportal::$_data[0]['data_directory'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('System will upload all user files in this folder','wp-job-portal'));?>
                        </div>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(WPJOBPORTAL_PLUGIN_PATH).esc_html(wpjobportal::$_data[0]['data_directory']);?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Admin email address','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('adminemailaddress',wpjobportal::$_data[0]['adminemailaddress'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Admin will receive email notifications on this address','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('System email address','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('mailfromaddress',wpjobportal::$_data[0]['mailfromaddress'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Email address that will be used to send emails','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Email from name','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('mailfromname',wpjobportal::$_data[0]['mailfromname'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Sender name that will be used in emails','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Show breadcrumbs','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('cur_location', $yesno,wpjobportal::$_data[0]['cur_location'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Show navigation in breadcrumbs','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Default date format','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('date_format', $date_format,wpjobportal::$_data[0]['date_format'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Date format for plugin','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-action-btn">
                    <a class="back-step wpjobportal-post-act-btn" href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=quickstart")); ?>" title="<?php echo esc_html(__('back','wp-job-portal')); ?>">
                        <?php echo esc_html(__('Back','wp-job-portal')); ?>
                    </a>
                    <a class="next-step wpjobportal-post-act-btn" href="javascript:void();" onclick="document.getElementById('wpjobportal-form-ins').submit();"  title="<?php echo esc_html(__('next','wp-job-portal')); ?>">
                        <?php echo esc_html(__('Next','wp-job-portal')); ?>
                    </a>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'postinstallation_save'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('step', 1),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_postinstallation_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
            </form>
        </div>
    </div>

</div>
