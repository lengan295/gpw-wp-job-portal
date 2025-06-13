<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/* Update for custom plugins by joomsky */
class WP_JOBPORTALUpdater {

	private $api_key = '';
	private $addon_update_data = array();
	private $addon_update_data_errors = array();
	public $addon_installed_array = '';// it is public static bcz it is being used in extended class

	public $addon_installed_version_data = '';// it is public static bcz it is being used in extended class

	public function __construct() {
		$this->jsUpdateIntilized();

		$transaction_key_array = array();
		$addon_installed_array = array();
		foreach (wpjobportal::$_active_addons AS $addon) {
			$addon_installed_array[] = 'wp-job-portal-'.$addon;
			$option_name = 'transaction_key_for_wp-job-portal-'.$addon;
			$transaction_key = wpjobportal::$_common->getTranskey($option_name);
			if(!in_array($transaction_key, $transaction_key_array)){
				$transaction_key_array[] = $transaction_key;
			}
		}
		$this->addon_installed_array = $addon_installed_array;
		$this->api_key = wp_json_encode($transaction_key_array);
	}

	// class constructor triggers this function. sets up intail hooks and filters to be used.
	public function jsUpdateIntilized(  ) {
		add_action( 'admin_init', array( $this, 'jsAdminIntilization' ) );
		include_once( 'class-js-server-calls.php' );
	}

	// admin init hook triggers this fuction. sets up admin specific hooks and filter
	public function jsAdminIntilization() {


		add_filter( 'plugins_api', array( $this, 'jsPluginsAPI' ), 10, 3 );

		if ( current_user_can( 'update_plugins' ) ) {
			$this->jsCheckTriggers();
			add_action( 'admin_notices', array( $this, 'jsCheckUpdateNotice' ) );
			add_action( 'after_plugin_row', array( $this, 'jsKeyInput' ) );
		}
	}

	public function jsKeyInput( $file ) {
		$file_array = wpjobportalphplib::wpJP_explode('/', $file);
		$addon_slug = $file_array[0];
		if(wpjobportalphplib::wpJP_strstr($addon_slug, 'wp-job-portal-')){
			$addon_name = wpjobportalphplib::wpJP_str_replace('wp-job-portal-', '', $addon_slug);
			if(isset($this->addon_update_data[$file]) || !in_array($addon_name, wpjobportal::$_active_addons)){ // Only checking which addon have update version
				$option_name = 'transaction_key_for_wp-job-portal-'.$addon_name;
				$transaction_key = wpjobportal::$_common->getTranskey($option_name);
				if($transaction_key == '' || $transaction_key == null){
					$transaction_key = 0;
				}
				$verify_results = WPJOBPORTALincluder::getJSModel('premiumplugin')->activate( array(
		            'token'    => $transaction_key,
		            'plugin_slug'    => $addon_name
		        ) );
		        if(isset($verify_results['verfication_status']) && $verify_results['verfication_status'] == 0){
		        	$updateaddon_slug = wpjobportalphplib::wpJP_str_replace("-", " ", $addon_slug);
		        	$message = wpjobportalphplib::wpJP_strtoupper( wpjobportalphplib::wpJP_substr( $updateaddon_slug, 0, 2 ) ).wpjobportalphplib::wpJP_substr(  wpjobportalphplib::wpJP_ucwords($updateaddon_slug), 2 ) .' authentication failed. Please insert valid key for authentication.';
		        	if(isset($this->addon_update_data[$file])){
		        		$message = 'There is new version of '. wpjobportalphplib::wpJP_strtoupper( wpjobportalphplib::wpJP_substr( $updateaddon_slug, 0, 2 ) ).wpjobportalphplib::wpJP_substr(  wpjobportalphplib::wpJP_ucwords($updateaddon_slug), 2 ) .' avaible. Please insert valid activation key for updation.';
		        		remove_action('after_plugin_row_'.$file,'wp_plugin_update_row');
					}
		        	include( 'views/html-key-input.php' );
		        	echo '
					<tr>
						<td class="plugin-update plugin-update colspanchange" colspan="3">
							<div class="update-message notice inline notice-error notice-alt"><p>'. esc_html($message) .'</p></div>
						</td>
					</tr>';
		        }
			}
		}
	}

