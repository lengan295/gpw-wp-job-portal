<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param wp-job-portal Optional
* UserDetail=>users
*/
?>
<div class="wpjobportal-user-list">
	<div class="wpjobportal-user-list-top-wrp">
		<?php
			wpjobportalincluder::getTemplate('user/views/admin/logo',array('user' => $user,'layout' => 'userlogo'));

			wpjobportalincluder::getTemplate('user/views/admin/userdetail',array('user' => $user,'layout' => 'userdetail'));
		?>
	</div>
	<div class="wpjobportal-user-list-btm-wrp">
		<?php
			wpjobportalincluder::getTemplate('user/views/admin/control',array('user' => $user,'layout' => 'userdetailcontrol'));
		?>
	</div>
</div>

<div class="wpjobportal-user-stats-wrp">
    <div class ="wpjobportal-user-stats-heading">
        <?php echo esc_html(__('User Stats','wp-job-portal')); ?>
    </div>
    <div class="wpjobportal-user-stats">
	    <?php if($user->roleid == 1){
	    	if (isset(wpjobportal::$_data['companies'])) {?>
	    		<div class="wpjobportal-user-stat-item">
		            <div class="wpjobportal-user-stat-image">
		            	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/latest-icons-admin/lower-icons/jobs.png" alt="<?php echo esc_html(__('Jobs','wp-job-portal')); ?>">
		            </div>
		            <div class="wpjobportal-user-stat-cnt">
			            <span class="wpjobportal-user-stat-number">
			            	<?php echo esc_html(wpjobportal::$_data['companies']) ?>
			            </span>
			            <span class="wpjobportal-user-stat-text">
			            	<?php echo esc_html(__('Companies','wp-job-portal'))?>
			            </span>
		            </div>
		        </div>
	    	<?php }
	    	if (isset(wpjobportal::$_data['jobs'])) { ?>
		        <div class="wpjobportal-user-stat-item">
		            <div class="wpjobportal-user-stat-image">
		            	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/latest-icons-admin/lower-icons/companies.png" alt="<?php echo esc_html(__('Companies','wp-job-portal')); ?>">
		            </div>
		            <div class="wpjobportal-user-stat-cnt">
			            <span class="wpjobportal-user-stat-number">
			            	<?php echo esc_html(wpjobportal::$_data['jobs']) ?>
			            </span>
			            <span class="wpjobportal-user-stat-text">
			            	<?php echo esc_html(__('Jobs','wp-job-portal'))?>
			            </span>
		            </div>
		        </div>
		    <?php } ?>
	        <?php if (isset(wpjobportal::$_data['department'])) { ?>
	        	<div class="wpjobportal-user-stat-item">
		            <div class="wpjobportal-user-stat-image">
		            	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/latest-icons-admin/lower-icons/department.png" alt="<?php echo esc_html(__('Department','wp-job-portal')); ?>">
		            </div>
		            <div class="wpjobportal-user-stat-cnt">
			            <span class="wpjobportal-user-stat-number">
			            	<?php echo esc_html(wpjobportal::$_data['department'])?>
			            </span>
			            <span class="wpjobportal-user-stat-text">
			            	<?php echo esc_html(__('Department','wp-job-portal'))?>
			            </span>
		            </div>
		        </div>
	        <?php }
	        if (isset(wpjobportal::$_data['jobapply'])) { ?>
		        <div class="wpjobportal-user-stat-item">
		            <div class="wpjobportal-user-stat-image">
		            	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/latest-icons-admin/lower-icons/job-applied.png" alt="<?php echo esc_html(__('Job Applies','wp-job-portal')); ?>">
		            </div>
		            <div class="wpjobportal-user-stat-cnt">
			            <span class="wpjobportal-user-stat-number">
			            	<?php echo esc_html(wpjobportal::$_data['jobapply']) ?>
			            </span>
			            <span class="wpjobportal-user-stat-text">
			            	<?php echo esc_html(__('Job Applies','wp-job-portal'))?>
			            </span>
		            </div>
		        </div>
		    <?php } ?>
	    <?php }elseif($user->roleid == 2){
	    	if (isset(wpjobportal::$_data['resume'])) {?>
		        <div class="wpjobportal-user-stat-item">
		            <div class="wpjobportal-user-stat-image">
		            	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/latest-icons-admin/lower-icons/reume.png" alt="<?php echo esc_html(__('Resumes','wp-job-portal')); ?>">
		            </div>
		            <div class="wpjobportal-user-stat-cnt">
			            <span class="wpjobportal-user-stat-number">
			            	<?php echo esc_html(wpjobportal::$_data['resume']) ?>
			            </span>
			            <span class="wpjobportal-user-stat-text">
			            	<?php echo esc_html(__('Resume','wp-job-portal'))?>
			            </span>
		            </div>
		        </div>
		    <?php }
		    if (isset(wpjobportal::$_data['jobapply'])) { ?>
		        <div class="wpjobportal-user-stat-item">
		            <div class="wpjobportal-user-stat-image">
		            	<img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/latest-icons-admin/lower-icons/job-applied.png" alt="<?php echo esc_html(__('Job Applies','wp-job-portal')); ?>">
		            </div>
		            <div class="wpjobportal-user-stat-cnt">
			            <span class="wpjobportal-user-stat-number">
			            	<?php echo esc_html(wpjobportal::$_data['jobapply']) ?>
			            </span>
			            <span class="wpjobportal-user-stat-text">
			            	<?php echo esc_html(__('Job Applies','wp-job-portal'))?>
			            </span>
		            </div>
		        </div>
	    	<?php } ?>
	    <?php } ?>
	</div>
</div>
