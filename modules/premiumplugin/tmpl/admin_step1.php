<?php if (!defined('ABSPATH')) die('Restricted Access');

delete_option( 'wpjobportal_addon_install_data' );
?>
    <div id="wpjobportaladmin-wrapper">
        <div id="wpjobportaladmin-leftmenu">
            <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
        </div>
        <div id="wpjobportaladmin-data">
            <div id="wpjobportal-wrapper-top">
                <div id="wpjobportal-wrapper-top-left">
                    <div id="wpjobportal-breadcrumbs">
                        <ul>
                            <li>
                                <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo esc_html(__('Dashboard','wp-job-portal')); ?>">
                                    <?php echo esc_html(__('Dashboard','wp-job-portal')); ?>
                                </a>
                            </li>
                            <li><?php echo esc_html(__('Install Addons','wp-job-portal')); ?></li>
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
            <div id="wpjobportal-admin-wrapper" class="wpjobportal-admin-installer-wrapper step1">
                <div id="wpjobportal-content">
                    <div id="black_wrapper_translation"></div>
                    <div id="jstran_loading">
                        <img alt="image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/spinning-wheel.gif" />
                    </div>
                    <div id="wpjobportal-lower-wrapper">
                        <div class="wpjobportal-addon-installer-wrapper" >
                            <form id="wpjobportalfrom" action="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_premiumplugin&task=verifytransactionkey&action=wpjobportaltask'),'wpjobportal_premiumplugin_nonce')); ?>" method="post">
                                <div class="wpjobportal-addon-installer-left-section-wrap" >
                                    <div class="wpjobportal-addon-installer-left-image-wrap" >
                                        <img class="wpjobportal-addon-installer-left-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/quickstrt_logo.png" />
                                        <span class="wpjobportal-addon-installer-left-title"><?php echo esc_html(__("Addon Installer",'wp-job-portal')); ?></span>
                                    </div>
                                </div>
                                <div class="wpjobportal-addon-installer-right-section-wrap" >
                                    <div class="wpjobportal-addon-installer-right-section-heading-wrp">
                                        <div class="wpjobportal-addon-installer-right-section-heading">
                                            <?php echo esc_html(__('Welcome to WP Job Portal Addon Installer','wp-job-portal'));?>
                                        </div>
                                        <div class="wpjobportal-addon-installer-right-section-btn-wrp">
                                            <a class="wpjobportal-addon-installer-right-section-head-closebtn" href="admin.php?page=wpjobportal"title="<?php echo esc_html(__('Close','wp-job-portal'));?>">
                                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/postinstallation/close.png" />
                                            </a>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-addon-installer-right-heading" >
                                        <?php echo esc_html(__("Please Insert Your Activation Key",'wp-job-portal')); ?>
                                    </div>
                                    <div class="wpjobportal-addon-installer-right-key-section" >
                                        <?php
                                        $error_message = '';
                                        $transactionkey = '';
                                        if(get_option( 'wpjobportal_addon_return_data', '' ) != ''){
                                            $wpjobportal_addon_return_data = json_decode(get_option( 'wpjobportal_addon_return_data' ),true);
                                            if(isset($wpjobportal_addon_return_data['status']) && $wpjobportal_addon_return_data['status'] == 0){
                                                $error_message = $wpjobportal_addon_return_data['message'];
                                                $transactionkey = $wpjobportal_addon_return_data['transactionkey'];
                                            }
                                            delete_option( 'wpjobportal_addon_return_data' );
                                        }

                                        ?>
                                        <div class="wpjobportal-addon-installer-right-key-field" >
                                            <input type="text" name="transactionkey" id="transactionkey" class="wpjobportal_key_field" value="<?php echo esc_attr($transactionkey);?>"/>
                                            <?php if($error_message != '' ){ ?>
                                                <div class="wpjobportal-addon-installer-right-key-field-message" > <img alt="image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/icon.png" /> <?php echo esc_html($error_message) ;?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="wpjobportal-addon-installer-right-key-button" >
                                            <button type="submit" class="wpjobportal_btn" role="submit" onclick="jsShowLoading();"><?php echo esc_html(__("Proceed",'wp-job-portal')); ?></button>
                                        </div>
                                    </div>
                                    <div class="wpjobportal-addon-installer-right-description" >
                                        <a class="wpjobportal-addon-installer-install-btn" href="?page=wpjobportal_premiumplugin&wpjobportallt=addonfeatures" class="wpjobportal-addon-installer-addon-list-link" target="_blank" >
                                            <?php echo esc_html(__("Add on list",'wp-job-portal')); ?>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function(){
            jQuery('#wpjobportalfrom').on('submit', function() {
                jsShowLoading();
            });
        });

        function jsShowLoading(){
            jQuery('div#black_wrapper_translation').show();
            jQuery('div#jstran_loading').show();
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>