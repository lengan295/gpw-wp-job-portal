<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
$isouruser = WPJOBPORTALincluder::getObjectClass('user')->isisWPJobportalUser();
if($isouruser != true){
    $link_url = wpjobportal::$_config->getConfigurationByConfigName('loginlinkforwpuser');
    if($link_url != ''){
        wpjobportal::$_data[0]['redirect_url'] = $link_url;
    }
}
//echo var_dump(wpjobportal::$_data[0]['redirect_url']);
if (wpjobportal::$_error_flag == null) {
    ?>
    <div class="wjportal-main-wrapper wjportal-clearfix">
        <div class="wjportal-page-header">
            <?php
                WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'login' , 'layout' => 'login'));
            ?>
            <?php
                if(!WPJOBPORTALincluder::getTemplate('templates/header',array('module'=>'user'))){
                    return;
                }
            ?>
        </div>
        <div class="wjportal-form-wrp wjportal-login-form">
            <div class="wjportal-form-sec-heading">
                <?php echo esc_html(__('Login into your account', 'wp-job-portal')); ?>
            </div>
            <?php
                if (!is_user_logged_in()) { // Display WordPress login form:
                    $args = array(
                        'redirect' => wpjobportal::$_data[0]['redirect_url'],
                      	'wpjobportalpageid' => wpjobportal::wpjobportal_getPageid(),
                        'form_id' => 'loginform-custom',
                        'label_username' => esc_html(__('Username', 'wp-job-portal')),
                        'label_password' => esc_html(__('Password', 'wp-job-portal')),
                        'label_remember' => esc_html(__('keep me login', 'wp-job-portal')),
                        'label_log_in' => esc_html(__('Login', 'wp-job-portal')),
                        'remember' => true
                    );
                    wp_login_form($args);
                } /* else { // If logged in:
                  wp_loginout( home_url() ); // Display "Log Out" link.
                  echo " | ";
                  wp_register('', ''); // Display "Site Admin" link.
                  } */
                    if(class_exists('wpjobportal')){ ?>
                        <?php
                            $defaultUrl = wpjobportal::wpjobportal_makeUrl(array('wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid(), 'wpjobportalme'=>'user', 'wpjobportallt'=>'userregister'));
                            $lrlink = WPJOBPORTALincluder::getJSModel('configuration')->getLoginRegisterRedirectLink($defaultUrl,'register');
                        ?>
                            <a class="wjportal-form-reg-btn" title="<?php echo esc_attr(esc_html(__('register','wp-job-portal'))); ?>" href="<?php echo esc_url($lrlink); ?>" href="<?php echo esc_html(__('register an account', 'wp-job-portal')); ?>">
                                <?php echo esc_html(__('Register an account', 'wp-job-portal')); ?>
                            </a>
                        <?php 
                        }       
                 ?>

            <?php do_action('wpjobportal_addons_social_login') ?>
        </div>
    </div>
<?php 
} ?>
</div>
