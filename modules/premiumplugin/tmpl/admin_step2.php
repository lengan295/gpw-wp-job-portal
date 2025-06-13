<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
 <?php
$allPlugins = get_plugins(); // associative array of all installed plugins

$addon_array = array();
foreach ($allPlugins as $key => $value) {
    $addon_index = wpjobportalphplib::wpJP_explode('/', $key);
    $addon_array[] = $addon_index[0];
}
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
            <div id="wpjobportal-head">
                <h1 class="wpjobportal-head-text"><?php echo esc_html(__('Install Addons','wp-job-portal')); ?></h1>
            </div>
            <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
                <div id="wpjobportal-content">
                    <div id="black_wrapper_translation"></div>
                    <div id="jstran_loading">
                        <img alt="image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/spinning-wheel.gif" />
                    </div>
                    <div id="wpjobportal-lower-wrapper">
                        <div class="wpjobportal-addon-installer-wrapper" >
                            <form id="wpjobportalfrom" action="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_premiumplugin&task=downloadandinstalladdons&action=wpjobportaltask'),'wpjobportal_premiumplugin_nonce')); ?>" method="post">
                                <div class="wpjobportal-addon-installer-left-section-wrap" >
                                    <div class="wpjobportal-addon-installer-left-image-wrap" >
                                        <img class="wpjobportal-addon-installer-left-image" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/addon-images/addon-installer-logo.png" />
                                    </div>
                                    <div class="wpjobportal-addon-installer-left-heading" >
                                        <?php echo esc_html(__("WP Job Portal",'wp-job-portal')); ?>
                                    </div>
                                    <div class="wpjobportal-addon-installer-left-title" >
                                        <?php echo esc_html(__("Wordpress Plugin",'wp-job-portal')); ?>
                                    </div>
                                    <div class="wpjobportal-addon-installer-left-description" >
                                        <?php echo esc_html(__("WP Job Portal is an open-source job board plugin for WordPress that provides advanced features to help you create a successful job board. In addition, we offer trusted WP Job Portal add-ons that can further enhance your job board's functionality in a fast, safe, and easy manner. Our add-ons have been designed to integrate seamlessly with our job board plugin, ensuring you can expand your job board's capabilities without any complications.",'wp-job-portal')); ?>
                                    </div>
                                </div>
                                <div class="wpjobportal-addon-installer-right-section-wrap step2" >
                                    <div class="wpjobportal-addon-installer-right-heading" >
                                        <?php echo esc_html(__("WP Job Portal Addon Installer",'wp-job-portal')); ?>
                                    </div>
                                    <?php /*
                                    <div class="wpjobportal-addon-installer-right-description" >
                                        lorem ipsum dolor sit amet
                                    </div> */ ?>
                                    <div class="wpjobportal-addon-installer-right-addon-wrapper" >
                                        <?php
                                        $wpjobportal_addon_install_data = false;
                                        if(get_option( 'wpjobportal_addon_install_data', '' )){
                                            $wpjobportal_addon_install_data = json_decode(get_option('wpjobportal_addon_install_data'), true);
                                        }
                                        $error_message = '';
                                        if($wpjobportal_addon_install_data){
                                            $result = $wpjobportal_addon_install_data;
                                            if(isset($result['status']) && $result['status'] == 1){ ?>
                                                <div class="wpjobportal-addon-installer-right-addon-title">
                                                    <?php echo esc_html(__("Select Addons for download",'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-addon-installer-right-addon-section" >
                                                    <?php
                                                    if(!empty($result['data'])){
                                                        $addon_availble_count = 0;
                                                        foreach ($result['data'] as $key => $value) {
                                                            if(!in_array($key, $addon_array)){
                                                                $addon_availble_count++;
                                                                $addon_slug_array = wpjobportalphplib::wpJP_explode('-', $key);
                                                                $addon_image_name = $addon_slug_array[count($addon_slug_array) - 1];
                                                                $addon_slug = wpjobportalphplib::wpJP_str_replace('-', '', $key);

                                                                $addon_img_path = '';
                                                                $addon_img_path = esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/addon-images/addons/';
                                                                if($value['status'] == 1){ ?>
                                                                    <div class="wpjobportal-addon-installer-right-addon-single" >
                                                                        <img class="wpjobportal-addon-installer-right-addon-image" data-addon-name="<?php echo esc_attr($key); ?>" src="<?php echo esc_url($addon_img_path.$addon_image_name.'.png');?>" />
                                                                        <div class="wpjobportal-addon-installer-right-addon-name" >
                                                                            <?php echo esc_html($value['title']) ;?>
                                                                        </div>
                                                                        <input type="checkbox" class="wpjobportal-addon-installer-right-addon-single-checkbox" id="addon-<?php echo esc_attr($key); ?>" name="<?php echo esc_attr($key); ?>" value="1">
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        if($addon_availble_count == 0){ // all allowed addon are already installed
                                                            $error_message = esc_html(__('All allowed add-ons are already installed','wp-job-portal')).'.';
                                                        }
                                                    }else{ // no addon returend
                                                        $error_message = esc_html(__('You are not allowed to install any add on','wp-job-portal')).'.';
                                                    }
                                                    if($error_message != ''){
                                                        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step1"));

                                                        echo '<div class="wpjobportal-addon-go-back-messsage-wrap">';
                                                        echo '<h1>';
                                                        echo esc_html($error_message);
                                                        echo '</h1>';

                                                        echo '<a class="wpjobportal-addon-go-back-link" href="'.esc_url($url).'">';
                                                        echo esc_html(__('Back','wp-job-portal'));
                                                        echo '</a>';
                                                        echo '</div>';
                                                    }
                                                     ?>
                                                </div>
                                                <?php if($error_message == ''){ ?>
                                                    <div class="wpjobportal-addon-installer-right-addon-bottom" >
                                                        <label for="wpjobportal-addon-installer-right-addon-checkall-checkbox"><input type="checkbox" class="wpjobportal-addon-installer-right-addon-checkall-checkbox" id="wpjobportal-addon-installer-right-addon-checkall-checkbox"><?php echo esc_html(__("Select All Addons",'wp-job-portal')); ?></label>
                                                    </div>
                                                <?php
                                                }
                                            }
                                        }else{
                                            $error_message = esc_html(__('Something went wrong','wp-job-portal')).'!';
                                            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step1"));

                                            echo '<div class="wpjobportal-addon-go-back-messsage-wrap">';
                                            echo '<h1>';
                                            echo esc_html($error_message);
                                            echo '</h1>';

                                            echo '<a class="wpjobportal-addon-go-back-link" href="'.esc_url($url).'">';
                                            echo esc_html(__('Back','wp-job-portal'));
                                            echo '</a>';
                                            echo '</div>';
                                        }

                                         ?>
                                    </div>
                                    <?php if($error_message == ''){ ?>
                                        <div class="wpjobportal-addon-installer-right-button" >
                                            <button type="submit" class="wpjobportal_btn" role="submit" onclick="jsShowLoading();"><?php echo esc_html(__("Proceed",'wp-job-portal')); ?></button>
                                        </div>
                                    <?php } ?>
                                </div>

                                <?php // to handle log error
                                $token = '';
                                if(isset($result['token']) && $result['token']!= ''){
                                    $token = $result['token'];
                                } ?>
                                    <input type="hidden" name="token" value="<?php echo esc_attr($token); ?>"/>
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

        jQuery('.wpjobportal-addon-installer-right-addon-image').on('click', function() {
            var addon_name = jQuery(this).attr('data-addon-name')
            var prop_checked = jQuery('#addon-'+addon_name).prop('checked');
            if(prop_checked){
                jQuery('#addon-'+addon_name).prop('checked', false);
            }else{
                jQuery('#addon-'+addon_name).prop('checked', true);
            }
        });
        // to handle select all check box.
        jQuery('#wpjobportal-addon-installer-right-addon-checkall-checkbox').change(function() {
           jQuery('.wpjobportal-addon-installer-right-addon-single-checkbox').prop('checked', this.checked);
       });


    });

    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jstran_loading').show();
    }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>

<?php
delete_option('wpjobportal_addon_install_data');

?>
