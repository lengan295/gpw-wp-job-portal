<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 * @param company  company object - optional
 * @param Default Parameters
 */
if (!isset($job)) {
    $job = null;
}
if (!isset($company)) {
    $company = null;
}
if (!isset($fields)) {
    $fields = wpjobportal::$_wpjpfieldordering->getFieldsOrderingforForm(2);
}
$formfields = array();
foreach($fields AS $field){
    $content = '';
   switch ($field->field) {
        case "jobtitle":
            $content = WPJOBPORTALformfield::text('title', isset($job->title) ? $job->title : '', array('class' => 'inputbox wjportal-form-input-field', 'data-validation' => $field->validation,'placeholder'=> wpjobportal::wpjobportal_getVariableValue($field->placeholder)));
        break;
        case 'jobcategory':
            $content = WPJOBPORTALformfield::select('jobcategory', WPJOBPORTALincluder::getJSModel('category')->getCategoryForCombobox(), isset($job->jobcategory)  ? $job->jobcategory : WPJOBPORTALincluder::getJSModel('category')->getDefaultCategoryId(), $field->placeholder, array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => $field->validation));
        break;
        case 'company':
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
            if(!WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                if (wpjobportal::$_common->wpjp_isadmin()) {
                    $content = WPJOBPORTALformfield::select('companyid', WPJOBPORTALincluder::getJSModel('company')->getCompaniesForCombo(), isset($job->companyid) ? $job->companyid : 0, esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Company','wp-job-portal')), array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => $field->validation));
                } else {
                    if(in_array('multicompany',wpjobportal::$_active_addons)){
                       if(WPJOBPORTALincluder::getObjectClass('user')->isemployer()){
                            if (WPJOBPORTALincluder::getJSModel('company')->employerHaveCompany($uid)) {
                                $content = WPJOBPORTALformfield::select('companyid', WPJOBPORTALincluder::getJSModel('company')->getCompanyForCombo($uid), isset($job->companyid) ? $job->companyid : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')), array('class' => 'inputbox wjportal-form-select-field', 'onchange' => 'getdepartments(\'departmentid\', this.value);', 'data-validation' => $field->validation));
                            } else {
                                $content = '<a class="wjportal-form-add-comp" href="'.wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'addcompany')).'">' . esc_html(__('Add','wp-job-portal')).' '. esc_html(__('Company', 'wp-job-portal')) . '</a><input type="hidden" name="companyid" id="companyid" data-validation="'.$field->validation.'" />';
                            }
                        }
                    }else{
                        $company = WPJOBPORTALincluder::getJSModel('company')->getSingleCompanyByUid($uid);
                        if(isset($company->id)){
                            $companyname = isset($job->companyid) ? WPJOBPORTALincluder::getJSModel('company')->getCompanynameById($job->companyid): $company->name;
                            $companyid = isset($job->companyid) ? $job->companyid : $company->id;
                         $content = "<div class='wjportal-form-text'>".$companyname ." </div>";
                            $content .= WPJOBPORTALformfield::hidden('companyid',$companyid);
                        }else{
                              $content = '<a class="wjportal-form-add-comp" href="'.wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'addcompany')).'">' . esc_html(__('Add','wp-job-portal')).' '. esc_html(__('Company', 'wp-job-portal')) . '</a><input type="hidden" name="companyid" id="companyid" data-validation="'.$field->validation.'" />';
                        }

                    }
                }
            }
        break;
        case 'heighesteducation':
            $content = "<div class='wjportal-form-2-fields'>";
            $content .= "<div class='wjportal-form-inner-fields'>";
            $content .= WPJOBPORTALformfield::select('educationid', WPJOBPORTALincluder::getJSModel('highesteducation')->getHighestEducationForCombo(), $job ? $job->educationid : WPJOBPORTALincluder::getJSModel('highesteducation')->getDefaultEducationId(), $field->placeholder, array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => $field->validation));
            $content .= "</div>";
            $content .= "<div class='wjportal-form-inner-fields'>";
            $content .= WPJOBPORTALformfield::text('degreetitle', $job ? $job->degreetitle : '', array('class' => 'inputbox wjportal-form-input-field','data-validation' => $field->validation));
            $content .= "</div>";
            $content .= "</div>";
        break;
        case 'experience':
            $content = WPJOBPORTALformfield::text('experience', $job ? $job->experience : '', array('class' => 'inputbox wjportal-form-input-field', 'data-validation' => $field->validation,'placeholder'=> wpjobportal::wpjobportal_getVariableValue($field->placeholder)));
        break;
        case 'map':
            if(in_array('addressdata', wpjobportal::$_active_addons)){
               $content = apply_filters('wp_jobportal_credit_addons_map_load_for_jobform',false,$field,$job);
            }
            break;
        case 'jobsalaryrange':
            $content = WPJOBPORTALincluder::getTemplateHtml('job/salary-field', array('class' => 'inputbox wjportal-form-select-field','field' => $field, 'job' => $job));
        break;
        case 'stoppublishing':
            if($field->required == 1) {
                $required = "required";
            }else{
                $required = '';
            }
            $content = WPJOBPORTALformfield::text('stoppublishing', isset($job->stoppublishing) ?  gmdate(wpjobportal::$_config->getConfigValue('date_format'), strtotime($job->stoppublishing))  : '', array('class' => 'custom_date one wjportal-form-date-field','placeholder'=>wpjobportal::wpjobportal_getVariableValue($field->placeholder),'data-validation'=>$required,'autocomplete'=>'off'));

        break;
        case 'metadescription':
            $content = WPJOBPORTALformfield::textarea('metadescription', isset($job->metadescription) ? $job->metadescription : '', array('class' => 'inputbox one wjportal-form-textarea-field', 'rows' => '7', 'cols' => '94', $field->validation));
        break;
        case 'department':
            // do not show department field for visitor add job form
            if(WPJOBPORTALincluder::getObjectClass('user')->isguest()){
                break;
            }
            $uid = WPJOBPORTALincluder::getObjectClass('user')->uid();
            $company = WPJOBPORTALincluder::getJSModel('company')->getSingleCompanyByUid($uid);
            if(isset($company) && !empty($company->uid)){
                $id = $company->uid;
            }else{
                $id = '' ;
            }
            $content = apply_filters('wpjobportal_addons_get_department',false,$job,$field,$id);
        break;
        case 'jobtype':
            $content = WPJOBPORTALformfield::select('jobtype', WPJOBPORTALincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset($job->jobtype) ? $job->jobtype : WPJOBPORTALincluder::getJSModel('jobtype')->getDefaultJobTypeId(), $field->placeholder, array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => $field->validation));
            break;
        case 'noofjobs':
            $content = WPJOBPORTALformfield::text('noofjobs', isset($job->noofjobs) ? $job->noofjobs : '', array('class' => 'inputbox one wjportal-form-input-field', 'data-validation' => $field->validation,'placeholder'=> wpjobportal::wpjobportal_getVariableValue($field->placeholder)));
            break;
        case 'jobstatus':
            $content = WPJOBPORTALformfield::select('jobstatus', WPJOBPORTALincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset($job->jobstatus) ? $job->jobstatus : WPJOBPORTALincluder::getJSModel('jobstatus')->getDefaultJobStatusId(), $field->placeholder, array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => $field->validation));
            break;
        case 'duration':
            $content = WPJOBPORTALformfield::text('duration', isset($job->duration) ? $job->duration : '', array('class' => 'inputbox wjportal-form-input-field', 'data-validation' => $field->validation,'placeholder'=> wpjobportal::wpjobportal_getVariableValue($field->placeholder)));
            break;
        case 'description':
            $content = WPJOBPORTALformfield::editor('description', isset($job->description) ? $job->description : '', array('class' => 'inputbox one wjportal-form-textarea-field'));
            break;
        case 'careerlevel':
            $content = WPJOBPORTALformfield::select('careerlevel', WPJOBPORTALincluder::getJSModel('careerlevel')->getCareerLevelsForCombo(), isset($job->careerlevel) ? $job->careerlevel : WPJOBPORTALincluder::getJSModel('careerlevel')->getDefaultCareerlevelId(), $field->placeholder, array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => $field->validation));
            break;
        case 'city':
            $content = WPJOBPORTALformfield::text('city', isset($job->city) ? $job->city : '', array('class' => 'inputbox wpjobportal-form-input-field wpjobportal-job-form-city-field', 'data-validation' => $field->validation));
            break;
        case 'tags':
          if(in_array('tag',wpjobportal::$_active_addons)){
                $content = apply_filters('wp_job_portal_credit_job_input_for_tagline',false,$field,$job) ;
            }
            break;
        case 'emailsetting':
            if(in_array('jobalert', wpjobportal::$_active_addons)){
                $content = apply_filters('wp_job_portal_credit_job_input_for_email_filter',false,$job,$field) ;
            }
            break;
        case 'metakeywords':
            $content = WPJOBPORTALformfield::textarea('metakeywords', isset($job->metakeywords) ? $job->metakeywords : '', array('class' => 'inputbox one wjportal-form-textarea-field', 'rows' => '7', 'cols' => '94', $field->validation));
            break;
        case 'metadescription':
            $content = WPJOBPORTALformfield::textarea('metakeywords', isset($job->metakeywords) ? $job->metakeywords : '', array('class' => 'inputbox one wjportal-form-textarea-field', 'rows' => '7', 'cols' => '94', $field->validation));
            break;
        case 'termsandconditions':
            if(!isset($job)){
                $termsandconditions_flag = 1;
                $termsandconditions_fieldtitle = $field->fieldtitle;
                // $content = get_the_permalink(wpjobportal::$_configuration['terms_and_conditions_page_job']);
            }
            break;
        case 'joblink':
            $content = WPJOBPORTALformfield::checkbox("jobapplylink", array('1' => esc_html__("Set Job Apply Redirect Link","wp-job-portal")), isset($job->jobapplylink) && $job->jobapplylink == 1 ? 1 : 0);
            $content .= "<div id='input-text-joblink'>".WPJOBPORTALformfield::text('joblink', isset($job->joblink) ? $job->joblink : '', array('class' => 'inputbox one wjportal-form-input-field input-text-joblink', 'data-validation' => $field->validation,'placeholder'=> wpjobportal::wpjobportal_getVariableValue($field->placeholder)))."</div>";
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
