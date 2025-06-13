<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
if ( !WPJOBPORTALincluder::getTemplate('templates/header', array('module' => 'common') )) {
    return;
}
if (wpjobportal::$_error_flag == null) {
} else {
    if(wpjobportal::$_error_flag_message !=''){
        echo wp_kses_post(wpjobportal::$_error_flag_message);
    }
 }
?>
</div>
