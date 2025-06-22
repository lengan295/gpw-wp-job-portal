<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
 * @param company      company details - optional
 * @param inputprefix  prefix to concat with input name and id - optional
 * @param fields       company fields - optional
 */
if (!isset($company) && !wpjobportal::$_common->wpjp_isadmin()) {
	$company = null;
    $email = $userinfo->emailaddress;
}else{
    $email = '';
}
if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
    $startNod = "company[";
    $endnote = "]";
    $sys = "company";
}else{
    $startNod = "";
    $endnote = "";
    $sys = "";
}
if (!isset($inputprefix)) {
    $inputprefix = '';
}
if (!isset($fields)) {
    $fields = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(1);
}

$formfields = array();

foreach($fields AS $field){
	$content = '';
    switch ($field->field){
        case 'name':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'name'.$endnote, isset($company->name) ? $company->name : null, array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'tagline':
            //if(in_array('tag', wpjobportal::$_active_addons)){
                $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'tagline'.$endnote, isset($company->tagline) ? $company->tagline : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));;
            ///}
        break;
        case 'contactemail':
            $content = WPJOBPORTALformfield::email($inputprefix.$startNod.'contactemail'.$endnote,isset($company->contactemail) ? $company->contactemail : $email,array('data-validation' => 'email'.'  '.$field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'url':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'url'.$endnote, isset($company->url) ? $company->url : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'tax_code':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'tax_code'.$endnote, isset($company->tax_code) ? $company->tax_code : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'contact_name':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'contact_name'.$endnote, isset($company->contact_name) ? $company->contact_name : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'contact_phone':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'contact_phone'.$endnote, isset($company->contact_phone) ? $company->contact_phone : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'description':
        	$content = WPJOBPORTALformfield::editor($inputprefix.$sys.'description', isset($company->description) ? $company->description : '',array('class' => 'wjportal-form-textarea-field'));
        break;
        case 'city':
            $content = WPJOBPORTALformfield::text($inputprefix.$sys.'city', '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class'=>'wpjobportal-company-form-city-field'));
            $content .= WPJOBPORTALformfield::hidden('cityforedit', isset($company->multicity) ? $company->multicity : '');
        break;
        case 'address1':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'address1'.$endnote, isset($company->address1) ? $company->address1 : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
        break;
        case 'address2':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'address2'.$endnote, $company ? $company->address2 : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
            break;
        case 'logo':
        	ob_start();
            ?>
            <div class="wjportal-form-upload">
                <div class="wjportal-form-upload-btn-wrp">
                    <span class="wjportal-form-upload-btn-wrp-txt"><?php echo isset($company->logofilename) ? esc_html($company->logofilename) : '' ;?> </span>
                    <span class="wjportal-form-upload-btn">
                        <?php echo esc_html(__('Upload Image','wp-job-portal')); ?>
                        <input id="logo" name="logo" type="file">
                    </span>
                </div>
                <?php
                if (isset($company->logofilename) && $company->logofilename != "") {
                    $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('data_directory');
                    $wpdir = wp_upload_dir();
                    $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
                    $class = '';
                }else{
                    $path = '';
                    $class = 'none';
                }?>
                <div class="wjportal-form-image-wrp" style="display:<?php echo esc_attr($class); ?> ;">
                    <img class="rs_photo wjportal-form-image" src="<?php echo esc_url($path); ?>" id="rs_photo" />
                    <img id="wjportal-form-delete-image" src="<?php echo esc_html(WPJOBPORTAL_PLUGIN_URL);?>includes/images/no.png" alt="<?php echo esc_html(__('cross','wp-job-portal')); ?>">
                </div>
                <?php
                $logoformat = wpjobportal::$_config->getConfigValue('image_file_type');
                $maxsize = wpjobportal::$_config->getConfigValue('company_logofilezize');
                echo '<div class="wjportal-form-help-txt">'.esc_html($logoformat).'</div>';
                echo '<div class="wjportal-form-help-txt">'.esc_html(__("Maximum","wp-job-portal")).' '.esc_html($maxsize).' Kb'.'</div>';
                ?>

            </div>
                <?php
                $content = ob_get_clean();
        break;
        case 'facebook_link':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'facebook_link'.$endnote, $company ? $company->facebook_link : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
            break;
        case 'youtube_link':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'youtube_link'.$endnote, $company ? $company->youtube_link : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
            break;
        case 'twiter_link':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'twiter_link'.$endnote, $company ? $company->twiter_link : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
            break;
        case 'linkedin_link':
            $content = WPJOBPORTALformfield::text($inputprefix.$startNod.'linkedin_link'.$endnote, $company ? $company->linkedin_link : '',array('data-validation' => $field->validation,'placeholder' => wpjobportal::wpjobportal_getVariableValue($field->placeholder),'class' => 'inputbox wjportal-form-input-field'));
            break;
        case 'termsandconditions':
            if(!isset($company)){
                $termsandconditions_flag = 1;
                $termsandconditions_fieldtitle = $field->fieldtitle;
                // $content = get_the_permalink(wpjobportal::$_configuration['terms_and_conditions_page_company']);
            }
            break;
        default:
            $content = wpjobportal::$_wpjpcustomfield->formCustomFields($field);
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
