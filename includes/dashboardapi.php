<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

//total stats widget start
function wpjobportal_dashboard_widgets_totalstats() {

    wp_add_dashboard_widget(
            'wpjobportal_dashboard_widgets_totalstats', // Widget slug.
            esc_html(__('Total Stats', 'wp-job-portal')), // Title.
            'wpjobportal_dashboard_widget_function_totalstats' // Display function.
    );
}

add_action('wp_dashboard_setup', 'wpjobportal_dashboard_widgets_totalstats');

function wpjobportal_dashboard_widget_function_totalstats() {
    wpjobportal::wpjobportal_addStyleSheets();
    $data = WPJOBPORTALincluder::getJSModel('wpjobportal')->widgetTotalStatsData();
    if ($data == true) {
        $html = '<div id="wp-job-portal-widget-wrapper">
					<div class="total-stats-widget-data">
						<img class="total-jobs" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/control_panel/admin-widgets/job.png"/>
						<div class="widget-data-right">
							<div class="data-number">
								' . wpjobportal::$_data['widget']['jobs']->totaljobs . '
							</div>
							<div class="data-text">
								' . esc_html(__('Jobs', 'wp-job-portal')) . '
							</div>
						</div>
					</div>
					<div class="total-stats-widget-data">
						<img class="total-companies" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/control_panel/admin-widgets/companies.png"/>
						<div class="widget-data-right">
							<div class="data-number">
								' . wpjobportal::$_data['widget']['companies']->totalcompanies . '
							</div>
							<div class="data-text">
								' . esc_html(__('Companies', 'wp-job-portal')) . '
							</div>
						</div>
					</div>
					<div class="total-stats-widget-data">
						<img class="total-resumes" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/control_panel/admin-widgets/reume.png"/>
						<div class="widget-data-right">
							<div class="data-number">
								' . wpjobportal::$_data['widget']['resumes']->totalresumes . '
							</div>
							<div class="data-text">
								' . esc_html(__('Resume', 'wp-job-portal')) . '
							</div>
						</div>
					</div>
					<div class="total-stats-widget-data">
						<img class="active-jobs" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/control_panel/admin-widgets/active-jobs.png"/>
						<div class="widget-data-right">
							<div class="data-number">
								' . wpjobportal::$_data['widget']['jobs']->activejobs . '
							</div>
							<div class="data-text">
								' . esc_html(__('Active Jobs', 'wp-job-portal')) . '
							</div>
						</div>
					</div>
					<div class="total-stats-widget-data">
						<img class="applied-jobs" src="' . esc_url(WPJOBPORTAL_PLUGIN_URL) . '/includes/images/control_panel/admin-widgets/job-applied.png"/>
						<div class="widget-data-right">
							<div class="data-number">
								' . wpjobportal::$_data['widget']['aplliedjobs']->appliedjobs . '
							</div>
							<div class="data-text">
								' . esc_html(__('Applied Jobs', 'wp-job-portal')) . '
							</div>
						</div>
					</div>
				</div>';
        echo wp_kses($html, WPJOBPORTAL_ALLOWED_TAGS);
    } else {
    	$msg = esc_html(__('No record found','wp-job-portal'));
        WPJOBPORTALlayout::getNoRecordFound($msg);
    }
}

//total stats widge end;
//
?>
