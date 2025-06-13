<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
/**
 * @param job      job object - optional
*/
?>
<?php

switch ($layouts) {
    case 'viewcomp_uppersection': ?>
        <div class="wjportal-companyinfo-wrp">
            <?php if(wpjobportal::$_data[0]->status == 3){ ?>
                    <div class="wjportal-companyinfo">
                        <span class="wjportal-comp-status"><?php echo esc_html(__('Pending Payment','wp-job-portal')); ?></span>
                    </div>
            <?php } ?>
            <?php if (isset(wpjobportal::$_data[2]['url']) && wpjobportal::$_data['companycontactdetail'] == true && $config_array['comp_show_url'] == 1 && wpjobportal::$_data[0]->url != '') { ?>
                    <div class="wjportal-companyinfo">
                        <a class="wjportal-companyinfo-link" href="<?php echo esc_url(wpjobportal::$_data[0]->url); ?>" target="_blank">
                            <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/website.png" alt="<?php echo esc_html(__('website','wp-job-portal')); ?>" title="<?php echo esc_html(__('website','wp-job-portal')); ?>">
                            <?php echo esc_html( wpjobportal::$_data[0]->url); ?>
                        </a>
                    </div>
            <?php } ?>
            <?php if (isset(wpjobportal::$_data[2]['city']) && !empty(wpjobportal::$_data[0]->location) && $config_array['comp_city'] == 1) { ?>
                <div class="wjportal-companyinfo">
                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/location.png" alt="<?php echo esc_html(__('location','wp-job-portal')); ?>" title="<?php echo esc_html(__('location','wp-job-portal')); ?>">
                    <span class="wjportal-companyinfo-data"><?php echo esc_html(wpjobportal::$_data[0]->location); ?></span>
                </div>
            <?php } ?>
        </div>
        <?php
    break;
}
?>