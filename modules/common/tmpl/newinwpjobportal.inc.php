<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<?php
wp_register_script( 'wpjobportal-inline-handle', '' );
wp_enqueue_script( 'wpjobportal-inline-handle' );

$inline_js_script = "
    jQuery(document).ready(function ($) {
        $.validate();
    });
    ";
wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
