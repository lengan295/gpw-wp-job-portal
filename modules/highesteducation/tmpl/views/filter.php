<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
*
*/
?>
<?php echo wp_kses(WPJOBPORTALformfield::text('title', wpjobportal::$_data['filter']['title'], array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Title', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::select('status', WPJOBPORTALincluder::getJSModel('common')->getstatus(), is_numeric(wpjobportal::$_data['filter']['status']) ? wpjobportal::$_data['filter']['status'] : '', esc_html(__('Select Status', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::submitbutton('btnsubmit', esc_html(__('Search', 'wp-job-portal')), array('class' => 'button wpjobportal-form-search-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::button('reset', esc_html(__('Reset', 'wp-job-portal')), array('class' => 'button wpjobportal-form-reset-btn', 'onclick' => 'resetFrom();')),WPJOBPORTAL_ALLOWED_TAGS); ?>


<?php echo wp_kses(WPJOBPORTALformfield::select('pagesize', array((object) array('id'=>20,'text'=>20), (object) array('id'=>50,'text'=>50), (object) array('id'=>100,'text'=>100)), wpjobportal::$_data['filter']['pagesize'],esc_html(__("Records per page",'wp-job-portal')), array('class' => ' wpjobportal-form-select-field wpjobportal-right','onchange'=>'document.wpjobportalform.submit();')),WPJOBPORTAL_ALLOWED_TAGS);?>

