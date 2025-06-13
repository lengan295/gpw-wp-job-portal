<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php

// document title (<title> tag value)
// returning any non empty value stops the regular excution of code and prints this value as title
// 99 makes sure that this is executed last to avoid getting it overridden

// add_filter('pre_get_document_title', 'wp_job_portal_document_title', 99);

// function wp_job_portal_document_title($title) {
//     $companyid = isset(wpjobportal::$_data[0]->id) ? wpjobportal::$_data[0]->id : '';
//     if($companyid == ''){
//         return '';
//     }
//     $company_title_options = get_option('wpjobportal_company_document_title_settings');
//     $company_document_title = WPJOBPORTALincluder::getJSModel('company')->makeCompanySeoDocumentTitle($company_title_options , $companyid);
//     return $company_document_title;
// }

    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";

        function getPackagePopup(resumeid) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            var jppage_id = ". wpjobportal::wpjobportal_getPageid() .";
            // this was multicompany addon dependent
            jQuery.post(ajaxurl, {action: 'wpjobportal_ajax', wpjobportalme: 'company', task: 'getPackagePopupForCompanyContactDetail', wpjobportalid: resumeid, '_wpnonce':'". esc_attr(wp_create_nonce("get-package-popup-for-company-contact-detail"))."',wpjobportalpageid:jppage_id}, function (data) {
                if (data) {
                    if(jQuery('#package-popup').length)
                    jQuery('#package-popup').remove();
                    jQuery('body').append(data);
                    jQuery('#wjportal-popup-background').show();
                    jQuery('#package-popup').slideDown('slow');

                } else {
                    jQuery('div.logo-container').append(\"<span style='color:Red;'>". esc_html(__('Error While Adding Feature job', 'wp-job-portal'))."\");
                }
            });
        }


        function selectPackage(packageid){
            jQuery('.package-div').css('border','1px solid #ccc');
            jQuery('.wjportal-pkg-item, .wpj-jp-pkg-item').removeClass('wjportal-pkg-selected');
            jQuery('#package-div-'+packageid).addClass('wjportal-pkg-selected');
            jQuery('#wpjobportal_packageid').val(packageid);
            jQuery('#jsre_featured_button').removeAttr('disabled');
        }

        jQuery(document).ready(function(){

        jQuery('#proceedPaymentBtn').click(function(){
            jQuery('div#wjportal-popup-background').show();
            jQuery('#payment-popup').slideDown('slow');
        });
        // added the below code to make sure the popup works smootly for job portal theme
        jQuery('#proceedPaymentBtn').click(function(){
            jQuery('div#'+common.theme_chk_prefix+'-popup-background').show();
            jQuery('#payment-popup').slideDown('slow');
        });
        jQuery('div#'+common.theme_chk_prefix+'-popup-background, .'+common.theme_chk_prefix+'-popup-close-icon').click(function(){
            jQuery('div#wjportal-popup-background').hide();
            jQuery('div#'+common.theme_chk_prefix+'-popup-background').hide();
            jQuery('#payment-popup').slideUp('slow');
        });

    });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
