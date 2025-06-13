<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wpjobportalServerCalls extends WP_JOBPORTALUpdater{

	private static $server_url = 'https://wpjobportal.com/setup/index.php';

	public static function wpjobportalPluginUpdateCheck($token_arrray_json) {
		$args = array(
			'request' => 'pluginupdatecheck',
			'token' => $token_arrray_json,
			'domain' => site_url()
		);

		$url = self::$server_url . '?' . http_build_query( $args, '', '&' );
		$request = wp_remote_get($url);

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'pluginupdatecheck case returned error';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return false;
		}

		$response = wp_remote_retrieve_body( $request );
		$response = json_decode($response);

		if ( is_object( $response ) ) {
			return $response;
		} else {
			$error_message = 'pluginupdatecheck case returned data which was not correct';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return false;
		}
	}

	public static function wpjobportalPluginUpdateCheckFromCDN() {

		$url = "https://d37svg59fxemmm.cloudfront.net/addonslatestversions.txt";
		$request = wp_remote_get($url);

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'pluginupdatecheck cdn case returned error';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return false;
		}

		$response = wp_remote_retrieve_body( $request );
		$response = json_decode($response);

		if ( is_object( $response ) ) {
			return $response;
		} else {
			$error_message = 'pluginupdatecheck cdn case returned data which was not correct';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return false;
		}
	}

	public static function wpjobportalGenerateToken($transaction_key,$addon_name) {
		$args = array(
			'request' => 'generatetoken',
			'transactionkey' => $transaction_key,
			'productcode' => $addon_name,
			'domain' => site_url()
		);

		$url = self::$server_url . '?' . http_build_query( $args, '', '&' );
		$request = wp_remote_get($url);
		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'generatetoken case returned error';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return array('error'=>$error_message);
		}

		$response = wp_remote_retrieve_body( $request );
		$response = json_decode($response,true);

		if ( is_array( $response ) ) {
			return $response;
		} else {
			$error_message = 'generatetoken case returned data which was not correct';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return array('error'=>$error_message);
		}
		return false;
	}


	public static function wpjobportalGetLatestVersions() {
		$args = array(
				'request' => 'getlatestversions'
			);
		$request = wp_remote_get( 'https://wpjobportal.com/appsys/addoninfo/index.php' . '?' . http_build_query( $args, '', '&' ) );

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'getlatestversions case returned error';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return false;
		}

		$response = wp_remote_retrieve_body( $request );
		// $response = array();
		// $response['js-support-ticket-agent'] = '1.1.0';
		// $response['js-support-ticket-actions'] = '1.1.0';
		// $response['js-support-ticket-announcement'] = '1.1.0';
		// $response['js-support-ticket-feedback'] = '1.1.0';

		$response = json_decode($response,true);
		if ( is_array( $response ) ) {
			return $response;
		} else {
			$error_message = 'getlatestversions case returned data which was not correct';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return false;
		}
	}

	public static function wpjobportalPluginInformation( $args ) {
		$defaults = array(
			'request'        => 'plugininformation',
			'plugin_slug'    => '',
			'version'        => '',
			'token'    => '',
			'domain'          => site_url()
		);

		$args    = wp_parse_args( $args, $defaults );
		$request = wp_remote_get( 'https://wpjobportal.com/appsys/addoninfo/index.php' . '?' . http_build_query( $args, '', '&' ) );

		if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
			$error_message = 'plugininformation case returned data error';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return false;
		}
		$response = wp_remote_retrieve_body( $request );

		$response = json_decode($response);

		if ( is_object( $response ) ) {
			return $response;
		} else {
			$error_message = 'plugininformation case returned data which is not correct';
			WPJOBPORTALincluder::getJSModel('systemerror')->addSystemError($error_message);
			return false;
		}
	}
}
