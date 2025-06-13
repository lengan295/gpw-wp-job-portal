<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* 
*/
?>
<div class="wpjobportal-form-wrapper">
    <div class="wpjobportal-form-title">
    	<?php echo esc_html(__('Name', 'wp-job-portal')); ?>
    	<font class="required-notifier">*</font>
    </div>
    <div class="wpjobportal-form-value">
    	<?php echo wp_kses(WPJOBPORTALformfield::text('name', isset(wpjobportal::$_data[0]->name) ? wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data[0]->name) : '', array('class' => 'inputbox one wpjobportal-form-input-field', 'data-validation' => 'required')),WPJOBPORTAL_ALLOWED_TAGS) ?>
    </div>
</div>
<div class="wpjobportal-form-wrapper">
    <div class="wpjobportal-form-title">
        <?php echo esc_html(__('International Name', 'wp-job-portal')); ?>
    </div>
    <div class="wpjobportal-form-value">
        <?php echo wp_kses(WPJOBPORTALformfield::text('internationalname', isset(wpjobportal::$_data[0]->internationalname) ? wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data[0]->internationalname) : '', array('class' => 'inputbox one wpjobportal-form-input-field')),WPJOBPORTAL_ALLOWED_TAGS) ?>
    </div>
</div>
<div class="wpjobportal-form-wrapper">
    <div class="wpjobportal-form-title">
        <?php echo esc_html(__('Native Name', 'wp-job-portal')); ?>
    </div>
    <div class="wpjobportal-form-value">
        <?php echo wp_kses(WPJOBPORTALformfield::text('localname', isset(wpjobportal::$_data[0]->localname) ? wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data[0]->localname) : '', array('class' => 'inputbox one wpjobportal-form-input-field')),WPJOBPORTAL_ALLOWED_TAGS) ?>
    </div>
</div>

<div class="wpjobportal-form-wrapper">
    <div class="wpjobportal-form-title">
    	<?php echo esc_html(__('Published', 'wp-job-portal')); ?>
    </div>
    <div class="wpjobportal-form-value">
    	<?php echo wp_kses(WPJOBPORTALformfield::radiobutton('enabled', array('1' => esc_html(__('Yes', 'wp-job-portal')), '0' => esc_html(__('No', 'wp-job-portal'))), isset(wpjobportal::$_data[0]->enabled) ? wpjobportal::$_data[0]->enabled : 1, array('class' => 'radiobutton')),WPJOBPORTAL_ALLOWED_TAGS); ?>
    </div>
</div>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('id', isset(wpjobportal::$_data[0]->id) ? esc_html(wpjobportal::$_data[0]->id) : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'country_savecountry'),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_country_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
<div class="wpjobportal-form-button">
    <a id="form-cancel-button" class="wpjobportal-form-cancel-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_country')); ?>" title="<?php echo esc_html(__('cancel', 'wp-job-portal')); ?>">
    	<?php echo esc_html(__('Cancel', 'wp-job-portal')); ?>
	</a>
    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Country', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
</div>