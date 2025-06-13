<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
$listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingDataForListing(3);
?>
<div class="wjportal-resume-cnt-wrp">
    <div class="wjportal-resume-middle-wrp">
        <div class="wpjp-resume-name padding">
            <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$myresume->aliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))); ?>">
                    <?php echo esc_html($myresume->first_name) . ' ' . esc_html($myresume->last_name); ?>
            </a>
        </div>
        <?php
	        $dateformat = wpjobportal::$_configuration['date_format'];
	        $curdate = date_i18n($dateformat);
            do_action('wpjobportal_addons_feature_resume_label',$myresume);
        ?>
        <div class="wpjp-job-resume-title-wrp padding">
            <?php if($myresume->application_title != ''){ ?>
                <span class="wpjp-resume-title">
                    <?php echo '(' . esc_html($myresume->application_title) . ')'; ?>
                </span>
            <?php } ?>
        </div>
        <div class="wpjp-resume-info-wrp padding">
            <div class="wpjp-resume-info">
               <!-- <span class="js-bold"><?php //echo esc_html(__('Category', 'wp-job-portal')) . ': '; ?></span>
               <span class="get-text"><?php //echo esc_html(wpjobportal::wpjobportal_getVariableValue($myresume->cat_title)); ?></span>
           </div>  -->
        <?php if(isset($listing_fields['salaryfixed'])){ ?>
            <div class="wpjp-resume-info">
                <span class="wpjp-text">
                    <?php echo esc_html( wpjobportal::wpjobportal_getVariableValue($listing_fields['salaryfixed'])) . ': '; ?>
                </span>
                <span class="wpjp-value">
                    <?php echo esc_html($myresume->salary); ?>
                </span>
            </div>
        <?php } ?>

        <?php if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){// same as some other files(exprince is calculated from the employers section) ?>
            <div class="wpjp-resume-info">
                <span class="wpjp-text">
                    <?php echo esc_html(__('Total Experience', 'wp-job-portal')) . ': '; ?>
                </span>
                <span class="wpjp-value">
                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue(WPJOBPORTALincluder::getJSModel('common')->getTotalExp($myresume->id)));?>
                </span>
            </div>
        <?php } ?>
        <?php if(isset($listing_fields['address_city'])){ ?>

            <div class="wpjp-resume-info">
                <span class="wpjp-text">
                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($listing_fields['address_city'])) . ': '; ?>
                </span>
                 <?php if ($myresume->location != '') { ?>
                    <span class="wpjp-value">
                        <?php echo esc_html($myresume->location); ?>
                    </span>
                <?php } ?>
            </div>
        <?php } ?>
            <?php
                // custom fiedls
                $customfields = apply_filters('wpjobportal_addons_get_custom_field',false,3,1,1);
                // if(in_array('customfield', wpjobportal::$_active_addons)){
                    foreach ($customfields as $field) {
                        $showCustom =  apply_filters('wpjobportal_addons_show_customfields_params',false,$field,10,$myresume->params);
                        echo esc_attr($showCustom);
                    }
                // }
            ?>
        </div>
    </div>
    <?php if($myresume->status != 3 && $myresume->isfeaturedresume = NULL ){ ?>
    	<div class="wpjp-resume-right padding">
            <div class="wpjp-view-resume-button">
                <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$myresume->aliasid, 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid()))); ?>">
                    <?php echo esc_html(__('View Resume', 'wp-job-portal')); ?>
                </a>
            </div>
        </div>
    <?php } ?>
    <?php if (($myresume->status == 3 || $myresume->isfeaturedresume == NULL) && in_array('credits',wpjobportal::$_active_addons) && !isset(wpjobportal::$_data['isdata'])) {
              do_action('wpjobportal_addons_makePayment_for_department',$myresume,'payresume');
        } elseif (($myresume->status == 3 || $myresume->isfeaturedresume == NULL ) && isset(wpjobportal::$_data['isdata'])) {
            if(in_array('multiresume', wpjobportal::$_active_addons)){
                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multiresume', 'wpjobportallt'=>'myresumes','wpjobportalpageid' =>wpjobportal::wpjobportal_getPageid()));
            }else{
                $link = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'myresumes','wpjobportalpageid' =>wpjobportal::wpjobportal_getPageid()));
            }
            ?>
            <div class="wpjp-bottom-action-link">
                <a class="wpjp-action-link" href="<?php echo esc_url($link); ?>">
                    <?php echo esc_html(__('Cancel Payment', 'wp-job-portal')); ?>
                </a>
                <button type="button" class="wpjobportal-property-list-fw-action-btn-link wpjobportal-prop-view-btn" id="proceedPaymentBtn">
                    <?php echo esc_html(__('Proceed To Payment','wp-job-portal')); ?>
                </button>
            </div>
        <?php
         }
        ?>
</div>


