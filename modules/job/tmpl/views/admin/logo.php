<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param logo wp-job-portal
*/
$data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
$company_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingData(1);
$html = '';
switch ($layout) {
	case 'logo':
		if(!isset($company_fields['logo'])){
			break;
		}
        $logo = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
		if (isset($job->logo) && $job->logo != '') {
	        $wpdir = wp_upload_dir();
	        $logo = $wpdir['baseurl'] . '/' . $data_directory.'/data/employer/comp_'.$job->companyid.'/logo/'. $job->logo;
	    }
		$html.= '<div class="wpjobportal-jobs-logo">
					<a href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_job&wpjobportallt=formjob&wpjobportalid='.esc_attr($job->id))).'>
						<img src='.$logo.' alt='.esc_html(__("logo",'wp-job-portal')).'>
					</a>
				</div>';
		break;
	case 'que-logo':
		if(!isset($company_fields['logo'])){
			break;
		}
        $path = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
	 	if ($job->logofilename != "") {
            $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
            $wpdir = wp_upload_dir();
            $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->logofilename;
        }
		$html.='<div class="wpjobportal-jobs-logo">
                    <a href='. esc_url_raw(admin_url('admin.php?page=wpjobportal_job&wpjobportallt=formjob&wpjobportalid='.esc_attr($job->id).'&isqueue=1')).'>
                    	<img src='.$path.' alt='.esc_html(__("logo",'wp-job-portal')).'>
                    </a>
                </div>';
		break;

	default:

		break;
}
echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);

?>
