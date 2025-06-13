<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param Pagination wpjobportal Admin s
*/
?>
<?php
if($module){
	echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses($pagination,WPJOBPORTAL_ALLOWED_TAGS) . '</div></div>';
}

