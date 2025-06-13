<?php
if (!defined('ABSPATH')) die('Restricted Access');
$c = WPJOBPORTALrequest::getVar('page',null,'wpjobportal');
$layout = WPJOBPORTALrequest::getVar('wpjobportallt');
$ff = WPJOBPORTALrequest::getVar('ff');
$for = WPJOBPORTALrequest::getVar('for');

wp_register_script( 'wpjobportal-menu-handle', '' );
wp_enqueue_script( 'wpjobportal-menu-handle' );

$menu_js_script = '
    jQuery( function() {
        jQuery( "#accordion" ).accordion({
            heightStyle: "content",
            collapsible: true,
            active: true,
        });
    });

    ';
wp_add_inline_script( 'wpjobportal-menu-handle', $menu_js_script );    
?>
<div id="wpjobportaladmin-logo">
    <a href="admin.php?page=wpjobportal" class="wpjobportaladmin-anchor">
        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/logo.png'; ?>"/>
    </a>
    <img id="wpjobportaladmin-menu-toggle" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/menu.png'; ?>" />
</div>
<ul class="wpjobportaladmin-sidebar-menu tree" data-widget="tree" id="accordion">
    <li class="treeview <?php if( ($c == 'wpjobportal' && $layout != 'themes' && $layout != 'shortcodes' && $layout != 'addonstatus') || $c == 'wpjobportal_activitylog' || $c == 'wpjobportal_systemerror' || $c == 'wpjobportal_slug' ) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal" title="<?php echo esc_html(__('dashboard' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('dashboard' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/dashboard.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Dashboard' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal' && ($layout == 'controlpanel' || $layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal" title="<?php echo esc_html(__('dashboard', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Dashboard', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_slug' && ($layout == 'slug')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_slug&wpjobportallt=slug" title="<?php echo esc_html(__('slug','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Slug','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal' && ($layout == 'pageseo' || $layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal&wpjobportallt=pageseo" title="<?php echo esc_html(__('SEO','wp-job-portal')); ?>">
                    <?php echo esc_html(__('SEO','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_activitylog' && ($layout == 'wpjobportal_activitylog' || $layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_activitylog" title="<?php echo esc_html(__('activity log','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Activity Log','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal' && ($layout == 'wpjobportalstats' || $layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal&wpjobportallt=wpjobportalstats" title="<?php echo esc_html(__('stats','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Stats','wp-job-portal')); ?>
                </a>
            </li>
            <?php /*<li class="<?php if($c == 'wpjobportal' && ($layout == 'translations')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal&wpjobportallt=translations" title="<?php echo esc_html(__('translations','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Translations','wp-job-portal')); ?>
                </a>
            </li> */?>
            <li class="<?php if($c == 'wpjobportal_systemerror' && ($layout == 'wpjobportal_systemerror' || $layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_systemerror" title="<?php echo esc_html(__('system errors','wp-job-portal')); ?>">
                    <?php echo esc_html(__('System Errors','wp-job-portal')); ?>
                </a>
            </li>

        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_job' || $c == 'wpjobportal_jobapply' || $c == 'wpjobportal_jobalert' || $c == 'wpjobportal_customfield' || ($c == 'wpjobportal_fieldordering' && $ff == 2)) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_job" title="<?php echo esc_html(__('jobs' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('jobs' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/jobs.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Jobs' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_job' && ($c == 'wpjobportal_jobapply' && $layout == 'jobappliedresume' || $layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_job" title="<?php echo esc_html(__('jobs', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Jobs', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_job' && ($layout == 'formjob')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_job&wpjobportallt=formjob" title="<?php echo esc_html(__('add new job', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Job', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_job' && ($layout == 'jobqueue')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_job&wpjobportallt=jobqueue" title="<?php echo esc_html(__('approval queue', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Approval Queue', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_fieldordering' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_fieldordering&ff=2" title="<?php echo esc_html(__('fields', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Fields', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_fieldordering' && ($layout == 'searchfields')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_fieldordering&wpjobportallt=searchfields&ff=2" title="<?php echo esc_html(__('search fields', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Search Fields', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_configuration' && ($layout == 'configurations')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=job_apply" title="<?php echo esc_html(__('configuration', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Job Apply Configurations', 'wp-job-portal')); ?>
                </a>
            </li>
            <?php
                //do_action('wpjobportal_addons_custom_fields_searchfields',$c,$layout);
            ?>
            <?php
            if(in_array('jobalert', wpjobportal::$_active_addons)){
                do_action('wpjobportal_addons_sidemenue_admin_jobalert',$c,$layout);
            }else{
                $plugininfo = checkWPJPPluginInfo('wp-job-portal-jobalert/wp-job-portal-jobalert.php');
                if($plugininfo['availability'] == "1"){
                    $text = $plugininfo['text'];
                    $url = "plugins.php?s=wp-job-portal-jobalert&plugin_status=inactive";
                }elseif($plugininfo['availability'] == "0"){
                    $text = $plugininfo['text'];
                    $url = "https://wpjobportal.com/product/job-alert/";
                } ?>
                <li class="disabled-menu">
                    <span class="wpjobportaladmin-text"><?php echo esc_html(__('Job Alert' , 'wp-job-portal')); ?></span>
                    <a href="<?php echo esc_url($url); ?>" class="wpjobportaladmin-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            <?php } ?>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_resume' ||  ($c == 'wpjobportal_fieldordering' && $ff == 3)) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_resume" title="<?php echo esc_html(__('resume' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('resume' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/resume.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Resume' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_resume' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_resume" title="<?php echo esc_html(__('resume', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Resume', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_resume' && ($layout == 'resumequeue')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_resume&wpjobportallt=resumequeue" title="<?php echo esc_html(__('approval queue', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Approval Queue', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_fieldordering' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_fieldordering&ff=3" title="<?php echo esc_html(__('fields', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Fields', 'wp-job-portal')); ?>
                </a>
            </li>
            <?php if(in_array('resumesearch', wpjobportal::$_active_addons)){ // hiding search fields without resume search addon ?>
                <li class="<?php if($c == 'wpjobportal_fieldordering' && ($layout == 'searchfields')) echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_fieldordering&wpjobportallt=searchfields&ff=3" title="<?php echo esc_html(__('search fields', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Search Fields', 'wp-job-portal')); ?>
                    </a>
                </li>
            <?php } ?>
            
                <li class="<?php if($c == 'wpjobportal_fieldordering' && ($layout == 'quickapply')) echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_fieldordering&ff=5" title="<?php echo esc_html(__('search fields', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Quick Apply Fields', 'wp-job-portal')); ?>
                    </a>
                </li>
        </ul>
    </li>

    <li class="treeview <?php if(($c == 'wpjobportal_company' || ($c == 'wpjobportal_fieldordering' && $ff == 1)) ) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_company" title="<?php echo esc_html(__('companies' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('companies' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/companies.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Companies' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_company' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_company" title="<?php echo esc_html(__('companies', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Companies', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_company' && ($layout == 'formcompany')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_company&wpjobportallt=formcompany" title="<?php echo esc_html(__('add new company', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Company', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_company' && ($layout == 'companiesqueue')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_company&wpjobportallt=companiesqueue" title="<?php echo esc_html(__('approval queue', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Approval Queue', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_fieldordering' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_fieldordering&ff=1" title="<?php echo esc_html(__('fields', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Fields', 'wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_configuration' || $c == 'wpjobportal_paymentmethodconfiguration' || $c == 'wpjobportal_cronjob' ) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('configuration' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/config.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Configuration' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_configuration' && ($layout == 'configurations')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_configuration&wpjobportallt=configurations&wpjpconfigid=general_setting" title="<?php echo esc_html(__('configuration', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Configuration', 'wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_premiumplugin') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_premiumplugin" title="<?php echo esc_html(__('ad ons' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('ad ons' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/ad-ons.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Install Addons' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_premiumplugin' && ($layout == '' || $layout == 'step1' || $layout == 'step2' || $layout == 'step3')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_premiumplugin" title="<?php echo esc_html(__('install addons','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Install Addons','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_premiumplugin' && ($layout == 'addonfeatures')) echo 'active'; ?>">
                <a href="?page=wpjobportal_premiumplugin&wpjobportallt=addonfeatures" title="<?php echo esc_html(__('addons list','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Addons List','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal' && $layout == 'addonstatus') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal&wpjobportallt=addonstatus" title="<?php echo esc_html(__('addons status' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('addons status' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/addon-status.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Addons Status' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal' && $layout == 'addonstatus') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal&wpjobportallt=addonstatus" title="<?php echo esc_html(__('Addons Status','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Addons Status','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal' && $layout == 'shortcodes' ) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal&wpjobportallt=shortcodes" title="<?php echo esc_html(__('short codes' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('short codes' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/short-codes.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Short Codes' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal' && ($layout == 'shortcodes')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal&wpjobportallt=shortcodes" title="<?php echo esc_html(__('short codes', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Short Codes', 'wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_theme') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_theme" title="<?php echo esc_html(__('colors' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('colors' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/theme.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Colors' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_theme') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_theme" title="<?php echo esc_html(__('colors','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Colors','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_report' || ($c == 'wpjobportal_reports')) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_report&wpjobportallt=overallreports" title="<?php echo esc_html(__('reports' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('reports' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/reports.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Reports' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_report' && ($layout == 'overallreports')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_report&wpjobportallt=overallreports" title="<?php echo esc_html(__('overall reports', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Overall Reports', 'wp-job-portal')); ?>
                </a>
            </li>
            <?php
                if(in_array('reports', wpjobportal::$_active_addons)){
                    do_action('wpjobportal_addons_admin_sidemenu_links_for_reports',$c,$layout);
                }else{
                    $plugininfo = checkWPJPPluginInfo('wp-job-portal-reports/wp-job-portal-reports.php');
                    if($plugininfo['availability'] == "1"){
                        $text = $plugininfo['text'];
                        $url = "plugins.php?s=wp-job-portal-reports&plugin_status=inactive";
                    }elseif($plugininfo['availability'] == "0"){
                        $text = $plugininfo['text'];
                        $url = "https://wpjobportal.com/product/reports/";
                    } ?>
                    <li class="disabled-menu fw">
                        <span class="wpjobportaladmin-text"><?php echo esc_html(__('Employer / Job Seeker Report' , 'wp-job-portal')); ?></span>
                        <a href="<?php echo esc_url($url); ?>" class="wpjobportaladmin-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                    </li>
               <?php } ?>
        </ul>
    </li>
    <?php if(in_array('departments', wpjobportal::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'wpjobportal_departments') echo 'active'; ?>">
            <a href="admin.php?page=wpjobportal_departments" title="<?php echo esc_html(__('departments' , 'wp-job-portal')); ?>">
                <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('departments' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/department.png'; ?>" />
                <span class="wpjobportaladmin-text">
                    <?php echo esc_html(__('Departments' , 'wp-job-portal')); ?>
                </span>
            </a>
            <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'wpjobportal_departments' && ($layout == '')) echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_departments" title="<?php echo esc_html(__('departments', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Departments', 'wp-job-portal')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'wpjobportal_departments' && ($layout == 'formdepartment')) echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_departments&wpjobportallt=formdepartment" title="<?php echo esc_html(__('add new department', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Add New Department', 'wp-job-portal')); ?>
                    </a>
                </li>
                <li class="<?php if($c == 'wpjobportal_departments' && ($layout == 'departmentqueue')) echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_departments&wpjobportallt=departmentqueue" title="<?php echo esc_html(__('approval queue', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Approval Queue', 'wp-job-portal')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php }else{
        $plugininfo = checkWPJPPluginInfo('wp-job-portal-departments/wp-job-portal-departments.php');
        if($plugininfo['availability'] == "1"){
            $text = $plugininfo['text'];
            $url = "plugins.php?s=wp-job-portal-departments&plugin_status=inactive";
        }elseif($plugininfo['availability'] == "0"){
            $text = $plugininfo['text'];
            $url = "https://wpjobportal.com/product/multi_departments/";
        } ?>
        <li class="treeview">
            <a href="javascript: void(0);" title="<?php echo esc_html(__('departments' , 'wp-job-portal')); ?>">
                <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('departments' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/grey-menu/department.png'; ?>" />
                <span class="wpjobportaladmin-text disabled-menu"><?php echo esc_html(__('Department' , 'wp-job-portal')); ?></span>
            </a>
            <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                <li class="disabled-menu">
                    <span class="wpjobportaladmin-text"><?php echo esc_html(__('departments' , 'wp-job-portal')); ?></span>
                    <a href="<?php echo esc_url($url); ?>" class="wpjobportaladmin-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>

    <?php if(in_array('coverletter', wpjobportal::$_active_addons)){ ?>
        <li class="treeview <?php if($c == 'wpjobportal_coverletter') echo 'active'; ?>" >
            <a href="admin.php?page=wpjobportal_coverletter" title="<?php echo esc_html(__('coverletters' , 'wp-job-portal')); ?>">
                <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('coverletters' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/cover-letter.png'; ?>" />
                <span class="wpjobportaladmin-text">
                    <?php echo esc_html(__('Cover Letters' , 'wp-job-portal')); ?>
                </span>
            </a>
            <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                <li class="<?php if($c == 'wpjobportal_coverletter' && (($layout == '') || ($layout == 'formcoverletter') )) echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_coverletter" title="<?php echo esc_html(__('coverletter', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Cover Letters', 'wp-job-portal')); ?>
                    </a>
                </li>
                <?php /*
                <li class="<?php if($c == 'wpjobportal_coverletter' && ($layout == 'formcoverletter')) echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_coverletter&wpjobportallt=formcoverletter" title="<?php //echo esc_html(__('add new cover letter', 'wp-job-portal')); ?>">
                        <?php //echo esc_html(__('Add New Cover Letter', 'wp-job-portal')); ?>
                    </a>
                </li>
                */ ?>
                <li class="<?php if($c == 'wpjobportal_coverletter' && ($layout == 'coverletterqueue')) echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_coverletter&wpjobportallt=coverletterqueue" title="<?php echo esc_html(__('approval queue', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Approval Queue', 'wp-job-portal')); ?>
                    </a>
                </li>
            </ul>
        </li>
    <?php }else{
        $plugininfo = checkWPJPPluginInfo('wp-job-portal-coverletter/wp-job-portal-coverletter.php');
        if($plugininfo['availability'] == "1"){
            $text = $plugininfo['text'];
            $url = "plugins.php?s=wp-job-portal-coverletter&plugin_status=inactive";
        }elseif($plugininfo['availability'] == "0"){
            $text = $plugininfo['text'];
            $url = "https://wpjobportal.com/product/multi_coverletter/";
        } ?>
        <li class="treeview">
            <a href="javascript: void(0);" title="<?php echo esc_html(__('coverletter' , 'wp-job-portal')); ?>">
                <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('coverletter' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/grey-menu/cover-letter.png'; ?>" />
                <span class="wpjobportaladmin-text disabled-menu"><?php echo esc_html(__('Cover Letter' , 'wp-job-portal')); ?></span>
            </a>
            <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                <li class="disabled-menu">
                    <span class="wpjobportaladmin-text"><?php echo esc_html(__('Cover Letter' , 'wp-job-portal')); ?></span>
                    <a href="<?php echo esc_url($url); ?>" class="wpjobportaladmin-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                </li>
            </ul>
        </li>
    <?php } ?>


    <?php
        if(in_array('message', wpjobportal::$_active_addons)){
            do_action('wpjobportal_addons_admin_sidemenu_links_for_message' , $c,$layout );
        }else{
            $plugininfo = checkWPJPPluginInfo('wp-job-portal-message/wp-job-portal-message.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=wp-job-portal-message&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://wpjobportal.com/product/messages/";
            } ?>
            <li class="treeview">
                <a href="javascript: void(0);" title="<?php echo esc_html(__('Message' , 'wp-job-portal')); ?>">
                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/grey-menu/message.png'; ?>" alt="<?php echo esc_html(__('message' , 'wp-job-portal')); ?>" class="wpjobportaladmin-menu-icon">
                    <span class="wpjobportaladmin-text disabled-menu"><?php echo esc_html(__('Message' , 'wp-job-portal')); ?></span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="disabled-menu">
                        <span class="wpjobportaladmin-text"><?php echo esc_html(__('Message' , 'wp-job-portal')); ?></span>
                        <a href="<?php echo esc_url($url); ?>" class="wpjobportaladmin-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                    </li>
                </ul>
            </li>
    <?php } ?>
    <?php
        if(in_array('credits', wpjobportal::$_active_addons)){
            do_action('wpjobportal_addons_admin_sidemenu_package',$c,$layout);
        }else{

            $plugininfo = checkWPJPPluginInfo('wp-job-portal-credits/wp-job-portal-credits.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=wp-job-portal-credits&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://wpjobportal.com/product/credit-system/";
            } ?>
            <li class="treeview">
                <a href="javascript: void(0);" title="<?php echo esc_html(__('Credits' , 'wp-job-portal')); ?>">
                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/grey-menu/credits.png'; ?>" alt="<?php echo esc_html(__('credits' , 'wp-job-portal')); ?>" class="wpjobportaladmin-menu-icon">
                    <span class="wpjobportaladmin-text disabled-menu"><?php echo esc_html(__('Credits' , 'wp-job-portal')); ?></span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="disabled-menu">
                        <span class="wpjobportaladmin-text"><?php echo esc_html(__('Credits' , 'wp-job-portal')); ?></span>
                        <a href="<?php echo esc_url($url); ?>" class="wpjobportaladmin-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                    </li>
                </ul>
            </li>
    <?php } ?>
    <li class="treeview <?php if($c == 'wpjobportal_country' || $c == 'wpjobportal_addressdata' || $c == 'wpjobportal_state' || $c == 'wpjobportal_city') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_country" title="<?php echo esc_html(__('countries' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('countries' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/address-data.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Address Data' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_city' && ($layout == 'loadaddressdata')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_city&wpjobportallt=loadaddressdata" title="<?php echo esc_html(__('load address data', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Load Address Data', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_city' && ($layout == 'locationnamesettings')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_city&wpjobportallt=locationnamesettings" title="<?php echo esc_html(__('Loaction Name settings', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Loaction Name Settings', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if(($c == 'wpjobportal_country' && $layout != 'formcountry') || $c == 'wpjobportal_state' || $c == 'wpjobportal_city' && ($layout == 'formcity' || $layout == '' )) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_country" title="<?php echo esc_html(__('countries', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Countries', 'wp-job-portal')); ?>&nbsp;/&nbsp;<?php echo esc_html(__('Cities', 'wp-job-portal')); ?>
                </a>
            </li>
            <?php /*
            <li class="<?php if($c == 'wpjobportal_country' && ($layout == 'formcountry')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_country&wpjobportallt=formcountry" title="<?php echo esc_html(__('add new country', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Country', 'wp-job-portal')); ?>
                </a>
            </li>
            */?>
        </ul>
    </li>
    <?php
        if(in_array('folder', wpjobportal::$_active_addons)){
            do_action('wpjobportal_addons_admin_sidemenu_links_for_folder' , $c,$layout );
        }else{
            $plugininfo = checkWPJPPluginInfo('wp-job-portal-folder/wp-job-portal-folder.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=wp-job-portal-folder&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://wpjobportal.com/product/folders/";
            } ?>
            <li class="treeview">
                <a href="javascript: void(0);" title="<?php echo esc_html(__('Folder' , 'wp-job-portal')); ?>">
                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/grey-menu/folders.png'; ?>" alt="<?php echo esc_html(__('folder' , 'wp-job-portal')); ?>" class="wpjobportaladmin-menu-icon">
                    <span class="wpjobportaladmin-text disabled-menu"><?php echo esc_html(__('Folder' , 'wp-job-portal')); ?></span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="disabled-menu">
                        <span class="wpjobportaladmin-text"><?php echo esc_html(__('Folder' , 'wp-job-portal')); ?></span>
                        <a href="<?php echo esc_url($url); ?>" class="wpjobportaladmin-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                    </li>
                </ul>
            </li>
    <?php } ?>

    <li class="treeview <?php if($c == 'wpjobportal_jobtype') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_jobtype" title="<?php echo esc_html(__('job types' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('job types' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/job-types.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Job Types' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_jobtype' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_jobtype" title="<?php echo esc_html(__('job types','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Job Types','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_jobtype' && ($layout == 'formjobtype')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_jobtype&wpjobportallt=formjobtype" title="<?php echo esc_html(__('add new job type','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Job Type','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_jobstatus') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_jobstatus" title="<?php echo esc_html(__('job status' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('job status' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/status.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Job Status' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_jobstatus' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_jobstatus" title="<?php echo esc_html(__('job status','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Job Status','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_jobstatus' && ($layout == 'formjobstatus')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_jobstatus&wpjobportallt=formjobstatus" title="<?php echo esc_html(__('add new job status','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Job Status','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php /*<li class="treeview <?php if($c == 'wpjobportal_shift') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_shift" title="<?php echo esc_html(__('shifts' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('shifts' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/job-shifts.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Shifts' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_shift' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_shift" title="<?php echo esc_html(__('shifts','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Shifts','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_shift' && ($layout == 'formshift')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_shift&wpjobportallt=formshift" title="<?php echo esc_html(__('add new shift','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Shift','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li> */ ?>
    <li class="treeview <?php if($c == 'wpjobportal_highesteducation') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_highesteducation" title="<?php echo esc_html(__('highest educations' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('highest educations' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/highest-education.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Highest Educations' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_highesteducation' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_highesteducation" title="<?php echo esc_html(__('highest educations','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Highest Educations','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_highesteducation' && ($layout == 'formhighesteducation')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_highesteducation&wpjobportallt=formhighesteducation" title="<?php echo esc_html(__('add new highest education','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Highest Education','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php /*<li class="treeview <?php if($c == 'wpjobportal_age') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_age" title="<?php echo esc_html(__('ages' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('ages' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/ages.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Ages' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_age' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_age" title="<?php echo esc_html(__('ages','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Ages','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_age' && ($layout == 'formages')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_age&wpjobportallt=formages" title="<?php echo esc_html(__('add new age','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Age','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li> */ ?>
    <li class="treeview <?php if($c == 'wpjobportal_careerlevel') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_careerlevel" title="<?php echo esc_html(__('career levels' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('career levels' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/career-levels.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Career Levels' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_careerlevel' && ($layout == 'wpjobportal_careerlevel' || $layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_careerlevel" title="<?php echo esc_html(__('career levels','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Career Levels','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_careerlevel' && ($layout == 'formcareerlevels')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_careerlevel&wpjobportallt=formcareerlevels" title="<?php echo esc_html(__('add new career level','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Career Level','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_currency') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_currency" title="<?php echo esc_html(__('currency' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('currency' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/currency.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Currency' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_currency' && ($layout == 'wpjobportal_currency' || $layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_currency" title="<?php echo esc_html(__('currency','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Currency','wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_currency' && ($layout == 'formcurrency')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_currency&wpjobportallt=formcurrency" title="<?php echo esc_html(__('add new currency','wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Currency','wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_category') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_category" title="<?php echo esc_html(__('categories' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('categories' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/category.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Categories' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_category' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_category" title="<?php echo esc_html(__('categories', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Categories', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_category' && ($layout == 'formcategory')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_category&wpjobportallt=formcategory" title="<?php echo esc_html(__('add new category', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Category', 'wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <?php
        if(in_array('tag', wpjobportal::$_active_addons)){
            do_action('wpjobportal_addons_admin_sidemenu_links_for_tags',$c,$layout);
        }else{
            $plugininfo = checkWPJPPluginInfo('wp-job-portal-tag/wp-job-portal-tag.php');
            if($plugininfo['availability'] == "1"){
                $text = $plugininfo['text'];
                $url = "plugins.php?s=wp-job-portal-tag&plugin_status=inactive";
            }elseif($plugininfo['availability'] == "0"){
                $text = $plugininfo['text'];
                $url = "https://wpjobportal.com/product/tags/";
            } ?>
            <li class="treeview">
                <a href="javascript: void(0);" title="<?php echo esc_html(__('Tags' , 'wp-job-portal')); ?>">
                    <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/grey-menu/tags.png'; ?>" alt="<?php echo esc_html(__('tags' , 'wp-job-portal')); ?>" class="wpjobportaladmin-menu-icon">
                    <span class="wpjobportaladmin-text disabled-menu"><?php echo esc_html(__('Tags' , 'wp-job-portal')); ?></span>
                </a>
                <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
                    <li class="disabled-menu">
                        <span class="wpjobportaladmin-text"><?php echo esc_html(__('Tags' , 'wp-job-portal')); ?></span>
                        <a href="<?php echo esc_url($url); ?>" class="wpjobportaladmin-install-btn" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
                    </li>
                </ul>
            </li>
    <?php } ?>
    <li class="treeview <?php if($c == 'wpjobportal_salaryrange' || $c == 'wpjobportal_salaryrangetype' ) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_salaryrangetype" title="<?php echo esc_html(__('salary range' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('salary range' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/salary-range.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Salary Range' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_salaryrangetype' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_salaryrangetype" title="<?php echo esc_html(__('salary range type', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Salary Range Type', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_salaryrangetype' && ($layout == 'formsalaryrangetype')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_salaryrangetype&wpjobportallt=formsalaryrangetype" title="<?php echo esc_html(__('add new salary range type', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Add New Salary Range Type', 'wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_user' || $c == 'wpjobportal_customfield' || ($c == 'wpjobportal_fieldordering' && ($layout == '') && $ff == 4)) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_user" title="<?php echo esc_html(__('users' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('users' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/users.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Users' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_user' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_user" title="<?php echo esc_html(__('users', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Users', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_user' && ($layout == 'assignrole')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_user&wpjobportallt=assignrole" title="<?php echo esc_html(__('assign role', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Assign Role', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_fieldordering' || $c == 'wpjobportal_customfield'  && ($layout == '' || $layout == 'formuserfield')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_fieldordering&ff=4" title="<?php echo esc_html(__('fields', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Fields', 'wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal_emailtemplate' || $c == 'wpjobportal_emailtemplatestatus') echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal_emailtemplate" title="<?php echo esc_html(__('email templates' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('email templates' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/email-templates.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Email Templates' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal_emailtemplatestatus' && ($layout == '')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplatestatus" title="<?php echo esc_html(__('options', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Options', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ew-cm') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=ew-cm" title="<?php echo esc_html(__('new company', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('New Company', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'd-cm') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=d-cm" title="<?php echo esc_html(__('delete company', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Delete Company', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'cm-sts') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=cm-sts" title="<?php echo esc_html(__('company status', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Company Status', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ew-ob') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=ew-ob" title="<?php echo esc_html(__('new job', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('New Job', 'wp-job-portal')); ?>
                </a>
            </li>
            <?php if(in_array('visitorcanaddjob', wpjobportal::$_active_addons)){ ?>
                <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ew-obv') echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_emailtemplate&for=ew-obv" title="<?php echo esc_html(__('new visitor job', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('New Visitor Job', 'wp-job-portal')); ?>
                    </a>
                </li>
            <?php } ?>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ob-sts') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=ob-sts" title="<?php echo esc_html(__('job status', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Job Status', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ob-d') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=ob-d" title="<?php echo esc_html(__('job delete', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Job Delete', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ew-rm') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=ew-rm" title="<?php echo esc_html(__('new resume', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('New Resume', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ew-rmv') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=ew-rmv" title="<?php echo esc_html(__('new visitor resume', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('New Visitor Resume', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'rm-sts') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=rm-sts" title="<?php echo esc_html(__('resume status', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Resume Status', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'd-rs') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=d-rs" title="<?php echo esc_html(__('delete resume', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Delete Resume', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'em-n') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=em-n" title="<?php echo esc_html(__('new employer', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('New Employer', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'obs-n') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=obs-n" title="<?php echo esc_html(__('new job seeker', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('New Job Seeker', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ad-jap') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=ad-jap" title="<?php echo esc_html(__('job apply admin', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Job Apply Admin', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'em-jap') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=em-jap" title="<?php echo esc_html(__('job apply employer', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Job Apply Employer', 'wp-job-portal')); ?>
                </a>
            </li>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'js-jap') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=js-jap" title="<?php echo esc_html(__('job apply job seeker', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Job Apply Job Seeker', 'wp-job-portal')); ?>
                </a>
            </li>
             <?php if(in_array('resumeaction', wpjobportal::$_active_addons)){ ?>
                <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ap-jap') echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_emailtemplate&for=ap-jap" title="<?php echo esc_html(__('applied resume status change', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('Applied Resume Status Change', 'wp-job-portal')); ?>
                    </a>
                </li>
            <?php } ?>

             <?php if(in_array('message', wpjobportal::$_active_addons)){ ?>
                <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'new-msg') echo 'active'; ?>">
                    <a href="admin.php?page=wpjobportal_emailtemplate&for=new-msg" title="<?php echo esc_html(__('new message alert', 'wp-job-portal')); ?>">
                        <?php echo esc_html(__('New Message Alert', 'wp-job-portal')); ?>
                    </a>
                </li>
            <?php } ?>

             <?php if(in_array('jobalert', wpjobportal::$_active_addons)){ ?>
                    <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'jb-at') echo 'active'; ?>">
                        <a href="admin.php?page=wpjobportal_emailtemplate&for=jb-at" title="<?php echo esc_html(__('job alert', 'wp-job-portal')); ?>">
                            <?php echo esc_html(__('Job Alert', 'wp-job-portal')); ?>
                        </a>
                    </li>
            <?php } ?>
            <?php if(in_array('tellfriend', wpjobportal::$_active_addons)){ ?>
            <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'jb-to-fri') echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal_emailtemplate&for=jb-to-fri" title="<?php echo esc_html(__('tell to friend', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Tell To Friend', 'wp-job-portal')); ?>
                </a>
            </li>
            <?php } ?>

                <?php if(in_array('credits', wpjobportal::$_active_addons)){ ?>
                        <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ew-pk-ad') echo 'active'; ?>">
                            <a href="admin.php?page=wpjobportal_emailtemplate&for=ew-pk-ad" title="<?php echo esc_html(__('Purchase Package Admin', 'wp-job-portal')); ?>">
                                <?php echo esc_html(__('Purchase Package Admin', 'wp-job-portal')); ?>
                            </a>
                        </li>
                        <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'ew-pk') echo 'active'; ?>">
                            <a href="admin.php?page=wpjobportal_emailtemplate&for=ew-pk" title="<?php echo esc_html(__('Purchase Package Admin', 'wp-job-portal')); ?>">
                                <?php echo esc_html(__('Purchase Package', 'wp-job-portal')); ?>
                            </a>
                        </li>
                        <li class="<?php if($c == 'wpjobportal_emailtemplate' && $for == 'st-pk') echo 'active'; ?>">
                            <a href="admin.php?page=wpjobportal_emailtemplate&for=st-pk" title="<?php echo esc_html(__('Purchase Package Admin', 'wp-job-portal')); ?>">
                                <?php echo esc_html(__('Purchase Status', 'wp-job-portal')); ?>
                            </a>
                        </li>
                <?php } ?>
        </ul>
    </li>
    <li class="treeview <?php if($c == 'wpjobportal' && $layout == 'help' ) echo 'active'; ?>">
        <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo esc_html(__('help' , 'wp-job-portal')); ?>">
            <img class="wpjobportaladmin-menu-icon" alt="<?php echo esc_html(__('help' , 'wp-job-portal')); ?>" src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/control_panel/dashboard/admin-left-menu/help.png'; ?>" />
            <span class="wpjobportaladmin-text">
                <?php echo esc_html(__('Help' , 'wp-job-portal')); ?>
            </span>
        </a>
        <ul class="wpjobportaladmin-sidebar-submenu treeview-menu">
            <li class="<?php if($c == 'wpjobportal' && ($layout == 'help')) echo 'active'; ?>">
                <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo esc_html(__('help', 'wp-job-portal')); ?>">
                    <?php echo esc_html(__('Help', 'wp-job-portal')); ?>
                </a>
            </li>
        </ul>
    </li>
</ul>
<?php
$menu_js_script = '
    var cookielist = document.cookie.split(";");
    for (var i=0; i<cookielist.length; i++) {
        if (cookielist[i].trim() == "wpjobportaladmin_collapse_admin_menu=1") {
            jQuery("#wpjobportaladmin-wrapper").addClass("menu-collasped-active");
            break;
        }
    }

    jQuery(document).ready(function(){

        var pageWrapper = jQuery("#wpjobportaladmin-wrapper");
        var sideMenuArea = jQuery("#wpjobportaladmin-leftmenu");

        jQuery("#wpjobportaladmin-menu-toggle").on("click", function () {

            if (pageWrapper.hasClass("menu-collasped-active")) {
                pageWrapper.removeClass("menu-collasped-active");
                document.cookie = "wpjobportaladmin_collapse_admin_menu=0; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/";
            }else{
                pageWrapper.addClass("menu-collasped-active");
                document.cookie = "wpjobportaladmin_collapse_admin_menu=1; expires=Sat, 01 Jan 2050 00:00:00 UTC; path=/";
            }

        });

        // to set anchor link active on menu collpapsed
        jQuery(".wpjobportaladmin-sidebar-menu li.treeview a").on("click", function() {
            if (!(pageWrapper.hasClass("menu-collasped-active"))) {
                window.location.href = jQuery(this).attr("href");
            }
        });
    });

    ';
wp_add_inline_script( 'wpjobportal-menu-handle', $menu_js_script );    
?>
