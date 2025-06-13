<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<span class="js-admin-title"><?php echo esc_html(__('User Stats Companies', 'wp-job-portal')) ?></span>
<?php
if (!empty(wpjobportal::$_data[0])) {
    ?>
    <table id="js-table">
        <thead>
            <tr>
                <th class="left-row"><?php echo esc_html(__('Name', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Category', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Created', 'wp-job-portal')); ?></th>
                <th><?php echo esc_html(__('Status', 'wp-job-portal')); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $status = array('1' => esc_html(__('Job Approved', 'wp-job-portal')), '-1' => esc_html(__('Job Rejected', 'wp-job-portal')));
            $deleteimg = 'remove.png';
            $deletealt = esc_html(__('Delete', 'wp-job-portal'));
            for ($i = 0, $n = count(wpjobportal::$_data[0]); $i < $n; $i++) {
                $row = wpjobportal::$_data[0][$i];
                ?>
                <tr valign="top">
                    <td class="left-row"><?php echo esc_html($row->name); ?></td>
                    <td><?php echo esc_html($row->cat_title); ?></td>
                    <td><?php echo esc_html(date_i18n($this->config['date_format'], strtotime($row->created))); ?></td>
                    <td>
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
