<?php
    if (!defined('ABSPATH'))
        die('Restricted Access');
    wp_enqueue_script('wpjobportal-res-tables', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/responsivetable.js');
    require_once WPJOBPORTAL_PLUGIN_PATH.'includes/addon-updater/wpjobportalupdater.php';
    $WP_JOBPORTALUpdater  = new WP_JOBPORTALUpdater();
    $cdnversiondata = $WP_JOBPORTALUpdater->getPluginVersionDataFromCDN();
    $not_installed = array();

    $wpjobportal_addons = $jssupportticket_addons = WPJOBPORTALincluder::getJSModel('wpjobportal')->getWPJPAddonsArray();

?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data" class="wpjpadmin-addons-list-data">
        <?php
            $msgkey = WPJOBPORTALincluder::getJSModel('wpjobportal')->getMessagekey();
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
                        <li><?php echo esc_html(__('Addons Status','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'wpjobportal' , 'layouts' => 'addonstatus')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <!-- admin addons status -->
            <div id="black_wrapper_translation"></div>
            <div id="jstran_loading">
                <img alt="<?php echo esc_html(__('spinning wheel','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/spinning-wheel.gif" />
            </div>
            <div class="wpjpadmin-addons-list-wrp">
                <?php
                $installed_plugins = get_plugins();
                ?>
                <?php
                    foreach ($wpjobportal_addons as $key1 => $value1) {
                        $matched = 0;
                        $version = "";
                        foreach ($installed_plugins as $name => $value) {
                            $install_plugin_name = str_replace(".php","",basename($name));
                            if($key1 == $install_plugin_name){
                                $matched = 1;
                                $version = $value["Version"];
                                $install_plugin_matched_name = $install_plugin_name;
                            }
                        }
                        $status = '';
                        if($matched == 1){ //installed
                            $name = $key1;
                            $title = $value1['title'];
                            $img = str_replace("wp-job-portal-", "", $key1).'.png';
                            $cdnavailableversion = "";
                            foreach ($cdnversiondata as $cdnname => $cdnversion) {
                                $install_plugin_name_simple = str_replace("-", "", $install_plugin_matched_name);
                                if($cdnname == str_replace("-", "", $install_plugin_matched_name)){
                                    if($cdnversion > $version){ // new version available
                                        $status = 'update_available';
                                        $cdnavailableversion = $cdnversion;
                                    }else{
                                        $status = 'updated';
                                    }
                                }    
                            }
                            WPJP_PrintAddoneStatus($name, $title, $img, $version, $status, $cdnavailableversion);
                        }else{ // not installed
                            $img = str_replace("wp-job-portal-", "", $key1).'.png';
                            $not_installed[] = array("name" => $key1, "title" => $value1['title'], "img" => $img, "status" => 'not-installed', "version" => "---");
                        }
                    }
                    foreach ($not_installed as $notinstall_addon) {
                        WPJP_PrintAddoneStatus($notinstall_addon["name"], $notinstall_addon["title"], $notinstall_addon["img"], $notinstall_addon["version"], $notinstall_addon["status"]);
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
function WPJP_PrintAddoneStatus($name, $title, $img, $version, $status, $cdnavailableversion = ''){
    $addoneinfo = WPJOBPORTALincluder::getJSModel('wpjobportal')->checkWPJPAddoneInfo($name);
    if ($status == 'update_available') {
        $wrpclass = 'wpjp-admin-addon-status wpjp-admin-addons-status-update-wrp';
        $btnclass = 'wpjp-admin-addons-update-btn';
        $btntxt = 'Update Now';
        $btnlink = 'id="wpjp-admin-addons-update" data-for="'.esc_attr($name).'"';
        $msg = '<span id="wpjp-admin-addon-status-cdnversion">'.esc_html(__('New Update Version','wp-job-portal'));
        $msg .= '<span>'." ".esc_html($cdnavailableversion)." ".'</span>';
        $msg .= esc_html(__('is Available','wp-job-portal')).'</span>';
    } elseif ($status == 'expired') {
        $wrpclass = 'wpjp-admin-addon-status wpjp-admin-addons-status-expired-wrp';
        $btnclass = 'wpjp-admin-addons-expired-btn';
        $btntxt = 'Expired';
        $btnlink = '';
        $msg = '';
    } elseif ($status == 'updated') {
        $wrpclass = 'wpjp-admin-addon-status';
        $btnclass = '';
        $btntxt = 'Updated';
        $btnlink = '';
        $msg = '';
    } else {
        $wrpclass = 'wpjp-admin-addon-status';
        $btnclass = 'wpjp-admin-addons-buy-btn';
        $btntxt = 'Buy Now';
        $btnlink = 'href="https://wpjobportal.com/add-ons/"';
        $msg = '';
    }
    $html = '
    <div class="'.esc_attr($wrpclass).'" id="'.esc_attr($name).'">
        <div class="wpjp-addon-status-image-wrp">
            <img alt="Addone image" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/addons/'.esc_attr($img).'" />
        </div>
        <div class="wpjp-admin-addon-status-title-wrp">
            <h2>'. esc_html($title) .'</h2>
            <a class="'. esc_attr($addoneinfo["actionClass"]) .'" href="'. esc_url($addoneinfo["url"]) .'">
                '. esc_html($addoneinfo["action"]) .'
            </a>
            '. wp_kses($msg, WPJOBPORTAL_ALLOWED_TAGS).'
        </div>
        <div class="wpjp-admin-addon-status-addonstatus-wrp">
            <span>'. esc_html(__('Status','wp-job-portal')) .': </span>
            <span class="wpjp-admin-adons-status-Active" href="#">
                '. esc_html($addoneinfo["status"]) .'
            </span>
        </div>
        <div class="wpjp-admin-addon-status-addonsversion-wrp">
            <span id="wpjp-admin-addon-status-cversion">
                '. esc_html(__('Version','wp-job-portal')).': 
                <span>
                    '. esc_html($version) .'
                </span>
            </span>
        </div>
        <div class="wpjp-admin-addon-status-addonstatusbtn-wrp">
            <a '. wp_kses($btnlink, WPJOBPORTAL_ALLOWED_TAGS).' class="'.esc_attr($btnclass).'">'. esc_html(wpjobportal::wpjobportal_getVariableValue($btntxt)) .'</a>
        </div>
        <div class="wpjp-admin-addon-status-msg wpjp_admin_success">
            <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL) .'includes/images/success.png" />
            <span class="wpjp-admin-addon-status-msg-txt"></span>
        </div>
        <div class="wpjp-admin-addon-status-msg wpjp_admin_error">
            <img src="'. esc_url(WPJOBPORTAL_PLUGIN_URL) .'includes/images/error.png" />
            <span class="wpjp-admin-addon-status-msg-txt"></span>
        </div>
    </div>';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

?>

<script>
    jQuery(document).ready(function(){
        jQuery(document).on("click", "a#wpjp-admin-addons-update", function(){
            jsShowLoading();
            var dataFor = jQuery(this).attr("data-for");
            var cdnVer = jQuery('#'+ dataFor +' #wpjp-admin-addon-status-cdnversion span').text();
            var currentVer = jQuery('#'+ dataFor +' #wpjp-admin-addon-status-cversion span').text();
            var cdnVersion = cdnVer.trim();
            var currentVersion = currentVer.trim();
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'wpjobportal', task: 'downloadandinstalladdonfromAjax', dataFor:dataFor, currentVersion:currentVersion, cdnVersion:cdnVersion, '_wpnonce':'<?php echo esc_attr(wp_create_nonce("download-and-install-addon")); ?>'}, function (data) {
                if (data) {
                    jsHideLoading();
                    data = JSON.parse(data);
                    if(data['error']){
                        jQuery('#' + dataFor).css('background-color', '#fff');
                        jQuery('#' + dataFor).css('border-color', '#FF4F4E');
                        jQuery('#' + dataFor + ' .wpjp-admin-addon-status-title-wrp span').hide();
                        jQuery('#' + dataFor + ' .wpjp-admin-addon-status-msg.wpjp_admin_error').show();
                        jQuery('#' + dataFor + ' .wpjp-admin-addon-status-msg.wpjp_admin_error span.wpjp-admin-addon-status-msg-txt').html(data['error']);
                        jQuery('#' + dataFor + ' .wpjp-admin-addon-status-msg.wpjp_admin_error').slideDown('slow');
                    } else if(data['success']) {
                        jQuery('#' + dataFor).css('background-color', '#fff');
                        jQuery('#' + dataFor).css('border-color', '#0C6E45');
                        jQuery('#' + dataFor + ' a#wpjp-admin-addons-update').hide();
                        jQuery('#' + dataFor + ' .wpjp-admin-addon-status-title-wrp span').hide();
                        jQuery('#' + dataFor + ' .wpjp-admin-addon-status-msg.wpjp_admin_success').show();
                        jQuery('#' + dataFor + ' .wpjp-admin-addon-status-msg.wpjp_admin_success span.wpjp-admin-addon-status-msg-txt').html(data['success']);
                        jQuery('#' + dataFor + ' .wpjp-admin-addon-status-msg.wpjp_admin_success').slideDown('slow');
                    }
                }
            });
        });
    });
    function jsShowLoading(){
        jQuery('div#black_wrapper_translation').show();
        jQuery('div#jstran_loading').show();
    }

    function jsHideLoading(){
        jQuery('div#black_wrapper_translation').hide();
        jQuery('div#jstran_loading').hide();
    }
</script>
