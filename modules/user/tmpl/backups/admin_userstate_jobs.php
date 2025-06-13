<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<span class="js-admin-title"><?php echo esc_html(__('User Stats Jobs', 'wp-job-portal')) ?></span>
<?php
if (!empty(wpjobportal::$_data[0])) {
    ?>
    <table id="js-table">
        <thead>
            <tr>
                <th class="left-row"><?php echo esc_html(__('Name', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Company', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Category', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Start publishing', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Stop publishing', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Created', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Status', 'wp-job-portal')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $k = 0;
            for ($i = 0, $n = count(wpjobportal::$_data[0]); $i < $n; $i++) {
                $row = wpjobportal::$_data[0][$i];
                ?>
                <tr valign="top">
                    <td class="left-row"><?php echo esc_html($row->title); ?></td>
                    <td><?php echo esc_html($row->companyname); ?></td>
                    <td><?php echo esc_html($row->cat_title); ?></td>
                    <td><?php echo esc_html($row->startpublishing); ?></td>
                    <td><?php echo esc_html($row->stoppublishing); ?></td>
                    <td><?php echo esc_html(date_i18n($this->config['date_format'], strtotime($row->created))); ?></td>
                    <td style="text-align: center;">
                        <?php
                        if ($row->status == 1)
                            echo "<font color='green'>" . esc_html($status[$row->status]) . "</font>";
                        elseif ($row->status == -1)
                            echo "<font color='red'>" . esc_html($status[$row->status]) . "</font>";
                        else
                            echo "<font color='blue'>" . esc_html($status[$row->status]) . "</font>";
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
