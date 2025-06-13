<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('wpjobportal-res-tables', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/responsivetable.js');
//Header
if(!WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'activitylog'))){
    return;
}
$activitylog = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
$categoryarray = array(
    (object) array('id' => 1, 'text' => esc_html(__('ID', 'wp-job-portal'))),
    (object) array('id' => 2, 'text' => esc_html(__('User Name', 'wp-job-portal'))),
    (object) array('id' => 3, 'text' => esc_html(__('Reference For', 'wp-job-portal'))),
    (object) array('id' => 4, 'text' => esc_html(__('Created', 'wp-job-portal')))
);
wp_register_script( 'wpjobportal-activity-handle', '' );
wp_enqueue_script( 'wpjobportal-activity-handle' );

$activity_js_script = '
    jQuery(document).ready(function () {
        jQuery("div#full_background,img#popup_cross").click(function () {
            HidePopup();
        });
    });

    function ShowPopup() {
        jQuery("div#full_background").show();
        jQuery("div#popup_main").fadeIn(300);
    }

    function HidePopup() {
        jQuery("div#full_background").hide();
        jQuery("div#popup_main").fadeOut(300);
    }
    function submitfrom() {
        jQuery("form#filter_form").submit();

    }

    function changeSortBy() {
        var value = jQuery("a#sort-icon").attr("data-sortby");
        var img = "";
        if (value == 1) {
            value = 2;
            img = jQuery("a#sort-icon").attr("data-image2");
        } else {
            img = jQuery("a#sort-icon").attr("data-image1");
            value = 1;
        }
        jQuery("img#sortingimage").attr("src", img);
        jQuery("input#sortby").val(value);
        jQuery("form#filter_form").submit();
    }

    function buttonClick() {
        changeSortBy();
    }
    function changeCombo() {
        jQuery("input#sorton").val(jQuery("select#sorting").val());
        changeSortBy();
    }

    ';
wp_add_inline_script( 'wpjobportal-activity-handle', $activity_js_script );    
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/leftmenue',array('module' => 'activitylog')); ?>
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
                        <li><?php echo esc_html(__('Activity Log','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'activitylog','layouts' => 'activitylog')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- filter form -->
            <?php WPJOBPORTALincluder::getTemplate('activitylog/views/filter'); ?>
            <!-- quick actions -->
            <?php WPJOBPORTALincluder::getTemplate('activitylog/views/multioperation',array('categoryarray' => $categoryarray)); ?>
            <?php if (!empty($activitylog)) { ?>
                <table id="wpjobportal-table" class="wpjobportal-table">
                    <thead>
                        <tr>
                            <th>
                                <?php echo esc_html(__('ID', 'wp-job-portal')); ?>
                            </th>
                            <th class="wpjobportal-text-left">
                                <?php echo esc_html(__('User Name', 'wp-job-portal')); ?>
                            </th>
                            <th class="wpjobportal-text-left">
                                <?php echo esc_html(__('Description', 'wp-job-portal')); ?>
                            </th>
                            <th>
                                <?php echo esc_html(__('Reference For', 'wp-job-portal')); ?>
                            </th>
                            <th>
                                <?php echo esc_html(__('Created', 'wp-job-portal')); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activitylog AS $data) {
                            //Detail
                            WPJOBPORTALincluder::getTemplate('activitylog/views/detail',array('data' => $data ));
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                    if (wpjobportal::$_data[1]) {
                        ///Pagination
                       WPJOBPORTALincluder::getTemplate('templates/admin/pagination',array('module' => 'activitylog','pagination' => wpjobportal::$_data[1]));
                    }
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    WPJOBPORTALlayout::getNoRecordFound($msg);
                }
            ?>
        </div>
    </div>
</div>
