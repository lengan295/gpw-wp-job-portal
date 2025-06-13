<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */

$published_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingData(1);
if(isset($published_fields['logo']) && $published_fields['logo'] != ''){
	switch ($layout) {
		case 'toprowlogo':
			if($job->companyid != '' && is_numeric($job->companyid) && $job->companyid > 0){
				if(empty(wpjobportal::$_data['shortcode_option_hide_company_logo'])){
		            $path = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
					if (isset($job->logofilename) && $job->logofilename != "") {
			            $wpdir = wp_upload_dir();
			            $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
			            $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $job->companyid . '/logo/' . $job->logofilename;
			        }
			        if(in_array('multicompany', wpjobportal::$_active_addons)){
			        	$url = "multicompany";
			        }else{
			        	$url = "company";
			        }
					echo '<div class="wjportal-jobs-logo">
		                    <a href='. esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$url, 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyid))) .'>
		                        <img src='. esc_url($path) .' alt="'.esc_html(__('Company logo','wp-job-portal')).'" />
		                    </a>
		                </div>
						';
				}
			}
			break;
		case 'logo':
			if($job->companyid != '' && is_numeric($job->companyid) && $job->companyid > 0){
				echo ' <div class="wjportal-job-company-logo">
		                    <img class="wjportal-job-company-logo-image" src='. esc_url(WPJOBPORTALincluder::getJSModel('company')->getLogoUrl($job->companyid,$job->logofilename)) .'  alt="'.esc_html(__('Company logo','wp-job-portal')).'">
		                </div>';
			}
		break;
	}
}
?>

