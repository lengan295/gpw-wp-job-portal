<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param wp job portal     job object - optional---
 * WP job portal Object's for calling Resume
 * Resume Section wise over Classes
*/
?>
<?php
    $html = '<div class="wjportal-resume-detail-wrapper">';
    $isowner = (WPJOBPORTALincluder::getObjectClass('user')->uid() == wpjobportal::$_data[0]['personal_section']->uid) ? 1 : 0;
    $html .= $resumeviewlayout->getPersonalTopSection($isowner, 1);
    $personal_section_title = wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fieldtitles']['section_personal']);
    $html .= '<div class="wjportal-resume-section-title">'. esc_html($personal_section_title) . '</div>';
    $html .= $resumeviewlayout->getPersonalSection(0, 1);
    $show_section_that_have_value = wpjobportal::$_config->getConfigValue('show_only_section_that_have_value');

    $resume_section_ordering = WPJOBPORTALincluder::getJSModel('fieldordering')->getResumeSections();
    foreach ($resume_section_ordering as $resume_section) {
        // to show resume section according to ordering in field ordering
        // also changed the fixed titles to section titles from field ordering.
        switch ($resume_section->field) {
            case 'section_education':
                $showflag = 1;
                if ($show_section_that_have_value == 1 && empty(wpjobportal::$_data[0]['institute_section'][0])){
                    $showflag = 0;
                }
                if (isset(wpjobportal::$_data[2][3]['section_education']) && $showflag == 1) {
                    // Handling Advance Resume Builder's Addons
                    $education_section_title = wpjobportal::wpjobportal_getVariableValue($resume_section->fieldtitle);
                    $html .= apply_filters('wpjobportal_addons_view_resume_by_section',false,'getEducationSection',$education_section_title);
                }
            break;
            case 'section_employer':
                $showflag = 1;
                if ($show_section_that_have_value == 1 && empty(wpjobportal::$_data[0]['employer_section'][0])){
                    $showflag = 0;
                }
                if (isset(wpjobportal::$_data[2][4]['section_employer']) && $showflag == 1) {
                    // Employer Section Resume
                    $html .= $resumeviewlayout->getEmployerSection(0, 0, 1);
                }

            break;
            case 'section_address':
                $showflag = 1;
                if ($show_section_that_have_value == 1 && empty(wpjobportal::$_data[0]['address_section'][0])){
                    $showflag = 0;
                }
                if (isset(wpjobportal::$_data[2][2]['section_address']) && $showflag == 1) {
                    // Address Section Resume
                    $html .= $resumeviewlayout->getAddressesSection(0, 0, 1);
                }
            break;
            case 'section_skills':
                $showflag = 1;
                if ($show_section_that_have_value == 1 && empty(wpjobportal::$_data[0]['personal_section']->skills)){
                    $showflag = 0;
                }
                if (isset(wpjobportal::$_data[2][5]['section_skills']) && $showflag == 1) {
                    // Handling Advance Resume Builder's Addons
                    $skills_section_title = wpjobportal::wpjobportal_getVariableValue($resume_section->fieldtitle);
                    $html .= apply_filters('wpjobportal_addons_view_resume_by_section',false,'getSkillSection',$skills_section_title);
                }
            break;
            case 'section_language':
                $showflag = 1;
                if ($show_section_that_have_value == 1 && empty(wpjobportal::$_data[0]['language_section'][0])){
                    $showflag = 0;
                }
                if (isset(wpjobportal::$_data[2][8]['section_language']) && $showflag == 1) {
                    $language_section_title = wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fieldtitles']['section_language']);
                    $html .= apply_filters('wpjobportal_addons_view_resume_by_section',false,'getLanguageSection',$language_section_title);
                }
            break;
            default:
                $showflag = 0;
                if (isset(wpjobportal::$_data[0]['personal_section']) && wpjobportal::$_data[0]['personal_section']->quick_apply == 1 ){// to handle quick apply case
                    break;
                }
                if (isset(wpjobportal::$_data[0]['personal_section']) && wpjobportal::$_data[0]['personal_section']->params !='[]' ){// to handle empty section to some extent
                    $showflag = 1;
                }
                if ($showflag == 1) {
                    // Handling Advance Resume Builder's Addons
                    if($resume_section->section > 8){ // to make sure this code only executes for custom resume sections.
                        $resume_section->fieldtitle = wpjobportal::wpjobportal_getVariableValue($resume_section->fieldtitle);
                        $html .= apply_filters('wpjobportal_addons_view_resume_by_section_custom',false,'getCustomSection',$resume_section);
                    }
                }
            break;

        }
    }

    // getting Data over resume class and Show
    echo wp_kses($html,WPJOBPORTAL_ALLOWED_TAGS);

    //new change
    if(isset(wpjobportal::$_data['fieldtitles']['tags'])){
        if (isset(wpjobportal::$_data[0]) && isset(wpjobportal::$_data[0]['personal_section'])) {
            $viewtags = wpjobportal::$_data[0]['personal_section']->viewtags;
        } else {
            $viewtags = '';
        }
        $viewtags = apply_filters('wpjobportal_addons_makeanchor_for_tags',false,$viewtags);
        echo wp_kses($viewtags,WPJOBPORTAL_ALLOWED_TAGS);
    }
?>
