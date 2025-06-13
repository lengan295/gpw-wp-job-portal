<?php
if (!defined('ABSPATH'))
die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
$job = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
$company = isset(wpjobportal::$_data['company']) ? wpjobportal::$_data['company'] : null;
$msg = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit', 'wp-job-portal')) : esc_html(__('Add New', 'wp-job-portal'));
?>
<div class="wjportal-main-wrapper wjportal-clearfix">
    <div class="wjportal-page-header">
        <?php 
            WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'job' , 'layout' => 'addjob','job' => $job));
            if(!WPJOBPORTALincluder::getTemplate('templates/header',array('module'=>'job'))){
                return;
            }
        ?>
    </div>
    <?php if (wpjobportal::$_error_flag == null) { ?>
    <div class="wjportal-form-wrp wjportal-add-job-form">
        <form class="wjportal-form" id="wpjobportal-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'task'=>'savejob'))); ?>">
            <?php
                if(in_array('credits', wpjobportal::$_active_addons)){
                    $submissionType = wpjobportal::$_config->getConfigValue('submission_type');
                    if($submissionType == 3  && !empty(wpjobportal::$_data['package'])){
                        WPJOBPORTALincluder::getTemplate('job/views/packages',array('package' => wpjobportal::$_data['package']));
                    }
                }
                $termsandconditions_flag = 0;
                $formfields = WPJOBPORTALincluder::getTemplate('job/form-fields', array(
                    'job' => $job,
                    'company' => $company
                ));
                foreach ($formfields as $formfield) {
                    WPJOBPORTALincluder::getTemplate('templates/form-field', $formfield);
                }
                $termsandconditions_flag = 0;
                if(isset(wpjobportal::$_data[2]) && !empty(wpjobportal::$_data[2])){ // to handle log errors
                    foreach (wpjobportal::$_data[2] AS $field) {
                        switch ($field->field) {
                            case 'termsandconditions':
                            if(!isset(wpjobportal::$_data[0])){
                                $termsandconditions_flag = 1;
                                $termsandconditions_fieldtitle = $field->fieldtitle;
                                $termsandconditions_link = get_the_permalink(wpjobportal::$_configuration['terms_and_conditions_page_job']);
                            }
                        break;
                        }
                    }
                }
                if($termsandconditions_flag == 1){
                ?> 
                    <div class="wpjobportal-terms-and-conditions-wrap" data-wpjobportal-terms-and-conditions="1" >
                        <?php echo wp_kses(WPJOBPORTALformfield::checkbox('termsconditions', array('1' => wpjobportal::wpjobportal_getVariableValue($termsandconditions_fieldtitle)), 0, array('class' => 'checkbox')),WPJOBPORTAL_ALLOWED_TAGS); ?>
                        <a title="<?php echo esc_html(__('Terms and Conditions','wp-job-portal')); ?>" href="<?php echo esc_url($termsandconditions_link); ?>" target="_blank" >
                            <img alt="<?php echo esc_html(__('Terms and Conditions','wp-job-portal')); ?>" title="<?php echo esc_html(__('Terms and Conditions','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/widget-link.png'; ?>" />
                        </a>
                    </div>
                <?php } ?>
                <?php 
                echo wp_kses(WPJOBPORTALformfield::hidden('id', isset(wpjobportal::$_data[0]->id) ? wpjobportal::$_data[0]->id : '' ),WPJOBPORTAL_ALLOWED_TAGS); 
                echo wp_kses(WPJOBPORTALformfield::hidden('draft', ''),WPJOBPORTAL_ALLOWED_TAGS); 

                echo wp_kses(WPJOBPORTALformfield::hidden('uid', esc_html(WPJOBPORTALincluder::getObjectClass('user')->uid())),WPJOBPORTAL_ALLOWED_TAGS);
                echo wp_kses(WPJOBPORTALformfield::hidden('created', isset(wpjobportal::$_data[0]->created) ? esc_html(wpjobportal::$_data[0]->created) : gmdate('Y-m-d H:i:s')),WPJOBPORTAL_ALLOWED_TAGS);
                echo wp_kses(WPJOBPORTALformfield::hidden('action', 'job_savejob'),WPJOBPORTAL_ALLOWED_TAGS); 
                echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportalpageid', get_the_ID()),WPJOBPORTAL_ALLOWED_TAGS);
                echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS);
                echo wp_kses(WPJOBPORTALformfield::hidden('upakid', isset(wpjobportal::$_data['package']) ? esc_html(wpjobportal::$_data['package']->id) : 0),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_job_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
                <input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo  esc_attr(wpjobportal::$_configuration['default_longitude']); ?>"/>
                <input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo esc_attr(wpjobportal::$_configuration['default_latitude']); ?>"/>
                <input type="hidden" id="edit_longitude" name="edit_longitude" value="<?php echo  isset(wpjobportal::$_data[0]->longitude) ? esc_attr(wpjobportal::$_data[0]->longitude) : ''; ?>"/>
                <input type="hidden" id="edit_latitude" name="edit_latitude" value="<?php echo  isset(wpjobportal::$_data[0]->latitude) ? esc_attr(wpjobportal::$_data[0]->latitude) : ''; ?>"/>
                <div class="wjportal-form-btn-wrp" id="save-button">                 
                    <?php
                        if (isset(wpjobportal::$_data[0]->id)) { // edit case form
                            echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')), array('class' => 'button wjportal-form-btn wjportal-save-btn')),WPJOBPORTAL_ALLOWED_TAGS);
                        } else { // new case form
                            echo "<div class='wjportal-form-2-btn'>". wp_kses(WPJOBPORTALformfield::button('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal')), array('class' => 'button wjportal-form-btn wjportal-save-btn','onclick'=>'submitjobform()')),WPJOBPORTAL_ALLOWED_TAGS) ."</div>";
                           if(!isset($company)){
                            // echo "<div class='wjportal-form-2-btn'>". wp_kses(WPJOBPORTALformfield::button('saveasdraft', esc_html(__('Save As Draft','wp-job-portal')), array('class' => 'button wjportal-form-btn wjportal-form-cancel-btn')),WPJOBPORTAL_ALLOWED_TAGS) ."</div>";
                           }
                        }
                    ?>
                </div>
        </form>
    </div>
<?php 
} else {
    echo wp_kses_post(wpjobportal::$_error_flag_message);
}
?>
    </div>
</div>
