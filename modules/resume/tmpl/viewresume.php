<?php
if (!defined('ABSPATH'))
die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
$printResume = WPJOBPORTALrequest::getVar('wpjobportallt');
if ($printResume == 'printresume')
    wp_head();
// Template header calling per module **Module => resume...
if(!WPJOBPORTALincluder::getTemplate('templates/header',array('module' => 'resume'))){
    return;
}
if(! wpjobportal::$_common->wpjp_isadmin()){
    if(!WPJOBPORTALincluder::getTemplate('templates/admin/header',array('module' => 'resume'))){
        return;
    }  
}
$resumeid = isset(wpjobportal::$_data[0]['personal_section']->id) ? wpjobportal::$_data[0]['personal_section']->id :'';
if (wpjobportal::$_error_flag == null) {
    $resumeviewlayout = WPJOBPORTALincluder::getObjectClass('resumeviewlayout');
    $name = wpjobportal::$_data[0]['personal_section']->first_name .  ' ' . wpjobportal::$_data[0]['personal_section']->last_name;
    ?>
    <div class="wjportal-main-wrapper wjportal-clearfix">
        <div id="full_background" style="display:none;"></div>
        <div class="wjportal-page-header">
            <?php 
                /**
                * @param get Template Method
                * Page Title For Module 
                * with Data Heading's
                **/
                WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'resume','layout' =>'viewresume','name' =>$name)); 
            ?>
        </div>
        <?php do_action('wpjobportal_addons_popup_main_outer_resume',wpjobportal::$_data[0]['personal_section']); ?>
        <?php
            if (isset(wpjobportal::$_data['socialprofile']) && wpjobportal::$_data['socialprofile'] == true) { // social profile
                $profileid = wpjobportal::$_data['socialprofileid'];
                WPJOBPORTALincluder::getObjectClass('socialmedia')->showprofilebyprofileid($profileid);
            } else {
                /**
                * @param get Template Method
                * recalling files redirection
                * Module resume Frontend / view resume
                **/
               WPJOBPORTALincluder::getTemplate('resume/views/frontend/viewresume',array(
                    'resumeviewlayout' => $resumeviewlayout,
               ));
            }
        ?>
        <?php 
            if(in_array('credits', wpjobportal::$_active_addons)){
                if(wpjobportal::$_config->getConfigValue('submission_type') == 2){
                    $paymentconfig = wpjobportal::$_data['paymentconfig'];
                    $price = wpjobportal::$_config->getConfigValue('job_viewresumecontact_price_perlisting');
                    $currencyid = wpjobportal::$_config->getConfigValue('job_currency_viewresumecontact_perlisting');
                    $decimals = WPJOBPORTALincluder::getJSModel('currency')->getDecimalPlaces($currencyid);
                    $formattedPrice = wpjobportalphplib::wpJP_number_format($price,$decimals);
                    // Fantacy price To calculate overplaces Amount's
                    $priceCompanytlist =wpjobportal::$_common->getFancyPrice($price,$currencyid,array('decimal_places'=>$decimals));
                    /**
                    * @param wp job portal wp hooks
                    * To redirect and check listing Price
                    *   Pay for Resume    
                    **/
                    do_action('wpjobportal_addons_perlisting_payment',$paymentconfig,$resumeid,'listingpaypalResumeView','job_viewresumecontact_price_perlisting','listingResumeViewstripe','resumetitle',wpjobportal::$_data[0]['personal_section']->application_title,$price,$currencyid,'Resume');
                }
            } ?>
    </div>
<?php 
} else {
    // Through Error Flag's Over the Page
    echo wp_kses_post(wpjobportal::$_error_flag_message);
} 
?>
</div>
