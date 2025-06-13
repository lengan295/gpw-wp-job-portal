<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALResumeViewlayout {

    public $config_array_sec=array();
    public $themecall = 0;
    public $class_prefix = '';


    function __construct(){
        $this->config_array_sec = wpjobportal::$_config->getConfigByFor('resume');
        $fieldsordering = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(3); // resume fields
        wpjobportal::$_data[2] = array();
        foreach ($fieldsordering AS $field) {
            wpjobportal::$_data['fieldtitles'][$field->field] = $field->fieldtitle;
            wpjobportal::$_data[2][$field->section][$field->field] = $field;
        }
        if(wpjobportal::$theme_chk == 2){/// code to manage class prefix for diffrent template cases
            $this->class_prefix = 'jsjb-jh';
            $this->themecall = 2;

        }elseif(wpjobportal::$theme_chk == 1){
            $this->class_prefix = 'wpj-jp';
            $this->themecall = 1;
        }else{
            $this->class_prefix = '';
        }

    }
    function getRowMapForView($text, $longitude, $latitude,$themecall=null) {
        $id = "div-id".uniqid();// unidiq might cause problem for starting with number value
        if(null != $themecall){
            $html = '<div class="'.esc_attr($this->class_prefix).'-resumedetail-address-map-wrap">
                        <div class="'.esc_attr($this->class_prefix).'-resumedetail-address-map">
                            <span class="'.esc_attr($this->class_prefix).'-resumedetail-address-map-showhide"><img src="' . JOB_PORTAL_THEME_IMAGE . '/cu_loc.png" class="image"/></span>
                            ' . $text . '
                        </div>
                        <div class="'.esc_attr($this->class_prefix).'-resumedetail-address-map-area" style="display: none;">
                            <div class="'.esc_attr($this->class_prefix).'-map-inner">
                                <div id="'.esc_attr($this->class_prefix).'-map" style="position: relative; overflow: hidden;">
                                    <div id="' . $id . '" class="map" style="width:100%;min-height:200px;">' . esc_attr($longitude) . ' - ' . esc_html($latitude) . '</div>
                                </div>
                            </div>
                        </div>';
                        wp_register_script( 'wpjobportal-inline-handle', '' );
                        wp_enqueue_script( 'wpjobportal-inline-handle' );
                        $inline_js_script = '
                            jQuery(document).ready(function(){
                                initialize("' . esc_attr($latitude) . '","' . esc_attr($longitude) . '","' . esc_attr($id) . '");
                            });';
                        wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
                        $html .='
                    </div>';
        }else{
            $html = '<div class="resume-map">
                    <div class="row-title"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/resume/hide-map.png" class="image"/>' . esc_html($text) . '</div>
                    <div class="row-value"><div id="' . esc_attr($id) . '" class="map" style="width:100%;min-height:200px;">' . esc_html($longitude) . ' - ' . esc_html($latitude) . '</div></div>
                    ';
                    wp_register_script( 'wpjobportal-inline-handle', '' );
                    wp_enqueue_script( 'wpjobportal-inline-handle' );
                    $inline_js_script = '
                        jQuery(document).ready(function() {
                            initialize("' . esc_attr($latitude) . '","' . esc_attr($longitude) . '","' . esc_attr($id) . '");
                        });
                    ';
                    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
                    $html .='
                </div>';
        }
        return $html;
    }


    function getAttachmentRowForViewJobManager($adminLogin) {
        return $this->getAttachmentRowForViewForTemplate($adminLogin);
    }

    function getAttachmentRowForViewJobHub($adminLogin) {
        return $this->getAttachmentRowForViewForTemplate($adminLogin);
    }

    function getAttachmentRowForViewForTemplate($adminLogin) {
        $html='<div id="'.esc_attr($this->class_prefix).'-resumedetail-attachment" class="'.esc_attr($this->class_prefix).'-resumedetail-section">
            <div class="'.esc_attr($this->class_prefix).'-resumedetail-section-title">
                <span class="'.esc_attr($this->class_prefix).'-resumedetail-section-icon">
                    <img alt="attachment" title="attachment" src="'.JOB_PORTAL_THEME_IMAGE.'/attchments.png">
                </span>
                <h5 class="'.esc_attr($this->class_prefix).'-resumedetail-section-txt">
                    '.esc_html(__("Attachment","wp-job-portal")).'
                </h5>
            </div>
            <div class="'.esc_attr($this->class_prefix).'-resumedetail-sec-data">
                <div class="'.esc_attr($this->class_prefix).'-resumedetail-sec-download">
                    <div class="input-group">';
                        foreach (wpjobportal::$_data[0]['file_section'] AS $file) {
                            $files=$file->filename;
                            $exp_extension = wpjobportalphplib::wpJP_explode(".", $files);
                            $extension = end($exp_extension);
                            $filename=wpjobportalphplib::wpJP_substr($files,'0','3')."...";
                            //$file_id_string = WPJOBPORTALincluder::getJSModel('common')->encodeIdForDownload($file->id);
                            $html .= '<a target="_blank" href="' . esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'action'=>'wpjobportaltask', 'task'=>'getresumefiledownloadbyid', 'wpjobportalid'=>$file->id, 'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('wpjobportalpageid'))),'wpjobportal_resume_nonce'.$file->id)) . '" class="file">
                                        <span class="wpjp-filename">' . esc_html($filename) . '</span><span class="wpjp-fileext">'.esc_html($extension).'</span>
                                        <i class="fa fa-download download" aria-hidden="true"></i>
                                    </a>';
                        }
                    $html .='</div>';
                    if(!empty(wpjobportal::$_data[0]['file_section']) && (wpjobportal::$_data['resumecontactdetail'] == true || $adminLogin)){
                         $html .= apply_filters('wpjobportal_addons_resume_action_ResumeFile',false,wpjobportal::$_data[0]['personal_section']);
                    }
                $html .= '</div>
            </div>
        </div>';
        return $html;
    }

    function getAttachmentRowForView($text,$themecall=null) {
        if(null !=$themecall) return;
        $html = '<div class="wjportal-resume-sec-row wjportal-resume-attachments-wrp">
                    <div class="wjportal-resume-sec-data wjportal-resume-row-full-width">
                        <div class="wjportal-resume-sec-data-title">' . wpjobportal::wpjobportal_getVariableValue($text) . ':</div>
                        <div class="wjportal-resume-sec-data-value">';
        if (!empty(wpjobportal::$_data[0]['file_section'])) {
            foreach (wpjobportal::$_data[0]['file_section'] AS $file) {
                //$file_id_string = WPJOBPORTALincluder::getJSModel('common')->encodeIdForDownload($file->id);
                $html .= '<a target="_blank" href="' . esc_url(wp_nonce_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'action'=>'wpjobportaltask', 'task'=>'getresumefiledownloadbyid', 'wpjobportalid'=>$file->id, 'wpjobportalpageid'=>WPJOBPORTALRequest::getVar('wpjobportalpageid'))),'wpjobportal_resume_nonce'.$file->id)) . '" class="file">
                            <span class="wjportal-resume-attachment-filename">' . $file->filename . '</span>
                            <span class="wjportal-resume-attachment-file-ext"></span>
                            <img class="wjportal-resume-attachment-file-download" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/resume/download.png" />
                        </a>';
            }
        }
        $html .= '      </div>
                    </div>
                </div>';
        return $html;
    }


    function getResumeSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if ($resumeformview == 0) { // edit form
            $html .= '<div class="wjportal-resume-section-wrapper '.esc_attr($this->class_prefix).'-resumedetail-sec-data" data-section="resume" data-sectionid="">';
            $i = 0;
            foreach (wpjobportal::$_data[2][6] AS $field => $required) {
                switch ($field) {
                    case 'resume':
                        if(null==$themecall){
                            if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                                $html .= '</div>'; // closing div for the more option
                            }
                        }
                        $value = wpjobportal::$_data[0]['personal_section']->resume;
                        $html .= '<div class="resume-section-data">' . $value . '</div>';
                        $i = 0;
                        break;
                    default:
                        $array = wpjobportal::$_wpjpcustomfield->showCustomFields($field, 11,wpjobportal::$_data[0]['personal_section']->params); //11 for view resume
                        if (is_array($array))
                            $html .= $this->getRowForView($array['title'], $array['value'], $i);
                        break;
                }
            }
            if(null==$themecall){
                if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                    $html .= '</div>'; // closing div for the more option
                }
            }
            $html .= '</div>';
        }
        return $html;
    }



    function getEmployerSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
        if ($resumeformview == 0) { // edit form
            if (!empty(wpjobportal::$_data[0]['employer_section'][0])){
                // section heading to print only once and printing it from field ordering
                $html .= '<div class="wjportal-resume-section-title">' . esc_html($this->getFieldTitleByField('section_employer')) . '</div>';
                foreach (wpjobportal::$_data[0]['employer_section'] AS $employer) {
                    $html .= '<div class="wjportal-resume-section-wrapper '.esc_attr($this->class_prefix).'-resumedetail-sec-data" data-section="employers" data-sectionid="' . $employer->id . '">';
                    $i = 0;
                    $value = $employer->employer;
                    if( ($employer->employer_from_date != '' && !strstr($employer->employer_from_date, '1970')) && ($employer->employer_to_date != '' && !strstr($employer->employer_to_date, '1970')) ){
                        $value .= '<span class="wpjp-resume-employer-dates">(' . date_i18n('M Y', strtotime($employer->employer_from_date)) . ' - ' . date_i18n('M Y', strtotime($employer->employer_to_date)) . ')</span>';
                    }
                    if ($viewlayout == 0) {
                        $value .= '<a class="edit" href="#"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/edit-resume.png" /></a>';
                        $value .= '<a class="delete" href="#"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/delete-resume.png" /></a>';
                    }
                    $html .= $this->getHeadingRowForView($value,$themecall);
                    foreach (wpjobportal::$_data[2][4] AS $field => $required) {
                        switch ($field) {
                            case 'employer_position':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_position;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_city':
                                $text = $this->getFieldTitleByField($field);
                                $value = wpjobportal::$_common->getLocationForView($employer->cityname, '', '');
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            /*EMPLOYEER STATUS IM AVAILABLE     */
                             case 'employer_current_status':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_current_status;
                                if($value!="" && $value==1){
                                    $originalDate = $employer->employer_from_date;
                                    $currentDate  = gmdate('d/m/Y');
                                    $multidate=human_time_diff(strtotime($originalDate),strtotime(date_i18n("Y-m-d H:i:s")));
                                    /*
                                    $duration=wpjobportal::$_common->getYearMonth($mkarray);
                                    $multidate='';
                                    foreach ($duration as $key => $value) {
                                        $name=array_search($value,$duration);
                                        switch ($name) {
                                            case 'years':
                                                if($value>0){
                                                $multidate.=' '.$value.'  '.wpjobportalphplib::wpJP_strtoupper($name);
                                                }
                                                break;
                                            case 'month':
                                               if($value>0){
                                                $multidate.=' '.$value.'  '.wpjobportalphplib::wpJP_strtoupper($name);
                                                }
                                                break;
                                            case 'days':
                                                if($value>0){
                                                $multidate.=' '.$value.'  '.wpjobportalphplib::wpJP_strtoupper($name);
                                                }
                                                break;
                                            default:
                                                if(isset($value)!=''>0){
                                                $multidate.=' '.$value.'  '.wpjobportalphplib::wpJP_strtoupper($name);
                                                }
                                                break;
                                        }
                                    }*/
                                    $html .= $this->getRowForView($text, $multidate, $i,$themecall,1);
                                }
                                break;
                            case 'employer_phone':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_phone;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                break;
                            case 'employer_address':
                                $text = $this->getFieldTitleByField($field);
                                $value = $employer->employer_address;
                                $html .= $this->getRowForView($text, $value, $i,$themecall,1);

                                break;

                            default:
                                $array = wpjobportal::$_wpjpcustomfield->showCustomFields($field,11,$employer->params); //11 for view resume
                                if (is_array($array))
                                    $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall,1);
                                break;
                        }
                    }
                    if(null==$themecall){
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>';
                        }
                    }
                    $html .= '</div>'; // section wrapper end;
                }
            }// new if closed (old code had without prenthisis forech below the if now there is title below the if so addded these prensthisis)
        }
        }
        return $html;
    }

   function getAddressesSection($resumeformview, $call, $viewlayout = 0,$themecall=null) {
        $html = '';
        if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
        if ($resumeformview == 0) { // view address sections
            if (!empty(wpjobportal::$_data[0]['address_section'][0])){
                // section heading to print only once and printing it from field ordering
                $html .= '<div class="wjportal-resume-section-title">' . esc_html($this->getFieldTitleByField('section_address')) . '</div>';
                foreach (wpjobportal::$_data[0]['address_section'] AS $address) {
                    $html .= '<div class="wjportal-resume-section-wrapper '.esc_attr($this->class_prefix).'-resumedetail-sec-data" data-section="addresses" data-sectionid="' . $address->id . '">';
                    $i = 0;
                    $loc = 0;
                    $value = $address->address;
                    if ($viewlayout == 0) {
                        $value .= '<a class="edit" href="#"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/edit-resume.png" /></a>';
                        $value .= '<a class="delete" href="#"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/delete-resume.png" /></a>';
                    }
                    $html .= $this->getHeadingRowForView($value,$themecall);
                    foreach (wpjobportal::$_data[2][2] AS $field => $required) {
                        switch ($field) {
                            case 'address_city':
                            case 'address_state':
                            case 'address_country':
                                if ($loc == 0) {
                                    $text = $this->getFieldTitleByField($field);
                                    $value = wpjobportal::$_common->getLocationForView($address->cityname, $address->statename, $address->countryname);
                                    $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                                    $loc++;
                                }
                                break;
                            case 'address_location':
                                if(!empty($address->longitude) && !empty($address->latitude)){
                                    $text = $this->getFieldTitleByField($field);
                                    $html .= apply_filters('wpjobportal_addons_map_resune_view',false,$text,$address,$themecall);
                                }
                                break;

                            default:
                                $array = wpjobportal::$_wpjpcustomfield->showCustomFields($field,11,$address->params);
                                //11 for view resume
                                if (is_array($array))
                                    $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall,1);
                                break;
                        }
                    }
                    if(null==$themecall){
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>';
                        }
                    }
                $html .= '</div>'; //section wrapper end;
            }
            } // new if closed the old code had no prenthisis on the if and had a foreach directly below if statement now there is section title
        }
        }
        return $html;
    }

    function getPersonalSection($resumeformview, $viewlayout = 0,$themecall=null) {
        $is_qucik_apply = 0;
        if(isset(wpjobportal::$_data[0]['personal_section']->quick_apply)){
            $is_qucik_apply = wpjobportal::$_data[0]['personal_section']->quick_apply;
        }
        $html = '';
        $personal=wpjobportal::$_data[0]['personal_section'];
        if ($resumeformview == 0) { // view section resume
            $html .= '<div class="wjportal-resume-section-wrapper '.esc_attr($this->class_prefix).'-resumedetail-sec-data" data-section="personal" data-sectionid="">';
            $i = 0;
            foreach (wpjobportal::$_data[2][1] AS $field => $required) {
                switch ($field) {
                    case 'cell':
                        if (wpjobportal::$_data['resumecontactdetail'] == true) {
                            $text = $this->getFieldTitleByField($field);
                            $value = wpjobportal::$_data[0]['personal_section']->cell;
                            $html .= $this->getRowForView($text, $value, $i,$themecall);
                        }
                        break;
                    case 'first_name':// in case of admin view resume first name was not printing at all
                        // only first name field is required so making it visible in content area
                            $text = $this->getFieldTitleByField($field);
                            $value = wpjobportal::$_data[0]['personal_section']->first_name;
                            $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'last_name':// in case of admin view resume last name was not printing at all
                        // only last name field is required so making it visible in content area
                            $text = $this->getFieldTitleByField($field);
                            $value = wpjobportal::$_data[0]['personal_section']->last_name;
                            $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;

                    case 'nationality':
                        if($is_qucik_apply == 1){ // dont print this field for quick apply resume
                            break;
                        }
                        $text = $this->getFieldTitleByField($field);
                        $value = wpjobportal::$_data[0]['personal_section']->nationality;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'gender':
                        if($is_qucik_apply == 1){ // dont print this field for quick apply resume
                            break;
                        }
                        $text = $this->getFieldTitleByField($field);
                        $value = '';
                        switch (wpjobportal::$_data[0]['personal_section']->gender) {
                            case '0':$value = esc_html(__('Does not matter', 'wp-job-portal'));
                                break;
                            case '1':$value = esc_html(__('Male', 'wp-job-portal'));
                                break;
                            case '2':$value = esc_html(__('Female', 'wp-job-portal'));
                                break;
                        }
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'job_category':
                        if($is_qucik_apply == 1){ // dont print this field for quick apply resume
                            break;
                        }
                        $text = $this->getFieldTitleByField($field);
                        $value = wpjobportal::$_data[0]['personal_section']->categorytitle;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'jobtype':
                        if($is_qucik_apply == 1){ // dont print this field for quick apply resume
                            break;
                        }
                        $text = $this->getFieldTitleByField($field);
                        $value = wpjobportal::$_data[0]['personal_section']->jobtypetitle;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'salaryfixed':
                        if($is_qucik_apply == 1){ // dont print this field for quick apply resume
                            break;
                        }
                        $text = $this->getFieldTitleByField($field);
                        $value = isset(wpjobportal::$_data[0]['personal_section']->salaryfixed) ?wpjobportal::$_data[0]['personal_section']->salaryfixed : '';
                        $html .= $this->getRowForView($text, $value, $i,$themecall,1);
                        break;
                    case 'keywords':
                        if($is_qucik_apply == 1){ // dont print this field for quick apply resume
                            break;
                        }
                        $text = $this->getFieldTitleByField($field);
                        $value = wpjobportal::$_data[0]['personal_section']->keywords;
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'searchable':
                        if($is_qucik_apply == 1){ // dont print this field for quick apply resume
                            break;
                        }
                        $text = $this->getFieldTitleByField($field);
                        $value = (wpjobportal::$_data[0]['personal_section']->searchable == 1) ? esc_html(__('Yes','wp-job-portal')) : esc_html(__('No','wp-job-portal'));
                        $html .= $this->getRowForView($text, $value, $i,$themecall);
                        break;
                    case 'resumefiles':
                        if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                            $html .= '</div>'; // closing div for the more option
                        }
                        $text = $this->getFieldTitleByField($field);
                        $html .= $this->getAttachmentRowForView($text,$themecall);
                        $i = 0;
                        break;
                    default:
                        if($is_qucik_apply == 1){ // dont print this field for quick apply resume
                            break;
                        }
                        $array =
                         wpjobportal::$_wpjpcustomfield->showCustomFields($field,11,wpjobportal::$_data[0]['personal_section']->params,'resume',wpjobportal::$_data[0]['personal_section']->id);// new parameters required for upload field
                        if (is_array($array)){
                            $html .= $this->getRowForView($array['title'], $array['value'], $i,$themecall);
                        }
                        break;
                }
            }
            // printing quick apply message
            if($is_qucik_apply == 1){
                if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                    $html .= '</div>'; // closing div for the more option
                }
                // fetching apply message field label
                $text = wpjobportal::$_wpjpfieldordering->getFieldTitleByFieldAndFieldfor('message',5);

                $value = WPJOBPORTALincluder::getJSModel('jobapply')->getQuickApplyMessageByresume(wpjobportal::$_data[0]['personal_section']->id);
                // $html .= $this->getRowForView($text, $value, $i,$themecall,1,1);

                $html .= '<div class="wjportal-resume-sec-row '.esc_attr($this->class_prefix).'-resumedetail-sec-value wjportal-resume-row-full-width-row">';
                $html .= '<div class="wjportal-custom-field wjportal-resume-sec-data wjportal-resume-row-full-width">
                        <div class="wjportal-custom-field-tit wjportal-resume-sec-data-title">' . esc_html(wpjobportal::wpjobportal_getVariableValue($text)) . ':</div>
                        <div class="wjportal-custom-field-val wjportal-resume-sec-data-value">' . esc_html(wpjobportal::wpjobportal_getVariableValue($value)) . '</div>
                    </div>';
                $html .= '</div>';

                $i = 0;
            }


            if ($i % 2 != 0) { // close the div if one field is print and the function is finished;
                $html .= '</div>'; // closing div for the more option
            }
            $html .= '</div>'; //section wrapper end;// commented it to solve issue with design.
        }
        return $html;
    }

    function getPersonalTopSection($owner, $resumeformview) {
        $adminLogin = current_user_can('manage_options');
        $is_qucik_apply = 0;
        if(isset(wpjobportal::$_data[0]['personal_section']->quick_apply)){
            $is_qucik_apply = wpjobportal::$_data[0]['personal_section']->quick_apply;
        }
        $html = '<div class="wjportal-resume-top-section">';
        if(!WPJOBPORTALincluder::getJSModel('common')->isElegantDesignEnabled()){
            if($is_qucik_apply == 0){ // hide photo which is not set in case of quick apply
                if (isset(wpjobportal::$_data[2][1]['photo'])) {
                    $html .= '<div class="wjportal-resume-image">';
                    $img = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
                    if (wpjobportal::$_data[0]['personal_section']->photo != '') {
                        $wpdir = wp_upload_dir();
                        $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
                        $img = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . wpjobportal::$_data[0]['personal_section']->id . '/photo/' . wpjobportal::$_data[0]['personal_section']->photo;
                    }
                    $html .= '<img src="' . esc_url($img) . '" />';
                    $html .= '</div>';
                }
            }
        // only hide photo section if unpublished
            $html .= '<div class="wjportal-resume-adv-act-wrp">';
            $layout = WPJOBPORTALrequest::getVar('wpjobportallt');
                if ($layout != 'printresume') {
                    if ($owner != 1) { // Current user is not owner and (Consider as employer)
                        if(!current_user_can('manage_options') && WPJOBPORTALincluder::getObjectClass('user')->isemployer()){
                            $html .= apply_filters('wpjobportal_addons_sendMessage_resume',false) ;
                        }
                    }

                    if (wpjobportal::$_data['resumecontactdetail'] == true || $adminLogin) {
                        $class = '';
                        //PDF + EXCEL HOOK
                            $html  .= apply_filters('wpjobportal_addons_resume_views_action_for_pdf',false,wpjobportal::$_data[0]['personal_section']->id);
                            $html  .= apply_filters('wpjobportal_addons_resume_views_action_export',false,wpjobportal::$_data[0]['personal_section']->id);
                       }
                       //PRINT HOOK
                       $html .= apply_filters('wpjobportal_addons_resume_views_action_for_print',false,wpjobportal::$_data[0]['personal_section']->id);
                    if(!empty(wpjobportal::$_data[0]['file_section']) && (wpjobportal::$_data['resumecontactdetail'] == true || $adminLogin)){
                        //Downloadable File Addons HOOK
                        $html .= apply_filters('wpjobportal_addons_resume_action_ResumeFile',false,wpjobportal::$_data[0]['personal_section']);
                    }
                    $html .= apply_filters('wpjobportal_addons_showresume_contact_detail',false,wpjobportal::$_data[0]['personal_section']->id,wpjobportal::$_data['resumecontactdetail'],$adminLogin);

                } elseif ($layout == 'printresume') {
                    $html .= '<a href="#" onClick="window.print();" class="grayBtn">' . esc_html(__('Print', 'wp-job-portal')) . '</a>';
                }
            $html .='</div>';
        }
            $html .= '<div class="wjportal-personal-data">';

        //getResumeSectionAjax
        if (isset(wpjobportal::$_data[2][1]['first_name']) || isset(wpjobportal::$_data[2][1]['last_name'])) {
            $layout = WPJOBPORTALrequest::getVar('layout');
            $editsocialclass = '';
            /*if ($resumeformview == 0 && ($layout == 'addresume' || $owner == 1)) {
                $html .= '<a class="personal_section_edit" href="#"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/edit-resume.png" /></a>';
                $editsocialclass = 'editform';
            }elseif($adminLogin || (!is_user_logged_in() && isset($_SESSION['wp-wpjobportal']))) {
                $html .= '<a class="personal_section_edit" href="#"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/edit-resume.png" /></a>';
                $editsocialclass = 'editform';
            }*/
            $html .= '<div id="job-info-sociallink" class="' . $editsocialclass . '">';
            if (!empty(wpjobportal::$_data[0]['personal_section']->facebook)) {
                if(wpjobportalphplib::wpJP_strstr(wpjobportal::$_data[0]['personal_section']->facebook, 'http') ){
                    $facebook = wpjobportal::$_data[0]['personal_section']->facebook ;
                }else{
                    $facebook = 'http://'.wpjobportal::$_data[0]['personal_section']->facebook;
                }
                $html .= '<a href="' . $facebook . '" target="_blank"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/scround/fb.png"/></a>';
            }
            if (!empty(wpjobportal::$_data[0]['personal_section']->twitter)) {
                if(wpjobportalphplib::wpJP_strstr(wpjobportal::$_data[0]['personal_section']->twitter, 'http') ){
                    $twitter = wpjobportal::$_data[0]['personal_section']->twitter;
                }else{
                    $twitter = 'http://'.wpjobportal::$_data[0]['personal_section']->twitter;
                }
                $html .= '<a href="' . $twitter . '" target="_blank"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/scround/twitter.png"/></a>';
            }
            if (!empty(wpjobportal::$_data[0]['personal_section']->googleplus)) {
                if(wpjobportalphplib::wpJP_strstr(wpjobportal::$_data[0]['personal_section']->googleplus, 'http') ){
                    $googleplus = wpjobportal::$_data[0]['personal_section']->googleplus;
                }else{
                    $googleplus = 'http://'.wpjobportal::$_data[0]['personal_section']->googleplus;
                }
                $html .= '<a href="' . $googleplus . '" target="_blank"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/scround/gmail.png"/></a>';
            }
            if (!empty(wpjobportal::$_data[0]['personal_section']->linkedin)) {
                if(wpjobportalphplib::wpJP_strstr(wpjobportal::$_data[0]['personal_section']->linkedin, 'http') ){
                    $linkedin = wpjobportal::$_data[0]['personal_section']->linkedin;
                }else{
                    $linkedin = 'http://'.wpjobportal::$_data[0]['personal_section']->linkedin;
                }
                $html .= '<a href="' . $linkedin . '" target="_blank"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/scround/in.png"/></a>';
            }
            $html .= '</div>';

            $html .= '</span>';
        }
        if (isset(wpjobportal::$_data[2][1]['application_title'])) {
            $html .= '<div class="wjportal-resume-title">' . wpjobportal::$_data[0]['personal_section']->application_title . '</div>';
        }
        if (wpjobportal::$_data['resumecontactdetail'] == true || $adminLogin) {
            if (isset(wpjobportal::$_data[2][1]['jobtype'])) {
                if(isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0]['personal_section']->jobtypetitle)){
                    $html .= '<div class="wjportal-resume-info"> <span class="wjportal-jobtype" style="background-color: '.wpjobportal::$_data[0]['personal_section']->jobtypecolor.';">'  . esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data[0]['personal_section']->jobtypetitle)) . '</span></div>';
                }
            }
            if (isset(wpjobportal::$_data[2][1]['email_address'])) {
                $html .= '<div class="wjportal-resume-info"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/email.png" alt="'.esc_html(__('email','wp-job-portal')).'" title="'.esc_html(__('email','wp-job-portal')).'" />' . wpjobportal::$_data[0]['personal_section']->email_address . '</div>';
            }

            if (isset(wpjobportal::$_data[2][1]['salaryfixed'])) {
                if(isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0]['personal_section']->salaryfixed)){
                    $html .= '<div class="wjportal-resume-info"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/salary.png" alt="'.esc_html(__('salary','wp-job-portal')).'" title="'.esc_html(__('salary','wp-job-portal')).'"/>'  . wpjobportal::$_data[0]['personal_section']->salaryfixed . '</div>';
                }
            }
            if (isset(wpjobportal::$_data[2][1]['cell'])) {
                    if(isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0]['personal_section']->cell)){
                        $html .= '<div class="wjportal-resume-info"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/number.png" alt="'.esc_html(__('number','wp-job-portal')).'"title="'.esc_html(__('number','wp-job-portal')).'" />'  . wpjobportal::$_data[0]['personal_section']->cell . '</div>';
                    }
            }

            if (isset(wpjobportal::$_data[2][2]['address'])) {
                if(isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0]['personal_section']->address)){
                    $address = isset(wpjobportal::$_data[0]['address_section'][0]) ?  wpjobportal::$_data[0]['address_section'][0]->address : '';
                    $country = isset(wpjobportal::$_data[0]['address_section'][0]) ? wpjobportal::$_data[0]['address_section'][0]->countryname : '';
                    $html .= '<div class="wjportal-resume-info"><img src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/location.png" alt="'.esc_html(__('location','wp-job-portal')).'" title="'.esc_html(__('location','wp-job-portal')).'"/>' . $address.','.$country . '</div>';
                }
            }

        }
        $html .= '</div>'; // close for the inner section
        $html .= '</div>'; // closing div of resume-top-section
        return $html;
    }

    function getFieldTitleByField($field){
        return wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fieldtitles'][$field]);
    }

    function getRowForView($text, $value, &$i,$themecall=null,$full=0) {
        $html = '';
        if(null != $themecall){
            if(1!=$full){
                if ($i == 0 || $i % 2 == 0) {
                    $html .= '<div class="wjportal-resume-sec-row '.esc_attr($this->class_prefix).'-resumedetail-sec-value">';
                }
            }
        }else{
            if ($i == 0 || $i % 2 == 0) {
                $html .= '<div class="wjportal-resume-sec-row '.esc_attr($this->class_prefix).'-resumedetail-sec-value">';
            }
        }
        if(null != $themecall){
            if(0==$full){
                $html .= '<div class="'.esc_attr($this->class_prefix).'-resumedetail-sec-value-left '.esc_attr($this->class_prefix).'-bigfont">
                            <span class="'.esc_attr($this->class_prefix).'-resumedetail-title">' . $text . ':</span>
                            <span class="'.esc_attr($this->class_prefix).'-resumedetail-value">' . wpjobportal::wpjobportal_getVariableValue($value) . '</span>
                        </div>';
            }else if(1==$full){
                $html .='<div class="'.esc_attr($this->class_prefix).'-resumedetail-sec-value '.esc_attr($this->class_prefix).'-bigfont">
                            <span class="'.esc_attr($this->class_prefix).'-resumedetail-sec-title">' . $text . ':</span>
                            <span class="'.esc_attr($this->class_prefix).'-resumedetail-sec-value">' . wpjobportal::wpjobportal_getVariableValue($value) . '</span>
                        </div>';
            }
        }else{
            $html .= '<div class="wjportal-custom-field wjportal-resume-sec-data">
                        <div class="wjportal-custom-field-tit wjportal-resume-sec-data-title">' . $text . ':</div>
                        <div class="wjportal-custom-field-val wjportal-resume-sec-data-value">' . wpjobportal::wpjobportal_getVariableValue($value) . '</div>
                    </div>';
        }
        $i++;
        if(null != $themecall){
            if(1!=$full){
                if ($i % 2 == 0) {
                    $html .= '</div>';
                }
            }
        }else{
            if ($i % 2 == 0) {
                $html .= '</div>';
            }
        }
        return $html;
    }

    function getRowForForm($text, $value) {
        $html = '<div class="wpjp-resume-date-wrp form">
                    <div class="row-title">' . $text . ':</div>
                    <div class="row-value">' . $value . '</div>
                </div>';
        return $html;
    }
    function getHeadingRowForView($value,$themecall=null) {
        if(null != $themecall){
            $html='<div class="'.esc_attr($this->class_prefix).'-resumedetail-sec-title1">
                <h6 class="'.esc_attr($this->class_prefix).'-resumedetail-sec-title1-txt">'.$value.'</h6>
            </div>';
        }else{
            $html = '<div class="wjportal-resume-inner-sec-heading">' . $value . '</div>';
        }
        return $html;
    }
    function makeanchorfortags($tags,$themecall=null) {
        if (empty($tags)) {
            if(null != $themecall) return;
            $anchor = '<div id="jsresume-tags-wrapper"></div>';
            return $anchor;
        }
        $array = wpjobportalphplib::wpJP_explode(',', $tags);
        $anchor="";
        if(null != $themecall){
            for ($i = 0; $i < count($array); $i++) {
                $with_spaces = wpjobportal::tagfillin($array[$i]);
                $anchor .= '<a title="tags" class="'.esc_attr($this->class_prefix).'-tag" href="' . wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumes', 'tags'=>$with_spaces)) . '"><i class="fas fa-tags tag" aria-hidden="true"></i>' . wpjobportal::wpjobportal_getVariableValue($array[$i]) . '</a>';
            }
        }else{
            $anchor .= '<div id="jsresume-tags-wrapper">';
            $anchor .= '<span class="jsresume-tags-title">' . esc_html(__('Tags', 'wp-job-portal')) . '</span>';
            $anchor .= '<div class="tags-wrapper-border">';
            for ($i = 0; $i < count($array); $i++) {
                $with_spaces = wpjobportal::tagfillin($array[$i]);
                $anchor .= '<a class="wpjobportal_tags_a" href="' . wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'resumes', 'tags'=>$with_spaces)) . '">' . wpjobportal::wpjobportal_getVariableValue($array[$i]) . '</a>';
            }
            $anchor .= '</div>';
            $anchor .= '</div>';
        }
        return $anchor;
    }

}

?>
