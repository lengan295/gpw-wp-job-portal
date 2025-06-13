<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* 
*/
?>
<?php
switch ($filter) {
    case 'resume':
    ?>
        <div id="resume-list-navebar" class="wjportal-filter-wrp">
            <div class="wjportal-filter">
                <?php echo wp_kses(WPJOBPORTALformfield::select('sorting', $sortbylist, isset(wpjobportal::$_data['combosort']) ? wpjobportal::$_data['combosort'] : null,esc_html(__("Default",'wp-job-portal')),array('onchange'=>'changeCombo()')), WPJOBPORTAL_ALLOWED_TAGS); ?>
            </div>
        <div class="wjportal-filter-image">
            <a class="sort-icon" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(wpjobportal::$_data['sortby']); ?>"><img id="sortingimage" src="<?php echo esc_url($image); ?>" /></a>
        </div>
    </div>
        <?php
    break;

    case 'airesumesearch':
        //do_action('wpjobportal_addons_airesumesearch_field');
        $html = '';
        $html.='<div class="wjportal-filter-ai-searchfrm-wrp">
                    <div class="wjportal-ai-searchfrm-logo-wrp">
                        <img class="wjportal-ai-searchfrm-logo" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/ai-icon.png " alt="' . esc_html(__('AI Search', 'wp-job-portal')) . '" />
                    </div>
                 ';
            $html.='<div class="wjportal-aifilter-search-wrp">
                <span class="wjportal-filter-ai-searchfrm-title">' . esc_html(__('Find your next star hire with AI-driven resume search', 'wp-job-portal')) . '</span>
             ';
            // Hide job title filter based on shortcode option
                $html.='    <div class="wjportal-filter-search-field-wrp">
                            '. WPJOBPORTALformfield::text('airesumesearcch',isset(wpjobportal::$_data['filter']['airesumesearcch']) ? wpjobportal::$_data['filter']['airesumesearcch'] : '',array('placeholder'=>esc_html(__("Ready to find your potential employee? Let's get started",'wp-job-portal')), 'class'=>'wjportal-filter-search-input-field')).'
                        </div>';
            $html.='    <div class="wjportal-filter-search-btn-wrp">
                            <button type="submit" class="wjportal-filter-search-btn">
                                <img class="wjportal-filter-search-field-icon" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/search-icon.png " alt="' . esc_html(__('Search', 'wp-job-portal')) . '" />
                                ' . esc_html(__('Search Resumes', 'wp-job-portal')) . '
                            </button>
                        </div>';
                //$html.='        <span class="wjportal-filter-ai-searchfrm-discription">' . esc_html(__('Start typing what you know – our AI will help you find the best matching resumes.', 'wp-job-portal')) . '</span>';
                $html.='
                </div>
        </div>';
        $html .= WPJOBPORTALformfield::hidden('wpjobportallay' , 'resumes');
        $html .= WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search' , 'WPJOBPORTAL_SEARCH');
        echo $html;
    break;
}
