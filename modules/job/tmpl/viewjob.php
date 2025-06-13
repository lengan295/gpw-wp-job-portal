<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
if ( !WPJOBPORTALincluder::getTemplate('templates/header', array('module' => 'job') )) {
    return;
}
if (wpjobportal::$_error_flag == null) {
    $job = isset(wpjobportal::$_data[0]) ? wpjobportal::$_data[0]  : null;
    $jobfields = wpjobportal::$_data[2];
    
    /* Member Package Pop-up */
    function getDataRow($title, $value) {
        $html = '<div class="wjportal-job-data">
                    <span class="wjportal-job-data-tit">' . $title . ': </span>
                    <span class="wjportal-job-data-val">' . $value . '</span>
                </div>';
        return $html;
    }

    function getHeading2($value) {
        $html = '<div class="heading2">' . $value . '</div>';
        return $html;
    }

    function getPeragraph($value) {
        $html = '<div class="peragraph">' . $value . '</div>';
        return $html;
    }
    echo '<meta property="description" content="'.esc_attr($job->metadescription).'"/>';
    echo '<meta property="keywords" content="'.esc_attr($job->metakeywords).'"/>';

    // published fields title array to handle visibilty on layouts
    wpjobportal::$_data['published_fields'] = array();
    foreach (wpjobportal::$_data[2] as $key => $value) {
        wpjobportal::$_data['published_fields'][$key] = $value->fieldtitle;
    }

    WPJOBPORTALincluder::getTemplate('job/views/frontend/viewjobdetail',array(
        'job' => $job,
        'jobfields' => $jobfields
    ));
    ?>
    <?php
} else {
    if(wpjobportal::$_error_flag_message !=''){
        echo wp_kses_post(wpjobportal::$_error_flag_message);
    }
}
?>
</div>
