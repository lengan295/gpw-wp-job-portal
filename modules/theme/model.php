<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALthemeModel {
    function getCurrentTheme() {


        $color1 = "#3baeda";
        $color2 = "#333333";
        $color3 = "#575757";

        $color_string_values = get_option("wpjp_set_theme_colors");
        if($color_string_values != ''){
            $json_values = json_decode($color_string_values,true);
            if(is_array($json_values) && !empty($json_values)){
                $color1 = $json_values['color1'];
                $color2 = $json_values['color2'];
                $color3 = $json_values['color3'];
            }
        }

        $theme['color1'] = esc_attr($color1);
        $theme['color2'] = esc_attr($color2);
        $theme['color3'] = esc_attr($color3);
        wpjobportal::$_data[0] = $theme;
        return $theme;
    }

    function storeTheme($data) {

        if (empty($data))
            return false;
        $data = wpjobportal::wpjobportal_sanitizeData($data);
        update_option('wpjp_set_theme_colors', wp_json_encode($data));
        $return = require(WPJOBPORTAL_PLUGIN_PATH . 'includes/css/style_color.php');

        if($return){
            return WPJOBPORTAL_SAVED;
        } else {
            return WPJOBPORTAL_SAVE_ERROR;
        }
    }

    function getColorCode($filestring, $colorNo) {
        if (wpjobportalphplib::wpJP_strstr($filestring, '$color' . $colorNo)) {
            $path1 = wpjobportalphplib::wpJP_strpos($filestring, '$color' . $colorNo);
            $path1 = wpjobportalphplib::wpJP_strpos($filestring, '#', $path1);
            $path2 = wpjobportalphplib::wpJP_strpos($filestring, ';', $path1);
            $colorcode = wpjobportalphplib::wpJP_substr($filestring, $path1, $path2 - $path1 - 1);
            return $colorcode;
        }
    }

      function replaceString(&$filestring, $colorNo, $data) {
        if (wpjobportalphplib::wpJP_strstr($filestring, '$color' . $colorNo)) {
            $path1 = wpjobportalphplib::wpJP_strpos($filestring, '$color' . $colorNo);
            $path2 = wpjobportalphplib::wpJP_strpos($filestring, ';', $path1);
            $filestring = substr_replace($filestring, '$color' . $colorNo . ' = "' . $data['color' . $colorNo] . '";', $path1, $path2 - $path1 + 1);
        }
    }

}

?>
