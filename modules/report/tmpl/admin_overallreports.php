<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php
    wp_enqueue_style('status-graph', esc_url(WPJOBPORTAL_PLUGIN_URL) . 'includes/css/status_graph.css');
?>
<!-- main wrapper -->
<div id="wpjobportaladmin-wrapper">
    <!-- left menu -->
    <div id="wpjobportaladmin-leftmenu">
        <?php  WPJOBPORTALincluder::getClassesInclude('wpjobportaladminsidemenu'); ?>
    </div>
    <div id="wpjobportaladmin-data">
        <?php
            $msgkey = WPJOBPORTALincluder::getJSModel('report')->getMessagekey();
            WPJOBPORTALMessages::getLayoutMessage($msgkey);
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        ?>
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
                        <li><?php echo esc_html(__('Overall Reports','wp-job-portal')); ?></li>
                    </ul>
                </div>
            </div>
            <div id="wpjobportal-wrapper-top-right">
                <div id="wpjobportal-config-btn">
                    <a href="admin.php?page=wpjobportal_configuration" title="<?php echo esc_html(__('configuration','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/config.png">
                   </a>
                </div>
                <div id="wpjobportal-help-btn" class="wpjobportal-help-btn">
                    <a href="admin.php?page=wpjobportal&wpjobportallt=help" title="<?php echo esc_html(__('help','wp-job-portal')); ?>">
                        <img src="<?php echo esc_url(WPJOBPORTAL_PLUGIN_URL); ?>includes/images/control_panel/dashboard/help.png">
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
                <?php echo esc_html(__('Overall Reports', 'wp-job-portal')); ?>
            </h1>
        </div>
        <!-- page content -->
        <div id="wpjobportal-admin-wrapper" class="p0 bg-n bs-n">
            <?php
            if(isset(wpjobportal::$_data['tot_jobs']) && !empty(wpjobportal::$_dat['tot_jobs'])){
                $total_jobs = @round((wpjobportal::$_data['tot_jobs'] / wpjobportal::$_data['totaljobs']) * 100);
            }else{
               $total_jobs = 100;
            }
            $total_appliedresume = 0;
            if(isset(wpjobportal::$_data['totaljobs']) && !empty(wpjobportal::$_dat['totaljobs'])){
                $total_appliedresume = @round((wpjobportal::$_data['totalappliedresume'] / wpjobportal::$_data['totaljobs']) * 100);
            }else{
               $total_appliedresume = 100;
            }

            $tot_comp = 0;
            if(isset(wpjobportal::$_data['tot_comp']) && !empty(wpjobportal::$_dat['tot_comp'])){
                $tot_comp = @round((wpjobportal::$_data['totalcompany'] / wpjobportal::$_data['tot_comp']) * 100);
            }else{
               $tot_comp = 100;
            }

            $tot_resume = 0;
            if(isset(wpjobportal::$_data['presume']) && !empty(wpjobportal::$_dat['presume'])){
                $tot_resume = @round((wpjobportal::$_data['presume'] / wpjobportal::$_data['totalresume']) * 100);
            }else{
               $tot_resume = 100;
            }
            ?>
            <!-- count boxes -->
            <div class="wpjobportal-count-wrp">
                <div class="wpjobportal-count-link">
                    <a class="wpjobportal-count-link wpjobportal-count-jobs" href="admin.php?page=wpjobportal_job" data-tab-number="1">
                        <div class="wpjobportal-count-cricle-wrp" data-per="<?php echo esc_attr($total_jobs); ?>" data-tab-number="1">
                            <div class="js-mr-rp" data-progress="<?php echo esc_attr($total_jobs); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="wpjobportal-count-link-text">
                            <?php
                                echo esc_html(__('Total Jobs', 'wp-job-portal'));
                                echo ' ( '.esc_html(wpjobportal::$_data['totaljobs']).' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="wpjobportal-count-link">
                    <a class="wpjobportal-count-link wpjobportal-count-resume" href="admin.php?page=wpjobportal_resume" data-tab-number="2">
                        <div class="wpjobportal-count-cricle-wrp" data-per="<?php echo esc_attr($tot_resume); ?>" >
                            <div class="js-mr-rp" data-progress="<?php echo esc_attr($tot_resume); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="wpjobportal-count-link-text">
                            <?php
                                echo esc_html(__('Total Resume', 'wp-job-portal'));
                                echo ' ( '. esc_html(wpjobportal::$_data['totalresume']).' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="wpjobportal-count-link">
                    <a class="wpjobportal-count-link wpjobportal-count-companies" href="admin.php?page=wpjobportal_company" data-tab-number="3">
                        <div class="wpjobportal-count-cricle-wrp" data-per="<?php echo esc_attr($tot_comp); ?>">
                            <div class="js-mr-rp" data-progress="<?php echo esc_attr($tot_comp); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="wpjobportal-count-link-text">
                            <?php
                                echo esc_html(__('Total Companies', 'wp-job-portal'));
                                echo ' ( '. esc_html(wpjobportal::$_data['totalcompany']).' )';
                            ?>
                        </div>
                    </a>
                </div>
                <div class="wpjobportal-count-link">
                    <a class="wpjobportal-count-link wpjobportal-count-active-jobs" href="admin.php?page=wpjobportal_job" data-tab-number="4">
                        <div class="wpjobportal-count-cricle-wrp" data-per="<?php echo esc_attr($total_appliedresume); ?>" >
                            <div class="js-mr-rp" data-progress="<?php echo esc_attr($total_appliedresume); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="wpjobportal-count-link-text">
                            <?php
                                echo esc_html(__('Applied resume', 'wp-job-portal'));
                                echo ' ( '. esc_html(wpjobportal::$_data['totalappliedresume']).' )';
                            ?>
                        </div>
                    </a>
                </div>
            </div>
            <div class="wpjobportal-report">
                <div class="wpjobportal-report-heading">
                    <?php echo esc_html(__('Overall Statistics','wp-job-portal')); ?>
                </div>
                <div id="curve_chart" class="wpjobportal-report-chart"></div>
            </div>
            <div class="wpjobportal-report halfwidth">
                <div class="wpjobportal-report-heading">
                    <?php echo esc_html(__('Report By Job Categories','wp-job-portal')); ?>
                </div>
                <div id="catbar1" class="wpjobportal-report-chart"></div>
            </div>
            <div class="wpjobportal-report halfwidth">
                <div class="wpjobportal-report-heading">
                    <?php echo esc_html(__('Report By Resume Categories','wp-job-portal')); ?>
                </div>
                <div id="catbar2" class="wpjobportal-report-chart"></div>
            </div>
            <div class="wpjobportal-report halfwidth">
                <div class="wpjobportal-report-heading">
                    <?php echo esc_html(__('Report By Job Cities','wp-job-portal')); ?>
                </div>
                <div id="citybar1" class="wpjobportal-report-chart"></div>
            </div>
            <div class="wpjobportal-report halfwidth">
                <div class="wpjobportal-report-heading">
                    <?php echo esc_html(__('Report By Company Cities','wp-job-portal')); ?>
                </div>
                <div id="citypie" class="wpjobportal-report-chart"></div>
            </div>
            <?php if(in_array('advanceresumebuilder', wpjobportal::$_active_addons)){ ?>
                <div class="wpjobportal-report halfwidth">
                    <div class="wpjobportal-report-heading">
                        <?php echo esc_html(__('Report By Resume Cities','wp-job-portal')); ?>
                    </div>
                    <div id="citybar2" class="wpjobportal-report-chart"></div>
                </div>
            <?php } ?>
            <div class="wpjobportal-report halfwidth">
                <div class="wpjobportal-report-heading">
                    <?php echo esc_html(__('Report By Job Types','wp-job-portal')); ?>
                </div>
                <div id="jobtypebar1" class="wpjobportal-report-chart"></div>
            </div>
            <div class="wpjobportal-report halfwidth">
                <div class="wpjobportal-report-heading">
                    <?php echo esc_html(__('Report By Resume Types','wp-job-portal')); ?>
                </div>
                <div id="jobtypebar2" class="wpjobportal-report-chart"></div>
            </div>
        </div>
    </div>
