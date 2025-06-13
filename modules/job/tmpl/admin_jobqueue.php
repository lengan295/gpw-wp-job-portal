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
    ?>
<?php
    ?>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function () {
            //start Approval queue jquery
            jQuery('.goldnew').hover(function () {
                jQuery(this).find('.goldnew-onhover').show();
            }, function () {
                jQuery(this).find('span.goldnew-onhover').fadeOut('slow');
            });
            jQuery('.featurednew').hover(function () {
                jQuery(this).find('span.featurednew-onhover').show();
            }, function () {
                jQuery(this).find('.featurednew-onhover').fadeOut('slow');
            });
            //end approval queue jquery
            jQuery('div.wpjobportal-jobs-list').each(function () {
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
            jQuery('.custom_date').datepicker({dateFormat: '". esc_js($js_scriptdateformat)."'});
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
            if (jQuery('div#job_' + id + ' span input').is(':checked')) {
                jQuery('div#job_' + id).addClass('blue');
            } else {
                jQuery('div#job_' + id).removeClass('blue');
            }
        }
        function highlightAll() {
            if (jQuery('span.selector input').is(':checked') == false) {
                jQuery('span.selector').css('display', 'none');
                jQuery('div.wpjobportal-jobs-list').removeClass('blue');
            }
            if (jQuery('span.selector input').is(':checked') == true) {
                jQuery('div.wpjobportal-jobs-list').addClass('blue');
                jQuery('span.selector').css('display', 'block');
            }
        }
        function showBorder(id) {
            jQuery('div#job_' + id + ' div#item-data').css('border', '1px solid rgb(78, 140, 245)');
            jQuery('div#job_' + id + ' div#item-data').css('border-bottom', '1px solid #dedede');
            jQuery('div#job_' + id + ' div#item-actions').css('border', '1px solid rgb(78, 140, 245)');
            jQuery('div#job_' + id + ' div#item-actions').css('border-top', 'none');
        }
        function hideBorder(id) {
            jQuery('div#job_' + id + ' div#item-data').css('border', '1px solid #dedede');
            jQuery('div#job_' + id + ' div#item-actions').css('border', '1px solid #dedede');
            jQuery('div#job_' + id + ' div#item-actions').css('border-top', 'none');
        }
        function resetFrom() {
            document.getElementById('location').value = '';
            document.getElementById('searchtitle').value = '';
            document.getElementById('searchcompany').value = '';
            document.getElementById('searchjobcategory').value = '';
            document.getElementById('searchjobtype').value = '';
            document.getElementById('datestart').value = '';
            document.getElementById('dateend').value = '';
            //jQuery('#gold1').prop('checked', false);
            jQuery('#featured1').prop('checked', false);
            document.getElementById('wpjobportalform').submit();
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<?php
    $categoryarray = array(
        (object) array('id' => 1, 'text' => esc_html(__('Job Title', 'wp-job-portal'))),
        (object) array('id' => 2, 'text' => esc_html(__('Company Name', 'wp-job-portal'))),
        (object) array('id' => 3, 'text' => esc_html(__('Category', 'wp-job-portal'))),
        (object) array('id' => 5, 'text' => esc_html(__('Location', 'wp-job-portal'))),
        (object) array('id' => 7, 'text' => esc_html(__('Status', 'wp-job-portal'))),
        (object) array('id' => 4, 'text' => esc_html(__('Job Type', 'wp-job-portal'))),
        (object) array('id' => 6, 'text' => esc_html(__('Created', 'wp-job-portal')))
    );
    WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'job'));
    ?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getTemplate('templates/admin/leftmenue',array('module' => 'job')); ?>
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
                        <li><?php echo esc_html(__('Job Approval Queue','wp-job-portal')); ?></li>
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
            WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle', array('module' => 'job','layouts' => 'jobapprovalque'));
        ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- quick actions -->
            <div id="wpjobportal-page-quick-actions">
                <label class="wpjobportal-page-quick-act-btn" onclick="return highlightAll();" for="selectall">
                    <input type="checkbox" name="selectall" id="selectall" value="">
                    <?php echo esc_html(__('Select All', 'wp-job-portal')) ?>
                </label>
                <a class="wpjobportal-page-quick-act-btn multioperation" message="<?php echo esc_attr(WPJOBPORTALMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')) .' ?'; ?>" data-for="remove" href="#" title="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/forced-delete.png" alt="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>" />
                    <?php echo esc_html(__('Delete', 'wp-job-portal')) ?>
                </a>
                <!-- sorting -->
                <?php
                    $image1 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/control_panel/dashboard/sorting-white-1.png";
                    $image2 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/control_panel/dashboard/sorting-white-2.png";
                    if (wpjobportal::$_data['sortby'] == 1) {
                        $image = $image1;
                    } else {
                        $image = $image2;
                    }
                ?>
                <div class="wpjobportal-sorting-wrp">
                    <span class="wpjobportal-sort-text">
                        <?php echo esc_html(__('Sort by', 'wp-job-portal')); ?>:
                    </span>
                    <span class="wpjobportal-sort-field">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('sorting', $categoryarray, wpjobportal::$_data['combosort'], '', array('class' => 'inputbox', 'onchange' => 'changeCombo();')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </span>
                    <a class="wpjobportal-sort-icon sort-icon" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(wpjobportal::$_data['sortby']); ?>">
                        <img id="sortingimage" src="<?php echo esc_url($image); ?>" />
                    </a>
                </div>
                <?php
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

            </div>
            <!-- filter form -->
            <form class="wpjobportal-filter-form" name="wpjobportalform" id="wpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue")); ?>">
                <?php
                    WPJOBPORTALincluder::getTemplate('job/views/admin/filter',array('layout' => 'jobfilter' ,'show' => 'yes'));
                ?>
            </form>
            <?php
                if (!empty(wpjobportal::$_data[0])) {
                    ?>
                    <form id="wpjobportal-list-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_job&wpjobportallt=jobqueue")); ?>">
                        <?php
                            foreach (wpjobportal::$_data[0] AS $job) {
                                WPJOBPORTALincluder::getTemplate('job/views/admin/joblist',array(
                                    'job' => $job,
                                    'layout' => 'que-control',
                                    'logo' => 'que-logo'
                                ));
                            }
                        ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'job_remove'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('task', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('callfrom', 2),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_job_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </form>
                    <?php
                    if (wpjobportal::$_data[1]) {
                        if(!WPJOBPORTALincluder::getTemplate('templates/admin/pagination',array('module' => 'job' , 'pagination' => wpjobportal::$_data[1]))){
                            return ;
                        }
                    }
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    WPJOBPORTALlayout::getNoRecordFound($msg);
                }
            ?>
        </div>
    </div>
</div>
