<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
wp_enqueue_script('jquery-ui-datepicker');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jquery-ui-smoothness.css');

$dateformat = wpjobportal::$_configuration['date_format'];
if($dateformat == 'm/d/Y' || $dateformat == 'd/m/y' || $dateformat == 'm/d/y' || $dateformat == 'd/m/Y') {
    $dash = '/';
}else{
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
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
    jQuery(document).ready(function ($) {
        $.validate();
    });
        jQuery(document).ready(function () {
            jQuery('.custom_date').datepicker({dateFormat: '". esc_attr($js_scriptdateformat)."'});
            jQuery('#photo').change(function () {
                var srcimage = jQuery('img.photo');
                readURL(this);
            });
        });


        function readURL(input) {
            if (input.files && input.files[0]) {
                var fileext = input.files[0].name.split('.').pop();
                var filesize = (input.files[0].size / 1024);
                var allowedsize = ". WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_size').";
                var allowedExt = '". WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type')."';
                allowedExt = allowedExt.split(',');
                if (jQuery.inArray(fileext, allowedExt) != - 1){
                    if (allowedsize > filesize){
                        jQuery('.wjportal-form-image-wrp').show();
                        jQuery('#rs_photo')[0].src = (window.URL ? URL : webkitURL).createObjectURL(input.files[0]);
                        jQuery('.wjportal-form-upload-btn-wrp-txt').html(input.files[0].name);
                        jQuery('img#wjportal-form-delete-image').on('click',function(){
                            jQuery('.wjportal-form-image-wrp').hide();
                            jQuery('input#photo').val('').clone(true);
                            jQuery('span.wjportal-form-upload-btn-wrp-txt').text('');
                        });
                        jQuery('#password,#confirmpassword').bind('change',validatePassword);
                    } else{
                        jQuery('input#photo').replaceWith(jQuery('input#photo').val('').clone(true));
                        alert(\"". esc_html(__("File size is greater then allowed file size", 'wp-job-portal'))."\");
                    }
                } else{
                    jQuery('input#photo').replaceWith(jQuery('input#photo').val('').clone(true));
                    alert(\"". esc_html(__("File ext. is mismatched", 'wp-job-portal'))."\");
                }

            }
        }

        function removeLogo(id) {
            if( confirm(\"". esc_html(__("Are you sure?",'wp-job-portal'))."\") ){
                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php'))."\";
                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'user', task: 'deleteUserPhoto', userid: id, '_wpnonce':'". esc_attr(wp_create_nonce("delete-user-photo"))."'}, function (data) {
                    if(data) {
                        jQuery('#wjportal-form-delete-image').attr('src',\"". esc_url(WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker')) ."\");
                        jQuery('.wjportal-form-image-wrp').hide();
                        jQuery('#wjportal-form-delete-image').hide();
                        jQuery('span.wjportal-form-upload-btn-wrp-txt').text('');
                    }else{
                        jQuery('div.logo-container').append(\"<span style='color:Red;'>". esc_html(__('Error Deleting Logo', 'wp-job-portal'))."\");
                    }
                });
            }
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
