<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
?>
<div class="wjportal-main-up-wrapper">
<?php
if(!WPJOBPORTALincluder::getTemplate('templates/header',array('module'=>'job'))){
    return;
}
?>   
    <div class="wjportal-main-wrapper wjportal-clearfix">
        <div class="wjportal-page-header">
            <?php
                WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module'=>'job','layout'=>'jobcities'));
            ?>
        </div>
        <div class="wjportal-by-type-wrp">
            <?php
                $number = wpjobportal::$_configuration['jobcity_per_row'];
                if ($number < 1 || $number > 100) {
                    $number = 3; // by default set to 3
                }
                $width = 100 / $number;
                $count = 0;
                if (isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0])) {
                    foreach (wpjobportal::$_data[0] AS $jobsBycity) {
                        if (($count % $number) == 0) {
                            if ($count == 0)
                                echo '<div class="wjportal-type-row-wrapper">';
                            else
                                echo '</div><div class="wjportal-type-row-wrapper">';
                        }
                        ?>
                        <div class="wjportal-type-wrapper" style="width:<?php echo esc_attr($width); ?>%;">
                            <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'city'=>$jobsBycity->cityid))); ?>" title="<?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($jobsBycity->cityname)); ?>">
                                <span class="wjportal-type-title">
                                    <?php
                                        if(wpjobportal::$_configuration['jobsbycities_countryname'] == 1){
                                            echo esc_html(wpjobportal::wpjobportal_getVariableValue($jobsBycity->cityname)).', '.esc_html(wpjobportal::wpjobportal_getVariableValue($jobsBycity->countryname));
                                        }else{
                                            echo esc_html(wpjobportal::wpjobportal_getVariableValue($jobsBycity->cityname));
                                        }
                                    ?>
                                </span>
                                <?php if(wpjobportal::$_configuration['jobsbycities_jobcount']){ ?>
                                    <span class="wjportal-type-num">
                                        <?php echo esc_html($jobsBycity->totaljobs); ?>
                                    </span>
                                <?php } ?>
                            </a>
                        </div>
                    <?php
                    $count++;
                }
                echo '</div>';
                }
            else {
                WPJOBPORTALlayout::getNoRecordFound();?>
            <?php }
            ?>
        </div>
    </div>	
</div>
