<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
$yesno = array((object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal')))
                    , (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="wpjobportaladmin-wrapper" class="wpjobportal-post-installation-wrp">
    <!-- content -->
    <div class="wpjobportal-post-installation">
        <div class="wpjobportal-post-menu">
            <div class="wpjobportal-post-installation-logowrp">
                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quickstrt_logo.png" />
            </div>
            <ul class="step-3">
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
                <li class="first-part">
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
                <li class="third-part active">
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
                    <?php echo esc_html(__('Job Seeker Settings','wp-job-portal'));?>
                </div>
                <div class="wpjobportal-post-head-rightbtns-wrp">
                    <span class="wpjobportal-post-head-pagestep">
                        <?php
                        $wpjobportal_multiple_employers =  get_option( "wpjobportal_multiple_employers", 1 );
                        if($wpjobportal_multiple_employers == 1){
                            echo esc_html(__('Step 4 of 5','wp-job-portal'));
                        }else{
                            echo esc_html(__('Step 3 of 4','wp-job-portal'));
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
                        <?php echo esc_html(__('Job Seeker default role','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('jobseeker_defaultgroup', $userroles,wpjobportal::$_data[0]['jobseeker_defaultgroup'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('This role will auto assign to new job seeker','wp-job-portal'));?>
                        </div>
                    </div>
                </div>

                 <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Resume auto approve','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('empautoapprove', $yesno,wpjobportal::$_data[0]['empautoapprove'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>

                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Enable Quick Apply for user', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('quick_apply_for_user', $yesno, wpjobportal::$_data[0]['quick_apply_for_user']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Show the', 'wp-job-portal')).' <b>'. esc_html(__('Quick Apply', 'wp-job-portal')).'</b> '. esc_html(__('form to logged-in users on the job detail page. If this is on,', 'wp-job-portal')).' <b>'.esc_html(__('Apply Now', 'wp-job-portal')).'</b> '.esc_html(__('will be turned off.', 'wp-job-portal')); ?>
                        </div>
                    </div>
                </div>

                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Visitor can apply to job','wp-job-portal'));
                        if(!in_array('visitorapplyjob', wpjobportal::$_active_addons)){ ?>
                            <strong>*</strong>
                        <?php } ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_can_apply_to_job', $yesno,wpjobportal::$_data[0]['visitor_can_apply_to_job'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>

                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Enable Quick Apply for visitor', 'wp-job-portal'));
                        if(!in_array('visitorapplyjob', wpjobportal::$_active_addons)){ ?>
                            <strong>*</strong>
                        <?php } ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('quick_apply_for_visitor', $yesno, wpjobportal::$_data[0]['quick_apply_for_visitor']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Show the', 'wp-job-portal')).' <b>'.esc_html(__('Quick Apply', 'wp-job-portal')).'</b> '.esc_html(__('form to visitor on the job detail page. If this is on,', 'wp-job-portal')).' <b>'.esc_html(__('Apply Now', 'wp-job-portal')).'</b> '.esc_html(__('will be turned off.', 'wp-job-portal')); ?>
                        </div>
                    </div>
                </div>
                <?php
                if(!in_array('visitorapplyjob', wpjobportal::$_active_addons)){ ?>
                    <div class="wpjobportal-post-data-row">
                        <div class="wpjobportal-post-tit">
                            &nbsp;
                        </div>
                        <div class="wpjobportal-post-val">
                            <div class="wpjobportal-post-smpledata-infowrp">
                                <img alt="<?php echo esc_html(__('Infi Icon','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/info-icon.png" />
                                <?php echo esc_html(__('* WP Job Portal Apply As Visitor addon is required for the configuration to work!','wp-job-portal')); ?>
                            </div>
                        </div>
                    </div>
                <?php }?>


                <?php /*
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Mark Job New','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('newdays',wpjobportal::$_data[0]['newdays'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('How many days system show New tag','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                */?>
               <div class="wpjobportal-post-action-btn">
                <?php
                    if($wpjobportal_multiple_employers == 1){
                        $back_url = esc_url_raw(admin_url('admin.php?page=wpjobportal_postinstallation&wpjobportallt=steptwo'));
                    }else{
                        $back_url = esc_url_raw(admin_url('admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepone'));
                    } ?>
                    <a class="next-step wpjobportal-post-act-btn" href="javascript:void();" onclick="document.getElementById('wpjobportal-form-ins').submit();" title="<?php echo esc_html(__('next','wp-job-portal')); ?>">
                        <?php echo esc_html(__('Next','wp-job-portal')); ?>
                    </a>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'postinstallation_save'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('step', 3),WPJOBPORTAL_ALLOWED_TAGS); ?>
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
