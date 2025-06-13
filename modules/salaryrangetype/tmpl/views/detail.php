<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* 
*/
$upimg = 'uparrow.png';
$downimg = 'downarrow.png';
?>
 <tr id="id_<?php echo esc_attr($row->id);?>">
    <td>
        <input type="checkbox" class="wpjobportal-cb" id="wpjobportal-cb" name="wpjobportal-cb[]" value="<?php echo esc_attr($row->id); ?>" />
    </td>
    <td class="wpjobportal-order-grab-column">
        <img alt="<?php echo esc_html(__('grab','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/list-full.png'?>"/>
     </td>
    <td class="wpjobportal-text-left">
        <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_salaryrangetype&wpjobportallt=formsalaryrangetype&wpjobportalid='.esc_attr($row->id))); ?>" title="<?php echo esc_html(__('title','wp-job-portal')); ?>">
            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($row->title)); ?>
        </a>
    </td>
    <td>
        <?php if ($row->isdefault == 1) { ?> 
            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/default.png" alt="Default" border="0" alt="<?php echo esc_html(__('default', 'wp-job-portal')); ?>" />
        <?php } else { ?>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_common&task=makedefault&action=wpjobportaltask&for=salaryrangetypes&id='.$row->id.$pageid),'wpjobportal_common_entity_nonce')); ?>" title="<?php echo esc_html(__('not default', 'wp-job-portal')); ?>">
                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/notdefault.png" border="0" alt="<?php echo esc_html(__('not default', 'wp-job-portal')); ?>" />
            </a>
        <?php } ?>  
    </td>   
    <td>
        <?php if ($row->status == 1) { ?>
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_salaryrangetype&task=unpublish&action=wpjobportaltask&wpjobportal-cb[]='.$row->id.$pageid),'wpjobportal_salary_range_type_nonce')); ?>" title="<?php echo esc_html(__('published', 'wp-job-portal')); ?>">
            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/good.png" border="0" alt="<?php echo esc_html(__('published', 'wp-job-portal')); ?>" />
        </a>
       <?php } else { ?>
        <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_salaryrangetype&task=publish&action=wpjobportaltask&wpjobportal-cb[]='.$row->id.$pageid),'wpjobportal_salary_range_type_nonce')); ?>" title="<?php echo esc_html(__('not published', 'wp-job-portal')); ?>">
            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/close.png" border="0" alt="<?php echo esc_html(__('not published', 'wp-job-portal')); ?>" />
        </a>
        <?php } ?>
    </td>
    <td>
        <a class="wpjobportal-table-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_salaryrangetype&wpjobportallt=formsalaryrangetype&wpjobportalid='.$row->id)); ?>" title="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/edit.png" alt="<?php echo esc_html(__('edit', 'wp-job-portal')); ?>">
        </a>
        <a class="wpjobportal-table-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_salaryrangetype&task=remove&action=wpjobportaltask&wpjobportal-cb[]='.$row->id),'wpjobportal_salary_range_type_nonce')); ?>" onclick='return confirmdelete("<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')) .' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/delete.png" alt="<?php echo esc_html(__('delete', 'wp-job-portal')); ?>">
        </a>
    </td>
</tr>
