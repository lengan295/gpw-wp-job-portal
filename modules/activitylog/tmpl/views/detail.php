<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal Optional
* ==>Detail
*/
?>
<tr>
	<td>
		<?php echo esc_html($data->id); ?>
	</td>
	<td class="wpjobportal-text-left">
		<?php echo esc_html($data->first_name) . ' ' . esc_html($data->last_name); ?>
	</td>
	<td class="wpjobportal-text-left">
		<?php echo wp_kses($data->description, WPJOBPORTAL_ALLOWED_TAGS); ?>
	</td>
	<td>
		<?php echo esc_html(wpjobportalphplib::wpJP_ucwords($data->referencefor)); ?>
	</td>
	<td>
		<?php echo esc_html($data->created); ?>
	</td>
</tr>
