<?php
if (!defined('ABSPATH'))
die('Restricted Access');
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <?php
            $msgkey = WPJOBPORTALincluder::getJSModel('thirdpartyimport')->getMessagekey();
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
                        <li><?php echo esc_html(__('Import Data Report','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'thirdpartyimport' , 'layouts' => 'importdata')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <?php

            $results_array = [
                'company' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ],
                'job' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ],
                'resume' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ],
                'user' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ],
                'jobapply' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ],
                'field' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ],

                'jobtype' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ],
                'category' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ],
                'tag' => [
                    'imported' => 10,
                    'skipped'  => 2,
                    'failed'   => 3,
                ]
            ];
            $plugin_label = 'WP Job Manager';
            if(!empty(wpjobportal::$_data['import_for'])){
                $import_for = wpjobportal::$_data['import_for'];
                if($import_for == 1){
                    $plugin_label = 'WP Job Manager';
                }
            }

            ?>
            <table class="wpjobportal-import-data-result-import-table">
                <thead>
                    <tr>
                        <th style="width:50%;"><?php echo  __('Entity','wp-job-portal'); ?></th>
                        <th style="text-align: center;background-color: #006D3A;width:16.6%;"><?php echo  __('Imported','wp-job-portal'); ?></th>
                        <th style="text-align: center;background-color: #A75424;width:16.6%;"><?php echo  __('Similar Found','wp-job-portal'); ?></th>
                        <th style="text-align: center;background-color: #891518;width:16.6%;"><?php echo  __('Not Imported','wp-job-portal'); ?></th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results_array as $type => $counts){
                        $label = ucwords(str_replace(['_', 'jobtype', 'jobapply'], [' ', 'Job Type', 'Job Application'], $type));
                        $imported = (int) $counts['imported'];
                        $skipped  = (int) $counts['skipped'];
                        $failed   = (int) $counts['failed'];

                        if($label == 'Company' && $imported > 1){
                            $label = 'Companies';
                        }else{
                            $label = $label.'s';
                        }
                        ?>
                        <tr>
                            <td><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($label)); ?></td>

                            <td class="wpjobportal-import-data-result-success">
                                <?php echo esc_html( $imported .' '. __('imported.','wp-job-portal') ); ?>
                            </td>

                            <td class="wpjobportal-import-data-result-similar">
                                <?php echo esc_html( $skipped .' '. __('skipped.','wp-job-portal') ); ?>
                            </td>

                            <td class="wpjobportal-import-data-result-failed">
                                <?php echo esc_html( $failed .' '. __('failed.','wp-job-portal') ); ?>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div>
