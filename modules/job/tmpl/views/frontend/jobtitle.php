<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param wpjob portal      job object - optional
*/
?>
<!-- Popup Loading For Job Apply -->
<div id="wjportal-popup-background"></div>
<div id="wjportal-listpopup" class="wjportal-popup-wrp">
    <div class="wjportal-popup-cnt">
        <img id="wjportal-popup-close-btn" alt="popup cross" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/popup-close.png">
        <div class="wjportal-popup-title">
            <span class="wjportal-popup-title2"></span>
        </div>
        <div class="wjportal-popup-contentarea"></div>
    </div>
</div>
<!-- Popup Ends there -->
<!-- Page Title View Job  -->
<div class="wjportal-page-header">
    <?php WPJOBPORTALincluder::getTemplate('templates/pagetitle',array(
        'module' => 'job'
        ,'layout' => 'jobdetail',
        'jobtitle' => $job->title
    )); ?>
</div>
<!-- Page Title Ends there -->

<div class="wjportal-jobdetail-wrapper">
    <?php
    /**
    * @param template redirection 
    * Frontend => detail with icon
    * # case Upper detail =>job_seeker
    **/
        WPJOBPORTALincluder::getTemplate('job/views/frontend/title',array(
            'job' => $job,
            'layout' =>'job_seeker'
        ));
    ?>
    <div class="wjportal-company-job-applyfrm-wrp">
        <div class="wjportal-company-job-applyfrm-leftwrp">
            <div class="wjportal-job-company-wrp">
                <?php
                /**
                * @param template redirection 
                * Frontend => file logo
                * # case logo
                **/
                    WPJOBPORTALincluder::getTemplate('job/views/frontend/logo',array(
                        'job' => $job,
                        'layout' => 'logo'
                    ));
                ?>
                <div class="wjportal-job-company-cnt">
                    <div class="wjportal-job-company-info">
                        <?php 
                        if(in_array('multicompany', wpjobportal::$_active_addons)){
                            $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyid));
                        }else{
                            $url = wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'company', 'wpjobportallt'=>'viewcompany', 'wpjobportalid'=>$job->companyid));
                        }
                        ?>
                        <?php if (wpjobportal::$_config->getConfigValue('comp_name')) { ?>
                            <a class="wjportal-job-company-name" href="<?php echo esc_url($url);?>">
                                <?php echo esc_html($job->companyname); ?>
                            </a>
                        <?php }?>
                    </div>
                    <?php
                    $comapny_listing_fields = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldOrderingData(1);
                    if(isset($job) && !empty($job->companyemail)) :
                        $config_array = wpjobportal::$_data['config'];
                        if ($config_array['comp_email_address'] == 1) :
                            if(isset($comapny_listing_fields['contactemail']) && $comapny_listing_fields['contactemail'] !='' ){
                    ?>
                                <div class="wjportal-job-company-info">
                                    <span class="wjportal-job-company-info-tit"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($comapny_listing_fields['contactemail'])); ?>:</span>
                                    <span class="wjportal-job-company-info-val"><?php echo esc_html($job->companyemail); ?></span>
                                </div>
                        <?php } ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php
                        if(isset($comapny_listing_fields['url']) && $comapny_listing_fields['url'] !='' ){
                            if(isset($job) && !empty($job->url)) :?>
                                <div class="wjportal-job-company-info">
                                    <span class="wjportal-job-company-info-tit"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($comapny_listing_fields['url'])); ?>:</span>
                                    <span class="wjportal-job-company-info-val"><?php echo esc_html($job->companyurl); ?></span>
                                </div>
                    <?php endif; ?>
                    <?php } ?>

                    
                    <div class="wjportal-job-company-btn-wrp">
                        <?php
                        /**
                        * @param template redirection 
                        * Frontend => View Body Data 
                        * # case apply lower btn
                        **/
                            WPJOBPORTALincluder::getTemplate('job/views/frontend/title',array(
                                'job' => $job,
                                'layout' => 'apply1'
                            ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="wjportal-job-data-wrp">
                <?php
                /**
                * @param template redirection 
                * Frontend => View Body Data 
                * # case detail body
                **/
                    WPJOBPORTALincluder::getTemplate('job/views/frontend/title',array(
                        'job' => $job,
                        'jobfields' => $jobfields,
                        'layout' => 'detailbody'
                    ));
                ?> 
            </div>
            <div class="wjportal-job-btn-wrp">
                <?php
                /**
                * @param template redirection 
                * Frontend => View btn Job View 
                * # case apply
                **/
                    // design upgraded
                    // WPJOBPORTALincluder::getTemplate('job/views/frontend/title',array(
                    //     'job' => $job,
                    //     'layout' => 'apply'
                    // ));
                ?>
            </div>
        </div>
        <?php
        // job apply form
                WPJOBPORTALincluder::getTemplate('job/views/frontend/jobapply', array('job' => $job));
        ?>
    </div>
</div>
