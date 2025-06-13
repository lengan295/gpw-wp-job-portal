<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

do_action('wpjobportal_load_wp_plugin_file');
// check for plugin using plugin name
if (is_plugin_active('wp-job-portal/wp-job-portal.php')) {
	$query = "SELECT * FROM `".wpjobportal::$_db->prefix."wj_portal_config` WHERE configname = 'versioncode' OR configname = 'last_version' OR configname = 'last_step_updater'";
	$result = wpjobportal::$_db->get_results($query);
	$config = array();
	foreach($result AS $rs){
		$config[$rs->configname] = $rs->configvalue;
	}
	$config['versioncode'] = wpjobportalphplib::wpJP_str_replace('.', '', $config['versioncode']);

    if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
        $creds = request_filesystem_credentials( site_url() );
        wp_filesystem( $creds );
    }

	if(!empty($config['last_version']) && $config['last_version'] != '' && $config['last_version'] < $config['versioncode']){
		$last_version = $config['last_version'] + 1; // files execute from the next version
		$currentversion = $config['versioncode'];
		for($i = $last_version; $i <= $currentversion; $i++){
			$path = WPJOBPORTAL_PLUGIN_PATH.'includes/updater/files/'.$i.'.php';
			if($wp_filesystem->exists($path)){
				include_once($path);
			}
		}
	}
	$mainfile = WPJOBPORTAL_PLUGIN_URL.'wp-job-portal.php';
	$contents_file = wp_remote_get($mainfile);
    if (is_wp_error($contents_file)) {
    	$contents = '';
    }else{
    	$contents = $contents_file['body'];
    }
	$contents = wpjobportalphplib::wpJP_str_replace("include_once 'includes/updater/updater.php';", '', $contents);
	if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
        $creds = request_filesystem_credentials( site_url() );
        wp_filesystem( $creds );
    }
    $wp_filesystem->put_contents( $mainfile, $contents);

	function recursiveremove($dir) {
		$structure = glob(rtrim($dir, "/").'/*');
		if (is_array($structure)) {
			foreach($structure as $file) {
				if (is_dir($file)) recursiveremove($file);
				elseif (is_file($file)) wp_delete_file($file);
			}
		}
		if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }
        $wp_filesystem->rmdir($dir);
	}            	
	$dir = WPJOBPORTAL_PLUGIN_PATH.'includes/updater';
	recursiveremove($dir);

}



?>
