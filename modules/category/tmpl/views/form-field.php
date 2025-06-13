<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal optional
* From Field
*/
?>

	<div class="wpjobportal-form-wrapper">
        <div class="wpjobportal-form-title">
	    	<?php echo esc_html(__('Title', 'wp-job-portal')); ?>
	    	<span style="color: red;" >*</span>
	    </div>
	    <div class="wpjobportal-form-value">
	    	<?php echo wp_kses(WPJOBPORTALformfield::text('cat_title', isset(wpjobportal::$_data[0]->cat_title) ? wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data[0]->cat_title) : '', array('class' => 'inputbox one wpjobportal-form-input-field', 'data-validation' => 'required')), WPJOBPORTAL_ALLOWED_TAGS) ?>
	    </div>
	</div>
	<div class="wpjobportal-form-wrapper">
        <div class="wpjobportal-form-title">
	    	<?php echo esc_html(__('Parent Category', 'wp-job-portal')); ?>
	    </div>
	    <div class="wpjobportal-form-value">
	    	<?php echo wp_kses(WPJOBPORTALformfield::select('parentid', WPJOBPORTALincluder::getJSModel('category')->getCategoryForCombobox(), isset(wpjobportal::$_data[0]->parentid) ? wpjobportal::$_data[0]->parentid : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Category', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	    </div>
	</div>
	<div class="wpjobportal-form-wrapper">
        <div class="wpjobportal-form-title">
	    	<?php echo esc_html(__('Published', 'wp-job-portal')); ?>
	    </div>
	    <div class="wpjobportal-form-value">
	    	<?php echo wp_kses(WPJOBPORTALformfield::radiobutton('isactive', array('1' => esc_html(__('Yes', 'wp-job-portal')), '0' => esc_html(__('No', 'wp-job-portal'))), isset(wpjobportal::$_data[0]->isactive) ? wpjobportal::$_data[0]->isactive : 1, array('class' => 'radiobutton')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	    </div>
	</div>
	<div class="wpjobportal-form-wrapper">
        <div class="wpjobportal-form-title">
	    	<?php echo esc_html(__('Default', 'wp-job-portal')); ?>
	    </div>
	    <div class="wpjobportal-form-value">
	    	<?php echo wp_kses(WPJOBPORTALformfield::radiobutton('isdefault', array('1' => esc_html(__('Yes', 'wp-job-portal')), '0' => esc_html(__('No', 'wp-job-portal'))), isset(wpjobportal::$_data[0]->isdefault) ? esc_html(wpjobportal::$_data[0]->isdefault) : 0, array('class' => 'radiobutton')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	    </div>
	</div>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('id', isset(wpjobportal::$_data[0]->id) ? esc_html(wpjobportal::$_data[0]->id) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('ordering', isset(wpjobportal::$_data[0]->ordering) ? esc_html(wpjobportal::$_data[0]->ordering) : '' ),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportal_isdefault', isset(wpjobportal::$_data[0]->isdefault) ? esc_html(wpjobportal::$_data[0]->isdefault) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'category_savecategory'),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_category_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<div class="wpjobportal-form-button">
	    <a id="form-cancel-button" class="wpjobportal-form-cancel-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_category')); ?>" title="<?php echo esc_html(__('cancel', 'wp-job-portal')); ?>">
	    	<?php echo esc_html(__('Cancel', 'wp-job-portal')); ?>
	    </a>
	    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Category', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	</div>
