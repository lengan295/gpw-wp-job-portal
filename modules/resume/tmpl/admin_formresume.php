<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
    //include_once WPJOBPORTAL_PLUGIN_PATH.'includes/css/style_color.php';
wp_enqueue_style('wpjobportal-color', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/color.css');
wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style.css');
wp_enqueue_style('wpjobportal-style-mobile', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style_mobile.css',array(),'1.1.1','(max-width: 480px)');;
wp_enqueue_style('wpjobportal-jobseeker-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jobseekercp.css');
wp_enqueue_style('wpjobportal-employer-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/employercp.css');
if (is_rtl()) {
    wp_register_style('wpjobportal-style-rtl', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/stylertl.css');
    wp_enqueue_style('wpjobportal-style-rtl');
}
update_option( 'wpjobportalresumeeditadmin', 1 );
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
	   <?php WPJOBPORTALincluder::getTemplate('templates/admin/leftmenue',array('module' => 'resume')); ?>
    </div>
    <div id="wpjobportaladmin-data">
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
                        <li><?php echo esc_html(__('Edit Resume','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'resume' ,'layouts' => 'formresume')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <?php
                require_once(WPJOBPORTAL_PLUGIN_PATH . 'modules/resume/tmpl/addresume.inc.php');
                require_once(WPJOBPORTAL_PLUGIN_PATH . 'modules/resume/tmpl/addresume.php');
            ?>
        </div>
    </div>
</div>