	public function jsCheckVersionUpdate( $update_data ) {
		if ( empty( $update_data->checked ) ) {
			return $update_data;
		}
		$response_version_data = get_transient('wpjobportal_addon_update_temp_data');
		$response_version_data_cdn = get_transient('wpjobportal_addon_update_temp_data_cdn');

		if(isset($_SERVER) &&  $_SERVER['REQUEST_URI'] !=''){
            if(wpjobportalphplib::wpJP_strstr( $_SERVER['REQUEST_URI'], 'plugins.php')) {
				$response_version_data = get_transient('wpjobportal_addon_update_temp_data_plugins');
				$response_version_data_cdn = get_transient('wpjobportal_addon_update_temp_data_plugins_cdn');
			}
        }
        if($response_version_data_cdn === false){
			$cdnversiondata = $this->getPluginVersionDataFromCDN();
			set_transient('wpjobportal_addon_update_temp_data_cdn', $cdnversiondata, HOUR_IN_SECONDS * 6);
			set_transient('wpjobportal_addon_update_temp_data_plugins_cdn', $cdnversiondata, 15);
		}else{
			$cdnversiondata = $response_version_data_cdn;
		}
		$newversionfound = 0;
		if ( $cdnversiondata) {
			if(is_object($cdnversiondata) ){
				foreach ($update_data->checked AS $key => $value) {
					$c_key_array = wpjobportalphplib::wpJP_explode('/', $key);
					$c_key = $c_key_array[0];
					$c_key = wpjobportalphplib::wpJP_str_replace("-","",$c_key);
					$newversion = $this->getVersionFromLiveData($cdnversiondata, $c_key);
					if($newversion){
						if(version_compare( $newversion, $value, '>' )){
							$newversionfound = 1;
						}
					}
				}
			}
		}
		if($newversionfound == 1){
			if($response_version_data === false){
				$response = $this->getPluginVersionData();
				set_transient('wpjobportal_addon_update_temp_data', $response, HOUR_IN_SECONDS * 6);
				set_transient('wpjobportal_addon_update_temp_data_plugins', $response, 15);
			}else{
				$response = $response_version_data;
			}
			if ( $response) {
				if(is_object($response) ){
					if(isset($response->addon_response_type) && $response->addon_response_type == 'no_key'){
						foreach ($update_data->checked AS $key => $value) {
							$c_key_array = wpjobportalphplib::wpJP_explode('/', $key);
							$c_key = $c_key_array[0];
							if(isset($response->addon_version_data->{$c_key})){
								if(version_compare( $response->addon_version_data->{$c_key}, $value, '>' )){
									$transient_val = get_transient('wpjobportal_addon_hide_update_notice');
									if($transient_val === false){
										set_transient('wpjobportal_addon_hide_update_notice', 1, DAY_IN_SECONDS );
									}
									$this->addon_update_data[$key] = $response->addon_version_data->{$c_key};
								}
							}
						}
					}else{// addon_response_type other than no_key
						foreach ($update_data->checked AS $key => $value) {
							$c_key_array = wpjobportalphplib::wpJP_explode('/', $key);
							$c_key = $c_key_array[0];
							if(isset($response->addon_update_data) && !empty($response->addon_update_data) && isset( $response->addon_update_data->{$c_key})){
								if(version_compare( $response->addon_update_data->{$c_key}->new_version, $value, '>' )){
									$update_data->response[ $key ] = $response->addon_update_data->{$c_key};
									$this->addon_update_data[$key] = $response->addon_update_data->{$c_key};
								}
							}elseif(isset($response->addon_version_data->{$c_key})){
								if(version_compare( $response->addon_version_data->{$c_key}, $value, '>' )){
									$transient_val = get_transient('wpjobportal_addon_hide_update_expired_key_notice');
									if($transient_val === false){
										set_transient('wpjobportal_addon_hide_update_expired_key_notice', 1, DAY_IN_SECONDS );
									}
									$this->addon_update_data_errors[$key] = $response->addon_version_data->{$c_key};
									$this->addon_update_data[$key] = $response->addon_version_data->{$c_key};
								}
							}else{ // set latest version from cdn data
								if ( $cdnversiondata) {
									if(is_object($cdnversiondata) ){
										$c_key_plain = wpjobportalphplib::wpJP_str_replace("-","",$c_key);
										$newversion = $this->getVersionFromLiveData($cdnversiondata, $c_key_plain);
										if($newversion){
											if(version_compare( $newversion, $value, '>' )){

												$option_name = 'transaction_key_for_'.$c_key;
												$transaction_key = wpjobportal::$_common->getTranskey($option_name);
												$addon_json_array = array();
												$addon_json_array[] = wpjobportalphplib::wpJP_str_replace('wp-job-portal-', '', $c_key);
												$url = 'https://wpjobportal.com/setup/index.php?token='.$transaction_key.'&productcode='. wp_json_encode($addon_json_array).'&domain='. site_url();

												// prepping data for seamless update of allowed addons
												$plugin = new stdClass();
												$plugin->id = 'w.org/plugins/wp-job-portal';
												$addon_slug = $c_key;
												$plugin->name = $addon_slug;
												$plugin->plugin = $addon_slug.'/'.$addon_slug.'.php';
												$plugin->slug = $addon_slug;
												$plugin->version = '1.0.0';
												$addonwithoutslash = wpjobportalphplib::wpJP_str_replace('-', '', $addon_slug);
												$plugin->new_version = $newversion; 
												$plugin->url = 'https://www.wpjobportal.com/';
												$plugin->download_url = $url;
												$plugin->package = $url;
												$plugin->trunk = $url;
												
												$update_data->response[ $key ] = $plugin;
												$this->addon_update_data[$key] = $plugin;
											}
										}

									}
								}
							}
						}
					}
				}
			}
		}// new version found	
		if(isset($update_data->checked)){
			$this->addon_installed_version_data = $update_data->checked;
		}
		return $update_data;
	}

