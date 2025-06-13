<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALrequest {
    /*
     * Check Request from both the Get and post method
     */

    static function getVar($variable_name, $method = null, $defaultvalue = null, $typecast = null,$editor_data=null) {
        $value = null;
        if ($method == null) {
            if (isset($_GET[$variable_name])) {
                if(is_array($_GET[$variable_name])){
                    $value = filter_var_array($_GET[$variable_name]);
                }else{
                    $value = wpjobportal::wpjobportal_sanitizeData($_GET[$variable_name]);
                }
            } elseif (isset($_POST[$variable_name])) {
                if(is_array($_POST[$variable_name])){
                    $value = filter_var_array($_POST[$variable_name]);
                }else{
                    if($editor_data == null){
                        $value = wpjobportal::wpjobportal_sanitizeData($_POST[$variable_name]);
                    }else{
                        $value = filter_var($_POST[$variable_name],FILTER_DEFAULT);
                    }
                }
            } elseif (get_query_var($variable_name)) {
                $value = get_query_var($variable_name);
            } elseif (isset(wpjobportal::$_data['sanitized_args'][$variable_name]) && wpjobportal::$_data['sanitized_args'][$variable_name] != '') {
                $value = wpjobportal::$_data['sanitized_args'][$variable_name];
            }
        } else {
            $method = wpjobportalphplib::wpJP_strtolower($method);
            switch ($method) {
                case 'post':
                    if (isset($_POST[$variable_name]))
                        if (is_array($_POST[$variable_name])) {
                            $value = filter_var_array($_POST[$variable_name]);
                        }else{
                            if($editor_data == null){
                                $value = wpjobportal::wpjobportal_sanitizeData($_POST[$variable_name]);
                            }else{
                                $value = filter_var($_POST[$variable_name],FILTER_DEFAULT);
                            }
                        }
                    break;
                case 'get':
                    if (isset($_GET[$variable_name]))
                        if (is_array($_GET[$variable_name])) {
                            $value = filter_var_array($_GET[$variable_name]);
                        }else{
                            $value = wpjobportal::wpjobportal_sanitizeData($_GET[$variable_name]);
                        }
                    break;
                case 'shortcode_option': // new case to handle shortcode attributes (since many variables have already used names so cant use above methods)
                    if (isset(wpjobportal::$_data['shortcode_options'][$variable_name]) && wpjobportal::$_data['shortcode_options'][$variable_name] != '') {
                        $value = wpjobportal::$_data['shortcode_options'][$variable_name];
                    }
                    break;
            }
        }
        if ($typecast != null) {
            $typecast = wpjobportalphplib::wpJP_strtolower($typecast);
            switch ($typecast) {
                case "int":
                    $value = (int) $value;
                    break;
                case "string":
                    $value = (string) $value;
                    break;
            }
        }
        if ($value == null)
            $value = $defaultvalue;
        //echo print_r($value); exit;
        return $value;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function get($method = null) {
        $array = null;
        if ($method != null) {
            $method = wpjobportalphplib::wpJP_strtolower($method);
            switch ($method) {
                case 'post':
                    $array = filter_var_array($_POST);
                    break;
                case 'get':
                    $array = filter_var_array($_GET);
                    break;
            }
        }
        return $array;
    }

    /*
     * Check Request from both the Get and post method
     */

    static function getLayout($layout, $method, $defaultvalue) {
        $layoutname = null;
        if ($method != null) {
            $method = wpjobportalphplib::wpJP_strtolower($method);
            switch ($method) {
                case 'post':
                    $layoutname = wpjobportal::wpjobportal_sanitizeData($_POST[$layout]);
                    break;
                case 'get':
                    $layoutname = wpjobportal::wpjobportal_sanitizeData($_GET[$layout]);
                    break;
            }
        } else {
            if (isset($_POST[$layout]))
                $layoutname = wpjobportal::wpjobportal_sanitizeData($_POST[$layout]);
            elseif (isset($_GET[$layout]))
                $layoutname = wpjobportal::wpjobportal_sanitizeData($_GET[$layout]);
            elseif (get_query_var($layout))
                $layoutname = get_query_var($layout);
            elseif (isset(wpjobportal::$_data['sanitized_args'][$layout]) && wpjobportal::$_data['sanitized_args'][$layout] != '')
                $layoutname = wpjobportal::$_data['sanitized_args'][$layout];
        }
        if ($layoutname == null) {
            $layoutname = $defaultvalue;
        }

        $is_elementor_edit_mode = false;

        if ( class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->editor ) {
            $is_elementor_edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
        }

        if (is_admin() && $is_elementor_edit_mode == false) {
        //if (is_admin() && !wp_is_json_request() && !wp_doing_ajax()) {
            $layoutname = 'admin_' . $layoutname;
        }
        return $layoutname;
    }

}

?>
