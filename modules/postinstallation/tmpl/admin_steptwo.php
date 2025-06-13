<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}
$yesno = array((object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal')))
                    , (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));
wp_enqueue_script('wpjobportal-commonjs', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/radio.js');
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
            <?php echo esc_html(__('Employer Configurations', 'wp-job-portal')); ?>
        </h1>
    </div>
    <!-- content -->
    <div class="wpjobportal-post-installation">
        <div class="wpjobportal-post-menu">
            <ul class="step-2">
                <li class="zero-part">
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
                <li class="second-part active">
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
                <?php echo esc_html(__('Employer Settings','wp-job-portal'));?>
            </div>
            <form id="wpjobportal-form-ins" class="wpjobportal-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&task=save&action=wpjobportaltask")); ?>">
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Enable Employer Area','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('disable_employer', $yesno,wpjobportal::$_data[0]['disable_employer'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('If no then front end employer area is not accessable','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Allow user register as employer','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('showemployerlink', $yesno,wpjobportal::$_data[0]['showemployerlink'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Effects on user registration','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Employer default role','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('employer_defaultgroup', $userroles,wpjobportal::$_data[0]['employer_defaultgroup'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('This role will auto assign to new employer','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Employer can view job seeker area','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('employerview_js_controlpanel', $yesno,wpjobportal::$_data[0]['employerview_js_controlpanel'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Company auto approve','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('companyautoapprove', $yesno,wpjobportal::$_data[0]['companyautoapprove'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Job auto approve','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('jobautoapprove', $yesno,wpjobportal::$_data[0]['jobautoapprove'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <div class="wpjobportal-post-action-btn">
                    <a class="back-step wpjobportal-post-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_postinstallation&wpjobportallt=stepone')); ?>" title="<?php echo esc_html(__('back','wp-job-portal')); ?>">
                        <?php echo esc_html(__('Back','wp-job-portal')); ?>
                    </a>
                    <a class="next-step wpjobportal-post-act-btn" href="javascript:void();"  onclick="document.getElementById('wpjobportal-form-ins').submit();" title="<?php echo esc_html(__('next','wp-job-portal')); ?>">
                        <?php echo esc_html(__('Next','wp-job-portal')); ?>
                    </a>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'postinstallation_save'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('step', 2),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_postinstallation_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
            </form>


        </div>
    </div>
</div>
