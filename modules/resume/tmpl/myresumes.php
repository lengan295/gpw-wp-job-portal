<?php
if (!defined('ABSPATH'))
die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
wp_enqueue_style('status-graph', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/status_graph.css');
$subtype = wpjobportal::$_config->getConfigValue('submission_type');
?>
<div class="wjportal-main-wrapper wjportal-clearfix">
        <div class="wjportal-page-header">
            <div class="wjportal-page-header-cnt">
                <?php WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module' => 'resume','layout' => 'multiresumeadd'));
                ?>
            </div>
            <?php if (wpjobportal::$_error_flag == null) { ?>
            <div class="wjportal-header-actions">
                <div class="wjportal-filter-wrp">
                    <?php
                        $image1 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/sort-up.png";
                        $image2 = esc_url(WPJOBPORTAL_PLUGIN_URL) . "includes/images/sort-down.png";
                        if (wpjobportal::$_data['sortby'] == 1) {
                            $image = $image1;
                        } else {
                            $image = $image2;
                        }
                        $categoryarray = array(
                            (object) array('id' => 1, 'text' => esc_html(__('Application title', 'wp-job-portal'))),
                            (object) array('id' => 2, 'text' => esc_html(__('First name', 'wp-job-portal'))),
                            (object) array('id' => 3, 'text' => esc_html(__('Category', 'wp-job-portal'))),
                            (object) array('id' => 4, 'text' => esc_html(__('Job type', 'wp-job-portal'))),
                            (object) array('id' => 5, 'text' => esc_html(__('Location', 'wp-job-portal'))),
                            (object) array('id' => 6, 'text' => esc_html(__('Created', 'wp-job-portal'))),
                            (object) array('id' => 7, 'text' => esc_html(__('Status', 'wp-job-portal')))
                        );
                        // resume filters
                        WPJOBPORTALincluder::getTemplate('resume/views/frontend/filter',array(
                            'sortbylist' => $categoryarray,
                            'filter' => 'resume',
                            'image' => $image,
                            'image1' => $image1,
                            'image2' => $image2
                        ));
                    ?>
                </div>
                <div class="wjportal-act-btn-wrp">
                    <?php  do_action('wpjobportal_addon_resume_action_addResume'); ?>
                </div>
            </div>
        <?php } ?>
        <?php
        if(!WPJOBPORTALincluder::getTemplate('templates/header',array('module'=>'resume'))){
                    return;
        } ?>
        </div>
        <div class="wjportal-resume-list-wrp wjportal-my-resume-wrp">
            <?php
            if(!empty(wpjobportal::$_data[0])){ ?>
                <form id="resume_form" method="post" action="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume','wpjobportallt'=>'myresumes','wpjobportalpageid' =>wpjobportal::wpjobportal_getPageid()))); ?>">
                <?php
                    $dateformat = wpjobportal::$_configuration['date_format'];
                    foreach (wpjobportal::$_data[0] AS $myresume) {

                        // to show resume as 100% when advanced resume builder is not installed.
                         if(!in_array('advanceresumebuilder', wpjobportal::$_active_addons)){
                            $status_array = array();
                            $status_array['percentage'] = 100;
                            $percentage = 100;
                         }else{
                            $status_array = WPJOBPORTALincluder::getJSModel('resume')->getResumePercentage($myresume->id);
                            $percentage = $status_array['percentage'];
                         }
                            WPJOBPORTALincluder::getTemplate('resume/views/frontend/resumelist',array(
                            'myresume' => $myresume,
                            'percentage' => $status_array['percentage'],
                            'dateformat' => $dateformat,
                            'control' => 'myresumes',
                            'module' => 'myresumes'
                         ));

                    }
                    if (wpjobportal::$_data[1]) {
                        WPJOBPORTALincluder::getTemplate('templates/pagination',array('module' => 'resume','pagination' => wpjobportal::$_data[1]));
                    }
                    echo wp_kses(WPJOBPORTALformfield::hidden('sortby', wpjobportal::$_data['sortby']),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('sorton', wpjobportal::$_data['sorton']),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('WPJOBPORTAL_form_search', 'WPJOBPORTAL_SEARCH'),WPJOBPORTAL_ALLOWED_TAGS);
                    echo wp_kses(WPJOBPORTALformfield::hidden('wpjobportallay', 'myresume'),WPJOBPORTAL_ALLOWED_TAGS);
                    ?>
                </form>
            <?php
            } else {
                $msg = esc_html(__('No record found','wp-job-portal'));
                if(in_array('multiresume', wpjobportal::$_active_addons)){
                    $mod = "multiresume";
                }else{
                    $mod = "resume";
                }
                $links[] = array(
                        'link' => wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>$mod, 'wpjobportallt'=>'addresume', 'wpjobportalpageid'=>wpjobportal::wpjobportal_getPageid())),
                        'text' => esc_html(__('Add New','wp-job-portal')) .' '. esc_html(__('Resume', 'wp-job-portal'))
                    );
                WPJOBPORTALlayout::getNoRecordFound($msg,$links);
            }?>
        </div>

</div>
</div>
