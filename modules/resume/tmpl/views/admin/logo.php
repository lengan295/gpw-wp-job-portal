<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
$listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingDataForListing(3);
?>
<?php
    $photo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
	if (isset($resume->photo) && $resume->photo != '') {
		$data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
	    $wpdir = wp_upload_dir();
	    $photo = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $resume->id. '/photo/' . $resume->photo;
	}
?>

<?php echo wp_kses($resumeque, WPJOBPORTAL_ALLOWED_TAGS) ?>
    
		<?php if(isset($listing_fields['photo'])){ ?>
			<a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid='.$resume->id)); ?>">
				<img src="<?php echo esc_url($photo); ?>" alt="<?php echo esc_html(__('logo','wp-job-portal')); ?>" />
			</a>
		<?php } ?>
		<div class="wpjobportal-resume-crt-date">
			<?php echo esc_html(date_i18n(wpjobportal::$_configuration['date_format'], strtotime($resume->created))); ?>
		</div>
	</div>
