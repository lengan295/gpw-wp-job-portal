<?php 
    if (!defined('ABSPATH')) die('Restricted Access');
    if(!WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'user'))){
        return;
    }
?>   
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function () {
            jQuery('span#showhidefilter').click(function (e) {
                e.preventDefault();
                var img2 = '". esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/filter-up.png';
                var img1 = '". esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/filter-down.png';
                if (jQuery('.default-hidden').is(':visible')) {
                    jQuery(this).find('img').attr('src', img1);
                } else {
                    jQuery(this).find('img').attr('src', img2);
                }
                jQuery('.default-hidden').toggle();
                var height = jQuery(this).height();
                var imgheight = jQuery(this).find('img').height();
                var currenttop = (height - imgheight) / 2;
                jQuery(this).find('img').css('top', currenttop);
            });
        });

        function resetFrom() {
            document.getElementById('searchname').value = '';
            document.getElementById('searchusername').value = '';
            document.getElementById('searchcompany').value = '';
            document.getElementById('searchresume').value = '';
            document.getElementById('searchrole').value = '';
            document.getElementById('wpjobportalform').submit();
        }

        function getUserRoleBasedInfo(roleid,uid) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'user', task: 'getUserRoleBasedInfo', roleid: roleid, uid: uid, '_wpnonce':'". esc_attr(wp_create_nonce("wpjobportal_user_nonce"))."'}, function (data) {
                if (data) {
                    var html_value = jQuery.parseJSON(data);
                    jQuery('div.wpjob-portal-role-info-uid-'+uid+' .wpjobportal-user-data-text-role-info').html(html_value).slideDown('slow');
                } else {
                    console.log('not data found against user');
                }
                jQuery('div.wpjob-portal-role-info-uid-'+uid+' .wpjobportal-user-data-text-role-info-btn').hide();
            });
        }

    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
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
                        <li><?php echo esc_html(__('Users','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'user' ,'layouts' => 'users')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- filter form -->
            <form class="wpjobportal-filter-form" name="wpjobportalform" id="wpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_user&wpjobportallt=users")); ?>">
                <?php WPJOBPORTALincluder::getTemplate('user/views/admin/filter'); ?>
            </form>
            <?php
                if (!empty(wpjobportal::$_data[0])) {
                    $wpdir = wp_upload_dir();
                    foreach (wpjobportal::$_data[0] AS $user) {
                        WPJOBPORTALincluder::getTemplate('user/views/admin/main',array('user' => $user));
                    }
                    if (wpjobportal::$_data[1]) {
                        WPJOBPORTALincluder::getTemplate('templates/admin/pagination',array('module' => 'user','pagination' => wpjobportal::$_data[1]));
                    }
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    WPJOBPORTALlayout::getNoRecordFound($msg);
                }
            ?>
        </div>
    </div>
</div>
