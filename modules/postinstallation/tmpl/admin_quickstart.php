<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
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
            <?php echo esc_html(__('Quick Start', 'wp-job-portal')); ?>
        </h1>
    </div>
    <!-- content -->
    <div class="wpjobportal-post-installation">
        <div class="wpjobportal-post-menu">
            <ul class="step-1">
                <li class="active">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=quickstart")); ?>" class="tab_icon">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quick-start.png" />
                        <?php echo esc_html(__('Quick Start','wp-job-portal')); ?>
                    </a>
                </li>
                <li class="first-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepone")); ?>" class="tab_icon">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings.png" />
                        <?php echo esc_html(__('General','wp-job-portal')); ?>
                    </a>
                </li>
                <li class="second-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=steptwo")); ?>" class="tab_icon">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/employers.png" />
                        <?php echo esc_html(__('Employer','wp-job-portal')); ?>
                    </a>
                </li>
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
                <?php echo esc_html(__('Configure WP Job Portal As','wp-job-portal'));?>
            </div>
            <form id="wpjobportal-form-ins" class="wpjobportal-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&task=save&action=wpjobportaltask")); ?>">

                <div class="wpjobportal-post-data-row wpjobportal-post-data-quickstart-row">
                    <div class="wpjobportal-post-employer-mode-wrap">
                        <div class="wpjobportal-post-employer-mode-single wpj-is-selected wpjobportal-pst-first">
                            <div class="wpjobportal-post-employer-mode-single-img-wrap">
                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/postinstallation/job-board-icon.png'; ?>"/>
                            </div>
                            <div class="wpjobportal-post-employer-mode-single-title">
                                <?php echo esc_html(__('Job Board','wp-job-portal'));?>
                            </div>
                            <div class="wpjobportal-post-employer-mode-single-desc">
                                <?php echo esc_html(__('Admins manage companies, jobs, etc., from the back-end, and employers manage companies, jobs, etc., from the front-end. Job seekers apply for jobs from the front-end.','wp-job-portal'));?>
                            </div>
                            <div class="wpjobportal-post-employer-mode-single-btn">
                                <button type="button" id="wpjobportal-post-employer-mode-job-board-btn" class="wpjobportal-post-employer-mode-selection-btn">
                                    <?php echo esc_html(__('Select Job Board','wp-job-portal'));?>
                                </button>
                            </div>
                        </div>
                        <div class="wpjobportal-post-employer-mode-single wpjobportal-pst-second">
                            <div class="wpjobportal-post-employer-mode-single-img-wrap">
                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/postinstallation/single-company.png'; ?>"/>
                            </div>
                            <div class="wpjobportal-post-employer-mode-single-title">
                                <?php echo esc_html(__('Single Company','wp-job-portal'));?>
                            </div>
                            <div class="wpjobportal-post-employer-mode-single-desc">
                                <?php echo esc_html(__('Only administrators manage job postings, companies, etc., from the back-end, and job seekers apply for jobs from the front-end.','wp-job-portal'));?>
                            </div>
                            <div class="wpjobportal-post-employer-mode-single-btn">
                                <button type="button" id="wpjobportal-post-employer-mode-single-company-btn" class="wpjobportal-post-employer-mode-selection-btn">
                                    <?php echo esc_html(__('Select Single Company','wp-job-portal'));?>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="wpjobportal-post-action-btn" style="text-align: center;">
                    <?php /* ?>
                    <a class="back-step wpjobportal-post-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo esc_html(__('back','wp-job-portal')); ?>">
                        <?php echo esc_html(__('Back','wp-job-portal')); ?>
                    </a>
                    <?php */ ?>
                    <a class="next-step wpjobportal-post-act-btn" href="javascript:void();" onclick="document.getElementById('wpjobportal-form-ins').submit();"  title="<?php echo esc_html(__('next','wp-job-portal')); ?>" style="float: none;">
                        <?php echo esc_html(__('Next','wp-job-portal')); ?>
                    </a>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('enable_multiple_employers_mode', 1),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'postinstallation_save'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('step', 0),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_postinstallation_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
            </form>
        </div>
    </div>

</div>

<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "

            jQuery( document ).ready(function() {
                jQuery('#wpjobportal-post-employer-mode-job-board-btn').click(function(){
                    jQuery('.wpjobportal-post-employer-mode-single').removeClass('wpj-is-selected');
                    jQuery(this).closest('.wpjobportal-post-employer-mode-single').addClass('wpj-is-selected');
                    jQuery('input#enable_multiple_employers_mode').val(1);

                });
                jQuery('#wpjobportal-post-employer-mode-single-company-btn').click(function(){
                    jQuery('.wpjobportal-post-employer-mode-single').removeClass('wpj-is-selected');
                    jQuery(this).closest('.wpjobportal-post-employer-mode-single').addClass('wpj-is-selected');
                    jQuery('input#enable_multiple_employers_mode').val(0);
                });

            });

            ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