</div>
<?php
wp_enqueue_script( 'jp-google-charts', esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/js/google-charts.js', array(), '1.1.1', false );
wp_register_script( 'google-charts-handle', '' );
wp_enqueue_script( 'google-charts-handle' );
$drawChartTop_js_script = "
    google.charts.load('current', {'packages':['corechart']});
    google.setOnLoadCallback(drawChartTop);
            function drawChartTop() {
            var data = new google.visualization.DataTable();
                    data.addColumn('date', '". esc_html(__("Dates", 'wp-job-portal'))."');
                    data.addColumn('number', '". esc_html(__("Jobs", 'wp-job-portal')) ."');
                    data.addColumn('number', '". esc_html(__("Resume", 'wp-job-portal'))."');
                    data.addColumn('number', '". esc_html(__("Company", 'wp-job-portal'))."');
                    data.addColumn('number', '". esc_html(__("Applied resume", 'wp-job-portal'))."');
                    data.addRows([
                        ".wpjobportal::$_data['line_chart_json_array']."
                    ]);
                    var options = {
                    colors:['#1EADD8', '#179650', '#D98E11', '#5F3BBB', '#DB624C'],
                            curveType: 'function',
                            legend: { position: 'bottom' },
                            pointSize: 6,
                            height: 400,
                            width: '100%',
                            // This line will make you select an entire row of data at a time
                            focusTarget: 'category',
                            chartArea: {width:'90%', top:50}
                    };
                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
                    chart.draw(data, options);
            }

";
wp_add_inline_script( 'google-charts-handle', $drawChartTop_js_script );

$drawChartCatBar1_js_script = "
    google.setOnLoadCallback(drawChartCatBar1);
    function drawChartCatBar1(){
    var data = google.visualization.arrayToDataTable([
            ['". esc_html(__('Categories', 'wp-job-portal')) ."', '". esc_html(__('Jobs', 'wp-job-portal')) ."', { role: 'style' }, { role: 'annotation' } ],
    ". wpjobportal::$_data['catbar1'] ."
    ]);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
            { calc: 'stringify',
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation' },
                    2]);
            var options = {
            title: '',
                    width:'100%',
                    height: 300,
                    bar: {groupWidth: '80%'},
                    legend: { position: 'none' },
                    chartArea: {width:'90%', top:50}
            };
            var chart = new google.visualization.BarChart(document.getElementById('catbar1'));
            chart.draw(view, options);
    }
