<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

wp_enqueue_script('jquery-ui-datepicker');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_style('jquery-ui-css', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jquery-ui-smoothness.css');
?>
<style>
.ui-datepicker{
    float: left;
}
</style>
<?php

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
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        function removeLogo(id) {
            var ajaxurl = '". esc_url_raw(admin_url('admin-ajax.php')) ."'
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'company', task: 'deletecompanylogo', companyid: id, '_wpnonce':'". esc_attr(wp_create_nonce("delete-company-logo"))."'}, function (data) {
                if (data) {
                    jQuery('img#comp_logo').css('display', 'none');
                    jQuery('span.remove-file').css('display', 'none');
                    jQuery('form#wpjobportal-form').append('<input type=\"hidden\" name=\"company_logo_deleted\">');
                } else {
                    jQuery('div.logo-container').append(\"<span style='color:Red;'> ". esc_html(__('Error Deleting Logo', 'wp-job-portal')) ." \");
                }
            });
        }
        jQuery(document).ready(function ($) {
            $('.custom_date').datepicker({dateFormat: '". esc_attr($js_scriptdateformat) ."'});
            //$.validate();
            //Token Input
            ";
            $multicities = " var multicities = ''; ";
            if(isset(wpjobportal::$_data[0]->multicity)) 
                if(!(wpjobportal::$_data[0]->multicity == "[]")) 
                    $multicities = " var multicities = ". wpjobportal::$_data[0]->multicity."; ";
            
            $inline_js_script .= $multicities;
            $inline_js_script .= "
            getTokenInput(multicities);
            jQuery('form#company_form').submit(function (e) {
                var termsandcondtions = jQuery('div.wpjobportal-terms-and-conditions-wrap').attr('data-wpjobportal-terms-and-conditions');
                if(termsandcondtions == 1){
                    if(!jQuery(\"input[name='termsconditions']\").is(\":checked\")){
                        alert(common.terms_conditions);
                        return false;
                    }
                }
            });
// moved this code to document.ready
            jQuery('body').delegate('#logo', 'click', function(e){
                    jQuery('input#logo').change(function(){
                        var srcimage = jQuery('img.rs_photo');
                        readURL(this, srcimage);
                    });
                });

            jQuery('body').delegate('img#wjportal-form-delete-image', 'click', function(e){
                jQuery('.wjportal-form-image-wrp').hide();
                jQuery('input#photo').val('').clone(true);
                jQuery('span.wjportal-form-upload-btn-wrp-txt').text('');
                var id =  jQuery('input[name=id]').val();
                // var id =  jQuery('#id').val();
                removeLogo(id);
            });
        });

        function submitresume(){
            var formvalid = jQuery('form#wpjobportal-form').isValid();
            if (formvalid == false) {
                event.preventDefault();
                return;
            }
            var test = true;
            jQuery('form#wpjobportal-form :input[type=email]').each(function(){
                var emailValue = jQuery(this).val();
                if(emailValue.length != 0){
                    var pattern = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    test = pattern.test(emailValue);
                    if (test == false) {
                        jQuery(this).css({ 'border-color': 'red'});
                    }
                }
            });
            if (test == false) {
                alert('Email is not of correct Format');
            } else {
                var termsandcondtions = jQuery('div.wpjobportal-terms-and-conditions-wrap').attr('data-wpjobportal-terms-and-conditions');
                if(termsandcondtions == 1){
                    if(!jQuery(\"input[name='termsconditions']\").is(\":checked\")){
                        alert(common.terms_conditions);
                        event.preventDefault();
                        return false;
                    }
                }
                jQuery('#wpjobportal-form').submit();
            }
        }

        function checkUrl(obj) {
            if (!obj.value.match(/^http[s]?\:\/\//))
                obj.value = 'http://' + obj.value;
        }

        function validate_url() {
            var value = jQuery('#url').val();
            if (typeof value != 'undefined') {
                if (value != '' && value != 'http://' ) {
                    if (value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
                            value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
                    {
                        return true;
                    }
                    else {
                        jQuery('#url').addClass('invalid');
                        alert('". esc_html(__("Enter Correct Company Site", "wp-job-portal")) ."');
                        return false;
                    }
                }
            }
            return true;
        }




        function readURL(input, srcimage) {
            if (input.files && input.files[0]) {
                var fileext = input.files[0].name.split('.').pop();
                var filesize = (input.files[0].size / 1024);
                var allowedsize = ". wpjobportal::$_config->getConfigurationByConfigName('company_logofilezize') .";
                var allowedExt = '". wpjobportal::$_config->getConfigurationByConfigName('image_file_type') ."';
                allowedExt = allowedExt.split(',');
                if (jQuery.inArray(fileext, allowedExt) != - 1){
                    if (allowedsize > filesize){
                        //New Library
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            jQuery('#rs_photo').attr('src', e.target.result);
                            jQuery('.wjportal-form-image-wrp').show();
                            jQuery('.wjportal-form-upload-btn-wrp-txt').html(input.files[0].name);
                            jQuery('img#wjportal-form-delete-image').on('click',function(){
                                jQuery('.wjportal-form-image-wrp').hide();
                                jQuery('input#logo').val('').clone(true);
                                jQuery('span.wjportal-form-upload-btn-wrp-txt').text('');
                            });
                       }
                        reader.readAsDataURL(input.files[0]);
                    } else{
                        jQuery('input#logo').replaceWith(jQuery('input#logo').val('').clone(true));
                        alert(\"". esc_html(__("File size is greater then allowed file size", "wp-job-portal")) ."\");
                    }
                } else{
                    jQuery('input#logo').replaceWith(jQuery('input#logo').val('').clone(true));
                    alert(\"". esc_html(__("File ext. is mismatched", "wp-job-portal")) ."\");
                }
            }
        }
        ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

        $inline_js_script = "
        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
        function getTokenInput(multicities) {
            var cityArray = '". esc_url_raw(admin_url("admin.php?page=wpjobportal_city&action=wpjobportaltask&task=getaddressdatabycityname")) ."';
            var city = jQuery('#cityforedit').val();
            if (city != '') {
                jQuery('.wpjobportal-company-form-city-field').tokenInput(cityArray, {
                    theme: 'wpjobportal',
                    preventDuplicates: true,
                    hintText: \"". esc_html(__("Type In A Search Term", "wp-job-portal")) ."\",
                    noResultsText: \"". esc_html(__("No Results", "wp-job-portal")) ."\",
                    searchingText: \"". esc_html(__("Searching", "wp-job-portal")) ."\",
                    // tokenLimit: 1,
                    prePopulate: multicities,";
                    $newtyped_cities = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                    if ($newtyped_cities == 1) { 
                        $inline_js_script .= "
                            onResult: function (item) {
                                if (jQuery.isEmptyObject(item)) {
                                    return [{id: 0, name: jQuery('tester').text()}];
                                } else {
                                    //add the item at the top of the dropdown
                                    item.unshift({id: 0, name: jQuery('tester').text()});
                                    return item;
                                }
                            },
                            onAdd: function (item) {
                                if (item.id > 0) {
                                    return;
                                }
                                if (item.name.search(',') == -1) {
                                    var input = jQuery('tester').text();
                                    alert(\"".esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "wp-job-portal")) ."\");
                                    jQuery('.wpjobportal-company-form-city-field').tokenInput('remove', item);
                                    return false;
                                } else {
                                    var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                                    var location_data =  jQuery('tester').text();
                                        //alert(new_loction_lat);
                                        var n_latitude;
                                        var n_longitude;
                                        var geocoder =  new google.maps.Geocoder();
                                        geocoder.geocode( { 'address': location_data}, function(results, status) {
                                            if (status == google.maps.GeocoderStatus.OK) {
                                                n_latitude = results[0].geometry.location.lat();
                                                n_longitude = results[0].geometry.location.lng();
                                            } else {
                                                alert(\"".  esc_html(__('Something got wrong','wp-job-portal')) .":\"+status);
                                            }
                                        });
                                        setTimeout(function(){ // timout is required to make sure that lat lang has value.
                                            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: location_data,latitude:n_latitude ,longitude:n_longitude , '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function(data){
                                                if (data){
                                                    try {
                                                        var value = jQuery.parseJSON(data);
                                                        jQuery('.wpjobportal-company-form-city-field').tokenInput('remove', item);
                                                        jQuery('.wpjobportal-company-form-city-field').tokenInput('add', {id: value.id, name: value.name});
                                                    }
                                                    catch (err) {
                                                        jQuery('.wpjobportal-company-form-city-field').tokenInput('remove', item);
                                                        alert(data);
                                                    }
                                                    }
                                            });
                                        },1500);
                                }
                            }
                            ";
                    } 
                    $inline_js_script .= "
                });
            } else {
                jQuery('.wpjobportal-company-form-city-field').tokenInput(cityArray, {
                    theme: 'wpjobportal',
                    preventDuplicates: true,
                    hintText: \"". esc_html(__("Type In A Search Term", "wp-job-portal")) ."\",
                    noResultsText: \"". esc_html(__("No Results", "wp-job-portal"))."\",
                    searchingText: \"".  esc_html(__("Searching", "wp-job-portal"))."\",
                    // tokenLimit: 1,";

                    $newtyped_cities = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('newtyped_cities');
                    if ($newtyped_cities == 1) { 
                        $inline_js_script .= "
                        onResult: function (item) {
                            if (jQuery.isEmptyObject(item)) {
                                return [{id: 0, name: jQuery('tester').text()}];
                            } else {
                                //add the item at the top of the dropdown
                                item.unshift({id: 0, name: jQuery('tester').text()});
                                return item;
                            }
                        },
                        onAdd: function (item) {
                            if (item.id > 0) {
                                return;
                            }
                            if (item.name.search(',') == -1) {
                                var input = jQuery('tester').text();
                                alert(\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", "wp-job-portal"))."\");
                                jQuery('.wpjobportal-company-form-city-field').tokenInput('remove', item);
                                return false;
                            } else {
                                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: jQuery('tester').text(), '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function (data) {
                                    if (data) {
                                        try {
                                            var value = jQuery.parseJSON(data);
                                            jQuery('.wpjobportal-company-form-city-field').tokenInput('remove', item);
                                            jQuery('.wpjobportal-company-form-city-field').tokenInput('add', {id: value.id, name: value.name});
                                        }
                                        catch (err) {
                                            jQuery('.wpjobportal-company-form-city-field').tokenInput('remove', item);
                                            alert(data);
                                        }
                                    }
                                });
                            }
                        } ";
                    } 
                $inline_js_script .= "
                });
            }
        }

        ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>