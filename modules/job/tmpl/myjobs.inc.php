<?php if (!defined('ABSPATH')) die('Restricted Access');
?>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
        function sortbychanged(){
            var sortval= jQuery('#sortbycombo').val();
            jQuery('#sortby').val('');
            jQuery('#sortby').val(sortval);
            jQuery('#job_form').submit();
        }
        jQuery(document).ready(function () {
            jQuery('a.sort-icon').click(function (e) {
                e.preventDefault();
                changeSortBy();
            });
         });

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
            jQuery('form#job_form').submit();
        }

        function changeCombo() {
            jQuery('input#sorton').val(jQuery('select#sorting').val());
            changeSortBy();
        }";

        if(in_array('copyjob', wpjobportal::$_active_addons)){  
            $inline_js_script .= "
            function copyJob(jobsid) {
                if (jobsid) {
                    jQuery('#js_ajax_pleasewait').show();
                    jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'job', task: 'makeJobCopyAjax', jobid: jobsid, '_wpnonce':'". esc_attr(wp_create_nonce("make-job-copy-ajax"))."'}, function (data) {
                        if (data) {
                            jQuery('#js_ajax_pleasewait').hide();
                            if (data == 'copied') {
                                jQuery('p#js_jobcopid').slideDown();
                                setTimeout(function () {
                                    location.reload();
                                }, 700);
                            }
                        }
                    });
                }
            }";
        }

        if(in_array('credits', wpjobportal::$_active_addons)){ 
            $inline_js_script .= "
            function selectPackage(packageid){
               jQuery('.package-div').css('border','1px solid #ccc');
                jQuery('.wjportal-pkg-item, .wpj-jp-pkg-item').removeClass('wjportal-pkg-selected');
                jQuery('#package-div-'+packageid).addClass('wjportal-pkg-selected');
                jQuery('#wpjobportal_packageid').val(packageid);
                jQuery('#jsre_featured_button').removeAttr('disabled');
            }

            function getPackagePopup(jobid,themecall) {
                var themecall = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                var jppage_id = ". wpjobportal::wpjobportal_getPageid() .";
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'featuredjob', task: 'getPackagePopupForFeaturedJob', wpjobportalid: jobid, '_wpnonce':'". esc_attr(wp_create_nonce("get-package-popup-for-featured-job"))."',wpjobportalpageid:jppage_id}, function (data) {
                    if (data) {
                        if(jQuery('#package-popup').length)
                        jQuery('#package-popup').remove();
                        jQuery('body').append(data);
                        if(null != themecall){
                            jQuery('#' + common.theme_chk_prefix + '-popup-background').show();
                        } else {
                            jQuery('#wjportal-popup-background').show();
                        }
                        jQuery('#package-popup').slideDown('slow');

                    } else {
                        jQuery('div.logo-container').append(\"<span style='color:Red;'>". esc_html(__('Error While Adding Feature job', 'wp-job-portal'))."\");
                    }
                });
            }

            function getPackageCopyPopup(jobid,themecall){
                var themecall = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                var jppage_id = ". wpjobportal::wpjobportal_getPageid() .";
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'copyjob', task: 'getPackagePopupForCopyJob', wpjobportalid: jobid, '_wpnonce':'". esc_attr(wp_create_nonce("get-package-popup-for-copy-job"))."',wpjobportalpageid:jppage_id}, function (data) {
                    if (data) {
                        if(jQuery('#package-popup').length)
                        jQuery('#package-popup').remove();
                        jQuery('body').append(data);
                        if(null != themecall){
                            jQuery('#' + common.theme_chk_prefix + '-popup-background').show();
                        } else {
                            jQuery('#wjportal-popup-background').show();
                        }
                        jQuery('#package-popup').slideDown('slow');

                    } else {
                        jQuery('div.logo-container').append(\"<span style='color:Red;'>". esc_html(__('Error While Adding Feature job', 'wp-job-portal'))."\");
                    }
                });
            }";
        }
        $inline_js_script .= "
        function makeExpiry() {
            jQuery('.goldnew').hover(function () {
                jQuery(this).find('.goldnew-onhover').show();
            }, function () {
                jQuery(this).find('span.goldnew-onhover').fadeOut('slow');
            });
            jQuery('span.wjportal-featured-tag-icon-wrp, span.wpj-jp-featured-tag-icon-wrp').hover(function () {
                jQuery(this).find('span.featurednew-onhover').show();
            }, function () {
                jQuery(this).find('.featurednew-onhover').fadeOut('slow');
            });
        }

        jQuery(document).ready(function () {
            makeExpiry();
        });

        function addBadgeToObject(cid, specialtype, object,themecall) {
            var themecall = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
            var html = '';

            if(1 ==common.theme_chk_number){
                html += '<span class=\"wpj-jp-featured-tag-icon-wrp\">';
                    html += '<span class=\"wpj-jp-featured-tag-icon\">';
                    html += '<i class=\"fa fa-star\"></i>';
                    html += '</span>';
                    html += '<span class=\"featurednew-onhover wpj-jp-featured-hover-wrp\" id=\"gold' + cid + '\" style=\"display: none;\">';
                    html += object.expiry;
                    html += '</span>';
                    html += '</span>';
                jQuery('div.object_' + cid).append(html);
                jQuery('div.wpj-jp-cp-data').prepend('<div class=\'frontend updated\'><p>". esc_html(__("Job Has Been Feature SuccesFully", 'wp-job-portal'))."</p></div>');
            }else if(2 ==common.theme_chk_number){
                html +='<div class=\'jsjb-jh-featured-icon\'><i class=\'fa fa-star\' aria-hidden=\'true\'></i></div>';
                jQuery('div.object_' + cid).find('div.jsjb-jh-jobs-row-left').append(html);
            }else{
                html += '<span class=\"wjportal-featured-tag-icon-wrp\">';
                html += '<span class=\"wjportal-featured-tag-icon\">';
                html += '<i class=\"fa fa-star\"></i>';
                html += '</span>';
                html += '<span class=\"featurednew-onhover wjportal-featured-hover-wrp\" id=\"gold' + cid + '\" style=\"display: none;\">';
                html += object.expiry;
                html += '</span>';
                html += '</span>';
                jQuery('#featuredjob_'+cid).hide();
                jQuery('div.object_' + cid).append(html);

                 jQuery('div.wjportal-page-header').append(\"<div class='  frontend updated'><p>". esc_html(__('Job Has Been Feature SuccessFully', 'wp-job-portal'))."</p></div>\");
            }
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
