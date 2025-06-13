<?php
    if (!defined('ABSPATH'))
        die('Restricted Access');
    wp_enqueue_script('iris');
    wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style.css');
    wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style_mobile.css');
    wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style_landscape.css');
    wp_enqueue_style('wpjobportal-style', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/style_tablet.css');
    if (is_rtl()) {
        wp_register_style('wpjobportal-style-rtl', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/stylertl.css');
        wp_enqueue_style('wpjobportal-style-rtl');
    }
    //include_once WPJOBPORTAL_PLUGIN_PATH . 'includes/css/style_color.php';
    wp_enqueue_style('wpjobportal-color', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/color.css');

?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <!-- top bar -->
        <div id="wpjobportal-wrapper-top">
            <div id="wpjobportal-wrapper-top-left">
                <div id="wpjobportal-breadcrumbs">
                    <ul>
                        <li>
                            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=wpjobportal')); ?>" title="<?php echo esc_html(__('dashboard','wp-job-portal')); ?>">
                                <?php echo esc_html(__('Dashboard','wp-job-portal')); ?>
                            </a>
                        </li>
                        <li><?php echo esc_html(__('Colors','wp-job-portal')); ?></li>
                    </ul>
                </div>
            </div>
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-vers-txt">
                    <?php echo esc_html(__('Version','wp-job-portal')).': '; ?>
                    <span class="wpjobportal-ver"><?php echo esc_html(WPJOBPORTALincluder::getJSModel('configuration')->getConfigValue('versioncode')); ?></span>
                </div>
            </div>
        </div>
        <!-- top head -->
        <div id="wpjobportal-head">
            <h1 class="wpjobportal-head-text">
                <?php echo esc_html(__('Colors', 'wp-job-portal')); ?>
            </h1>
            <a class="wpjobportal-add-link button" id="saveColors" href="#" title="<?php echo  esc_html(__('add job','wp-job-portal'))?>">
                <?php echo  esc_html(__('Save Colors','wp-job-portal'))?>
            </a>
            <a href="#" id="preset_theme" class="wpjobportal-add-link white-bg button" title="<?php echo  esc_html(__('preset','wp-job-portal'))?>">
                <?php echo esc_html(__('Preset', 'wp-job-portal')); ?>
            </a>
        </div>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0">
            <div id="theme_heading">
                <form action="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wpjobportal_theme&task=savetheme&action=task'),'wpjobportal_theme_nonce')); ?>" method="POST" name="adminForm" id="adminForm">
                    <div class="color_portion">
                        <span class="color_title">
                            <?php echo esc_html(__('Primary Color', 'wp-job-portal')); ?>
                        </span>
                        <span class="color_wrp">
                            <input type="text" name="color1" id="color1" value="<?php echo esc_attr(wpjobportal::$_data[0]['color1']); ?>" style="background:<?php echo esc_attr(wpjobportal::$_data[0]['color1']); ?>;"/>
                            <span class="color_wrp_img">
                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/themes/colorpicker.png">
                            </span>
                        </span>
                    </div>
                    <div class="color_portion">
                        <span class="color_title">
                            <?php echo esc_html(__('Secondary Color', 'wp-job-portal')); ?></span>
                        <span class="color_wrp">
                            <input type="text" name="color2" id="color2" value="<?php echo esc_attr(wpjobportal::$_data[0]['color2']); ?>" style="background:<?php echo esc_attr(wpjobportal::$_data[0]['color2']); ?>;"/>
                            <span class="color_wrp_img">
                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/themes/colorpicker.png">
                            </span>
                        </span>
                    </div>
                    <div class="color_portion">
                        <span class="color_title">
                            <?php echo esc_html(__('Content Color', 'wp-job-portal')); ?>
                        </span>
                        <span class="color_wrp">
                            <input type="text" name="color3" id="color3" value="<?php echo esc_attr(wpjobportal::$_data[0]['color3']); ?>" style="background:<?php echo esc_attr(wpjobportal::$_data[0]['color3']); ?>;"/>
                            <span class="color_wrp_img">
                                <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/themes/colorpicker.png">
                            </span>
                        </span>
                    </div>
                    <input type="hidden" name="form_request" value="wpjobportal" />
                </form>
            </div>
            <div class="js_effect_preview">
                <div class="wjportal-page-header">
                    <div class="wjportal-page-header-cnt">
                        <div class="wjportal-page-heading">
                            <?php echo esc_html(__('My Jobs', 'wp-job-portal')); ?>
                        </div>
                        <div class="wjportal-breadcrumbs-wrp">
                            <div class="wjportal-breadcrumbs-links wjportal-breadcrumbs-firstlinks">
                                <a class="wjportal-breadcrumbs-link" href="#">
                                    <?php echo esc_html(__('Dashboard', 'wp-job-portal')); ?>
                                </a>
                            </div>
                            <div class="wjportal-breadcrumbs-links wjportal-breadcrumbs-lastlink">
                                <?php echo esc_html(__('My Jobs', 'wp-job-portal')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wjportal-header-actions">
                        <div class="wjportal-filter-wrp">
                            <div class="wjportal-filter">
                                <select name="sorting" id="sorting">
                                    <option value="">
                                        <?php echo esc_html(__('Default', 'wp-job-portal')); ?>
                                    </option>
                                    <option class="" value="1">
                                        <?php echo esc_html(__('Job Title', 'wp-job-portal')); ?>
                                    </option>
                                    <option class="" value="2">
                                        <?php echo esc_html(__('Company Name', 'wp-job-portal')); ?>
                                    </option>
                                    <option class="" value="3">
                                        <?php echo esc_html(__('Category', 'wp-job-portal')); ?>
                                    </option>
                                    <option class="" value="5">
                                        <?php echo esc_html(__('Location', 'wp-job-portal')); ?>
                                    </option>
                                    <option class="" value="7">
                                        <?php echo esc_html(__('Status', 'wp-job-portal')); ?>
                                    </option>
                                    <option class="" value="4">
                                        <?php echo esc_html(__('Job Type', 'wp-job-portal')); ?>
                                    </option>
                                </select>
                            </div>
                            <div class="wjportal-filter-image">
                                <a class="sort-icon" href="#" >
                                    <img id="sortingimage" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/sort-up.png">
                                </a>
                            </div>
                        </div>
                        <div class="wjportal-act-btn-wrp">
                            <a class="wjportal-act-btn" href="#">
                                <i class="fa fa-plus"></i>
                                <?php echo esc_html(__('Add New Job', 'wp-job-portal')); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="wjportal-jobs-list">
                    <div class="wjportal-jobs-list-top-wrp">
                        <div class="wjportal-jobs-logo">
                            <a href="#">
                                <img src="<?php echo esc_url(WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer')); ?>" alt="<?php echo esc_html(__('Company logo','wp-job-portal')); ?>">
                            </a>
                        </div>
                        <div class="wjportal-jobs-cnt-wrp">
                            <div class="wjportal-jobs-middle-wrp">
                                <div class="wjportal-jobs-data">
                                    <a class="wjportal-companyname" href="#">
                                        <?php echo esc_html(__('Buruj Solution','wp-job-portal')); ?>
                                    </a>
                                </div>
                                <div class="wjportal-jobs-data">
                                    <span class="wjportal-job-title">
                                        <a href="#">
                                            <?php echo esc_html(__('PHP Developer','wp-job-portal')); ?>
                                        </a>
                                    </span>
                                    <span class="wjportal-item-status" style="background:#00a859;">
                                        <?php echo esc_html(__('Publish','wp-job-portal')); ?>
                                    </span>
                                    <span class="wjportal-featured-tag-icon-wrp">
                                        <span class="wjportal-featured-tag-icon">
                                            <i class="fa fa-star"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="wjportal-jobs-data">
                                    <span class="wjportal-jobs-data-text">
                                        <?php echo esc_html(__('Computer/IT','wp-job-portal')); ?>
                                    </span>
                                    <span class="wjportal-jobs-data-text">
                                        <?php echo esc_html(__('Karachi, Pakistan','wp-job-portal')); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="wjportal-jobs-right-wrp">
                                <div class="wjportal-jobs-info">
                                    <span class="wjportal-job-type" style="background:#00abfa">
                                        <?php echo esc_html(__('Full-Time','wp-job-portal')); ?>
                                    </span>
                                </div>
                                <div class="wjportal-jobs-info">
                                    <div class="wjportal-jobs-salary">
                                        1,000 - 1,500 $
                                        <span class="wjportal-salary-type">  / <?php echo esc_html(__('Per Month','wp-job-portal')); ?></span>
                                    </div>
                                </div>
                                <div class="wjportal-jobs-info">
                                    7 <?php echo esc_html(__('hours Ago','wp-job-portal')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wjportal-jobs-list-btm-wrp">
                        <div class="wjportal-jobs-action-wrp">
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Edit Job','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Edit Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" href="#" title="<?php echo esc_html(__('Delete Job','wp-job-portal')); ?>"><?php echo esc_html(__('Delete Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Copy Job','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Copy Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn wjportal-jobs-apply-res" title="<?php echo esc_html(__('Resume','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Resume (1)','wp-job-portal')); ?></a>
                        </div>
                    </div>
                </div>
                <div class="wjportal-jobs-list">
                    <div class="wjportal-jobs-list-top-wrp">
                        <div class="wjportal-jobs-logo">
                            <a href="#">
                                <img src="<?php echo esc_url(WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer')); ?>" alt="<?php echo esc_html(__('Company logo','wp-job-portal')); ?>">
                            </a>
                        </div>
                        <div class="wjportal-jobs-cnt-wrp">
                            <div class="wjportal-jobs-middle-wrp">
                                <div class="wjportal-jobs-data">
                                    <a class="wjportal-companyname" href="#">
                                        <?php echo esc_html(__('Buruj Solution','wp-job-portal')); ?>
                                    </a>
                                </div>
                                <div class="wjportal-jobs-data">
                                    <span class="wjportal-job-title">
                                        <a href="#">
                                            <?php echo esc_html(__('Android Developer','wp-job-portal')); ?>
                                        </a>
                                    </span>
                                    <span class="wjportal-item-status" style="background:#00a859;">
                                        <?php echo esc_html(__('Publish','wp-job-portal')); ?>
                                    </span>
                                </div>
                                <div class="wjportal-jobs-data">
                                    <span class="wjportal-jobs-data-text">
                                        <?php echo esc_html(__('Computer/IT','wp-job-portal')); ?>
                                    </span>
                                    <span class="wjportal-jobs-data-text">
                                        <?php echo esc_html(__('Gujranwala, Pakistan','wp-job-portal')); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="wjportal-jobs-right-wrp">
                                <div class="wjportal-jobs-info">
                                    <span class="wjportal-job-type" style="background:#00abfa">
                                        <?php echo esc_html(__('Full-Time','wp-job-portal')); ?>
                                    </span>
                                </div>
                                <div class="wjportal-jobs-info">
                                    <div class="wjportal-jobs-salary">
                                        1,000 - 1,500 $
                                        <span class="wjportal-salary-type">  / <?php echo esc_html(__('Per Month','wp-job-portal')); ?></span>
                                    </div>
                                </div>
                                <div class="wjportal-jobs-info">
                                    7 <?php echo esc_html(__('hours Ago','wp-job-portal')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wjportal-jobs-list-btm-wrp">
                        <div class="wjportal-jobs-action-wrp">
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Edit Job','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Edit Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" href="#" title="<?php echo esc_html(__('Delete Job','wp-job-portal')); ?>"><?php echo esc_html(__('Delete Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Copy Job','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Copy Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Add Featured ','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Add Featured','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn wjportal-jobs-apply-res" title="<?php echo esc_html(__('Resume','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Resume (0)','wp-job-portal')); ?></a>
                        </div>
                    </div>
                </div>
                <div class="wjportal-jobs-list">
                    <div class="wjportal-jobs-list-top-wrp">
                        <div class="wjportal-jobs-logo">
                            <a href="#">
                                <img src="<?php echo esc_url(WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer')); ?>" alt="<?php echo esc_html(__('Company logo','wp-job-portal')); ?>">
                            </a>
                        </div>
                        <div class="wjportal-jobs-cnt-wrp">
                            <div class="wjportal-jobs-middle-wrp">
                                <div class="wjportal-jobs-data">
                                    <a class="wjportal-companyname" href="#">
                                        <?php echo esc_html(__('Buruj Solution','wp-job-portal')); ?>
                                    </a>
                                </div>
                                <div class="wjportal-jobs-data">
                                    <span class="wjportal-job-title">
                                        <a href="#">
                                            <?php echo esc_html(__('Accountant','wp-job-portal')); ?>
                                        </a>
                                    </span>
                                    <span class="wjportal-item-status" style="background:#00a859;">
                                        <?php echo esc_html(__('Publish','wp-job-portal')); ?>
                                    </span>
                                </div>
                                <div class="wjportal-jobs-data">
                                    <span class="wjportal-jobs-data-text">
                                        <?php echo esc_html(__('Computer/IT','wp-job-portal')); ?>
                                    </span>
                                    <span class="wjportal-jobs-data-text">
                                        <?php echo esc_html(__('Lahore, Pakistan','wp-job-portal')); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="wjportal-jobs-right-wrp">
                                <div class="wjportal-jobs-info">
                                    <span class="wjportal-job-type" style="background:#00abfa">
                                        <?php echo esc_html(__('Full-Time','wp-job-portal')); ?>
                                    </span>
                                </div>
                                <div class="wjportal-jobs-info">
                                    <div class="wjportal-jobs-salary">
                                        1,000 - 1,500 $
                                        <span class="wjportal-salary-type">  / <?php echo esc_html(__('Per Month','wp-job-portal')); ?></span>
                                    </div>
                                </div>
                                <div class="wjportal-jobs-info">
                                    7 <?php echo esc_html(__('hours Ago','wp-job-portal')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wjportal-jobs-list-btm-wrp">
                        <div class="wjportal-jobs-action-wrp">
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Edit Job','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Edit Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" href="#" title="<?php echo esc_html(__('Delete Job','wp-job-portal')); ?>"><?php echo esc_html(__('Delete Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Copy Job','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Copy Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Add Featured ','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Add Featured','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn wjportal-jobs-apply-res" title="<?php echo esc_html(__('Resume','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Resume (10)','wp-job-portal')); ?></a>
                        </div>
                    </div>
                </div>
                <div class="wjportal-jobs-list">
                    <div class="wjportal-jobs-list-top-wrp">
                        <div class="wjportal-jobs-logo">
                            <a href="#">
                                <img src="<?php echo esc_url(WPJOBPORTALincluder::getJSModel('common')->getDefaultImage('employer')); ?>" alt="<?php echo esc_html(__('Company logo','wp-job-portal')); ?>">
                            </a>
                        </div>
                        <div class="wjportal-jobs-cnt-wrp">
                            <div class="wjportal-jobs-middle-wrp">
                                <div class="wjportal-jobs-data">
                                    <a class="wjportal-companyname" href="#">
                                        <?php echo esc_html(__('Buruj Solution','wp-job-portal')); ?>
                                    </a>
                                </div>
                                <div class="wjportal-jobs-data">
                                    <span class="wjportal-job-title">
                                        <a href="#">
                                            <?php echo esc_html(__('Database Administrator','wp-job-portal')); ?>
                                        </a>
                                    </span>
                                    <span class="wjportal-item-status" style="background:#00a859;">
                                        <?php echo esc_html(__('Publish','wp-job-portal')); ?>
                                    </span>
                                </div>
                                <div class="wjportal-jobs-data">
                                    <span class="wjportal-jobs-data-text">
                                        <?php echo esc_html(__('Computer/IT','wp-job-portal')); ?>
                                    </span>
                                    <span class="wjportal-jobs-data-text">
                                        <?php echo esc_html(__('Islamabad, Pakistan','wp-job-portal')); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="wjportal-jobs-right-wrp">
                                <div class="wjportal-jobs-info">
                                    <span class="wjportal-job-type" style="background:#00abfa">
                                        <?php echo esc_html(__('Full-Time','wp-job-portal')); ?>
                                    </span>
                                </div>
                                <div class="wjportal-jobs-info">
                                    <div class="wjportal-jobs-salary">
                                        1,000 - 1,500 $
                                        <span class="wjportal-salary-type">  / <?php echo esc_html(__('Per Month','wp-job-portal')); ?></span>
                                    </div>
                                </div>
                                <div class="wjportal-jobs-info">
                                    7 <?php echo esc_html(__('hours Ago','wp-job-portal')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wjportal-jobs-list-btm-wrp">
                        <div class="wjportal-jobs-action-wrp">
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Edit Job','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Edit Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" href="#" title="<?php echo esc_html(__('Delete Job','wp-job-portal')); ?>"><?php echo esc_html(__('Delete Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Copy Job','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Copy Job','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn" title="<?php echo esc_html(__('Add Featured ','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Add Featured','wp-job-portal')); ?></a>
                            <a class="wjportal-jobs-act-btn wjportal-jobs-apply-res" title="<?php echo esc_html(__('Resume','wp-job-portal')); ?>" href="#"><?php echo esc_html(__('Resume (1)','wp-job-portal')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function () {
            makeColorPicker('". esc_js(wpjobportal::$_data[0]['color1'])."', '". esc_js(wpjobportal::$_data[0]['color2'])."', '". esc_js(wpjobportal::$_data[0]['color3'])."');


        function makeColorPicker(color1, color2, color3) {
            jQuery('input#color1').iris({
                color: color1,
                onShow: function (colpkr) {
                    jQuery(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
                },
                change: function (c_event, ui) {
                    hex = ui.color.toString();
                    jQuery('div#wpjobportal-header-main-wrapper').css('background-color', hex);
                    jQuery('a.headerlinks').mouseover(function () {
                        jQuery(this).css('color', '#' + hex);
                    });
                    jQuery('a.headerlinks').mouseout(function () {
                        jQuery(this).css('color', jQuery('input#color7').val());
                    });
                    jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title a').css('color',hex);
                    jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-act-btn').css('color',hex);
                    jQuery('span.wjportal-featured-tag-icon-wrp span.wjportal-featured-tag-icon').css('background-color',hex);
                    jQuery('input#color1').css('background-color',hex);
                }
            });
            jQuery('input#color2').iris({
                color: color2,
                onShow: function (colpkr) {
                    jQuery(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
                },
                change: function (c_event, ui) {
                    hex = ui.color.toString()
                    jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname').css('color', hex);
                    jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary').css('color', hex);
                    jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res').css('color', hex);
                    jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res').hover(function () {
                        jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res').css('borderColor', hex);
                        jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res').css('background-color', hex);
                        jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res').css('color', '#fff');
                    })
                    jQuery('input#color2').css('background-color', hex);
                }
            });
            jQuery('input#color3').iris({
                color: color3,
                onShow: function (colpkr) {
                    jQuery(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    jQuery(colpkr).fadeOut(500);
                    return false;
                },
                change: function (c_event, ui) {
                    hex = ui.color.toString()
                    jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text').css('color', hex);
                    jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info').css('color', hex);
                    jQuery('input#color3').css('background-color',  hex);

                }
            });
            jQuery(document).click(function (e) {
                if (!jQuery(e.target).is('#color1, #color2, #color3')) {
                    jQuery('#color1, #color2, #color3').iris('hide');
                }
            });
            jQuery('#color1, #color2, #color3').click(function (event) {
                jQuery('#color1, #color2, #color3').iris('hide');
                jQuery(this).iris('show');
                return false;
            });
        }
        });
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>

<div id="black_wrapper_jobapply" style="display:none;"></div>
<div id="js_jobapply_main_wrapper" class="theme_popup" style="display:none;padding:0px 5px;">
    <div id="js_job_wrapper">
        <span class="js_job_controlpanelheading"><?php echo esc_html(__('Preset Theme', 'wp-job-portal')); ?>
        <img class="closepp" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/popup-close.png">
        </span>
        <div class="js_theme_wrapper">
            <div class="theme_platte">
                <div class="color_wrapper">
                    <div class="color 1" style="background:#36bc9a;"></div>
                    <div class="color 2" style="background:#333333;"></div>
                    <div class="color 3" style="background:#575757;"></div>
                    <span class="theme_name"><?php echo esc_html(__('Mint', 'wp-job-portal')); ?></span>
                    <img class="preview" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/themes/preview1.png" />
                    <a href="#" class="preview"></a>
                    <a href="#" class="set_theme"></a>
                </div>
            </div>
            <div class="theme_platte">
                <div class="color_wrapper">
                    <div class="color 1" style="background:#e43039;"></div>
                    <div class="color 2" style="background:#940007;"></div>
                    <div class="color 3" style="background:#575757;"></div>
                    <span class="theme_name"><?php echo esc_html(__('Red', 'wp-job-portal')); ?></span>
                    <img class="preview" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/themes/preview2.png" />
                    <a href="#" class="preview"></a>
                    <a href="#" class="set_theme"></a>
                </div>
            </div>
            <div class="theme_platte">
                <div class="color_wrapper">
                    <div class="color 1" style="background:#3baeda;"></div>
                    <div class="color 2" style="background:#333333;"></div>
                    <div class="color 3" style="background:#575757;"></div>
                    <span class="theme_name"><?php echo esc_html(__('Aqua', 'wp-job-portal')); ?></span>
                    <img class="preview" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/themes/preview3.png" />
                    <a href="#" class="preview"></a>
                    <a href="#" class="set_theme"></a>
                </div>
            </div>
            <div class="theme_platte">
                <div class="color_wrapper">
                    <div class="color 1" style="background:#4d89dc;"></div>
                    <div class="color 2" style="background:#000000;"></div>
                    <div class="color 3" style="background:#575757;"></div>
                    <span class="theme_name"><?php echo esc_html(__('Blue Jeans', 'wp-job-portal')); ?></span>
                    <img class="preview" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/themes/preview4.png" />
                    <a href="#" class="preview"></a>
                    <a href="#" class="set_theme"></a>
                </div>
            </div>
            <div class="theme_platte">
                <div class="color_wrapper">
                    <div class="color 1" style="background:#8cc051;"></div>
                    <div class="color 2" style="background:#366600;"></div>
                    <div class="color 3" style="background:#575757;"></div>
                    <span class="theme_name"><?php echo esc_html(__('Grass', 'wp-job-portal')); ?></span>
                    <img class="preview" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/themes/preview5.png" />
                    <a href="#" class="preview"></a>
                    <a href="#" class="set_theme"></a>
                </div>
            </div>
            <div class="theme_platte">
                <div class="color_wrapper">
                    <div class="color 1" style="background:#db4453;"></div>
                    <div class="color 2" style="background:#80000d;"></div>
                    <div class="color 3" style="background:#575757;"></div>
                    <span class="theme_name"><?php echo esc_html(__('Grape fruit', 'wp-job-portal')); ?></span>
                    <img class="preview" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/themes/preview6.png" />
                    <a href="#" class="preview"></a>
                    <a href="#" class="set_theme"></a>
                </div>
            </div>
            <div class="theme_platte">
                <div class="color_wrapper">
                    <div class="color 1" style="background:#967bdc;"></div>
                    <div class="color 2" style="background:#391a8c;"></div>
                    <div class="color 3" style="background:#575757;"></div>
                    <span class="theme_name"><?php echo esc_html(__('Lavender', 'wp-job-portal')); ?></span>
                    <img class="preview" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/themes/preview7.png" />
                    <a href="#" class="preview"></a>
                    <a href="#" class="set_theme"></a>
                </div>
            </div>
            <div class="theme_platte">
                <div class="color_wrapper">
                    <div class="color 1" style="background:#000000;"></div>
                    <div class="color 2" style="background:#120045;"></div>
                    <div class="color 3" style="background:#575757;"></div>
                    <span class="theme_name"><?php echo esc_html(__('Black', 'wp-job-portal')); ?></span>
                    <img class="preview" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>/includes/images/themes/preview8.png" />
                    <a href="#" class="preview"></a>
                    <a href="#" class="set_theme"></a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $inline_js_script = "
        jQuery(document).ready(function () {
            jQuery('#saveColors').click(function (e) {
                jQuery('form#adminForm').submit();
            });
            jQuery('a#preset_theme').click(function (e) {
                e.preventDefault();
                jQuery('div#js_jobapply_main_wrapper').fadeIn();
                jQuery('div#black_wrapper_jobapply').fadeIn();
            });
            jQuery('div#black_wrapper_jobapply , img.closepp').click(function () {
                jQuery('div#js_jobapply_main_wrapper').fadeOut();
                jQuery('div#black_wrapper_jobapply').fadeOut();
            });
            jQuery('a.preview').each(function (index, element) {
                jQuery(this).hover(function () {
                    if (index > 2)
                        jQuery(this).parent().find('img.preview').css('top', '-110px');
                    jQuery(jQuery(this).parent().find('img.preview')).show();
                }, function () {
                    jQuery(jQuery(this).parent().find('img.preview')).hide();
                });
            });
            jQuery('a.set_theme').each(function (index, element) {
                jQuery(this).click(function (e) {
                    e.preventDefault();
                    var div = jQuery(this).parent();
                    var color1 = rgb2hex(jQuery(div.find('div.1')).css('background-color'));
                    var color2 = rgb2hex(jQuery(div.find('div.2')).css('background-color'));
                    var color3 = rgb2hex(jQuery(div.find('div.3')).css('background-color'));
                    jQuery('input#color1').val(color1).css('background-color', color1);
                    jQuery('input#color2').val(color2).css('background-color', color2);
                    jQuery('input#color3').val(color3).css('background-color', color3);
                    jQuery('input.submit-button').css('background-color', color2);
                    themeSelectionEffect();
                    jQuery('div#js_jobapply_main_wrapper').fadeOut();
                    jQuery('div#black_wrapper_jobapply').fadeOut();
                });
            });
        });
        function rgb2hex(rgb) {
            rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
            function hex(x) {
                return ('0' + parseInt(x).toString(16)).slice(-2);
            }
            return '#' + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
        }
        function themeSelectionEffect() {
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname').css('color', jQuery('input#color2').val());
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname').hover(function () {
                jQuery(this).css('color', jQuery('input#color1').val());
            }, function () {
                    jQuery(this).css('color', jQuery('input#color2').val());
            })
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title a').css('color', jQuery('input#color1').val());
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title a').hover(function () {
                jQuery(this).css('color', jQuery('input#color2').val());
            }, function () {
                    jQuery(this).css('color', jQuery('input#color1').val());
            })
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text').css('color', jQuery('input#color3').val());
            jQuery('span.wjportal-featured-tag-icon-wrp span.wjportal-featured-tag-icon').css('background-color', jQuery('input#color1').val());
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info').css('color', jQuery('input#color3').val());
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary').css('color', jQuery('input#color2').val());
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-act-btn').css('color', jQuery('input#color1').val());
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-act-btn').hover(function () {
                jQuery(this).css('borderColor', jQuery('input#color1').val());
                jQuery(this).css('background-color', jQuery('input#color1').val());
                jQuery(this).css('color', '#fff');
            }, function () {
                jQuery(this).css('borderColor', '#d4d4d5');
                jQuery(this).css('color', jQuery('input#color1').val());
                jQuery(this).css('background-color', '#fff');
            })
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res').css('color', jQuery('input#color2').val());
            jQuery('div.wjportal-jobs-list div.wjportal-jobs-list-btm-wrp div.wjportal-jobs-action-wrp a.wjportal-jobs-apply-res').hover(function () {
                jQuery(this).css('borderColor', jQuery('input#color2').val());
                jQuery(this).css('background-color', jQuery('input#color2').val());
                jQuery(this).css('color', '#fff');
            }, function () {
                jQuery(this).css('borderColor', '#d4d4d5');
                jQuery(this).css('color', jQuery('input#color2').val());
                jQuery(this).css('background-color', '#fff');
            })
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
