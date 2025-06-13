<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALupdates {

    static function checkUpdates($cversion=null) {
        if (is_null($cversion)) {
            $cversion = wpjobportal::$_currentversion;
        }
        if ( ! function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ) {
            $creds = request_filesystem_credentials( site_url() );
            wp_filesystem( $creds );
        }

        $installedversion = WPJOBPORTALupdates::getInstalledVersion();
        if ($installedversion != $cversion) {
            $query = "REPLACE INTO `".wpjobportal::$_db->prefix."wj_portal_config` (`configname`, `configvalue`, `configfor`) VALUES ('last_version','','default');";
            wpjobportal::$_db->query($query); //old actual
            /*wpjobportal::$_db->show_errors(false);
            @wpjobportal::$_db->query($query);          */
            $query = "SELECT configvalue FROM `".wpjobportal::$_db->prefix."wj_portal_config` WHERE configname='versioncode'";
            $versioncode = wpjobportal::$_db->get_var($query);
            $versioncode = wpjobportalphplib::wpJP_str_replace('.','',$versioncode);
            $query = "UPDATE `".wpjobportal::$_db->prefix."wj_portal_config` SET configvalue = '".esc_sql($versioncode)."' WHERE configname = 'last_version';";
            wpjobportal::$_db->query($query);
            $from = $installedversion + 1;
            $to = $cversion;
            if ($from != "" && $to != "") {
                for ($i = $from; $i <= $to; $i++) {
                    $installfile = WPJOBPORTAL_PLUGIN_PATH . 'includes/updates/sql/' . $i . '.sql';

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
                                if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                                    $query_string = trim(implode('', $query));
                                    $query_string = wpjobportalphplib::wpJP_str_replace("#__", wpjobportal::$_db->prefix, $query_string);
                                    if (!empty($query_string)) {
                                        wpjobportal::$_db->query($query_string);
                                    }
                                    $query = array();
                                }
                            }
                        } else {
                            // echo 'Failed to open file.';
                        }
                    } else {
                        // echo 'File does not exist.';
                    }
                }
            }

        }
    }

    static function getInstalledVersion() {
        $query = "SELECT configvalue FROM `" . wpjobportal::$_db->prefix . "wj_portal_config` WHERE configname = 'versioncode'";
        $version = wpjobportal::$_db->get_var($query);
        if (!$version)
            $version = '100';
        else
            $version = wpjobportalphplib::wpJP_str_replace('.', '', $version);
        return $version;
    }

}

?>
