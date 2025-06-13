<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
$searchjobtag = array((object) array('id' => 1, 'text' => esc_html(__('Top left', 'wp-job-portal')))
                    , (object) array('id' => 2, 'text' => esc_html(__('Top right', 'wp-job-portal')))
                    , (object) array('id' => 3, 'text' => esc_html(__('Middle left', 'wp-job-portal')))
                    , (object) array('id' => 4, 'text' => esc_html(__('Middle right', 'wp-job-portal')))
                    , (object) array('id' => 5, 'text' => esc_html(__('Bottom left', 'wp-job-portal')))
                    , (object) array('id' => 6, 'text' => esc_html(__('Bottom right', 'wp-job-portal'))));

$yesno = array((object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal')))
                    , (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}

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
            <?php echo esc_html(__('Jobseeker Configurations', 'wp-job-portal')); ?>
        </h1>
    </div>
    <!-- content -->
    <div class="wpjobportal-post-installation">
        <div class="wpjobportal-post-menu">
            <ul class="step-3">
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
                <?php $wpjobportal_multiple_employers =  get_option( "wpjobportal_multiple_employers", 1 );
                if($wpjobportal_multiple_employers == 1){ ?>
                    <li class="second-part">
                        <a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_postinstallation&wpjobportallt=steptwo")); ?>" class="tab_icon">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/employers.png" />
                            <?php echo esc_html(__('Employer','wp-job-portal')); ?>
                        </a>
                    </li>
                <?php } ?>
                <li class="third-part active">
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
                <?php echo esc_html(__('Jobseeker Settings','wp-job-portal'));?>
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
                        <?php echo esc_html(__('Visitor can apply to job','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_can_apply_to_job', $yesno,wpjobportal::$_data[0]['visitor_can_apply_to_job'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <?php /*
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Search icon position','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('searchjobtag', $searchjobtag,wpjobportal::$_data[0]['searchjobtag'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-post-help-text">
                            <?php echo esc_html(__('Position for search icon on jobs listing page','wp-job-portal'));?>
                        </div>
                    </div>
                </div>
                */?>
                <div class="wpjobportal-post-data-row">
                    <div class="wpjobportal-post-tit">
                        <?php echo esc_html(__('Resume auto approve','wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-post-val">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('empautoapprove', $yesno,wpjobportal::$_data[0]['empautoapprove'],'',array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
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
                    <a class="back-step wpjobportal-post-act-btn" href="<?php echo esc_url($back_url); ?>" title="<?php echo esc_html(__('back','wp-job-portal')); ?>">
                        <?php echo esc_html(__('Back','wp-job-portal')); ?>
                    </a>
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
