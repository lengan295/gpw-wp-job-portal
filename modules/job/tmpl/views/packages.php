<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
* @param WP JOB PORTAL --Packages
* @param Current Package Detail's
*/
?>
<?php
// print_r($package);
// exit();
if(isset($package)){ 
    if (wpjobportal::$theme_chk == 1) { ?>
    <div class="wpj-jp-pkg-list">
        <div class="wpj-jp-pkg-list-top">
            <div class="wpj-jp-pkg-list-title">
                <h4 class="wpj-jp-pkg-list-title-txt">
                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue( $package->title)); ?>
                </h4>
            </div>
        </div>
        <div class="wpj-jp-pkg-list-mid">
            <?php if(isset($package)){?>
                <div class="wpj-jp-pkg-list-data">
                    <span class="wpj-jp-pkg-list-laebl">
                        <?php echo esc_html__("Total Jobs","wp-job-portal")." : "; ?>
                    </span>
                    <?php echo $package->job==-1 ? esc_html__('Unlimited','wp-job-portal') : esc_html(wpjobportal::wpjobportal_getVariableValue( $package->job)); ?>
                </div>
                <div class="wpj-jp-pkg-list-data">
                    <span class="wpj-jp-pkg-list-laebl">
                        <?php echo esc_html__("Remaining Jobs","wp-job-portal")." : "; ?>
                    </span>
                    <?php echo $package->job==-1 ? esc_html__('Unlimited','wp-job-portal') : esc_html(wpjobportal::wpjobportal_getVariableValue( $package->remjob)); ?>
                </div>
                <div class="wpj-jp-pkg-list-data">
                    <span class="wpj-jp-pkg-list-laebl">
                        <?php echo esc_html__("Job Expiry","wp-job-portal")." : "; ?>
                    </span>
                    <?php 
                        echo ($package->jobtime==-1 ? esc_html__('Unlimited','wp-job-portal') : esc_html(wpjobportal::wpjobportal_getVariableValue( $package->jobtime)));
                        echo ($package->jobtime==-1 ? esc_html__('Unlimited','wp-job-portal') : esc_html(wpjobportal::wpjobportal_getVariableValue( $package->jobtimeunit)));
                    ?>
                </div>
            <?php } ?>
        </div>
        <div class="wpj-jp-pkg-list-btm">
            <div class="wpj-jp-pkg-list-action-wrp">
                <a class="wpj-jp-outline-btn" title="<?php echo esc_attr__('change package', "wp-job-portal"); ?>" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job','wpjobportallt'=>'addjob'))); ?>">
                    <?php echo esc_html__("Change Package", "wp-job-portal") ?>
                </a>
            </div>
            <div class="wpj-jp-pkg-list-exp-date">
                <?php echo esc_html__('Ends On','wp-job-portal').': '.esc_html(date_i18n(wpjobportal::$_configuration['date_format'],strtotime($package->enddate))); ?>
            </div>        
        </div>
    </div>
    <?php } else { ?>
    <div class="wjportal-packages-list-wrp">
        <div class="wjportal-packages-list">
            <div class="wjportal-pkg-list-item">
                <div class="wjportal-pkg-list-item-top">
                    <div class="wjportal-pkg-list-item-title">
                        <div class="wjportal-pkg-list-item-title-txt">
                            <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($package->title)); ?>
                        </div>
                    </div>
                </div>
                <div class="wjportal-pkg-list-item-mid">
                    <?php if(isset($package)) { ?>
                        <div class="wjportal-pkg-list-item-data">
                            <div class="wjportal-pkg-list-item-row">
                                <span class="wjportal-pkg-list-item-row-tit">
                                    <?php echo esc_html(__('Total Jobs','wp-job-portal')). ' : '; ?>
                                </span>
                                <span class="wjportal-pkg-list-item-row-val">
                                    <?php echo ($package->job==-1 ? esc_html(__('Unlimited','wp-job-portal')) : esc_html(wpjobportal::wpjobportal_getVariableValue($package->job))); ?>
                                </span>
                            </div>
                            <div class="wjportal-pkg-list-item-row">
                                <span class="wjportal-pkg-list-item-row-tit">
                                    <?php echo esc_html(__('Remaining Job','wp-job-portal')). ' : '; ?>
                                </span>
                                <span class="wjportal-pkg-list-item-row-val">
                                    <?php echo ($package->job==-1 ? esc_html(__('Unlimited','wp-job-portal')) : esc_html(wpjobportal::wpjobportal_getVariableValue($package->remjob))); ?>
                                </span>
                            </div>
                            <div class="wjportal-pkg-list-item-row">
                                <span class="wjportal-pkg-list-item-row-tit">
                                    <?php echo esc_html(__('Job Expiry','wp-job-portal')). ' : '; ?>
                                </span>
                                <span class="wjportal-pkg-list-item-row-val">
                                    <?php echo ($package->jobtime==-1 ? esc_html(__('Unlimited','wp-job-portal')) : esc_html(wpjobportal::wpjobportal_getVariableValue($package->jobtime)));
                                    echo ($package->jobtime==-1 ? esc_html(__('Unlimited','wp-job-portal')) : esc_html(wpjobportal::wpjobportal_getVariableValue($package->jobtimeunit)));
                                     ?>
                                </span>
                            </div>
                        </div>
                   <?php } ?>
                </div>
                <div class="wjportal-pkg-list-item-btm">
                    <div class="wjportal-pkg-list-item-action-wrp">
                        <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'job','wpjobportallt'=>'addjob'))); ?>" class="wjportal-pkg-list-item-act-btn" title="<?php echo esc_attr(__('Change package','wp-job-portal')); ?>">
                            <?php echo esc_html(__('Change Package','wp-job-portal')); ?>
                        </a>
                    </div>
                    <div class="wjportal-pkg-list-item-exp-date">
                        <?php echo esc_html(__('Ends On','wp-job-portal')).': '.esc_html(date_i18n(wpjobportal::$_configuration['date_format'],strtotime($package->enddate))); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }
} ?>
