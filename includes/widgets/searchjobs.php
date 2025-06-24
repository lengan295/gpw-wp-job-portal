<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class WPJOBPORTALjobssearchjobs_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'WPJOBPORTALjobssearchjobs_widget',
            esc_html(__('Job Search', 'wp-job-portal')),
            array(
                'description' => esc_html(__('Search jobs form WP Job Portal database', 'wp-job-portal')),
                'show_instance_in_rest' => true,
            )
        );
    }

    public function widget($args, $instance) {
        $defaults = [
            'title' => esc_html__('Search Job', 'wp-job-portal'),
            'showtitle' => 1,
            'jobtitle' => 1,
            'category' => 1,
            'jobtype' => 1,
            'jobstatus' => 0,
            'salaryrange' => 0,
            'shift' => 0,
            'duration' => 0,
            'company' => 1,
            'address' => 1,
            'columnperrow' => 1,
            'layout' => 'vertical',
            'show_adv_button' => 1,
            'use_icons_for_buttons' => 0,
            //'custom_css_classes' => '',
            'field_custom_class' => '',
            'show_labels' => 1,
            'show_placeholders' => 0,
        ];
        $instance = wp_parse_args($instance, $defaults);

        echo wp_kses($args['before_widget'], WPJOBPORTAL_ALLOWED_TAGS);
        if (!empty($instance['title']) && $instance['showtitle']) {
            echo wp_kses($args['before_title'], WPJOBPORTAL_ALLOWED_TAGS) .
                 esc_html($instance['title']) .
                 wp_kses($args['after_title'], WPJOBPORTAL_ALLOWED_TAGS);
        }

        if (defined('REST_REQUEST') && REST_REQUEST) {
            ob_start();
        }

        if (!locate_template('wp-job-portal/widget-searchjobs.php', true, true)) {
            wpjobportal::wpjobportal_addStyleSheets();
            $modules_html = WPJOBPORTALincluder::getJSModel('jobsearch')->getSearchJobs_Widget(
                $instance['title'],
                $instance['showtitle'],
                $instance['jobtitle'],
                $instance['category'],
                $instance['jobtype'],
                $instance['jobstatus'],
                $instance['salaryrange'],
                $instance['shift'],
                $instance['duration'],
                0, // startpublishing
                0, // stoppublishing
                $instance['company'],
                $instance['address'],
                $instance['columnperrow'],
                $instance['layout'],
                $instance['show_adv_button'],
                $instance['use_icons_for_buttons'],
                $instance['field_custom_class'],
                $instance['show_labels'],
                $instance['show_placeholders']
            );
            echo $modules_html;
        }

        if (defined('REST_REQUEST') && REST_REQUEST) {
            return ob_get_clean();
        }

        echo wp_kses($args['after_widget'], WPJOBPORTAL_ALLOWED_TAGS);
    }

    public function form($instance) {
        $instance = wp_parse_args((array) $instance, array(
            'title' => '',
            'showtitle' => 1,
            'jobtitle' => 1,
            'category' => 1,
            'jobtype' => 1,
            'jobstatus' => 0,
            'salaryrange' => 0,
            'shift' => 0,
            'duration' => 0,
            'company' => 1,
            'address' => 1,
            'columnperrow' => 1,
            'layout' => 'vertical',
            'show_adv_button' => 1,
            'use_icons_for_buttons' => 0,
            'field_custom_class' => '',
            'show_labels' => 1,
            'show_placeholders' => 0,
        ));

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php echo esc_html__('Title', 'wp-job-portal'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>

        <p>
            <input id="<?php echo esc_attr($this->get_field_id('showtitle')); ?>" name="<?php echo esc_attr($this->get_field_name('showtitle')); ?>" type="checkbox" value="1" <?php checked($instance['showtitle'], 1); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('showtitle')); ?>">
                <?php echo esc_html__('Show Title', 'wp-job-portal'); ?>
            </label>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout')); ?>">
                <?php echo esc_html__('Layout', 'wp-job-portal'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('layout')); ?>" name="<?php echo esc_attr($this->get_field_name('layout')); ?>">
                <option value="vertical" <?php selected($instance['layout'], 'vertical'); ?>>
                    <?php echo esc_html__('Vertical', 'wp-job-portal'); ?>
                </option>
                <option value="horizontal" <?php selected($instance['layout'], 'horizontal'); ?>>
                    <?php echo esc_html__('Horizontal', 'wp-job-portal'); ?>
                </option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columnperrow')); ?>">
                <?php echo esc_html__('Columns per Row', 'wp-job-portal'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('columnperrow')); ?>" name="<?php echo esc_attr($this->get_field_name('columnperrow')); ?>">
                <option value="1" <?php if (esc_attr($instance['columnperrow']) == 1) echo "selected"; ?>><?php echo esc_html(__('1 Column', 'wp-job-portal')); ?></option>
                <option value="2" <?php if (esc_attr($instance['columnperrow']) == 2) echo "selected"; ?>><?php echo esc_html(__('2 Columns', 'wp-job-portal')); ?></option>
                <option value="3" <?php if (esc_attr($instance['columnperrow']) == 3) echo "selected"; ?>><?php echo esc_html(__('3 Columns', 'wp-job-portal')); ?></option>
                <option value="4" <?php if (esc_attr($instance['columnperrow']) == 4) echo "selected"; ?>><?php echo esc_html(__('4 Columns', 'wp-job-portal')); ?></option>
                <option value="5" <?php if (esc_attr($instance['columnperrow']) == 5) echo "selected"; ?>><?php echo esc_html(__('5 Columns', 'wp-job-portal')); ?></option>
            </select>
        </p>

        <h3 style="margin-top:10px; font-weight: bold;">
            <?php echo esc_html__('Search Fields', 'wp-job-portal'); ?>
        </h3>

        <?php
        // Define fields to show as checkboxes
        $checkbox_fields = array(
            'jobtitle' => __('Job Title', 'wp-job-portal'),
            'company' => __('Company', 'wp-job-portal'),
            'category' => __('Job Category', 'wp-job-portal'),
            'jobtype' => __('Job Type', 'wp-job-portal'),
            'address' => __('City', 'wp-job-portal'),
            'show_labels' => __('Show Labels', 'wp-job-portal'),
            'show_placeholders' => __('Show Placeholders', 'wp-job-portal'),
            'use_icons_for_buttons' => __('Use Icons for Buttons', 'wp-job-portal'),
            'show_adv_button' => __('Show Advanced Search Button', 'wp-job-portal'),
        );

        foreach ($checkbox_fields as $field => $label): ?>
            <p>
                <input id="<?php echo esc_attr($this->get_field_id($field)); ?>" name="<?php echo esc_attr($this->get_field_name($field)); ?>" type="checkbox" value="1" <?php checked($instance[$field], 1); ?> />
                <label for="<?php echo esc_attr($this->get_field_id($field)); ?>">
                    <?php echo esc_html($label); ?>
                </label>
            </p>
        <?php endforeach; ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('field_custom_class')); ?>">
                <?php echo esc_html__('Custom Field Class', 'wp-job-portal'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('field_custom_class')); ?>" name="<?php echo esc_attr($this->get_field_name('field_custom_class')); ?>" type="text" value="<?php echo esc_attr($instance['field_custom_class']); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();

        // Text/select fields
        $text_fields = array(
            'title', 'layout', 'columnperrow', 'field_custom_class'
        );
        foreach ($text_fields as $field) {
            $instance[$field] = !empty($new_instance[$field]) ? sanitize_text_field($new_instance[$field]) : '';
        }

        // Checkbox fields
        $checkbox_fields = array(
            'showtitle', 'jobtitle', 'category', 'jobtype', 'jobstatus',
            'salaryrange', 'shift', 'duration', 'company', 'address',
            'show_adv_button', 'use_icons_for_buttons', 'show_labels', 'show_placeholders'
        );
        foreach ($checkbox_fields as $field) {
            $instance[$field] = !empty($new_instance[$field]) ? 1 : 0;
        }

        return $instance;
    }
}

function WPJOBPORTALjobssearchjobs_load_widget() {
    register_widget('WPJOBPORTALjobssearchjobs_widget');
}
add_action('widgets_init', 'WPJOBPORTALjobssearchjobs_load_widget');