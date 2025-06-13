<?php
if (!defined('ABSPATH'))
die('Restricted Access');
wp_enqueue_script('wpjobportal-res-tables', WPJOBPORTAL_PLUGIN_URL . 'includes/js/responsivetable.js');
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
	<div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <?php
            $msgkey = WPJOBPORTALincluder::getJSModel('city')->getMessagekey();
            WPJOBPORTALMessages::getLayoutMessage($msgkey);
        ?>
        <!-- top bar -->
        <div id="wpjobportal-wrapper-top">
            <div id="wpjobportal-wrapper-top-left">
                <div id="wpjobportal-breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo __("dashboard","wp-job-portal"); ?>">
                                <?php echo __("Dashboard","wp-job-portal"); ?>
                            </a>
                        </li>
                        <li><?php echo __("Location Name Settings","wp-job-portal"); ?></li>
                    </ul>
                </div>
            </div>
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo __("configuration","wp-job-portal"); ?>">
                        <img src="<?php echo WPJOBPORTAL_PLUGIN_URL; ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-help-btn" class="wpjobportal-help-btn">
                    <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo __("help","wp-job-portal"); ?>">
                        <img src="<?php echo WPJOBPORTAL_PLUGIN_URL; ?>includes/images/control_panel/dashboard/help.png">
                   </a>
                </div>
                <div id="wpjobportal-vers-txt">
                    <?php echo __("Version","wp-job-portal").': '; ?>
                    <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
                </div>
            </div>
        </div>
        <!-- top head -->
        <div id="wpjobportal-head">
            <h1 class="wpjobportal-head-text">
                <?php echo __("Location Name Settings", "wp-job-portal"); ?>
            </h1>
            <a href="<?php echo esc_url(admin_url('admin.php?page=wpjobportal_city&wpjobportallt=loadaddressdata')); ?>" class="wpjobportal-add-link button" title="<?php echo __("Location Name Settings", "wp-job-portal"); ?>">
                <?php echo __("Import Location Data", "wp-job-portal"); ?>
            </a>
        </div>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <form id="wpjobportal-list-form" class="wpjobportal-form" enctype="multipart/form-data" method="post" action="<?php echo esc_url(admin_url('admin.php?page=wpjobportal_city')); ?>">
                <div class="wpjobportal-city-data-form-wrap wpjobportal-city-data-form-wrap-left">
                    <?php
                    $name_preference = get_option("wpjobportal_location_name_preference");
                     ?>
                    <div class="wpjobportal-form-wrapper">
                        <div class="wpjobportal-form-title">
                            <?php echo __("City Name Preferences", "wp-job-portal"). ': '; ?>
                        </div>
                        <div class="wpjobportal-form-value">
                            <span class="wpjobportal-form-radio-field wpjobportal-form-radio-field-first">
                                <input type="radio" name="name_preference" id="name_preference1"  <?php if($name_preference != 2) {?> checked="checked" <?php } ?> value="1" />
                                <label for="name_preference1"><?php echo __("International Name", "wp-job-portal"); ?></label>
                            </span>
                            <span class="wpjobportal-form-radio-field wpjobportal-form-radio-field-second">
                                <input type="radio" name="name_preference" id="name_preference2" <?php if($name_preference == 2) {?> checked="checked" <?php } ?> value="2" />
                                <label for="name_preference2"><?php echo __("Native Name", "wp-job-portal"); ?></label>
                            </span>
                            <span id="loadaddressdata_city_name_msg">
                                <?php echo __("To enhance the user experience, do you prefer displaying city names in English or in their native language.", "wp-job-portal"); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="wpjobportal-form-button">
                    <input class="button wpjobportal-form-save-btn" type="submit" name="submit_app" id="submitbutton" value="<?php echo __("Save Settings", "wp-job-portal"); ?>" onclick="return validate_form(document.adminForm)" />
                </div>
                <div class="wpjobportal-city-data-sample" >
                    <div class="wpjobportal-city-data-sample-heading" >
                        <?php echo __("Sample Data", "wp-job-portal"); ?>
                    </div>
                    <div class="wpjobportal-city-data-table-wrap csl-frst-wdth" >
                        <table class="wpjobportal-city-data-sample-data" >
                            <thead>
                                <tr>
                                    <th><?php echo __("Name", "wp-job-portal"); ?> *</th>
                                    <th><?php echo __("International Name", "wp-job-portal"); ?></th>
                                    <th><?php echo __("Native Name", "wp-job-portal"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach (wpjobportal::$_data[0] as $single_city) {
                                ?>
                                    <tr>
                                        <td class="wpjobportal-location-setting-name"><?php echo $single_city->name;?></td>
                                        <td class="wpjobportal-location-setting-localname" ><?php echo $single_city->internationalname;?></td>
                                        <td  class="wpjobportal-location-setting-internationalname"><?php echo $single_city->localname;?></td>

                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                        <span class="wpjobportal-set-name-disc" >*<?php echo __("Currently Set", "wp-job-portal"); ?></span>
                    </div>
                                        <div class="wpjobportal-city-data-table-wrap csl-scnd-wdth" >

                    </div>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'), WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('task', 'savecitynamesettings'), WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'city_savecitynamesettings'), WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', wp_create_nonce('wpjobportal_address_data_nonce')), WPJOBPORTAL_ALLOWED_TAGS); ?>
            </form>

        </div>
    </div>
</div>

<?php
wp_register_script( 'wpjobportal-inline-handle', '' );
wp_enqueue_script( 'wpjobportal-inline-handle' );

$inline_js_script = "
    jQuery(document).ready(function () {
        jQuery('input[name=\"name_preference\"]').change(function () {
            var selectedValue = jQuery('input[name=\"name_preference\"]:checked').val();

            // Loop through each row that contains the name fields
            jQuery('tr').each(function () {

                // Get the international and native names for each row
                var internationalName = jQuery(this).find('.wpjobportal-location-setting-localname').text();
                var nativeName = jQuery(this).find('.wpjobportal-location-setting-internationalname').text();

                // Update the name based on the selected radio button
                if (selectedValue == '1') {
                    // If 'International Name' is selected, update the td with international name
                    jQuery(this).find('.wpjobportal-location-setting-name').text(internationalName);
                } else if (selectedValue == '2') {
                    // If 'Native Name' is selected, update the td with native name
                    jQuery(this).find('.wpjobportal-location-setting-name').text(nativeName);
                }
            });
        });
    });
    ";
wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>

