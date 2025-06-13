<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param js-job Controll
*/
?>
<?php
$html = '';
switch ($layout) {
	case 'usercontrol':
		$user_group = WPJOBPORTALincluder::getJSModel('user')->getWPRoleNameById($user->wpuid);
		?>
		<div class="wpjobportal-user-action-wrp">
		    <a class="wpjobportal-user-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=enforcedeleteuser&wpjobportalid='.esc_attr($user->id)),'wpjobportal_user_nonce')); ?>" onclick='return confirm("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).'?'; ?>");' title="<?php echo esc_html(__('enforce delete', 'wp-job-portal')) ?>">
		    	<?php echo esc_html(__('Enforce Delete', 'wp-job-portal')) ?>
		    </a>
		    <a class="wpjobportal-user-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=deleteuser&wpjobportalid='.esc_attr($user->id)),'wpjobportal_user_nonce')); ?>" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')) ?>">
		    	<?php echo esc_html(__('Delete', 'wp-job-portal')) ?>
		    </a>
		    <a class="wpjobportal-user-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=changeuserstatus&wpjobportalid='.esc_attr($user->id)),'wpjobportal_user_nonce')); ?>" title="<?php echo ($user->status == 1) ? esc_html(__('Disable', 'wp-job-portal')) : esc_html(__('Enable', 'wp-job-portal')); ?>">
		    	<?php echo ($user->status == 1) ? esc_html(__('Disable', 'wp-job-portal')) : esc_html(__('Enable', 'wp-job-portal')); ?>
		    </a>
		    <?php
            // hide the button if user is admin
            if($user_group != 'administrator'){
            ?>
			    <a class="wpjobportal-user-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_user&wpjobportallt=changerole&wpjobportalid='.esc_attr($user->id))); ?>" title="<?php echo esc_html(__('change role', 'wp-job-portal')) ?>">
			    	<?php echo esc_html(__('Change Role', 'wp-job-portal')) ?>
			    </a>
			<?php }?>
		    <a class="wpjobportal-user-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_user&wpjobportallt=userdetail&id='.esc_attr($user->id))); ?>" title="<?php echo esc_html(__('change role', 'wp-job-portal')) ?>">
		    	<?php echo esc_html(__('Details', 'wp-job-portal')) ?>
		    </a>
		</div>

<?php
		break;
	case 'userdetailcontrol':
		$user_group = WPJOBPORTALincluder::getJSModel('user')->getWPRoleNameById($user->uid);
		?>
		<div class="wpjobportal-user-action-wrp">
            <a class="wpjobportal-user-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=enforcedeleteuser&wpjobportalid='.esc_attr($user->id)),'wpjobportal_user_nonce')); ?>" onclick='return confirm("<?php echo esc_html(__('This will delete every thing about this record','wp-job-portal')).'. '.esc_html(__('Are you sure to delete','wp-job-portal')).'?'; ?>");' title="<?php echo esc_html(__('enforce delete', 'wp-job-portal')) ?>">
            	<?php echo esc_html(__('Enforce Delete', 'wp-job-portal')) ?>
            </a>
            <a class="wpjobportal-user-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=deleteuser&wpjobportalid='.esc_attr($user->id)),'wpjobportal_user_nonce')); ?>" onclick='return confirm("<?php echo esc_html(__('Are you sure to delete', 'wp-job-portal')).' ?'; ?>");' title="<?php echo esc_html(__('delete', 'wp-job-portal')) ?>">
            	<?php echo esc_html(__('Delete', 'wp-job-portal')) ?>
            </a>
            <a class="wpjobportal-user-act-btn" href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_user&action=wpjobportaltask&task=changeuserstatus&wpjobportalid='.esc_attr($user->id)),'wpjobportal_user_nonce')); ?>&detail=1" title="<?php echo ($user->status == 1) ? esc_html(__('Disable', 'wp-job-portal')) : esc_html(__('Enable', 'wp-job-portal')); ?>">
            	<?php echo ($user->status == 1) ? esc_html(__('Disable', 'wp-job-portal')) : esc_html(__('Enable', 'wp-job-portal')); ?>
            </a>
            <?php
            // hide the button if user is admin
            if($user_group != 'administrator'){
            ?>
	            <a class="wpjobportal-user-act-btn" href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_user&wpjobportallt=changerole&wpjobportalid='.esc_attr($user->id))); ?>" title="<?php echo esc_html(__('change role', 'wp-job-portal')) ?>">
	            	<?php echo esc_html(__('Change Role', 'wp-job-portal')) ?>
	            </a>
	        <?php } ?>
        </div>

		<?php
		break;
	
	default:
		# code...
		break;
}
?>
