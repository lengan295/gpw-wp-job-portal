<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param pagination Fronteend  --wpjobportal
*/
?>
<?php
if ($module){
echo '<div id="wjportal-pagination" class="wjportal-pagination-wrp">' . wp_kses_post($pagination) . '</div>';
}
?>