<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
    wp_register_script( 'wpjobportal-inline-handle', '' );
    wp_enqueue_script( 'wpjobportal-inline-handle' );

    $inline_js_script = "
        jQuery(document).ready(function() {
            //for notifications
            jQuery('div.notifications').hide();
                    jQuery('img.notifications').on('click', function(){
            jQuery('div.notifications, div.notifications').slideToggle();
            });
                    jQuery('span.count_notifications').on('click', function(){
            jQuery('div.notifications, div.notifications').slideToggle();
            });
                    //for messages
                    jQuery('div.messages').hide();
                    jQuery('img.messages').on('click', function(){
            jQuery('div.messages, div.messages').slideToggle();
            });
                    jQuery('span.count_messages').on('click', function(){
            jQuery('div.messages, div.messages').slideToggle();
            });
                    jQuery('div#wpjobportal-popup-background, img#popup_cross').click(function(){
            jQuery('div#wpjobportal-popup').hide();
                    jQuery('div#wpjobportal-popup-background').hide();
            });
        });

        function updateNotificationStatus () {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action:'wpjobportal_ajax', wpjobportalme:'notifications', task:'changeNotifyOfNotifications', '_wpnonce':'". esc_attr(wp_create_nonce("change-notify-of-notifications"))."'}, function(data){
                jQuery('span.count_notifications').html(0);
            });
        }

        function updateNotificationStatusView (notid) {
            var ajaxurl = \"". esc_url_raw(admin_url('admin-ajax.php')) ."\";
            jQuery.post(ajaxurl, {action:'wpjobportal_ajax', wpjobportalme:'notifications', task:'changeViewOfNotifications', noid:notid, '_wpnonce':'". esc_attr(wp_create_nonce("change-view-of-notifications"))."'}, function(data){
                jQuery('#view-notifications-div').slideUp();
                return;
            });
        }
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<?php
wp_enqueue_script( 'jp-google-charts', esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/js/google-charts.js', array(), '1.1.1', false );
wp_register_script( 'google-charts-handle', '' );
wp_enqueue_script( 'google-charts-handle' );
    //this code may not in use
if(!empty(wpjobportal::$_data['stack_chart_horizontal']['data'])){
    $js_script = "
        google.load('visualization', '1', {packages:['corechart']});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                        ". wpjobportal::$_data['stack_chart_horizontal']['title'] .",
                        ". wpjobportal::$_data['stack_chart_horizontal']['data'] ."
                        ]);
            var options = {
                            title: '". esc_html(__('Number of jobs in last six months', 'wp-job-portal')) ."'
                                    , pointSize: 6
                                    , colors:['#1EADD8', '#179650', '#D98E11', '#DB624C', '#5F3BBB']
                                    , curveType: 'function'
                                    , legend: { position: 'none' }
                            , focusTarget: 'category'
                                    , chartArea: {width:'90%', top:50}
                            , vAxis: { format: '0'}
                        };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }
    ";
    wp_add_inline_script( 'google-charts', $js_script );
}

?>

 <?php

/*function wpjobportal_jobseekercheckLinks($name) {

    $print = false;
    switch ($name) {
        case 'formresume': $visname = 'vis_jsformresume';
            break;
        case 'jobcat': $visname = 'vis_wpjobportalcat';
            break;
        case 'myresumes': $visname = 'vis_jsmyresumes';
            break;
        case 'listnewestjobs': $visname = 'vis_jslistnewestjobs';
            break;
        case 'listallcompanies': $visname = 'vis_jslistallcompanies';
            break;
        case 'listjobbytype': $visname = 'vis_jslistjobbytype';
            break;
        case 'myappliedjobs': $visname = 'vis_jsmyappliedjobs';
            break;
        case 'jobsearch': $visname = 'vis_wpjobportalearch';
            break;
        case 'my_jobsearches': $visname = 'vis_jsmy_jobsearches';
            break;
       case 'jscredits': $visname = 'vis_jscredits';
            break;
        case 'jscreditlog': $visname = 'vis_jscreditlog';
            break;
        case 'jspurchasehistory': $visname = 'vis_jspurchasehistory';
            break;
        case 'jsratelist': $visname = 'vis_jsratelist';
            break;
        case 'jsmy_stats': $visname = 'vis_jsmy_stats';
            break;
        case 'jobalertsetting': $visname = 'vis_wpjobportalalertsetting';
            break;
        case 'jsmessages': $visname = 'vis_jsmessages';
            break;
        case 'wpjobportal_rss': $visname = 'vis_job_rss';
            break;
        case 'jsregister': $visname = 'vis_jsregister';
            break;
        case 'jsactivejobs_graph': $visname = 'vis_jsactivejobs_graph';
            break;
        case 'jssuggestedjobs_box': $visname = 'vis_jssuggestedjobs_box';
            break;
        case 'jsappliedresume_box': $visname = 'vis_jsappliedresume_box';
            break;
        case 'listjobshortlist': $visname = 'vis_jslistjobshortlist';
            break;
        case 'jsmystats': $visname = 'vis_jsmystats';
            break;
        case 'jobsloginlogout': $visname = 'jobsloginlogout';
            break;
        case 'temp_jobseeker_dashboard_jobs_graph': $visname = 'vis_temp_jobseeker_dashboard_jobs_graph';
            break;
        case 'temp_jobseeker_dashboard_useful_links': $visname = 'vis_temp_jobseeker_dashboard_useful_links';
            break;
        case 'temp_jobseeker_dashboard_apllied_jobs': $visname = 'vis_temp_jobseeker_dashboard_apllied_jobs';
            break;
        case 'temp_jobseeker_dashboard_shortlisted_jobs': $visname = 'vis_temp_jobseeker_dashboard_shortlisted_jobs';
            break;
        case 'temp_jobseeker_dashboard_credits_log': $visname = 'vis_temp_jobseeker_dashboard_credits_log';
            break;
        case 'temp_jobseeker_dashboard_purchase_history': $visname = 'vis_temp_jobseeker_dashboard_purchase_history';
            break;
        case 'temp_jobseeker_dashboard_newest_jobs': $visname = 'vis_temp_jobseeker_dashboard_newest_jobs';
            break;
        case 'jobsbycities': $visname = 'vis_jobsbycities';
            break;

        default:$visname = 'vis_js' . $name;
            break;
    }
    $isouruser = WPJOBPORTALincluder::getObjectClass('user')->isisWPJobportalUser();
    $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();

    $guest = false;

    if($isguest == true){
        $guest = true;
    }
    if($isguest == false && $isouruser == false){
        $guest = true;
    }

    $config_array = wpjobportal::$_data['configs'];
    if ($guest == false) {
        if (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
            if (isset($config_array[$name]) && $config_array[$name] == 1)
               $print = true;
        }elseif (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
            if ($config_array['employerview_js_controlpanel'] == 1)
                if (isset($config_array["$visname"]) && $config_array["$visname"] == 1) {
                    $print = true;
                }
        }
    } else {
        if (isset($config_array["$visname"]) && $config_array["$visname"] == 1)
            $print = true;
        }

    return $print;
}*/

?>
