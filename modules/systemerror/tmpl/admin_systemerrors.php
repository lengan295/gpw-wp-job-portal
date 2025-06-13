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
            $msgkey = WPJOBPORTALincluder::getJSModel('systemerror')->getMessagekey();
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
                        <li><?php echo esc_html(__('Error Log','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'systemerror' , 'layouts' => 'systemerror')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0">
            <?php
                if (!empty(wpjobportal::$_data[0])) {
                    ?>
                    <table id="wpjobportal-table" class="wpjobportal-table">
                        <thead>
                            <tr>
                                <th class="wpjobportal-text-left w70">
                                    <?php echo esc_html(__('Error', 'wp-job-portal')); ?>
                                </th>
                                <th>
                                    <?php echo esc_html(__('View', 'wp-job-portal')); ?>
                                </th>
                                <th>
                                    <?php echo esc_html(__('Date', 'wp-job-portal')); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach (wpjobportal::$_data[0] AS $systemerror) {
                                    $isview = ($systemerror->isview == 1) ? 'close.png' : 'good.png';
                                    ?>
                                    <tr>
                                        <td class="wpjobportal-text-left w70">
                                            <?php echo esc_html($systemerror->error); ?>
                                        </td>
                                        <td>
                                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/<?php echo esc_attr($isview); ?>" />
                                        </td>
                                        <td>
                                            <?php
                                                echo esc_html(date_i18n(wpjobportal::$_configuration['date_format'], strtotime($systemerror->created)));
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if (wpjobportal::$_data[1]) {
                        echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(wpjobportal::$_data[1]) . '</div></div>';
                    }
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    WPJOBPORTALlayout::getNoRecordFound($msg);
                }
            ?>
        </div>
    </div>
</div>
