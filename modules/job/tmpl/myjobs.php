<?php
if (!defined('ABSPATH')) die('Restricted Access');
    $labelflag = true;
    $labelinlisting = wpjobportal::$_configuration['labelinlisting'];
    if ($labelinlisting != 1)
        $labelflag = false;
    ?>
    <div class="wjportal-main-up-wrapper">
    <div class="wjportal-main-wrapper wjportal-clearfix">
        <div class="wjportal-page-header">
            <div class="wjportal-page-header-cnt">
                <?php
                WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'myjob','layout' => 'myjob'));
                ?>
            </div>
            <?php if(wpjobportal::$_error_flag == null){ ?>
                    <div class="wjportal-header-actions">
                    <?php
                    #Search List
                        $categoryarray = array(
                            (object) array('id' => 1, 'text' => esc_html(__('Job Title', 'wp-job-portal'))),
                            (object) array('id' => 2, 'text' => esc_html(__('Company Name', 'wp-job-portal'))),
                            (object) array('id' => 3, 'text' => esc_html(__('Category', 'wp-job-portal'))),
                            (object) array('id' => 5, 'text' => esc_html(__('Location', 'wp-job-portal'))),
                            (object) array('id' => 7, 'text' => esc_html(__('Status', 'wp-job-portal'))),
                            (object) array('id' => 4, 'text' => esc_html(__('Job Type', 'wp-job-portal'))),
                            (object) array('id' => 6, 'text' => esc_html(__('Created', 'wp-job-portal'))),
                            (object) array('id' => 8, 'text' => esc_html(__('Salary', 'wp-job-portal')))
                        );
                    //Filter js-job
                        $image1 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/sort-up.png";
                        $image2 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/sort-down.png";
                        if (wpjobportal::$_data['sortby'] == 1) {
                            $image = $image1;
                        } else {
                            $image = $image2;
                        }
                        WPJOBPORTALincluder::getTemplate('job/views/frontend/filter',array(
                            'sortbylist' => $categoryarray,
                            'layout' => 'myjobfilter',
                            'image' => $image,
                            'image1' => $image1,
                            'image2' => $image2
                        ));
                    ?>
                    </div>
            <?php } ?>
            <?php
            if ( !WPJOBPORTALincluder::getTemplate('templates/header',array('module' => 'job')) ) {
                return;
            }
            ?>
        </div>
        <?php if (wpjobportal::$_error_flag == null) { ?>
                <div class="wjportal-jobs-list-wrapper wjportal-my-jobs-wrp">
                    <form id="job_form" method="post" action="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job','wpjobportallt'=>'myjobs'))); ?>">
                        <?php
                            if (!empty(wpjobportal::$_data[0])) {
                                foreach (wpjobportal::$_data[0] AS $job) {
                                    # Template For Job View
                                    WPJOBPORTALincluder::getTemplate('job/views/frontend/joblist',array(
                                        'job' => $job,
                                        'labelflag' => $labelflag,
                                        'control' => 'myjobs'
                                    ));
                                }
                                # pagination Template
                                if (wpjobportal::$_data[1]) {
                                    WPJOBPORTALincluder::getTemplate('templates/pagination',array('module' => 'job','pagination' => wpjobportal::$_data[1]));
                                }
                                echo wp_kses(WPJOBPORTALformfield::hidden('sortby', wpjobportal::$_data['sortby']),WPJOBPORTAL_ALLOWED_TAGS);
                                echo wp_kses(WPJOBPORTALformfield::hidden('sorton', wpjobportal::$_data['sorton']),WPJOBPORTAL_ALLOWED_TAGS);
                                echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS);
                                echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportallay', 'appliedjobs'),WPJOBPORTAL_ALLOWED_TAGS);
                        ?>
                    </form>
                </div>
        <?php
            } else {
                $msg = esc_html(__('No record found','wp-job-portal'));
                $linkmyjobs[] = array(
                    'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'addjob')),
                    'text' => esc_html(__('Add New','wp-job-portal')) .' '. esc_html(__('Job', 'wp-job-portal'))
                );
                WPJOBPORTALLayout::getNoRecordFound($msg,$linkmyjobs);
            }
        ?>
    </div>
<?php
} else {
    if(wpjobportal::$_error_flag_message != ''){
        echo wp_kses_post(wpjobportal::$_error_flag_message);
    }
}
?>
</div>
