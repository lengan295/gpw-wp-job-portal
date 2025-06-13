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
            $msgkey = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getMessagekey();
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
                        <li><?php echo esc_html(__('Email Templates Options','wp-job-portal')); ?></li>
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
        <div id="wpjobportal-head">
            <h1 class="wpjobportal-head-text">
                <?php echo esc_html(__('Email Templates Options', 'wp-job-portal')) ?>
            </h1>
        </div>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <table id="wpjobportal-table" class="wpjobportal-table wpjobportal-templates-status-table">
                <thead>
                    <tr>
                        <th class="wpjobportal-text-left">
                            <?php echo esc_html(__('Title', 'wp-job-portal')); ?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Employer', 'wp-job-portal')); ?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Job Seeker', 'wp-job-portal')); ?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Admin', 'wp-job-portal')); ?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Job Seeker Visitor', 'wp-job-portal')); ?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Employer Visitor', 'wp-job-portal')); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- compnay section -->
                    <tr>
                        <td colspan="6" class="wpjobportal-table-section-header">
                            <?php echo esc_html(__('Company', 'wp-job-portal')); ?>
                        </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['add_new_company']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_company']['employer'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_company']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_company']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td> - </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_company']['admin'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_company']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_company']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td> - </td>
                        <td> - </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['delete_company']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['delete_company']['employer'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['delete_company']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['delete_company']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['company_status']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['company_status']['employer'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['company_status']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['company_status']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                    </tr>
                    <!-- job section -->
                    <tr>
                        <td colspan="6" class="wpjobportal-table-section-header">
                            <?php echo esc_html(__('Job', 'wp-job-portal')); ?>
                        </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['add_new_job']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_job']['employer'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_job']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_job']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td> - </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_job']['admin'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_job']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_job']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_job']['employer_vis'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_job']['tempid'].'&actionfor=5'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_job']['tempid'].'&actionfor=5'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['delete_job']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['delete_job']['employer'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['delete_job']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['delete_job']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['job_status']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['job_status']['employer'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['job_status']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['job_status']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['job_status']['employer_vis'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['job_status']['tempid'].'&actionfor=5'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['job_status']['tempid'].'&actionfor=5'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                    <!-- resume section -->
                    <tr>
                        <td colspan="6" class="wpjobportal-table-section-header">
                            <?php echo esc_html(__('Resume', 'wp-job-portal')); ?>
                        </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['add_new_resume']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>-</td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_resume']['jobseeker'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_resume']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_resume']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_resume']['admin'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_resume']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_resume']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_resume']['jobseeker_vis'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_resume']['tempid'].'&actionfor=4'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_resume']['tempid'].'&actionfor=4'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['resume-delete']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>-</td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['resume-delete']['jobseeker'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['resume-delete']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['resume-delete']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['resume_status']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>-</td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['resume_status']['jobseeker'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['resume_status']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['resume_status']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['resume_status']['jobseeker_vis'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['resume_status']['tempid'].'&actionfor=4'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['resume_status']['tempid'].'&actionfor=4'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                    </tr>
                    <!-- employer section -->
                    <tr>
                        <td colspan="6" class="wpjobportal-table-section-header">
                            <?php echo esc_html(__('Employer', 'wp-job-portal')); ?>
                        </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['add_new_employer']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_employer']['employer'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_employer']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_employer']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_employer']['admin'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_employer']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_employer']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <!-- jobseeker section -->
                    <tr>
                        <td colspan="6" class="wpjobportal-table-section-header">
                            <?php echo esc_html(__('Job seeker', 'wp-job-portal')); ?>
                        </td>
                    </tr>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['add_new_jobseeker']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>-</td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_jobseeker']['jobseeker'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_jobseeker']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_jobseeker']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['add_new_jobseeker']['admin'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_jobseeker']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['add_new_jobseeker']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <!-- miscellaneous section -->
                    <tr>
                        <td colspan="6" class="wpjobportal-table-section-header">
                            <?php echo esc_html(__('Miscellaneous', 'wp-job-portal')); ?>
                        </td>
                    </tr>
                    <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                            <tr>
                                <?php
                                    $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['package_purchase']['tempname']);
                                ?>
                               <td class="wpjobportal-text-left">
                                    <?php echo esc_html($lang); ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package_purchase']['employer'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_purchase']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_purchase']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package_purchase']['jobseeker'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_purchase']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_purchase']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package_purchase']['admin'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_purchase']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_purchase']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <?php
                                    $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['package_status']['tempname']);
                                ?>
                                <td class="wpjobportal-text-left">
                                    <?php echo esc_html($lang); ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package_status']['employer'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_status']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_status']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package_status']['jobseeker'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_status']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_status']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package_status']['admin'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_status']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package_status']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <?php
                                    $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['package-purchase-admin']['tempname']);
                                ?>
                                 <td class="wpjobportal-text-left">
                                    <?php echo esc_html($lang); ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package-purchase-admin']['employer'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package-purchase-admin']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package-purchase-admin']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package-purchase-admin']['jobseeker'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package-purchase-admin']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package-purchase-admin']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if (wpjobportal::$_data[0]['package-purchase-admin']['admin'] == 1) { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package-purchase-admin']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['package-purchase-admin']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                    <?php } ?>
                    <tr>
                        <?php
                            $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['jobapply_jobapply']['tempname']);
                        ?>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($lang); ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['jobapply_jobapply']['employer'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['jobapply_jobapply']['jobseeker'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (wpjobportal::$_data[0]['jobapply_jobapply']['admin'] == 1) { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } else { ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['jobapply_jobapply']['tempid'].'&actionfor=3'),'wpjobportal_emailstatus_nonce')); ?>" title="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>">
                                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('dont send email', 'wp-job-portal')); ?>" />
                                </a>
                            <?php } ?>
                        </td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                <?php if(in_array('resumeaction', wpjobportal::$_active_addons)){ ?>
                        <tr>
                            <?php
                                $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['applied-resume_status']['tempname']);
                            ?>
                            <td class="wpjobportal-text-left"><?php echo esc_html($lang); ?></td>
                            <td>-</td>
                            <td>
                                <?php if (wpjobportal::$_data[0]['applied-resume_status']['jobseeker'] == 1) { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['applied-resume_status']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>">
                                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('Send email', 'wp-job-portal')); ?>" /></a>
                                <?php } else { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['applied-resume_status']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>">
                                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('Dont send email', 'wp-job-portal')); ?>" /></a>
                                <?php } ?>
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                <?php } ?>
                <?php if(in_array('message', wpjobportal::$_active_addons)){ ?>
                        <tr>
                            <?php
                                $lang = WPJOBPORTALincluder::getJSModel('emailtemplatestatus')->getLanguageForEmail(wpjobportal::$_data[0]['new_message']['tempname']);
                            ?>
                            <td class="wpjobportal-text-left"><?php echo esc_html($lang); ?></td>

                            <td>
                                <?php if (wpjobportal::$_data[0]['new_message']['employer'] == 1) { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['new_message']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>">
                                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('Send email', 'wp-job-portal')); ?>" /></a>
                                <?php } else { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['new_message']['tempid'].'&actionfor=1'),'wpjobportal_emailstatus_nonce')); ?>">
                                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('Dont send email', 'wp-job-portal')); ?>" /></a>
                                <?php } ?>
                            </td>

                            <td>
                                <?php if (wpjobportal::$_data[0]['new_message']['jobseeker'] == 1) { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=noSendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['new_message']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>">
                                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('Send email', 'wp-job-portal')); ?>" /></a>
                                <?php } else { ?>
                                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_emailtemplatestatus&task=sendEmail&action=wpjobportaltask&wpjobportalid='.wpjobportal::$_data[0]['new_message']['tempid'].'&actionfor=2'),'wpjobportal_emailstatus_nonce')); ?>">
                                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('Dont send email', 'wp-job-portal')); ?>" /></a>
                                <?php } ?>
                            </td>

                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
