<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp-job-portal Optional
* Filter Pop-up
*/
?>
<div id="full_background" style="display:none;"></div>
<div id="popup_main" style="display:none;">
	<span class="popup-top">
	    <span id="popup_title" >
	        <?php echo esc_html(__('Settings', 'wp-job-portal')); ?>
	    </span>
	    <img id="popup_cross" alt="popup cross"  src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/popup-close.png">
	</span>
    <div id="checkbox-popup-wrapper">
        <form id="filter_form" method="post" action="?page=wpjobportal_activitylog&wpjobportallt=activitylogs">
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[age]', array('1' => esc_html(__('Ages', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['age']) ? wpjobportal::$_data['filter']['age'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[job]', array('1' => esc_html(__('Jobs', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['job']) ? wpjobportal::$_data['filter']['job'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[company]', array('1' => esc_html(__('Company', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['company']) ? wpjobportal::$_data['filter']['company'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[careerlevel]', array('1' => esc_html(__('Career Level', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['careerlevel']) ? wpjobportal::$_data['filter']['careerlevel'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[city]', array('1' => esc_html(__('City', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['city']) ? wpjobportal::$_data['filter']['city'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[state]', array('1' => esc_html(__('State', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['state']) ? wpjobportal::$_data['filter']['state'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[country]', array('1' => esc_html(__('Country', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['country']) ? wpjobportal::$_data['filter']['country'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[category]', array('1' => esc_html(__('Category', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['category']) ? wpjobportal::$_data['filter']['category'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[currency]', array('1' => esc_html(__('Currency', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['currency']) ? wpjobportal::$_data['filter']['currency'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[customfield]', array('1' => esc_html(__('Custom Field', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['customfield']) ? wpjobportal::$_data['filter']['customfield'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[emailtemplate]', array('1' => esc_html(__('Email Template', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['emailtemplate']) ? wpjobportal::$_data['filter']['emailtemplate'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[experience]', array('1' => esc_html(__('Experience', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['experience']) ? wpjobportal::$_data['filter']['experience'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[highesteducation]', array('1' => esc_html(__('Highest Education', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['highesteducation']) ? wpjobportal::$_data['filter']['highesteducation'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <?php if(in_array('coverletter',wpjobportal::$_active_addons)){ ?>
                <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[coverletter]', array('1' => esc_html(__('Cover Letter', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['coverletter']) ? wpjobportal::$_data['filter']['coverletter'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <?php } ?>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[jobstatus]', array('1' => esc_html(__('Job Status', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['jobstatus']) ? wpjobportal::$_data['filter']['jobstatus'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[jobtype]', array('1' => esc_html(__('Job Type', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['jobtype']) ? wpjobportal::$_data['filter']['jobtype'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[salaryrangetype]', array('1' => esc_html(__('Salary Range Type', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['salaryrangetype']) ? wpjobportal::$_data['filter']['salaryrangetype'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[salaryrange]', array('1' => esc_html(__('Salary Range', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['salaryrange']) ? wpjobportal::$_data['filter']['salaryrange'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[shift]', array('1' => esc_html(__('Shift', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['shift']) ? wpjobportal::$_data['filter']['shift'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <?php if(in_array('departments',wpjobportal::$_active_addons)){ ?>
                <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[department]', array('1' => esc_html(__('Department', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['department']) ? wpjobportal::$_data['filter']['department'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <?php } ?>

            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[resume]', array('1' => esc_html(__('Resume', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['resume']) ? wpjobportal::$_data['filter']['resume'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <?php if(in_array('resumesearch',wpjobportal::$_active_addons)){ ?>
                <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[resumesearches]', array('1' => esc_html(__('Resume Search', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['resumesearches']) ? wpjobportal::$_data['filter']['resumesearches'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <?php } ?>

            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[jobsearch]', array('1' => esc_html(__('Job Search', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['jobsearch']) ? wpjobportal::$_data['filter']['jobsearch'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <div class="checkbox-filter"><?php echo wp_kses(WPJOBPORTALformfield::checkbox('filter[jobapply]', array('1' => esc_html(__('Job Apply', 'wp-job-portal'))), isset(wpjobportal::$_data['filter']['jobapply']) ? wpjobportal::$_data['filter']['jobapply'] : 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?></div>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('searchsubmit', 1 ),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('sortby', wpjobportal::$_data['sortby']),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('sorton', wpjobportal::$_data['sorton']),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <div class="popup-act-btn-wrp" >
                <a href="#" class="popup-act-btn" onclick="submitfrom()" title="<?php echo esc_html(__('submit', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Submit', 'wp-job-portal')); ?>
                </a>
            </div>
        </form>
    </div>
</div>
