<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
if(!empty($this->addon_installed_array)){
	$new_transient_flag = 0;
	//delete_transient('wpjobportal_addon_update_flag');
	$response = get_transient('wpjobportal_addon_update_flag');
	if(!$response){
		$response = $this->getPluginLatestVersionData();
		set_transient('wpjobportal_addon_update_flag',$response,HOUR_IN_SECONDS * 6);
		$new_transient_flag = 1;
	}
	if(!empty($response)){
		foreach ($this->addon_installed_array as $addon) {
			if(!isset($response[$addon])){
				continue;
			}
			$plugin_file_path = WP_PLUGIN_DIR."/".$addon.'/'.$addon.'.php';

			// $plugin_file_path = plugins_url($addon . '/' . $addon . '.php');
			// $plugin_file_path = content_url().'/plugins/'.$addon.'/'.$addon.'.php';

			$plugin_data = get_plugin_data($plugin_file_path);
			$transient_val = get_transient('dismiss-wpjobportal-addon-update-notice-'.$addon);
			if($new_transient_flag == 1){
				delete_transient('dismiss-wpjobportal-addon-update-notice-'.$addon);
			}
			if(!$transient_val){
				if (version_compare( $response[$addon], $plugin_data['Version'], '>' ) ) { ?>
					<div class="updated">
						<p class="wpjm-updater-dismiss" style="float:right;"><a href="<?php echo esc_url( add_query_arg( 'dismiss-wpjobportal-addon-update-notice-' . sanitize_title( $addon ), '1' ) ); ?>"><?php __('Hide notice', 'wp-job-portal'); ?></a></p>
						<p><?php printf( '<a href="%s">New Version is avaible</a> for "%s".', esc_url_raw(admin_url('plugins.php')), esc_html( $plugin_data['Name'] ) ); ?></p>
					</div>
				<?php }
			}
		}
	}

}

if(get_option( 'wpjobportal-addon-key-error-message', '' ) != ''){
	echo '<div class="notice notice-error is-dismissible"><p>'. esc_html(get_option( 'wpjobportal-addon-key-error-message')) .'</p></div>';
	delete_option( 'wpjobportal-addon-key-error-message' );
}
?>
