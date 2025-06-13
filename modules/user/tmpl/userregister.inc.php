<?php
	if (!defined('ABSPATH'))
    die('Restricted Access');
    ////*******Script Design For Image****//////
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui-css', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jquery-ui-smoothness.css');
	$config = wpjobportal::$_configuration;
	if ($config['date_format'] == 'm/d/Y' || $config['date_format'] == 'd/m/y' || $config['date_format'] == 'm/d/y' || $config['date_format'] == 'd/m/Y') {
	    $dash = '/';
	} else {
	    $dash = '-';
	}
	$dateformat = $config['date_format'];
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
<style type="text/css">
.ui-datepicker{
    float: left;
}
</style>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
    	jQuery(document).ready(function ($) {
    	    $.validate();
    	});
		jQuery(document).ready(function() {
			addDatePicker();
	        jQuery('#photo').change(function () {
	        	var srcimage = jQuery('img.photo');
	        	readURL(this);
	        });
	     });
		function readURL(input) {
			if (input.files && input.files[0]) {
				var fileext = input.files[0].name.split('.').pop();
		        var filesize = (input.files[0].size / 1024);
		        var allowedsize = '".WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_size')."';
		        var allowedExt = \"". WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('image_file_type')."\";
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

		function validatePassword(){
			var pass = jQuery('#password').val();
			var cpass = jQuery('#confirmpassword').val();
			if(pass!='' && cpass!='' && pass!=cpass){
				jQuery('#password-validation-span').text(\"". esc_html(__("Passwords do not match",'wp-job-portal'))."\");
			}else{
				jQuery('#password-validation-span').text('');
			}
		}

		function addDatePicker(){
	        jQuery('.custom_date').datepicker({dateFormat: '". $js_scriptdateformat."'});
	    }
	    function onSubmit(token) {
         document.getElementById('wpjobportal_registration_form').submit();
       }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
