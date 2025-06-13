<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALcaptcha {

    function getCaptchaForForm() {
        $config_array = WPJOBPORTALincluder::getJSModel('configuration')->getConfigByFor('captcha');
        $rand = $this->randomNumber();
        WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable($rand,'','wpjobportal_spamcheckid','captcha');
        $wpjobportal_rot13 = wp_rand(0, 1);
        WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable($wpjobportal_rot13,'','wpjobportal_rot13','captcha');
        $operator = 2;
        if ($operator == 2) {
            $tcalc = $config_array['owncaptcha_calculationtype'];
        }
        $max_value = 20;
        $negativ = 1;
        $operend_1 = wp_rand($negativ, $max_value);
        $operend_2 = wp_rand($negativ, $max_value);
        $operand = $config_array['owncaptcha_totaloperand'];
        if ($operand == 3) {
            $operend_3 = wp_rand($negativ, $max_value);
        }

        if ($config_array['owncaptcha_calculationtype'] == 2) { // Subtraction
            if ($config_array['owncaptcha_subtractionans'] == 1) {
                $ans = $operend_1 - $operend_2;
                if ($ans < 0) {
                    $one = $operend_2;
                    $operend_2 = $operend_1;
                    $operend_1 = $one;
                }
                if ($operand == 3) {
                    $ans = $operend_1 - $operend_2 - $operend_3;
                    if ($ans < 0) {
                        if ($operend_1 < $operend_2) {
                            $one = $operend_2;
                            $operend_2 = $operend_1;
                            $operend_1 = $one;
                        }
                        if ($operend_1 < $operend_3) {
                            $one = $operend_3;
                            $operend_3 = $operend_1;
                            $operend_1 = $one;
                        }
                    }
                }
            }
        }

        if ($tcalc == 0)
            $tcalc = wp_rand(1, 2);

        if ($tcalc == 1) { // Addition
            if ($wpjobportal_rot13 == 1) { // ROT13 coding
                if ($operand == 2) {
                    WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable(wpjobportalphplib::wpJP_str_rot13(wpjobportalphplib::wpJP_safe_encoding($operend_1 + $operend_2)),'','wpjobportal_spamcheckresult','captcha');
                } elseif ($operand == 3) {
                    WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable(wpjobportalphplib::wpJP_str_rot13(wpjobportalphplib::wpJP_safe_encoding($operend_1 + $operend_2 + $operend_3)),'','wpjobportal_spamcheckresult','captcha');
                }
            } else {
                if ($operand == 2) {
                    WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable(wpjobportalphplib::wpJP_safe_encoding($operend_1 + $operend_2),'','wpjobportal_spamcheckresult','captcha');
                } elseif ($operand == 3) {
                    WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable(wpjobportalphplib::wpJP_safe_encoding($operend_1 + $operend_2 + $operend_3),'','wpjobportal_spamcheckresult','captcha');
                }
            }
        } elseif ($tcalc == 2) { // Subtraction
            if ($wpjobportal_rot13 == 1) {
                if ($operand == 2) {
                    WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable(wpjobportalphplib::wpJP_str_rot13(wpjobportalphplib::wpJP_safe_encoding($operend_1 - $operend_2)),'','wpjobportal_spamcheckresult','captcha');
                } elseif ($operand == 3) {
                    WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable(wpjobportalphplib::wpJP_str_rot13(wpjobportalphplib::wpJP_safe_encoding($operend_1 - $operend_2 - $operend_3)),'','wpjobportal_spamcheckresult','captcha');
                }
            } else {
                if ($operand == 2) {
                    WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable(wpjobportalphplib::wpJP_safe_encoding($operend_1 - $operend_2),'','wpjobportal_spamcheckresult','captcha');
                } elseif ($operand == 3) {
                    WPJOBPORTALincluder::getObjectClass('wpjpnotification')->addSessionNotificationDataToTable(wpjobportalphplib::wpJP_safe_encoding($operend_1 - $operend_2 - $operend_3),'','wpjobportal_spamcheckresult','captcha');
                }
            }
        }
        $add_string = "";
        if (wpjobportal::$theme_chk == 1) {
            $add_string .= '<div class="wpj-jp-form-captcha" ><div class="wpj-jp-form-label" for="' . $rand . '">';
        } else {
            $add_string .= '<div class="wjportal-form-row wjportal-form-captcha" ><div class="wjportal-form-title" for="' . $rand . '">';
        }

        if ($tcalc == 1) {
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . esc_html(__('Plus', 'wp-job-portal')) . ' ' . $operend_2 . ' ' . esc_html(__('Equals', 'wp-job-portal')) . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . esc_html(__('Plus', 'wp-job-portal')) . ' ' . $operend_2 . ' ' . esc_html(__('Plus', 'wp-job-portal')) . ' ' . $operend_3 . ' ' . esc_html(__('Equals', 'wp-job-portal')) . ' ';
            }
        } elseif ($tcalc == 2) {
            $converttostring = 0;
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . esc_html(__('Minus', 'wp-job-portal')) . ' ' . $operend_2 . ' ' . esc_html(__('Equals', 'wp-job-portal')) . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . esc_html(__('Minus', 'wp-job-portal')) . ' ' . $operend_2 . ' ' . esc_html(__('Minus', 'wp-job-portal')) . ' ' . $operend_3 . ' ' . esc_html(__('Equals', 'wp-job-portal')) . ' ';
            }
        }

        $add_string .= '<font color="red">* </font></div>';
        $class_prefix = "";
        if(wpjobportal::$theme_chk == 1){
            $class_prefix = 'wpj-jp';
        }

        $add_string .= '<div class="wjportal-form-value"><input type="text" name="' . $rand . '" id="' . $rand . '" size="3" class="inputbox form-control wjportal-form-input-field '.$class_prefix.'-input-field  ' . $rand . '" value="" data-validation="required" /></div>';
        $add_string .= '</div>';

        return $add_string;
    }

    function randomNumber() {
        $pw = '';

        // first character has to be a letter
        $characters = range('a', 'z');
        $pw .= $characters[wp_rand(0, 25)];

        // other characters arbitrarily
        $numbers = range(0, 9);
        $characters = array_merge($characters, $numbers);

        $pw_length = wp_rand(4, 12);

        for ($i = 0; $i < $pw_length; $i++) {
            $pw .= $characters[wp_rand(0, 35)];
        }
        return $pw;
    }

    private function performChecks() {
        $wpjobportal_rot13 = WPJOBPORTALincluder::getObjectClass('wpjpnotification')->getNotificationDatabySessionId('wpjobportal_rot13',true);
        if ($wpjobportal_rot13 == 1) {
            $spamcheckresult = wpjobportalphplib::wpJP_str_rot13(WPJOBPORTALincluder::getObjectClass('wpjpnotification')->getNotificationDatabySessionId('wpjobportal_spamcheckresult',true));
        } else {
            $spamcheckresult = WPJOBPORTALincluder::getObjectClass('wpjpnotification')->getNotificationDatabySessionId('wpjobportal_spamcheckresult',true);
        }
        $spamcheckresult = wpjobportalphplib::wpJP_safe_decoding($spamcheckresult);


        $spamcheck = WPJOBPORTALincluder::getObjectClass('wpjpnotification')->getNotificationDatabySessionId('wpjobportal_spamcheckid',true);
        $spamcheck = WPJOBPORTALrequest::getVar($spamcheck, '', 'post');
        if (!is_numeric($spamcheckresult) || $spamcheckresult != $spamcheck) {
            return false; // Failed
        }
        return true;
    }

    function checkCaptchaUserForm() {
        if (!$this->performChecks())
            $return = 2;
        else
            $return = 1;
        return $return;
    }

}

?>
