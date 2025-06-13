<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php

class WPJOBPORTALpremiumpluginController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = "premiumplugin";
        if ($this->canAddLayout()) {
            $layout = WPJOBPORTALrequest::getLayout('wpjobportallt', null, 'step1');
            switch ($layout) {
                case 'admin_step1':
                    wpjobportal::$_data['versioncode'] = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode');
                    wpjobportal::$_data['productcode'] = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('productcode');
                    wpjobportal::$_data['producttype'] = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
                break;
                case 'admin_addonfeatures';// to avoid default case
                    break;
                case 'admin_step2':
                    break;
                case 'admin_step3':
                    break;
                default:
                    exit();    
            }
            $module =  'premiumplugin';
            wpjobportal::$_data['sanitized_args']['wpjobportal_nonce'] = esc_html(wp_create_nonce('wpjobportal_nonce'));
            WPJOBPORTALincluder::include_file($layout, $module);
        }
    }

    function canAddLayout() {
        $nonce_value = WPJOBPORTALrequest::getVar('wpjobportal_nonce');
        if ( wp_verify_nonce( $nonce_value, 'wpjobportal_nonce') ) {
            if (isset($_POST['form_request']) && $_POST['form_request'] == 'wpjobportal')
                return false;
            elseif (isset($_GET['action']) && $_GET['action'] == 'wpjobportaltask')
                return false;
            else
                return true;
        }
    }

    function verifytransactionkey(){

        $post_data['transactionkey'] = WPJOBPORTALrequest::getVar('transactionkey','','');
        if($post_data['transactionkey'] != ''){


            $post_data['domain'] = site_url();
            $post_data['step'] = 'one';
            $post_data['myown'] = 1;

            $url = 'https://wpjobportal.com/setup/index.php';

            $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
            if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                $result = $response['body'];
                $result = json_decode($result,true);

            }else{
                $result = false;
                if(!is_wp_error($response)){
                   $error = $response['response']['message'];
               }else{
                    $error = $response->get_error_message();
               }
            }

            if(is_array($result) && isset($result['status']) && $result['status'] == 1 ){ // means everthing ok
                $installdata = $result;
                $installdata['actual_transaction_key'] = $post_data['transactionkey'];
                $result['actual_transaction_key'] = $post_data['transactionkey'];
                // in case of session not working
                add_option('wpjobportal_addon_install_data',wp_json_encode($result));
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step2"));
                wp_redirect($url);
                return;
            }else{
                if(isset($result[0]) && $result[0] == 0){
                    $error = $result[1];
                }elseif(isset($result['error']) && $result['error'] != ''){
                    $error = $result['error'];
                }
            }
        }else{
            $error = esc_html(__('Please insert activation key to proceed','wp-job-portal')).'!';
        }
        $wpjobportal_addon_return_data = array();
        $wpjobportal_addon_return_data['status'] = 0;
        $wpjobportal_addon_return_data['message'] = $error;
        $wpjobportal_addon_return_data['transactionkey'] = $post_data['transactionkey'];
        update_option( 'wpjobportal_addon_return_data', wp_json_encode($wpjobportal_addon_return_data) );
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step1"));
        wp_redirect($url);
        return;
    }

    function downloadandinstalladdons(){
        $nonce = WPJOBPORTALrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'wpjobportal_premiumplugin_nonce') ) {
             die( 'Security check Failed' );
        }
        $post_data = WPJOBPORTALrequest::get('post');

        $addons_array = $post_data;
        if(isset($addons_array['token'])){
            unset($addons_array['token']);
        }
        $addon_json_array = array();

        foreach ($addons_array as $key => $value) {
            $addon_json_array[] = wpjobportalphplib::wpJP_str_replace('wp-job-portal-', '', $key);
        }

        $token = $post_data['token'];
        if($token == ''){
            $wpjobportal_addon_return_data = array();
            $wpjobportal_addon_return_data['status'] = 0;
            $wpjobportal_addon_return_data['message'] = esc_html(__('Addon Installation Failed','wp-job-portal')).'!';
            $wpjobportal_addon_return_data['transactionkey'] = '';
            update_option( 'wpjobportal_addon_return_data', wp_json_encode($wpjobportal_addon_return_data) );
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step1"));
            wp_redirect($url);
            exit;
        }
        $site_url = site_url();
        $site_url = wpjobportalphplib::wpJP_str_replace("https://","",$site_url);
        $site_url = wpjobportalphplib::wpJP_str_replace("http://","",$site_url);
        $url = 'https://wpjobportal.com/setup/index.php?token='.$token.'&productcode='. wp_json_encode($addon_json_array).'&domain='. $site_url;

        $install_count = 0;

        $installed = $this->install_plugin($url);
        if ( !is_wp_error( $installed ) && $installed ) {
            // had to run two seprate loops to save token for all the addons even if some error is triggered by activation.
            foreach ($post_data as $key => $value) {
                if(wpjobportalphplib::wpJP_strstr($key, 'wp-job-portal-')){
                    update_option('transaction_key_for_'.$key,$token);
                }
            }

            foreach ($post_data as $key => $value) {
                if(wpjobportalphplib::wpJP_strstr($key, 'wp-job-portal-')){
                    $activate = activate_plugin( $key.'/'.$key.'.php' );
                    $install_count++;
                }
            }

        }else{
            $wpjobportal_addon_return_data = array();
            $wpjobportal_addon_return_data['status'] = 0;
            $wpjobportal_addon_return_data['message'] = esc_html(__('Addon Installation Failed','wp-job-portal')).'!';
            $wpjobportal_addon_return_data['transactionkey'] = '';
            update_option( 'wpjobportal_addon_return_data', wp_json_encode($wpjobportal_addon_return_data) );
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step1"));
            wp_redirect($url);
            exit;
        }
        $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step3"));
        wp_redirect($url);
    }

    function install_plugin( $plugin_zip ) {// is only called from a same controler function
        do_action('wpjobportal_load_wp_admin_file');
        WP_Filesystem();
        $tmpfile = download_url( $plugin_zip);

        if ( !is_wp_error( $tmpfile ) && $tmpfile ) {
            $plugin_path = WP_CONTENT_DIR;
            $plugin_path = $plugin_path.'/plugins/';
            $path =WPJOBPORTAL_PLUGIN_PATH.'addon.zip';

            copy( $tmpfile, $path );


            $unzipfile = unzip_file( $path, $plugin_path);
            @wp_delete_file( $path ); // must wp_delete_file afterwards
            @wp_delete_file( $tmpfile ); // must wp_delete_file afterwards

            if ( is_wp_error( $unzipfile ) ) {
                $wpjobportal_addon_return_data = array();
                $wpjobportal_addon_return_data['status'] = 0;
                $wpjobportal_addon_return_data['message'] = esc_html(__('Addon installation failed, Directory permission error','wp-job-portal')).'!';
                $wpjobportal_addon_return_data['transactionkey'] = '';
                update_option( 'wpjobportal_addon_return_data', wp_json_encode($wpjobportal_addon_return_data) );
                $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step1"));
                wp_redirect($url);
                exit;
            } else {
                return true;
            }
        }else{
            $wpjobportal_addon_return_data = array();
            $wpjobportal_addon_return_data['status'] = 0;
            $wpjobportal_addon_return_data['message'] = esc_html(__('Addon Installation Failed, File download error','wp-job-portal')).'!';
            $wpjobportal_addon_return_data['transactionkey'] = '';
            update_option( 'wpjobportal_addon_return_data', wp_json_encode($wpjobportal_addon_return_data) );
            $url = esc_url_raw(admin_url("admin.php?page=wpjobportal_premiumplugin&wpjobportallt=step1"));
            wp_redirect($url);
            exit;
        }
    }
}
$WPJOBPORTALpremiumpluginController = new WPJOBPORTALpremiumpluginController();
?>
