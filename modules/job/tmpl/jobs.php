<?php
if (!defined('ABSPATH')) die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
if ( !WPJOBPORTALincluder::getTemplate('templates/header',array('module' => 'job')) ) {
    return;
}

$jobs = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0] : null;
$labelflag = true;
$labelinlisting = wpjobportal::$_configuration['labelinlisting'];
if ($labelinlisting != 1)
    $labelflag = false;
?>
<div class="wjportal-main-wrapper wjportal-clearfix">
    <div class="wjportal-page-header">
        <?php
            if(!WPJOBPORTALincluder::getTemplate('templates/pagetitle', array('module' => 'job', 'layout' => 'newestjob' ))){
                return;
            }
        ?>
    </div>
    <div class="wjportal-newest-jobs">
        <?php
        $job_list_ai_filter = wpjobportal::$_config->getConfigValue('job_list_ai_filter');
        $ai_extra_cls = "";
        if($job_list_ai_filter != 0){ // show job filter without ai
            $ai_extra_cls = "wjportal-filter-aisearch-main-wrp";
        }
        if(empty(wpjobportal::$_data['shortcode_option_hide_filter'])){ ?>
            <div class="wjportal-filter-search-main-wrp  <?php echo esc_attr($ai_extra_cls);?>">
                <form class="wjportal-form-wrp" id="job_form" method="post" action="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs'))); ?>">
                    <?php
                        if($job_list_ai_filter == 0){ // show job filter without ai
                            WPJOBPORTALincluder::getTemplate('job/views/frontend/filter',array('layout' => 'newestjobsfilter'));
                        }else{
                            WPJOBPORTALincluder::getTemplate('job/views/frontend/filter',array('layout' => 'aijobfilter'));
                        }
                    ?>
                </form>
            </div>
        <?php } ?>
        <div class="wjportal-jobs-list-wrapper">
            <?php
                if (!empty($jobs)) {
                    foreach ($jobs AS $job) {
                        WPJOBPORTALincluder::getTemplate('job/views/frontend/joblist', array(
                            'job' => $job,
                            'labelflag' => $labelflag,
                            'control' => ''
                        ));
                    }

                    if(empty(wpjobportal::$_data['shortcode_option_no_of_jobs'])){ // if no_of_jobs is set in shortcode dont show pagiantion
                        if (wpjobportal::$_data[1]) {
                            WPJOBPORTALincluder::getTemplate('templates/pagination',array(
                                'pagination' => wpjobportal::$_data[1],
                                'module' => 'job'
                            ));
                        }
                    }
                } else {
                    $msg = esc_html(__('No record found','wp-job-portal'));
                    WPJOBPORTALlayout::getNoRecordFound($msg);
                }
            ?>
        </div>
    </div>
</div>
</div>
