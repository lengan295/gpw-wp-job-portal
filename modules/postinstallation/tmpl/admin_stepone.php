<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
$date_format = array((object) array('id' => 'd-m-Y', 'text' => esc_html(__('DD MM YYYY', 'wp-job-portal'))),
                (object) array('id' => 'm/d/Y', 'text' => esc_html(__('MM DD YYYY', 'wp-job-portal'))),
                (object) array('id' => 'Y-m-d', 'text' => esc_html(__('YYYY MM DD', 'wp-job-portal'))));
$yesno = array((object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal')))
                , (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="wpjobportaladmin-wrapper" class="wpjobportal-post-installation-wrp">
    <!-- content -->
    <div class="wpjobportal-post-installation">
        <div class="wpjobportal-post-menu">
            <div class="wpjobportal-post-installation-logowrp">
                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quickstrt_logo.png" />
            </div>
            <ul class="step-1">
                <li class="zero-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=quickstart")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quick-start.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quick-strt-w.png" />
                            <?php echo esc_html(__('Quick Configuration','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <li class="active">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepone")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings-white.png" />
                            <?php echo esc_html(__('General Settings','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <?php $wpjobportal_multiple_employers =  get_option( "wpjobportal_multiple_employers", 1 );
                if($wpjobportal_multiple_employers == 1){ ?>
                <li class="second-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=steptwo")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/employers.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings-white.png" />
                            <?php echo esc_html(__('Employer Settings','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <?php }?>
                <li class="third-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepthree")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/jobseeker.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/jobseeker-w.png" />
                            <?php echo esc_html(__('Job Seeker Settings','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <li class="fourth-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepfour")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/sample-data.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/sample-data-w.png" />
                            <?php echo esc_html(__('Sample Data','wp-job-portal')); ?>
                        </span>
                    </a>
                </li>
                <li class="setup-complete">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=setupcomplete")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/finshed.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/finshed-w.png" />
                            <?php echo esc_html(__('Setup Complete','wp-job-portal')); ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="wpjobportal-post-data">
            <div class="wpjobportal-post-heading-wrp">
                <div class="wpjobportal-post-heading">
                    <?php echo esc_html(__('General Settings','wp-job-portal'));?>
                </div>
                <div class="wpjobportal-post-head-rightbtns-wrp">
                    <span class="wpjobportal-post-head-pagestep">
                        <?php
                        $wpjobportal_multiple_employers =  get_option( "wpjobportal_multiple_employers", 1 );
                        if($wpjobportal_multiple_employers == 1){
                            echo esc_html(__('Step 2 of 5','wp-job-portal'));
                        }else{
                            echo esc_html(__('Step 2 of 4','wp-job-portal'));
                        }
                        ?>
                    </span>
                    <a class="wpjobportal-post-head-closebtn" href="admin.php?page=wpjobportal"title="<?php echo esc_html(__('Close','wp-job-portal'));?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/close.png" />
                    </a>
                </div>
            </div>
            <form id="wpjobportal-form-ins" class="wpjobportal-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&task=save&action=wpjobportaltask")); ?>">
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Title','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('title',wpjobportal::$_data[0]['title'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Enter the Site Title','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <?php /*<div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('System slug','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('system_slug',wpjobportal::$_data[0]['system_slug'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
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
                */?>
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

                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Default address display style', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php
                        $defaultaddressdisplaytype = array((object) array('id' => 'csc', 'text' => esc_html(__('City','wp-job-portal')).', ' .esc_html(__('State','wp-job-portal')).', ' .esc_html(__('Country', 'wp-job-portal'))), (object) array('id' => 'cs', 'text' => esc_html(__('City','wp-job-portal')).', ' .esc_html(__('State', 'wp-job-portal'))), (object) array('id' => 'cc', 'text' => esc_html(__('City','wp-job-portal')).', ' .esc_html(__('Country', 'wp-job-portal'))), (object) array('id' => 'c', 'text' => esc_html(__('City', 'wp-job-portal'))));
                        echo wp_kses(WPJOBPORTALformfield::select('defaultaddressdisplaytype', $defaultaddressdisplaytype, wpjobportal::$_data[0]['defaultaddressdisplaytype']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Address display style for plugin','wp-job-portal'));?>
                        </div>
                    </div>
                </div>

                <div class="wpjobportal-post-action-btn">
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
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
    jQuery(document).ready(function () {
        jQuery('.inputbox').on('focus', function () {
        jQuery(this)
            .closest('.wpjobportal-post-val')
            .prev('.wpjobportal-post-tit')
            .css('color', '#1572e8');
        });
        jQuery('.inputbox').on('blur', function () {
        jQuery(this)
            .closest('.wpjobportal-post-val')
            .prev('.wpjobportal-post-tit')
            .css('color', '');
        });
    });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>