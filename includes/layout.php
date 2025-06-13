<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALlayout {

    static function getNoRecordFound($message = null, $linkarray = array()) {        
        if($message == null){
            $message = esc_html(__('Could not find any matching results', 'wp-job-portal'));
        }
        $html = '
                <div class="wjportal-error-messages-wrp">
                    <div class="wjportal-error-msg-image-wrp">
                        <img class="wjportal-error-msg-image" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/errors/no-record.png" alt="'.esc_html(__("no record", "wp-job-portal")).'" />
                    </div>
                    <div class="wjportal-error-msg-txt">
                        ' . $message . ' !...
                    </div>    
                    <div class="wjportal-error-msg-actions-wrp">';
                        if(!empty($linkarray)){
                            foreach($linkarray AS $link){
                                if( isset($link['text']) && $link['text'] != ''){
                                    $html .= '<a class="wjportal-error-msg-act-btn wjportal-error-msg-act-login-btn" href="' . $link['link'] . '">' . $link['text'] . '</a>';
                                }
                            }
                        }
        $html .=    '</div>
                </div>
        ';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

    static function getAdminPopupNoRecordFound() {
        $html = '
                <div class="wjportal-error-messages-wrp">
                    <div class="wjportal-error-msg-image-wrp">
                        <img class="wjportal-error-msg-image" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/errors/no-record.png" alt="'.esc_html(__("no record", "wp-job-portal")).'" />
                    </div>
                    <div class="wjportal-error-msg-txt">
                        '.esc_html(__("No record found !...","wp-job-portal")).'
                    </div>
                </div>
        ';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

    static function getNoRecordFoundInSpecialCase() {
        if (is_admin()) {
            $link = 'admin.php?page=wpjobportal_wpjobportal';
        } else {
            $link = get_the_permalink();
        }
        $html = '
                <div class="wjportal-error-messages-wrp">
                    <div class="wjportal-error-msg-image-wrp">
                        <img class="wjportal-error-msg-image" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/errors/no-record.png" alt="'.esc_html(__("no record", "wp-job-portal")).'" />
                    </div>
                    <div class="wjportal-error-msg-txt">
                        ' . esc_html(__('No record found !...', 'wp-job-portal')) . '
                    </div>
                </div>
        ';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

    static function getSystemOffline() {
        $offline_text = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('offline_text');
        $html = '
                <div class="wjportal-main-up-wrapper">
                <div class="wjportal-error-messages-wrp wjportal-error-messages-style2">
                    <div class="wjportal-error-msg-image-wrp">
                        <img class="wjportal-error-msg-image" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/errors/system-offline.png" alt="'.esc_html(__("system offline", "wp-job-portal")).'" />
                    </div> 
                    <div class="wjportal-error-msg-txt wpjobportal-off-config-text ">
                        ' . $offline_text . '
                    </div>
                    <div class="wjportal-error-msg-txt2">
                        '.esc_html(__('Unfortunately sytem is offline for a bit of maintenance right now. But soon we will be up.','wp-job-portal')).'
                    </div>
                </div>
                </div>
        ';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

    static function getUserDisabledMsg() {
        $html = '
            <div class="wjportal-main-up-wrapper">
                <div class="wjportal-error-messages-wrp">
                    <div class="wjportal-error-msg-image-wrp">
                        <img class="wjportal-error-msg-image" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/errors/user-ban.png" alt="'.esc_html(__("user ban", "wp-job-portal")).'" />
                    </div>
                    <div class="wjportal-error-msg-txt">
                        ' . esc_html(__('Your account is disabled, please contact system administrator !...', 'wp-job-portal')) . '
                    </div>
                </div>
            </div>
        ';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

    static function getUserGuest() {
        $html = '<div class="wjportal-error-messages-wrp">
                    <div class="wjportal-error-msg-image-wrp">
                        <img class="wjportal-error-msg-image" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/errors/login.png" alt="'.esc_html(__("login", "wp-job-portal")).'" />
                    </div>
                    <div class="wjportal-error-msg-txt">
                        ' . esc_html(__('To Access This Page Please Login !...', 'wp-job-portal')) . '
                    </div>
                    <div class="wjportal-error-msg-actions-wrp">
                        <a class="wjportal-error-msg-act-btn wjportal-error-msg-act-login-btn" href="' . get_the_permalink() . '">' . esc_html(__('Back to control panel', 'wp-job-portal')) . '</a>
                    </div>
                </div>
        ';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

    static function getRegistrationDisabled() {
        $html = '<div class="wjportal-error-messages-wrp">
                    <div class="wjportal-error-msg-image-wrp">
                        <img class="wjportal-error-msg-image" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/errors/register-banned.png" alt="'.esc_html(__("register banned", "wp-job-portal")).'" />
                    </div>
                    <div class="wjportal-error-msg-txt">
                        ' . esc_html(__('Registration is disabled by admin, please contact to system administrator !...', 'wp-job-portal')) . '
                    </div>
                </div>
        ';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

    static function setMessageFor($for, $link = null, $linktext = null, $return = 0) {
        $image = null;
        $description = '';
        $login_register_link = 0;
        switch ($for) {
            case '1': // User is guest
                $description = esc_html(__('You are not logged in', 'wp-job-portal'));
                $login_register_link = 1;
                break;
            case '2': // User is job seeker
                $description = esc_html(__('Jobseeker not allowed to perform this action', 'wp-job-portal'));
                break;
            case '3': // User is employer
                $description = esc_html(__('Employer not allowed to perform this action', 'wp-job-portal'));
                break;
            case '4': // User is not allowed to do that b/c of credits
                $description = esc_html(__('You do not have enough credits', 'wp-job-portal'));
                break;
            case '5': // When employer is disabled from configuration 
                $description = esc_html(__('Employer is disabled by admin', 'wp-job-portal'));
                break;
            case '6': // When job/company/resume is not approved or expired 
                $description = esc_html(__('The page you are looking for no longer exists', 'wp-job-portal'));
                break;
            case '7': // Employer not allowed in jobseeker area
                $description = esc_html(__('Employer not allowed in job seeker area', 'wp-job-portal'));
                break;
            case '8': // Already loged in 
                $description = esc_html(__('You are already logged in', 'wp-job-portal'));
                break;
            case '9': // User have no role
                $description = esc_html(__('Please select your role', 'wp-job-portal'));
                break;
            case '10': // User have no role
                $description = esc_html(__('You are not allowed', 'wp-job-portal'));
                break;
            case '15':
                $description = esc_html(__('Buy New Package','wp-job-portal'));
                break;
            case '16':
                $description = esc_html(__('You are not allowed to add more than one','wp-job-portal').' '.wpjobportal::wpjobportal_getVariableValue($linktext).' '.__('contact adminstrator','wp-job-portal'));
                break;
            case '16':
                $description = esc_html(__('Payment is not made against this job contact adminstrator','wp-job-portal'));
                break;
            case '18':
                $description = esc_html(__('Addon Page Not Found','wp-job-portal'));
                break;
        }
        $html = WPJOBPORTALlayout::getUserNotAllowed($description, $link, $linktext, $image, $return,$login_register_link);
        if ($return == 1) {
            return $html;
        }
    }

    static function getUserNotAllowed($description, $link, $linktext, $image, $return = 0,$login_register_link = 0) {
        $html = '<div class="wjportal-main-up-wrapper">
                <div class="wjportal-error-messages-wrp">
                    <div class="wjportal-error-msg-image-wrp">
                        <img class="wjportal-error-msg-image" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/images/errors/not-allowed.png" alt="'.esc_html(__("not allowed", "wp-job-portal")).'" />
                    </div>
                    <div class="wjportal-error-msg-txt">
                        ' . $description . ' !...
                    </div>
                    <div class="wjportal-error-msg-actions-wrp">
                    ';
                        if($linktext == null){
                            $linktext = "Login";
                        }
                        if ($link != null) {
                            $lrlink = $link;
                            if($login_register_link == 1){
                                $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($link,'login');
                            }
                            $html .= '<a class="wjportal-error-msg-act-btn wjportal-error-msg-act-login-btn" href="' . $lrlink . '">' . wpjobportal::wpjobportal_getVariableValue($linktext) . '</a>';
                            if($linktext == "Login"){
                                $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'user', 'wpjobportallt'=>'userregister','wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));
                                $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'register');
                                $html .= '<a class="wjportal-error-msg-act-btn wjportal-error-msg-act-register-btn" href="' . $lrlink . '">' . esc_html(__("Register",'wp-job-portal')) . '</a>';
                            }
                        }
                    $html .= '
                    </div>
                </div>
                </div>
        ';
        if ($return == 0) {
            echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
        } else {
            return $html;
        }
    }

    static function getUserAlreadyLoggedin( $link ) {
        $html = '<div class="wjportal-main-up-wrapper">
                    <div class="wjportal-error-messages-wrp">
                        <div class="wjportal-error-msg-txt">
                            ' . esc_html(__('You are already logged in !...', 'wp-job-portal')) . '
                        </div>
                        <div class="wjportal-error-msg-actions-wrp">';
        $html .= '<a class="wjportal-error-msg-act-btn wjportal-error-msg-act-login-btn" href="' . $link. '">' . esc_html(__('Logout','wp-job-portal')) . '</a>';
        $html .= '</div>
                </div>
                </div>
        ';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    }

}

?>