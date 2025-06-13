<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALResumeFormlayout {

    public $config_array_sec=array();
    public $resumefields=array();
    public $class_prefix = '';
    public $themecall = 0;
    public $show_terms_and_conditions = 0;
    public $terms_and_conditions_title = '';

    function __construct(){
        $this->config_array_sec = wpjobportal::$_config->getConfigByFor('resume');
        $fieldsordering = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(3); // resume fields
        $this->resumefields = $fieldsordering;
        wpjobportal::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            wpjobportal::$_data['fieldtitles'][$field->field] = $field->fieldtitle;
            wpjobportal::$_data[2][$field->section][$field->field] = $field;
        }
        if(WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()){
            wpjobportal::$_data['userinfo'] = WPJOBPORTALincluder::getObjectClass('user')->getEmployerProfile();
        }

        if(wpjobportal::$theme_chk == 2){/// code to manage class prefix for diffrent template cases
            $this->class_prefix = 'jsjb-jh';
            $this->themecall = 2;

        }elseif(wpjobportal::$theme_chk == 1){
            $this->class_prefix = '';
            $this->themecall = 1;
        }else{
            $this->class_prefix = '';
        }
    }

    function getFieldTitleByField($field){
        return wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fieldtitles'][$field]);
    }

    function getResumeFormUserFieldJobManager($title, $field,$required) {
        $html = '<div class="js-col-md-12 js-form-wrapper">
        <div class="js-col-md-12 js-form-title '.esc_attr($this->class_prefix).'-bigfont">' . esc_attr($title);
        if($required==1){
            $html .= '<span class="'.esc_attr($this->class_prefix).'-error-msg">*</span>';
        }
        $html .= '</div>
            <div class="js-col-md-12 js-form-value">' . $field . '</div>
        </div>';

        return $html;
    }



    function getResumeFormUserField($field, $object , $section , $sectionid, $ishidden,$themecall=null) {
        $visibleclass = "";
        if (isset($field->visibleparams) && $field->visibleparams != ''){
            $visibleclass = " visible js-form-custm-flds-wrp ";
        }
        $id = isset($object->id)  ? $object->id : NULL;
        $params = isset($object->params) ? $object->params : NULL;
        $data = NULL;
        $result = wpjobportal::$_wpjpcustomfield->formCustomFieldsResume($field , $id , $params,null,$section , $sectionid, $ishidden,$themecall);
        if( isset($result['value']) && $result['value'] != null){
            if(null !=$themecall){
                $data .= '<div class="resume-row-wrapper form resumefieldswrapper '.esc_attr($visibleclass).' ">';
                $data .= '  <label class="resumefieldtitle" for="">';
                $data .=        wpjobportal::wpjobportal_getVariableValue($result['title']);
                                if($field->required == 1){
                $data .= '          <span class="error-msg">*</span>';
                                }
                $data .= '  </label>';
                $data .= '  <div class="resumefieldvalue">';
                $data .=        $result['value'];
                        $description = $field->description;
                        if(!empty($description)){
                            $data .= '<div class="wjportal-form-help-txt">'. esc_html(wpjobportal::wpjobportal_getVariableValue($description)).'</div>';
                        }
                $data .= '  </div>
                          </div>';

            }else{
                $data .= '<div class="resume-row-wrapper form resumefieldswrapper '.esc_attr($visibleclass).' ">';
                $data .= '  <label class="resumefieldtitle" for="">';
                $data .=        wpjobportal::wpjobportal_getVariableValue($result['title']);
                                if($field->required == 1){
                $data .= '          <span class="error-msg">*</span>';
                                }
                $data .= '  </label>';
                $data .= '  <div class="resumefieldvalue">';
                $data .=        $result['value'];
                        $description = $field->description;
                        if(!empty($description)){
                            $data .= '<div class="wjportal-form-help-txt">'. esc_html(wpjobportal::wpjobportal_getVariableValue($description)).'</div>';
                        }
                $data .= '  </div>
                          </div>';
            }
            return $data;
        }
        return $result;
    }


    function getResumeCheckBoxField($field, $fieldValue){
        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;

        $name = 'sec_1['.$fieldName.']';
        $data = '
            <div class="resume-row-wrapper form wjportal-form-row">
                <div class="row-title wjportal-form-title">';
                    if ($required == 1) {
                        $data .= wpjobportal::wpjobportal_getVariableValue($fieldtitle) . ' <font color="red"> *</font>';
                        $cssclass = "required";
                    }else {
                        $data .= wpjobportal::wpjobportal_getVariableValue($fieldtitle);
                        $cssclass = "";
                    }
                $data .= '</div>
                <div class="row-value wjportal-form-value">
                    <div class="checkbox-field wpjp-form-value wjportal-searchable-wrp">';
                        $data .= WPJOBPORTALformfield::checkbox($name, array('1' => wpjobportal::wpjobportal_getVariableValue($fieldtitle)), $fieldValue);
                        $data .= '
                    </div>
                    ';
                $description = $field->description;
                if(!empty($description)){
                    $data .= '<div class="wjportal-form-help-txt">'. esc_html(wpjobportal::wpjobportal_getVariableValue($description)).'</div>';
                }
                $data .= '
                </div>
            </div>';
        return $data;
    }

    function getResumeSelectFieldJobManager($fieldtitle,$fieldName,$fieldValue,$required,$column){
        $html="";
        if($column==4){
            $html .= '<div class="js-col-md-3 '.esc_attr($this->class_prefix).'-field-padding">';
        }else{
            $html .= '<div class="js-col-md-12 js-form-wrapper">';

        }
        $html .= '
            <div class="js-col-md-12 js-form-title '.esc_attr($this->class_prefix).'-bigfont">' . $fieldtitle;
            if($required==1){
                $html .='<span class="'.esc_attr($this->class_prefix).'-error-msg">*</span>';
            }
            $html .='</div>
            <div class="js-col-md-12 js-form-value">' . $fieldValue . '</div>
        </div>';
        return $html;
    }

    function getResumeSelectField($field, $fieldValue,$column=0,$themecall=null) {

        $fieldtitle="";
        if(isset($field->fieldtitle)) $fieldtitle = $field->fieldtitle;
        $fieldName="";
        if(isset($field->field)) $fieldName = $field->field;
        $required="";
        if(isset($field->required)) $required = $field->required;
        if(null != $themecall){
            $data = '
                <div class="wjportal-form-row">
                    <div class="wjportal-form-title">
                        <label " for="' . $fieldName . '">' . wpjobportal::wpjobportal_getVariableValue($fieldtitle);
                            if ($required == 1) {
                                $data .= '<span class="error-msg" style="color: red;"> *</span>';
                            }
            $data .= '
                        </label>
                    </div>
                    <div class="wjportal-form-value">
                        ' . $fieldValue .'
                    </div>
                </div>';
        }else{
            $data = '
                <div class="wjportal-form-row">
                    <div class="wjportal-form-title">
                        <label " for="' . $fieldName . '">' . wpjobportal::wpjobportal_getVariableValue($fieldtitle);
                            if ($required == 1) {
                                $data .= '<span class="error-msg" style="color: red;"> *</span>';
                            }
            $data .= '
                        </label>
                    </div>
                    <div class="wjportal-form-value">
                        ' . $fieldValue .'
                        ';
                $description = $field->description;
                if(!empty($description)){
                    $data .= '<div class="wjportal-form-help-txt">'. esc_html(wpjobportal::wpjobportal_getVariableValue($description)).'</div>';
                }
                $data .= '
                    </div>
                </div>';
        }
        return $data;
    }
    function getResumeSectionTitleJobPortal($sectionid,$title) {

        $html='<h2 id="jsresume_sectionid'.esc_attr($sectionid).'" class="wjportal-resume-section-title">' . wpjobportal::wpjobportal_getVariableValue($title) . '</h2>';

        return $html;
    }

    function getSectionTitle($sectionFor, $title , $sectionid,$themecall) {
        if ($sectionFor == "education") {
            $sectionFor = "institute";
        }
        switch ($sectionFor) {
            case 'personal':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobPortal($sectionid,$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.esc_attr($sectionid).'" class="wjportal-resume-section-title"><strong>' . wpjobportal::wpjobportal_getVariableValue($title) . '</strong></div>';
                }
            break;
            case 'address':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobPortal($sectionid,$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.esc_attr($sectionid).'" class="wjportal-resume-section-title">' . wpjobportal::wpjobportal_getVariableValue($title) . '</div>';
                }

            break;
            case 'institute':
                if(null!=$themecall){
                    $html= $this->getResumeSectionTitleJobPortal($sectionid,$title);
                }else{
                    $html = apply_filters('wpjobportal_addons_resume_formTitile',false,$sectionid,$title);
                }

            break;
            case 'employer':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobPortal($sectionid,$title);
                }else{
                    $html = '<div id="jsresume_sectionid'.esc_attr($sectionid).'" class="wjportal-resume-section-title">' . wpjobportal::wpjobportal_getVariableValue($title) . '</div>';
                }

            break;
            case 'skills':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobPortal($sectionid,$title);
                }else{
                    $html = apply_filters('wpjobportal_addons_resume_formTitile',false,$sectionid,$title);
                }

            break;
            case 'language':
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobPortal($sectionid,$title);
                }else{
                    $html = apply_filters('wpjobportal_addons_resume_formTitile',false,$sectionid,$title);
                }

            break;
		default:
                if(null!=$themecall){
                    $html=$this->getResumeSectionTitleJobPortal($sectionid,$title);
                }else{
                    $html = apply_filters('wpjobportal_addons_resume_formTitile',false,$sectionid,$title);
                }
            break;
        }
        return $html;
    }

    function getFieldForPersonalSectionJobManager($fieldtitle,$fieldName,$fieldValue,$required,$extraattr,$columns = 0){

        $data="";

        if($columns == 3){
            $data .= '<div class="js-col-md-4 '.esc_attr($this->class_prefix).'-field-padding">';
        }else{
            $data .= '<div class="js-col-md-12 js-form-wrapper">';
        }
        $data .= '
            <div class="js-col-md-12 js-form-title '.esc_attr($this->class_prefix).'-bigfont">' . wpjobportal::wpjobportal_getVariableValue($fieldtitle);
            if ($required == 1) {
                $data .= '<span class="'.esc_attr($this->class_prefix).'-error-msg"     color: redstyle="color: red;"> *</span>';
            }
            $data .='</div>
            <div class="js-col-md-12 js-form-value">';
                $data .='<input class="inputbox form-control '.esc_attr($this->class_prefix).'-input-field';

                        if ($required == 1 ) {
                                $data .= ' required ';
                        }
                        if($fieldName == "date_of_birth" || $fieldName == "date_start" ){
                            $data .= ' custom_date ';
                            if($fieldValue = '0000-00-00 00:00:00'){
                                $fieldValue = '';
                            }
                        }
                        $data .= '"';
                        if ($fieldName == "email_address") {
                            $data .= ' data-validation="email"';
                        }
                        if ($required == 1 && $fieldName != "email_address") {
                            $data .= ' data-validation="required"';
                        }
                $name = 'sec_1['.$fieldName.']';
                $data .=        ' type="text" name="' . $name . '" id="' . $fieldName . '" value = "' .  wpjobportalphplib::wpJP_htmlspecialchars($fieldValue).'"' ;
                if (!empty($extraattr)){
                    foreach ($extraattr AS $key => $val){
                        $data .= ' ' . $key . '="' . $val . '"';
                    }
                }
                $data .= '" />';
            $data .='</div>
        </div>';
        return $data;
    }

    function getFieldForPersonalSection($field, $fieldValue, $columns = 0,$extraattr=array(),$themecall=null) {

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;
        $description = $field->description;
        $style = '';
        $jb_jm_class="";
        if($columns == 3){
            $style = ' formresumethree';
        }
        if(null != $themecall){
            $data = '
                <div class="wjportal-form-row'.$style.'">
                    <div class="wjportal-form-title">
                        <label for="' . $fieldName . '">';
                        $data .= wpjobportal::wpjobportal_getVariableValue($fieldtitle);
                            if ($required == 1) {
                                        $data .= '<span class="error-msg" style="color: red;"> *</span>';
                            }
            $data .= '</label>
                    </div>
                    <div class="wjportal-form-value">
                        <input class="inputbox wjportal-form-input-field';
                            if ($required == 1 ) {
                                $data .= ' required ';
                            }
                            if($fieldName == "date_of_birth" || $fieldName == "date_start" ){
                                $data .= ' custom_date ';
                            }
                            $data .= '"';
                            if ($fieldName == "email_address") {
                                $data .= ' data-validation="email"';
                            }
                            if ($required == 1 && $fieldName != "email_address") {
                                $data .= ' data-validation="required"';
                            }

            $name = 'sec_1['.$fieldName.']';
            $data .=        ' type="text" name="' . $name . '" id="' . $fieldName . '" value = "' .  wpjobportalphplib::wpJP_htmlspecialchars($fieldValue).'"' ;
                // adding place holder in field
            $placeholder = $field->placeholder;
            if(!empty($placeholder)){
                $data .= ' placeholder="'. esc_html(wpjobportal::wpjobportal_getVariableValue($placeholder)).'" ';
            }
            if (!empty($extraattr)){
                foreach ($extraattr AS $key => $val){
                    $data .= ' ' . $key . '="' . $val . '"';
                }
            }
                $data .= '" />
                ';
                $description = $field->description;
                if(!empty($description)){
                    $data .= '<div class="wjportal-form-help-txt">'. esc_html(wpjobportal::wpjobportal_getVariableValue($description)).'</div>';
                }
                $data .= '
                </div>
            </div>';
        }else{
            $data = '
                <div class="wjportal-form-row'.$style.'">
                    <div class="wjportal-form-title">
                        <label for="' . $fieldName . '">';
                        $data .= wpjobportal::wpjobportal_getVariableValue($fieldtitle);
                            if ($required == 1) {
                                        $data .= '<span class="error-msg" style="color: red;"> *</span>';
                            }
            $data .= '</label>
                    </div>
                    <div class="wjportal-form-value">
                        <input class="inputbox wjportal-form-input-field';
                            if ($required == 1 ) {
                                $data .= ' required ';
                            }
                            if($fieldName == "date_of_birth" || $fieldName == "date_start" ){
                                $data .= ' custom_date ';
                            }
                            $data .= '"';
                            if ($fieldName == "email_address") {
                                $data .= ' data-validation="email"';
                            }
                            if ($required == 1 && $fieldName != "email_address") {
                                $data .= ' data-validation="required"';
                            }




            $name = 'sec_1['.$fieldName.']';
            $placeholder = $field->placeholder;
            if(!empty($placeholder)){
                $data .= ' placeholder="'. esc_html(wpjobportal::wpjobportal_getVariableValue($placeholder)).'" ';
            }
            $data .=        ' type="text" name="' . $name . '" id="' . $fieldName . '" value = "' .  wpjobportalphplib::wpJP_htmlspecialchars($fieldValue).'"' ;
            if (!empty($extraattr)){
                foreach ($extraattr AS $key => $val){
                    $data .= ' ' . $key . '="' . $val . '"';
                }
            }
                $data .= '" />
                ';
                $description = $field->description;
                if(!empty($description)){
                    $data .= '<div class="wjportal-form-help-txt">'. esc_html(wpjobportal::wpjobportal_getVariableValue($description)).'</div>';
                }
                $data .= '
                </div>
            </div>';
        }
        return $data;
    }
    function getFieldForMultiSectionJobManager($fieldtitle,$fieldName,$required,$fieldValue,$field_id_for,$section, $sectionid, $ishidden){
            $html = '<div class="js-col-md-12 js-form-wrapper">
            <div class="js-col-md-12 js-form-title '.esc_attr($this->class_prefix).'-bigfont" for="'.$field_id_for.'">' . wpjobportal::wpjobportal_getVariableValue($fieldtitle);
                if ($required == 1) {
                    $html .= '<span class="'.esc_attr($this->class_prefix).'-error-msg">*</span>';
                }
              $html .='</div>
            <div class="js-col-md-12 js-form-value">';
                $data_required = '';
                $class_required = '';
                if($ishidden != ''){
                    if ($required == 1) {
                        $data_required = 'data-myrequired="required"';
                    }
                    if ($fieldName == "email_address") {
                        $data_required = 'data-myrequired="required validate-email"';
                    }
                }else{
                    if ($required == 1) {
                        $class_required = ' required';
                    }
                    if ($fieldName == "email_address") {
                        $class_required = ' required validate-email';
                    }
                }

                $html .= '<input class="inputbox form-control '.esc_attr($this->class_prefix).'-input-field '.$class_required.'" '.$data_required;

                switch ($section) {
                    case '2': $section = 'sec_2'; break;
                    case '3': $section = 'sec_3'; break;
                    case '4': $section = 'sec_4'; break;
                    case '5': $section = 'sec_5'; break;
                    case '6': $section = 'sec_6'; break;
                    case '7': $section = 'sec_7'; break;
                    case '8': $section = 'sec_8'; break;
                }
                $name = $section."[$fieldName][$sectionid]";

                $html .=    ' type="text" name="' . $name . '" id="' . $field_id_for . '" maxlength="250" value = "' .  wpjobportalphplib::wpJP_htmlspecialchars($fieldValue) . '" />';

            $html .= '</div>
        </div>';
        return $html;

    }

    function getFieldForMultiSection($field, $fieldValue, $section, $sectionid, $ishidden,$themecall ) {

        $fieldtitle = $field->fieldtitle;
        $fieldName = $field->field;
        $required = $field->required;
        $placeholder = $field->placeholder;

        $field_id_for = $fieldName.$section.$sectionid;
        if(null !=$themecall){

            $data = '
                <div class="wjportal-form-row">
                    <div class="wjportal-form-title">
                        <label for="' . $field_id_for . '">';
                            $data .= wpjobportal::wpjobportal_getVariableValue($fieldtitle);
                            if ($required == 1) {
                                        $data .= '<span class="error-msg">*</span>';
                            }
            $data .= '  </label>
                    </div>';
            $data .= '<div class="wjportal-form-value">';

            $data_required = '';
            $class_required = '';
            if($ishidden != ''){
                if ($required == 1) {
                    $data_required = 'data-myrequired="required"';
                }
                if ($fieldName == "email_address") {
                    $data_required = 'data-myrequired="required validate-email"';
                }
            }else{
                if ($required == 1) {
                    $class_required = ' required';
                }
                if ($fieldName == "email_address") {
                    $class_required = ' required validate-email';
                }
            }

            $data .= '<input class="inputbox wjportal-form-input-field'.$class_required.'" '.$data_required;

            switch ($section) {
                case '2': $section = 'sec_2'; break;
                case '3': $section = 'sec_3'; break;
                case '4': $section = 'sec_4'; break;
                case '5': $section = 'sec_5'; break;
                case '6': $section = 'sec_6'; break;
                case '7': $section = 'sec_7'; break;
                case '8': $section = 'sec_8'; break;
            }
            $name = $section."[$fieldName][$sectionid]";

            if($placeholder != ''){
                $data .= ' placeholder="'.wpjobportalphplib::wpJP_htmlspecialchars($placeholder).'" ';
            }

            $data .=    ' type="text" name="' . $name . '" id="' . $field_id_for . '" maxlength="250" value = "' .  wpjobportalphplib::wpJP_htmlspecialchars($fieldValue) . '" />
                    </div>
                </div>';

        }else{

            $data = '
                <div class="wjportal-form-row">
                    <div class="wjportal-form-title">
                        <label for="' . $field_id_for . '">';
                            $data .= wpjobportal::wpjobportal_getVariableValue($fieldtitle);
                            if ($required == 1) {
                                        $data .= '<span class="error-msg">*</span>';
                            }
            $data .= '  </label>
                    </div>';
            $data .= '<div class="wjportal-form-value">';

            $data_required = '';
            $class_required = '';
            if($ishidden != ''){
                if ($required == 1) {
                    $data_required = 'data-myrequired="required"';
                }
                if ($fieldName == "email_address") {
                    $data_required = 'data-myrequired="required validate-email"';
                }
            }else{
                if ($required == 1) {
                    $class_required = ' required';
                }
                if ($fieldName == "email_address") {
                    $class_required = ' required validate-email';
                }
            }

            $data .= '<input class="inputbox wjportal-form-input-field'.$class_required.'" '.$data_required;

            switch ($section) {
                case '2': $section = 'sec_2'; break;
                case '3': $section = 'sec_3'; break;
                case '4': $section = 'sec_4'; break;
                case '5': $section = 'sec_5'; break;
                case '6': $section = 'sec_6'; break;
                case '7': $section = 'sec_7'; break;
                case '8': $section = 'sec_8'; break;
            }
            $name = $section."[$fieldName][$sectionid]";

            $data .=    ' type="text" name="' . $name . '" id="' . $field_id_for . '" maxlength="250" value = "' .  wpjobportalphplib::wpJP_htmlspecialchars($fieldValue) . '" />
                ';
                    $description = $field->description;
                    if(!empty($description)){
                        $data .= '<div class="wjportal-form-help-txt">'. esc_html(wpjobportal::wpjobportal_getVariableValue($description)).'</div>';
                    }
                    $data .= '
                    </div>
                </div>';
        }

        return $data;
    }

    function prepareDateFormat(){

        $config_date=wpjobportal::$_config->getConfigurationByConfigName('date_format');
        if ($config_date == 'm/d/Y'){
            $dash = '/';
        }else{
            $dash = "-";
        }
        $dateformat = $config_date;
        $firstdash = wpjobportalphplib::wpJP_strpos($dateformat, $dash, 0);
        $firstvalue = wpjobportalphplib::wpJP_substr($dateformat, 0, $firstdash);
        $firstdash = $firstdash + 1;
        $seconddash = wpjobportalphplib::wpJP_strpos($dateformat, $dash, $firstdash);
        $secondvalue = wpjobportalphplib::wpJP_substr($dateformat, $firstdash, $seconddash - $firstdash);
        $seconddash = $seconddash + 1;
        $thirdvalue = wpjobportalphplib::wpJP_substr($dateformat, $seconddash, wpjobportalphplib::wpJP_strlen($dateformat) - $seconddash);
        //$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
        $js_dateformat =  $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;

        return $js_dateformat;
    }

    function getCityFieldForForm($for , $sectionid, $object, $field , $ishidden,$themecall){

        $html = '';
        switch ($for) {
            case '2':
                $cityfor = 'address'; break;
            case '3':
                $cityfor = 'institute'; break;
            case '4':
                $cityfor = 'employer'; break;
            case '7':
                $cityfor = 'reference'; break;
            break;
        }
        $data_required = '';
        $city_required = ($field->required ? 'required' : '');
        if($ishidden){
            if($city_required){
                $data_required = 'data-myrequired="required"';
                $city_required = '';
            }
        }
        $cityforedit = '';
        $data = array('city_id' => null, 'city_name' => null);
        if (isset($object->{$field->field}) AND ($object->{$field->field})) {
            $cityforedit = 1;
            $data['city_id'] = $object->{$field->field};
            //$data['city_name'] = $object->cityname ;
            $default_location_view = wpjobportal::$_config->getConfigurationByConfigName('defaultaddressdisplaytype');
            $data['city_name'] = WPJOBPORTALincluder::getJSModel('common')->getLocationForView($object->cityname, $object->statename, $object->countryname);
            /*switch ($default_location_view) {
                case 'csc':
                   $data['city_name'] .= ", " . $object->statename . ", " . $object->countryname;
                  $data['city_name'] .=", " . $object->countryname;
                    break;
                case 'cs':
                    $data['city_name'] .= ", " . $object->statename;
                    break;
                case 'cc':
                    $data['city_name'] .= ", " . $object->countryname;
                    break;
            }*/
        }
        $field_city_id="'".$cityfor.'_city_'.$sectionid."'";
        $edit_field_city_id="'".$cityfor.'cityforedit_'.$sectionid."'";
        $html .= '
            <div class="wjportal-form-row">
                <div class="wjportal-form-title">
                    <label id="'.esc_attr($cityfor).'_citymsg" for="'.esc_attr($cityfor).'_city_'.esc_attr($sectionid).'">' . wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
                        if ($field->required == 1) {
                            $html .= '<span class="error-msg">*</span>';
                        }
        $html .= '  </label>
                </div>
                <div class="wjportal-form-value">
                    <input data-for="'.esc_attr($cityfor).'_'.esc_attr($sectionid).'" class="inputbox jstokeninputcity ' . $city_required . '" '.$data_required.' type="text" name="sec_'.$for.'['.esc_attr($cityfor).'_city]['.esc_attr($sectionid).']" id="'.esc_attr($cityfor).'_city_'.esc_attr($sectionid).'" size="40" maxlength="100" value="'.$data['city_name'].'" />
                    <input type="hidden" name="sec_'.$for.'['.esc_attr($cityfor).'cityforedit]['.esc_attr($sectionid).']" id="'.esc_attr($cityfor).'cityforedit_'.esc_attr($sectionid).'" value="'. wpjobportalphplib::wpJP_htmlspecialchars($cityforedit).'" />
                    <input type="hidden" class="jscityid" name="jscityid" value="'.$data['city_id'].'" />
                    <input type="hidden" class="jscityname" name="jscityname" value="'.$data['city_name'].'" />
                    ';
                $description = $field->description;
                if(!empty($description)){
                    $html .= '<div class="wjportal-form-help-txt">'. esc_html(wpjobportal::wpjobportal_getVariableValue($description)).'</div>';
                }
                $html .= '
                </div>';
            $html .= '</div>';
        return $html;
    }

   function makeResumeSectionFields($themecall=null){
        $resume="";
        if(isset(wpjobportal::$_data[0]['personal_section'])) $resume = wpjobportal::$_data[0]['personal_section'];
        //$fields_ordering = wpjobportal::$_data[1];

        $html = '<div id="jssection_resume" class="section_wrapper jssectionwrapper ">';
        if(empty($resume->resume)){
            //$jssection_hide = (isset(wpjobportal::$_data['resumeid']) && is_numeric(wpjobportal::$_data['resumeid']))?"": 'jssection_hide';
            $jssection_hide = 'jssection_hide';
        }else{
            ///$jssection_hide = (isset(wpjobportal::$_data['resumeid']) && is_numeric(wpjobportal::$_data['resumeid']))?"": 'jssection_hide';
            $jssection_hide = '';
        }
        $sectionid = 0;
        // <div class="jsundo wjportal-resume-section-undo"><img class="jsundoimage wjportal-resume-section-undo-image" onclick="undoThisSection(this);" src="'.JURI::root().'components/com_wpjobportal/images/resume/undo-icon.png" /></div>
        // <img class="jsdeleteimage wjportal-resume-section-delete" onclick="deleteThisSection(this);" src="'.JURI::root().'components/com_wpjobportal/images/resume/delete-icon.png" />
        $html .= '<div class="section_wrapper form wjportal-resume-section jssection_wrapper '.$jssection_hide.' jssection_resume_'.esc_attr($sectionid).'">';
        foreach (wpjobportal::$_data[2][6] as $field) {
            switch ($field->field) {
                case "resume":
                    $fvalue = isset($resume->resume) ? $resume->resume : '';
                    $req = ($field->required ? 'required' : '');
                    $data_required = '';
                    if($jssection_hide){
                        if($req){
                            $data_required = 'data-myrequired="required"';
                            $req = '';
                        }
                    }
                    $html .= '
                        <div class="wpjp-form-wrapper js-col-md-12 js-form-wrapper">
                            <label id="" class="wpjp-form-title " for="resumeeditor">' . wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
                                if ($field->required == 1) {
                                    $html .= '<span class="error-msg">*</span>';
                                }
                    //$name = 'sec_6[resume]['.esc_attr($sectionid).']';
                    $name = 'resumeeditor';

                    //$value=wp_editor(isset($resume->resume) ? $resume->resume: '', 'resume', array('media_buttons' => false, 'data-validation' => $req));
                    $value=isset($resume->resume) ? $resume->resume: '';
                    $efield = WPJOBPORTALformfield::textarea('resume', $value, array('class' => 'inputbox one resumeeditor form-control '.esc_attr($this->class_prefix).'-textarea-field', 'height'=>'270px','rows'=>'10','cols'=>'40'));
                    $efield .= WPJOBPORTALformfield::hidden('resume_edit_val','');
                    $html .= '</label>
                            <div class="wpjp-form-value ">
                                '.$efield.'
                            </div>
                        </div>';
                    break;
                default:
                    $html .= $this->getResumeFormUserField($field, $resume , 6 , $sectionid, $jssection_hide,$themecall);
                break;
            }
        }
        $id = '';
        $deletethis = (empty($resume->resume)) ? 1 : 0;
        $html .= '<input type="hidden" id="deletethis6'.esc_attr($sectionid).'" class="jsdeletethissection" name="sec_6[deletethis]['.esc_attr($sectionid).']" value="'. wpjobportalphplib::wpJP_htmlspecialchars($deletethis).'">
                    <input type="hidden" id="id" name="sec_6[id]['.esc_attr($sectionid).']" value="'.$id.'">
            </div></div>';
        if(empty($resume->resume)){
            if(null !=$themecall){
                $html .= '<div class="wpjp-add-new-section-link wjportal-resume-add-new-section-btn" onclick="showResumeSection( this, \'resume\');"><i class="fa fa-plus"></i>'.esc_html(__('Add Resume','wp-job-portal')).'</div>';
            }else{
                $html .= '<div class="wpjp-add-new-section-link wjportal-resume-add-new-section-btn" onclick="showResumeSection( this, \'resume\');"><i class="fa fa-plus"></i>'.esc_html(__('Add Resume','wp-job-portal')).'</div>';

            }
        }
        return $html;
    }

    /* function makeAddressSectionFields($themecall=null) {
        $addresses=array();
        if(isset(wpjobportal::$_data[0]['address_section'])){
            $addresses = wpjobportal::$_data[0]['address_section'];
        }
        //$fields_ordering = wpjobportal::$_data[1];
        $sections_allowed = wpjobportal::$_config->getConfigurationByConfigName('max_resume_addresses');
        $j = 1;
        $html = '<div id="jssection_address" class="jssectionwrapper section_wrapper wjportal-resume-section-wrp">';
        if(empty($addresses)){
            $addresses = array();
            for ($i=0; $i < $sections_allowed; $i++) {
                $addresses[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($addresses);
            $j = $sections_allowed - $totalexistings;
            if($totalexistings < $sections_allowed){
                for ($i=0; $i < $j; $i++) {
                    $addresses[] = 'new';
                }
            }
        }

        $sectionid = 0;
        $sectionhead = 1;
        foreach ($addresses as $address) {

            //$jssection_hide = isset($address->id) ? '' :((isset(wpjobportal::$_data['resumeid']) && is_numeric(wpjobportal::$_data['resumeid']))?"": 'jssection_hide');
            $jssection_hide = isset($address->id) ? '' : 'jssection_hide';
            //$jssection_hide = isset($address->id) ? '' : '';
            $html .= '<div class="section_wrapper form wjportal-resume-section '.$jssection_hide.' jssection_address_'.esc_attr($sectionid).'">
                        <div class="wjportal-resume-section-head">'.esc_html(__('Address','wp-job-portal')). ' ' .$sectionhead++.'</div>
                        <div class="jsundo wjportal-resume-section-undo"><img class="jsundoimage wjportal-resume-section-undo-image" onclick="undoThisSection(this);" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage wjportal-resume-section-delete" onclick="deleteThisSection(this);" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/resume/delete-icon.png" />';
            foreach (wpjobportal::$_data[2][2] as $field) {
                switch ($field->field) {
                    case "address_city":
                        $for = 2;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $address, $field ,$jssection_hide,$themecall);
                        break;
                    case "address":
                        $fieldValue = isset($address->address) ? $address->address : '';
                        $html .= $this->getFieldForMultiSection($field, $fieldValue, 2, $sectionid, $jssection_hide,$themecall);
                        break;
                    case "address_location": //longitude and latitude
                        $required = ($field->required ? 'required' : '');
                        $latitude = isset($address->latitude) ? $address->latitude : '';
                        $longitude = isset($address->longitude) ? $address->longitude : '';
                        $data_required = '';
                        if($jssection_hide){
                            if($required){
                                $data_required = 'data-myrequired="required"';
                                $required = '';
                            }
                        }
                        $html .= apply_filters('wpjobportal_addons_loadadressdata_for_resume',false,$address,$required,$data_required,$sectionid,$field,$this->class_prefix,$jssection_hide);
                    break;

                    default:
                        $html .= $this->getResumeFormUserField($field, $address , 2 ,  $sectionid, $jssection_hide,$themecall);
                    break;
                }
            }
            $id = isset($address->id) ? $address->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis2'.esc_attr($sectionid).'" class="jsdeletethissection" name="sec_2[deletethis]['.esc_attr($sectionid).']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_2[id]['.esc_attr($sectionid).']" value="'.$id.'">';
                    if(null !=$themecall){
                        $html .= '<hr class="'.esc_attr($this->class_prefix).'-resume-section-sep" />';
                    }
                    $html .= '</div>';
            $sectionid++;
        }
        $html .= '</div>';
        if($j > 0){
            if(null !=$themecall){
                $html .= '<div class="wpjp-add-new-section-link  '.esc_attr($this->class_prefix).'-resume-addnewbutton" onclick="showResumeSection( this ,\'address\');">
                <span class="'.esc_attr($this->class_prefix).'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.esc_html(__('Add New','wp-job-portal')).' '. esc_html(__('Address','wp-job-portal')).'
                </span></div>';
            }else{
                $html .= '<div class="wpjp-add-new-section-link wjportal-resume-add-new-section-btn" onclick="showResumeSection( this ,\'address\');"><i class="fa fa-plus"></i>'.esc_html(__('Add New','wp-job-portal')).' '. esc_html(__('Address','wp-job-portal')).'</div>';
            }
        }
        return $html;
    }

    function makeEmployerSectionFields($themecall=null){
        $employers="";
        if(isset(wpjobportal::$_data[0]['employer_section'])){
            $employers = wpjobportal::$_data[0]['employer_section'];
        }
        $sections_allowed = wpjobportal::$_config->getConfigurationByConfigName('max_resume_employers');
        $js_dateformat = $this->prepareDateFormat();
        $j = 1;
        $html = '<div id="jssection_employer" class="jssectionwrapper section_wrapper wjportal-resume-section-wrp">';
        if(empty($employers)){
            $employers = array();
            for ($i=0; $i < $sections_allowed; $i++) {
                $employers[] = 'new';
            }
        }else{
            //Edit case to show remaining allowed sections
            $totalexistings = count($employers);
            $j = $sections_allowed - $totalexistings;
            if($totalexistings < $sections_allowed){
                for ($i=0; $i < $j; $i++) {
                    $employers[] = 'new';
                }
            }
        }

        $sectionid = 0;
        $sectionhead = 1;
        foreach ($employers as $employer) {
            $jssection_hide = isset($employer->id) ? '' : 'jssection_hide';
            $html .= '<div class="section_wrapper form wjportal-resume-section jssection_wrapper '.$jssection_hide.' jssection_employer_'.esc_attr($sectionid).'">
                        <div class="wjportal-resume-section-head">'.esc_html(__('Employer','wp-job-portal')).' '.$sectionhead++.'</div>
                        <div class="jsundo wjportal-resume-section-undo"><img class="jsundoimage wjportal-resume-section-undo-image" onclick="undoThisSection(this);" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/resume/undo-icon.png" /></div>
                        <img class="jsdeleteimage wjportal-resume-section-delete" onclick="deleteThisSection(this);" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/resume/delete-icon.png" />';
            $counter = 0;
            foreach (wpjobportal::$_data[2][4] as $field) {
                switch ($field->field) {
                    case "employer":
                        $fvalue = isset($employer->employer) ? $employer->employer : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,$themecall);
                        break;
                    case "employer_position":
                        $fvalue = isset($employer->employer_position) ? $employer->employer_position : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,$themecall);
                        break;
                   //
                    case "employer_from_date":
                    case "employer_to_date":
                    case "employer_current_status":
                        if($counter == 0){
                                $field_obj = '';
                                foreach (wpjobportal::$_data[2][4] as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "employer_from_date":
                                            $html .= '
                                                <div id="fromdate'.esc_attr($sectionid).'" class="wjportal-form-row">
                                                    <div class="wjportal-form-title">
                                                        <label for="employer_from_date4'.esc_attr($sectionid).'">' . wpjobportal::wpjobportal_getVariableValue($field_obj->fieldtitle);
                                                            if ($field_obj->required == 1) {
                                                                $html .= '<span class="'.esc_attr($this->class_prefix).'-error-msg error-msg">*</span>';
                                                            }
                                            $html .='   </label>
                                                    </div>
                                                    <div class="wjportal-form-value">';
                                                         $fieldValue = isset($employer->employer_from_date) ? date_i18n(wpjobportal::$_configuration['date_format'],strtotime($employer->employer_from_date)) : '';
                                                        $html .= '<input type="text" class="input wjportal-form-date-field form-control '.esc_attr($this->class_prefix).'-input-field custom_date" name="sec_4[employer_from_date][]" id="employer_from_date4'.esc_attr($sectionid).'" value='. $fieldValue .'>';
                                            $html .='</div>
                                                </div>';
                                            break;
                                        case "employer_to_date":
                                        $fvalue = isset($employer->employer_current_status) ? $employer->employer_current_status : '';
                                        if($fvalue==1){
                                            $display="none";
                                        }else if($fvalue==0){
                                            $display="";
                                        }else{
                                            $display="";
                                        }
                                            $html .= '
                                                <div class="wjportal-form-row" id="resto_date'.esc_attr($sectionid).'" style="display:'.$display.'" >
                                                    <div class="wjportal-form-title">
                                                        <label class="wpjp-form-title " for="employer_to_date4'.esc_attr($sectionid).'">' . wpjobportal::wpjobportal_getVariableValue($field_obj->fieldtitle);
                                                            if ($field_obj->required == 1) {
                                                                $html .= '<span class="'.esc_attr($this->class_prefix).'-error-msg error-msg">*</span>';
                                                            }
                                            $html .='   </label>
                                                    </div>
                                                    <div class="wjportal-form-value">';
                                                          $fieldValue = isset($employer->employer_to_date) ? date_i18n(wpjobportal::$_configuration['date_format'],strtotime($employer->employer_to_date)) : '';
                                                        $html .= '<input type="text" class="input wjportal-form-date-field form-control '.esc_attr($this->class_prefix).'-input-field custom_date" name="sec_4[employer_to_date][]" id="employer_to_date4'.esc_attr($sectionid).'" onchange="dateValidator('.esc_attr($sectionid).')" value='. $fieldValue .'>';
                                            $html .='</div>
                                                </div>';
                                            break;
                                        case "employer_current_status":
                                            $html .= '<div class="wjportal-form-row">
                                                        <div class="wjportal-form-title">
                                                            <label class="wpjp-form-title " for="' . $field->id . '">';
                                            $html .=            wpjobportal::wpjobportal_getVariableValue($field_obj->fieldtitle);
                                            $html .='       </label>
                                                        </div>
                                                        <div class="wjportal-form-value">';
                                                $fvalue = isset($employer->employer_current_status) ? $employer->employer_current_status : '';
                                            $html .= '<label class="wjportal-input-box-switch"><input type="checkbox" onclick="disablefields('.esc_attr($sectionid).')" class="input wjportal-form-chkbox-field" name="sec_4[employer_current_status][]" id="employer_current_status'.esc_attr($sectionid).'" value="'.$fvalue.'"
                                            ';
                                              if($fvalue==1){
                                                    $html.='checked="checked"';
                                                }else if($fvalue==0){
                                                    $html.='';
                                                }else if($fvalue==''){
                                                }else{

                                                }
                                                $html.='><span class="wjportal-input-box-slider"></span></label>';
                                                $html .='</div>
                                                </div>';
                                            break;
                                        }
                                    }
                            }
                            $counter = 1;
                        break;
                    case "employer_city":
                        $for = 4;
                        $html .= $this->getCityFieldForForm( $for , $sectionid, $employer, $field , $jssection_hide,$themecall);
                        break;
                    case "employer_phone":
                        $fvalue = isset($employer->employer_phone) ? $employer->employer_phone : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,$themecall);
                        break;
                    case "employer_address":
                        $fvalue = isset($employer->employer_address) ? $employer->employer_address : '';
                        $html .= $this->getFieldForMultiSection($field, $fvalue, 4, $sectionid, $jssection_hide,$themecall);
                        break;

                    default:
                        $html .= $this->getResumeFormUserField($field, $employer , 4 , $sectionid, $jssection_hide,$themecall);
                    break;
                }
            }
            $id = isset($employer->id) ? $employer->id : '';
            $deletethis = ($id != '') ? 0 : 1;
            $html .= '<input type="hidden" id="deletethis4'.esc_attr($sectionid).'" class="jsdeletethissection" name="sec_4[deletethis]['.esc_attr($sectionid).']" value="'.$deletethis.'">
                        <input type="hidden" id="id" name="sec_4[id]['.esc_attr($sectionid).']" value="'.$id.'">';
                    if(null !=$themecall){
                        $html .= '<hr class="'.esc_attr($this->class_prefix).'-resume-section-sep" />';
                    }
                    $html .='</div>';
            $sectionid++;
        }
        $html .= '</div>';
        if($j > 0){
            if(null !=$themecall){
                $html .= '<div class="wpjp-add-new-section-link  '.esc_attr($this->class_prefix).'-resume-addnewbutton" onclick="showResumeSection( this ,\'employer\');">
                <span class="'.esc_attr($this->class_prefix).'-addresume-addfield-btn-txt"><i class="fa fa-plus-square-o" aria-hidden="true"></i>'.esc_html(__('Add New','wp-job-portal')).' '. esc_html(__('Employer','wp-job-portal')).'
                </span></div>';
            }else{
                $html .= '<div class="wpjp-add-new-section-link wjportal-resume-add-new-section-btn " onclick="showResumeSection( this ,\'employer\');"><i class="fa fa-plus"></i>'.esc_html(__('Add New','wp-job-portal')).' '. esc_html(__('Employer').'</div>';
            }
        }
        return $html;

    } */

    function makePersonalSectionFields($themecall=null) {
        $resume="";
        $userinfo = isset(wpjobportal::$_data['userinfo']) ? wpjobportal::$_data['userinfo'] : null;
        if(isset(wpjobportal::$_data[0]['personal_section'])){
            $resume = wpjobportal::$_data[0]['personal_section'];
        }
        $resumelists = "";
        $js_dateformat = $this->prepareDateFormat();
        $sectionid = 0;
        if(isset($userinfo)){
            $emailAddress =  $userinfo->emailaddress;
            $firstName = $userinfo->first_name ;
            $lastName = $userinfo->last_name;
        }else{
             $emailAddress =  '';
            $firstName = '' ;
            $lastName = '';
        }
        $data = '<div class="wjportal-resume-section-wrp" data-section="personal" data-sectionid="">';
            $name_counter = 0;
            $cell_counter = 0;
            $date_counter = 0;
            $available_counter = 0;
            $searchable_counter = 0;
            foreach (wpjobportal::$_data[2][1] as $field) {
                switch ($field->field) {
                    case "application_title":
                            $fieldValue = isset($resume->application_title) ? $resume->application_title : "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,'','',$themecall);
                        break;
                    case "first_name":
                    case "last_name":
                        if($name_counter == 0){
                            $data .= '';
                                $field_obj = '';
                                foreach (wpjobportal::$_data[2][1] as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "first_name":
                                                $fieldValue = isset($resume->first_name) ? $resume->first_name : $firstName;
                                                $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue, 3,'',$themecall);
                                            break;
                                        case "last_name":
                                                $fieldValue = isset($resume->last_name) ? $resume->last_name : $lastName;
                                                $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue, 3,'',$themecall);
                                            break;
                                    }
                                }
                            $data .= '';
                        }
                        $name_counter = 1;
                        break;
                    case "email_address": $email_required = ($field->required ? 'required' : '');
                            $fieldValue = isset($resume->email_address) ? $resume->email_address : $emailAddress;
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,'','',$themecall);
                        break;
                    case "cell":
                        if($cell_counter == 0){
                            $data .= '';
                                $field_obj = '';
                                foreach (wpjobportal::$_data[2][1] as $field_obj) {
                                    switch ($field_obj->field) {
                                        case "cell":
                                            $fieldValue = isset($resume->cell) ? $resume->cell : "";
                                            $data .= $this->getFieldForPersonalSection($field_obj, $fieldValue , 3,'',$themecall);
                                            break;
                                    }
                                }
                            $data .= '';
                        }
                        $cell_counter = 1;
                        break;
                    case "gender":
                            $value=isset($resume->gender)?$resume->gender:"";
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = WPJOBPORTALformfield::resumeSelect('gender', wpjobportal::$_common->getGender(), $value,'sec_1', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Gender', 'wp-job-portal')), array('class' => 'inputbox form-control wjportal-form-select-field '.esc_attr($this->class_prefix).'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "photo":
                        $text = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);

                        $photo_required = ($field->required ? 'required' : '');
                        $imgpath = '';

                        $img = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
                        $class = 'none';

                        if (!empty($resume->photo)) {
                            $wpdir = wp_upload_dir();
                            $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
                            $img = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->id . '/photo/' . $resume->photo;
                            $class = '';
                        }

                        $resumephoto = isset($resume->photo) ? $resume->photo : null;
                        //starts From there
                        $fieldvalue = '
                            <div class="wjportal-form-upload">
                                <div class="wjportal-form-upload-btn-wrp">
                                    <span class="wjportal-form-upload-btn-wrp-txt">'.$resumephoto.'
                                    </span>
                                    <span class="wjportal-form-upload-btn">
                                        '.esc_html(__("Upload Image","wp-job-portal")).'
                                        <input type="file" name="photo" class="photo" id="photo" />
                                    </span>
                                </div>
                                <div class="wjportal-form-image-wrp" style="display:'.$class.'">
                                    <img class="rs_photo wjportal-form-image" id="rs_photo" src="' . esc_url($img) . '" alt="'.esc_html(__('Resume image','wp-job-portal')).'"/>
                                    <img id="wjportal-form-delete-image" alt="cross" src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/no.png" />
                                </div>';
                                $logoformat = wpjobportal::$_config->getConfigurationByConfigName('image_file_type');
                                $maxsize = wpjobportal::$_config->getConfigurationByConfigName('resume_photofilesize');
                                $p_detail = '<div class="wjportal-form-help-txt"> ('.$logoformat.')</div>';
                                $p_detail .= '<div class="wjportal-form-help-txt"> ('.esc_html(__("Max logo size allowed","wp-job-portal")).' '.$maxsize.' Kb)</div>';
                            $fieldvalue .= $p_detail;
                        $fieldvalue .= '</div>';
                        $data .= $this->getRowForForm($text, $fieldvalue,$themecall);
                        break;
                    case "resumefiles":

                        $text = wpjobportal::wpjobportal_getVariableValue($field->fieldtitle);
                        $req = ''; // for checking field is required or not
                        if ($field->required == 1) {
                            $text .= '<span style="color:red;">*</span>';
                            $req = 'required';
                        }
                        $fieldvalue = '<div class="wjportal-form-upload">
                                    <div id="resumefileswrapper" class="wjportal-form-upload-btn-wrp"><span class="livefiles wjportal-form-upload-files">';
                                    $file_names_string = '';
                        if (!empty(wpjobportal::$_data[0]['file_section'])) {
                            foreach (wpjobportal::$_data[0]['file_section'] AS $file) {
                                $fieldvalue .= '<a href="#" id="file_' . $file->id . '" onclick="deleteResumeFile(' . $file->id . ');" class="file">
                                            <span class="filename wjportal-form-upload-file-name">' . $file->filename . '</span><span class="fileext wjportal-form-upload-file-text"></span>
                                            <img class="filedownload wjportal-form-upload-file-close" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/resume/cancel.png" />
                                        </a>';
                                $file_names_string .= $file->filename;
                            }
                        }
                        $fieldvalue .= '</span><span class="clickablefiles wjportal-form-upload-btn">'.esc_html(__('Select Multiple Files','wp-job-portal')).'</span>';

                        $fieldvalue .= '<input  type="file" id="resumefiles" value="'.$file_names_string.'" placeholder="Choose Resume File" class="wjportal-form-upload-file" name="resumefiles[]" data-validation="' . $req . '" multiple="true" style="display:none;" />';
                        $fieldvalue .= '</div>';
                        $logoformat = wpjobportal::$_config->getConfigurationByConfigName('document_file_type');
                                $maxsize = wpjobportal::$_config->getConfigurationByConfigName('document_file_size');
                                $p_detail = '<div class="wjportal-form-help-txt"> ('.$logoformat.')</div>';
                                $p_detail .= '<div class="wjportal-form-help-txt"> ('.esc_html(__("Max logo size allowed","wp-job-portal")).' '.$maxsize.' Kb)</div>';
                            $fieldvalue .= $p_detail;
                        $fieldvalue .= '</div>';
                        $data .= $this->getRowForForm($text, $fieldvalue,$themecall);
                        break;
                    case "job_category":
                            $value=isset($resume->job_category)?$resume->job_category:WPJOBPORTALincluder::getJSModel('category')->getDefaultCategoryId();
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = WPJOBPORTALformfield::resumeSelect('job_category', WPJOBPORTALincluder::getJSModel('category')->getCategoryForCombobox(''),$value,'sec_1', esc_html(__('Select','wp-job-portal')) , array('class' => 'inputbox wjportal-form-select-field  form-control '.esc_attr($this->class_prefix).'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "jobtype":
                            $value = isset($resume->jobtype) ? $resume->jobtype : WPJOBPORTALincluder::getJSModel('jobtype')->getDefaultJobTypeId();
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = WPJOBPORTALformfield::resumeSelect('jobtype', WPJOBPORTALincluder::getJSModel('jobtype')->getJobTypeForCombo(), $value,'sec_1', esc_html(__('Select','wp-job-portal')) , array('class' => 'inputbox one wjportal-form-select-field form-control '.esc_attr($this->class_prefix).'-select-field', 'data-validation' => $req));
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case "nationality":
                            $value = isset($resume->nationalityid) ? $resume->nationalityid : "";
                            $req = ($field->required ? 'required' : '');
                            $fieldValue = WPJOBPORTALformfield::resumeSelect('nationality', WPJOBPORTALincluder::getJSModel('country')->getCountriesForCombo(), $value,'sec_1', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Nationality', 'wp-job-portal')), array('class' => 'inputbox  form-control wjportal-form-select-field '.esc_attr($this->class_prefix).'-select-field', 'data-validation' => $req));;
                            $data .= $this->getResumeSelectField($field, $fieldValue,'',$themecall);
                        break;
                    case 'salaryfixed':
                            $salaryfixed_require = ($field->required ? 'required' : '');
                            $fieldValue = isset($resume->salaryfixed) ? $resume->salaryfixed : "";
                            $data .= $this->getFieldForPersonalSection($field, $fieldValue,'','',$themecall);
                        break;
                    case 'tags':
                        if(in_array('tag', wpjobportal::$_active_addons)){
                            $value = isset($resume->resumetags) ? $resume->resumetags : '';
                            $data .= $this->getFieldForPersonalSection($field,$value,'','',$themecall);
                            wp_register_script( 'wpjobportal-inline-handle', '' );
                            wp_enqueue_script( 'wpjobportal-inline-handle' );
                            $inline_js_script = '
                                            jQuery(document).ready(function(){
                                                getTokenInputTags(' . $value . ');
                                            });';
                            wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
                        }
                    break;
                    case "searchable":
                        if($searchable_counter == 0){
                            $value = isset($resume->searchable) ? $resume->searchable : "";
                            $data .= $this->getResumeCheckBoxField($field, $value);
                        }
                        $searchable_counter = 1;
                    break;
                    case 'termsandconditions':
                        if (isset(wpjobportal::$_data['resumeid']) && is_numeric(wpjobportal::$_data['resumeid'])) {
                        }else{
                            $this->show_terms_and_conditions = 1;
                            $this->terms_and_conditions_title = $field->fieldtitle;
                        }
                    break;

                    default:
                        $data .= $this->getResumeFormUserField($field, $resume , 1 ,  0 , '',$themecall);
                    break;
                }
            }
        // if ($sectionmoreoption == 1) {
        //     $data .= '</div>'; // closing div for the more option
        // }
        $data .= '</div>'; // to handle background color of scetions
        return $data;
    }

    function printResume($themecall=null) {

        //check wheather to show resume form or resumeformview
        $resumeformview = 1; // for add case
        if (isset(wpjobportal::$_data['resumeid']) && is_numeric(wpjobportal::$_data['resumeid'])) {
            $resumeformview = 0; // for edit case
            $resumeid=wpjobportal::$_data['resumeid'];
        }
        if(wpjobportal::$theme_chk == 1 && !wpjobportal::$_common->wpjp_isadmin()){
            $this->class_prefix = 'wpj-jp-form-wrp wpj-jp-resume-form';
            $this->themecall = 1;
            $themecall = 1;
        }else{
            $this->class_prefix = 'wjportal-form-wrp wjportal-resume-form';
        }
        $html = '<div id="resume-wrapper" class="'.esc_attr($this->class_prefix).'">';
        $form_class="wjportal-form";
        if(1 == $themecall){
            // $html='<div class="jsjb-jm-form-wrap">';
            // $form_class="jsjb-jm-form";
        }elseif(2 == $themecall){
            $html='<div class="jsjb-jh-form-wrap">';
            $form_class="jsjb-jh-form";
        }
        $check = apply_filters('wpjobportal_addons_multiresume_add',false,$form_class);
        if($check == false){
            $html .= '<form class="'.$form_class.'" id="resumeform" method="post" enctype="multipart/form-data" action="'.wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'task'=>'saveresume')).'" >';

        }else{
            $html .= $check;
        }
        if (!isset(wpjobportal::$_data[0]['personal_section']->uid)) {
            $isowner = 1; // user come to add new resume
        } else {
            $isowner = (WPJOBPORTALincluder::getObjectClass('user')->uid() == wpjobportal::$_data[0]['personal_section']->uid) ? 1 : 0;
        }
        foreach ($this->resumefields AS $field) {
            if($field->published == 1 || (WPJOBPORTALincluder::getObjectClass('user')->isguest() && $field->isvisitorpublished == 1)){// disabling field for user also disabled them for visitor
                switch ($field->field){
                    case 'section_personal':
                        $title = 'Personal Information';
                        $title = $field->fieldtitle;
                        $html .= $this->getSectionTitle('personal', $title , 1,$themecall);
                        $html .= $this->makePersonalSectionFields($themecall);
                    break;
                    case 'section_address':
                        $title = 'Address';
                        $title = $field->fieldtitle;
                        $html .= apply_filters('wpjobportal_addons_section_wise_form',false,'getSectionTitle','address',$title,'3',$themecall);
                        $html .= apply_filters('wpjobportal_addons_selection_fields',false,'makeAddressSectionFields',$themecall);
                    break;
                    case 'section_education':
                        $title = 'Education';
                        $title = $field->fieldtitle;
                        $html .= apply_filters('wpjobportal_addons_section_wise_form',false,'getSectionTitle','education',$title,'3',$themecall);
                        $html .= apply_filters('wpjobportal_addons_selection_fields',false,'makeInstituteSectionFields',$themecall);
                        break;
                    case 'section_employer':
                        $title = 'Employer';
                        $title = $field->fieldtitle;
                        $html .= apply_filters('wpjobportal_addons_section_wise_form',false,'getSectionTitle','employer',$title,'3',$themecall);
                        $html .= apply_filters('wpjobportal_addons_selection_fields',false,'makeEmployerSectionFields',$themecall);
                        break;
                    case 'section_skills':
                        if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                            $title = 'Skills';
                            $title = $field->fieldtitle;
                            $html .= $this->getSectionTitle('skills', $title, 5,$themecall);
                            $html .= apply_filters('wpjobportal_addons_selection_fields',false,'makeSkillsSectionFields',$themecall);
                        }
                        break;
                    case 'section_language':
                        if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                            $title = 'Language';
                            $title = $field->fieldtitle;
                            $html .= $this->getSectionTitle('language', $title, 8,$themecall);
                            $html .=apply_filters('wpjobportal_addons_selection_fields',false,'makeLanguageSectionFields',$themecall);
                        }
                        break;

                    default:
                        if($field->is_section_headline == 1){ // to print resume custom sections
                            if($field->field != 'section_resume'){ // avoid resume editor section legacy code issue
                                $title = $field->fieldtitle;
                                $sectionid = $field->section;
                                $html .= $this->getSectionTitle($field->field, $title, $sectionid,$themecall);
                                $html .= apply_filters('wpjobportal_addons_selection_fields_custom_sections',false,'makeCustomResumeSectionFields',$sectionid,$themecall);
                            }
                        }
                    break;
                }
            }
        }
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $html .= '<div class="wpjp-resume-section-button">'.wp_enqueue_script('wpjobportal-repactcha-script', $protocol.'www.google.com/recaptcha/api.js');
        if(current_user_can('manage_options')){
            $one = '';
            $two = '';
            $three = '';
            $four = '';
            if(isset(wpjobportal::$_data[0]['personal_section']->status)){
                if(wpjobportal::$_data[0]['personal_section']->status == 1){
                    $one = ' selected ';
                }elseif(wpjobportal::$_data[0]['personal_section']->status == 0){
                    $two = ' selected ';
                }elseif(wpjobportal::$_data[0]['personal_section']->status == -1){
                    $three = ' selected ';
                }elseif(wpjobportal::$_data[0]['personal_section']->status == 3){
                    $four = ' selected ';
                }
            }
            $status = isset(wpjobportal::$_data[0]['personal_section']->status) ? wpjobportal::$_data[0]['personal_section']->status : '';
            $html .= '
                <div class="wjportal-form-row">
                    <div class="wjportal-form-title">
                        <label id="total_experiencemsg" class="row-title" for="status">'.esc_html(__('Status','wp-job-portal')).'</label>
                    </div>
                    <div class="wjportal-form-value">
                    <select id="status" name="sec_1[status]" class="wjportal-form-select-field">
                        <option ';
                        $selected = ($status == 1) ? 'selected="selected"' : '';
            $html .=    $selected.' value="1" '.$one.'>'.esc_html(__('Approved','wp-job-portal')).'</option>
                        <option ';
                        $selected = ($status == 0) ? 'selected="selected"' : '';
            $html .=    $selected.' value="0" '.$two.'>'.esc_html(__('Pending','wp-job-portal')).'</option>
                        <option ';
                        $selected = ($status == -1) ? 'selected="selected"' : '';
            $html .=    $selected.' value="-1" '.$three.'>'.esc_html(__('Reject','wp-job-portal')).'</option>
                        <option ';
                        $selected = ($status == 3) ? 'selected="selected"' : '';
            $html .=    $selected.' value="3" '.$four.'>'.esc_html(__('Pending Payment','wp-job-portal')).'</option>
                    </select></div>
                </div>
                ';
        }
        $isvisitor=false;
        if(isset($_COOKIE['wpjobportal_apply_visitor'])){
            if (!is_user_logged_in()) {
                $isvisitor=true;
            }
        }
        $google_recaptcha_3 = false;
        $config_array = wpjobportal::$_config->getConfigByFor('captcha');
        if (!is_user_logged_in() && $config_array['resume_captcha'] == 1) {
            if ($config_array['captcha_selection'] == 1) { // Google recaptcha
                if($config_array['recaptcha_version'] == 1){
                    $html .= '<div class="g-recaptcha" data-sitekey="'.$config_array["recaptcha_publickey"].'"></div>';
                }else{
                    $google_recaptcha_3 = true;
                }

            } else { // own captcha
                $captcha = new WPJOBPORTALcaptcha;
                $html .= '<div class="recaptcha-wrp">'.$captcha->getCaptchaForForm().'</div>';
            }
        }

        if($this->show_terms_and_conditions == 1){
            $termsandconditions_link = get_the_permalink(wpjobportal::$_configuration['terms_and_conditions_page_resume']);
            $html .='
                <div class="js-col-md-12 js-form-wrapper wjportal-terms-and-conditions-wrap wpjobportal-terms-and-conditions-wrap" data-wpjobportal-terms-and-conditions="1" >
                    <div class="js-col-md-12 js-form-value">
                        '.WPJOBPORTALformfield::checkbox('termsconditions', array('1' => wpjobportal::wpjobportal_getVariableValue($this->terms_and_conditions_title)), 0, array('class' => 'checkbox')).'
                        <a title="'. esc_html(__('Terms and Conditions','wp-job-portal')).'" href="'. $termsandconditions_link.'" target="_blank" >
                        <img alt="'. esc_html(__('Terms and Conditions','wp-job-portal')).'" title="'. esc_html(__('Terms and Conditions','wp-job-portal')).'" src="'. esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/widget-link.png" /></a>
                    </div>
                </div>

            ';
        }
        $created = isset(wpjobportal::$_data[0]['personal_section']->created) ? wpjobportal::$_data[0]['personal_section']->created : gmdate('Y-m-d H:i:s');
        $html .= '<div class="wpjp-resume-form-btn-wrp">
                <input type="hidden" id="created" name="sec_1[created]" value="'.esc_attr($created).'">';
            $html .=WPJOBPORTALformfield::hidden('id', isset(wpjobportal::$_data[0]['personal_section']->id) ? wpjobportal::$_data[0]['personal_section']->id : '' );
            if(isset(wpjobportal::$_data[0]['personal_section']->uid) && ""!=wpjobportal::$_data[0]['personal_section']->uid){
                $uid=wpjobportal::$_data[0]['personal_section']->uid;
            } else{
                $uid=WPJOBPORTALincluder::getObjectClass('user')->uid();
            }
            //$html .= '<input type="hidden" id="uid" name="sec_1[uid]" value="'.esc_attr($uid).'">';
            $html .=WPJOBPORTALformfield::hidden('uid', esc_html($uid));
            $html .=WPJOBPORTALformfield::hidden('action', 'resume_saveresume');
            $html .=WPJOBPORTALformfield::hidden('wpjobportalpageid',esc_html( get_the_ID()));
            $html .=WPJOBPORTALformfield::hidden('creditid', '');
            $html .=WPJOBPORTALformfield::hidden('upakid', isset(wpjobportal::$_data['package']) ? esc_html(wpjobportal::$_data['package']->id) : 0);
            $html .=WPJOBPORTALformfield::hidden('form_request', 'wpjobportal');
            $html .=WPJOBPORTALformfield::hidden('resume_logo_deleted', '');
            $html .=WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_resume_nonce')));
            $html .='<div class="wjportal-form-btn-wrp" id="save-button">';
            $guestallowed = 0;
            if (WPJOBPORTALincluder::getObjectClass('user')->isguest()) {
                $guestallowed = wpjobportal::$_config->getConfigurationByConfigName('visitor_can_add_resume');
            }
            if(!wpjobportal::$_common->wpjp_isadmin()){ // site
                if(in_array('multiresume', wpjobportal::$_active_addons)){
                    $cancel_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multiresume', 'wpjobportallt'=>'myresumes'));
                }else{
                    //$cancel_link=wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobseeker'));
                    $cancel_link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes'));// to redirect on cancel to my resumes
                }
            }elseif(wpjobportal::$_common->wpjp_isadmin()){
                $cancel_link = esc_url_raw(admin_url("admin.php?page=wpjobportal_resume"));
            }
            $btn_cancel=false;
            if(!$isvisitor && is_user_logged_in() ){
                $btn_cancel=true;
            }
            if($btn_cancel==true)  {
                $html .= '<div class="wjportal-form-2-btn">';
            }

            if($google_recaptcha_3 == false){ // to handle case of google recpatcha version 3
                if ($isvisitor &&  !wpjobportal::$_common->wpjp_isadmin()) {
                    $html .= '<input class="'.esc_attr($this->class_prefix).'-btn-primary wjportal-form-btn wjportal-form-save-btn" type="button" onclick="submitresume();" value="' . esc_html(__('Apply Now', 'wp-job-portal')) . '"/>';
                } else {
                    $html .= '<input class="'.esc_attr($this->class_prefix).'-btn-primary wjportal-form-btn wjportal-form-save-btn" type="button" onclick="submitresume();" value="' . esc_html(__("Save Resume", "wp-job-portal")) . '"/>';
                }
            }else{
                if ($isvisitor &&  !wpjobportal::$_common->wpjp_isadmin()) {
                    $html .= '<input class="'.esc_attr($this->class_prefix).'-btn-primary wjportal-form-btn wjportal-form-save-btn g-recaptcha" data-sitekey="'. esc_attr($config_array['recaptcha_publickey']).'" data-callback="onSubmit" data-action="submit" type="button" value="' . esc_html(__('Apply Now', 'wp-job-portal')) . '"/>';
                } else {
                    $html .= '<input class="'.esc_attr($this->class_prefix).'-btn-primary wjportal-form-btn wjportal-form-save-btn g-recaptcha" data-sitekey="'. esc_attr($config_array['recaptcha_publickey']).'" data-callback="onSubmit" data-action="submit" type="button"  value="' . esc_html(__("Save Resume", "wp-job-portal")) . '"/>';
                }
            }

            if($btn_cancel==true)  $html .= '</div>';
            if(!$isvisitor && is_user_logged_in() ){
                if($btn_cancel==true)  $html .= '<div class="wjportal-form-2-btn">';
                    $html .= '<a class="resume_submits cancel '.esc_attr($this->class_prefix).' wjportal-form-btn wjportal-form-cancel-btn" href="'.esc_url($cancel_link).'">' . esc_html(__('Cancel Resume', 'wp-job-portal')) . '</a>';
                if($btn_cancel==true)  {
                        $html .= '</div>';
                }
            }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</form>';
        $html .= '</div>';// section wrapper end;
        //echo wp_kses($html,WPJOBPORTAL_ALLOWED_TAGS);
        echo $html;
        /*if (isset(wpjobportal::$_data[0]) && isset(wpjobportal::$_data[0]['personal_section'])) {
            $viewtags = wpjobportal::$_data[0]['personal_section']->viewtags;
        } else {
            $viewtags = '';
        }
        $viewtags = apply_filters('wpjobportal_addons_makeanchor_for_tags',false,$viewtags);
        echo esc_attr($viewtags);*/
    }

    function getRowForView($text, $value, &$i) {
        $html = '';
        if ($i == 0 || $i % 2 == 0) {
            $html .= '<div class="wpjp-resume-row-wrp">';
        }
        $html .= '<div class="resume-row-wrapper">
                    <div class="wpjp-form-title">' . $text . ':</div>
                    <div class="wpjp-form-value">' . wpjobportal::wpjobportal_getVariableValue($value) . '</div>
                </div>';
        $i++;
        if ($i % 2 == 0) {
            $html .= '</div>';
        }
        return $html;
    }

    function getRowForForm($text, $value,$themecall=null) {
        if(null != $themecall){
            $html = '<div class="wjportal-form-row">
                <div class="wjportal-form-title">' . $text . ':</div>
                <div class="wjportal-form-value">' . $value . '</div>
            </div>';
        }else{
            $html = '<div class="wjportal-form-row">
                <div class="wjportal-form-title">' . $text . ':</div>
                <div class="wjportal-form-value">' . $value . '</div>
            </div>';
        }
        return $html;
    }

    function getHeadingRowForView($value) {
        $html = '<div class="resume-heading-row">' . $value . '</div>';
        return $html;
    }
}

?>
