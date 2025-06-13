<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param Multi operation =>Select All
* Delete All
*/
if (isset($job->id)) {
    $jobid = $job->id;
} else {
    $jobid = '';
}
?>
<div id="wpjobportal-page-quick-actions">
    <label class="wpjobportal-page-quick-act-btn" onclick="return highlightAll();" for="selectall">
        <input type="checkbox" name="selectall" id="selectall" value="">
        <?php echo esc_html(__('Select All', 'wp-job-portal')) ?>
    </label>
    <a class="wpjobportal-page-quick-act-btn multioperation" onclick="gettoget()" message="<?php echo esc_attr(WPJOBPORTALMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')) .' ?'; ?>" data-for="remove" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_job&task=remove&action=wpjobportaltask&&callfrom=1&wpjobportal-cb[]='.$jobid),'wpjobportal_job_nonce')); ?>">
        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/forced-delete.png" alt="<?php echo esc_html(__('delete', 'wp-job-portal')) ?>" />
        <?php echo esc_html(__('Delete', 'wp-job-portal')) ?>
    </a>
    <?php
        $image1 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/control_panel/dashboard/sorting-white-1.png";
        $image2 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/control_panel/dashboard/sorting-white-2.png";
        if (wpjobportal::$_data['sortby'] == 1) {
            $image = $image1;
        } else {
            $image = $image2;
        }
    ?>
    <div class="wpjobportal-sorting-wrp">
        <span class="wpjobportal-sort-text">
            <?php echo esc_html(__('Sort by', 'wp-job-portal')); ?>:
        </span>
        <span class="wpjobportal-sort-field">
            <?php echo wp_kses(WPJOBPORTALformfield::select('sorting', $categoryarray, wpjobportal::$_data['combosort'], '', array('class' => 'inputbox', 'onchange' => 'changeCombo();')),WPJOBPORTAL_ALLOWED_TAGS); ?>
        </span>
        <a class="wpjobportal-sort-icon sort-icon" href="#" data-image1="<?php echo esc_attr($image1); ?>" data-image2="<?php echo esc_attr($image2); ?>" data-sortby="<?php echo esc_attr(wpjobportal::$_data['sortby']); ?>">
            <img id="sortingimage" src="<?php echo esc_url($image); ?>" />
        </a>
    </div>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        function changeSortBy() {
            var value = jQuery('a.sort-icon').attr('data-sortby');
            var img = '';
            if (value == 1) {
                value = 2;
                img = jQuery('a.sort-icon').attr('data-image2');
            } else {
                img = jQuery('a.sort-icon').attr('data-image1');
                value = 1;
            }
            jQuery('img#sortingimage').attr('src', img);
            jQuery('input#sortby').val(value);
            jQuery('form#wpjobportalform').submit();
        }
        jQuery('a.sort-icon').click(function (e) {
            e.preventDefault();
            changeSortBy();
        });
        function changeCombo() {
            jQuery('input#sorton').val(jQuery('select#sorting').val());
            changeSortBy();
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
</div>