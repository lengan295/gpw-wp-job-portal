<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

?>
<div class="wjportal-main-up-wrapper">
<?php
   if(!WPJOBPORTALincluder::getTemplate('templates/header',array('module'=>'common'))){
        return;
   }

// to disbale this page for admin
if(current_user_can( 'manage_options' )){ // if current user is admin
    wpjobportal::$_error_flag = true; // set error flag if there's no already
    wpjobportal::$_error_flag_message_for = 10;
    wpjobportal::$_error_flag_message = WPJOBPORTALLayout::setMessageFor(10 , '' , '',1);
}
if (wpjobportal::$_error_flag == null) {
    $module = WPJOBPORTALrequest::getVar('wpjobportalme');
    $layout = WPJOBPORTALrequest::getVar('wpjobportallt');
    $email = "";
    $uid = get_current_user_id();
    if(isset($_COOKIE['wpjobportal-socialemail'])){
        $email = sanitize_key($_COOKIE['wpjobportal-socialemail']);
    }
    $title = wpjobportal::$_config->getConfigurationByConfigName('title');?>
    <div class="wjportal-main-wrapper wjportal-clearfix">
        <div class="wjportal-page-header">
            <div class="wjportal-page-heading">
                <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue( $title)); ?>
            </div>
        </div>
        <div class="wjportal-form-wrp wjportal-new-login-form">
            <form class="wjportal-form" id="coverletter_form" method="post" action="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'common', 'task'=>'savenewinwpjobportal'))); ?>">
                <div class="wjportal-form-sec-heading"><?php echo esc_html(__('Are you new in', 'wp-job-portal')).' '.esc_html(wpjobportal::wpjobportal_getVariableValue( $title)); ?></div>
                <div class="wjportal-form-row">
                    <div class="wjportal-form-title"><?php echo esc_html(__('Please select your role', 'wp-job-portal')); ?> <font >*</font></div>
                    <div class="wjportal-form-value">
                        <?php echo wp_kses(WPJOBPORTALformfield::select('roleid', WPJOBPORTALincluder::getJSModel('common')->getRolesForCombo(''), '', esc_html(__('Select Role', 'wp-job-portal')), array('class' => 'inputbox wjportal-form-select-field', 'data-validation' => 'required')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                    </div>
                </div>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('desired_module', esc_html($module)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('desired_layout', esc_html($layout)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('id', ''),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('uid', esc_html($uid)),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('action', 'common_savenewinwpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportalpageid', esc_html(get_the_ID())),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_new_in_jobportal_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php
                    if(isset($_COOKIE['wpjobportal-socialmedia']) && !empty($_COOKIE['wpjobportal-socialid'])){
                        echo wp_kses(WPJOBPORTALformfield::hidden('emailaddress', $email),WPJOBPORTAL_ALLOWED_TAGS);
                        echo wp_kses(WPJOBPORTALformfield::hidden('socialmedia', sanitize_key($_COOKIE['wpjobportal-socialmedia'])),WPJOBPORTAL_ALLOWED_TAGS);
                        echo wp_kses(WPJOBPORTALformfield::hidden('first_name', sanitize_key($_COOKIE['wpjobportal-socialfirstname'])),WPJOBPORTAL_ALLOWED_TAGS);
                        echo wp_kses(WPJOBPORTALformfield::hidden('last_name', sanitize_key($_COOKIE['wpjobportal-sociallastname'])),WPJOBPORTAL_ALLOWED_TAGS);
                        echo wp_kses(WPJOBPORTALformfield::hidden('socialid', sanitize_key($_COOKIE['wpjobportal-socialid'])),WPJOBPORTAL_ALLOWED_TAGS);
                    }
                ?>
                <div class="wjportal-form-btn-wrp">
                    <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Submit', 'wp-job-portal')), array('class' => 'button wjportal-form-btn wjportal-save-btn')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                </div>
            </form>
        </div>
    </div><?php 
}else{
    echo wp_kses_post(wpjobportal::$_error_flag_message);
}
?>
</div>
