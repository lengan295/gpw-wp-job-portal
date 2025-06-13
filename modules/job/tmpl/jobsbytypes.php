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
                WPJOBPORTALincluder::getTemplate('templates/pagetitle',array('module'=>'job','layout'=>'jobtype'));
            ?>
        </div>
        <div class="wjportal-by-type-wrp">
            <?php
                $number = 3;
                if(wpjobportal::$_data['config']['jobtype_per_row'] != ''){ // to handle float value in configuration
                    $number = ceil(wpjobportal::$_data['config']['jobtype_per_row']);
                }

                if ($number < 1 || $number > 100) {
                    $number = 3; // by default set to 3
                }
                $width = 100 / $number;
                $count = 0;
                if (isset(wpjobportal::$_data[0]) && !empty(wpjobportal::$_data[0])) {
                    foreach (wpjobportal::$_data[0] AS $jobsBytype) {
                        if (($count % $number) == 0) {
                            if ($count == 0)
                                echo '<div class="wjportal-type-row-wrapper">';
                            else
                                echo '</div><div class="wjportal-type-row-wrapper">';
                        }
                        ?>
                        <div class="wjportal-type-wrapper" style="width:<?php echo esc_attr($width); ?>%;">
                            <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job', 'wpjobportallt'=>'jobs', 'jobtype'=>$jobsBytype->alias))); ?>" title="<?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($jobsBytype->title)); ?>">
                                <span class="wjportal-type-title">
                                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($jobsBytype->title)); ?>
                                </span>
                                <?php if(wpjobportal::$_data['config']['jobtype_numberofjobs']){ ?>
                                    <span class="wjportal-type-num">
                                        <?php echo esc_html($jobsBytype->totaljobs); ?>
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
