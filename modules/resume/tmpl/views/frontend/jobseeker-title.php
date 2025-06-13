<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
$dateformat = wpjobportal::$_configuration['date_format'];
?>
		<span class="my-resume-modified-date" >
		    <span class="my-resume-modified-date-title" >
		        <?php echo esc_html(__('Modified Date','wp-job-portal')); ?>
		    </span>
		    <span class="my-resume-modified-date-value" >
		        <?php echo esc_html(date_i18n($dateformat,strtotime($myresume->last_modified))); ?>
		    </span>
		</span>
	</div>
	<div class="data-bigupper">
	<div class="big-upper-upper">
	<div class="headingtext item-title">
	<a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$myresume->id, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))); ?>">
	    <span class="title"><?php echo esc_html($myresume->first_name) . " " . esc_html($myresume->last_name); ?></span>
	</a>
	</div>
	<span class="buttonu"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($myresume->jobtypetitle)); ?></span><span class="datecreated"><?php echo esc_html(__('Created', 'wp-job-portal')) . ': ' . esc_html(date_i18n($dateformat, strtotime($myresume->created))); ?></span>
	</div>
	<div class="big-upper-lower listing-fields">
	<div class="myresume-list-bottom-left">
	<span class="lower-upper-title">(
	        <?php echo esc_html($myresume->application_title); ?>
	    </a>)
	</span>
	<div class="custom-field-wrapper">
	    <span class="js-bold"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fields']['email_address'])) . ': '; ?></span>
	    <span class="get-text"><?php echo esc_html($myresume->email_address); ?></span>
	</div>
	<div class="custom-field-wrapper">
	    <span class="js-bold">
	    <?php
	        if(!isset(wpjobportal::$_data['fields']['desired_salary'])){
	            wpjobportal::$_data['fields']['desired_salary'] = wpjobportal::$_wpjpfieldordering->getFieldTitleByFieldAndFieldfor('desired_salary',3);
	        }
	        echo esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fields']['desired_salary'])) . ': '; ?>
	    </span>
	    <span class="get-text"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($myresume->salaryfixed)); ?></span>
	</div>
	<div class="custom-field-wrapper">
	    <span class="js-bold"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fields']['job_category'])) . ': '; ?></span>
	    <span class="get-text"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($myresume->cat_title)); ?></span>
	</div>
	<div class="custom-field-wrapper">
	    <span class="js-bold">
	    <?php
	        if(!isset(wpjobportal::$_data['fields']['total_experience'])){
	            wpjobportal::$_data['fields']['total_experience'] = wpjobportal::$_wpjpfieldordering->getFieldTitleByFieldAndFieldfor('total_experience',3);
	        }
	        echo esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fields']['total_experience'])) . ': '; ?></span>
	    <span class="get-text"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($myresume->total_experience)); ?></span>
	</div>
	<?php
	// custom fields
	// if(in_array('customfield', wpjobportal::$_active_addons)){
		$customfields = apply_filters('wpjobportal_addons_get_custom_field',false,3,1,1);
		foreach ($customfields as $field) {
		    $showCustom = apply_filters('wpjobportal_addons_show_customfields_params',false,$field,9,$myresume->params);
		    echo esc_attr($showCustom);
		}
	// }
	?>
</div>
