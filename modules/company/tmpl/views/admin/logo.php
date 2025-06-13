<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param WP JOB PORTAL
*/
$company_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingData(1);
switch ($layout) {
	case 'que-logo':
		if(!isset($company_fields['logo'])){
			break;
		}
	    $path = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
		if ($company->logofilename != "") {
		    $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
		    $wpdir = wp_upload_dir();
		    $path = $wpdir['baseurl'] .'/'. $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
		}
		echo '<div class="wpjobportal-company-logo">';
		echo '	<a href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_company&wpjobportallt=formcompany&wpjobportalid='.esc_attr($company->id))).'&isqueue=1 title='.esc_html(__("logo","wp-job-portal")).'>
					<img src='. esc_url($path).' alt='.esc_html(__("logo","wp-job-portal")).'>
				</a>
				<div class="wpjobportal-company-crt-date">
					'.esc_html(date_i18n(wpjobportal::$_configuration['date_format'], strtotime($company->created))).'
				</div>
			</div>';
		
		break;
	case 'comp-logo':
		if(!isset($company_fields['logo'])){
			break;
		}
        $path = WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer');
		if ($company->logofilename != "") {
			$wpdir = wp_upload_dir();
            $data_directory = WPJOBPORTALincluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
            $path = $wpdir['baseurl'] . '/' . $data_directory . '/data/employer/comp_' . $company->id . '/logo/' . $company->logofilename;
        }
        echo '<div class="wpjobportal-company-logo">
                	<a href='.esc_url_raw(admin_url('admin.php?page=wpjobportal_company&wpjobportallt=formcompany&wpjobportalid='.esc_html($company->id))).' title='.esc_html(__("logo","wp-job-portal")).'>
                		<img src='.esc_url($path).' alt='.esc_html(__("logo","wp-job-portal")).'>
                	</a>
                	<div class="wpjobportal-company-crt-date">
                		'.esc_html(date_i18n(wpjobportal::$_configuration['date_format'], strtotime($company->created))).'
                	</div>
                </div>';
		break;
}


