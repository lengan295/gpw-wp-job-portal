<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal optional --
*Filter's & Sortion
*/
?>
<!-- quick actions -->
<div id="wpjobportal-page-quick-actions">
    <?php
        $image1 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/control_panel/dashboard/sorting-white-1.png";
        $image2 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/control_panel/dashboard/sorting-white-2.png";
        if (wpjobportal::$_data['sortby'] == 1) {
            $image = $image1;
        } else {
            $image = $image2;
        }
    ?>
    <div Onclick="ShowPopup()" id="filter-activity-log" class="wpjobportal-page-quick-act-btn">
        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/settings.png">
        <?php echo esc_html(__('Settings', 'wp-job-portal')); ?>
    </div>
    <div class="wpjobportal-sorting-wrp">
        <span class="wpjobportal-sort-text">
            <?php echo esc_html(__('Sort by', 'wp-job-portal')); ?>:
        </span>
        <span class="wpjobportal-sort-field">
            <?php echo wp_kses(WPJOBPORTALformfield::select('sorting', $categoryarray, wpjobportal::$_data['combosort'], '', array('class' => 'inputbox', 'onchange' => 'changeCombo();')),WPJOBPORTAL_ALLOWED_TAGS); ?>
        </span>
        <a class="wpjobportal-sort-icon" id="sort-icon" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(wpjobportal::$_data['sortby']); ?>" onclick="buttonClick();">
            <img id="sortingimage" src="<?php echo esc_url($image); ?>" />
        </a>
    </div>
</div>
