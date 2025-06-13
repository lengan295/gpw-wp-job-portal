<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param js-job Optional
*/
?>
<?php
$approved = ($user->status == 1) ? '<span class="text-green">' . esc_html(__('Approved', 'wp-job-portal')) . '</span>' : '<span class="text-red">' . esc_html(__('Rejected', 'wp-job-portal')) . '</span>';
?>
<div id="user_<?php echo esc_attr($user->id); ?>" class="wpjobportal-user-list">
    <div id="item-data">
        <span id="selector_<?php echo esc_attr($user->id); ?>" class="selector">
            <input type="checkbox" onclick="javascript:highlight(<?php echo esc_js($user->id); ?>);" class="wpjobportal-cb" id="wpjobportal-cb" name="wpjobportal-cb[]" value="<?php echo esc_attr($user->id); ?>" />
        </span>
		<?php
			wpjobportalincluder::getTemplate('user/views/admin/detail',array('user' => $user));
		?>
	</div>
</div>