	public function jsPluginsAPI( $false, $action, $args ) {
		if (!isset( $args->slug )) {
			return false;
		}

		if(wpjobportalphplib::wpJP_strstr($args->slug, 'wp-job-portal-')){
			$response = $this->jsGetPluginInfo($args->slug);
			if ($response) {
				$response->sections = json_decode(wp_json_encode($response->sections),true);
				$response->banners = json_decode(wp_json_encode($response->banners),true);
				$response->contributors = json_decode(wp_json_encode($response->contributors),true);
				return $response;
			}
		}else{
			return false;// to handle the case of plugins that need to check version data from wordpress repositry.
		}
	}

	public function jsGetPluginInfo($addon_slug) {
		$option_name = 'transaction_key_for_'.$addon_slug;
		$transaction_key = wpjobportal::$_common->getTranskey($option_name);

		if(!$transaction_key){
			die('transient');
			return false;
		}

		// $plugin_file_path = content_url().'/plugins/'.$addon_slug.'/'.$addon_slug.'.php';
		// $plugin_file_path = plugins_url($addon_slug . '/' . $addon_slug . '.php');
		$plugin_file_path = WP_PLUGIN_DIR."/".$addon_slug.'/'.$addon_slug.'.php';
		$plugin_data = get_plugin_data($plugin_file_path);

		$response = wpjobportalServerCalls::wpjobportalPluginInformation( array(
			'plugin_slug'    => $addon_slug,
			'version'        => $plugin_data['Version'],
			'token'    => $transaction_key,
			'domain'          => site_url()
		) );
		if ( isset( $response->errors ) ) {
			$this->handle_errors( $response->errors );
		}

		// If everything is okay return the $response
		if ( isset( $response ) && is_object( $response ) && $response !== false ) {
			return $response;
		}

		return false;
	}

