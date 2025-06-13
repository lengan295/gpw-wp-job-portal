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
    if ( !WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'resume'))) {
        return;
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
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

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

        function selectPackage(packageid){
            jQuery('#package-div-'+packageid).addClass('pkg-selected');
            jQuery('#wpjobportal_packageid').val(packageid);
            jQuery('#upakid').val(packageid);
            jQuery('#pkg-disabled-btn').removeAttr('disabled');
            jQuery('.proceed-without-paying').removeClass('disabled-btn');
            jQuery('.pkg-item').removeClass('pkg-selected');
            jQuery('#package-div-'+packageid).addClass('pkg-selected');
            if (jQuery('#package-div-'+packageid).hasClass('pkg-selected')) {
                jQuery('.proceed-without-paying').addClass('disabled-btn');
            }
        }


        function changeButton(cid, specialtype) {
            var non = jQuery('#featuredwpnonce').val();
            var html = '<a href=\"admin.php?page=wpjobportal_featureresume&task=removefeaturedresume&action=wpjobportaltask&resumeid=' + cid + '&_wpnonce='+non+'\" class=\"js-action-link button wpjobportal-resume-act-btn\" title=\"". esc_html(__('remove featured', 'wp-job-portal'))."\">". esc_html(__('Remove Featured', 'wp-job-portal'))."</a>';
            jQuery('a.' + specialtype + '_' + cid).replaceWith(html);
        }

        function addBadgeToObject(cid, specialtype, expiry) {
            var html = '';
            html = '<span class=\"featurednew wpjobportal-featured-tag-icon-wrp\" data-id=\"' + cid + '\">';
            html += '<span id=\"badge_featured\" class=\"wpjobportal-featured-tag-icon\"><i class=\"fa fa-star\"></i></span>';
            html += '<span class=\"featurednew-onhover wpjobportal-featured-hover-wrp\" id=\"gold' + cid + '\" style=\"display: none;\">';
            html += \"". esc_html(__('Expiry Date', 'wp-job-portal'))." : \" + expiry;
            html += '</span>';
            html += '</span>';
            jQuery('div#resume_' + cid).find('div#item-data').append(html);
            changeButton(cid,specialtype);
        }";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

    $inline_js_script = "
        function makeExpiry() {
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
        }

        jQuery(document).ready(function () {

            jQuery('.custom_date').datepicker({dateFormat: '". esc_attr($js_scriptdateformat)."'});
            jQuery('div#full_background').click(function () {
                closePopup();
            });
            jQuery('img#popup_cross').click(function () {
                closePopup();
            });
            jQuery('div.wpjobportal-resume-list').each(function () {
                jQuery('div#' + this.id).hover(function () {
                    jQuery('div#' + this.id + ' div span.selector').show();
                }, function () {
                    if (jQuery('div#' + this.id + ' div span.selector input:checked').length > 0) {
                        jQuery('div#' + this.id + ' div span.selector').show();
                    } else {
                        jQuery('div#' + this.id + ' div span.selector').hide();
                    }
                });
            });
            makeExpiry();
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
            if (jQuery('div#resume_' + id + ' div span input:checked').length > 0) {
                showBorder(id);
            } else {
                hideBorder(id);
            }
        }
        function showBorder(id) {
            jQuery('div#resume_' + id).addClass('blue');
        }
        function hideBorder(id) {
            jQuery('div#resume_' + id).removeClass('blue');
        }
        function highlightAll() {
            if (jQuery('span.selector input').is(':checked') == false) {
                jQuery('span.selector').css('display', 'none');
                jQuery('div.wpjobportal-resume-list').removeClass('blue');
            }
            if (jQuery('span.selector input').is(':checked') == true) {
                jQuery('span.selector').css('display', 'block');
                jQuery('div.wpjobportal-resume-list').addClass('blue');
            }
        }
        function checkAllSelection() {
            var totalItems = jQuery('div.wpjobportal-resume-list').length;
            jQuery('div.wpjobportal-resume-list').each(function () {
            });
        }

        function resetFrom() {
            document.getElementById('searchtitle').value = '';
            document.getElementById('searchname').value = '';
            document.getElementById('searchjobcategory').value = '';
            document.getElementById('searchjobtype').value = '';
            document.getElementById('datestart').value = '';
            document.getElementById('dateend').value = '';
            document.getElementById('status').value = '';
            //jQuery('#gold1').prop('checked', false);
            jQuery('#featured1').prop('checked', false);
            document.getElementById('wpjobportalform').submit();
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<?php
    $resumes = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
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
                        <li><?php echo esc_html(__('Resume','wp-job-portal')); ?></li>
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
            if (!WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'resume' ,'layouts' => 'resume' ))){
                return;
            }
        ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <!-- quick actions -->
            <?php
                WPJOBPORTALincluder::getTemplate('resume/views/admin/multioperation',array(
                  'categoryarray'=>$categoryarray
                ));
            ?>
            <!-- filter form -->
            <form class="wpjobportal-filter-form" name="wpjobportalform" id="wpjobportalform" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=resumes")); ?>">
                <?php WPJOBPORTALincluder::getTemplate('resume/views/admin/filters',array('extra' => (int)0 ,'layout' => 'admin_filter'));?>
            </form>
            <?php
                if (!empty($resumes)) { ?>
                    <form id="wpjobportal-list-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_resume")); ?>">
                        <?php
                            foreach ($resumes AS $resume) {?>
                                <div id="resume_<?php echo esc_attr($resume->id); ?>" class="wpjobportal-resume-list">
                                    <div id="item-data">
                                        <?php do_action('wpjobportal_addons_feature_resume_title',$resume); ?>
                                        <span id="selector_<?php echo esc_attr($resume->id); ?>" class="selector">
                                            <input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($resume->id); ?>);" class="wpjobportal-cb" id="wpjobportal-cb" name="wpjobportal-cb[]" value="<?php echo esc_attr($resume->id); ?>" />
                                        </span>
                                        <?php
                                            WPJOBPORTALincluder::getTemplate('resume/views/admin/details',array(
                                                'resume' => $resume,
                                                'control' => 'resume',
                                                'arr' => ''
                                            ));
                                        ?>
                                    </div>
                                </div>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'resume_remove'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('task', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('callfrom', 1),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('featuredwpnonce', esc_html(wp_create_nonce('delete-featured-resume'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_resume_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('package', 'featuredresume'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <?php echo wp_kses(WPJOBPORTALformfield::hidden('upakid', 'featuredresume'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <?php } ?>
                    </form>
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
