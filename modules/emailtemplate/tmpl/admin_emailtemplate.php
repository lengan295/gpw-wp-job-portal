<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
	<div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <?php 
            $msgkey = WPJOBPORTALincluder::getJSModel('emailtemplate')->getMessagekey();
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
                        <li><?php echo esc_html(__('Email Templates','wp-job-portal')); ?></li>
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
                <?php echo esc_html(__('Email Templates', 'wp-job-portal')); ?>
            </h1>
        </div>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <form method="post" class="emailtemplateform" action="<?php echo esc_url_raw(admin_url("?page=wpjobportal_emailtemplate&task=saveemailtemplate")); ?>">
                <div class="wpjobportal-email-menu">
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ew-cm') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ew-cm')); ?>" title="<?php echo esc_html(__('new company', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('New Company', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'd-cm') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=d-cm')); ?>" title="<?php echo esc_html(__('delete company', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Delete Company', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'cm-sts') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=cm-sts')); ?>" title="<?php echo esc_html(__('company status', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Company Status', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ew-ob') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ew-ob')); ?>" title="<?php echo esc_html(__('new job', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('New Job', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <?php if(in_array('visitorcanaddjob', wpjobportal::$_active_addons)){ ?>
                                <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ew-obv') echo 'selected'; ?>">
                                    <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ew-obv')); ?>" title="<?php echo esc_html(__('new visitor job', 'wp-job-portal')); ?>">
                                        <?php echo esc_html(__('New Visitor Job', 'wp-job-portal')); ?>
                                        
                                    </a>
                                </span>
                        <?php } ?>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ob-sts') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ob-sts')); ?>" title="<?php echo esc_html(__('job status', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Job Status', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ob-d') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ob-d')); ?>" title="<?php echo esc_html(__('job delete', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Job Delete', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ew-rm') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ew-rm')); ?>" title="<?php echo esc_html(__('new resume', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('New Resume', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ew-rmv') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ew-rmv')); ?>" title="<?php echo esc_html(__('new visitor resume', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('New Visitor Resume', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'rm-sts') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=rm-sts')); ?>" title="<?php echo esc_html(__('resume status', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Resume Status', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'd-rs') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=d-rs')); ?>" title="<?php echo esc_html(__('delete resume', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Delete Resume', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'em-n') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=em-n')); ?>" title="<?php echo esc_html(__('new employer', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('New Employer', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'obs-n') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=obs-n')); ?>" title="<?php echo esc_html(__('new job seeker', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('New Job Seeker', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ad-jap') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ad-jap')); ?>" title="<?php echo esc_html(__('job apply admin', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Job Apply Admin', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'em-jap') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=em-jap')); ?>" title="<?php echo esc_html(__('job apply employer', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Job Apply Employer', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'js-jap') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=js-jap')); ?>" title="<?php echo esc_html(__('job apply job seeker', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Job Apply Job Seeker', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <?php if(in_array('resumeaction', wpjobportal::$_active_addons)){ ?>
                        <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ap-jap') echo 'selected'; ?>">
                            <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ap-jap')); ?>" title="<?php echo esc_html(__('applied resume status change', 'wp-job-portal')); ?>">
                                <?php echo esc_html(__('Applied Resume Status Change', 'wp-job-portal')); ?>
                                
                            </a>
                        </span>
                    <?php } ?>
                    <?php if(in_array('message', wpjobportal::$_active_addons)){ ?>
                        <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'new-msg') echo 'selected'; ?>">
                            <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=new-msg')); ?>" title="<?php echo esc_html(__('new message alert', 'wp-job-portal')); ?>">
                                <?php echo esc_html(__('New Message Alert', 'wp-job-portal')); ?>

                            </a>
                        </span>
                    <?php } ?>

                     <?php if(in_array('resumeaction', wpjobportal::$_active_addons)){ ?>
                        <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'jb-at') echo 'selected'; ?>">
                            <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=jb-at')); ?>" title="<?php echo esc_html(__('job alert', 'wp-job-portal')); ?>">
                                <?php echo esc_html(__('Job Alert', 'wp-job-portal')); ?>
                                
                            </a>
                        </span>
                    <?php } ?>
                     <?php if(in_array('tellfriend', wpjobportal::$_active_addons)){ ?>
                        <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'jb-to-fri') echo 'selected'; ?>">
                            <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=jb-to-fri')); ?>" title="<?php echo esc_html(__('tell to friend', 'wp-job-portal')); ?>">
                                <?php echo esc_html(__('Tell To Friend', 'wp-job-portal')); ?>
                                
                            </a>
                        </span>
                        <?php } ?>
                   <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ew-pk-ad') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ew-pk-ad')); ?>" title="<?php echo esc_html(__('Purchase Package', 'wp-job-portal')).' '.esc_html(__(' Admin', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Purchase Package', 'wp-job-portal')).' '.esc_html(__('Admin', 'wp-job-portal')); ?>
                            
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'ew-pk') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=ew-pk')); ?>" title="<?php echo esc_html(__('Purchase Package', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Purchase Package', 'wp-job-portal')); ?>
                            
                        </a>
                    </span>
                    <span class="wpjobportal-email-menu-link <?php if (wpjobportal::$_data[1] == 'st-pk') echo 'selected'; ?>">
                        <a class="wpjobportal-email-link" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_emailtemplate&for=st-pk')); ?>" title="<?php echo esc_html(__('Purchase Status', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Purchase Status', 'wp-job-portal')); ?>
                        </a>
                    </span>
                    <?php } ?>
                </div>
                <div class="wpjobportal-email-body">
                    <div class="wpjobportal-email-form-wrapper">
                        <div class="wpjobportal-email-form-title">
                            <?php echo esc_html(__('Subject', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-email-form-field">
                            <?php echo wp_kses(WPJOBPORTALformfield::text('subject', wpjobportal::$_data[0]->subject, array('class' => 'inputbox', 'style' => 'width:100%;')),WPJOBPORTAL_ALLOWED_TAGS) ?>
                        </div>
                    </div>
                    <div class="wpjobportal-email-form-wrapper">
                        <div class="wpjobportal-email-form-title">
                            <?php echo esc_html(__('Body', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-email-form-field">
                            <?php wp_editor(wpjobportal::$_data[0]->body, 'body', array('media_buttons' => false)); ?>
                        </div>
                    </div>
                    <div class="wpjobportal-email-parameters">
                        <div class="wpjobportal-email-parameter-heading"><?php echo esc_html(__('Parameters', 'wp-job-portal')) ?></div>
                        <?php if (wpjobportal::$_data[1] == 'ew-cm') { ?>
                            <span class="wpjobportal-email-paramater">{COMPANY_NAME}:  <?php echo esc_html(__('Company name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{EMPLOYER_NAME}:  <?php echo esc_html(__('Employer name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COMPANY_LINK}:  <?php echo esc_html(__('View company', 'wp-job-portal')); ?></span>
                            
                            <span class="wpjobportal-email-paramater">{COMPANY_STATUS}:  <?php echo esc_html(__('Company status for approve,reject,pending', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'cm-sts') { ?>
                            <span class="wpjobportal-email-paramater">{COMPANY_NAME}:  <?php echo esc_html(__('Company name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{EMPLOYER_NAME}:  <?php echo esc_html(__('Employer Name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COMPANY_LINK}:  <?php echo esc_html(__('View company', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COMPANY_STATUS}:  <?php echo esc_html(__('Company approve or reject', 'wp-job-portal')).'('.esc_html(__('Gold','wp-job-portal')) .','.esc_html(__('Featured','wp-job-portal')) . ')'; ?></span>
                            
                        <?php } elseif (wpjobportal::$_data[1] == 'd-cm') { ?>
                            <span class="wpjobportal-email-paramater">{COMPANY_NAME}:  <?php echo esc_html(__('Company Name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COMPANY_OWNER_NAME}:  <?php echo esc_html(__('Company Owner Name', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'd-rs') { ?>
                            <span class="wpjobportal-email-paramater">{RESUME_TITLE}:  <?php echo esc_html(__('Resume title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'ew-ob') { ?>
                            <span class="wpjobportal-email-paramater">{JOB_TITLE}:  <?php echo esc_html(__('Job title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{EMPLOYER_NAME}:  <?php echo esc_html(__('Employer name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOB_LINK}:  <?php echo esc_html(__('Job link', 'wp-job-portal')); ?></span>
                            
                            <span class="wpjobportal-email-paramater">{COMPANY_NAME}:  <?php echo esc_html(__('Company name', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'ob-sts') { ?>
                            <span class="wpjobportal-email-paramater">{JOB_TITLE}:  <?php echo esc_html(__('Job title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{EMPLOYER_NAME}:  <?php echo esc_html(__('Employer name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOB_LINK}:  <?php echo esc_html(__('Job link', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOB_STATUS}:  <?php echo esc_html(__('Job  approve or reject', 'wp-job-portal')).'('.esc_html(__('Gold','wp-job-portal')) .','.esc_html(__('Featured','wp-job-portal')) . ')'; ?></span>
                            
                        <?php } elseif (wpjobportal::$_data[1] == 'em-n') { ?>
                            <span class="wpjobportal-email-paramater">{USER_ROLE}:  <?php echo esc_html(__('Role for employer', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{USER_NAME}:  <?php echo esc_html(__('Employer name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{CONTROL_PANEL_LINK}:  <?php echo esc_html(__('Employer control panel link', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'obs-n') { ?>
                            <span class="wpjobportal-email-paramater">{USER_ROLE}:  <?php echo esc_html(__('Role for job seeker', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{USER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{CONTROL_PANEL_LINK}:  <?php echo esc_html(__('Job seeker control panel link', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'ew-obv') { ?>
                            <span class="wpjobportal-email-paramater">{JOB_TITLE}:  <?php echo esc_html(__('Job title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{EMPLOYER_NAME}:  <?php echo esc_html(__('Employer name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOB_LINK}:  <?php echo esc_html(__('Job link', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COMPANY_NAME}:  <?php echo esc_html(__('Company name', 'wp-job-portal')); ?></span>    
                        <?php } elseif (wpjobportal::$_data[1] == 'ob-d') { ?>
                            <span class="wpjobportal-email-paramater">{JOB_TITLE}:  <?php echo esc_html(__('Job title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{EMPLOYER_NAME}:  <?php echo esc_html(__('Employer name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COMPANY_NAME}:  <?php echo esc_html(__('Company name', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'em-jap') { ?>
                            <span class="wpjobportal-email-paramater">{JOB_TITLE}:  <?php echo esc_html(__('Job title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{EMPLOYER_NAME}:  <?php echo esc_html(__('Employer name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_TITLE}:  <?php echo esc_html(__('Resume title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_DATA}:  <?php echo esc_html(__('Resume data', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_APPLIED_STATUS}:  <?php echo esc_html(__('Resume curent status', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COVER_LETTER_TITLE}:  <?php echo esc_html(__('Cover letter title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COVER_LETTER_DESCRIPTION}:  <?php echo esc_html(__('Cover letter description', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'js-jap') { ?>
                            <span class="wpjobportal-email-paramater">{JOB_TITLE}:  <?php echo esc_html(__('Job title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COMPANY_NAME}:  <?php echo esc_html(__('Company name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_TITLE}:  <?php echo esc_html(__('Resume title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_APPLIED_STATUS}:  <?php echo esc_html(__('Resume curent status', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'ew-rm') { ?>
                            <span class="wpjobportal-email-paramater">{RESUME_TITLE}:  <?php echo esc_html(__('Resume title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_LINK}:  <?php echo esc_html(__('Resume link', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_STATUS}:  <?php echo esc_html(__('Resume  approve or reject', 'wp-job-portal')).'('.esc_html(__('Gold','wp-job-portal')) .','.esc_html(__('Featured','wp-job-portal')) . ')'; ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'ew-rmv') { ?>
                            <span class="wpjobportal-email-paramater">{RESUME_TITLE}:  <?php echo esc_html(__('Resume title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_LINK}:  <?php echo esc_html(__('Resume link', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_STATUS}:  <?php echo esc_html(__('Resume  approve or reject', 'wp-job-portal')).'('.esc_html(__('Gold','wp-job-portal')) .','.esc_html(__('Featured','wp-job-portal')) . ')'; ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'rm-sts') { ?>
                            <span class="wpjobportal-email-paramater">{RESUME_TITLE}:  <?php echo esc_html(__('Resume title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_LINK}:  <?php echo esc_html(__('Resume link', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_STATUS}:  <?php echo esc_html(__('Resume  approve or reject', 'wp-job-portal')).'('.esc_html(__('Gold','wp-job-portal')) .','.esc_html(__('Featured','wp-job-portal')) . ')'; ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'ew-ms') { ?>
                            <span class="wpjobportal-email-paramater">{RESUME_TITLE}:  <?php echo esc_html(__('Resume title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'ad-jap') { ?>
                            <span class="wpjobportal-email-paramater">{EMPLOYER_NAME}:  <?php echo esc_html(__('Employer name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOB_TITLE}:  <?php echo esc_html(__('Job Title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_LINK}:  <?php echo esc_html(__('Resume link', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_DATA}:  <?php echo esc_html(__('Resume data', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COVER_LETTER_TITLE}:  <?php echo esc_html(__('Cover letter title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{COVER_LETTER_DESCRIPTION}:  <?php echo esc_html(__('Cover letter description', 'wp-job-portal')); ?></span>
                        <?php } elseif (wpjobportal::$_data[1] == 'ap-jap') { ?>
                            <span class="wpjobportal-email-paramater">{JOB_TITLE}:  <?php echo esc_html(__('Job title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{JOBSEEKER_NAME}:  <?php echo esc_html(__('Job seeker name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_STATUS}:  <?php echo esc_html(__('Applied resume status', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{RESUME_LINK}:  <?php echo esc_html(__('Resume link', 'wp-job-portal')); ?></span>
                        <?php }elseif(wpjobportal::$_data[1] == 'new-msg') { ?>
                            <span class="wpjobportal-email-paramater">{RECIPIENT_NAME}:  <?php echo esc_html(__('Recipient Name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{SENDER_NAME}:  <?php echo esc_html(__('Sender Name', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{SENDER_USER_ROLE}:  <?php echo esc_html(__('Sender Role', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{MESSAGE_TEXT}:  <?php echo esc_html(__('Message Text', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{ENTITY_INFO}:  <?php echo esc_html(__('Job/Resume Info', 'wp-job-portal')); ?></span>
                        <?php }elseif(wpjobportal::$_data[1] == 'ew-pk-ad' || wpjobportal::$_data[1] == 'ew-pk' || wpjobportal::$_data[1] == 'st-pk') { ?>
                            <span class="wpjobportal-email-paramater">{USER_NAME}:  <?php echo esc_html(__('User name', 'wp-job-portal')); ?>/<?php echo esc_html(__("Agency name",'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{PACKAGE_TITLE}:  <?php echo esc_html(__('Package title', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{PACKAGE_PRICE}:  <?php echo esc_html(__('Package price', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{PACKAGE_LINK}:  <?php echo esc_html(__('View package', 'wp-job-portal')); ?></span>
                            <span class="wpjobportal-email-paramater">{PUBLISH_STATUS}:  <?php echo esc_html(__('Publish status', 'wp-job-portal')); ?></span>
                            <?php
                        } ?>
                    </div>
                    <div class="wpjobportal-config-btn">
                        <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Email Template', 'wp-job-portal')), array('class' => 'button wpjobportal-config-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>          
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('id', esc_html(wpjobportal::$_data[0]->id)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('created', esc_html(wpjobportal::$_data[0]->created)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('templatefor', esc_html(wpjobportal::$_data[0]->templatefor)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('for', esc_html(wpjobportal::$_data[1])),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'emailtemplate_saveemailtemplate'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_email_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
            </form>
        </div>
    </div>
</div>
