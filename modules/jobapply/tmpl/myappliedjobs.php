 <?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
$labelflag = true;
$labelinlisting = wpjobportal::$_configuration['labelinlisting'];
if ($labelinlisting != 1) {
    $labelflag = false;
} ?>
<div class="wjportal-main-wrapper wjportal-clearfix">
    <div class="wjportal-page-header">
        <div class="wjportal-page-header-cnt">
            <?php
                WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'jobapply','layout' => 'myapplied'));
            ?>
        </div>
        <div id="my-applied-jobs-wrraper" class="wjportal-header-actions">
            <div class="wjportal-filter-wrp">
                <?php
                    $image1 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/sort-up.png";
                    $image2 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/sort-down.png";
                    if (isset(wpjobportal::$_data['sortby']) && wpjobportal::$_data['sortby'] == 1) {
                        $image = $image1;
                    } else {
                        $image = $image2;
                    }
                    $categoryarray = array(
                        (object) array('id' => 1, 'text' => esc_html(__('Job Title', 'wp-job-portal'))),
                        (object) array('id' => 2, 'text' => esc_html(__('Company Name', 'wp-job-portal'))),
                        (object) array('id' => 5, 'text' => esc_html(__('Location', 'wp-job-portal'))),
                        (object) array('id' => 7, 'text' => esc_html(__('Status', 'wp-job-portal'))),
                        (object) array('id' => 4, 'text' => esc_html(__('Job Type', 'wp-job-portal'))),
                        (object) array('id' => 6, 'text' => esc_html(__('Created', 'wp-job-portal'))),
                        (object) array('id' => 8, 'text' => esc_html(__('Salary', 'wp-job-portal')))
                    );
                    // resume filters
                     WPJOBPORTALincluder::getTemplate('jobapply/views/frontend/filter',array(
                        'sortbylist' => $categoryarray,
                        'layout' => 'myjobapplfilter',
                        'image' => $image,
                        'image1' => $image1,
                        'image2' => $image2
                    ));
                ?>
            </div>
        </div>
        <?php if(!WPJOBPORTALincluder::getTemplate('templates/header',array('module' => 'jobapply'))){
            return;
        } ?>
    </div>
    <?php if (isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0])) { ?>
            <form id="job_form" method="post" action="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'jobapply','wpjobportallt'=>'myappliedjobs'))); ?>">
                <div class="wjportal-jobs-list-wrapper wjportal-applied-jobs-wrp">
                    <?php
                        foreach (wpjobportal::$_data[0] AS $appliedJobs) {
                            WPJOBPORTALincluder::getTemplate('job/views/frontend/joblist',array('job'=>$appliedJobs,'labelflag'=>$labelflag,'control'=>'resumetitle'));
                        }
                    ?>
                </div>
                <?php
                    if (wpjobportal::$_data[1]) {
                        if(!WPJOBPORTALincluder::getTemplate('templates/pagination',array('module' => 'jobapply','pagination' => wpjobportal::$_data[1]))) {
                            return;
                        }
                    }
                    echo wp_kses(WPJOBPORTALformfield::hidden('sortby', wpjobportal::$_data['sortby']),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('sorton', wpjobportal::$_data['sorton']),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportallay', 'appliedjobs'),WPJOBPORTAL_ALLOWED_TAGS);
                ?>
          </form>

          <?php

        } else {
            WPJOBPORTALlayout::getNoRecordFound();
        }
    ?>
</div>