";
wp_add_inline_script( 'google-charts-handle', $drawChartCatBar1_js_script );

$drawChartCatBar2_js_script = "
    google.setOnLoadCallback(drawChartCatBar2);
    function drawChartCatBar2(){
    var data = google.visualization.arrayToDataTable([
            ['". esc_html(__('Categories', 'wp-job-portal'))."', '". esc_html(__('Resume', 'wp-job-portal')) ."', { role: 'style' }, { role: 'annotation' } ],
    ". wpjobportal::$_data['catbar2'] ."
    ]);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
            { calc: 'stringify',
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation' },
                    2]);
            var options = {title: '', width:'100%', height: 300, bar: {groupWidth: '80%'}, legend: { position: 'none' }};
            var chart = new google.visualization.ColumnChart(document.getElementById('catbar2'));
            chart.draw(view, options);
    }

    google.setOnLoadCallback(drawChart);
    function drawChart() {
    var piedata = google.visualization.arrayToDataTable([
            ['". esc_html(__('Categories', 'wp-job-portal')) ."', '". esc_html(__('Companies', 'wp-job-portal')) ."'],
    ". wpjobportal::$_data['catpie'] ."
    ]);
            var pieoptions = {title: '', width:'100%', height:300, legend: {position:'bottom'}, pieHole: 0.4, };
            var piechart = new google.visualization.PieChart(document.getElementById('catpie'));
            piechart.draw(piedata, pieoptions);
    }
";
wp_add_inline_script( 'google-charts-handle', $drawChartCatBar2_js_script );