	// does changes according to admin triggers.
	private function jsCheckTriggers() {
		$wpjobportal_addon_array_for_token = WPJOBPORTALrequest::getVar('wpjobportal_addon_array_for_token','post');
		if ( isset($wpjobportal_addon_array_for_token) && ! empty($wpjobportal_addon_array_for_token)){
			$transaction_key = '';
			$addon_name = '';
			foreach ($wpjobportal_addon_array_for_token as $key => $value) {
				$addon_transaction_key = WPJOBPORTALrequest::getVar($value.'_transaction_key');
				if(isset($addon_transaction_key) && $addon_transaction_key != ''){
					$transaction_key = sanitize_text_field($addon_transaction_key);
					$addon_name = sanitize_text_field($value);
					break;
				}
			}

			if($transaction_key != ''){
				$token = $this->wpjobportalGetTokenFromTransactionKey( $transaction_key,$addon_name);
				if($token){
					foreach ($wpjobportal_addon_array_for_token as $key => $value) {
						update_option('transaction_key_for_'.$value,$token);
					}
				}else{
					update_option( 'wpjobportal-addon-key-error-message', esc_html(__('Something went wrong','wp-job-portal')));
				}
			}
		}else{
			foreach ($this->addon_installed_array as $key) {
				$dismiss_wpjobportal_addon_update_notice = WPJOBPORTALrequest::getVar('dismiss-wpjobportal-addon-update-notice-'.$key,'get');
				if ( ! empty( $dismiss_wpjobportal_addon_update_notice ) ) {
					set_transient('dismiss-wpjobportal-addon-update-notice-'.$key, 1, DAY_IN_SECONDS );
				}
			}
		}
	}

	public function jsCheckUpdateNotice( ) {
		include_once( 'views/html-update-availble.php' );
		// if ( sizeof( $this->errors ) === 0 && ! get_option( $this->plugin_slug . '_hide_update_notice' ) ) {
		// }
	}

	public function getPluginVersionData() {
			$response = wpjobportalServerCalls::wpjobportalPluginUpdateCheck($this->api_key);
			if ( isset( $response->errors ) ) {
				$this->jsHandleErrors( $response->errors );
			}

			// Set version variables
			if ( isset( $response ) && is_object( $response ) && $response !== false ) {
				return $response;
			}
		return false;
	}

	public function getPluginVersionDataFromCDN() {
			$response = wpjobportalServerCalls::wpjobportalPluginUpdateCheckFromCDN();
			if ( isset( $response->errors ) ) {
				$this->jsHandleErrors( $response->errors );
			}

			// Set version variables
			if ( isset( $response ) && is_object( $response ) && $response !== false ) {
				return $response;
			}
		return false;
	}


	private function getVersionFromLiveData($data, $addon_name){
		foreach ($data as $key => $value) {
			if($key == $addon_name){
				return $value;
			}
		}
		return;
	}

	public function getPluginLatestVersionData() {
		$response = wpjobportalServerCalls::wpjobportalGetLatestVersions();
		// Set version variables
		if ( isset( $response ) && is_array( $response ) && $response !== false ) {
			return $response;
		}
		return false;
	}

	public function wpjobportalGetTokenFromTransactionKey($transaction_key,$addon_name) {
		$response = wpjobportalServerCalls::wpjobportalGenerateToken($transaction_key,$addon_name);
		// Set version variables
		if (is_array($response) && isset($response['verfication_status']) && $response['verfication_status'] == 1 ) {
			return $response['token'];
		}else{
			$error_message = esc_html(__('Something went wrong','wp-job-portal'));
			if(is_array($response) && isset($response['error'])){
				$error_message = $response['error'];
			}
			update_option( 'wpjobportal-addon-key-error-message', $error_message );
		}
		return false;
	}
}
?>
