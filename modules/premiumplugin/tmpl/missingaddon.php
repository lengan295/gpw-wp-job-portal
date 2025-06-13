<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
if (wpjobportal::$_config['offline'] == 2) {
    ?>
    <?php WPJOBPORTALMessages::getMessage(); ?>
    <div class="jsst-main-up-wrapper">
        <?php include_once(WPJOBPORTAL_PLUGIN_PATH . 'includes/header.php'); ?>
        <h1 class="jsst-missing-addon-message" >
            Page Not Found !!
        </h1>
    <?php
} else {
    WPJOBPORTALlayout::getSystemOffline();
} ?>