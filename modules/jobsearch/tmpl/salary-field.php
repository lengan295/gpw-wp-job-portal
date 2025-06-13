<?php
/**
* @param field    salary field object
* @param job      job object - optional
*/
$salarytypelist = array(
    (object) array('id'=>WPJOBPORTAL_SALARY_NEGOTIABLE,'text'=>esc_html(__("Negotiable",'wp-job-portal'))),
    (object) array('id'=>WPJOBPORTAL_SALARY_FIXED,'text'=>esc_html(__("Fixed",'wp-job-portal'))),
    (object) array('id'=>WPJOBPORTAL_SALARY_RANGE,'text'=>esc_html(__("Range",'wp-job-portal'))),
);
?>
<?php if (wpjobportal::$theme_chk == 1) { ?>
    <div class="wjportal-form-5-fields">
        <div class="wjportal-form-inner-fields">        
            <?php echo wp_kses(WPJOBPORTALformfield::select('salarytype', $salarytypelist, isset(wpjobportal::$_data['filter']['salarytype']) ? wpjobportal::$_data['filter']['salarytype']: 0, esc_html(__("Select",'wp-job-portal')).' '.esc_html(__("Salary Type",'wp-job-portal')), array('class' => 'inputbox sal wjportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
        </div>
        <div class="wjportal-form-inner-fields">    
            <?php echo wp_kses(WPJOBPORTALformfield::text('salaryfixed',isset(wpjobportal::$_data['filter']['salaryfixed']) ? wpjobportal::$_data['filter']['salaryfixed']: 0, array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=>'e.g 45000')),WPJOBPORTAL_ALLOWED_TAGS); ?>
        </div>
        <div class="wjportal-form-inner-fields">    
            <?php echo wp_kses(WPJOBPORTALformfield::text('salarymin', isset(wpjobportal::$_data['filter']['salarymin']) ? wpjobportal::$_data['filter']['salarymin']: 0, array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=>'e.g 3000')),WPJOBPORTAL_ALLOWED_TAGS); ?>
        </div>
        <div class="wjportal-form-inner-fields">    
            <?php echo wp_kses(WPJOBPORTALformfield::text('salarymax', isset(wpjobportal::$_data['filter']['salarymax']) ? wpjobportal::$_data['filter']['salarymax']: 0, array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=>'e.g 6000')),WPJOBPORTAL_ALLOWED_TAGS); ?>
        </div>
        <div class="wjportal-form-inner-fields">    
            <?php echo wp_kses(WPJOBPORTALformfield::select('salaryduration', WPJOBPORTALincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(wpjobportal::$_data['filter']['salaryduration']) ? wpjobportal::$_data['filter']['salaryduration']:0, esc_html(__('Select','wp-job-portal')), array('class' => 'inputbox sal wjportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
        </div>
    </div>
<?php } else { ?>
<div class="wjportal-form-5-fields">
    <div class="wjportal-form-inner-fields">        
        <?php echo wp_kses(WPJOBPORTALformfield::select('salarytype', $salarytypelist,'', esc_html(__("Select",'wp-job-portal')).' '.esc_html(__("Salary Type",'wp-job-portal')), array('class' => 'inputbox sal wjportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
    </div>
    <div class="wjportal-form-inner-fields">    
        <?php echo wp_kses(WPJOBPORTALformfield::text('salaryfixed','', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 45000','wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
    </div>
    <div class="wjportal-form-inner-fields">    
        <?php echo wp_kses(WPJOBPORTALformfield::text('salarymin', '', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 3000','wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
    </div>
    <div class="wjportal-form-inner-fields">    
        <?php echo wp_kses(WPJOBPORTALformfield::text('salarymax', '', array('class' => 'inputbox sal wjportal-form-input-field','placeholder'=> esc_html(__('e.g 6000','wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
    </div>
    <div class="wjportal-form-inner-fields">    
        <?php echo wp_kses(WPJOBPORTALformfield::select('salaryduration', WPJOBPORTALincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), WPJOBPORTALincluder::getJSModel('salaryrangetype')->getDefaultSalaryRangeTypeId(), esc_html(__('Select','wp-job-portal')), array('class' => 'inputbox sal wjportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
    </div>
</div>
<?php } ?>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
    jQuery(document).ready(function ($) {
        jQuery(document).delegate('#salarytype', 'change', function(){
            var salarytype = jQuery(this).val();
            if(salarytype == 1){ //negotiable
                jQuery('#salaryfixed').hide();
                jQuery('#salarymin').hide();
                jQuery('#salarymax').hide();
                jQuery('#salaryduration').hide();
                jQuery('.wjportal-form-symbol').hide();
            }else if(salarytype == 2){ //fixed
                jQuery('#salaryfixed').show();
                jQuery('#salarymin').hide();
                jQuery('#salarymax').hide();
                jQuery('#salaryduration').show();
                jQuery('.wjportal-form-symbol').show();
            }else if(salarytype == 3){ //range
                jQuery('#salaryfixed').hide();
                jQuery('#salarymin').show();
                jQuery('#salarymax').show();
                jQuery('#salaryduration').show();
                jQuery('.wjportal-form-symbol').show();
            }else{ //not selected
                jQuery('#salaryfixed').hide();
                jQuery('#salarymin').hide();
                jQuery('#salarymax').hide();
                jQuery('#salaryduration').hide();
                jQuery('.wjportal-form-symbol').hide();
            }
        });

        jQuery('#salarytype').change();
        });

    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
