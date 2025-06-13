<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
if (!WPJOBPORTALincluder::getTemplate('templates/header',array('module' => 'user'))){
    return ;
}
if (!is_user_logged_in()) {
    // check to make sure user registration is enabled
    $is_enable = get_option('users_can_register');
    // only show the registration form if allowed
    if ($is_enable) { ?>
        <div class="wjportal-main-wrapper wjportal-clearfix">
            <div class="wjportal-page-header">
                <?php
                    WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'user','layout'=>'reg'));
                ?>
            </div>
            <!-- registration form fields -->
            <div class="wjportal-form-wrp wjportal-register-form">
                <?php
                    // show any error messages after form submission
                    wpjobportal_show_error_messages();
                ?>
                <form id="wpjobportal_registration_form" class="wjportal-form" action="" method="POST" enctype="multipart/form-data">
                    <?php WPJOBPORTALincluder::getTemplate('user/views/frontend/form-field'); ?>
                </form>
            </div>
        </div>
        <?php
    } else {
        WPJOBPORTALlayout::getRegistrationDisabled();
    }
}
?>
<?php
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $config_array = wpjobportal::$_config->getConfigByFor('captcha');
    if($config_array['captcha_selection'] == 1 && $config_array['recaptcha_privatekey'] ){
        wp_enqueue_script('wpjobportal-repaptcha-scripti', $protocol . 'www.google.com/recaptcha/api.js');
    }
?>
</div>
