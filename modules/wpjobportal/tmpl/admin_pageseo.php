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
                        <li><?php echo esc_html(__('SEO','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'wpjobportal' , 'layouts' => 'seooptions')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0">
            <?php
            $company_settings = get_option('wpjobportal_company_document_title_settings');
            $job_settings = get_option('wpjobportal_job_document_title_settings');
            $resume_settings = get_option('wpjobportal_resume_document_title_settings');
            ?>
            <form id="wpjobportal-form" class="wpjobportal-configurations" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal&task=savedocumenttitleoptions")); ?>">
                <div class="wpjobportal-seo-option-page-wrap">
                    <h3 class="wpjobportal-config-heading-main">
                        <?php echo esc_html(__('Document Title Settings', 'wp-job-portal')); ?>
                    </h3>
                    <div class="wpjobportal-config-row">
                        <div class="wpjobportal-config-title">
                            <?php echo esc_html(__('Company detail document title', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-config-value">
                            <?php echo wp_kses(WPJOBPORTALformfield::text('wpjobportal_company_document_title_settings', $company_settings, array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <div class="wpjobportal-config-description">
                                <?php echo esc_html(__('Company detail page document title options are Company Name and Company City . eg- [name] [location] [separator] [sitename]', 'wp-job-portal')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wpjobportal-config-row">
                        <div class="wpjobportal-config-title">
                            <?php echo esc_html(__('Job detail document title', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-config-value">
                            <?php echo wp_kses(WPJOBPORTALformfield::text('wpjobportal_job_document_title_settings', $job_settings, array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <div class="wpjobportal-config-description">
                                    <?php echo esc_html(__('Job detail page document title options are Job Title, Company Name, Job Category, Job Type and Job Cities . eg- [title] [companyname] [jobcategory] [jobtype] [location] [separator] [sitename]', 'wp-job-portal')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wpjobportal-config-row">
                        <div class="wpjobportal-config-title">
                            <?php echo esc_html(__('Resume detail document title', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-config-value">
                            <?php echo wp_kses(WPJOBPORTALformfield::text('wpjobportal_resume_document_title_settings', $resume_settings, array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <div class="wpjobportal-config-description">
                                <?php echo esc_html(__('Resume detail document title options are Application Title, Job Category, Job Type and Resume Location. eg- [applicationtitle] [jobcategory] [jobtype] [location] [separator] [sitename]', 'wp-job-portal')); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $job_seo = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('job_seo');
                    $company_seo = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('company_seo');
                    $resume_seo = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('resume_seo');
                    ?>
                    <h3 class="wpjobportal-config-heading-main">
                        <?php echo esc_html(__('URL Settings', 'wp-job-portal')); ?>
                    </h3>
                    <div class="wpjobportal-config-row">
                        <div class="wpjobportal-config-title">
                            <?php echo esc_html(__('Job Detail URL', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-config-value">
                            <?php echo wp_kses(WPJOBPORTALformfield::text('job_seo', $job_seo, array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <div class="wpjobportal-config-description">
                                <?php echo esc_html(__('Job detail URL options are title, company, category, location, jobtype. eg- [title] [company] [category] [jobtype] [location]', 'wp-job-portal')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wpjobportal-config-row">
                        <div class="wpjobportal-config-title">
                            <?php echo esc_html(__('Company Detail URL', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-config-value">
                            <?php echo wp_kses(WPJOBPORTALformfield::text('company_seo', $company_seo, array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <div class="wpjobportal-config-description">
                                <?php echo esc_html(__('Company detail url options are name, location. eg- [name] [location]', 'wp-job-portal')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wpjobportal-config-row">
                        <div class="wpjobportal-config-title">
                            <?php echo esc_html(__('Resume Detail URL', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-config-value">
                            <?php echo wp_kses(WPJOBPORTALformfield::text('resume_seo', $resume_seo, array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <div class="wpjobportal-config-description">
                                <?php echo esc_html(__('Resume detail URL options are title, category, location. eg- [title] [location]', 'wp-job-portal')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportallt', 'configurations'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'configuration_savedocumenttitleoptions'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_document_title_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <div class="wpjobportal-config-btn">
                    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')), array('class' => 'button wpjobportal-config-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
    </div>
</div>