<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param wp-job-portal Detail
*/
?>
<div class="wpjobportal-user-list-top-wrp">
	<?php
		wpjobportalincluder::getTemplate('user/views/admin/logo',array('user' => $user,'layout' => 'userlogo'));

		wpjobportalincluder::getTemplate('user/views/admin/userdetail',array('user' => $user,'layout' => 'user'));
	?>
</div>
<div class="wpjobportal-user-list-btm-wrp">
	<?php
		wpjobportalincluder::getTemplate('user/views/admin/control',array('user' => $user,'layout' => 'usercontrol'));
	?>
</div>