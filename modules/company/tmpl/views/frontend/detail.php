<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param wp job portal 
* Company => Detail via Template 
* redirection's 
*/
$dateformat = wpjobportal::$_configuration['date_format'];

/**
* @param wp job portal
* # company list 
* generic module for cases
*/
$listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingDataForListing(1);
 if(in_array('multicompany', wpjobportal::$_active_addons)){
    $mod = "multicompany";
}else{
    $mod = "company";
} 
?>
<?php
switch ($layout) {
    case 'companydetail':
        ?>
        <div class="wjportal-company-data-wrp">
            <?php if (wpjobportal::$_data['companycontactdetail'] == true){?>
                <div class="wjportal-company-sec-title">
                    <?php echo esc_html(__('Company Info','wp-job-portal')); ?>
                </div>
            <?php } 
                $dateformat = wpjobportal::$_configuration['date_format'];
                $description_field_label = '';
                foreach (wpjobportal::$_data[2] AS $key => $val) {
                    switch ($key) {
                        case 'contactemail':
                            if (wpjobportal::$_data['companycontactdetail'] == true)
                                if ($config_array['comp_email_address'] == 1)
                                    if(isset( wpjobportal::$_data[0]) && !empty( wpjobportal::$_data[0]->contactemail)){
                                        echo wp_kses(getDataRow(wpjobportal::wpjobportal_getVariableValue($val), wpjobportal::$_data[0]->contactemail), WPJOBPORTAL_ALLOWED_TAGS);
                                    }
                                    
                            break;
                        case 'address1':
                            if (wpjobportal::$_data['companycontactdetail'] == true)
                                if(isset( wpjobportal::$_data[0]) && !empty( wpjobportal::$_data[0]->address1)){
                                    echo wp_kses(getDataRow(wpjobportal::wpjobportal_getVariableValue($val), wpjobportal::$_data[0]->address1), WPJOBPORTAL_ALLOWED_TAGS);
                                }
                            break;
                        case 'address2':
                            if (wpjobportal::$_data['companycontactdetail'] == true)
                                if(isset( wpjobportal::$_data[0]) && !empty( wpjobportal::$_data[0]->address2)){
                                    echo wp_kses(getDataRow(wpjobportal::wpjobportal_getVariableValue($val), wpjobportal::$_data[0]->address2), WPJOBPORTAL_ALLOWED_TAGS);
                                }
                            break;
                        default: // handle the user fields data
                            if($key == 'description'){// show description field based of field ordering & show dynamic field title
                                $description_field_label = $val;
                            }
                            $customfields = WPJOBPORTALincluder::getObjectClass('customfields')->userFieldsData(1);
                                foreach($customfields AS $field){
                                    if($key == $field->field){
                                        $showCustom =  wpjobportal::$_wpjpcustomfield->showCustomFields($field,5,wpjobportal::$_data[0]->params,'company',wpjobportal::$_data[0]->id);
                                        echo wp_kses($showCustom, WPJOBPORTAL_ALLOWED_TAGS);
                                    }
                                }
                           
                        break;
                    }
                }
            ?>
        </div>
        <?php 
        $config_array = wpjobportal::$_data['config'];
        if($description_field_label != '' && $config_array['comp_description'] == 1){
        ?>
            <div class="wjportal-company-data-wrp">
                <div class="wjportal-company-sec-title">
                    <?php echo esc_html( wpjobportal::wpjobportal_getVariableValue($description_field_label)); ?>
                </div>
                <div class="wjportal-company-desc">
                    <?php echo wp_kses(wpjobportal::$_data[0]->description, WPJOBPORTAL_ALLOWED_TAGS); ?>
                </div>
            </div>
        <?php } ?>
        <?php
            do_action('wpjobportal_addons_company_contact_detail',wpjobportal::$_data[0],wpjobportal::$_data['companycontactdetail']);
        break;
    case 'detail':
     $config_array = wpjobportal::$_data['config']; ?>
        <div class="wjportal-company-cnt-wrp">
            <div class="wjportal-company-middle-wrp">
                <?php if(isset($listing_fields['url']) && $listing_fields['url'] !='' ){ ?>
                    <?php if( $config_array['comp_show_url'] == 1): ?>
                            <div class="wjportal-company-data">
                                <span class="wjportal-companyname">
                                    <?php echo esc_html($company->url); ?>
                                </span>
                            </div>
                    <?php endif; ?>
                <?php } ?>

                <div class="wjportal-company-data"> 
                    <?php
                        if(empty(wpjobportal::$_data['shortcode_option_hide_company_name'])){
                            if (wpjobportal::$_config->getConfigValue('comp_name')) { ?>
                                <span class="wjportal-company-title">
                                    <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$company->aliasid))); ?>">
                                        <?php echo esc_html($company->name); ?>
                                    </a>
                                </span>
                                <?php
                            }
                        }
                        // to show featured tag on all companies layout
                        if(WPJOBPORTALincluder::getObjectClass('user')->isemployer() || (isset($companies_layout) && $companies_layout == 'companies')){
                            do_action('wpjobportal_addons_lable_comp_feature',$company);
                        }
                    ?>
                </div>
                <div class="wjportal-company-data">
                    <?php if(!isset($showcreated) || $showcreated): ?>
                        <div class="wjportal-company-data-text">
                            <span class="wjportal-company-data-title">
                                <?php echo esc_html(__('Created', 'wp-job-portal')) . ':'; ?>
                            </span>
                            <span class="wjportal-company-data-value">
                                <?php echo esc_html(human_time_diff(strtotime($company->created),strtotime(date_i18n("Y-m-d H:i:s")))).' '.esc_html(__("Ago",'wp-job-portal')); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if(WPJOBPORTALincluder::getObjectClass('user')->isemployer()){ ?>
                    <div class="wjportal-company-data-text">
                        <span class="wjportal-company-data-title">
                            <?php echo esc_html(__('Status', 'wp-job-portal')) . ':'; ?>
                        </span>
                        <?php
                            $color = ($company->status == 1) ? "green" : "red";
                            if ($company->status == 1) {
                                $statusCheck = esc_html(__('Approved', 'wp-job-portal'));
                            } elseif ($company->status == 0) {
                                $statusCheck = esc_html(__('Waiting for approval', 'wp-job-portal'));
                            }elseif($company->status == 2){
                                 $statusCheck = esc_html(__('Pending For Approval of Payment', 'wp-job-portal'));
                            }elseif ($company->status == 3) {
                                $statusCheck = esc_html(__('Pending Due To Payment', 'wp-job-portal'));
                            }else {
                                $statusCheck = esc_html(__('Rejected', 'wp-job-portal'));
                            }
                        ?>
                        <span class="wjportal-company-data-value <?php echo esc_attr($color); ?>">
                            <?php echo esc_html($statusCheck); ?>
                        </span>
                    </div>
                    <?php }
                    if(empty(wpjobportal::$_data['shortcode_option_hide_company_location'])){
                        if(isset($company) && !empty($company->location) && $config_array['comp_city'] == 1):
                             if(isset($listing_fields['city']) && $listing_fields['city'] !='' ){ ?>
                                <div class="wjportal-company-data-text">
                                    <span class="wjportal-company-data-title">
                                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['city'])) . ':'; ?>
                                    </span>
                                    <span class="wjportal-company-data-value">
                                        <?php echo esc_html($company->location); ?>
                                    </span>
                                </div><?php
                            }
                         endif;
                    } ?>
                </div>
                <!-- custom fields -->
                <div class="wjportal-custom-field-wrp">
                    <?php
                        $customfields = WPJOBPORTALincluder::getObjectClass('customfields')->userFieldsData(1,1);
                            foreach ($customfields as $field) {
                                $showCustom =  wpjobportal::$_wpjpcustomfield->showCustomFields($field,8,$company->params);
                                echo wp_kses($showCustom, WPJOBPORTAL_ALLOWED_TAGS);
                            }
                    ?>
                </div>
            </div>
            <div class="wjportal-company-right-wrp">
                <div class="wjportal-company-action">
                    <a class="wjportal-company-act-btn" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$company->aliasid))); ?>" title="<?php echo esc_html(__('View company','wp-job-portal')); ?>">
                        <?php echo esc_html(__('View Company','wp-job-portal')); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    break;
   }
