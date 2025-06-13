<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
 */
?>
<div id="wpjobportal-top-comp-right">
    <div id="wpjobportallist-comp-header" class="wpjobportalqueuereletive">
        <div id="innerheaderlefti">
            <span class="datablockhead-left"></span><span class="datablockhead-left"><span class="notbold color-blue"><a href="<?php echo esc_url_raw(admin_url("admin.php?page=wpjobportal_resume&wpjobportallt=formresume&wpjobportalid=".$resume->id));?>"><?php echo esc_html($resume->application_title); ?></a></span>

                <?php
                    $dateformat = wpjobportal::$_configuration['date_format'];
                    $curdate = date_i18n($dateformat);
                 ?>
            </span>
        </div>
        <div class="flag-and-type">
            <span id="js-queues-statuses" class="for-responsive"><?php
                $class_color = '';
                $arr = array();
                if ($resume->status == 0) {
                    if ($class_color == '') {
                        ?>
                    <?php } ?>
                    <?php
                    $class_color = 'q-self';
                    $arr['self'] = 1;
                }
                ?>

            </span>
        </div>
    </div>
    <div id="wpjobportallist-comp-body" class="wpjobportallist-comp-body-for-responsive">
        <span class="datablock" ><span class="txt-resume"><?php echo esc_html(__('Name', 'wp-job-portal')); ?>: </span><span class="txt notbold color"><?php echo esc_html($resume->first_name) . ' ' . esc_html($resume->last_name); ?></span></span>
        <span class="datablock" ><span class="txt-resume"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fields']['job_category'])); ?>: </span><span class="txt notbold color"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($resume->cat_title)); ?></span></span>
        <span class="datablock" ><span class="txt-resume">
        <?php
            if(!isset(wpjobportal::$_data['fields']['desired_salary'])){
                wpjobportal::$_data['fields']['desired_salary'] = WPJOBPORTALincluder::getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('desired_salary',3);
            }
            echo esc_html(wpjobportal::wpjobportal_getVariableValue(wpjobportal::$_data['fields']['salaryfixed'])); ?>: </span><span class="txt notbold color"><?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($resume->salary)); ?></span> </span>
        <span class="datablock job-que-category" ><span class="txt-resume"><?php echo esc_html(__('Location', 'wp-job-portal')); ?>: </span><span class="txt notbold color"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('city')->getLocationDataForView($resume->city)); ?></span></span>
    </div>
</div>
	<?php
		WPJOBPORTALincluder::getTemplate('resume/views/admin/control',array(
			'resume' =>	$resume,
			'arr' => $arr,
            'control' => $control
		));
	?>
