<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
wpjobportal::$_data['resumeid'] = isset(wpjobportal::$_data['resumeid']) ? wpjobportal::$_data['resumeid'] : '';
echo wp_kses(WPJOBPORTALformfield::hidden('resume_temp', wpjobportal::$_data['resumeid']),WPJOBPORTAL_ALLOWED_TAGS);
    ?>
    <div id="resume-wating" class="loading"></div>
    <div id="black_wrapper_jobapply" style="display:none;"></div>
    <div id="warn-message" style="display: none;">
        <span class="close-warnmessage"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/close-icon.png" /></span>
        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/warning-icon.png" />
        <span class="text"></span>
    </div>
    <div id="resume-files-popup-wrapper" class="wpjp-resume-popup-wrp"style="display:none;">
        <span class="close-resume-files">
            <?php echo esc_html(__('Resume Files', 'wp-job-portal')); ?>
            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/popup-close.png" />
        </span>
        <div class="wpjp-resumepopup-section-wrapper">
            <span class="wpjp-clickable-files"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/resume/select-file.png"/><?php echo esc_html(__('Select files', 'wp-job-portal')); ?></span>
            <span class="clickablefiles"><?php echo esc_html(__('Selected files', 'wp-job-portal')); ?></span>
            <span id="resume-files-selected"><?php echo esc_html(__('No file selected', 'wp-job-portal')); ?></span>
            <div class="wpjp-resume-filepopup-lowersection-wrapper">
                <div class="allowedfiles"><?php echo esc_html(__('Files allowed', 'wp-job-portal')) . ' ( ' . esc_html(wpjobportal::$_config->getConfigurationByConfigName('document_max_files')) . ' )'; ?></div>
                <div class="allowedextension">( <?php echo esc_html(wpjobportal::$_config->getConfigurationByConfigName('document_file_type')); ?> )</div>
                <div class="allowedsize"><?php echo esc_html(__('Maximum file size', 'wp-job-portal')) . ' ( ' . esc_html(wpjobportal::$_config->getConfigurationByConfigName('document_file_size')) . ' KB )'; ?></div>
            </div>
        </div>
    </div>
    <div class="wjportal-main-wrapper wjportal-clearfix" <?php if (isset($_COOKIE['wpjobportal_apply_visitor'])) echo 'style="padding-bottom:63px;"'; ?>>
        <?php $msg = isset(wpjobportal::$_data[0]) ? esc_html(__('Edit Resume', 'wp-job-portal')) : esc_html(__('Add New Resume', 'wp-job-portal'));
            if(isset($_COOKIE['wpjobportal_apply_visitor'])){
                if (!is_user_logged_in()) {
                    $msg = esc_html(__('Job Apply', 'wp-job-portal'));
                }
            }
        ?>
        <div class="wjportal-page-header">
            <?php
               WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'resume','layout'=>'myresume','msg'=> $msg));
                if(in_array('credits', wpjobportal::$_active_addons) && wpjobportal::$_config->getConfigValue('submission_type') ==3 && isset(wpjobportal::$_data['package'])){
                    # Package Inclder
                    do_action('wpjobportal_addons_module_packagesdetail',wpjobportal::$_data['package'],'multiresume');
                }
            ?>
        </div>
        <?php
        if ( !WPJOBPORTALincluder::getTemplate('templates/header',array('module' => 'resume'))) {
            return;
        }
        ?>
        <?php
        if(isset($_COOKIE['wpjobportal_apply_visitor']) && in_array('visitorapplyjob', wpjobportal::$_active_addons)){
            if (!is_user_logged_in()) {
                $visitorJobInfo = apply_filters('wpjobaddnos_visitor_apply_job',false,sanitize_key($_COOKIE['wpjobportal_apply_visitor']),wpjobportal::$_data['jobinfo'],wpjobportal::$_configuration['labelinlisting']);
            }
        }
        ////***For Calling Input Parameter***///
            $layouts="resumeformlayout";
            WPJOBPORTALincluder::getTemplate('resume/form-field',$layout=array(
                'layouts' => $layouts
            ));
        ?>
    </div>
    <div id="ajax-loader" style="display:none"><img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/loading.gif"></div>
<?php
?>
</div>
