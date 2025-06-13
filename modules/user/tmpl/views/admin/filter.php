<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param js-job Form Filter  
*/
?>
<?php echo wp_kses(WPJOBPORTALformfield::text('searchname', wpjobportal::$_data['filter']['searchname'], array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Name', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::text('searchusername', wpjobportal::$_data['filter']['searchusername'], array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Word Press user login', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::text('searchcompany', wpjobportal::$_data['filter']['searchcompany'], array('class' => 'inputbox wpjobportal-form-input-field default-hidden', 'placeholder' => esc_html(__('Company', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::text('searchresume', wpjobportal::$_data['filter']['searchresume'], array('class' => 'inputbox wpjobportal-form-input-field default-hidden', 'placeholder' => esc_html(__('Resume', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::select('searchrole', WPJOBPORTALincluder::getJSModel('common')->getRolesForCombo(), wpjobportal::$_data['filter']['searchrole'], esc_html(__('Select Role', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::submitbutton('btnsubmit', esc_html(__('Search', 'wp-job-portal')), array('class' => 'button wpjobportal-form-search-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::button('reset', esc_html(__('Reset', 'wp-job-portal')), array('class' => 'button wpjobportal-form-reset-btn', 'onclick' => 'resetFrom();')),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php /*
<span id="showhidefilter"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/filter-down.png"/></span>
*/ ?>