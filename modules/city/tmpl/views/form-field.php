<?php
/**
*
*/
$countryid = get_option("wpjobportal_countryid_for_city" );
$stateid = get_option("wpjobportal_stateid_for_city" );
?>

<div class="wpjobportal-form-wrapper">
    <div class="wpjobportal-form-title">
        <?php echo esc_html(__('State', 'wp-job-portal')); ?>
    </div>
	<div class="wpjobportal-form-value">
        <?php echo wp_kses(WPJOBPORTALformfield::select('stateid', WPJOBPORTALincluder::getJSModel('state')->getStatesForCombo(isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0]->countryid : $countryid ), isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0]->stateid : $stateid, esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('State', 'wp-job-portal')), array('class' => 'inputbox one wpjobportal-form-select-field')),WPJOBPORTAL_ALLOWED_TAGS); ?>
    </div>
</div>

<div class="wpjobportal-form-wrapper">
    <div class="wpjobportal-form-title">
        <?php echo esc_html(__('City', 'wp-job-portal')); ?>
        <span style="color: red;" >*</span>
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
        <?php echo esc_html(__('Latitude', 'wp-job-portal')); ?>
    </div>
    <div class="wpjobportal-form-value">
        <?php echo wp_kses(WPJOBPORTALformfield::text('latitude', isset(wpjobportal::$_data[0]->latitude) ? wpjobportal::$_data[0]->latitude : '', array('class' => 'inputbox one wpjobportal-form-input-field')),WPJOBPORTAL_ALLOWED_TAGS) ?>
    </div>
</div>
<div class="wpjobportal-form-wrapper">
    <div class="wpjobportal-form-title">
        <?php echo esc_html(__('Longitude', 'wp-job-portal')); ?>
    </div>
    <div class="wpjobportal-form-value">
        <?php echo wp_kses(WPJOBPORTALformfield::text('longitude', isset(wpjobportal::$_data[0]->longitude) ? wpjobportal::$_data[0]->longitude : '', array('class' => 'inputbox one wpjobportal-form-input-field')),WPJOBPORTAL_ALLOWED_TAGS) ?>
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
<?php echo wp_kses(WPJOBPORTALformfield::hidden('id', isset(wpjobportal::$_data[0]->id) ? wpjobportal::$_data[0]->id : ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php
if (isset(wpjobportal::$_data[0]->id) AND ( wpjobportal::$_data[0]->id != 0)) {
    echo wp_kses(WPJOBPORTALformfield::hidden('isedit', 1),WPJOBPORTAL_ALLOWED_TAGS);
}
?>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'city_savecity'),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wp-job-portal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
<?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_city_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
<div class="wpjobportal-form-button">
    <a id="form-cancel-button" class="wpjobportal-form-cancel-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_city&countryid='.$countryid)); ?>&stateid=<?php echo esc_attr($stateid); ?>" title="<?php echo esc_html(__('cancel', 'wp-job-portal')); ?>">
        <?php echo esc_html(__('Cancel', 'wp-job-portal')); ?>
    </a>
    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('City', 'wp-job-portal')), array('class' => 'button wpjobportal-form-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
</div>
