<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function ($) {
            var wpjpconfigid = '". esc_js(wpjobportal::$_data["wpjpconfigid"]) ."';
            if (wpjpconfigid == 'general_setting') {
                jQuery('#general_setting').css('display','inline-block');
                jQuery('#gen_setting').addClass('active');
            }else if (wpjpconfigid == 'visitor_setting') {
                jQuery('#visitor_setting').css('display','inline-block');
                jQuery('#vis_setting').addClass('active');
            }else if (wpjpconfigid == 'package_setting') {
                jQuery('#package_setting').css('display','inline-block');
                jQuery('#pack_setting').addClass('active');
            }else if (wpjpconfigid == 'social_share') {
                jQuery('#social_share').css('display','inline-block');
                jQuery('#social_setting').addClass('active');
            }else if (wpjpconfigid == 'rss_setting') {
                jQuery('#rss_setting').css('display','inline-block');
                jQuery('#rs_setting').addClass('active');
            }else if (wpjpconfigid == 'login_register') {
                jQuery('#login_register').css('display','inline-block');
                jQuery('#lr_setting').addClass('active');
            }else if (wpjpconfigid == 'job_apply') {
                jQuery('#job_apply').css('display','inline-block');
                jQuery('#apply_setting').addClass('active');
            }else if (wpjpconfigid == 'ai_settings') {
                jQuery('#ai_settings').css('display','inline-block');
                jQuery('#ai_settings').addClass('active');
            }else{
                jQuery('#general_setting').css('display','inline-block');
                jQuery('#gen_setting').addClass('active');
            }
            // jQuery('#tabs').tabs();
            $.validate();
            jQuery('#loginwithlinkedin').change(function(){
                var isselect = jQuery('#loginwithlinkedin option:selected').val();
                if(isselect == 1){
                    jQuery('#apikeylinkedin').attr('data-validation', 'required');
                }else{
                    jQuery('#apikeylinkedin').removeAttr('data-validation');
                }
            });
            jQuery('#loginwithfacebook').change(function(){
                var isselect = jQuery('#loginwithfacebook option:selected').val();
                if(isselect == 1){
                    jQuery('#apikeyfacebook').attr('data-validation', 'required');
                }else{
                    jQuery('#apikeyfacebook').removeAttr('data-validation');
                }
            });
            //indeed validation
            jQuery('#indeedjob_enabled').change(function(){
                var isselect = jQuery('#indeedjob_enabled option:selected').val();
                if(isselect == 1){
                    jQuery('#indeedjob_apikey').attr('data-validation', 'required');
                    jQuery('#indeedjob_category').attr('data-validation', 'required');
                    jQuery('#indeedjob_location').attr('data-validation', 'required');
                }else{
                    jQuery('#indeedjob_apikey').removeAttr('data-validation');
                    jQuery('#indeedjob_category').removeAttr('data-validation');
                    jQuery('#indeedjob_location').removeAttr('data-validation');
                }
            });
            //career builder validation
            jQuery('#careerbuilder_enabled').change(function(){
                var isselect = jQuery('#careerbuilder_enabled option:selected').val();
                if(isselect == 1){
                    jQuery('#careerbuilder_developerkey').attr('data-validation', 'required');
                }else{
                    jQuery('#careerbuilder_developerkey').removeAttr('data-validation');

                }
            });
            // login and register redirect
            jQuery('select#set_register_redirect_link').change(function(){
                var value = jQuery(this).val();
                if (value == 2){
                    jQuery('.register_redirect_link').attr('style','display: block');
                    jQuery('.wpjobportal-register-redirect-value').addClass('wpjobportal-config-2-fields');
                }else{
                    jQuery('.register_redirect_link').attr('style','display: none');
                    jQuery('.wpjobportal-register-redirect-value').removeClass('wpjobportal-config-2-fields');
                }
            });
            jQuery('select#set_login_redirect_link').change(function(){
                var value = jQuery(this).val();
                if (value == 2){
                    jQuery('.login_redirect_link').attr('style','display: block');
                    jQuery('.wpjobportal-login-redirect-value').addClass('wpjobportal-config-2-fields');
                }else{
                    jQuery('.login_redirect_link').attr('style','display: none');
                    jQuery('.wpjobportal-login-redirect-value').removeClass('wpjobportal-config-2-fields');
                }
            });
            var value = jQuery('select#set_register_redirect_link').val();
            if (value == 2){
                jQuery('.register_redirect_link').attr('style','display: block');
                jQuery('.wpjobportal-register-redirect-value').addClass('wpjobportal-config-2-fields');
            } else {
                jQuery('.register_redirect_link').attr('style','display: none');
                jQuery('.wpjobportal-register-redirect-value').removeClass('wpjobportal-config-2-fields');
            }
            var value = jQuery('select#set_login_redirect_link').val();
            if (value == 2){
                jQuery('.login_redirect_link').attr('style','display: block');
                jQuery('.wpjobportal-login-redirect-value').addClass('wpjobportal-config-2-fields');
            } else {
                jQuery('.login_redirect_link').attr('style','display: none');
                jQuery('.wpjobportal-login-redirect-value').removeClass('wpjobportal-config-2-fields');
            }

        });
        // for the set register 
        jQuery(document).ready(function () {
            // for job seeker
            jQuery('select#jobseeker_set_register_link').change(function(){
               var value = jQuery(this).val();
                if (value == 2) {
                   jQuery('.js_registerlink_field').attr('style','display: block');
                   jQuery('.js_registerpage_field').attr('style','display: none');
                } else {
                    jQuery('.js_registerlink_field').attr('style','display: none');
                    jQuery('.js_registerpage_field').attr('style','display: block');
                }
            });
            var value = jQuery('select#jobseeker_set_register_link').val();
            if (value == 2) {
                jQuery('.js_registerlink_field').attr('style','display: block');
                jQuery('.js_registerpage_field').attr('style','display: none');
            } else {
                jQuery('.js_registerlink_field').attr('style','display: none');
                jQuery('.js_registerpage_field').attr('style','display: block');
            }
            // for employer
            jQuery('select#employe_set_register_link').change(function(){
               var value = jQuery(this).val();
                if (value == 2){
                    jQuery('.emp_registerlink_field').attr('style','display: block');
                    jQuery('.emp_registerpage_field').attr('style','display: none');
                }else{
                    jQuery('.emp_registerlink_field').attr('style','display: none');
                    jQuery('.emp_registerpage_field').attr('style','display: block');
                }
            });
            var value = jQuery('select#employe_set_register_link').val();
            if (value == 2){
                jQuery('.emp_registerlink_field').attr('style','display: block');
                jQuery('.emp_registerpage_field').attr('style','display: none');
            } else {
                jQuery('.emp_registerlink_field').attr('style','display: none');
                jQuery('.emp_registerpage_field').attr('style','display: block');
            }


            // default image field related js

            jQuery('#wjportal-form-delete-image').click(function(){
                jQuery('.wjportal-form-image-wrp').slideUp('slow');
                jQuery('#remove_default_image').val(1);
            });


        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>

<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
$theme_chk = wpjobportal::$theme_chk ;
wp_enqueue_script('jquery-ui-tabs');
// Lists objecs
$date_format = array((object) array('id' => 'd-m-Y', 'text' => esc_html(__('dd-mm-yyyy', 'wp-job-portal'))), (object) array('id' => 'm/d/Y', 'text' => esc_html(__('mm/dd/yyyy', 'wp-job-portal'))), (object) array('id' => 'Y-m-d', 'text' => esc_html(__('yyyy-mm-dd', 'wp-job-portal'))));
$yesno = array((object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal'))), (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));
$searchjobtag = array((object) array('id' => 1, 'text' => esc_html(__('Top left', 'wp-job-portal'))), (object) array('id' => 2, 'text' => esc_html(__('Top right', 'wp-job-portal'))), (object) array('id' => 3, 'text' => esc_html(__('Middle left', 'wp-job-portal'))), (object) array('id' => 4, 'text' => esc_html(__('Middle right', 'wp-job-portal'))), (object) array('id' => 5, 'text' => esc_html(__('Bottom left', 'wp-job-portal'))), (object) array('id' => 6, 'text' => esc_html(__('Bottom right', 'wp-job-portal'))));
$captchalist = array((object) array('id' => 1, 'text' => esc_html(__('Google Captcha', 'wp-job-portal'))), (object) array('id' => 2, 'text' => esc_html(__('WP JOB PORTAL Captcha', 'wp-job-portal'))));
$captchacalculation = array((object) array('id' => 0, 'text' => esc_html(__('Any', 'wp-job-portal'))), (object) array('id' => 1, 'text' => esc_html(__('Addition', 'wp-job-portal'))), (object) array('id' => 2, 'text' => esc_html(__('Subtraction', 'wp-job-portal'))));
$captchaop = array((object) array('id' => 2, 'text' => 2), (object) array('id' => 3, 'text' => 3));
$showhide = array((object) array('id' => 1, 'text' => esc_html(__('Show', 'wp-job-portal'))), (object) array('id' => 0, 'text' => esc_html(__('Hide', 'wp-job-portal'))));
$defaultradius = array((object) array('id' => 1, 'text' => esc_html(__('Meters', 'wp-job-portal'))), (object) array('id' => 2, 'text' => esc_html(__('Kilometers', 'wp-job-portal'))), (object) array('id' => 3, 'text' => esc_html(__('Miles', 'wp-job-portal'))), (object) array('id' => 4, 'text' => esc_html(__('Nautical Miles', 'wp-job-portal'))));
$defaultaddressdisplaytype = array((object) array('id' => 'csc', 'text' => esc_html(__('City','wp-job-portal')).', ' .esc_html(__('State','wp-job-portal')).', ' .esc_html(__('Country', 'wp-job-portal'))), (object) array('id' => 'cs', 'text' => esc_html(__('City','wp-job-portal')).', ' .esc_html(__('State', 'wp-job-portal'))), (object) array('id' => 'cc', 'text' => esc_html(__('City','wp-job-portal')).', ' .esc_html(__('Country', 'wp-job-portal'))), (object) array('id' => 'c', 'text' => esc_html(__('City', 'wp-job-portal'))));
$social = array(1 => '');
$leftright = array((object) array('id' => 1, 'text' => esc_html(__('Left align', 'wp-job-portal'))),(object) array('id' => 2, 'text' => esc_html(__('Right align', 'wp-job-portal'))));
$wpjobportalsubmissiontypes = array(
    (object) array('id'=>1,'text'=>esc_html(__("Free",'wp-job-portal'))),
    (object) array('id'=>2,'text'=>esc_html(__("Per Listing",'wp-job-portal'))),
    (object) array('id'=>3,'text'=>esc_html(__("Membership",'wp-job-portal')))
);
$mappingservices = array(
    (object) array('id'=>'gmap','text'=>esc_html(__("Google Map",'wp-job-portal'))),
    (object) array('id'=>'osm','text'=>esc_html(__("Open Street Map",'wp-job-portal'))),
    );
$registerlinkoptions = array(
    (object) array('id' => '1', 'text' => esc_html(__('WP Job Portal Register Page', 'wp-job-portal'))),
    (object) array('id' => '3', 'text' => esc_html(__('WordPress Default Register Page', 'wp-job-portal'))),
    (object) array('id' => '2', 'text' => esc_html(__('Custom', 'wp-job-portal')))
);
$loginlinkoptions = array(
    (object) array('id' => '1', 'text' => esc_html(__('WP Job Portal Login Page', 'wp-job-portal'))),
    (object) array('id' => '3', 'text' => esc_html(__('WordPress Default Login Page', 'wp-job-portal'))),
    (object) array('id' => '2', 'text' => esc_html(__('Custom', 'wp-job-portal')))
);
$defaultcustom = array(
    (object) array('id' => '1', 'text' => esc_html(__('Default', 'wp-job-portal'))),
    (object) array('id' => '2', 'text' => esc_html(__('Custom', 'wp-job-portal')))
);

$recaptcha_version = array((object) array('id' => 1, 'text' => esc_html(__('Recaptcha Version 2', 'wp-job-portal'))), (object) array('id' => 2, 'text' => esc_html(__('Recaptcha Version 3', 'wp-job-portal'))));
global $wp_roles;
$roles = $wp_roles->get_names();
$userroles = array();
foreach ($roles as $key => $value) {
    $userroles[] = (object) array('id' => $key, 'text' => $value);
}

$msgkey = WPJOBPORTALincluder::getJSModel('configuration')->getMessagekey();
WPJOBPORTALMessages::getLayoutMessage($msgkey); ?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
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
                        <li><?php echo esc_html(__('Configuration','wp-job-portal')); ?></li>
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
        <div id="wpjobportal-head" class="wpjobportal-config-head">
            <h1 class="wpjobportal-head-text">
                <?php echo esc_html(__('Configurations', 'wp-job-portal')); ?>
            </h1>
        </div>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="wpjobportal-config-main-wrapper">
            <form id="wpjobportal-form" class="wpjobportal-configurations" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_configuration&task=saveconfiguration")); ?>"  enctype="multipart/form-data">
                    <div class="wpjobportal-configurations-toggle">
                        <img alt="<?php echo esc_html(__('menu','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/admin-left-menu/menu.png" />
                        <span class="jslm_text"><?php echo esc_html(__('Select Configuration', 'wp-job-portal')); ?></span>
                    </div>
                    <div class="wpjobportal-left-menu wpjobportal-config-left-menu">
                        <?php echo wp_kses(WPJOBPORTALincluder::getJSModel('configuration')->getConfigSideMenu(),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                    <div class="wpjobportal-right-content">
                        <div id="tabs" class="tabs">
                            <!-- GENERAL SETTINGS -->
                            <div id="general_setting" class="wpjobportal-hide-config">
                                <ul>
                                    <li class="ui-tabs-active">
                                        <a href="#site_setting">
                                            <?php echo esc_html(__('Site Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <?php if(in_array('message', wpjobportal::$_active_addons)){ ?>
                                        <li>
                                            <a href="#message">
                                                <?php echo esc_html(__('Messages', 'wp-job-portal')); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <a href="#defaul_setting">
                                            <?php echo esc_html(__('Default Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#categories">
                                            <?php echo esc_html(__('Categories', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#email">
                                            <?php echo esc_html(__('Email', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <?php if(in_array('addressdata', wpjobportal::$_active_addons)){ ?>
                                        <li>
                                            <a href="#googlemapadsense">
                                                <?php echo esc_html(__("Map", 'wp-job-portal')); ?>
                                            </a>
                                        </li>
                                   <?php  } ?>
                                        <li>
                                            <a href="#offline">
                                                <?php echo esc_html(__('Offline', 'wp-job-portal')); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#terms">
                                                <?php echo esc_html(__('Term And Conditions', 'wp-job-portal')); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#url-settings">
                                                <?php echo esc_html(__('URL Settings', 'wp-job-portal')); ?>
                                            </a>
                                        </li>
                                </ul>
                                <div class="tabInner">
                                    <!-- SITE SETTINGS -->
                                    <div id="site_setting" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Site Settings', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Title', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('title', wpjobportal::$_data[0]['title'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Data directory', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('data_directory', wpjobportal::$_data[0]['data_directory'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('System will upload all user files in this folder', 'wp-job-portal')); echo '<br/><b>"'.esc_url(WPJOBPORTAL_PLUGIN_PATH.wpjobportal::$_data[0]['data_directory']).'"</b>'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /*<div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('System slug', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('system_slug', wpjobportal::$_data[0]['system_slug'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div> */?>
                                        <?php if($theme_chk == 0){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show breadcrumbs', 'wp-job-portal'))?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('cur_location', $yesno, wpjobportal::$_data[0]['cur_location']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show navigation in breadcrumbs', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Date format', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('date_format', $date_format, wpjobportal::$_data[0]['date_format'], '', array('class' => 'inputbox', 'data-validation' => '')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Date format for plugin', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Currency', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('job_currency', wpjobportal::$_data[0]['job_currency'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Currency', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /*
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Mark Job New', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('newdays', wpjobportal::$_data[0]['newdays'], array('class' => 'inputbox not-full-width')),WPJOBPORTAL_ALLOWED_TAGS); ?> <?php echo esc_html(__('Days', 'wp-job-portal')); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('How many days system show New tag', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        */?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Image file extensions', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('image_file_type', wpjobportal::$_data[0]['image_file_type'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Add image allowed extensions', 'wp-job-portal')) .'. '. esc_html(__('Must be comma separated', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Image file size', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('image_file_size', wpjobportal::$_data[0]['image_file_size'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    KB
                                                </div>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('System will not upload if image size is greater than the given size', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /*
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('User can add city in database', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('newtyped_cities', $yesno, wpjobportal::$_data[0]['newtyped_cities']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('User can add new city in the system', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        */ ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Maximum record for city field', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('number_of_cities_for_autocomplete', wpjobportal::$_data[0]['number_of_cities_for_autocomplete'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Set number of cities to show in result of the location input box', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(in_array('tag', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('User can add tag in database', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('newtyped_tags', $yesno, wpjobportal::$_data[0]['newtyped_tags']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('User can add new tags in the system', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Maximum record for tag field', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('number_of_tags_for_autocomplete', wpjobportal::$_data[0]['number_of_tags_for_autocomplete'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Set number of tags to show in result of the tag input box', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Currency symbol position', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('currency_align', $leftright, wpjobportal::$_data[0]['currency_align'], '', array('class' => 'inputbox', 'data-validation' => '')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show currency symbol left or right to the amount', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Job types per row', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('jobtype_per_row', wpjobportal::$_data[0]['jobtype_per_row'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show number of job types per row on', 'wp-job-portal')) ." '".esc_html(__('job by type', 'wp-job-portal')) ."' ". esc_html(__('page', 'wp-job-portal')) .'.'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Show WP Job Portal page title', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('show_wpjobportal_page_title', $yesno, wpjobportal::$_data[0]['show_wpjobportal_page_title']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show page title above wpjobportal breadcrumbs', 'wp-job-portal')) ; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- MASSAGES -->
                                    <?php if(in_array('message', wpjobportal::$_active_addons)){ ?>
                                        <div id="message" class="wpjobportal_gen_body">
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Messages', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Message auto approve', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('message_auto_approve', $yesno, wpjobportal::$_data[0]['message_auto_approve']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Auto approve messages for job seeker and employer', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Conflict message auto approve', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('conflict_message_auto_approve', $yesno, wpjobportal::$_data[0]['conflict_message_auto_approve']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Auto approve conflicted messages for job seeker and employer', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php  } ?>
                                    <!-- DEFAULT SETTINGS -->
                                    <div id="defaul_setting" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Default Settings', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Default page', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('default_pageid', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(), wpjobportal::$_data[0]['default_pageid']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Select WP JOB PORTAL default page, on action system will redirect on selected page. If not select default page, email links and support icon might not work.', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Default address display style', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('defaultaddressdisplaytype', $defaultaddressdisplaytype, wpjobportal::$_data[0]['defaultaddressdisplaytype']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Employer default role', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('employer_defaultgroup', $userroles, wpjobportal::$_data[0]['employer_defaultgroup']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('This role will auto assign to new employer','wp-job-portal'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Job Seeker default role', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('jobseeker_defaultgroup', $userroles, wpjobportal::$_data[0]['jobseeker_defaultgroup']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('This role will auto assign to new job seeker','wp-job-portal'));?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Default Pagination size', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('pagination_default_page_size', wpjobportal::$_data[0]['pagination_default_page_size'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Maximum number of records per page', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Default Image For WP Job Portal', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <input id="default_image" name="default_image" type="file">
                                                <input id="remove_default_image" name="remove_default_image" type="hidden" value="0">
                                                <div class="wpjobportal-config-description wpjobportal-config-default-image-wrap ">
                                                    <?php
                                                    if (isset(wpjobportal::$_data[0]['default_image']) && wpjobportal::$_data[0]['default_image'] != "") {
                                                        $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('data_directory');
                                                        $wpdir = wp_upload_dir();
                                                        $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/default_image/' . wpjobportal::$_data[0]['default_image'];
                                                        $class = '';
                                                    }else{
                                                        $path = '';
                                                        $class = 'none';
                                                    }?>
                                                    <div class="wjportal-form-image-wrp" style="display:<?php echo esc_attr($class); ?> ;">
                                                        <img class="wpjobportal-config-default-image" src="<?php echo esc_url($path); ?>" id="rs_photo" />
                                                        <img id="wjportal-form-delete-image" src="<?php echo esc_html(WPJOBPORTAL_PLUGIN_URL);?>includes/images/no.png" alt="<?php echo esc_html(__('cross','wp-job-portal')); ?>">
                                                    </div>
                                                    <?php
                                                    $logoformat = wpjobportal::$_config->getConfigValue('image_file_type');
                                                    $maxsize = wpjobportal::$_config->getConfigValue('image_file_size');
                                                    echo '<div class="wjportal-form-help-txt">'.esc_html($logoformat).'</div>';
                                                    echo '<div class="wjportal-form-help-txt">'.esc_html(__("Maximum","wp-job-portal")).' '.esc_html($maxsize).' Kb'.'</div>';
                                                    ?>

                                                    <?php echo esc_html(__('This image will shown as default image for entities & users if no other image is provided.', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php /* this configuration is not in use
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Default country', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('default_country', WPJOBPORTALincluder::getJSModel('country')->getCountriesForCombo(), wpjobportal::$_data[0]['default_country']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Select default country for user added cities', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div> */ ?>
                                    </div>
                                    <!-- CATEGORIES -->
                                    <div id="categories" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Categories', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Show All Categories', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('job_resume_show_all_categories', $yesno, wpjobportal::$_data[0]['job_resume_show_all_categories']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('If no is selected then only categories that have jobs or resumes will be shown on jobs,resumes by categories layout', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Categories per row', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('categories_colsperrow', wpjobportal::$_data[0]['categories_colsperrow'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show number of categories per row in', 'wp-job-portal'))." '".esc_html(__('job/resume by category', 'wp-job-portal'))."' ". esc_html(__('page', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Sub-categories limit', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('subcategory_limit', wpjobportal::$_data[0]['subcategory_limit'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('How many sub categories show in popup on', 'wp-job-portal'))." '" .esc_html(__('job/resume by category', 'wp-job-portal'))."' ". esc_html(__('page', 'wp-job-portal')) .'.'; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- EMAILS -->
                                    <div id="email" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Email', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Sender email address', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('mailfromaddress', wpjobportal::$_data[0]['mailfromaddress'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Email address that will be used to send emails', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Sender name', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('mailfromname', wpjobportal::$_data[0]['mailfromname'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Sender name that will be used in emails', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Admin email address', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('adminemailaddress', wpjobportal::$_data[0]['adminemailaddress'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Admin will receive email notifications on this address', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- MAP -->
                                    <?php if(in_array('addressdata', wpjobportal::$_active_addons)){ ?>
                                        <div id="googlemapadsense" class="wpjobportal_gen_body">
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Map', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Map height', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('mapheight', wpjobportal::$_data[0]['mapheight'], array('class' => 'inputbox not-full-width')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Set map height for plugin','wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="full_background" style="display:none;" onclick="hidediv();"></div>
                                            <div id="popup_main" style="display:none;width:70%; height:<?php echo esc_attr(wpjobportal::$_configuration['mapheight']) + 70; ?>px">
                                                <span class="popup-top">
                                                    <span id="popup_title" >
                                                        <?php echo esc_html(__('Map', 'wp-job-portal')); ?>
                                                    </span>
                                                    <img id="popup_cross" onclick="hidediv();" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/popup-close.png">
                                                </span>
                                                <div id="map" style="width:100%; height:<?php echo esc_attr(wpjobportal::$_configuration['mapheight']); ?>px">
                                                    <div id="map_container">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Map', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <a href="Javascript: showdiv();loadMap();">
                                                        <?php echo esc_html(__('Show Map', 'wp-job-portal')); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Google Map API key', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('google_map_api_key', wpjobportal::$_data[0]['google_map_api_key'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Get API key from','wp-job-portal')); ?>
                                                        <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">
                                                            <?php echo esc_html(__('here','wp-job-portal')); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Mapping Service', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('mappingservice', $mappingservices, wpjobportal::$_data[0]['mappingservice'], esc_html(__("Select",'wp-job-portal'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Default longitude', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('default_longitude', wpjobportal::$_data[0]['default_longitude'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Default latitude', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('default_latitude', wpjobportal::$_data[0]['default_latitude'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Default map radius type', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('defaultradius', $defaultradius, wpjobportal::$_data[0]['defaultradius']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- OFFLINE -->
                                    <div id="offline" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Offline', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Offline', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('offline', $yesno, wpjobportal::$_data[0]['offline']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Offline Message', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value full-width">
                                                <?php wp_editor(wpjobportal::$_data[0]['offline_text'], 'offline_text', array('media_buttons' => false)); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- TERMS AND CONDITIONS -->
                                    <div id="terms" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Term And Conditions', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Company terms and conditions page', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('terms_and_conditions_page_company', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(), wpjobportal::$_data[0]['terms_and_conditions_page_company']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Select terms and conditions page for company', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Job terms and conditions page', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('terms_and_conditions_page_job', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(), wpjobportal::$_data[0]['terms_and_conditions_page_job']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Select terms and conditions page for job', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Resume terms and conditions page', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('terms_and_conditions_page_resume', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(), wpjobportal::$_data[0]['terms_and_conditions_page_resume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Select terms and conditions page for Resume', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- URL SETTTINGS -->
                                    <div id="url-settings" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('URL Settings', 'wp-job-portal')); ?>
                                        </h3>

                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Job Detail URL', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('job_seo', wpjobportal::$_data[0]['job_seo'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Job detail URL options are title, company, category, location, jobtype. eg- [title] [company]', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Company Detail URL', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('company_seo', wpjobportal::$_data[0]['company_seo'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Company detail url options are name, category, location. eg- [name] [category] [location]', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Resume Detail URL', 'wp-job-portal')); ?>

                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('resume_seo', wpjobportal::$_data[0]['resume_seo'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Resume detail URL options are title, category, location. eg- [title] [location]', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- VISITOR SETTINGS -->
                            <div id="visitor_setting" class="wpjobportal-hide-config">
                                <ul>
                                    <li class="ui-tabs-active">
                                        <a href="#captcha_setting">
                                            <?php echo esc_html(__('Captcha Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#visitor_setting_employer_side">
                                            <?php echo esc_html(__('Employer Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#js_visitor">
                                            <?php echo esc_html(__('Jobseeker Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#emp_visitorlinks">
                                            <?php echo esc_html(__('Employer Links', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#js_memberlinks">
                                            <?php echo esc_html(__('Jobseeker Links', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tabInner">
                                    <!-- CAPTCHA SETTING -->
                                    <div id="captcha_setting" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Captcha Setting', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Show captcha on registration form', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('cap_on_reg_form', $yesno, wpjobportal::$_data[0]['cap_on_reg_form'], '', array('class' => 'inputbox', 'data-validation' => '')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show captcha on WP JOB PORTAL registration form','wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('default captcha', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('captcha_selection', $captchalist, wpjobportal::$_data[0]['captcha_selection']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Select captcha for plugin', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <h3 class="wpjobportal-config-heading-main wpjobportal-inner-heading">
                                            <?php echo esc_html(__('Default Captcha', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('WP JOB PORTAL captcha calculation type', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('owncaptcha_calculationtype', $captchacalculation, wpjobportal::$_data[0]['owncaptcha_calculationtype']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Select calculation type (addition, subtraction)', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('WP JOB PORTAL captcha answer always positive', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('owncaptcha_subtractionans', $yesno, wpjobportal::$_data[0]['owncaptcha_subtractionans']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Subtraction answer should be positive', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Number of operands for WP JOB PORTAL captcha', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('owncaptcha_totaloperand', $captchaop, wpjobportal::$_data[0]['owncaptcha_totaloperand']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Number of operands for captcha', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <h3 class="wpjobportal-config-heading-main wpjobportal-inner-heading">
                                            <?php echo esc_html(__('Google reCaptcha', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row google-recaptcha">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Google reCaptcha Version', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('recaptcha_version', $recaptcha_version, wpjobportal::$_data[0]['recaptcha_version']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Select the Google reCaptcha Version','wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row google-recaptcha">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Google reCaptcha Site Key', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('recaptcha_publickey', wpjobportal::$_data[0]['recaptcha_publickey'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Enter the Google reCaptcha site key from','wp-job-portal')).' &nbsp; '.'https://www.google.com/recaptcha/admin'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row google-recaptcha">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Google reCaptcha Private Key', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('recaptcha_privatekey', wpjobportal::$_data[0]['recaptcha_privatekey'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Enter the Google reCaptcha private key from','wp-job-portal')) .' &nbsp; '.'https://www.google.com/recaptcha/admin' ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- VISITOR SETTING EMPLOYER SETTING -->
                                    <div id="visitor_setting_employer_side" class="wpjobportal_gen_body">
                                        <?php if(in_array('visitorcanaddjob', wpjobportal::$_active_addons)){ ?>
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Job Posting Options', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Visitor can post job', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_can_post_job', $yesno, wpjobportal::$_data[0]['visitor_can_post_job']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Visitor can post a job', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Allow edit job', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_can_edit_job', $yesno, wpjobportal::$_data[0]['visitor_can_edit_job']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Visitor can edit his posted job', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show captcha', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('job_captcha', $yesno, wpjobportal::$_data[0]['job_captcha']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show captcha on visitor form job', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Visitor post job redirect page ', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_add_job_redirect_page', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(), wpjobportal::$_data[0]['visitor_add_job_redirect_page']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('whenever any visitor posts a job, he will be redirected to this page', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <h3 class="wpjobportal-config-heading-main wpjobportal-inner-heading">
                                            <?php echo esc_html(__('Visitors Can View Employer', 'wp-job-portal')); ?>
                                        </h3>
                                        <?php if(in_array('resumesearch', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Resume Search', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_emp_resumesearch', $showhide, wpjobportal::$_data[0]['visitorview_emp_resumesearch']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('View Resume', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_emp_viewresume', $showhide, wpjobportal::$_data[0]['visitorview_emp_viewresume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Resume Categories', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_emp_resumecat', $showhide, wpjobportal::$_data[0]['visitorview_emp_resumecat']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- VISITOR SETTING JOBSEEKER SIDE -->
                                    <div id="js_visitor" class="wpjobportal_gen_body">
                                        <?php if(in_array('tellfriend',wpjobportal::$_active_addons) || in_array('visitorapplyjob', wpjobportal::$_active_addons)){ ?>
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Job Seeker', 'wp-job-portal')); ?>
                                            </h3>
                                        <?php } ?>
                                        <?php if(in_array('visitorapplyjob', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Visitor can apply to job', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_can_apply_to_job', $yesno, wpjobportal::$_data[0]['visitor_can_apply_to_job']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('visitorapplyjob', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Visitor can add resume', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_can_add_resume', $yesno, wpjobportal::$_data[0]['visitor_can_add_resume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('visitorapplyjob', wpjobportal::$_active_addons)){ ?>
                                            <?php  /*
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show login message to visitor', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_show_login_message', $yesno, wpjobportal::$_data[0]['visitor_show_login_message']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show login option to visitor on job apply', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            */?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Visitor post resume redirect page ', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_add_resume_redirect_page', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(), wpjobportal::$_data[0]['visitor_add_resume_redirect_page']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('whenever any visitor posts a resume, he will be redirected to this page', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('visitorapplyjob', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show captcha on resume form', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('resume_captcha', $yesno, wpjobportal::$_data[0]['resume_captcha']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show captcha on visitor form resume', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('tellfriend',wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show captcha on Job alert form', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('job_alert_captcha', $yesno, wpjobportal::$_data[0]['job_alert_captcha']),WPJOBPORTAL_ALLOWED_TAGS); ?><br clear="all"/>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show captcha visitor job alert form', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('tellfriend',wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show captcha on tell a friend popup', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('tell_a_friend_captcha', $yesno, wpjobportal::$_data[0]['tell_a_friend_captcha']),WPJOBPORTAL_ALLOWED_TAGS); ?><br clear="all"/>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show captcha on visitor tell a friend popup', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('jobalert', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Job alert for visitor', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('overwrite_jobalert_settings', $yesno, wpjobportal::$_data[0]['overwrite_jobalert_settings']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <h3 class="wpjobportal-config-heading-main wpjobportal-inner-heading">
                                            <?php echo esc_html(__('Visitors Can View Job seeker', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Control Panel', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_js_controlpanel', $showhide, wpjobportal::$_data[0]['visitorview_js_controlpanel']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('View company', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_emp_viewcompany', $showhide, wpjobportal::$_data[0]['visitorview_emp_viewcompany']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('View Job', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_emp_viewjob', $showhide, wpjobportal::$_data[0]['visitorview_emp_viewjob']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Jobs By Categories', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_js_jobcat', $showhide, wpjobportal::$_data[0]['visitorview_js_jobcat']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Newest jobs', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_js_newestjobs', $showhide, wpjobportal::$_data[0]['visitorview_js_newestjobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Search job', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_js_jobsearch', $showhide, wpjobportal::$_data[0]['visitorview_js_jobsearch']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Job search result', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_js_jobsearchresult', $showhide, wpjobportal::$_data[0]['visitorview_js_jobsearchresult']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- VISITOER LINKS AS EMPLOYER -->
                                    <div id="emp_visitorlinks" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Control Panel', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Control Panel', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('visitorview_emp_conrolpanel', $showhide, wpjobportal::$_data[0]['visitorview_emp_conrolpanel']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Enable disable control panel for visitor', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($theme_chk == 0){ ?>
                                        <?php } else { ?>
                                            <h3 class="wpjobportal-config-heading-main wpjobportal-inner-heading">
                                                <?php echo esc_html(__('Employer Dashboard', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Stats Graph', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_employer_dashboard_stats_graph', $showhide, wpjobportal::$_data[0]['vis_temp_employer_dashboard_stats_graph']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Useful Links','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_employer_dashboard_useful_links', $showhide, wpjobportal::$_data[0]['vis_temp_employer_dashboard_useful_links']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Applied Resume', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_employer_dashboard_applied_resume', $showhide, wpjobportal::$_data[0]['vis_temp_employer_dashboard_applied_resume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Saved Search', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_employer_dashboard_saved_search', $showhide, wpjobportal::$_data[0]['vis_temp_employer_dashboard_saved_search']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Invoice', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_employer_dashboard_purchase_history', $showhide, wpjobportal::$_data[0]['vis_temp_employer_dashboard_purchase_history']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Newest Resume', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_employer_dashboard_newest_resume', $showhide, wpjobportal::$_data[0]['vis_temp_employer_dashboard_newest_resume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <h3 class="wpjobportal-config-heading-main wpjobportal-inner-heading">
                                            <?php echo esc_html(__('Employer Control Panel Links', 'wp-job-portal')); ?>
                                        </h3>
                                        <?php if($theme_chk == 0){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Jobs Graph', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jobs_graph', $showhide, wpjobportal::$_data[0]['vis_jobs_graph']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Recent Resumes Box', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_ememployerresumebox', $showhide, wpjobportal::$_data[0]['vis_ememployerresumebox']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('My Companies', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emmycompanies', $showhide, wpjobportal::$_data[0]['vis_emmycompanies']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emformcompany', $showhide, wpjobportal::$_data[0]['vis_emformcompany']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('My Jobs', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emmyjobs', $showhide, wpjobportal::$_data[0]['vis_emmyjobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emformjob', $showhide, wpjobportal::$_data[0]['vis_emformjob']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <?php /*
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Applied Resume', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emalljobsappliedapplications', $showhide, wpjobportal::$_data[0]['vis_emalljobsappliedapplications']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        */?>
                                        <?php if(in_array('resumesearch', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Resume Search', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emresumesearch', $showhide, wpjobportal::$_data[0]['vis_emresumesearch']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('resumesearch', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Saved Searches', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emmy_resumesearches', $showhide, wpjobportal::$_data[0]['vis_emmy_resumesearches']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('departments', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Departments', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emmydepartment', $showhide, wpjobportal::$_data[0]['vis_emmydepartment']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('departments', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row" class="wpjobportal_gen_body">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Department', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emformdepartment', $showhide, wpjobportal::$_data[0]['vis_emformdepartment']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('folder', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Folders', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emmyfolders', $showhide, wpjobportal::$_data[0]['vis_emmyfolders']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Folder', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emnewfolders', $showhide, wpjobportal::$_data[0]['vis_emnewfolders']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Invoice', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emppurchasehistory', $showhide, wpjobportal::$_data[0]['vis_emppurchasehistory']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Subscriptions', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_empratelist', $showhide, wpjobportal::$_data[0]['vis_empratelist']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Packages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_empcreditlog', $showhide, wpjobportal::$_data[0]['vis_empcreditlog']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Packages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_empcredits', $showhide, wpjobportal::$_data[0]['vis_empcredits']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php  } ?>
                                        <?php if(in_array('message', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Messages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emmessages', $showhide, wpjobportal::$_data[0]['vis_emmessages']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('rssfeedback', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Resume RSS', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_resume_rss', $showhide, wpjobportal::$_data[0]['vis_resume_rss']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Register', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emempregister', $showhide, wpjobportal::$_data[0]['vis_emempregister']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Resume By Categories', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_emresumebycategory', $showhide,wpjobportal::$_data[0]['vis_emresumebycategory']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- VISITOR LINKS AS JOB SEEKER -->
                                    <div id="js_memberlinks" class="wpjobportal_gen_body">
                                        <?php if($theme_chk == 0){ ?>
                                        <?php }else{ ?>
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Job Seeker Dashboard','wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Jobs Graph', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_jobseeker_dashboard_jobs_graph', $showhide, wpjobportal::$_data[0]['temp_jobseeker_dashboard_jobs_graph']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Useful Links', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_jobseeker_dashboard_useful_links', $showhide, wpjobportal::$_data[0]['temp_jobseeker_dashboard_useful_links']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Applied jobs', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_jobseeker_dashboard_apllied_jobs', $showhide, wpjobportal::$_data[0]['temp_jobseeker_dashboard_apllied_jobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Shortlisted Jobs', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_jobseeker_dashboard_shortlisted_jobs', $showhide, wpjobportal::$_data[0]['temp_jobseeker_dashboard_shortlisted_jobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Invoice', 'wp-job-portal')); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_jobseeker_dashboard_purchase_history', $showhide, wpjobportal::$_data[0]['temp_jobseeker_dashboard_purchase_history']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Newest Jobs', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_temp_jobseeker_dashboard_newest_jobs', $showhide, wpjobportal::$_data[0]['temp_jobseeker_dashboard_newest_jobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Job Seeker Control Panel Links','wp-job-portal')); ?>
                                        </h3>
                                        <?php if($theme_chk == 0){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Active Jobs Graph', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsactivejobs_graph', $showhide, wpjobportal::$_data[0]['vis_jsactivejobs_graph']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Newsest Jobs Box', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsjobseekernewestjobs', $showhide, wpjobportal::$_data[0]['vis_jsjobseekernewestjobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('My Resumes', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsmyresumes', $showhide, wpjobportal::$_data[0]['vis_jsmyresumes']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Add','wp-job-portal')) .' '. esc_html(__('Resume', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsformresume', $showhide, wpjobportal::$_data[0]['vis_jsformresume']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Jobs By Categories', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_wpjobportalcat', $showhide, wpjobportal::$_data[0]['vis_wpjobportalcat']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Newest Jobs', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jslistnewestjobs', $showhide, wpjobportal::$_data[0]['vis_jslistnewestjobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Jobs By Types', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jslistjobbytype', $showhide, wpjobportal::$_data[0]['vis_jslistjobbytype']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <?php if(in_array('multicompany', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('All Companies', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jslistallcompanies', $showhide, wpjobportal::$_data[0]['vis_jslistallcompanies']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Jobs By Cities', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jobsbycities', $showhide, wpjobportal::$_data[0]['vis_jobsbycities']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>

                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('My Applied Jobs', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsmyappliedjobs', $showhide, wpjobportal::$_data[0]['vis_jsmyappliedjobs']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Invoice', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jspurchasehistory', $showhide, wpjobportal::$_data[0]['vis_jspurchasehistory']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Subscription', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsratelist', $showhide, wpjobportal::$_data[0]['vis_jsratelist']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Packages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jscreditlog', $showhide, wpjobportal::$_data[0]['vis_jscreditlog']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Packages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jscredits', $showhide, wpjobportal::$_data[0]['vis_jscredits']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('coverletter', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('My Cover Letters', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsmycoverletter', $showhide, wpjobportal::$_data[0]['vis_jsmycoverletter']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Add Cover Letter', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsformcoverletter', $showhide, wpjobportal::$_data[0]['vis_jsformcoverletter']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Search Job', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_wpjobportalearch', $showhide, wpjobportal::$_data[0]['vis_wpjobportalearch']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <?php if(in_array('resumeserach', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Saved Searches', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsmy_jobsearches', $showhide, wpjobportal::$_data[0]['vis_jsmy_jobsearches']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('jobalert', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Job Alert', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_wpjobportalalertsetting', $showhide, wpjobportal::$_data[0]['vis_wpjobportalalertsetting']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('message', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Messages', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsmessages', $showhide, wpjobportal::$_data[0]['vis_jsmessages']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('rssfeedback', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Jobs RSS', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_job_rss', $showhide, wpjobportal::$_data[0]['vis_job_rss']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Register', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jsregister', $showhide, wpjobportal::$_data[0]['vis_jsregister']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                            </div>
                                        </div>
                                        <?php if(in_array('shortlist', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Short Listed Jobs', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('vis_jslistjobshortlist', $showhide, wpjobportal::$_data[0]['vis_jslistjobshortlist']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php if(in_array('email', wpjobportal::$_active_addons)){ ?>
                                            <div id="email">
                                                <h3 class="wpjobportal-config-heading-main">
                                                    <?php echo esc_html(__('Applied Resume Alert', 'wp-job-portal')); ?>
                                                </h3>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Applied resume notification', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('jobseeker_resume_applied_status', $yesno, wpjobportal::$_data[0]['jobseeker_resume_applied_status']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Applied resume status change mail to jobseeker', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php  } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- PACKAGE SETTINGS -->
                            <div id="package_setting" class="wpjobportal-hide-config">
                                <ul>
                                    <li class="ui-tabs-active">
                                        <a href="#package">
                                            <?php echo esc_html(__('Package Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#paid_submission">
                                            <?php echo esc_html(__('Paid Submission', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tabInner">
                                    <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                                        <div id="package" class="wpjobportal_gen_body">
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Package Settings', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Auto Assign Free package to new user', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('auto_assign_free_package', $yesno, wpjobportal::$_data[0]['auto_assign_free_package']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('This configuration controls whethre new user will get free package (if free package exsist in the system)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('free package purchase', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('free_package_purchase_only_once', $yesno, wpjobportal::$_data[0]['free_package_purchase_only_once']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('This configuration controls whether user can be free package more than once', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Free Package purchase auto approve', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('free_package_auto_approve', $yesno, wpjobportal::$_data[0]['free_package_auto_approve']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('This configuration controls whether free package will be auto approve or not', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="paid_submission" class="wpjobportal_gen_body">
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Paid Submission', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Submission Type', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('submission_type', $wpjobportalsubmissiontypes, wpjobportal::$_data[0]['submission_type']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title-section">
                                                    <?php echo esc_html(__('Company', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Company', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('company_currency_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['company_currency_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('company_price_perlisting', wpjobportal::$_data[0]['company_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Company', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('company_feature_currency_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['company_feature_currency_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('company_feature_price_perlisting', wpjobportal::$_data[0]['company_feature_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Number of days untill', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Company', 'wp-job-portal'))."</span> ".esc_html(__('expire', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('company_featureexpire_price_perlisting', wpjobportal::$_data[0]['company_featureexpire_price_perlisting'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Company Expiry Days (per-listing)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Number of days untill', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Company', 'wp-job-portal'))."</span> ".esc_html(__('expire', 'wp-job-portal'))." (". esc_html(__('Free', 'wp-job-portal')).")"; ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('company_featureexpire_free', wpjobportal::$_data[0]['company_featureexpire_free'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Company Expiry Days (Free)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title-section">
                                                    <?php echo esc_html(__('Job', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Job', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_currency_price_perlisting', wpjobportal::$_data[0]['job_currency_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Number of days untill', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Job', 'wp-job-portal'))."</span> ".esc_html(__('expire', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('jobexpiry_days_perlisting', wpjobportal::$_data[0]['jobexpiry_days_perlisting'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Job Expiry Days (per-listing)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Number of days untill', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Job', 'wp-job-portal'))."</span> ".esc_html(__('expire', 'wp-job-portal'))." (". esc_html(__('Free', 'wp-job-portal')).")"; ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('jobexpiry_days_free', wpjobportal::$_data[0]['jobexpiry_days_free'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Job Expiry Days (Free)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Job', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_feature_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_feature_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('jobs_feature_price_perlisting', wpjobportal::$_data[0]['jobs_feature_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Number of days untill', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Job', 'wp-job-portal'))."</span> ".esc_html(__('expire', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('featuredjobexpiry_days_perlisting', wpjobportal::$_data[0]['featuredjobexpiry_days_perlisting'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Job Expiry Days (per-listing)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Number of days untill', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Job', 'wp-job-portal'))."</span> ".esc_html(__('expire', 'wp-job-portal'))." (". esc_html(__('Free', 'wp-job-portal')).")"; ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('featuredjobexpiry_days_free', wpjobportal::$_data[0]['featuredjobexpiry_days_free'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Featured Job Expiry Days (Free)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title-section">
                                                    <?php echo esc_html(__('Resume', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Resume', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_resume_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_resume_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_resume_price_perlisting', wpjobportal::$_data[0]['job_resume_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Resume', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_featureresume_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_featureresume_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_featureresume_price_perlisting', wpjobportal::$_data[0]['job_featureresume_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Number of days untill', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Resume', 'wp-job-portal'))."</span> ".esc_html(__('expire', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('job_resume_days_perlisting', wpjobportal::$_data[0]['job_resume_days_perlisting'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Featured Resume Expiry Days (per-listing)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Number of days untill', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Featured Resume', 'wp-job-portal'))."</span> ".esc_html(__('expire', 'wp-job-portal'))." (". esc_html(__('Free', 'wp-job-portal')).")"; ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('job_resume_days_free', wpjobportal::$_data[0]['job_resume_days_free'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Featured Resume Expiry Days (Free)', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php /*
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Resume Save Search', 'wp-job-portal'))."</span> "." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_resumesavesearch_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_resumesavesearch_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_resumesavesearch_price_perlisting', wpjobportal::$_data[0]['job_resumesavesearch_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            */ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title-section">
                                                    <?php echo esc_html(__('Miscellaneous', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Job Apply', 'wp-job-portal'))."</span> "." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_jobapply_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_jobapply_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_jobapply_price_perlisting', wpjobportal::$_data[0]['job_jobapply_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Job Alert', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_jobalert_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_jobalert_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_jobalert_price_perlisting', wpjobportal::$_data[0]['job_jobalert_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Resume View Contact Detail', 'wp-job-portal'))."</span> "." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_viewresumecontact_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_viewresumecontact_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_viewresumecontact_price_perlisting', wpjobportal::$_data[0]['job_viewresumecontact_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Company View Contact Detail', 'wp-job-portal'))."</span> "." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_viewcompanycontact_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_viewcompanycontact_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_viewcompanycontact_price_perlisting', wpjobportal::$_data[0]['job_viewcompanycontact_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Department', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_department_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_department_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_department_price_perlisting', wpjobportal::$_data[0]['job_department_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Price per', 'wp-job-portal'))." <span class='wpjobportal-config-title-span'>".esc_html(__('Cover Letter', 'wp-job-portal'))."</span> ".esc_html(__('submission', 'wp-job-portal'))." (". esc_html(__('only for', 'wp-job-portal')).' "'. esc_html(__('per listing', 'wp-job-portal')).'" '. esc_html(__('mode', 'wp-job-portal')).")"; ?>
                                                </div>
                                                <div class="wpjobportal-config-value wpjobportal-config-2-fields">
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('job_currency_coverletter_perlisting',WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), wpjobportal::$_data[0]['job_currency_coverletter_perlisting'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-inner-fields">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('job_coverletter_price_perlisting', wpjobportal::$_data[0]['job_coverletter_price_perlisting'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- SOCIAL SHARE -->
                            <div id="social_share" class="wpjobportal-hide-config">
                                <ul>
                                    <?php if(in_array('socialshare', wpjobportal::$_active_addons)){ ?>
                                        <li class="ui-tabs-active">
                                            <a href="#socialsharing">
                                                <?php echo esc_html(__('Social Links', 'wp-job-portal')); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                    <?php if(in_array('sociallogin', wpjobportal::$_active_addons)){ ?>
                                        <li>
                                            <a href="#facebook">
                                                <?php echo esc_html(__('Facebook', 'wp-job-portal')); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#linkedin">
                                                <?php echo esc_html(__('Linkedin', 'wp-job-portal')); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <div class="tabInner">
                                    <!-- SOCIAL LINKS -->
                                    <?php if(in_array('socialshare', wpjobportal::$_active_addons)){ ?>
                                        <div id="socialsharing" class="wpjobportal_gen_body">
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Social Links', 'wp-job-portal')) ?>

                                            </h3>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_fb_like', $social, (wpjobportal::$_data[0]['employer_share_fb_like'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Facebook likes', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_fb_share', $social, (wpjobportal::$_data[0]['employer_share_fb_share'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Facebook share', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_fb_comments', $social, (wpjobportal::$_data[0]['employer_share_fb_comments'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Facebook comments', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <?php
                                            /*
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_google_like', $social, (wpjobportal::$_data[0]['employer_share_google_like'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Google likes', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_google_share', $social, (wpjobportal::$_data[0]['employer_share_google_share'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Google share', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            */?>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_blog_share', $social, (wpjobportal::$_data[0]['employer_share_blog_share'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Blogger', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_linkedin_share', $social, (wpjobportal::$_data[0]['employer_share_linkedin_share'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Linkedin', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_digg_share', $social, (wpjobportal::$_data[0]['employer_share_digg_share'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Digg', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_twitter_share', $social, (wpjobportal::$_data[0]['employer_share_twitter_share'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Twitter', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_myspace_share', $social, (wpjobportal::$_data[0]['employer_share_myspace_share'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Myspace', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                            <div class="wpjobportal-config-social-share-row">
                                                <label>
                                                    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('employer_share_yahoo_share', $social, (wpjobportal::$_data[0]['employer_share_yahoo_share'] == 1) ? 1 : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <?php echo esc_html(__('Yahoo', 'wp-job-portal')); ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!-- SOCIAL MEDIA -->
                                    <?php if(in_array('sociallogin', wpjobportal::$_active_addons)){ ?>
                                        <div id="socialmedia">
                                            <div id="facebook" class="wpjobportal_gen_body">
                                                <h3 class="wpjobportal-config-heading-main">
                                                    <?php echo esc_html(__('Facebook', 'wp-job-portal')); ?>

                                                </h3>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Login with facebook', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('loginwithfacebook', $yesno, wpjobportal::$_data[0]['loginwithfacebook']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('Facebook user can login in WP JOB PORTAL', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Job apply with facebook', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('applywithfacebook', $yesno, wpjobportal::$_data[0]['applywithfacebook']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('Facebook user can apply to jobs', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('API key', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('apikeyfacebook', wpjobportal::$_data[0]['apikeyfacebook'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('API key is required for facebook app', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Secret', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('clientsecretfacebook', wpjobportal::$_data[0]['clientsecretfacebook'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('Client secret here', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php 
                                                    $pageid = wpjobportal::$_db->get_var("SELECT configvalue FROM `".wpjobportal::$_db->prefix."wj_portal_config` WHERE configname = 'default_pageid'");
                                                    $url_fb_apply = site_url("?page_id=".$pageid."&wpjobportalme=sociallogin&action=wpjobportaltask&task=jobapplysocial&media=facebook");
                                                    $url_fb_login = site_url("?page_id=".$pageid."&wpjobportalme=sociallogin&action=wpjobportaltask&task=sociallogin&media=facebook");
                                                    $url_li_apply = site_url("?page_id=".$pageid."&wpjobportalme=sociallogin&action=wpjobportaltask&task=jobapplysocial&media=linkedin");
                                                    $url_li_login = site_url("?page_id=".$pageid."&wpjobportalme=sociallogin&action=wpjobportaltask&task=sociallogin&media=linkedin");
                                                ?>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Valid OAuth redirect URI for social login', 'wp-job-portal')); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($url_fb_login)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Valid OAuth redirect URI for social apply', 'wp-job-portal')); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($url_fb_apply)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="linkedin" class="wpjobportal_gen_body">
                                                <h3 class="wpjobportal-config-heading-main">
                                                    <?php echo esc_html(__('Linkedin', 'wp-job-portal')); ?>

                                                </h3>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Login with linkedin', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('loginwithlinkedin', $yesno, wpjobportal::$_data[0]['loginwithlinkedin']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('Linkedin user can login in WP JOB PORTAL', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Job apply with linkedin', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('applywithlinkedin', $yesno, wpjobportal::$_data[0]['applywithlinkedin']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('Linkedin user can apply to jobs', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('API key', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('apikeylinkedin', wpjobportal::$_data[0]['apikeylinkedin'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('API key is required for linkedin app', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Secret', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('clientsecretlinkedin', wpjobportal::$_data[0]['clientsecretlinkedin'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('App secret here', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Valid OAuth redirect URI for social login', 'wp-job-portal')); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($url_li_login)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Valid OAuth redirect URI for social apply', 'wp-job-portal')); ?>
                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($url_li_apply)); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php /*
                                            <div id="xing" class="wpjobportal_gen_body">
                                                <h3 class="wpjobportal-config-heading-main">
                                                    <?php echo esc_html(__('Xing', 'wp-job-portal')); ?>
                                                </h3>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Login with xing', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('loginwithxing', $yesno, wpjobportal::$_data[0]['loginwithxing']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('Xing user can login in WP JOB PORTAL', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Job apply with xing', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::select('applywithxing', $yesno, wpjobportal::$_data[0]['applywithxing']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('Xing user can apply to jobs', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('API key', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('apikeyxing', wpjobportal::$_data[0]['apikeyxing'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                        <div class="wpjobportal-config-description">
                                                            <?php echo esc_html(__('API key is required for xing app', 'wp-job-portal')); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="wpjobportal-config-row">
                                                    <div class="wpjobportal-config-title">
                                                        <?php echo esc_html(__('Secret', 'wp-job-portal')); ?>

                                                    </div>
                                                    <div class="wpjobportal-config-value">
                                                        <?php echo wp_kses(WPJOBPORTALformfield::text('clientsecretxing', wpjobportal::$_data[0]['clientsecretxing'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            */?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- RSS SETTING -->
                            <div id="rss_setting" class="wpjobportal-hide-config">
                                <ul>
                                    <li class="ui-tabs-active">
                                        <a href="#rssjob">
                                            <?php echo esc_html(__('Job Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#rssresume">
                                            <?php echo esc_html(__('Resume Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tabInner">
                                    <?php if(in_array('rssfeedback', wpjobportal::$_active_addons)){ ?>
                                        <div id="rssjob" class="wpjobportal_gen_body">
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Job Settings', 'wp-job-portal')); ?>

                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Jobs RSS', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('job_rss', $showhide, wpjobportal::$_data[0]['job_rss']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Title', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_job_title', wpjobportal::$_data[0]['rss_job_title'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Must provide title for job feed', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Description', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::textarea('rss_job_description', wpjobportal::$_data[0]['rss_job_description']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Must provide description for job feed', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Copyright', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_job_copyright', wpjobportal::$_data[0]['rss_job_copyright'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Leave blank to hide', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Editor', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_job_editor', wpjobportal::$_data[0]['rss_job_editor'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Leave blank to hide editor used for feed content issue', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Time to live', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_job_ttl', wpjobportal::$_data[0]['rss_job_ttl'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Time to live for job feed', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Webmaster', 'wp-job-portal')); ?>

                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_job_webmaster', wpjobportal::$_data[0]['rss_job_webmaster'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Leave blank to hide webmaster used for technical issue', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="wpjobportal-config-heading-main wpjobportal-inner-heading">
                                                <?php echo esc_html(__('Job block', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show with categories', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('rss_job_categories', $showhide, wpjobportal::$_data[0]['rss_job_categories']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Use rss categories with our job categories', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Company image', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('rss_job_image', $showhide, wpjobportal::$_data[0]['rss_job_image']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show company logo with job feeds', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="rssresume" class="wpjobportal_gen_body">
                                            <h3 class="wpjobportal-config-heading-main">
                                                <?php echo esc_html(__('Resume Settings', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Resume RSS', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('resume_rss', $showhide, wpjobportal::$_data[0]['resume_rss']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Title', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_resume_title', wpjobportal::$_data[0]['rss_resume_title'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Must provide title for resume feed', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Description', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::textarea('rss_resume_description', wpjobportal::$_data[0]['rss_resume_description']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Must provide description for resume feed', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Webmaster', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_resume_webmaster', wpjobportal::$_data[0]['rss_resume_webmaster'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Leave blank to hide webmaster used for technical issue', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Editor', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_resume_editor', wpjobportal::$_data[0]['rss_resume_editor'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Leave blank to hide editor used for feed content issue', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Time to live', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_resume_ttl', wpjobportal::$_data[0]['rss_resume_ttl'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Time to live for resume feed', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Copyright', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('rss_resume_copyright', wpjobportal::$_data[0]['rss_resume_copyright'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Leave blank to hide', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="wpjobportal-config-heading-main wpjobportal-inner-heading">
                                                <?php echo esc_html(__('Resume block', 'wp-job-portal')); ?>
                                            </h3>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show with categories', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('rss_resume_categories', $showhide, wpjobportal::$_data[0]['rss_resume_categories']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Use rss categories with our resume categories', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show resume file', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('rss_resume_file', $showhide, wpjobportal::$_data[0]['rss_resume_file']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show resume files to downloadable from feed', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- LOGIN / REGISTER -->
                            <div id="login_register" class="wpjobportal-hide-config">
                                <ul>
                                    <li class="ui-tabs-active">
                                        <a href="#login">
                                            <?php echo esc_html(__('Login', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#register">
                                            <?php echo esc_html(__('Register', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tabInner">
                                    <div id="login" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Login', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Set Login Link', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value wpjobportal-login-redirect-value">
                                                <div class="wpjobportal-config-inner-fields">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('set_login_redirect_link',$loginlinkoptions, wpjobportal::$_data[0]['set_login_redirect_link'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                                <div class="wpjobportal-config-inner-fields login_redirect_link">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('login_redirect_link', wpjobportal::$_data[0]['login_redirect_link'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Set login redirect Link Default or Custom', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Show Log In/Out Button For Employer', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('emploginlogout', $yesno, wpjobportal::$_data[0]['emploginlogout']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show login/logout button on employer control panel', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Show Log In/Out Button For Jobseeker', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('jobsloginlogout', $yesno, wpjobportal::$_data[0]['jobsloginlogout']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show login logout button in job seeker control panel', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Redirect Link For WP User after login', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('loginlinkforwpuser', wpjobportal::$_data[0]['loginlinkforwpuser'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Link To Redirect WP user who does not have a role in WP Job Portal', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="register" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Register', 'wp-job-portal')); ?>
                                        </h3>
                                        <?php $is_enable = get_option('users_can_register');
                                        if(!$is_enable){
                                         ?>
                                            <div id="wpjobportal-registraion-disabled">
                                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/info-icon.png">
                                                <?php echo esc_html(__('Registration is disabled in WordPress settings, please enable to Membership: anyone can register.', 'wp-job-portal')); ?>
                                                <a href="<?php echo esc_url(admin_url('options-general.php'));?>"><?php echo esc_html(__('Settings', 'wp-job-portal')); ?><img style="margin:0 5px;" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/widget-link.png"></a>
                                            </div>
                                        <?php }?>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Set register Link', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value wpjobportal-register-redirect-value">
                                                <div class="wpjobportal-config-inner-fields">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('set_register_redirect_link',$registerlinkoptions, wpjobportal::$_data[0]['set_register_redirect_link'],null,array('class'=>'half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                                <div class="wpjobportal-config-inner-fields register_redirect_link">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::text('register_redirect_link', wpjobportal::$_data[0]['register_redirect_link'],array('class'=>'inputbox half')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Set register redirect Link Default or Custom', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title"><?php echo esc_html(__('Set Employer After Register link', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('employe_set_register_link', $defaultcustom, wpjobportal::$_data[0]['employe_set_register_link']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('New Employer Set After Register link redirect page', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row emp_registerpage_field">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Employer After Registration Redirect Page ', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('register_employer_redirect_page', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(), wpjobportal::$_data[0]['register_employer_redirect_page']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('whenever anyone registers as employer he will be redirected to this page', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row emp_registerlink_field">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Employer After Registration Redirect Link', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('employe_register_link', wpjobportal::$_data[0]['employe_register_link'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Custome Empolyer After Regitser Link', 'wp-job-portal')); ?>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title"><?php echo esc_html(__('Set Job seeker After Register link', 'wp-job-portal')); ?></div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('jobseeker_set_register_link', $defaultcustom, wpjobportal::$_data[0]['jobseeker_set_register_link']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('New Job seeker Set After Register link redirect page', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="wpjobportal-config-row js_registerpage_field">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Job seeker After Registration redirect page ', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('register_jobseeker_redirect_page', WPJOBPORTALincluder::getJSModel('postinstallation')->getPageList(), wpjobportal::$_data[0]['register_jobseeker_redirect_page']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('whenever anyone registers as job seeker, he will be redirected to this page', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row js_registerlink_field">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Job Seeker After Registration Redirect Link', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::text('jobseeker_register_link', wpjobportal::$_data[0]['jobseeker_register_link'], array('class' => 'inputbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Custome Job seeker After Regitser Link', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Job Apply Configuration -->
                            <div id="job_apply" class="wpjobportal-hide-config">
                                <ul>
                                    <li class="ui-tabs-active">
                                        <a href="#quick_apply">
                                            <?php echo esc_html(__('Quick Apply', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#job_apply_settings">
                                            <?php echo esc_html(__('Job Apply', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tabInner">
                                    <div id="quick_apply" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Quick Apply Now', 'wp-job-portal')); ?>
                                        </h3>

                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Enable Quick Apply for user', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('quick_apply_for_user', $yesno, wpjobportal::$_data[0]['quick_apply_for_user']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show the', 'wp-job-portal')).' <b>'. esc_html(__('Quick Apply', 'wp-job-portal')).'</b> '. esc_html(__('form to logged-in users on the job detail page. If this is on,', 'wp-job-portal')).' <b>'.esc_html(__('Apply Now', 'wp-job-portal')).'</b> '.esc_html(__('will be turned off.', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Enable Quick Apply for visitor', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('quick_apply_for_visitor', $yesno, wpjobportal::$_data[0]['quick_apply_for_visitor']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show the', 'wp-job-portal')).' <b>'.esc_html(__('Quick Apply', 'wp-job-portal')).'</b> '.esc_html(__('form to visitor on the job detail page. If this is on,', 'wp-job-portal')).' <b>'.esc_html(__('Apply Now', 'wp-job-portal')).'</b> '.esc_html(__('will be turned off.', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Enable captcha for visitor Quick Apply', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('quick_apply_captcha', $yesno, wpjobportal::$_data[0]['quick_apply_captcha']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Show captcha to visitor on quick apply form.', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div id="job_apply_settings" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main">
                                            <?php echo esc_html(__('Job Apply', 'wp-job-portal')); ?>
                                        </h3>
                                        <div class="wpjobportal-config-row">
                                            <div class="wpjobportal-config-title">
                                                <?php echo esc_html(__('Show apply', 'wp-job-portal')); ?>
                                            </div>
                                            <div class="wpjobportal-config-value">
                                                <?php echo wp_kses(WPJOBPORTALformfield::select('showapplybutton', $yesno, wpjobportal::$_data[0]['showapplybutton']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                <div class="wpjobportal-config-description">
                                                    <?php echo esc_html(__('Controls the visibility of apply in plugin', 'wp-job-portal')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(in_array('visitorapplyjob', wpjobportal::$_active_addons)) { ?>
                                            <?php /*
                                            // this configration was in code twice
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Visitor can apply to job', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_can_apply_to_job', $yesno, wpjobportal::$_data[0]['visitor_can_apply_to_job']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show login message to visitor', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('visitor_show_login_message', $yesno, wpjobportal::$_data[0]['visitor_show_login_message']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show login option to visitor on job apply', 'wp-job-portal')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            */?>
                                        <?php } ?>


                                    </div>
                                </div>
                            </div>
                            <!-- ai addons configs -->

                            <div id="ai_settings" class="wpjobportal-hide-config">
                                <ul>
                                    <li class="ui-tabs-active">
                                        <a href="#ai_settings_tab">
                                            <?php echo esc_html(__('AI Settings', 'wp-job-portal')); ?>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tabInner">
                                    <div id="ai_settings_tab" class="wpjobportal_gen_body">
                                        <h3 class="wpjobportal-config-heading-main" id="aijobsearch">
                                            <?php echo esc_html(__('AI Job Search', 'wp-job-portal')); ?>
                                        </h3>
                                        <?php if(in_array('aijobsearch', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('AI Search On Job Search Page', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('job_search_ai_form', $yesno, wpjobportal::$_data[0]['job_search_ai_form']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show the AI search field on job search form', 'wp-job-portal'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('AI Search Filter On Job Listing', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('job_list_ai_filter', $yesno, wpjobportal::$_data[0]['job_list_ai_filter']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show the AI search field on job listing', 'wp-job-portal'));?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <h3 class="wpjobportal-config-heading-main" id="aisuggestedjobs">
                                            <?php echo esc_html(__('AI Suggested Jobs', 'wp-job-portal')); ?>
                                        </h3>
                                        <?php if(in_array('aisuggestedjobs', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show AI Suggested Jobs Button', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('show_suggested_jobs_button', $yesno, wpjobportal::$_data[0]['show_suggested_jobs_button']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show AI suggested jobs button on my resumes page to job seeker.', 'wp-job-portal'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('AI Suggested Jobs On Dashboard', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('show_suggested_jobs_dashboard', $yesno, wpjobportal::$_data[0]['show_suggested_jobs_dashboard']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show AI suggested jobs on job seeker dashboard.', 'wp-job-portal'));?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }?>

                                        <h3 class="wpjobportal-config-heading-main" id="airesumesearch">
                                            <?php echo esc_html(__('AI Resume Search', 'wp-job-portal')); ?>
                                        </h3>
                                        <?php if(in_array('airesumesearch', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('AI Search On Resume Search Page', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('resume_search_ai_form', $yesno, wpjobportal::$_data[0]['resume_search_ai_form']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show the AI search field on resume search form', 'wp-job-portal'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('AI Search Filter On Resume Listing', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('resume_list_ai_filter', $yesno, wpjobportal::$_data[0]['resume_list_ai_filter']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show the AI search field on resume listing', 'wp-job-portal'));?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <h3 class="wpjobportal-config-heading-main" id="aisuggestedresumes">
                                            <?php echo esc_html(__('AI Suggested Resumes', 'wp-job-portal')); ?>
                                        </h3>
                                        <?php if(in_array('aisuggestedresumes', wpjobportal::$_active_addons)){ ?>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('Show AI Suggested Resumes Button', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('show_suggested_resumes_button', $yesno, wpjobportal::$_data[0]['show_suggested_resumes_button']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show AI suggested resumes button on my jobs page to employer.', 'wp-job-portal'));?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="wpjobportal-config-row">
                                                <div class="wpjobportal-config-title">
                                                    <?php echo esc_html(__('AI Suggested Resumes On Dashboard', 'wp-job-portal')); ?>
                                                </div>
                                                <div class="wpjobportal-config-value">
                                                    <?php echo wp_kses(WPJOBPORTALformfield::select('show_suggested_resumes_dashboard', $yesno, wpjobportal::$_data[0]['show_suggested_resumes_dashboard']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                                                    <div class="wpjobportal-config-description">
                                                        <?php echo esc_html(__('Show AI suggested resumes on employer dashboard.', 'wp-job-portal'));?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('isgeneralbuttonsubmit', 1),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportallt', 'configurations'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'configuration_saveconfiguration'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_configuration_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <div class="wpjobportal-config-btn">
                    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Configuration', 'wp-job-portal')), array('class' => 'button wpjobportal-config-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
        <?php
            $inline_js_script = "
                function hideshowtables(table_id) {
                    var obj = document.getElementById(table_id);
                    var bool = obj.style.display;
                    if (bool == '')
                        obj.style.display = 'none';
                    else
                        obj.style.display = '';
                }
            ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        ?>
        <style>
            div#map_container{
                z-index:1000;
                position:relative;
                background:#000;
                width:100%;
                height:<?php echo esc_attr(wpjobportal::$_configuration['mapheight']) . 'px'; ?>;}
        </style>
        <?php
            $mappingservice = wpjobportal::$_data[0]['mappingservice'];
            // For google map
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            wp_enqueue_script ( 'wpjp-google-map-api' , $protocol . 'maps.googleapis.com/maps/api/js?key=' . wpjobportal::$_data[0]['google_map_api_key']);
            // for street map
            wp_enqueue_script( 'wpjp-osm-js', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/js/ol.min.js' );
            wp_enqueue_style( 'wpjp-osm-css', esc_url(WPJOBPORTAL_PLUGIN_URL).'/includes/css/ol.min.css' );
        ?>
        <?php
            $inline_js_script = "
                osmMap = null;
                function loadMap() {
                    var mappingservice = jQuery('#mappingservice').val();
                    if(mappingservice == 'gmap'){
                        loadGoogleMap();
                        }else if(mappingservice == 'osm'){
                            loadOsmMap();
                        }
                }
                function loadGoogleMap(){
                     var default_latitude = document.getElementById('default_latitude').value;
                    var default_longitude = document.getElementById('default_longitude').value;
                    var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                    zoom = 10;
                    var myOptions = {
                        zoom: zoom,
                        center: latlng,
                        scrollwheel: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    var map = new google.maps.Map(document.getElementById('map_container'), myOptions);
                    var lastmarker = new google.maps.Marker({
                        postiion: latlng,
                        map: map,
                    });
                    var marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                    });
                    marker.setMap(map);
                    lastmarker = marker;
                    google.maps.event.addListener(map, 'click', function (e) {
                        var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                        geocoder = new google.maps.Geocoder();
                        geocoder.geocode({'latLng': latLng}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (lastmarker != '')
                                    lastmarker.setMap(null);
                                var marker = new google.maps.Marker({
                                    position: results[0].geometry.location,
                                    map: map,
                                });
                                marker.setMap(map);
                                lastmarker = marker;
                                document.getElementById('default_latitude').value = marker.position.lat();
                                document.getElementById('default_longitude').value = marker.position.lng();
                            } else {
                                alert(\"". esc_html(__("Geocode was not successful for the following reason", 'wp-job-portal')).": \" + status);
                            }
                        });
                    });
                }
                function loadOsmMap(){
                    var default_latitude = parseFloat(document.getElementById('default_latitude').value);
                        var default_longitude = parseFloat(document.getElementById('default_longitude').value);
                        var coordinate = [default_longitude,default_latitude];
                        if(!osmMap){
                            osmMap = new ol.Map({
                                target: 'map_container',
                                layers: [
                                    new ol.layer.Tile({
                                        source: new ol.source.OSM()
                                    })
                                ],
                            });
                        }
                        osmMap.setView(new ol.View({
                            center: ol.proj.fromLonLat(coordinate),
                            zoom: 10
                        }));
                        osmAddMarker(osmMap, coordinate);
                }
                function showdiv() {
                    document.getElementById('map').style.visibility = 'visible';
                    jQuery('div#full_background').css('display', 'block');
                    jQuery('div#popup_main').slideDown('slow');
                }
                function hidediv() {
                    document.getElementById('map').style.visibility = 'hidden';
                    jQuery('div#popup_main').slideUp('slow');
                    jQuery('div#full_background').hide();
                }
            ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        ?>

    </div>
</div>
