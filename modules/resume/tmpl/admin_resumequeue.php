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
    $resumeque = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
    ?>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function () {
            jQuery('.custom_date').datepicker({dateFormat: '". $js_scriptdateformat."'});
            //start Approval queue jquery

            //end approval queue jquery
            jQuery('div#full_background').click(function () {
                closePopup();
            });
            jQuery('img#popup_cross').click(function () {
                closePopup();
            });
            jQuery('div#wp-job-portal-comp-listwrapper').each(function () {
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
            if (jQuery('div.resume_' + id + ' span input').is(':checked')) {
                jQuery('div.resume_' + id).addClass('blue');
            } else {
                jQuery('div.resume_' + id).removeClass('blue');
            }
        }
        function highlightAll() {
            if (jQuery('span.selector input').is(':checked') == false) {
                jQuery('span.selector').css('display', 'none');
                jQuery('div#wp-job-portal-comp-listwrapper').removeClass('blue');
            }
            if (jQuery('span.selector input').is(':checked') == true) {
                jQuery('div#wp-job-portal-comp-listwrapper').addClass('blue');
                jQuery('span.selector').css('display', 'block');
            }
        }
        function showBorder(id) {
            jQuery('div#resume_' + id + ' div#item-data').css('border', '1px solid rgb(78, 140, 245)');
            jQuery('div#resume_' + id + ' div#item-data').css('border-bottom', '1px solid #dedede');
            jQuery('div#resume_' + id + ' div#item-actions').css('border', '1px solid rgb(78, 140, 245)');
            jQuery('div#resume_' + id + ' div#item-actions').css('border-top', 'none');
        }
        function hideBorder(id) {
            jQuery('div#resume_' + id + ' div#item-data').css('border', '1px solid #dedede');
            jQuery('div#resume_' + id + ' div#item-actions').css('border', '1px solid #dedede');
            jQuery('div#resume_' + id + ' div#item-actions').css('border-top', 'none');
        }
        function checkAllSelection() {
            var totalItems = jQuery('div.resume-container').length;
            jQuery('div.resume-container').each(function () {
                // if (jQuery('div.item'))
            });
        }

        function resetFrom() {
            document.getElementById('searchtitle').value = '';
            document.getElementById('searchname').value = '';
            document.getElementById('searchjobcategory').value = '';
            document.getElementById('searchjobtype').value = '';
            document.getElementById('datestart').value = '';
            document.getElementById('dateend').value = '';
            jQuery('#featured1').prop('checked', false);
            document.getElementById('wpjobportalform').submit();
        }
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

<?php
    $categoryarray = array(
        (object) array('id' => 1, 'text' => esc_html(__('Application title', 'wp-job-portal'))),
        (object) array('id' => 2, 'text' => esc_html(__('First name', 'wp-job-portal'))),
        (object) array('id' => 3, 'text' => esc_html(__('Category', 'wp-job-portal'))),
        (object) array('id' => 4, 'text' => esc_html(__('Job type', 'wp-job-portal'))),
        (object) array('id' => 5, 'text' => esc_html(__('Location', 'wp-job-portal'))),
        (object) array('id' => 6, 'text' => esc_html(__('Created', 'wp-job-portal'))),
        (object) array('id' => 7, 'text' => esc_html(__('Status', 'wp-job-portal')))
    );
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <?php
            if ( !WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'resume')) ) {
                return;
            }
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
                        <li><?php echo esc_html(__('Resume Queue','wp-job-portal')); ?></li>
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
            if ( !WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'resume','layouts' => 'resumeque')) ) {
                return;
            }
        ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- quick actions -->
            <?php
                WPJOBPORTALincluder::getTemplate('resume/views/admin/multioperation', array(
                    'categoryarray' =>$categoryarray
                ));
            ?>
            <!-- filter form -->
            <form class="wpjobportal-filter-form" name="wpjobportalform" id="wpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue")); ?>">
                <?php
                    WPJOBPORTALincluder::getTemplate('resume/views/admin/filters',array(
                        'extra' => (int) 1,
                        'layout' => 'admin_filter'
                    ));
                ?>
            </form>
            <?php
                if (!empty($resumeque)) {
                    ?>
                    <div id="resume-approval-queue" class="wpjobportal-resume-list">
                        <form id="wpjobportal-list-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_resume")); ?>">
                            <?php
                                foreach ($resumeque AS $resume) {
                                    $class_color = '';
                                    $arr = array();
                                    if ($resume->isfeaturedresume == 0) {
                                        $class_color = 'q-feature';
                                        $arr['feature'] = 1;
                                    }
                                    if ($resume->status == 0) {
                                        if ($class_color == '') {
                                            ?>
                                        <?php } ?>
                                        <?php
                                        $class_color = 'q-self';
                                        $arr['self'] = 1;
                                    } ?>
                                    <div id="wp-job-portal-comp-listwrapper" class="wpjobportal-resume-list resume_<?php echo esc_attr($resume->id); ?>">
                                        <?php do_action('wpjobportal_addons_feature_resume_title',$resume); ?>
                                        <span id="selector_<?php echo esc_attr($resume->id); ?>" class="selector">
                                            <input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($resume->id); ?>);" class="wpjobportal-cb" id="wpjobportal-cb" name="wpjobportal-cb[]" value="<?php echo esc_attr($resume->id); ?>" />
                                        </span>
                                        <?php
                                        WPJOBPORTALincluder::getTemplate('resume/views/admin/details',array(
                                            'resume' => $resume,
                                            'control' => 'resumeque',
                                            'arr' => $arr
                                        )); ?>
                                    </div>
                                    <?php
                                }
                            ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'resume_remove'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('task', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('callfrom', 2),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_resume_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        </form>
                    </div>
                    <?php
                    if (wpjobportal::$_data[1]) {
                        WPJOBPORTALincluder::getTemplate('templates/admin/pagination',array(
                            'pagination' => wpjobportal::$_data[1],
                            'module' => 'resume'
                        ));
                    }
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    WPJOBPORTALlayout::getNoRecordFound($msg);
                }
            ?>
        </div>
    </div>
</div>
