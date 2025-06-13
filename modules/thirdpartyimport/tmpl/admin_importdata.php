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
                        <li><?php echo esc_html(__('Import Data','wp-job-portal')); ?></li>
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
        <div id="wpjobportal-admin-wrapper" class="wpjobportal-importdata-adddons-main-wrap">
            <?php
                $count_for = wpjobportal::$_data['count_for'];
                $entity_counts = [];
                if($count_for > 0 &&  !empty(wpjobportal::$_data['entity_counts'])){
                    $entity_counts = wpjobportal::$_data['entity_counts'];
                }
                //echo '<pre>';print_r($entity_counts);echo '</pre>';

                // plugins for which we support importing data
                $plguins_array = [];

                // plugin data
                $plguins_array['dummp_plgn'] = [];
                $plguins_array['dummp_plgn']['name'] = esc_html(__('Dummy Plugin','wp-job-portal'));
                $plguins_array['dummp_plgn']['path'] = "dummy-manager/dummy-manager.php"; // needed to check if plugin is active
                $plguins_array['dummp_plgn']['internalid'] = 2; // value used to identfy the plugin

                // plugin data
                $plguins_array['wp_job_manager'] = [];
                $plguins_array['wp_job_manager']['name'] = esc_html(__('Job Manager','wp-job-portal'));
                $plguins_array['wp_job_manager']['path'] = "wp-job-manager/wp-job-manager.php";  // needed to check if plugin is active
                $plguins_array['wp_job_manager']['internalid'] = 1; // value used to identfy the plugin

                // plugin data
                $plguins_array['dummp_plgn2'] = [];
                $plguins_array['dummp_plgn2']['name'] = esc_html(__('Dummy Plugin 2','wp-job-portal'));
                $plguins_array['dummp_plgn2']['path'] = "dummy-manager/dummy-manager.php"; // needed to check if plugin is active
                $plguins_array['dummp_plgn2']['internalid'] = 3; // value used to identfy the plugin


            foreach ($plguins_array as $plugin) {
                // check if Plugin is active
                if($count_for != $plugin['internalid']){
                    $extr_clss = 'wpjobportal-plugin-notinstalled';
                    if ( is_plugin_active( $plugin['path'] ) ) {
                        $extr_clss = '';
                    }
                ?>
                    <div class="wpjobportal-plugins-imprt-datasec <?php echo esc_attr($extr_clss);?>">
                        <span class="wpjobportal-plugins-imprt-data-plgnnme"><?php echo esc_html($plugin['name']); ?></span>
                        <?php if($extr_clss != ''){ ?>
                            <span class="wpjobportal-plugins-imprt-databtn">
                                <img class="wpjobportal-plugins-imprterror-image" alt="<?php echo esc_html(__('icon','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/imprt-icon.png" />
                                <?php echo esc_html(__('Plugin not installed','wp-job-portal')); ?>
                            </span>
                        <?php }else{ ?>
                            <a class="wpjobportal-plugins-imprt-databtn" href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_thirdpartyimport&wpjobportallt=importdata&selected_plugin=".$plugin['internalid'])); ?>" title="<?php echo esc_html(__('Fetch Data','wp-job-portal')); ?>"><?php echo esc_html(__('Fetch Data','wp-job-portal')); ?></a>
                        <?php } ?>
                    </div>
                <?php
                }else{
                    if(!empty($entity_counts)){ ?>
                        <div class="wpjobportal-singleplugin-imprt-data-sec">
                            <span class="wpjobportal-singleplugin-imprt-datatitle"><?php echo esc_html($plugin['name']); ?></span>


                            <?php foreach ($entity_counts as $entity_name => $entity_val) {
                                $entity_name = ucwords(str_replace('_', ' ', $entity_name));
                                $extr_clss = '';
                                if($entity_name == "Tags"){
                                    if(!in_array('tag', wpjobportal::$_active_addons)){
                                        $extr_clss = 'wpjobportal-singleplugin-imprt-data-addonnot-instllwrp';
                                    }
                                }
                            ?>
                                <div class="wpjobportal-singleplugin-imprt-datadisc <?php echo esc_attr($extr_clss);?>">
                                    <?php echo esc_html($entity_val).'&nbsp;'.esc_html(wpjobportal::wpjobportal_getVariableValue($entity_name)).'&nbsp;'.esc_html(__('found','wp-job-portal'));

                                    if($entity_name == "Resumes"){
                                        if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                                         ?>
                                            <br>
                                            <span class="wpjobportal-import-data-addon-message"><?php echo esc_html(__('Advanced Resume Builder Addon missing, resume sections data will not be imported!','wp-job-portal')); ?></span>
                                            <?php
                                        }
                                    }
                                    if($extr_clss != ''){ ?>
                                    <span class="wpjobportal-singleplugin-imprt-data-addonnot-instll">
                                        <img class="wpjobportal-plugins-imprterror-image" alt="<?php echo esc_html(__('icon','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/imprt-icon.png" />
                                        <?php echo esc_html(__('Addon not installed please install addon first.','wp-job-portal')); ?>

                                    </span> <?php
                                    } ?>
                                </div>
                                <?php
                            } ?>
                            <div class="wpjobportal-singleplugin-imprt-databtn-wrp">
                                    <a class="wpjobportal-singleplugin-imprt-databtn" title="<?php echo esc_html(__('Import Data','wp-job-portal')); ?>" href="<?php echo esc_url_raw(wp_nonce_url(admin_url("admin.php?page=wpjobportal_thirdpartyimport&task=importjobmanagerdata&action=wpjobportaltask&selected_plugin=".$plugin['internalid']),'wpjobportal_job_manager_import_nonce')) ?>"><?php echo esc_html(__('Import Data','wp-job-portal')); ?></a>
                            </div>
                        </div><?php
                    }
                }
            } ?>
        </div>
    </div>
</div>
<?php

/*

<div class="wpjobportal-singleplugin-imprt-datadisc wpjobportal-singleplugin-imprt-data-addonnot-instllwrp">
                                <?php echo esc_html(__('10254 Departments found','wp-job-portal')); ?>

                            </div>

*/

?>