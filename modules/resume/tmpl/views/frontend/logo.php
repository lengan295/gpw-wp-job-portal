<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param WPJOB PORTAL
 * @param Logo 
 */
?>

<?php
$photourl = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('jobseeker');
if (isset($myresume->photo) && $myresume->photo != "") {
    $wpdir = wp_upload_dir();
    $data_directory = wpjobportal::$_config->getConfigurationByConfigName('data_directory');
    $photourl = $wpdir['baseurl'] . '/' . $data_directory . '/data/jobseeker/resume_' . $myresume->id . '/photo/' . $myresume->photo;
}
$url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$myresume->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()));

if(empty(wpjobportal::$_data['shortcode_option_hide_resume_photo'])){ ?>
    <div class="wjportal-resume-logo">
        <span class="fir">
            <a href="<?php echo esc_url($url); ?>">
                <img  src="<?php echo esc_url($photourl); ?>" />
            </a>
        </span>
    </div>
<?php
}
?>