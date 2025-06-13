<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param field 		fieldordering field object
 * @param title 		field title or name
 * @param required  	is field required
 * @param content 		field html
 * @param description 	field description
 */
if (isset($field)) {
	if (!isset($title)) {
		$title = $field->fieldtitle;
	}
	if (!isset($required)) {
		$required = $field->required;
	}
	 if (!isset($description)) {
	 	$description = $field->description;
	 }
} else {
    if (!isset($title)) {
        $title = '';
    }
    if (!isset($required)) {
        $required = false;
    }
    if (!isset($description)) {
        $description = '';
    }
}
$visibleclass = "";
if (isset($field->visibleparams) && $field->visibleparams != ''){
    $visibleclass = " visible js-form-custm-flds-wrp";
}
?>
<div class="wjportal-form-row <?php echo esc_attr($visibleclass);?>">
    <div class="wjportal-form-title">

        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($title)); ?>
        
        <?php if($required == 1 && WPJOBPORTALrequest::getVar('wpjobportalme') != "jobsearch"): ?>
        	<font>*</font>
    	<?php endif; ?>

    </div>
    <div class="wjportal-form-value">

        <?php echo wp_kses($content, WPJOBPORTAL_ALLOWED_TAGS); ?>

        <?php if(!empty($description)): ?>
        	<div class="wjportal-form-help-txt"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($description)); ?></div>
        <?php endif; ?>

    </div>
</div>
