<?php
if (!defined('ABSPATH')) die('Restricted Access');
$company = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
?>
<div class="wjportal-main-up-wrapper">
<div class="wjportal-main-wrapper wjportal-clearfix">
    <div class="wjportal-page-header">
        <?php
        WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'company' , 'layout' => 'companyinfo','company'=>$company));
        if ( !WPJOBPORTALincluder::getTemplate('templates/header',array('module' => 'company'))) {
                return;
            }
        ?>
    </div>
    <div class="wjportal-form-wrp wjportal-add-company-form">
        <?php
            $userinfo = WPJOBPORTALincluder::getObjectClass('user')->getEmployerProfile();
            if(isset(wpjobportal::$_data['package']) && in_array('credits', wpjobportal::$_active_addons) ){
                # package change
                WPJOBPORTALincluder::getTemplate('company/views/packages',array('module' => 'company','packages'=>wpjobportal::$_data['package']));
            }
        ?>
        <form class="wjportal-form" id="wpjobportal-form" method="post" enctype="multipart/form-data" action="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'task'=>'savecompany'))); ?>">
            <?php
                $formfields = WPJOBPORTALincluder::getTemplate('company/form-fields', array(
                    'company' => $company,
                    'userinfo' => $userinfo
                ));
                foreach ($formfields as $formfield) {
                    WPJOBPORTALincluder::getTemplate('templates/form-field', $formfield);
                }
                $termsandconditions_flag = 0;
                if(isset(wpjobportal::$_data[2])){
                    foreach (wpjobportal::$_data[2] AS $field) {
                        switch ($field->field) {
                            case 'termsandconditions':
                            if(!isset(wpjobportal::$_data[0])){
                                $termsandconditions_flag = 1;
                                $termsandconditions_fieldtitle = $field->fieldtitle;
                                $termsandconditions_link = get_the_permalink(wpjobportal::$_configuration['terms_and_conditions_page_company']);
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
                        <img alt="<?php echo esc_html(__('Terms and Conditions','wp-job-portal')); ?>" title="<?php echo esc_html(__('Terms and Conditions','wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/widget-link.png'; ?>" /></a>
                    </div>
                <?php }
        	?>
            <div class="wjportal-form-btn-wrp">
                <?php echo wp_kses(WPJOBPORTALformfield::submitbutton('save', esc_html(__('Save','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')), array('class' => 'button wjportal-form-btn wjportal-save-btn','onclick'=>"submitresume()")),WPJOBPORTAL_ALLOWED_TAGS); ?>
            </div>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('id', $company ? esc_html($company->id) : '' ),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportalpageid', get_the_ID()),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('form_request', 'wpjobportal'),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('created', isset(wpjobportal::$_data[0]->created) ? esc_html(wpjobportal::$_data[0]->created) : gmdate('Y-m-d H:i:s')),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <?php echo wp_kses(WPJOBPORTALformfield::hidden('_wpnonce', esc_html(wp_create_nonce('wpjobportal_company_nonce'))),WPJOBPORTAL_ALLOWED_TAGS); ?>
            <?php
                if(in_array('credits', wpjobportal::$_active_addons)){
                 echo wp_kses(WPJOBPORTALformfield::hidden('upakid', isset(wpjobportal::$_data['package']) ? esc_html(wpjobportal::$_data['package']->id) : 0),WPJOBPORTAL_ALLOWED_TAGS);
                }
            ?>
        </form>
    </div>
</div>
</div>
