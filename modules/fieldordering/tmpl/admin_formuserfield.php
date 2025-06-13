<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
wp_register_script( 'wpjobportal-inline-handle', '' );
wp_enqueue_script( 'wpjobportal-inline-handle' );

$inline_js_script = "
    jQuery(document).ready(function ($) {
        $.validate();
    });
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
                        <li><?php echo esc_html(__('User Fields','wp-job-portal')); ?></li>
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
        <div id="wpjobportal-head">
            <h1 class="wpjobportal-head-text">
                <?php
                    $heading = isset(wpjobportal::$_data[0]['userfield']->id) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add', 'wp-job-portal'));
                    echo esc_html($heading) . ' ' . esc_html(__('User Field', 'wp-job-portal'));
                ?>
            </h1>
        </div>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper">
            <?php

                 $equalnotequal = array(
                (object) array('id' => 1, 'text' => esc_html(__('Equal', 'wp-job-portal'))),
                (object) array('id' => 0, 'text' => esc_html(__('Not Equal', 'wp-job-portal'))));
                $yesno = array(
                    (object) array('id' => 1, 'text' => esc_html(__('Yes', 'wp-job-portal'))),
                    (object) array('id' => 0, 'text' => esc_html(__('No', 'wp-job-portal'))));

                if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                    // $sectionarray = array(
                    // (object) array('id' => 1, 'text' => esc_html(__('Personal Information', 'wp-job-portal'))),
                    // (object) array('id' => 2, 'text' => esc_html(__('Addresses', 'wp-job-portal'))),
                    // (object) array('id' => 3, 'text' => esc_html(__('Education', 'wp-job-portal'))),
                    // (object) array('id' => 4, 'text' => esc_html(__('Employer', 'wp-job-portal'))),
                    // (object) array('id' => 5, 'text' => esc_html(__('Skills', 'wp-job-portal'))),
                    // (object) array('id' => 6, 'text' => esc_html(__('Resume', 'wp-job-portal'))),
                    // (object) array('id' => 7, 'text' => esc_html(__('References', 'wp-job-portal'))),
                    // (object) array('id' => 8, 'text' => esc_html(__('Languages', 'wp-job-portal'))));
                    $resumesections = WPJOBPORTALincluder::getJSModel('fieldordering')->getResumeSections();
                    $sectionarray = array();
                    foreach ($resumesections as $section) {
                        $sectionarray[] = (object) array('id' => $section->section, 'text' => esc_html(wpjobportal::wpjobportal_getVariableValue($section->fieldtitle)));
                    }
                } else {
                    $sectionarray = array(
                    (object) array('id' => 1, 'text' => esc_html(__('Personal Information', 'wp-job-portal'))));
                }

                    if(isset(wpjobportal::$_data[0]['userfield']->userfieldtype)){
                        if(wpjobportal::$_data[0]['userfield']->userfieldtype == 'text' || wpjobportal::$_data[0]['userfield']->userfieldtype == 'email' || wpjobportal::$_data[0]['userfield']->userfieldtype == 'date' || wpjobportal::$_data[0]['userfield']->userfieldtype == 'textarea'){
                            $fieldtypes = array(
                                (object) array('id' => 'text', 'text' => esc_html(__('Text Field', 'wp-job-portal'))),
                                (object) array('id' => 'date', 'text' => esc_html(__('Date', 'wp-job-portal'))),
                                (object) array('id' => 'email', 'text' => esc_html(__('Email Address', 'wp-job-portal'))),
                                (object) array('id' => 'textarea', 'text' => esc_html(__('Text Area', 'wp-job-portal')))
                            );
                            $fieldtype_Array = array('class' => 'inputbox one wpjobportal-form-select-field', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);');
                        }else{
                            $fieldtypes = array(
                                (object) array('id' => 'text', 'text' => esc_html(__('Text Field', 'wp-job-portal'))),
                                (object) array('id' => 'checkbox', 'text' => esc_html(__('Check Box', 'wp-job-portal'))),
                                (object) array('id' => 'date', 'text' => esc_html(__('Date', 'wp-job-portal'))),
                                (object) array('id' => 'combo', 'text' => esc_html(__('Drop Down', 'wp-job-portal'))),
                                (object) array('id' => 'email', 'text' => esc_html(__('Email Address', 'wp-job-portal'))),
                                (object) array('id' => 'radio', 'text' => esc_html(__('Radio Button', 'wp-job-portal'))),
                                (object) array('id' => 'textarea', 'text' => esc_html(__('Text Area', 'wp-job-portal'))),
                                (object) array('id' => 'multiple', 'text' => esc_html(__('Multi Select', 'wp-job-portal'))),
                                (object) array('id' => 'file', 'text' => esc_html(__('File Upload', 'wp-job-portal')))

                            );
                            if(in_array('advanceresumebuilder', wpjobportal::$_active_addons) && wpjobportal::$_data[0]['fieldfor'] == 3){
                                $fieldtypes[] = (object) array('id' => 'resumesection', 'text' => esc_html(__('Resume Section', 'wp-job-portal')));
                            }
                            $fieldtype_Array = array('class' => 'inputbox one wpjobportal-form-select-field', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);','disabled'=>'disabled');
                        }
                    }else{
                        $fieldtypes = array(
                            (object) array('id' => 'text', 'text' => esc_html(__('Text Field', 'wp-job-portal'))),
                            (object) array('id' => 'checkbox', 'text' => esc_html(__('Check Box', 'wp-job-portal'))),
                            (object) array('id' => 'date', 'text' => esc_html(__('Date', 'wp-job-portal'))),
                            (object) array('id' => 'combo', 'text' => esc_html(__('Drop Down', 'wp-job-portal'))),
                            (object) array('id' => 'email', 'text' => esc_html(__('Email Address', 'wp-job-portal'))),
                            (object) array('id' => 'radio', 'text' => esc_html(__('Radio Button', 'wp-job-portal')))

                        );
                        $fieldtype_Array = array('class' => 'inputbox one', 'data-validation' => 'required', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);');
                    }
            ?>
            <form id="wpjobportal-form" class="wpjobportal-form" method="post" action="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_fieldordering&task=saveuserfield")); ?>">
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Field Type', 'wp-job-portal')); ?>
                        <span style="color: red;" >*</span>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('userfieldtype', $fieldtypes, isset(wpjobportal::$_data[0]['userfield']->userfieldtype) ? wpjobportal::$_data[0]['userfield']->userfieldtype : 'text', '',$fieldtype_Array),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <div class="wpjobportal-form-wrapper" id="for-combo-wrapper" style="display:none;">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Parent Field', 'wp-job-portal')); ?>
                        <span style="color: red;" >*</span>
                    </div>
                    <div class="wpjobportal-form-value" id="for-combo"></div>    	
                </div>
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Field Title', 'wp-job-portal')); ?>
                        <span style="color: red;" >*</span>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('fieldtitle', isset(wpjobportal::$_data[0]['userfield']->fieldtitle) ? wpjobportal::$_data[0]['userfield']->fieldtitle : '', array('class' => 'inputbox one wpjobportal-form-input-field', 'data-validation' => 'required')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <?php if(!isset(wpjobportal::$_data[0]['userfield']->id) || ((wpjobportal::$_data[0]['userfield']->isuserfield == 1 || wpjobportal::$_data[0]['userfield']->cannotshowonlisting == 0) && !((wpjobportal::$_data[0]['userfield']->fieldfor == 3 && wpjobportal::$_data[0]['userfield']->section != 1 )))){ ?>
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Show on listing', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('showonlisting', $yesno, isset(wpjobportal::$_data[0]['userfield']->showonlisting) ? wpjobportal::$_data[0]['userfield']->showonlisting : 0, '', array('class' => 'inputbox one wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <?php } ?>
                <?php if (wpjobportal::$_data[0]['fieldfor'] == 3) { ?>
                    <div class="wpjobportal-form-wrapper">
                        <div class="wpjobportal-form-title">
                            <?php echo esc_html(__('Resume Section', 'wp-job-portal')); ?>
                            <span style="color: red;" >*</span>
                        </div>
                        <div class="wpjobportal-form-value">
                            <?php 
                                if(isset(wpjobportal::$_data[0]['userfield'])){
                                    $farray = array('class' => 'inputbox one wpjobportal-form-select-field', 'data-validation' => 'required', 'disabled' => 'true');
                                }else{
                                    $farray = array('class' => 'inputbox one wpjobportal-form-select-field', 'data-validation' => 'required', 'onChange' => 'disableListingField()', 'onChange' => 'comboOfFieldsBySection(this.value)');
                                }
                                echo wp_kses(WPJOBPORTALformfield::select('section', $sectionarray, isset(wpjobportal::$_data[0]['userfield']->section) ? wpjobportal::$_data[0]['userfield']->section : '', '', $farray),WPJOBPORTAL_ALLOWED_TAGS); echo '<span class="wpjobportal-fieldordering-warning">[ '.esc_html(__('Section cannot be changeable in edit case','wp-job-portal')).' ]</span>';
                            ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if(!isset(wpjobportal::$_data[0]['userfield']->id) || wpjobportal::$_data[0]['userfield']->cannotunpublish == 0){ ?>
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Published', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('published', $yesno, isset(wpjobportal::$_data[0]['userfield']->published) ? wpjobportal::$_data[0]['userfield']->published : 1, '', array('class' => 'inputbox one wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Required', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('required', $yesno, isset(wpjobportal::$_data[0]['userfield']->required) ? wpjobportal::$_data[0]['userfield']->required : 0, '', array('class' => 'inputbox one wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <?php } ?>
                <?php if(!isset(wpjobportal::$_data[0]['userfield']->id) || wpjobportal::$_data[0]['userfield']->cannotsearch == 0){ ?>
                    <div class="wpjobportal-form-wrapper">
                        <div class="wpjobportal-form-title">
                            <?php echo esc_html(__('Search', 'wp-job-portal'));//esc_html(__('User Search', 'wp-job-portal')); ?>
                        </div>
                        <div class="wpjobportal-form-value">
                            <?php echo wp_kses(WPJOBPORTALformfield::select('search_user', $yesno, isset(wpjobportal::$_data[0]['userfield']->search_user) ? wpjobportal::$_data[0]['userfield']->search_user : 1, '', array('class' => 'inputbox one wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Place Holder', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('placeholder', isset(wpjobportal::$_data[0]['userfield']->placeholder) ? wpjobportal::$_data[0]['userfield']->placeholder : '', array('class' => 'inputbox one wpjobportal-form-input-field','maxlength'=>225)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <div class="wpjobportal-form-wrapper">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Description', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::text('description', isset(wpjobportal::$_data[0]['userfield']->description) ? wpjobportal::$_data[0]['userfield']->description : '', array('class' => 'inputbox one wpjobportal-form-input-field','maxlength'=>225)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <?php
                    $optionsvaule = '';
                    if(isset(wpjobportal::$_data[0]['userfieldparams']) && !empty(wpjobportal::$_data[0]['userfieldparams'])){
                        $optionsvaule = implode("\n", wpjobportal::$_data[0]['userfieldparams']);
                    }
                ?>
                <div class="wpjobportal-form-wrapper" id="divValues">
                    <div class="wpjobportal-form-title">
                        <?php echo esc_html(__('Options', 'wp-job-portal')); ?>
                    </div>
                    <div class="wpjobportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::textarea('options', $optionsvaule, array('class'=>'inputbox one wpjobportal-form-textarea-field','rows'=>10)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                    <div class="wpjobportal-form-description">
                        <?php echo esc_html(__('Use enter key to separate options','wp-job-portal')); ?>
                    </div>
                </div>
                <?php
                // this functionality is only for custom fields
                if (isset(wpjobportal::$_data[0]['userfield']->isuserfield) && wpjobportal::$_data[0]['userfield']->isuserfield == 1) { ?>
                    <div class="wpjobportal-form-wrapper wpjobportal-form-visible-wrapper">
                        <div class="wpjobportal-form-title"><?php echo esc_html(__('Visible', 'wp-job-portal')); ?></div>
                        <div class="wpjobportal-form-value wpj-visisble-parent-field">
                            <?php echo wp_kses(WPJOBPORTALformfield::select('visibleParent', WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsForVisibleCombobox(wpjobportal::$_data['fieldfor'],isset(wpjobportal::$_data[0]['userfield']->field) ? wpjobportal::$_data[0]['userfield']->field : '',isset(wpjobportal::$_data[0]['userfield']->id) ? wpjobportal::$_data[0]['userfield']->id : '',wpjobportal::$_data[0]['userfield']->section), isset(wpjobportal::$_data[0]['visibleparams']['visibleParent']) ? wpjobportal::$_data[0]['visibleparams']['visibleParent'] : '', esc_html(__('Select Parent', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field wpjobportal-form-input-field-visible', 'onchange' => 'getChildForVisibleCombobox(this.value);')), WPJOBPORTAL_ALLOWED_TAGS); ?>
                            <span id="visibleValue">
                                <?php echo wp_kses(WPJOBPORTALformfield::select('visibleValue', isset(wpjobportal::$_data[0]['visibleValue']) ? wpjobportal::$_data[0]['visibleValue'] : '', isset(wpjobportal::$_data[0]['visibleparams']['visibleValue']) ? wpjobportal::$_data[0]['visibleparams']['visibleValue'] : '', esc_html(esc_html(__('Select Child', 'wp-job-portal'))), array('class' => 'inputbox one wpjobportal-form-select-field wpjobportal-form-input-field-visible')), WPJOBPORTAL_ALLOWED_TAGS); ?>
                            </span>
                            <?php echo wp_kses(WPJOBPORTALformfield::select('visibleCondition', $equalnotequal, isset(wpjobportal::$_data[0]['visibleparams']['visibleCondition']) ? wpjobportal::$_data[0]['visibleparams']['visibleCondition'] : '2', esc_html(__('Select Condition', 'wp-job-portal')), array('class' => 'inputbox one wpjobportal-form-select-field wpjobportal-form-input-field-visible')), WPJOBPORTAL_ALLOWED_TAGS); ?>
                        </div>
                        <div class="wpjobportal-form-desc">
                            <?php echo esc_html(__('To use this feature please fill the above three fields.', 'wp-job-portal')); ?>
                        </div>
                    </div>
                <?php } ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('id', isset(wpjobportal::$_data[0]['userfield']->id) ? esc_html(wpjobportal::$_data[0]['userfield']->id) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('isuserfield', isset(wpjobportal::$_data[0]['userfield']->isuserfield) ? esc_html(wpjobportal::$_data[0]['userfield']->isuserfield) : 1),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('fieldfor', wpjobportal::$_data[0]['fieldfor']),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('section_value', isset(wpjobportal::$_data[0]['userfield']->section) ? esc_html(wpjobportal::$_data[0]['userfield']->section) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('ordering', isset(wpjobportal::$_data[0]['userfield']->ordering) ? esc_html(wpjobportal::$_data[0]['userfield']->ordering) : '' ),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'fieldordering_saveuserfield'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('fieldname', isset(wpjobportal::$_data[0]['userfield']->field) ? esc_html(wpjobportal::$_data[0]['userfield']->field) : '' ),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('field', isset(wpjobportal::$_data[0]['userfield']->field) ? esc_html(wpjobportal::$_data[0]['userfield']->field) : '' ),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('user_field_type', isset(wpjobportal::$_data[0]['userfield']->userfieldtype) ? esc_html(wpjobportal::$_data[0]['userfield']->userfieldtype): '' ),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php //echo wp_kses(WPJOBPORTALformfield::hidden('arraynames2', $arraynames),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_field_nonce'))),WPJOBPORTAL_ALLOWED_TAGS);
                $ff = WPJOBPORTALrequest::getVar('ff','get','');
                ?>
                <div class="wpjobportal-form-button">
                    <a id="form-cancel-button" class="wpjobportal-form-cancel-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_fieldordering&ff='.esc_attr($ff))); ?>" title="<?php echo esc_html(__('cancel', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Cancel', 'wp-job-portal')); ?>
                    </a>
                    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('User Field', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                </div>
            </form>
            <?php
                $inline_js_script = "
                    jQuery(document).ready(function () {
                        toggleType(jQuery('#userfieldtype').val());
                    });

                    function disableAll() {
                        jQuery('#divValues').slideUp();
                        jQuery('.divColsRows').slideUp();
                        jQuery('#divText').slideUp();
                    }
                    function disableListingField() {
                     //   alert('function called');
                        jQuery('#showonlisting').val(0);
                        jQuery('#showonlisting').attr('disabled',true);
                        // to handle show/hide of visiblilty control fields.
                    if(section_val > 1 && section_val < 9 ){// 1 is personal section 9 is language section
                        jQuery('.wpjobportal-form-visible-wrapper').slideUp();
                    }else{
                        jQuery('.wpjobportal-form-visible-wrapper').slideDown();
                    }

                    }

                    function toggleType(type) {
                        disableAll();
                        selType(type);
                    }
                    
                    function selType(sType) {
                        var elem;
                        /*
                         text
                         checkbox
                         date
                         combo
                         email
                         textarea
                         radio
                         editor
                         depandant_field
                         multiple*/
                         jQuery('.wpjobportal-form-visible-wrapper').slideDown();
                        switch (sType) {
                            /*case 'editor':
                                jQuery('#divText').slideUp();
                                jQuery('#divValues').slideUp();
                                jQuery('.divColsRows').slideUp();
                                jQuery('div#for-combo-wrapper').hide();
                                jQuery('div#for-combo-options').hide();
                                jQuery('div#for-combo-options-head').hide();
                                break;*/
                            case 'textarea':
                                jQuery('#divText').slideUp();
                                jQuery('.divColsRows').slideDown();
                                jQuery('#divValues').slideUp();
                                jQuery('div#for-combo-wrapper').hide();
                                jQuery('div#for-combo-options').hide();
                                jQuery('div#for-combo-options-head').hide();
                                break;
                            case 'email':
                            //case 'password':
                            case 'text':
                                jQuery('#divText').slideDown();
                                jQuery('div#for-combo-wrapper').hide();
                                jQuery('div#for-combo-options').hide();
                                jQuery('div#for-combo-options-head').hide();
                                break;
                            case 'combo':
                            case 'multiple':
                                jQuery('#divValues').slideDown();
                                //jQuery('div#for-combo-wrapper').hide();
                                jQuery('div#for-combo-options').hide();
                                //jQuery('div#for-combo-options-head').hide();
                                break;
                            /*case 'depandant_field':
                                comboOfFields();
                                break;*/
                            case 'radio':
                            case 'checkbox':
                                //jQuery('.divColsRows').slideDown();
                                jQuery('#divValues').slideDown();
                                //jQuery('div#for-combo-wrapper').hide();
                                jQuery('div#for-combo-options').hide();
                                //jQuery('div#for-combo-options-head').hide();
                                /*
                                 if (elem=getObject('jsNames[0]')) {
                                 elem.setAttribute('mosReq',1);
                                 }
                                 */
                                break;
                            //case 'delimiter':
                            case 'file':
                                    jQuery('#section').val(1);
	                        jQuery('#section').attr('disabled',true);
                                jQuery('#showonlisting').val(0);
                                jQuery('#showonlisting').attr('disabled',true);
                                jQuery('#search_user').val(0);
                                jQuery('#search_user').attr('disabled',true);
                            	jQuery('#required').val(0);
	                        jQuery('#required').attr('disabled',true);

                                jQuery('#divText').slideUp();
                                jQuery('#divValues').slideUp();
                                jQuery('.divColsRows').slideUp();
                                jQuery('div#for-combo-wrapper').hide();
                                jQuery('div#for-combo-options').hide();
                                jQuery('div#for-combo-options-head').hide();
                            break;
                            case 'resumesection':
                            jQuery('#section').val(0);
                            jQuery('#section').attr('disabled',true);

                            jQuery('#showonlisting').val(0);
                            jQuery('#showonlisting').attr('disabled',true);

                            jQuery('#search_user').val(0);
                            jQuery('#search_user').attr('disabled',true);

                            jQuery('#required').val(0);
                            jQuery('#required').attr('disabled',true);

                            jQuery('#search_user').val(0);
                            jQuery('#search_user').attr('disabled',true);

                            jQuery('#placeholder').val('');
                            jQuery('#placeholder').attr('disabled',true);

                            jQuery('#divText').slideUp();
                            jQuery('#divValues').slideUp();
                            jQuery('.divColsRows').slideUp();
                            jQuery('div#for-combo-wrapper').hide();
                            jQuery('div#for-combo-options').hide();
                            jQuery('div#for-combo-options-head').hide();
                            jQuery('.wpjobportal-form-visible-wrapper').slideUp();
                            break;
                        }
                    }

                    /*function comboOfFields() {
                        var ff = jQuery('input#fieldfor').val();
                        var pf = jQuery('input#fieldname').val();
                        jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'fieldordering', task: 'getFieldsForComboByFieldFor', fieldfor: ff, parentfield: pf, '_wpnonce':'". esc_attr(wp_create_nonce("get-fields-for-combo-by-field-for"))."'}, function (data) {
                            if (data) {
                                console.log(data);
                                var d = jQuery.parseJSON(data);
                                jQuery('div#for-combo').html(d);
                                jQuery('div#for-combo-wrapper').show();
                            }
                        });
                    }*/

                    function comboOfFieldsBySection(section_value) {
                        //var section_value = jQuery('input#section_value').val();
                        jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'fieldordering', task: 'getFieldsForComboBySection', sectionfor: section_value, '_wpnonce':'". esc_attr(wp_create_nonce("get-fields-for-combo-by-section"))."'}, function (data) {
                            if (data) {
                                console.log(data);
                                var d = jQuery.parseJSON(data);
                                jQuery('div.wpj-visisble-parent-field').html(d);
                            }
                        });
                    }

                    function getDataOfSelectedField() {
                        var field = jQuery('select#parentfield').val();
                        jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'fieldordering', task: 'getSectionToFillValues', pfield: field, '_wpnonce':'". esc_attr(wp_create_nonce("get-section-to-fill-values"))."'}, function (data) {
                            if (data) {
                                console.log(data);
                                var d = jQuery.parseJSON(data);
                                jQuery('div#for-combo-options-head').show();
                                jQuery('div#for-combo-options').html(d);
                                jQuery('div#for-combo-options').show();
                            }
                        });
                    }

                    function getNextField(divid,object) {
                        var textvar = divid + '[]';
                        var fieldhtml = \"<span class='input-field-wrapper' ><input type='text' name='\" + textvar + \"' class='inputbox one user-field'  /><img class='input-field-remove-img' src='". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/remove.png' /></span>\";
                        jQuery(object).before(fieldhtml);
                    }

                    function getObject(obj) {
                        var strObj;
                        if (document.all) {
                            strObj = document.all.item(obj);
                        } else if (document.getElementById) {
                            strObj = document.getElementById(obj);
                        }
                        return strObj;
                    }

                    function insertNewRow() {
                        var fieldhtml = '<span class=\"input-field-wrapper\" ><input name=\"values[]\" id=\"values[]\" value=\"\" class=\"inputbox one user-field\" type=\"text\" /><img class=\"input-field-remove-img\" src=\"". esc_url(WPJOBPORTAL_PLUGIN_URL)."includes/images/remove.png\" /></span>';
                        jQuery('#user-field-val-button').before(fieldhtml);
                    }
                    jQuery(document).ready(function () {
                        jQuery('body').delegate('img.input-field-remove-img', 'click', function () {
                            jQuery(this).parent().remove();
                        });
                    });

                    function getChildForVisibleCombobox(val) {
                    jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', val: val, wpjobportalme: 'fieldordering', task: 'getChildForVisibleCombobox', '_wpnonce':'". esc_attr(wp_create_nonce("get-child-for-visible-combobox"))."'}, function (data) {
                        if (data != false) {
                            jQuery('#visibleValue').html(wpjobportalDecodeHTML(data));
                        }else{
                            jQuery('#visibleValue').html('<div class=\'premade-no-rec\'>". esc_html(__('No response found','wp-job-portal'))."</div>');
                        }
                    });//jquery closed
                }

                function wpjobportalDecodeHTML(html) {
                    var txt = document.createElement('textarea');
                    txt.innerHTML = html;
                    return txt.value;
                }
                    ";
                wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
            ?>
        </div>
    </div>
</div>
