<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal optional
* Multioperation
*/
?>
<div id="wpjobportal-page-quick-actions">
    <a class="wpjobportal-page-quick-act-btn multioperation" message="<?php echo esc_attr(WPJOBPORTALMessages::getMSelectionEMessage()); ?>" data-for="publish" href="#" title="<?php echo esc_html(__('publish', 'wp-job-portal')) ?>">
    	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" alt="<?php echo esc_html(__('publish', 'wp-job-portal')) ?>" />
    	<?php echo esc_html(__('Publish', 'wp-job-portal')) ?>
    </a>
    <a class="wpjobportal-page-quick-act-btn multioperation" message="<?php echo esc_attr(WPJOBPORTALMessages::getMSelectionEMessage()); ?>" data-for="unpublish" href="#" title="<?php echo esc_html(__('unpublish', 'wp-job-portal')) ?>">
    	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" alt="<?php echo esc_html(__('unpublish', 'wp-job-portal')) ?>" />
    	<?php echo esc_html(__('Unpublish', 'wp-job-portal')) ?>
    </a>
    <a class="wpjobportal-page-quick-act-btn multioperation" message="<?php echo esc_attr(WPJOBPORTALMessages::getMSelectionEMessage()); ?>" confirmmessage="<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')) . ' ?'; ?>" data-for="remove" href="#" title="<?php echo esc_html(__('delete', 'wp-job-portal')) ?>">
    	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/forced-delete.png" alt="<?php echo esc_html(__('delete', 'wp-job-portal')) ?>" />
    	<?php echo esc_html(__('Delete', 'wp-job-portal')) ?>
    </a>
</div>