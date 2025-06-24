<?php
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="wpjobportaladmin-wrapper" class="wpjobportal-post-installation-wrp">
    <!-- content -->
    <div class="wpjobportal-post-installation">
        <div class="wpjobportal-post-menu">
            <div class="wpjobportal-post-installation-logowrp">
                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quickstrt_logo.png" />
            </div>
            <ul class="step-1">
                <li class="active">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=quickstart")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quick-start.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quick-strt-w.png" />
                            <?php echo esc_html(__('Quick Configuration','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
                <li class="first-part">
                    <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepone")); ?>" class="tab_icon">
                        <span class="wpjobportal-post-installation-lftimages-wrp">
                            <img class="wpjobportal-post-installation-lfticon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings.png" />
                            <img class="wpjobportal-post-installation-white-icon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/general-settings-white.png.png" />
                            <?php echo esc_html(__('General Settings','wp-job-portal')); ?>
                        </span>
                        <img class="wpjobportal-post-installation-white-arrowicon" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/arrow.png" />
                    </a>
                </li>
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
                    <?php echo esc_html(__('Select Plugin Setting for WP Job Portal','wp-job-portal'));?>
                </div>
                <div class="wpjobportal-post-head-rightbtns-wrp">
                    <span class="wpjobportal-post-head-pagestep"><?php echo esc_html(__('Step 1 of 5','wp-job-portal'));?></span>
                    <a class="wpjobportal-post-head-closebtn" href="admin.php?page=wpjobportal"title="<?php echo esc_html(__('Close','wp-job-portal'));?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/close.png" />
                    </a>
                </div>
            </div>
            <form id="wpjobportal-form-ins" class="wpjobportal-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&task=save&action=wpjobportaltask")); ?>">
                <div class="wpjobportal-post-data-row wpjobportal-post-data-quickstart-row">
                    <div class="wpjobportal-post-data-subtitle">
                        <?php echo esc_html(__('Configure WP Job Portal As','wp-job-portal'));?>
                    </div>
                    <div class="wpjobportal-post-employer-mode-wrap">
                        <div class="wpjobportal-post-employer-mode-single wpj-is-selected wpjobportal-pst-first">
                            <div class="wpjobportal-post-employer-mode-single-img-wrap">
                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/postinstallation/job-board-icon.png'; ?>"/>
                            </div>
                            <div class="wpjobportal-post-card-mainwrp">
                                <div class="wpjobportal-post-card-leftclm">
                                    <div class="wpjobportal-post-employer-mode-single-title">
                                        <?php echo esc_html(__('Job Board','wp-job-portal'));?>
                                    </div>
                                    <div class="wpjobportal-post-employer-mode-single-desc">
                                        <?php echo esc_html(__('Allow employers and job Seekers to register on the site.','wp-job-portal'));?>
                                    </div>
                                </div>
                                <div class="wpjobportal-post-employer-mode-single-btn">
                                    <button type="button" id="wpjobportal-post-employer-mode-job-board-btn" class="wpjobportal-post-employer-mode-selection-btn active">
                                       <div class="wpjobportal-post-employer-mode-selection-circle"></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="wpjobportal-post-employer-mode-single wpjobportal-pst-second">
                            <div class="wpjobportal-post-employer-mode-single-img-wrap">
                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/postinstallation/single-company.png'; ?>"/>
                            </div>
                            <div class="wpjobportal-post-card-mainwrp">
                                <div class="wpjobportal-post-card-leftclm">
                                    <div class="wpjobportal-post-employer-mode-single-title">
                                        <?php echo esc_html(__('Single Company','wp-job-portal'));?>
                                    </div>
                                    <div class="wpjobportal-post-employer-mode-single-desc">
                                            <?php echo esc_html(__('Only job seekers can register. Jobs and companies are managed by the admin.','wp-job-portal'));?>
                                    </div>
                                </div>
                                <div class="wpjobportal-post-employer-mode-single-btn">
                                    <button type="button" id="wpjobportal-post-employer-mode-single-company-btn" class="wpjobportal-post-employer-mode-selection-btn">
                                        <div class="wpjobportal-post-employer-mode-selection-circle"></div>
                                    </button>
                                </div>
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
                        <?php echo esc_html(__('Next Setup','wp-job-portal')); ?>
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
            // quick start toogle
            jQuery(document).ready(function () {
                jQuery('.wpjobportal-post-employer-mode-selection-btn').on('click', function () {
                  if (jQuery(this).hasClass('active')) {
                    // Remove active and wpj-is-selected from clicked
                    jQuery(this).removeClass('active');
                    jQuery(this).parent().parent().parent().removeClass('wpj-is-selected');
              
                    // Find the other button and add active + wpj-is-selected
                    jQuery('.wpjobportal-post-employer-mode-selection-btn').not(this).each(function () {
                      jQuery(this).addClass('active');
                      jQuery(this).parent().parent().parent().addClass('wpj-is-selected');
                    });
                  } else {
                    // Standard toggle behavior
                    jQuery('.wpjobportal-post-employer-mode-selection-btn').removeClass('active');
                    jQuery('.wpjobportal-post-employer-mode-selection-btn').each(function () {
                      jQuery(this).parent().parent().parent().removeClass('wpj-is-selected');
                    });
                    jQuery(this).addClass('active');
                    jQuery(this).parent().parent().parent().addClass('wpj-is-selected');
                  }
                });
              });
            ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );


?>
