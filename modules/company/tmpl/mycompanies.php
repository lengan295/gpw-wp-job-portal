<?php
if (!defined('ABSPATH')) die('Restricted Access');

$companies = isset(wpjobportal::$_data[0]) && is_array(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : array();
?>
<div class="wjportal-main-up-wrapper">
<div class="wjportal-main-wrapper wjportal-clearfix">
    <div class="wjportal-page-header">
        <div class="wjportal-page-header-cnt">
            <?php 
                if (!WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'company','layout' => 'mycompany'))){
                    return;
                }
                
            ?>
        </div>
        <div class="wjportal-header-actions">
            <div class="wjportal-act-btn-wrp">
                <?php
                if(wpjobportal::$_error_flag == null && WPJOBPORTALincluder::getObjectClass('user')->isemployer()){
                    if(in_array('multicompany',wpjobportal::$_active_addons)){
                        echo '<a class="wjportal-act-btn" href='.esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'addcompany'))).'><i class="fa fa-plus"></i>'.esc_html(__('Add New','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')) .'</a> ';
                    }else{
                        echo '<a class="wjportal-act-btn" href='.esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'addcompany'))).'><i class="fa fa-plus"></i>'.esc_html(__('Add New','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal')) .'</a> ';
                    }
                }
                ?>
            </div>
        </div>
        <?php
        if ( !WPJOBPORTALincluder::getTemplate('templates/header',array('module' => 'company')) ) {
                return;
        }
        ?>
    </div>
    <div class="wjportal-company-list-wrapper wjportal-my-company-wrp">
        <?php
        if (!empty($companies)) {
            //////******Data Listing*********//////
            foreach ($companies AS $company) {
               WPJOBPORTALincluder::getTemplate('company/views/frontend/companylist', array(
                   'company' => $company,
                   'module' => 'company',
                   'layout' => 'control'
                ));
            }
            ///**pagination Calling*******///
            if (wpjobportal::$_data[1]) {
                WPJOBPORTALincluder::getTemplate('templates/pagination',array(
                    'pagination' => wpjobportal::$_data[1],
                    'module' => 'multicompany'
                ));
            }
        }else{
            $msg = esc_html(__('No record found','wp-job-portal'));
            $linkcompany[] = array(
                'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'addcompany')),
                'text' => esc_html(__('Add New','wp-job-portal')) .' '. esc_html(__('Company', 'wp-job-portal'))
            );
            WPJOBPORTALlayout::getNoRecordFound($msg, $linkcompany);
        }
        ?>
    </div>
</div>
</div>
