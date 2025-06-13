<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**

 */
$email = '';

$fields = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(5);

$formfields = array();

foreach($fields AS $field){
    // If the Elegant Design feature is enabled, set the field placeholder to the field title if it's empty.
    if(WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled()){
        if (empty($field->placeholder)) {
            $field->placeholder = $field->fieldtitle;
        }
    }
	$content = '';
    switch ($field->field){
        case 'full_name':
            $content = WPJOBPORTALformfield::text('full_name', null, array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'email':
            $content = WPJOBPORTALformfield::text('email', null, array('data-validation' => 'email'.'  '.$field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'phone':
            $content = WPJOBPORTALformfield::text('phone', null, array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'message':
            $content = WPJOBPORTALformfield::textarea('message', '', array('class' => 'inputbox one wjportal-form-textarea-field', 'rows' => '7', 'cols' => '25', $field->validation));
        break;
        case 'resume':
        	ob_start();
            ?>
            <div class="wjportal-form-upload">
                <div class="wjportal-form-upload-btn-wrp">
                    <span class="wjportal-form-upload-btn-wrp-txt"></span>
                    <span class="wjportal-form-upload-btn">
                        <?php echo esc_html(__('Upload Resume','wp-job-portal')); ?>
                        <input id="resumefiles" name="resumefiles" type="file" >
                    </span>
                </div>
                <?php
                $logoformat = wpjobportal::$_config->getConfigValue('document_file_type');
                $maxsize = wpjobportal::$_config->getConfigValue('document_file_size');
                echo '<div class="wjportal-form-help-txt">'.esc_html($logoformat).'</div>';
                echo '<div class="wjportal-form-help-txt">'.esc_html(__("Maximum","wp-job-portal")).' '.esc_html($maxsize).' Kb'.'</div>';
                ?>

            </div>
                <?php
                $content = ob_get_clean();
        break;

        default:
            //$content = wpjobportal::$_wpjpcustomfield->formCustomFields($field);
    	break;
    }
    if (!empty($content)) {
        $formfields[] = array(
            'field' => $field,
            'content' => $content
        );
    }
}
return $formfields;
