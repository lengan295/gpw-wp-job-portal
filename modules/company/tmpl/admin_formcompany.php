<?php
    if (!defined('ABSPATH')) die('Restricted Access');
    wp_enqueue_script('jquery-ui-datepicker');
    $company = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
    $fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsOrderingforForm(WPJOBPORTAL_COMPANY);
    if(!WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'company'))) {
        return;
    }
    wp_enqueue_style('jquery-ui-css', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/jquery-ui-smoothness.css');
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/leftmenue',array('module' => 'company')); ?>
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
                        <li><?php echo esc_html(__('Add New Company','wp-job-portal')); ?></li>
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
        <?php WPJOBPORTALincluder::getTemplate('templates/admin/pagetitle',array('module' => 'company','layouts' => 'comp','company' => $company)); ?>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <form id="company_form" class="wpjobportal-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_company&task=savecompany")); ?>">
                <?php
                    // user selection field
                    echo wp_kses(WPJOBPORTALformfield::hidden('uid', $company ? $company->uid : null),WPJOBPORTAL_ALLOWED_TAGS);
                    if (!$company && $fields['uid']->published == 1) {
                        wpjobportal::$_data['admin_form_company'] = 1;
                        WPJOBPORTALincluder::getTemplate('templates/admin/form-field', array(
                            'field' => $fields['uid'],
                            'content' => WPJOBPORTALincluder::getTemplateHtml('templates/admin/user-selection')
                        ));
                    }

                    // all fields
                    $formfields = WPJOBPORTALincluder::getTemplate('company/form-fields', array(
                        'company' => $company,
                        'fields' => $fields
                    ));

                    foreach ($formfields as $formfield) {
                        WPJOBPORTALincluder::getTemplate('templates/admin/form-field', $formfield);
                    }

                    // status field
                    if (isset($fields['status']) && isset($fields['status']->published) && $fields['status']->published == 1) {// log errors
                        $status = array(
                            (object) array('id' => 0, 'text' => esc_html(__('Pending', 'wp-job-portal'))),
                            (object) array('id' => 1, 'text' => esc_html(__('Approved', 'wp-job-portal'))),
                            (object) array('id' => -1, 'text' => esc_html(__('Rejected', 'wp-job-portal'))),
                            (object) array('id' => 3, 'text' => esc_html(__('Pending Payment', 'wp-job-portal')))
                        );
                        WPJOBPORTALincluder::getTemplate('templates/admin/form-field', array(
                            'field' => $fields['status'],
                            'content' => WPJOBPORTALformfield::select('status', $status, $company ? $company->status : 1, esc_html(__('Select Status', 'wp-job-portal')), array('class' => 'inputbox one wpjobportal-form-select-field'))
                        ));
                    }
                ?>
                <div class="wpjobportal-form-button">
                    <a id="form-cancel-button" class="wpjobportal-form-cancel-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_company')); ?>" title="<?php echo esc_html(__('cancel', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Cancel', 'wp-job-portal')); ?>
                    </a>
                    <?php
                    if (isset(wpjobportal::$_data[0]->id) || (!in_array('credits',wpjobportal::$_active_addons)) ) {
                        echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn')),WPJOBPORTAL_ALLOWED_TAGS);// validate_url() was only checked in edit case
                    }else{
                        echo wp_kses(WPJOBPORTALformfield::button('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn', 'credit_userid' => '', 'onclick' => "wpjobportalformpopupAdmin('add_company','company_form');")),WPJOBPORTAL_ALLOWED_TAGS);
                    }
                    ?>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('payment', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('id', $company ? esc_html($company->id) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('package', 'companies'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('upakid', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('startfeatureddate', isset(wpjobportal::$_data[0]->startfeatureddate) ? esc_html(wpjobportal::$_data[0]->startfeatureddate) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('serverstatus', isset(wpjobportal::$_data[0]->serverstatus) ? esc_html(wpjobportal::$_data[0]->serverstatus) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('serverid', isset(wpjobportal::$_data[0]->serverid) ? esc_html(wpjobportal::$_data[0]->serverid) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('user-popup-title-text', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('User', 'wp-job-portal'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('isadmin', '1'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_company_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
        </form>
        </div>
    </div>
</div>

<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function ($) {
            //Date Format
            $('.custom_date').datepicker({dateFormat: '". wpjobportal::$_common->getCalendarDateFormat()."'});
            $.validate();
            //Token Input
            ";
            $multicities = "var multicities = '';";
            if(isset(wpjobportal::$_data[0]->multicity)) 
                if(!(wpjobportal::$_data[0]->multicity == "[]")) 
                    $multicities = "var multicities = ". wpjobportal::$_data[0]->multicity.";";
            
            $inline_js_script .= $multicities;
            $inline_js_script .= "
            getTokenInput(multicities);
        });

        function checkUrl(obj) {
            if (!obj.value.match(/^http[s]?\:\/\//))
                obj.value = 'http://' + obj.value;
        }

        jQuery('body').delegate('img#wjportal-form-delete-image', 'click', function(e){
            jQuery('.wjportal-form-image-wrp').hide();
            jQuery('input#photo').val('').clone(true);
            jQuery('span.wjportal-form-upload-btn-wrp-txt').text('');
            var id =  jQuery('input[name=id]').val();
            removeLogo(id);
        });

        function validate_url() {
            var value = jQuery('#url').val();
            if (typeof value != 'undefined') {
                if (value != '') {
                    if (value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
                            value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
                    {
                        return true;
                    }
                    else {
                        jQuery('#url').addClass('invalid');
                        alert(\"". esc_html(__("Enter Correct Company Site", 'wp-job-portal'))."\");
                        return false;
                    }
                }
            }
            return true;
        }

        function selectPackage(packageid){
            jQuery('.package-div').css('border','1px solid #ccc');
            jQuery('#package-div-'+packageid).addClass('pkg-selected');
            jQuery('#wpjobportal_packageid').val(packageid);
            jQuery('#upakid').val(packageid);
            jQuery('.pkg-item').removeClass('pkg-selected');
            jQuery('#package-div-'+packageid).addClass('pkg-selected');
            jQuery('#pkg-disabled-btn').removeAttr('disabled');
            jQuery('.proceed-without-paying').removeClass('disabled-btn');
            if (jQuery('#package-div-'+packageid).hasClass('pkg-selected')) {
                jQuery('.proceed-without-paying').addClass('disabled-btn');
            }
        }

        function removeLogo(id) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'company', task: 'deletecompanylogo', companyid: id, '_wpnonce':'". esc_attr(wp_create_nonce("delete-company-logo"))."'}, function (data) {
                if (data) {
                    jQuery('img#comp_logo').css('display', 'none');
                    jQuery('form#company_form').append('<input type=\"hidden\" name=\"company_logo_deleted\" value=\"1\"/>');
                } else {
                    jQuery('div.logo-container').append(\"<span style='color:Red;'>". esc_html(__('Error Deleting Logo', 'wp-job-portal'))."\");
                }
            });
        }

        jQuery('body').delegate('#logo', 'click', function(e){
                jQuery('input#logo').change(function(){
                    var srcimage = jQuery('img.rs_photo');
                    readURL(this, srcimage);
                });
            });

        function readURL(input, srcimage) {
            if (input.files && input.files[0]) {
                var fileext = input.files[0].name.split('.').pop();
                var filesize = (input.files[0].size / 1024);
                var allowedsize = ". wpjobportal::$_config->getConfigurationByConfigName('company_logofilezize').";
                var allowedExt = '". wpjobportal::$_config->getConfigurationByConfigName('image_file_type')."';
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
                        alert(\"". esc_html(__("File size is greater then allowed file size", 'wp-job-portal'))."\");
                    }
                } else{
                    jQuery('input#logo').replaceWith(jQuery('input#logo').val('').clone(true));
                    alert('". esc_html(__("File ext. is mismatched", 'wp-job-portal'))."');
                }
            }
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
        $inline_js_script = "
        function getTokenInput(multicities) {
            var cityArray = '". esc_url_raw(admin_url("admin.php?page=wpjobportal_city&action=wpjobportaltask&task=getaddressdatabycityname"))."';
            var city = jQuery('#cityforedit').val();
            if (city != '') {
                jQuery('#city').tokenInput(cityArray, {
                    theme: 'wpjobportal',
                    preventDuplicates: true,
                    hintText: \"". esc_html(__("Type In A Search Term", 'wp-job-portal'))."\",
                    noResultsText: \"".esc_html(__("No Results", 'wp-job-portal'))."\",
                    searchingText: \"". esc_html(__("Searching", 'wp-job-portal'))."\",
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
                            console.log('test');
                            if (item.name.search(',') == -1) {
                                var input = jQuery('tester').text();
                                alert(\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                                jQuery('#city').tokenInput('remove', item);
                                return false;
                            } else {
                                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: jQuery('tester').text(), '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function (data) {
                                    if (data) {
                                        try {
                                            var value = jQuery.parseJSON(data);
                                            jQuery('#city').tokenInput('remove', item);
                                            jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                        }
                                        catch (err) {
                                            jQuery('#city').tokenInput('remove', item);
                                            alert(data);
                                        }
                                    }
                                });
                            }
                        }";
                    }
                    $inline_js_script .= "
                });
            } else {
                jQuery('#city').tokenInput(cityArray, {
                    theme: 'wpjobportal',
                    preventDuplicates: true,
                    hintText: \"". esc_html(__("Type In A Search Term", 'wp-job-portal'))."\",
                    noResultsText: \"". esc_html(__("No Results", 'wp-job-portal'))."\",
                    searchingText: \"". esc_html(__("Searching", 'wp-job-portal'))."\",
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
                                alert(\"". esc_html(__("Location Format Is Not Correct Please Enter City In This Format City Name Country Name Or City Name State Name Country Name", 'wp-job-portal'))."\");
                                jQuery('#city').tokenInput('remove', item);
                                return false;
                            } else {
                                var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
                                jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'city', task: 'savetokeninputcity', citydata: jQuery('tester').text(), '_wpnonce':'". esc_attr(wp_create_nonce("save-token-input-city"))."'}, function (data) {
                                    if (data) {
                                        try {
                                            var value = jQuery.parseJSON(data);
                                            jQuery('#city').tokenInput('remove', item);
                                            jQuery('#city').tokenInput('add', {id: value.id, name: value.name});
                                        }
                                        catch (err) {
                                            jQuery('#city').tokenInput('remove', item);
                                            alert(data);
                                        }
                                    }
                                });
                            }
                        }";
                    } 
                    $inline_js_script .= "
                });
            }
        }
        ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
