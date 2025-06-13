<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param  wp-job-portal
*Filter
*/
?>
<?php echo wp_kses(WPJOBPORTALformfield::text('searchname', wpjobportal::$_data['filter']['searchname'], array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Name', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::select('status', WPJOBPORTALincluder::getJSModel('common')->getstatus(), is_numeric(wpjobportal::$_data['filter']['status']) ? wpjobportal::$_data['filter']['status'] : '', esc_html(__('Select Status', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
<div class="wpjobportal-form-checkbox-field">
    <?php echo wp_kses(WPJOBPORTALformfield::checkbox('city', array('1' => esc_html(__('Has cities', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['city']) ? wpjobportal::$_data['filter']['city'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
</div>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::submitbutton('btnsubmit', esc_html(__('Search', 'wp-job-portal')), array('class' => 'button wpjobportal-form-search-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::button('reset', esc_html(__('Reset', 'wp-job-portal')), array('class' => 'button wpjobportal-form-reset-btn', 'onclick' => 'resetFrom();')),WPJOBPORTAL_ALLOWED_TAGS); ?>