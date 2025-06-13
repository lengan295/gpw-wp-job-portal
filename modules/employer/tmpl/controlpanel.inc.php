<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
wp_enqueue_script( 'jp-google-charts', esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/js/google-charts.js', array(), '1.1.1', false );
wp_register_script( 'google-charts-handle', '' );
wp_enqueue_script( 'google-charts-handle' );
?>
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
        var counter = jQuery('span.count_notifications').text();
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
        google.load('visualization', '1', {packages:['corechart']});



        /*function showLoginPopup(){
            jQuery('div#wpjobportal-popup-background').show();
            jQuery('div#wpjobportal-popup').show();
        }*/
    ";
    wp_add_inline_script( 'wpjobportal-inline-handle', $inline_js_script );
?>
<?php

/*function wpjobportal_employercheckLinks($name) {
    $print = false;
    switch ($name) {
        case 'formcompany': $visname = 'vis_emformcompany';
        break;
        case 'alljobsappliedapplications': $visname = 'vis_emalljobsappliedapplications';
        break;
        case 'mycompanies': $visname = 'vis_emmycompanies';
        break;
        case 'resumesearch': $visname = 'vis_emresumesearch';
        break;
        case 'formjob': $visname = 'vis_emformjob';
        break;
        case 'my_resumesearches': $visname = 'vis_emmy_resumesearches';
        break;
        case 'myjobs': $visname = 'vis_emmyjobs';
        break;
        case 'formdepartment': $visname = 'vis_emformdepartment';
        break;
        case 'my_stats': $visname = 'vis_emmy_stats';
        break;
        case 'empresume_rss': $visname = 'vis_resume_rss';
        break;
        case 'newfolders': $visname = 'vis_emnewfolders';
        break;
        case 'empregister': $visname = 'vis_emempregister';
        break;
        case 'empcredits': $visname = 'vis_empcredits';
        break;
        case 'empcreditlog': $visname = 'vis_empcreditlog';
        break;
        case 'emppurchasehistory': $visname = 'vis_emppurchasehistory';
        break;
        case 'empmessages': $visname = 'vis_emmessages';
        break;
        case 'empregister': $visname = 'vis_emregister';
        break;
        case 'empratelist': $visname = 'vis_empratelist';
        break;
        case 'jobs_graph': $visname = 'vis_jobs_graph';
        break;
        case 'resume_graph': $visname = 'vis_resume_graph';
        break;
        case 'box_newestresume': $visname = 'vis_box_newestresume';
        break;
        case 'box_appliedresume': $visname = 'vis_box_appliedresume';
        break;
        case 'emploginlogout': $visname = 'emploginlogout';
        break;
        case 'empmystats': $visname = 'vis_empmystats';
        break;
        case 'emresumebycategory': $visname = 'vis_emresumebycategory';
        break;
        case 'temp_employer_dashboard_stats_graph': $visname = 'vis_temp_employer_dashboard_stats_graph';
        break;
        case 'temp_employer_dashboard_useful_links': $visname = 'vis_temp_employer_dashboard_useful_links';
        break;
        case 'temp_employer_dashboard_applied_resume': $visname = 'vis_temp_employer_dashboard_applied_resume';
        break;
        case 'temp_employer_dashboard_saved_search': $visname = 'vis_temp_employer_dashboard_saved_search';
        break;
        case 'temp_employer_dashboard_credits_log': $visname = 'vis_temp_employer_dashboard_credits_log';
        break;
        case 'temp_employer_dashboard_purchase_history': $visname = 'vis_temp_employer_dashboard_purchase_history';
        break;
        case 'temp_employer_dashboard_newest_resume': $visname = 'vis_temp_employer_dashboard_newest_resume';
        break;
        default:$visname = 'vis_em' . $name;
        break;
    }

    $isouruser = WPJOBPORTALincluder::getObjectClass('user')->isWPJOBPortalUser();
    $isguest = WPJOBPORTALincluder::getObjectClass('user')->isguest();

    $guest = false;

    if($isguest == true){
        $guest = true;
    }
    if($isguest == false && $isouruser == false){
        $guest = true;
    }

    $config_array = wpjobportal::$_data['config'];

    if ($guest == false) {
        if (WPJOBPORTALincluder::getObjectClass('user')->isemployer()) {
            if ($config_array[$name] == 1){
                $print = true;
            }
        }elseif (WPJOBPORTALincluder::getObjectClass('user')->isjobseeker()) {
            if ($config_array["$visname"] == 1) {
                $print = true;
            }
        }
    } else {
        if ($config_array["$visname"] == 1)
            $print = true;
    }
    return $print;
}*/

function jobWrapper($resumeid, $path, $first_name, $middle_name, $last_name, $application_title, $email_address, $Category) {
    $html = '<div class="job-wrapper">
    <div class="img">
        <a href="' . esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resumeid))) . '">
            <img src="' . esc_url($path) . '">
        </a>
    </div>
    <div class="detail">
       <div class="upper">
          <a href="' . esc_url(wpjobportal::wpjobportal_makeUrl(array('wpjobportalme'=>'resume', 'wpjobportallt'=>'viewresume', 'wpjobportalid'=>$resumeid))) . '">' . esc_html($first_name) . ' ' . esc_html($middle_name) . ' ' . esc_html($last_name) . '</a>
      </div>
      <div class="lower">
          <div class="resume_title">(' . esc_html($application_title) . ')</div>
          <div class="for-rtl">
             <span class="text">'. esc_html(__('Email','wp-job-portal')) .': </span>
             <span class="get-text ">' . esc_html($email_address) . '</span>
         </div>
         <div class="for-rtl">
             <span class="text">'. esc_html(__('Category','wp-job-portal')) .': </span>
             <span class="get-text">' . wpjobportal::wpjobportal_getVariableValue($Category) . '</span>
         </div>
     </div>
 </div>
</div>';
return $html;
}
?>
