<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param wp job portal 
* Edit & ADD form For User 
* wp job Portal Users
*/
if(isset($user)){

}
function printFormField($title, $field, $description) {
    $html = '<div class="wjportal-form-row">
    <div class="wjportal-form-title">' . wp_kses(wpjobportal::wpjobportal_getVariableValue($title),WPJOBPORTAL_ALLOWED_TAGS) . '</div>
    <div class="wjportal-form-value">' . $field;
    if (!empty($description)) {
        $html .= '<div class="wjportal-form-help-txt">'.$description.'</div>';
    }
    $html .= '</div></div>';
    return $html;
}
function getRowForForm($text, $value,$themecall=null) {
    $html = '<div class="wjportal-form-row">
    <div class="wjportal-form-title">' . wp_kses(wpjobportal::wpjobportal_getVariableValue($text),WPJOBPORTAL_ALLOWED_TAGS) . ':</div>
    <div class="wjportal-form-value">' . $value . '</div>
    </div>';
    return $html;
}
?>

<?php
$fieldslist = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(4);
foreach ($fieldslist AS $field) {
    switch ($field->field) {
        case 'skype':
        if($field->published == 1) {
            $req = '';
            $optional = 'true';
            $title = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
            $description = '';
            if($field->required == 1) {
                $req = 'required';
                $title .= '<font class="required-notifier">*</font>';
                $optional = '';
            }
            $field = WPJPOBPORTALformfield::text($field->field, isset(wpjobportal::$_data[0]->skype) ? wpjobportal::$_data[0]->skype : '', array('class' => 'inputbox one wjportal-form-input-field', 'data-validation' => $req,'data-validation-optional'=>$optional));
            echo wp_kses(printFormField($title, $field, $description), WPJOBPORTAL_ALLOWED_TAGS);
        }
        break;
        case 'wpjobportal_user_email':
        if(!isset(wpjobportal::$_data[0])){
            if($field->published == 1) {
                $req = '';
				$title = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
                $description = $field->description;
                if($field->required == 1) {
                    $req = 'email';
                    $title .= '<font class="required-notifier">*</font>';
                }
                $field = WPJOBPORTALformfield::text('wpjobportal_user_email', isset(wpjobportal::$_data[0]->emailaddress) ? wpjobportal::$_data[0]->emailaddress : '', array('class' => 'inputbox one wjportal-form-input-field', 'data-validation' => $req, 'placeholder' => $field->placeholder));
                echo wp_kses(printFormField($title, $field, $description), WPJOBPORTAL_ALLOWED_TAGS);
            }
        }
        break;
        case 'wpjobportal_user_first':
        if($field->published == 1) {
            $req = '';
            $title = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
            $description = $field->description;
            if($field->required == 1) {
                $req = 'required';
                $title .= '<font class="required-notifier">*</font>';
            }
            $field = WPJOBPORTALformfield::text('wpjobportal_user_first', isset(wpjobportal::$_data[0]->first_name) ? wpjobportal::$_data[0]->first_name : '', array('class' => 'inputbox one wjportal-form-input-field', 'data-validation' => $req, 'placeholder' => $field->placeholder));
            echo wp_kses(printFormField($title, $field, $description), WPJOBPORTAL_ALLOWED_TAGS);
        }
        break;
        case 'wpjobportal_user_last':
        if($field->published == 1) {
            $req = '';
            $title = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
            $description = $field->description;
            if($field->required == 1) {
                $req = 'required';
                $title .= '<font class="required-notifier">*</font>';
            }
            $field = WPJOBPORTALformfield::text('wpjobportal_user_last', isset(wpjobportal::$_data[0]->last_name) ? wpjobportal::$_data[0]->last_name : '', array('class' => 'inputbox one wjportal-form-input-field', 'data-validation' => $req, 'placeholder' => $field->placeholder));
            echo wp_kses(printFormField($title, $field, $description), WPJOBPORTAL_ALLOWED_TAGS);
        }
        break;
        case 'wpjobportal_user_login':
        if(!isset(wpjobportal::$_data[0])){
            if($field->published == 1) {
                $req = '';
		$title = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
                $description = $field->description;
                if($field->required == 1) {
                    $req = 'required';
                    $title .= '<font class="required-notifier">*</font>';
                }
                $field = WPJOBPORTALformfield::text('wpjobportal_user_login', isset(wpjobportal::$_data[0]->name) ? wpjobportal::$_data[0]->name : '', array('class' => 'inputbox one wjportal-form-input-field', 'data-validation' => $req, 'placeholder' => $field->placeholder));
                echo wp_kses(printFormField($title, $field, $description), WPJOBPORTAL_ALLOWED_TAGS);
            }
        }
        break;
        case 'photo':   
        if($field->published == 1) {
            $req = '';
            $title = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
            $description = $field->description;
            ?>
            <div class="wjportal-form-row">
                <div class="wjportal-form-title" for="wjportal_user_profile">
                    <?php echo esc_html($title);?>
                </div>
                <div class="wjportal-form-value">
                    <?php
                                    /////**********Use OF Field Ordering Method**********/////
                    $themecall='';
                    $text = '';
                    $photo_required ='';
                    $imgpath = '';

                    $img = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
                    $display = 'none';
                    $photoname = '';
                    if(isset(wpjobportal::$_data[0]->photo) && !empty(wpjobportal::$_data[0]->photo)){
                        $wpdir = wp_upload_dir();
                        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
                        $img = $wpdir['baseurl'] . '/' . $data_directory . '/data/profile/profile_' . wpjobportal::$_data[0]->uid . '/profile/' . wpjobportal::$_data[0]->photo;
                        $display = '';
                        $photoname  = wpjobportal::$_data[0]->photo;
                    }

                    $fieldvalue = '
                    <div class="wjportal-form-upload-btn-wrp">
                    <span class="wjportal-form-upload-btn-wrp-txt">'.$photoname.'
                    </span>
                    <span class="wjportal-form-upload-btn">
                    '.esc_html(__("Upload Image","wp-job-portal")).'
                    <input type="file" name="photo" class="photo wjportal-form-upload-field" id="photo" value='.$photoname.' />
                    </span>
                    </div>
                    <div class="wjportal-form-image-wrp" style="display:'.$display.';">
                    <img class="rs_photo wjportal-form-image" id="rs_photo" src="' . $img . '" alt="'.esc_html(__('Profile image','wp-job-portal')).'"/>';
                    if(isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0]->id)){
                        $fieldvalue .= '<img id="wjportal-form-delete-image" onClick="return removeLogo('.wpjobportal::$_data[0]->uid.')" alt="cross" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/no.png" />';
                    }else{
                        $fieldvalue .= '<img id="wjportal-form-delete-image"  alt="cross" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/no.png" />';
                    }
                    $fieldvalue .=  '</div>';
                    $logoformat = wpjobportal::$_config->getConfigurationByConfigName('image_file_type');
                    $maxsize = wpjobportal::$_config->getConfigurationByConfigName('image_file_size');
                    $p_detail = '<div class="wjportal-form-help-txt"> ('.$logoformat.') </div>';
                    $p_detail .= '<div class="wjportal-form-help-txt">  ('.esc_html(__("Max logo size allowed","wp-job-portal")).' '.$maxsize.' Kb) </div>';
                    if (!empty($description)) {
                        $p_detail .= '<div class="wjportal-form-help-txt">'.$description.'</div>';
                    }
                    $fieldvalue .= $p_detail;
                    ?>
                    <div class="wjportal-form-upload">
                        <?php echo wp_kses($fieldvalue, WPJOBPORTAL_ALLOWED_TAGS) ;?>
                    </div>
                </div>
            </div>
            <?php
        }
        break;
        default:
        if($field->isuserfield == 1) {
            $req = '';
            $title = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
            $description = $field->description;
            if($field->required == 1) {
                $req = 'required';
                $title .= '<font class="required-notifier">*</font>';
            }
            $field = wpjobportal::$_wpjpcustomfield->formCustomFields($field);
            echo wp_kses(printFormField($title, $field, $description), WPJOBPORTAL_ALLOWED_TAGS);
        }
        break;
    }
} 
?>
<!-- Static Input Get Validate -->
<?php  if(!isset(wpjobportal::$_data[0])){  ?>
    <div class="wjportal-form-row">
        <div class="wjportal-form-title" for="password">
            <?php echo  esc_html(__("Password","wp-job-portal")); ?> <font>*</font>
        </div>
        <div class="wjportal-form-value">
            <input name="wpjobportal_user_pass" id="password" data-validation="required" class="required wjportal-form-input-field" type="password" placeholder="<?php echo esc_html(__("Password",'wp-job-portal')); ?>"/>
        </div>
    </div>
    <div class="wjportal-form-row">
        <div class="wjportal-form-title" for="password_again">
            <?php echo esc_html(__('Password Again','wp-job-portal')); ?> <font>*</font>
        </div>
        <div class="wjportal-form-value">
            <input name="wpjobportal_user_pass_confirm" id="password_again" data-validation="required" class="required wjportal-form-input-field" type="password" placeholder="<?php echo esc_html(__('Password again','wp-job-portal')); ?>"/>
        </div>
    </div>
    <div class="wjportal-form-row wjportal-form-roles">
        <?php
        do_action('register_form');
        ?>
    </div>
    <?php
    $config_array = wpjobportal::$_config->getConfigByFor('captcha');
    $google_recaptcha_3 = false;

    if ($config_array['cap_on_reg_form'] == 1) {
        if ($config_array['captcha_selection'] == 1) { // Google recaptcha

            if ($config_array['recaptcha_version'] == 1) { // Google recaptcha 2
                ?>
                <div class="g-recaptcha" data-sitekey="<?php echo esc_attr($config_array['recaptcha_publickey']);?>"></div>
                <?php
            }else{
                $google_recaptcha_3 = true;
            }
        } else { // own captcha
            $captcha = new WPJOBPORTALcaptcha;
            echo wp_kses($captcha->getCaptchaForForm(), WPJOBPORTAL_ALLOWED_TAGS);
        }
    }
    echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportalpageid', wpjobportal::wpjobportal_getPageid()), WPJOBPORTAL_ALLOWED_TAGS);
    echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportal_nonce', esc_html(wp_create_nonce('wpjobportal_nonce'))), WPJOBPORTAL_ALLOWED_TAGS);
    ?>
    <div class="wjportal-form-btn-wrp">
        <?php if($google_recaptcha_3 == false){ ?>
            <input type="submit" id="save" class="button wjportal-form-btn wjportal-save-btn g-recaptcha" value="<?php echo esc_html(__('Register New Account','wp-job-portal')); ?>"/>
        <?php }else{ ?>
            <input type="submit" id="save" data-sitekey="<?php echo esc_attr($config_array['recaptcha_publickey']);?>" data-callback='onSubmit' data-action='submit' class="button wjportal-form-btn wjportal-save-btn g-recaptcha" value="<?php echo esc_html(__('Register New Account','wp-job-portal')); ?>"/>
        <?php } ?>
    </div>
    <input type="hidden" name="wpjobportal_jobs_register_nonce" value="<?php echo esc_html(wp_create_nonce('wpjobportal-jobs-register-nonce')); ?>"/>
    <?php } ?>
