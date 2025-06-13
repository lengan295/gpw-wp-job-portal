<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
?>
<?php
$logo = isset($logo) ? $logo : null;
$path = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
if($myresume->photo != "") {
	$data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
	$wpdir = wp_upload_dir();
	$path = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $myresume->id . '/photo/' . $myresume->photo;
}
	if($logo=="1"){
		?>
		<span class="fir">
            <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$myresume->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))); ?>">
                <img  src="<?php echo esc_url($path); ?>" />
            </a>
        </span>
		<?php
	}else{?>
	<div class="wjportal-resume-logo">
		<a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$myresume->aliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))); ?>">
		<img src="<?php echo esc_url($path); ?>">
		</a>
	</div>
	<?php
	}
?>