<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobSearchModel {

    function getSearchJobs_Widget($title, $showtitle, $fieldtitle, $category, $jobtype, $jobstatus, $salaryrange, $shift, $duration, $startpublishing, $stoppublishing, $company, $address, $columnperrow, $layout = 'vertical', $show_adv_button = true, $use_icons_for_buttons = false, $field_custom_class = '',$show_labels = 1,$show_placeholders = 0) {
        // new variables
        //$layout = 'vertical', $show_adv_button = false, $use_icons_for_buttons = false, $custom_css_classes = '', $field_custom_class = ''

        // Count how many fields are enabled
        $enabled_fields = array_filter([
            $fieldtitle,
            $category,
            $jobtype,
            $company,
            $address
        ]);

        $count = 0;
        foreach ($enabled_fields as $enabled) {
            if ($enabled) {
                $count++;
            }
        }
        $visible_field_count = $count;
        // to handle button widths ( mainly for horizental style and advnce search diasble case for less then four fields)
        $button_wrap_class = '';
        // Set widths
        if ($layout === 'vertical') {
            $field_width = '100';
            $button_style = '100';
        } else {
            if ($visible_field_count > 3) {
                $columns = $columnperrow; //
                $field_width = round(100 / $columns, 2);
                $button_style = $field_width;
            } else {
                $button_style = $show_adv_button ? 25 : 15;
                $button_wrap_class = $show_adv_button ? '' : 'wpjobportal-search-btn-full-width';// to handle button widths ( mainly for horizental style and advnce search diasble case for less then four fields)
                $field_columns = $columnperrow; // avoid divide-by-zero
                $field_width = round((100 - $button_style) / $field_columns, 2);
            }
        }
        if(!function_exists('renderCurrentFieldJP')){
            function renderCurrentFieldJP($title_str, $field_html, $field_width) {
                $current_html = '<div class="wjportal-form-row " style="width:' . esc_attr($field_width) . '%;">
                    <div class="wjportal-form-tit">' . esc_html($title_str) . '</div>
                    <div class="wjportal-form-val">' . wp_kses($field_html, WPJOBPORTAL_ALLOWED_TAGS) . '</div>
                </div>';
                return $current_html;
            }
        }


        $layout_class = $layout == 'horizontal' ? 'wjportal-form-horizontal' : 'wjportal-form-vertical';

        $html = '<div id="wpjobportal_mod_wrapper" class="wjportal-search-mod wjportal-form-mod ' . esc_attr($layout_class) . '">';

        if ($showtitle == 1 && $title != '') {
            $html .= '<div id="wpjobportal-mod-heading" class="wjportal-mod-heading">' . esc_html($title) . '</div>';
        }

        $html .= '<form class="job_form wjportal-form" id="job_form" method="post" action="' . esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))) . '">';


        if ($fieldtitle == 1) {
            $title = '';
            $placeholder = '';
            if($show_labels == 1){
                $title = esc_html(__('Job Title', 'wp-job-portal'));
            }
            if($show_placeholders == 1){
                $placeholder = esc_html(__('Job Title', 'wp-job-portal'));
            }
            $value = WPJOBPORTALformfield::text('jobtitle', '', array('class' => 'inputbox'.' '.$field_custom_class, 'placeholder' => $placeholder));
            $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        if ($category == 1) {
            $title = '';
            $placeholder = '';
            if($show_labels == 1){
                $title = esc_html(__('Job Category', 'wp-job-portal'));
            }
            if($show_placeholders == 1){
                $placeholder = esc_html(__('Select Job Category', 'wp-job-portal'));
            }
            $value = WPJOBPORTALformfield::select('category[]', WPJOBPORTALincluder::getJSModel('category')->getCategoriesForCombo(), isset(wpjobportal::$_data['filter']['category']) ? wpjobportal::$_data['filter']['category'] : '', $placeholder, array('class' => 'inputbox'.' '.$field_custom_class));
            $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        if ($jobtype == 1) {
            $title = '';
            $placeholder = '';
            if($show_labels == 1){
                $title = esc_html(__('Job Type', 'wp-job-portal'));
            }
            if($show_placeholders == 1){
                $placeholder = esc_html(__('Select Job Type', 'wp-job-portal'));
            }
            $value = WPJOBPORTALformfield::select('jobtype[]', WPJOBPORTALincluder::getJSModel('jobtype')->getJobTypeForCombo(), isset(wpjobportal::$_data['filter']['jobtype']) ? wpjobportal::$_data['filter']['jobtype'] : '', $placeholder, array('class' => 'inputbox'.' '.$field_custom_class));
            $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        if ($jobstatus == 1) {
            $title = '';
            $placeholder = '';
            if($show_labels == 1){
                $title = esc_html(__('Job Status', 'wp-job-portal'));
            }
            if($show_placeholders == 1){
                $placeholder = esc_html(__('Select Job Status', 'wp-job-portal'));
            }
            $value = WPJOBPORTALformfield::select('jobstatus[]', WPJOBPORTALincluder::getJSModel('jobstatus')->getJobStatusForCombo(), isset(wpjobportal::$_data['filter']['jobstatus']) ? wpjobportal::$_data['filter']['jobstatus'] : '', $placeholder, array('class' => 'inputbox'.' '.$field_custom_class));
            $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        if ($duration == 1) {
            $title = '';
            $placeholder = '';
            if($show_labels == 1){
                $title = esc_html(__('Duration', 'wp-job-portal'));
            }
            if($show_placeholders == 1){
                $placeholder = esc_html(__('Duration', 'wp-job-portal'));
            }
            $value = WPJOBPORTALformfield::text('duration', isset(wpjobportal::$_data['filter']['duration']) ? wpjobportal::$_data['filter']['duration'] : '', array('class' => 'inputbox'.' '.$field_custom_class, 'placeholder' => $placeholder));
            $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        if ($company == 1) {
            $title = '';
            $placeholder = '';
            if($show_labels == 1){
                $title = esc_html(__('Company', 'wp-job-portal'));
            }
            if($show_placeholders == 1){
                $placeholder = esc_html(__('Select Company', 'wp-job-portal'));
            }
            $value = WPJOBPORTALformfield::select('company[]', WPJOBPORTALincluder::getJSModel('company')->getCompaniesForCombo(), isset(wpjobportal::$_data['filter']['company']) ? wpjobportal::$_data['filter']['company'] : '', $placeholder, array('class' => 'inputbox'.' '.$field_custom_class));
            $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        if ($address == 1) {
            $title = '';
            $placeholder = '';
            if($show_labels == 1){
                $title = esc_html(__('City', 'wp-job-portal'));
            }
            if($show_placeholders == 1){
                $placeholder = esc_html(__('City', 'wp-job-portal'));
            }
            $value = WPJOBPORTALformfield::text('city', isset(wpjobportal::$_data['filter']['city']) ? wpjobportal::$_data['filter']['city'] : '', array('class' => 'inputbox wpjobportal-job-search-widget-city-field', 'placeholder' => $placeholder));
            $html .= renderCurrentFieldJP($title, $value, $field_width);
        }


        if ($salaryrange == 1) {
            // $title = esc_html(__('Salary Range', 'wp-job-portal'));
            // $value  = WPJOBPORTALformfield::select('currencyid', WPJOBPORTALincluder::getJSModel('currency')->getCurrencyForCombo(), isset(wpjobportal::$_data[0]['filter']->currencyid) ? wpjobportal::$_data[0]['filter']->currencyid : '', esc_html(__('Select','wp-job-portal')) . ' ' . esc_html(__('Currency', 'wp-job-portal')), array('class' => 'inputbox sal'.' '.$field_custom_class));
            // $value .= WPJOBPORTALformfield::select('salaryrangestart', WPJOBPORTALincluder::getJSModel('salaryrange')->getJobStartSalaryRangeForCombo(), isset(wpjobportal::$_data[0]['filter']->salaryrange) ? wpjobportal::$_data[0]['filter']->salaryrange : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Salary Range','wp-job-portal')) .' '. esc_html(__('Start', 'wp-job-portal')), array('class' => 'inputbox sal'.' '.$field_custom_class));
            // $value .= WPJOBPORTALformfield::select('salaryrangeend', WPJOBPORTALincluder::getJSModel('salaryrange')->getJobEndSalaryRangeForCombo(), isset(wpjobportal::$_data[0]['filter']->salaryrange) ? wpjobportal::$_data[0]['filter']->salaryrange : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Salary Range','wp-job-portal')) .' '. esc_html(__('End', 'wp-job-portal')), array('class' => 'inputbox sal'.' '.$field_custom_class));
            // $value .= WPJOBPORTALformfield::select('salaryrangetype', WPJOBPORTALincluder::getJSModel('salaryrangetype')->getSalaryRangeTypesForCombo(), isset(wpjobportal::$_data[0]['filter']->salaryrangetype) ? wpjobportal::$_data[0]['filter']->salaryrangetype : '', esc_html(__('Select','wp-job-portal')) .' '. esc_html(__('Salary Range Type', 'wp-job-portal')), array('class' => 'inputbox sal'.' '.$field_custom_class));
            // $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        if ($startpublishing == 1) {
            // $title = esc_html(__('Start Publishing', 'wp-job-portal'));
            // $value = WPJOBPORTALformfield::date('startpublishing', isset(wpjobportal::$_data['filter']['startpublishing']) ? wpjobportal::$_data['filter']['startpublishing'] : '', array('class' => 'inputbox'.' '.$field_custom_class));
            // $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        if ($stoppublishing == 1) {
            // $title = esc_html(__('Stop Publishing', 'wp-job-portal'));
            // $value = WPJOBPORTALformfield::date('stoppublishing', isset(wpjobportal::$_data['filter']['stoppublishing']) ? wpjobportal::$_data['filter']['stoppublishing'] : '', array('class' => 'inputbox'.' '.$field_custom_class));
            // $html .= renderCurrentFieldJP($title, $value, $field_width);
        }

        // Buttons
        $search_label = $use_icons_for_buttons ? ' <i class="fa fa-search"></i> ' : esc_html(__('Search Job', 'wp-job-portal'));
        $adv_label = $use_icons_for_buttons ? ' <i class="fa fa-cogs"></i> ' : esc_html(__('Advance Search', 'wp-job-portal'));

        $html .= '<div class="wjportal-form-btn-row '.esc_attr($button_wrap_class).' " style="width:' . $button_style . '%;"> ';
                if($show_labels == 1){
                    $html .='    <div class="wjportal-form-tit">&nbsp;</div>';
                }
        $html .='
                        <button type="submit" class="wjportal-filter-search-btn">
                            ' . $search_label . '
                        </button>
            ' . ( ($show_adv_button) ? '<a class="anchor wjportal-form-btn wjportal-form-adv-srch-btn" href="' . esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobsearch', 'wpjobportallt'=>'jobsearch', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))) . '">' . $adv_label . '</a>' : '') . '
        </div>';

        $html .= '
            <input type="hidden" id="issearchform" name="issearchform" value="1"/>
            <input type="hidden" id="WPJOBPORTAL_form_search" name="WPJOBPORTAL_form_search" value="WPJOBPORTAL_SEARCH"/>
            <input type="hidden" id="wpjobportallay" name="wpjobportallay" value="jobs"/>
        </form>
        </div>';

        wp_register_script( 'wpjobportal-inline-handle', '' );
        wp_enqueue_script( 'wpjobportal-inline-handle' );
        $inline_js_script = '
            function getTokenInputWidget() {
                var cityArray = "' . esc_url_raw(admin_url("admin.php?page=wpjobportal_city&action=wpjobportaltask&task=getaddressdatabycityname")) . '";
                jQuery(".wpjobportal-job-search-widget-city-field").tokenInput(cityArray, {
                    theme: "wpjobportal",
                    preventDuplicates: true,
                    hintText: "' . esc_html(__('Type In A Search Term', 'wp-job-portal')) . '",
                    noResultsText: "' . esc_html(__('No Results', 'wp-job-portal')) . '",
                    searchingText: "' . esc_html(__('Searching', 'wp-job-portal')) . '"
                });
            }
            jQuery(document).ready(function(){
                getTokenInputWidget();
            });
        ';
        wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );

        return $html;
    }

    function getJobSearchOptions() {
        wpjobportal::$_data[2] = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldsOrderingforSearch(2);
    }

    function getMessagekey(){
        $key = 'jobsearch';if(wpjobportal::$_common->wpjp_isadmin()){$key = 'admin_'.$key;}return $key;
    }
}

?>