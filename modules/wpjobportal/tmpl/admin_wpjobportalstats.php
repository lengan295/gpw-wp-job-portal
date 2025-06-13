<?php 
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('wpjobportal-res-tables', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/responsivetable.js');
$data = wpjobportal::$_data[0];
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <?php WPJOBPORTALincluder::getTemplate('templates/admin/leftmenue',array('module'=>'wpjobportal')); ?>
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
                        <li><?php echo esc_html(__('Stats','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'wpjobportal' , 'layouts' => 'stats')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <table id="wpjobportal-table" class="wpjobportal-table">
                <thead>
                    <tr>
                        <th class="wpjobportal-text-left">
                            <?php echo esc_html(__('Title', 'wp-job-portal')); ?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Total', 'wp-job-portal')); ?>
                        </th>
                        <th>
                            <?php echo esc_html(__('Active', 'wp-job-portal')); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html(__('Companies', 'wp-job-portal')); ?>
                        </td>
                        <td>
                            <?php echo esc_html($data['companies']->totalcompanies); ?>   
                        </td>
                        <td>
                            <?php echo esc_html($data['companies']->activecompanies); ?>   
                        </td>
                    </tr>
                    <?php if (in_array('featuredcompany',wpjobportal::$_active_addons) && in_array('credits', wpjobportal::$_active_addons)) { ?>
                        <tr>
                            <td class="wpjobportal-text-left">
                                <?php echo esc_html(__('Featured Companies', 'wp-job-portal')); ?>
                            </td>
                            <td>
                                <?php echo isset($data['featuredcompanies']->totalfeaturedcompanies) ? esc_html($data['featuredcompanies']->totalfeaturedcompanies): '' ; ?>
                            </td>
                            <td>
                                <?php echo isset($data['featuredcompanies']->activefeaturedcompanies) ? esc_html($data['featuredcompanies']->activefeaturedcompanies): '' ; ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html(__('Jobs', 'wp-job-portal')); ?>
                        </td>
                        <td>
                            <?php echo esc_html($data['jobs']->totaljobs); ?>
                        </td>
                        <td>
                            <?php echo esc_html($data['jobs']->activejobs); ?>
                        </td>
                    </tr>
                    <?php if (in_array('featuredjob',wpjobportal::$_active_addons) && in_array('credits', wpjobportal::$_active_addons)) { ?>
                        <tr>
                            <td class="wpjobportal-text-left">
                                <?php echo esc_html(__('Featured Jobs', 'wp-job-portal')); ?>
                            </td>
                            <td>
                                <?php echo esc_html($data['featuredjobs']->totalfeaturedjobs); ?>
                            </td>
                            <td>
                                <?php echo esc_html($data['featuredjobs']->activefeaturedjobs); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html(__('Resume', 'wp-job-portal')); ?>
                        </td>
                        <td>
                            <?php echo esc_html($data['resumes']->totalresumes); ?>
                        </td>
                        <td>
                            <?php echo esc_html($data['resumes']->activeresumes); ?>
                        </td>
                    </tr>
                    <?php if (in_array('featureresume',wpjobportal::$_active_addons) && in_array('credits', wpjobportal::$_active_addons)) { ?>
                        <tr>
                            <td class="wpjobportal-text-left">
                                <?php echo esc_html(__('Featured Resume', 'wp-job-portal')); ?>
                            </td>
                            <td>
                                <?php echo esc_html($data['featuredresumes']->totalfeaturedresumes); ?>
                            </td>
                            <td>
                                <?php echo esc_html($data['featuredresumes']->activefeaturedresumes); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html(__('Employer', 'wp-job-portal')); ?>
                        </td>
                        <td>
                            <?php echo esc_html($data['totalemployer']->totalemployer); ?>
                        </td>
                        <td>
                            <?php echo '-'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html(__('Jobseeker', 'wp-job-portal')); ?>
                        </td>
                        <td>
                            <?php echo esc_html($data['totaljobseeker']->totaljobseeker); ?>
                        </td>
                        <td>
                            <?php echo '-'; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
