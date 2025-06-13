<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALpremiumpluginModel {

    private static $server_url = 'https://wpjobportal.com/setup/index.php';

    function verfifyAddonActivation($addon_name){
        $option_name = 'transaction_key_for_wp-job-portal-'.$addon_name;
        $transaction_key = wpjobportal::$_common->getTranskey($option_name);
        try {
            if (! $transaction_key ) {
                throw new Exception( 'License key not found' );
            }
            if ( empty( $transaction_key ) ) {
                throw new Exception( 'License key not found' );
            }
            $activate_results = $this->activate( array(
                'token'    => $transaction_key,
                'plugin_slug'    => $addon_name
            ) );
            if ( false === $activate_results ) {
                throw new Exception( 'Connection failed to the server' );
            } elseif ( isset( $activate_results['error_code'] ) ) {
                throw new Exception( $activate_results['error'] );
            } elseif(isset($activate_results['verfication_status']) && $activate_results['verfication_status'] == 1 ){
                return true;
            }
            throw new Exception( 'License could not activate. Please contact support.' );
        } catch ( Exception $e ) {
            echo '<div class="notice notice-error is-dismissible">
                    <p>'.esc_html($e->getMessage()).'.</p>
                </div>';
            return false;
        }
    }

    function logAddonDeactivation($addon_name){
        $option_name = 'transaction_key_for_wp-job-portal-'.$addon_name;
        $transaction_key = wpjobportal::$_common->getTranskey($option_name);

        $activate_results = $this->deactivate( array(
            'token'    => $transaction_key,
            'plugin_slug'    => $addon_name
        ) );
    }

    function logAddonDeletion($addon_name){
        $option_name = 'transaction_key_for_wp-job-portal-'.$addon_name;
        $transaction_key = wpjobportal::$_common->getTranskey($option_name);
        $activate_results = $this->delete( array(
            'token'    => $transaction_key,
            'plugin_slug'    => $addon_name
        ) );
    }

    public static function activate( $args ) {
        $site_url = site_url();
        $site_url = wpjobportalphplib::wpJP_str_replace("http://","",$site_url);
        $site_url = wpjobportalphplib::wpJP_str_replace("https://","",$site_url);

        $defaults = array(
            'request'  => 'activate',
            'domain' => $site_url,
            'activation_call' => 1
        );

        $args    = wp_parse_args( $defaults, $args );

        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $args, '', '&' ) );
        if ( is_wp_error( $request ) ) {
            return wp_json_encode( array( 'error_code' => $request->get_error_code(), 'error' => $request->get_error_message() ) );
        }

        if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
            return wp_json_encode( array( 'error_code' => wp_remote_retrieve_response_code( $request ), 'error' => 'Error code: ' . wp_remote_retrieve_response_code( $request ) ) );
        }
        $response =  wp_remote_retrieve_body( $request );
        $response = json_decode($response,true);
        return $response;
    }

    /**
     * Attempt t deactivate a license
     */
    public static function deactivate( $dargs ) {
        $site_url = site_url();
        $site_url = wpjobportalphplib::wpJP_str_replace("http://","",$site_url);
        $site_url = wpjobportalphplib::wpJP_str_replace("https://","",$site_url);

        $defaults = array(
            'request'  => 'deactivate',
            'domain' => $site_url
        );

        $args    = wp_parse_args( $defaults, $dargs );
        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $args, '', '&' ) );
        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
            return false;
        } else {
            return wp_remote_retrieve_body( $request );
        }
    }
    /**
     * Attempt t deactivate a license
     */
    public static function delete( $args ) {
        $site_url = site_url();
        $site_url = wpjobportalphplib::wpJP_str_replace("http://","",$site_url);
        $site_url = wpjobportalphplib::wpJP_str_replace("https://","",$site_url);

        $defaults = array(
            'request'  => 'delete',
            'domain' => $site_url,
        );

        $args    = wp_parse_args( $defaults, $args );
        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $args, '', '&' ) );
        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
            return false;
        } else {
            return;
        }
    }

    function verifyAddonSqlFile($addon_name,$addon_version){
        $option_name = 'transaction_key_for_wp-job-portal-'.$addon_name;
        $transaction_key = wpjobportal::$_common->getTranskey($option_name);
        // $addonversion = wpjobportalphplib::wpJP_str_replace('.', '', $addon_version);
        $site_url = site_url();
        $site_url = wpjobportalphplib::wpJP_str_replace("http://","",$site_url);
        $site_url = wpjobportalphplib::wpJP_str_replace("https://","",$site_url);

        $network_site_url = network_site_url();
        $network_site_url = wpjobportalphplib::wpJP_str_replace("http://","",$network_site_url);
        $network_site_url = wpjobportalphplib::wpJP_str_replace("https://","",$network_site_url);

        $defaults = array(
            'request'  => 'getactivatesql',
            'domain' => $network_site_url,
            'subsite' => $site_url,
            'activation_call' => 1,
            'plugin_slug' => $addon_name,
            'addonversion' => $addon_version,
            'token' => $transaction_key
        );
        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $defaults, '', '&' ) );
        if ( is_wp_error( $request ) ) {
            return wp_json_encode( array( 'error_code' => $request->get_error_code(), 'error' => $request->get_error_message() ) );
        }

        if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
            return wp_json_encode( array( 'error_code' => wp_remote_retrieve_response_code( $request ), 'error' => 'Error code: ' . wp_remote_retrieve_response_code( $request ) ) );
        }

        $response =  wp_remote_retrieve_body( $request );
        return $response;
    }

    function getAddonSqlForUpdation($plugin_slug,$installed_version,$new_version){
        $option_name = 'transaction_key_for_wp-job-portal-'.$plugin_slug;
        $transaction_key = wpjobportal::$_common->getTranskey($option_name);
        $site_url = site_url();
        $site_url = wpjobportalphplib::wpJP_str_replace("http://","",$site_url);
        $site_url = wpjobportalphplib::wpJP_str_replace("https://","",$site_url);

        $network_site_url = network_site_url();
        $network_site_url = wpjobportalphplib::wpJP_str_replace("http://","",$network_site_url);
        $network_site_url = wpjobportalphplib::wpJP_str_replace("https://","",$network_site_url);

        $defaults = array(
            'request'  => 'getupdatesql',
            'domain' => $network_site_url,
            'subsite' => $site_url,
            'activation_call' => 1,
            'plugin_slug' => $plugin_slug,
            'installedversion' => $installed_version,
            'newversion' => $new_version,
            'token' => $transaction_key
        );

        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $defaults, '', '&' ) );
        if ( is_wp_error( $request ) ) {
            return wp_json_encode( array( 'error_code' => $request->get_error_code(), 'error' => $request->get_error_message() ) );
        }

        if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
            return wp_json_encode( array( 'error_code' => wp_remote_retrieve_response_code( $request ), 'error' => 'Error code: ' . wp_remote_retrieve_response_code( $request ) ) );
        }

        $response =  wp_remote_retrieve_body( $request );
        return $response;
    }

    function getAddonUpdateSqlFromUpdateDir($installedversion,$newversion,$directory){
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        if($installedversion != "" && $newversion != ""){
            for ($i = ($installedversion + 1); $i <= $newversion; $i++) {
                $installfile = $directory . '/' . $i . '.sql';

                // Check if the file exists
                if ($wp_filesystem->exists($installfile)) {
                    $delimiter = ';';
                    // Get the file contents
                    $file_contents = $wp_filesystem->get_contents($installfile);
                    if ($file_contents !== false) {
                        $lines = explode("\n", $file_contents);
                        $query = array();

                        foreach ($lines as $line) {
                            $query[] = $line;
                            if (wpjobportalphplib::wpJP_preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                                $query_string = wpjobportalphplib::wpJP_trim(implode('', $query));
                                $query_string = wpjobportalphplib::wpJP_str_replace("#__", wpjobportal::$_db->prefix, $query_string);
                                if (!empty($query_string)) {
                                    wpjobportal::$_db->query($query_string);
                                }
                                $query = array();
                            }
                        }
                    } else {
                        //echo 'Failed to open file.';
                    }
                }
            }
        }
    }

    function getAddonUpdateSqlFromLive($installedversion,$newversion,$plugin_slug){
        if($installedversion != "" && $newversion != "" && $plugin_slug != ""){
            $addonsql = $this->getAddonSqlForUpdation($plugin_slug,$installedversion,$newversion);
            $decodedata = json_decode($addonsql,true);
            $delimiter = ';';
			if(isset($decodedata['update_sql'])) $update_sql = $decodedata['update_sql']; else $update_sql = "";
            if(isset($decodedata['verfication_status']) && $update_sql != ""){
                $lines = wpjobportalphplib::wpJP_explode(PHP_EOL, $addonsql);
                if(!empty($lines)){
                    foreach($lines as $line){
                        $query[] = $line;
                        if (wpjobportalphplib::wpJP_preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                            $query = wpjobportalphplib::wpJP_trim(implode('', $query));
                            $query = wpjobportalphplib::wpJP_str_replace("#__", wpjobportal::$_db->prefix, $query);
                            if (!empty($query)) {
                                wpjobportal::$_db->query($query);
                            }
                        }
                        if (is_string($query) === true) {
                            $query = array();
                        }
                    }
                }
            }
        }
    }

    // get country cities sql from live
    function getAddressSqlFile($addon_name,$addon_version,$countrycode){
        $option_name = 'transaction_key_for_wp-job-portal-'.$addon_name;
        $transaction_key = wpjobportal::$_common->getTranskey($option_name);
        // $addonversion = wpjobportalphplib::wpJP_str_replace('.', '', $addon_version);
        $site_url = site_url();
        $site_url = wpjobportalphplib::wpJP_str_replace("http://","",$site_url);
        $site_url = wpjobportalphplib::wpJP_str_replace("https://","",$site_url);

        $network_site_url = network_site_url();
        $network_site_url = wpjobportalphplib::wpJP_str_replace("http://","",$network_site_url);
        $network_site_url = wpjobportalphplib::wpJP_str_replace("https://","",$network_site_url);

        $defaults = array(
            'request'  => 'getcitiessql',
            'domain' => $network_site_url,
            'subsite' => $site_url,
            'activation_call' => 1,
            'plugin_slug' => $addon_name,
            'addonversion' => $addon_version,
            'countrycode' => $countrycode,
            'token' => $transaction_key
        );

        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $defaults, '', '&' ) );
        if ( is_wp_error( $request ) ) {
            return wp_json_encode( array( 'error_code' => $request->get_error_code(), 'error' => $request->get_error_message() ) );
        }

        if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
            return wp_json_encode( array( 'error_code' => wp_remote_retrieve_response_code( $request ), 'error' => 'Error code: ' . wp_remote_retrieve_response_code( $request ) ) );
        }

        $response =  wp_remote_retrieve_body( $request );
        return $response;
    }

}

?>
