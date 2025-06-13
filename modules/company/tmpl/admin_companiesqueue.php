<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jquery-ui-datepicker');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jquery-ui-smoothness.css');

$dateformat = wpjobportal::$_configuration['date_format'];
if ($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
    $dash = '/';
} else {
    $dash = '-';
}
$firstdash = wpjobportalphplib::wpJP_strpos($dateformat, $dash, 0);
$firstvalue = wpjobportalphplib::wpJP_substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = wpjobportalphplib::wpJP_strpos($dateformat, $dash, $firstdash);
$secondvalue = wpjobportalphplib::wpJP_substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = wpjobportalphplib::wpJP_substr($dateformat, $seconddash, wpjobportalphplib::wpJP_strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
$js_scriptdateformat = wpjobportalphplib::wpJP_str_replace('Y', 'yy', $js_scriptdateformat);
if ( !WPJOBPORTALincluder::getTemplate('templates/admin/header', array('module' => 'company')) ) {
        return;
}
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function () {
            //end approval queue jquery
            jQuery('.custom_date').datepicker({dateFormat: '". $js_scriptdateformat."'});
            jQuery('div.wpjobportal-company-list').each(function () {
                jQuery(this).hover(function () {
                    jQuery(this).find('span.selector').show();
                }, function () {
                    if (jQuery(this).find('span.selector input:checked').length > 0) {
                        jQuery(this).find('span.selector').show();
                    } else {
                        jQuery(this).find('span.selector').hide();
                    }
                });
            });
            jQuery('span#showhidefilter').click(function (e) {
                e.preventDefault();
                var img2 = '". esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/filter-up.png';
                var img1 = '". esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/filter-down.png';
                if (jQuery('.default-hidden').is(':visible')) {
                    jQuery(this).find('img').attr('src', img1);
                } else {
                    jQuery(this).find('img').attr('src', img2);
                }
                jQuery('.default-hidden').toggle();
                var height = jQuery(this).height();
                var imgheight = jQuery(this).find('img').height();
                var currenttop = (height - imgheight) / 2;
                jQuery(this).find('img').css('top', currenttop);
            });
        });

        function highlight(id) {
            if (jQuery('div#company_' + id + ' span input').is(':checked')) {
                jQuery('div#company_' + id).addClass('blue');
            } else {
                jQuery('div#company_' + id).removeClass('blue');
            }
        }
        function highlightAll() {
            if (jQuery('span.selector input').is(':checked') == false) {
                jQuery('span.selector').css('display', 'none');
                jQuery('div.wpjobportal-company-list').removeClass('blue');
            }
            if (jQuery('span.selector input').is(':checked') == true) {
                jQuery('div.wpjobportal-company-list').addClass('blue');
                jQuery('span.selector').css('display', 'block');
            }
        }
        function resetFrom() {
            document.getElementById('searchcompany').value = '';
            // document.getElementById('searchjobcategory').value = '';
            document.getElementById('datestart').value = '';
            document.getElementById('dateend').value = '';
            document.getElementById('wpjobportalform').submit();
        }
        function approveActionPopup(id) {
            var cname = '.jobsqueueapprove_' + id;
            jQuery(cname).show();
            jQuery(cname).mouseout(function () {
                jQuery(cname).hide();
            });
        }

        function rejectActionPopup(id) {
            var cname = '.jobsqueuereject_' + id;
            jQuery(cname).show();
            jQuery(cname).mouseout(function () {
                jQuery(cname).hide();
            });
        }
        function hideThis(obj) {
            jQuery(obj).find('div#wpjobportal-queue-actionsbtn').hide();
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

?>

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
                        <li><?php echo esc_html(__('Companies Approval Queue','wp-job-portal')); ?></li>
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
        <?php
            if ( !WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle', array('module' => 'company','layouts' => 'companyque')) ) {
                return;
            }
        ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- quick actions -->
            <?php
            // catgegory table is no longer in query
                $categoryarray = array(
                    (object) array('id' => 1, 'text' => esc_html(__('Company Name', 'wp-job-portal'))),
                    (object) array('id' => 3, 'text' => esc_html(__('Created', 'wp-job-portal'))),
                    (object) array('id' => 4, 'text' => esc_html(__('Location', 'wp-job-portal'))),
                    (object) array('id' => 5, 'text' => esc_html(__('Status', 'wp-job-portal')))
                );
                /*****Template Sortion****////
                WPJOBPORTALincluder::getTemplate('company/views/admin/filter',array(
                    'categoryarray' => $categoryarray,
                    'layouts' => 'compfilter'
                ));
            $inline_js_script = "
                function changeSortBy() {
                    var value = jQuery('a.sort-icon').attr('data-sortby');
                    var img = '';
                    if (value == 1) {
                        value = 2;
                        img = jQuery('a.sort-icon').attr('data-image2');
                    } else {
                        img = jQuery('a.sort-icon').attr('data-image1');
                        value = 1;
                    }
                    jQuery('img#sortingimage').attr('src', img);
                    jQuery('input#sortby').val(value);
                    jQuery('form#wpjobportalform').submit();
                }
                jQuery('a.sort-icon').click(function (e) {
                    e.preventDefault();
                    changeSortBy();
                });
                function changeCombo() {
                    jQuery('input#sorton').val(jQuery('select#sorting').val());
                    changeSortBy();
                }
            ";
            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );    
            ?>
            <!-- filter form -->
            <form class="wpjobportal-filter-form" name="wpjobportalform" id="wpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_company&wpjobportallt=companiesqueue")); ?>">
                <?php
                    //Form -Filter
                    WPJOBPORTALincluder::getTemplate('company/views/admin/filter',array('layouts' => 'que-filter'));
                ?>
            </form>
            <?php
                if (!empty(wpjobportal::$_data[0])) {
                    ?>
                    <form id="wpjobportal-list-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_company")); ?>">
                        <?php
                            $wpdir = wp_upload_dir();
                            foreach (wpjobportal::$_data[0] AS $company) {
                                $class_color = '';
                                $arr = array();
                                if ($company->isfeaturedcompany == 0) {
                                    if ($class_color == '') {
                                         ?>
                                    <?php }
                                    /* ?>
                                    <span class="q-feature forapproval">
                                        <?php echo esc_html(__('Featured', 'wp-job-portal')); ?>
                                    </span>
                                    <?php */
                                        $class_color = 'q-feature';
                                        $arr['feature'] = 1;
                                }
                                if ($company->status == 0) {
                                    if ($class_color == '') {
                                        ?>
                                    <?php } ?>
                                    <?php
                                    $class_color = 'q-self';
                                    $arr['self'] = 1;
                                }
                                WPJOBPORTALincluder::getTemplate('company/views/admin/companylist',array(
                                    'company' => $company,
                                    'control' => 'que-control',
                                    'wpdir' => $wpdir,
                                    'arr' => $arr,
                                    'layout' => 'que-logo'
                                ));
                            }
                        ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'company_remove'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('task', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('callfrom', 2),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_company_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </form>
                    <?php
                    if (wpjobportal::$_data[1]) {
                       WPJOBPORTALincluder::getTemplate('templates/admin/pagination',array('module' => 'company','pagination' => wpjobportal::$_data[1]));
                    }
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    WPJOBPORTALlayout::getNoRecordFound($msg);
                }
            ?>
        </div>
    </div>
</div>