$drawChartCatBar3_js_script = "

    google.setOnLoadCallback(drawChartCityBar1);
    function drawChartCityBar1(){
    var data = google.visualization.arrayToDataTable([
            ['". esc_html(__('Cities', 'wp-job-portal')) ."', '". esc_html(__('Jobs', 'wp-job-portal')) ."', { role: 'style' }, { role: 'annotation' } ],
    ". wpjobportal::$_data['citybar1'] ."
    ]);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
            { calc: 'stringify',
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation' },
                    2]);
            var options = {
            title: '',
                    width:'100%',
                    height: 300,
                    bar: {groupWidth: '80%'},
                    legend: { position: 'none' },
                    chartArea: {width:'90%', top:50}
            };
            var chart = new google.visualization.BarChart(document.getElementById('citybar1'));
            chart.draw(view, options);
    }
";
wp_add_inline_script( 'google-charts-handle', $drawChartCatBar3_js_script );

$drawChartCatBar4_js_script = "

    google.setOnLoadCallback(drawChartCityBar2);
    //4
    function drawChartCityBar2(){
    var data = google.visualization.arrayToDataTable([
            ['". esc_html(__('Cities', 'wp-job-portal')) ."', '". esc_html(__('Resume', 'wp-job-portal')) ."', { role: 'style' }, { role: 'annotation' } ],
    ". wpjobportal::$_data['citybar2'] ."
    ]);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
            { calc: 'stringify',
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation' },
                    2]);
            var options = {title: '', width:'100%', height: 300, bar: {groupWidth: '80%'}, legend: { position: 'none' }};
            var chart = new google.visualization.ColumnChart(document.getElementById('citybar2'));
            chart.draw(view, options);
    }

    google.setOnLoadCallback(drawChartCity);
    function drawChartCity() {
    var piedata = google.visualization.arrayToDataTable([
            ['". esc_html(__('Cities', 'wp-job-portal')) ."', '". esc_html(__('Companies', 'wp-job-portal')) ."'],
    ". wpjobportal::$_data['citypie'] ."
    ]);
            var pieoptions = {title: '', width:'100%', height:300, legend: {position:'bottom'}, pieHole: 0.4, };
            var piechart = new google.visualization.PieChart(document.getElementById('citypie'));
            piechart.draw(piedata, pieoptions);
    }
";
wp_add_inline_script( 'google-charts-handle', $drawChartCatBar4_js_script );

$drawChartCatBar5_js_script = "

    google.setOnLoadCallback(drawChartJobtypeBar1);
    function drawChartJobtypeBar1(){
    var data = google.visualization.arrayToDataTable([
            ['". esc_html(__('Job type', 'wp-job-portal')) ."', '". esc_html(__('Jobs', 'wp-job-portal')) ."', { role: 'style' }, { role: 'annotation' } ],
     ". wpjobportal::$_data['jobtypebar1'] ."
    ]);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
            { calc: 'stringify',
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation' },
                    2]);
            var options = {
            title: '',
                    width:'100%',
                    height: 300,
                    bar: {groupWidth: '80%'},
                    legend: { position: 'none' },
                    chartArea: {width:'90%', top:50}
            };
            var chart = new google.visualization.BarChart(document.getElementById('jobtypebar1'));
            chart.draw(view, options);
    }

    google.setOnLoadCallback(drawChartJobtypeBar2);
    function drawChartJobtypeBar2(){
    var data = google.visualization.arrayToDataTable([
            ['". esc_html(__('Job type', 'wp-job-portal')) ."', '". esc_html(__('Resume', 'wp-job-portal')) ."', { role: 'style' }, { role: 'annotation' } ],
    ". wpjobportal::$_data['jobtypebar2'] ."
    ]);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
            { calc: 'stringify',
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation' },
                    2]);
            var options = {title: '', width:'100%', height: 300, bar: {groupWidth: '80%'}, legend: { position: 'none' }};
            var chart = new google.visualization.ColumnChart(document.getElementById('jobtypebar2'));
            chart.draw(view, options);
    }
";
wp_add_inline_script( 'google-charts-handle', $drawChartCatBar5_js_script );

?>
