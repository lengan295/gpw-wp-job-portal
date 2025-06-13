<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$role = wpjobportal::$_data[0];
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
	<!-- left menu -->
	<div id="wpjobportaladmin-leftmenu">
		<?php WPJOBPORTALincluder::getTemplate('templates/admin/leftmenue',array('module' => 'user')); ?>
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
                        <li><?php echo esc_html(__('Change Role','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'user' ,'layouts' => 'userform')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <span class="wpjobportal-page-description" ><?php echo esc_html(__("Changing a user's role will not remove its entites from the system !","wp-job-portal")); ?> </span>
		    <form id="wpjobportal-form" class="wpjobportal-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=wpjobportal_user&task=saveuserrole"),'wpjobportal_user_nonce')); ?>">
		       <?php WPJOBPORTALincluder::getTemplate('user/views/admin/form-field',array('role' => $role)); ?>
		    </form>
	    </div>
	</div>
</div>
