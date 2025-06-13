<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param js-job optional
* Filter's FrontEnd
*/
?>
<?php
$html = '';
switch ($layout) {
	case 'myjobfilter':
	    $html.= '<div class="wjportal-filter-wrp">';
        $html.= '   <div class="wjportal-filter">';
        $html.=         WPJOBPORTALformfield::select('sorting', $sortbylist, isset(wpjobportal::$_data['combosort']) ? wpjobportal::$_data['combosort'] : null,esc_html(__("Default",'wp-job-portal')),array('onchange'=>'changeCombo()'));
        $html.='    </div>';
        $html.= '   <div class="wjportal-filter-image">';
        $html .= '<a class="sort-icon" href="#" data-image1='. esc_attr($image1).' data-image2='. esc_attr($image2).' data-sortby='.(wpjobportal::$_data['sortby']).'><img id="sortingimage" src='.  esc_attr($image).'></a>';
        // $html .= '</div>';
        // $html.= '   <div class="wjportal-filter-image">';
        // $html.= '       <a href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs','sortby' => wpjobportal::$_sortlinks['newest'], 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))) .' >';
        // $html.= '           <img  src='.esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/" . esc_attr($img) .'>';
        // $html.= '       </a>
        //             </div>';
        $html.= ' </div>
                </div>';
        $html.= '<div class="wjportal-act-btn-wrp">';
        $html.= '    <a class="wjportal-act-btn" href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'addjob'))).'> <i class="fa fa-plus"></i>'. esc_html(__('Add New Job', 'wp-job-portal')).'</a>
                </div>';

		break;


	case 'newestjobsfilter':

            $html.='<div class="wjportal-filter-search-wrp"> ';
            // Hide job title filter based on shortcode option
            if(empty(wpjobportal::$_data['shortcode_option_hide_filter_job_title'])){ // if this value is set means hide this option is set in shortcode
                $html.='    <div class="wjportal-filter-search-field-wrp">
                            '. WPJOBPORTALformfield::text('jobtitle',isset(wpjobportal::$_data['filter']['jobtitle']) ? wpjobportal::$_data['filter']['jobtitle'] : '',array('placeholder'=>esc_html(__('Title','wp-job-portal')), 'class'=>'wjportal-filter-search-input-field')).'
                        </div> ';
            }
            // Hide job location filter based on shortcode option
            if(empty(wpjobportal::$_data['shortcode_option_hide_filter_job_location'])){ // if this value is set means hide this option is set in shortcode
                $html.='    <div class="wjportal-filter-search-field-wrp">
                            '. WPJOBPORTALformfield::text('city',isset(wpjobportal::$_data['filter']['city_ids']) ? wpjobportal::$_data['filter']['city_ids'] : '',array('placeholder'=>esc_html(__("City",'wp-job-portal')),'class'=>'wpjobportal-job-listing-city-field')).'
                        </div> ';
            }
            $html.='    <div class="wjportal-filter-search-btn-wrp">
                            <button type="submit" class="wjportal-filter-search-btn">
                                <i class="fa fa-search"></i>
                            </button>
                            <button id="reset-newest-jobfilter" type="reset" class="wjportal-filter-reset-btn">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div>
                </div>';
            $html .= WPJOBPORTALformfield::hidden('wpjobportallay' , 'jobs');
            $html .= WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search' , 'WPJOBPORTAL_SEARCH');
		break;

    case 'aijobfilter':
            // action  hook code is in AI Job search addon
            // hook creates inout field and hidden fields for form
            //$html .= do_action('wpjobportal_addons_aijobsearch_field');
        $html.='<div class="wjportal-filter-ai-searchfrm-wrp">
                    <div class="wjportal-ai-searchfrm-logo-wrp">
                        <img class="wjportal-ai-searchfrm-logo" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/ai-icon.png " alt="' . esc_html(__('AI Search', 'wp-job-portal')) . '" />
                    </div>
                 ';
            $html.='<div class="wjportal-aifilter-search-wrp">
                <span class="wjportal-filter-ai-searchfrm-title">' . esc_html(__('Unlock your career potential with AI-driven job search', 'wp-job-portal')) . '</span>
             ';
            // Hide job title filter based on shortcode option

            $html.='    <div class="wjportal-filter-search-field-wrp">
                        '. WPJOBPORTALformfield::text('aijobsearcch',isset(wpjobportal::$_data['filter']['aijobsearcch']) ? wpjobportal::$_data['filter']['aijobsearcch'] : '',array('placeholder'=>esc_html(__("Ready to find your dream job? Let's get started",'wp-job-portal')), 'class'=>'wjportal-filter-search-input-field')).'
                    </div>';
            $html.='    <div class="wjportal-filter-search-btn-wrp">
                            <button type="submit" class="wjportal-filter-search-btn">
                                <img class="wjportal-filter-search-field-icon" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/search-icon.png " alt="' . esc_html(__('Search', 'wp-job-portal')) . '" />
                                ' . esc_html(__('Search Jobs', 'wp-job-portal')) . '
                            </button>
                        </div>';
                    //$html.='    <span class="wjportal-filter-ai-searchfrm-discription">' . esc_html(__('Start typing what you know – our AI will help you find the best matching jobs.', 'wp-job-portal')) . '</span>';
                    $html.='
                </div>
        </div>';
        $html .= WPJOBPORTALformfield::hidden('wpjobportallay' , 'jobs');
        $html .= WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search' , 'WPJOBPORTAL_SEARCH');
        break;
}
echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);

?>
