<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<div id="wpjobportal-head">
    <h1 class="wpjobportal-head-text">
        <?php echo esc_html(__('User Stats Resume', 'wp-job-portal')) ?>
    </h1>
</div>
<?php
if (!empty(wpjobportal::$_data[0])) {
    ?>  		
    <table id="wpjobportal-table" class="wpjobportal-table">
        <thead>
            <tr>
                <th class="wpjobportal-text-left">
                    <?php echo esc_html(__('Name', 'wp-job-portal')); ?>
                </th>
                <th>
                    <?php echo esc_html(__('Application Title', 'wp-job-portal')); ?>
                </th>
                <th>
                    <?php echo esc_html(__('Category', 'wp-job-portal')); ?>
                </th>
                <th>
                    <?php echo esc_html(__('Created', 'wp-job-portal')); ?>
                </th>
                <th>
                    <?php echo esc_html(__('Status', 'wp-job-portal')); ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
                $k = 0;
                for ($i = 0, $n = count(wpjobportal::$_data[0]); $i < $n; $i++) {
                    $row = wpjobportal::$_data[0][$i];
                    ?>
                    <tr>
                        <td class="wpjobportal-text-left">
                            <?php echo esc_html($row->first_name) . ' ' . esc_html($row->last_name); ?>
                        </td>
                        <td>
                            <?php echo esc_html($row->application_title); ?>	
                        </td>
                        <td>
                            <?php echo esc_html($row->cat_title); ?>	
                        </td>
                        <td>
                            <?php echo esc_html(date_i18n($this->config['date_format'], strtotime($row->create_date))); ?>
                        </td>
                        <td>
                            <?php
                                if ($row->status == 1)
                                    echo "<span class='wpjobportal-table-priority-color' style='background:green'>" . esc_html($status[$row->status]) . "</span>";
                                elseif ($row->status == -1)
                                    echo "<span class='wpjobportal-table-priority-color' style='background:red'>" . esc_html($status[$row->status]) . "</span>";
                                else
                                    echo "<span class='wpjobportal-table-priority-color' style='background:blue'>" . esc_html($status[$row->status]) . "</span>";
                            ?>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
            ?>
        </tbody>
    </table>
    <?php
    if (wpjobportal::$_data[1]) {
        echo '<div class="tablenav"><div class="tablenav-pages">' . wp_kses_post(wpjobportal::$_data[1]) . '</div></div>';
    }
} else {
    $msg = esc_html(__('No record found','wp-job-portal'));
    WPJOBPORTALlayout::getNoRecordFound($msg);
}
?>
