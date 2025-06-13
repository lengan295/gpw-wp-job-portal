<?php
if (!defined('ABSPATH'))
die('Restricted Access');
wp_enqueue_script('wpjobportal-res-tables', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/js/responsivetable.js');
if (!WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'highesteducation'))){
    return;
}
?>

<?php 
wp_register_script( 'wpjobportal-inline-handle', '' );
wp_enqueue_script( 'wpjobportal-inline-handle' );
wp_enqueue_script('jquery-ui-sortable');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', $protocol.'ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
?>
<?php
    $inline_js_script = "
         jQuery(document).ready(function () {
                jQuery('table#wpjobportal-table tbody').sortable({ 
                    handle : '.wpjobportal-order-grab-column',
                    update  : function () {
                        jQuery('.wpjobportal-saveorder-wrp').slideDown('slow');
                        var abc =  jQuery('table#wpjobportal-table tbody').sortable('serialize');
                        jQuery('input#fields_ordering_new').val(abc);
                    }
                });
            });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>


<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getTemplate('templates/admin/leftmenue',array('module' => 'highesteducation')); ?>
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
                        <li><?php echo esc_html(__('Educations','wp-job-portal')); ?></li>
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
        <?php  WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'highesteducation' ,'layouts' => 'highesteducation')); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- quick actions -->
            <?php WPJOBPORTALincluder::getTemplate('highesteducation/views/multioperation'); ?>
            <?php
                $inline_js_script = "
                    function resetFrom() {
                        jQuery('input#title').val('');
                        jQuery('select#status').val('');
                        jQuery('form#wpjobportalform').submit();
                    }
                ";
                wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
            ?>
            <!-- filter form -->
            <form class="wpjobportal-filter-form" name="wpjobportalform" id="wpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_highesteducation")); ?>">
                <?php WPJOBPORTALincluder::getTemplate('highesteducation/views/filter'); ?>
            </form>
            <?php
                if (!empty(wpjobportal::$_data[0])) {
                    ?>
                    <form id="wpjobportal-list-form" method="post" action="<?php echo esc_url(wp_nonce_url(admin_url("admin.php?page=wpjobportal_highesteducation&task=saveordering"),'wpjobportal_highest_education_nonce')); ?>">
                        <table id="wpjobportal-table" class="wpjobportal-table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="selectall" id="selectall" value="">
                                    </th>
                                    <th>
                                        <?php echo esc_html(__('Ordering', 'wp-job-portal')); ?>
                                    </th>
                                    <th class="wpjobportal-text-left">
                                        <?php echo esc_html(__('Title', 'wp-job-portal')); ?>
                                    </th>
                                    <th>
                                        <?php echo esc_html(__('Default', 'wp-job-portal')); ?>
                                    </th>
                                    <th>
                                        <?php echo esc_html(__('Published', 'wp-job-portal')); ?>
                                    </th>
                                    <th>
                                        <?php echo esc_html(__('Action', 'wp-job-portal')); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $pagenum = WPJOBPORTALrequest::getVar('pagenum', 'get', 1);
                                    $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                                    $islastordershow = WPJOBPORTALpagination::isLastOrdering(wpjobportal::$_data['total'], $pagenum);
                                    for ($i = 0, $n = count(wpjobportal::$_data[0]); $i < $n; $i++) {
                                        $row = wpjobportal::$_data[0][$i];
                                        WPJOBPORTALincluder::getTemplate('highesteducation/views/main',array('row' => $row,'i' => $i ,'pagenum' => $pagenum ,'n' => $n ,'pageid' => $pageid ,'islastordershow' => $islastordershow ));
                                        }
                                        ?>
                            </tbody>
                        </table>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('fields_ordering_new', '123'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('task', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('pagenum', ($pagenum > 1) ? esc_html($pagenum) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_highest_education_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <div class="wpjobportal-saveorder-wrp" style="display: none;">
                        <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save Ordering', 'wp-job-portal')), array('class' => 'button wpjobportal-form-act-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        </div>

                    </form>
                    <?php
                        if (wpjobportal::$_data[1]) {
                            echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(wpjobportal::$_data[1]) . '</div></div>';
                        }
                    } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    $link[] = array(
                                'link' => 'admin.php?page=wpjobportal_highesteducation&wpjobportallt=formhighesteducation',
                                'text' => esc_html(__('Add New','wp-job-portal')) .' '. esc_html(__('Highest Education','wp-job-portal'))
                            );
                    WPJOBPORTALlayout::getNoRecordFound($msg,$link);
                    }
            ?>
        </div>
    </div>
</div>
