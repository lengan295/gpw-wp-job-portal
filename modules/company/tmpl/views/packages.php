<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
* @param WP JOB PORTAL---
* @param Packages Details For Company
*/
?>
<?php
if(isset($packages)){ 
    if (wpjobportal::$theme_chk == 1) { ?>
    <div class="wpj-jp-pkg-list">
        <div class="wpj-jp-pkg-list-top">
            <div class="wpj-jp-pkg-list-title">
                <h4 class="wpj-jp-pkg-list-title-txt">
                    <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($packages->title)); ?>
                </h4>
            </div>
        </div>
        <div class="wpj-jp-pkg-list-mid">
            <?php if(isset($packages)){?>
                <div class="wpj-jp-pkg-list-data">
                    <span class="wpj-jp-pkg-list-laebl">
                        <?php echo esc_html__("Total Company","wp-job-portal")." : "; ?>
                    </span>
                    <?php echo $packages->companies==-1 ? esc_html__('Unlimited','wp-job-portal') : esc_html($packages->companies); ?>
                </div>
                <div class="wpj-jp-pkg-list-data">
                    <span class="wpj-jp-pkg-list-laebl">
                        <?php echo esc_html__("Remaining Company","wp-job-portal")." : "; ?>
                    </span>
                    <?php echo $packages->companies==-1 ? esc_html__('Unlimited','wp-job-portal') : esc_html($packages->remcompany); ?>
                </div>
            <?php } ?>
        </div>
        <div class="wpj-jp-pkg-list-btm">
            <div class="wpj-jp-pkg-list-action-wrp">
                <a class="wpj-jp-outline-btn" title="<?php echo esc_attr__('change package', "wp-job-portal"); ?>" href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany','wpjobportallt'=>'addcompany'))); ?>">
                    <?php echo esc_html__("Change Package", "wp-job-portal") ?>
                </a>
            </div>
            <div class="wpj-jp-pkg-list-exp-date">
                <?php echo esc_html__('Ends On','wp-job-portal').': '.esc_html(date_i18n(wpjobportal::$_configuration['date_format'],strtotime(wpjobportal::$_data['package']->enddate))); ?>
            </div>        
        </div>
    </div>
<?php } else { ?>
    <div class="wjportal-packages-list">
        <div class="wjportal-pkg-list-item">
            <div class="wjportal-pkg-list-item-top">
                <div class="wjportal-pkg-list-item-title">
                    <div class="wjportal-pkg-list-item-title-txt">
                        <?php echo esc_html(wpjobportal::wpjobportal_getVariableValue($packages->title)); ?>
                    </div>
                </div>
            </div>
            <div class="wjportal-pkg-list-item-mid">
                <div class="wjportal-pkg-list-item-data">
                    <?php if(isset($packages)){?>
                        <div class="wjportal-pkg-list-item-row">
                            <span class="wjportal-pkg-list-item-row-tit">
                                <?php echo esc_html(__('Total Company','wp-job-portal')). ' : '; ?>
                            </span>
                            <span class="wjportal-pkg-list-item-row-val">
                                <?php echo ($packages->companies==-1 ? esc_html(__('Unlimited','wp-job-portal')) : esc_html(wpjobportal::wpjobportal_getVariableValue($packages->companies))); ?>
                            </span>
                        </div>
                        <div class="wjportal-pkg-list-item-row">
                            <span class="wjportal-pkg-list-item-row-tit">
                                <?php echo esc_html(__('Remaining Company','wp-job-portal')). ' : '; ?>
                            </span>
                            <span class="wjportal-pkg-list-item-row-val">
                                <?php echo ($packages->companies==-1 ? esc_html(__('Unlimited','wp-job-portal')) : esc_html(wpjobportal::wpjobportal_getVariableValue($packages->remcompany))); ?>
                            </span>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="wjportal-pkg-list-item-btm">
                <div class="wjportal-pkg-list-item-action-wrp">
                    <a href="<?php echo esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'multicompany','wpjobportallt'=>'addcompany'))); ?>" class="wjportal-pkg-list-item-act-btn" title="<?php echo esc_attr(__('change package','wp-job-portal')); ?>">
                        <?php echo esc_html(__('Change Package','wp-job-portal')); ?>
                    </a>
                </div>
                <div class="wjportal-pkg-list-item-exp-date">
                    <?php echo esc_html(__('Ends On','wp-job-portal')).': '.esc_html(date_i18n(wpjobportal::$_configuration['date_format'],strtotime($packages->enddate))); ?>
                </div>
            </div>
        </div>
    </div>
<?php }
} ?>
