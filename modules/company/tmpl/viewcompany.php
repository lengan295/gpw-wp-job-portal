<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
if ( !WPJOBPORTALincluder::getTemplate('templates/header', array('module' => 'company')) ) {
    return;
}
//get Company id && Company Name

$companyname = isset(wpjobportal::$_data[0]->name) ? wpjobportal::$_data[0]->name : '';
$companyid = isset(wpjobportal::$_data[0]->id) ? wpjobportal::$_data[0]->id : '';
if (wpjobportal::$_error_flag == null) {
    function getDataRow($title, $value) {
        $html = '<div class="wjportal-company-data">
                    <span class="wjportal-company-data-tit">' . $title . ':</span>
                    <span class="wjportal-company-data-val">' . $value . '</span>
                </div>';
        return $html;
    }
    $data_class = (isset(wpjobportal::$_data[2]['logo'])) ? 'two_column' : 'one_column';
    $config_array = wpjobportal::$_data['config'];
    ?>
    <div class="wjportal-main-wrapper wjportal-clearfix">
        <div class="wjportal-page-header">
            <?php
                WPJOBPORTALincluder::getTemplate('templates/pagetitle', array('module' => 'company', 'layout' => 'company','data' => wpjobportal::$_data[0],'config_array' => $config_array));
            ?>
        </div>
        <div class="wjportal-companydetail-wrapper">
            <?php
            /**
            * @param Details Section For Company View
            * @param config => admin Configuration
            **/
            WPJOBPORTALincluder::getTemplate('company/views/frontend/viewcompany',array(
                'config_array' => $config_array,
                'data_class' => $data_class,
                'module' => 'company',
                'config_array' => wpjobportal::$_data['config']
            ));
            if (WPJOBPORTALincluder::getObjectClass('user')->isemployer() == 0) { ?>
                <div class="wjportal-job-btn-wrp">
                    <?php $compalias = wpjobportal::$_data[0]->alias.'-'.wpjobportal::$_data[0]->id; ?>
                </div>
            <?php } ?>
            <?php 
                if(in_array('credits', wpjobportal::$_active_addons)){
                    if(wpjobportal::$_config->getConfigValue('submission_type') == 2){
                        $paymentconfig = wpjobportal::$_data['paymentconfig'];
                        $price = wpjobportal::$_config->getConfigValue('job_viewcompanycontact_price_perlisting');
                        $currencyid = wpjobportal::$_config->getConfigValue('job_currency_viewcompanycontact_perlisting');
                        $decimals = WPJOBPORTALincluder::getJSModel('currency')->getDecimalPlaces($currencyid);
                        $formattedPrice = wpjobportalphplib::wpJP_number_format($price,$decimals);
                        //Price Listing For Department
                        $priceCompanytlist = wpjobportal::$_common->getFancyPrice($price,$currencyid,array('decimal_places'=>$decimals));
                        //Apply Filter's
                        do_action('wpjobportal_addons_perlisting_payment',$paymentconfig,$companyid,'listingpaypalCompanyView','job_viewcompanycontact_price_perlisting','listingCompanyViewstripe','companytitle',$companyname,$price,$currencyid,'Department',wpjobportal::wpjobportal_getPageid());
                    }
                }
            ?>
        </div>
    </div>
        <?php 
        } else {
            // Error Message Throw
            if(wpjobportal::$_error_flag_message !=''){
                echo wp_kses_post(wpjobportal::$_error_flag_message);
            }
        }
        ?>
</div>
