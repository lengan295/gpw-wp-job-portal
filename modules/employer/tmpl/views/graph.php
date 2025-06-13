<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
/**
 * @param job      job object - optional
*/
?>
<?php
wp_enqueue_script( 'jp-google-charts', esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/js/google-charts.js', array(), '1.1.1', false );
wp_register_script( 'google-charts-handle', '' );
wp_enqueue_script( 'google-charts-handle' );
?>
<?php
//if(WPJOBPORTALincluder::getObjectClass('user')->isemployer()){ ?>
    <div id='wpjobportal-center' class="wjportal-cp-graph-inner-wrp">
    <?php
    $js_script = "

            google.charts.load('current', {'packages':['corechart']});
            google.setOnLoadCallback(drawStackChartHorizontal);
            function drawStackChartHorizontal() {
                var data = google.visualization.arrayToDataTable([
                        ". wpjobportal::$_data['stack_chart_horizontal']['title'] . ",
                        ". wpjobportal::$_data['stack_chart_horizontal']['data'] ."
    	        ]);
                var view = new google.visualization.DataView(data);
                ";
                if (wpjobportal::$theme_chk == 1) { 
                    $js_script .= "
                    var options = {
                        title: '". esc_html(__('Job Apply', 'wp-job-portal')) ."',
                        'height':500,
                        isStacked: true,
                        legend: {position: 'top'},
                        hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},
                        vAxis: {minValue: 0},
                    };
                    ";
                } else { 
                    $js_script .= "
                    var options = {
                        title: '". esc_html(__('Job Apply', 'wp-job-portal')) ."',
                        'height':400,
                        'width':734,
                        isStacked: true,
                        legend: {position: 'top'},
                        hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},
                        vAxis: {minValue: 0},
                        chartArea: {
                            left: 65,
                            width: 640,
                        }
                    };";
                } 
                $js_script .= "
                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        ";
         wp_add_inline_script( 'google-charts-handle', $js_script );
        ?>
        <div id="chart_div" class="wjportal-cp-graph employer"></div>
    </div>
<?php 
//}
?>
	
