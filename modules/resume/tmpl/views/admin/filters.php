<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
?>
	<?php echo wp_kses(WPJOBPORTALformfield::text('searchtitle', wpjobportal::$_data['filter']['searchtitle'], array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Title', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::text('searchname', wpjobportal::$_data['filter']['searchname'], array('class' => 'inputbox wpjobportal-form-input-field', 'placeholder' => esc_html(__('Name', 'wp-job-portal')))),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::select('searchjobcategory', WPJOBPORTALincluder::getJSModel('category')->getCategoriesForCombo('kb'), wpjobportal::$_data['filter']['searchjobcategory'], esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Category', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::select('searchjobtype', WPJOBPORTALincluder::getJSModel('jobtype')->getJobTypeForCombo(), wpjobportal::$_data['filter']['searchjobtype'], esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Job Type', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::text('datestart', wpjobportal::$_data['filter']['datestart'], array('class' => 'custom_date wpjobportal-form-input-field', 'placeholder' => esc_html(__('Date Start', 'wp-job-portal')), 'autocomplete' => 'off')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::text('dateend', wpjobportal::$_data['filter']['dateend'], array('class' => 'custom_date wpjobportal-form-input-field', 'placeholder' => esc_html(__('Date End', 'wp-job-portal')), 'autocomplete' => 'off')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php 
	if($extra!=1){
		echo wp_kses(WPJOBPORTALformfield::select('status', WPJOBPORTALincluder::getJSModel('common')->getListingStatus(), wpjobportal::$_data['filter']['status'], esc_html(__('Select Status', 'wp-job-portal')), array('class' => 'inputbox wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS);
	}
	 ?>
	<?php if(in_array('featureresume', wpjobportal::$_active_addons)){ ?>
		<div class="wpjobportal-form-checkbox-field">
			<?php echo wp_kses(WPJOBPORTALformfield::checkbox('featured', array('1' => esc_html(__('Featured', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['featured']) ? wpjobportal::$_data['filter']['featured'] : 0, array('class ' => 'checkbox default-hidden')),WPJOBPORTAL_ALLOWED_TAGS); ?>
		</div>
	<?php } ?>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::submitbutton('btnsubmit', esc_html(__('Search', 'wp-job-portal')), array('class' => 'button wpjobportal-form-search-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::button('reset', esc_html(__('Reset', 'wp-job-portal')), array('class' => 'button wpjobportal-form-reset-btn', 'onclick' => 'resetFrom();')),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('sortby', wpjobportal::$_data['sortby']),WPJOBPORTAL_ALLOWED_TAGS); ?>
	<?php echo wp_kses(WPJOBPORTALformfield::hidden('sorton', wpjobportal::$_data['sorton']),WPJOBPORTAL_ALLOWED_TAGS); ?>
