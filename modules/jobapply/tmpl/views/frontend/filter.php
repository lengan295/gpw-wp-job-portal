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

            $html.= '<div class="wjportal-filter">';
            $html.=   WPJOBPORTALformfield::select('sortbycombo', $sortbylist, isset(wpjobportal::$_data['filter']['sortby']) ? wpjobportal::$_data['filter']['sortby'] : null,esc_html(__("Default",'wp-job-portal')),array('onchange'=>'sortbychanged()'));
            $html.='</div>';
            $html.= '<div class="wjportal-filter-image">';
            $html.= '<a href='.esc_url( wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'myjobs','sortby' => wpjobportal::$_sortlinks['newest'], 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))) .' class='.esc_attr($select).'>';
            $html.= '<img src='.esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/" . $img .' alt='.esc_html(__('sort','wp-job-portal')).'>';
            $html.= '</a>
                  </div>';
            break;
        case 'myjobapplfilter':

                $html.= '<div class="wjportal-filter-wrp">';
                $html.= '   <div class="wjportal-filter">';
                $html.=         WPJOBPORTALformfield::select('sorting', $sortbylist, isset(wpjobportal::$_data['combosort']) ? wpjobportal::$_data['combosort'] : null,esc_html(__("Default",'wp-job-portal')),array('onchange'=>'changeCombo()'));
                $html.='    </div>';
                $html.= '   <div class="wjportal-filter-image">';
                $data_sortby = '';
                if (isset(wpjobportal::$_data['sortby'])) {
                    $data_sortby = wpjobportal::$_data['sortby'];
                }
                $html .= '<a class="sort-icon" href="#" data-image1='. esc_attr($image1).' data-image2='. esc_attr($image2).' data-sortby='.$data_sortby.'><img id="sortingimage" src='.  esc_url($image).'></a>';
                $html .= '</div>';
                $html.= ' </div>';
        break;
        case 'sortby':?>
        <div id="resume-list-navebar" class="wjportal-filter-wrp">
            <div class="wjportal-filter">
                <?php echo wp_kses(WPJOBPORTALformfield::select('sorting', $sortbylist, isset(wpjobportal::$_data['combosort']) ? wpjobportal::$_data['combosort'] : null,esc_html(__("Default",'wp-job-portal')),array('onchange'=>'changeCombo()')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            </div>
        <div class="wjportal-filter-image">
            <a class="sort-icon" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(wpjobportal::$_data['sortby']); ?>"><img id="sortingimage" src="<?php echo esc_url($image); ?>" /></a>
        </div>
    </div>
    <?php
    if (wpjobportal::$_data[0]['applied'] != null or wpjobportal::$_data[0]['hits'] != null) { ?>
        <div class="wjportal-view-job-count">
            <span class="wjportal-view-job-txt">
                <?php echo esc_html(__('Job View', 'wp-job-portal')) . ': ' . esc_html(wpjobportal::$_data[0]['hits']) . ' / ' . esc_html(__('Applied', 'wp-job-portal')) . ': ' . esc_html(wpjobportal::$_data[0]['applied']) ?>
            </span>
        </div>
    <?php }
    }
    echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
?>
