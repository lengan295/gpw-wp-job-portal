<?php
if (!defined('ABSPATH'))
    die('Restricted Access');

class JP_Shortcodes_Wigdet extends \Elementor\Widget_Base {

	public function get_name() {
		return 'jp_shortcodes_wigdet';
	}

	public function get_title() {
		return esc_html__( 'Job Portal', 'elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories(){
		 return ['job-portal-category']; // Moves the widget to the "Job Portal" category
	}

	// search results triggers
	public function get_keywords(){
		return [ 'job', 'portal' , 'page', 'shortcode' ];
	}

	protected function register_controls(){

		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'WP Job Portal', 'elementor-addon' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// Combo Box (Select Dropdown) for Job Type
	    $this->add_control(
	        'jp_shortcode',
	        [
	            'label' => __('Select Page To Show', 'custom-elementor'),
	            'type' => \Elementor\Controls_Manager::SELECT,
	            'options' => [
						'wpjobportal_jobseeker_controlpanel' => __('Job Seeker Control Panel', 'text-domain'),
	                	'wpjobportal_employer_controlpanel' => __('Employer Control Panel', 'text-domain'),
						'wpjobportal_job' => __('Job Listing', 'text-domain'),
						'wpjobportal_job_categories' => __('Job Categories', 'text-domain'),
						'wpjobportal_job_types' => __('Job Types', 'text-domain'),
						'wpjobportal_my_appliedjobs' => __('My Applied Jobs', 'text-domain'),
						'wpjobportal_my_companies' => __('My Companies', 'text-domain'),
						'wpjobportal_my_departments' => __('My Departments', 'text-domain'),
						'wpjobportal_my_jobs' => __('My Jobs', 'text-domain'),
						'wpjobportal_my_resumes' => __('My Resumes', 'text-domain'),
						'wpjobportal_searchjob' => __('Search Jobs', 'text-domain'),
						'wpjobportal_searchresume' => __('Search Resumes', 'text-domain'),
						'wpjobportal_jobbycategory' => __('Jobs by Category', 'text-domain'),
	            ],
	            'default' => 'wpjobportal_job',
	        ]
	    );

	    	// array to manage visibilty for styling fields

		$pages_for_styling_array = array();
		$pages_for_styling_array['jp_shortcode'] = array('wpjobportal_jobseeker_controlpanel');
		$pages_for_no_styling_msg_array['jp_shortcode!'] = array('wpjobportal_jobseeker_controlpanel');
		// $pages_for_styling_array['jp_shortcode'] = array('wpjobportal_job', 'wpjobportal_jobseeker_controlpanel', 'wpjobportal_employer_controlpanel');
		// $pages_for_no_styling_msg_array['jp_shortcode!'] = array('wpjobportal_job', 'wpjobportal_jobseeker_controlpanel', 'wpjobportal_employer_controlpanel');


		//to handle default/reset case
        $color_array['color1'] = "#3baeda";
        $color_array['color2'] = "#333333";
        $color_array['color3'] = "#575757";

        // handle color css
        // to handle the case of not all three are changed
        // $color_array = array();
        // $color_string_values = get_option("wpjp_set_theme_colors");
        // if($color_string_values != ''){
        //     $json_values = json_decode($color_string_values,true);
        //     if(is_array($json_values) && !empty($json_values)){
        //         $color_array['color1'] = esc_attr($json_values['color1']);
        //         $color_array['color2'] = esc_attr($json_values['color2']);
        //         $color_array['color3'] = esc_attr($json_values['color3']);
        //     }
        // }


	    $this->add_control(
	        'main_bottom_message',
	        [
	            'type' => \Elementor\Controls_Manager::RAW_HTML,
	            'raw'  => '
	            <div style="border: 2px solid #D19BE5; background-color: #FAF3FF; color: #4A148C; padding: 15px; border-radius: 8px; text-align: center; width: 100%; margin: 20px auto;">
	                <div> <img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/themes/message-1-icon.png" style="width:80px;margin-bottom:15px;"></div>
	                <p style="font-size:17px;line-height:22px;">
	                    Color and typography settings apply <strong>globally</strong> to all pages of the WP Job Portal. To see the changes take effect, please save or
	                    <span style="background:#D19BE5; font-weight: bold; text-decoration: underline;">Publish the page.</span>
	                </p>
	            </div>
	            ',
	            'separator' => 'after', // Ensures it's placed below
	            'condition' => $pages_for_styling_array,
	        ]
	    );
	    $this->add_control(
	        'no_main_bottom_message_color',
	        [
	            'type' => \Elementor\Controls_Manager::RAW_HTML,
	            'raw'  => '
	            <div style="border: 2px solid #000; background-color: #FFF; color: #000; padding: 15px; border-radius: 8px; text-align: center; width: 100%; margin: 20px auto;">
	                <div> <img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/themes/message-2-icon.png" style="width:80px;margin-bottom:15px;"></div>
	                <p style="font-size:17px;line-height:22px;">
	                    Color styling and typography live preview is a vailable for
	                    Job Seeker Dashboard, but the changes will <strong>apply to all</strong>
	                    pages of the WP Job Portal plugin.
	                    Pages are: <br>
	                    * Job Seeker Dashboard <br>
	                </p>

	            </div>
	            ',
	            'separator' => 'after', // Ensures it's placed below
	            'condition' => $pages_for_no_styling_msg_array,
	        ]
	    );

		$this->end_controls_section();

		// Content Tab End

		// Color Styles Tab Section Section
		$this->start_controls_section(
		            'style_section',
		            [
		                'label' => __('Colors', 'custom-elementor'),
		                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		            ]
		        );

		        $this->add_control(
		            'jp_primarycolor',
		            [
		                'label'     => __('Primary Color', 'custom-elementor'),
		                'type'      => \Elementor\Controls_Manager::COLOR,
		                'selectors' => [
				            '{{WRAPPER}} .wjportal-main-wrapper .wjportal-cp-user-act-btn' => 'background: {{VALUE}} !important;',
				            '{{WRAPPER}} .wjportal-main-wrapper .wjportal-cp-view-btn' => 'background: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title a' => 'color: {{VALUE}} !important;',
				            //'{{WRAPPER}} div.wjportal-filter-search-main-wrp div.wjportal-filter-search-wrp div.wjportal-filter-search-btn-wrp .wjportal-filter-search-btn' => 'color: {{VALUE}} !important;',
				            //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list a.wjportal-list-anchor:hover' => 'color: {{VALUE}} !important;',
				            '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-user-logo' => 'border-color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobdetail-wrapper div.wjportal-job-company-wrp div.wjportal-job-company-btn-wrp .wjportal-job-company-btn' => 'border-color: {{VALUE}} !important;background: {{VALUE}} !important;',
				            // elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-companyname' => 'color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-sect-wrp .wjportal-elegant-addon-cp-view-btn-wrp a' => 'background-color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner' => 'background-color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-page-heading' => 'border-color: {{VALUE}} !important;',
				            //'{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-filter-search-wrp .wjportal-elegant-addon-filter-search-btn-wrp button' => 'background-color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-apply-btn-wrp a.wjportal-elegant-addon-jobs-apply-btn' => 'background-color: {{VALUE}} !important;border-color: {{VALUE}} !important;',
				        ],
				        'condition' => $pages_for_styling_array,
				        // 'default' => $color_array['color1'],
		            ]
		        );

		        // $this->add_control(
		        //     'use_default_color1',
		        //     [
		        //         'label' => __( 'Use Default Color For Color 1', 'plugin-name' ),
		        //         'type' => \Elementor\Controls_Manager::SWITCHER,
		        //         'default' => 'no',
		        //     ]
		        // );

		        $this->add_control(
		            'jp_primarycolor_desc',
		            [
		                'type' => \Elementor\Controls_Manager::RAW_HTML,
		                'raw'  => '<p class="elementor-control-field-description">Color WP Job Portal page content</p>',
		                'separator' => 'after', // Ensures it's placed below
		                'condition' => $pages_for_styling_array,
		            ]
		        );
		        $this->add_control(
		            'jp_secondarycolor',
		            [
		                'label'     => __('Secondary Color', 'custom-elementor'),
		                'type'      => \Elementor\Controls_Manager::COLOR,
		                'selectors' => [
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname' => 'color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary' => 'color: {{VALUE}} !important;',
				            //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title' => 'color: {{VALUE}} !important;',
				            '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-sec-title' => 'color: {{VALUE}} !important;',
				            // elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-right .wjportal-elegant-addon-cp-sec-title' => 'color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-elegant-addon-cp-sec-title' => 'color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-buttnrow a' => 'color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-job-title a' => 'color: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-page-heading' => 'color: {{VALUE}} !important;',

		                ],
		                'condition' => $pages_for_styling_array,
		                // 'default' => $color_array['color2'],
		            ]
		        );
		        $this->add_control(
		            'jp_secondarycolor_desc',
		            [
		                'type' => \Elementor\Controls_Manager::RAW_HTML,
		                'raw'  => '<p class="elementor-control-field-description">Color WP Job Portal page content</p>',
		                'separator' => 'after', // Ensures it's placed below
		                'condition' => $pages_for_styling_array,
		            ]
		        );

		        $this->add_control(
		            'jp_contentcolor',
		            [
		                'label'     => __('Content Color', 'custom-elementor'),
		                'type'      => \Elementor\Controls_Manager::COLOR,
		                'selectors' => [
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list a.wjportal-list-anchor' => 'color: {{VALUE}} !important;',
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list a.wjportal-list-anchor' => 'color: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text' => 'color: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info' => 'color: {{VALUE}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-list-anchor span' => 'color: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue' => 'color: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue span' => 'color: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-discription' => 'color: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-filter-search-wrp input' => 'color: {{VALUE}} !important;',
		                ],
		                'condition' => $pages_for_styling_array,
		                // 'default' => $color_array['color3'],
		            ]
		        );
		        $this->add_control(
		            'jp_contentcolor_desc',
		            [
		                'type' => \Elementor\Controls_Manager::RAW_HTML,
		                'raw'  => '<p class="elementor-control-field-description">Color WP Job Portal page content</p>',
		                'separator' => 'after', // Ensures it's placed below
		                'condition' => $pages_for_styling_array,
		            ]
		        );
		         $this->add_control(
		        	        'styling_bottom_message',
		        	        [
		        	            'type' => \Elementor\Controls_Manager::RAW_HTML,
		        	            'raw'  => '
		        	            <div style="border: 2px solid #D19BE5; background-color: #FAF3FF; color: #4A148C; padding: 15px; border-radius: 8px; text-align: center; width: 100%; margin: 20px auto;">
		        	                <div> <img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/themes/message-1-icon.png" style="width:80px;margin-bottom:15px;"></div>
		        	                <p style="font-size:17px;line-height:22px;">
		        	                    Color and typography settings apply <strong>globally</strong> to all pages of the WP Job Portal. To see the changes take effect, please save or
		        	                    <span style="background:#D19BE5; font-weight: bold; text-decoration: underline;">Publish the page.</span>
		        	                </p>
		        	            </div>
		        	            ',
		        	            'separator' => 'after', // Ensures it's placed below
		        	            'condition' => $pages_for_styling_array,
		        	        ]
		        	    );
		        	    $this->add_control(
		        	        'no_styling_bottom_message_color',
		        	        [
		        	            'type' => \Elementor\Controls_Manager::RAW_HTML,
		        	            'raw'  => '
		        	            <div style="border: 2px solid #000; background-color: #FFF; color: #000; padding: 15px; border-radius: 8px; text-align: center; width: 100%; margin: 20px auto;">
		        	                <div> <img src="'.esc_url(WPJOBPORTAL_PLUGIN_URL).'includes/images/themes/message-2-icon.png" style="width:80px;margin-bottom:15px;"></div>
		        	                <p style="font-size:17px;line-height:22px;">
		        	                    Color styling and typography live preview is a vailable for
		        	                    Job Seeker Dashboard, but the changes will <strong>apply to all</strong>
		        	                    pages of the WP Job Portal plugin.
		        	                    Pages are: <br>
		        	                    * Job Seeker Dashboard <br>
		        	                </p>

		        	            </div>
		        	            ',
		        	            'separator' => 'after', // Ensures it's placed below
		        	            'condition' => $pages_for_no_styling_msg_array,
		        	        ]
		        	    );

			$this->end_controls_section();

			// Typography Section Section

			// body typo graphy start
		        $this->start_controls_section(
		            'body_typography_section',
		            [
		                'label' => __('Content Styling', 'custom-elementor'),
		                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		                'condition' => $pages_for_styling_array,
		            ]
		        );

				$this->add_control(
				    'typography_content_font_family',
				    [
				        'label' => __('Font Family', 'custom-elementor'),
				        'type' => \Elementor\Controls_Manager::FONT,
				        'default' => '',
				        'selectors' => [
				            //'{{WRAPPER}} .wjportal-main-wrapper *' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info span.wjportal-job-type' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary span.wjportal-salary-type' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title' => 'font-family: {{VALUE}} !important;',
				            //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list span.wjportal-cp-link-text' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info' => 'font-family: {{VALUE}} !important;',
				            // elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue span' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-companyname' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-discription' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-list-anchor span' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-logowrp .wjportal-elegant-addon-user-namewrp' => 'font-family: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-job-title a' => 'font-family: {{VALUE}} !important;',

				        ],
				    ]
				);
				$this->add_control(
				    'typography_content_font_size',
				    [
				        'label' => __('Font Size', 'custom-elementor'),
				        'type' => \Elementor\Controls_Manager::SLIDER,
				        'size_units' => ['px', 'em', 'rem'],
				        'range' => [
				            'px' => ['min' => 10, 'max' => 100],
				            'em' => ['min' => 0.5, 'max' => 5],
				            'rem' => ['min' => 0.5, 'max' => 5],
				        ],
				        'default' => [
				            'unit' => 'px',
				            'size' => 15,
				        ],
				        'selectors' => [
				            //'{{WRAPPER}} .wjportal-main-wrapper *' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info span.wjportal-job-type' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary span.wjportal-salary-type' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list span.wjportal-cp-link-text' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            //'{{WRAPPER}} ' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            // elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue span' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-companyname' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-discription' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-list-anchor span' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-logowrp .wjportal-elegant-addon-user-namewrp' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-job-title a' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				        ],
				    ]
				);

				$this->add_control(
				    'typography_content_font_weight',
				    [
				        'label' => __('Font Weight', 'custom-elementor'),
				        'type' => \Elementor\Controls_Manager::SELECT,
				        'options' => [
				            '100' => 'Thin',
				            '200' => 'Extra Light',
				            '300' => 'Light',
				            '400' => 'Regular',
				            '500' => 'Medium',
				            '600' => 'Semi Bold',
				            '700' => 'Bold',
				            '800' => 'Extra Bold',
				            '900' => 'Black',
				        ],
				        'default' => '400',
				        'selectors' => [
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info span.wjportal-job-type' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary span.wjportal-salary-type' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title' => 'font-weight: {{VALUE}} !important;',
				            //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list span.wjportal-cp-link-text' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info' => 'font-weight: {{VALUE}} !important;',
				            // elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue span' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-companyname' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-discription' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-list-anchor span' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-logowrp .wjportal-elegant-addon-user-namewrp' => 'font-weight: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-job-title a' => 'font-weight: {{VALUE}} !important;',
				        ],
				    ]
				);

				$this->add_control(
				    'typography_content_text_transform',
				    [
				        'label' => __('Text Transform', 'custom-elementor'),
				        'type' => \Elementor\Controls_Manager::SELECT,
				        'options' => [
				            'none' => 'None',
				            'uppercase' => 'Uppercase',
				            'lowercase' => 'Lowercase',
				            'capitalize' => 'Capitalize',
				        ],
				        'default' => 'none',
				        'selectors' => [
				        	'{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info span.wjportal-job-type' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary span.wjportal-salary-type' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title' => 'text-transform: {{VALUE}} !important;',
				            //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list span.wjportal-cp-link-text' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info' => 'text-transform: {{VALUE}} !important;',
				            // elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue span' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-companyname' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-discription' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-list-anchor span' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-logowrp .wjportal-elegant-addon-user-namewrp' => 'text-transform: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-job-title a' => 'text-transform: {{VALUE}} !important;',
				        ],
				    ]
				);

				$this->add_control(
				    'typography_content_font_style',
				    [
				        'label' => __('Font Style', 'custom-elementor'),
				        'type' => \Elementor\Controls_Manager::SELECT,
				        'options' => [
				            'normal' => 'Normal',
				            'italic' => 'Italic',
				            'oblique' => 'Oblique',
				        ],
				        'default' => 'normal',
				        'selectors' => [
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info span.wjportal-job-type' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary span.wjportal-salary-type' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title' => 'font-style: {{VALUE}} !important;',
				            //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list span.wjportal-cp-link-text' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info' => 'font-style: {{VALUE}} !important;',
				            // elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue span' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-companyname' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-discription' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-list-anchor span' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-logowrp .wjportal-elegant-addon-user-namewrp' => 'font-style: {{VALUE}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-job-title a' => 'font-style: {{VALUE}} !important;',
				        ],
				    ]
				);

				$this->add_control(
				    'typography_content_line_height',
				    [
				        'label' => __('Line Height', 'custom-elementor'),
				        'type' => \Elementor\Controls_Manager::SLIDER,
				        'size_units' => ['em', 'px', 'rem'],
				        'range' => [
				            'em' => ['min' => 0.5, 'max' => 3, 'step' => 0.1],
				            'px' => ['min' => 10, 'max' => 100, 'step' => 1],
				            'rem' => ['min' => 0.5, 'max' => 5, 'step' => 0.1],
				        ],
				        'default' => [
				            'unit' => 'em',
				            'size' => 1.1,
				        ],
				        'selectors' => [
							'{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info span.wjportal-job-type' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary span.wjportal-salary-type' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							//'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list span.wjportal-cp-link-text' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							'{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info' => 'line-height: {{SIZE}}{{UNIT}} !important;',
							// elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue span' => 'line-height: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-companyname' => 'line-height: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-discription' => 'line-height: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-list-anchor span' => 'line-height: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-logowrp .wjportal-elegant-addon-user-namewrp' => 'line-height: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-job-title a' => 'line-height: {{SIZE}}{{UNIT}} !important;',
				        ],
				    ]
				);

				$this->add_control(
				    'typography_content_letter_spacing',
				    [
				        'label' => __('Letter Spacing', 'custom-elementor'),
				        'type' => \Elementor\Controls_Manager::SLIDER,
				        'size_units' => ['px', 'em', 'rem'],
				        'range' => [
				            'px' => ['min' => -5, 'max' => 10, 'step' => 0.1],
				            'em' => ['min' => -0.5, 'max' => 2, 'step' => 0.1],
				            'rem' => ['min' => -0.5, 'max' => 2, 'step' => 0.1],
				        ],
				        'default' => [
				            'unit' => 'px',
				            'size' => 1,
				        ],
				        'selectors' => [
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-jobs-data-text' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data a.wjportal-companyname' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info span.wjportal-job-type' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info div.wjportal-jobs-salary span.wjportal-salary-type' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-middle-wrp div.wjportal-jobs-data span.wjportal-job-title' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-short-links-wrp div.wjportal-cp-short-links-list div.wjportal-cp-list span.wjportal-cp-link-text' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-jobs-list div.wjportal-jobs-list-top-wrp div.wjportal-jobs-cnt-wrp div.wjportal-jobs-right-wrp div.wjportal-jobs-info' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            // elegent
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-keyvalue span' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-companyname' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-discription' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-list-anchor span' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-logowrp .wjportal-elegant-addon-user-namewrp' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				            '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-list .wjportal-elegant-addon-jobs-data .wjportal-elegant-addon-job-title a' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
				        ],
				    ]
				);


		        $this->add_control(
		            'typography_description',
		            [
		                'type' => \Elementor\Controls_Manager::RAW_HTML,
		                'raw'  => '<p class="elementor-control-field-description">Typography For WP Job Portal Page Body Content.</p>',
		                'separator' => 'after', // Ensures it's placed below
		                'condition' => $pages_for_styling_array,
		            ]
		        );
		        $this->end_controls_section();
    		// Typography Body Section END

    		// Typography Section Title Section Start

		        $this->start_controls_section(
		            'title_typography_section',
		            [
		                'label' => __('Section Title Styling', 'custom-elementor'),
		                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		                'condition' => $pages_for_styling_array,
		            ]
		        );

		        // Font Family
		        $this->add_control(
		            'typography_section_title_font_family',
		            [
		                'label' => __('Font Family', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::FONT,
		                'default' => 'Arial, sans-serif',
		                'selectors' => [
		                    '{{WRAPPER}} .wjportal-main-wrapper div#wjportal-job-cp-wrp div.wjportal-cp-sec-title' => 'font-family: {{VALUE}} !important;',
		                    //'{{WRAPPER}} .wjportal-main-wrapper div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title' => 'font-family: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-page-header div.wjportal-page-heading' => 'font-family: {{VALUE}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-right .wjportal-elegant-addon-cp-sec-title' => 'font-family: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-elegant-addon-cp-sec-title' => 'font-family: {{VALUE}} !important;',
		                ],
		            ]
		        );

		        // Font Size
		        $this->add_control(
		            'typography_section_title_font_size',
		            [
		                'label' => __('Font Size', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => ['px', 'em', 'rem'],
		                'range' => [
		                    'px' => ['min' => 10, 'max' => 100],
		                    'em' => ['min' => 0.5, 'max' => 5],
		                    'rem' => ['min' => 0.5, 'max' => 5],
		                ],
		                'default' => [
		                    'unit' => 'px',
		                    'size' => 25,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .wjportal-main-wrapper div#wjportal-job-cp-wrp div.wjportal-cp-sec-title' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} .wjportal-main-wrapper div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-page-header div.wjportal-page-heading' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-right .wjportal-elegant-addon-cp-sec-title' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-elegant-addon-cp-sec-title' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                ],
		            ]
		        );

		        // Font Weight
		        $this->add_control(
		            'typography_section_title_font_weight',
		            [
		                'label' => __('Font Weight', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '400',
		                'options' => [
		                    '100' => 'Thin',
		                    '200' => 'Extra Light',
		                    '300' => 'Light',
		                    '400' => 'Regular',
		                    '500' => 'Medium',
		                    '600' => 'Semi Bold',
		                    '700' => 'Bold',
		                    '800' => 'Extra Bold',
		                    '900' => 'Black',
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .wjportal-main-wrapper div#wjportal-job-cp-wrp div.wjportal-cp-sec-title' => 'font-weight: {{VALUE}} !important;',
		                    //'{{WRAPPER}} .wjportal-main-wrapper div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title' => 'font-weight: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-page-header div.wjportal-page-heading' => 'font-weight: {{VALUE}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-right .wjportal-elegant-addon-cp-sec-title' => 'font-weight: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-elegant-addon-cp-sec-title' => 'font-weight: {{VALUE}} !important;',
		                ],
		            ]
		        );

		        // Text Transform
		        $this->add_control(
		            'typography_section_title_text_transform',
		            [
		                'label' => __('Text Transform', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'none',
		                'options' => [
		                    'none' => __('None', 'custom-elementor'),
		                    'uppercase' => __('Uppercase', 'custom-elementor'),
		                    'lowercase' => __('Lowercase', 'custom-elementor'),
		                    'capitalize' => __('Capitalize', 'custom-elementor'),
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .wjportal-main-wrapper div#wjportal-job-cp-wrp div.wjportal-cp-sec-title' => 'text-transform: {{VALUE}} !important;',
		                    //'{{WRAPPER}} .wjportal-main-wrapper div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title' => 'text-transform: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-page-header div.wjportal-page-heading' => 'text-transform: {{VALUE}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-right .wjportal-elegant-addon-cp-sec-title' => 'text-transform: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-elegant-addon-cp-sec-title' => 'text-transform: {{VALUE}} !important;',
		                ],
		            ]
		        );

		        // Font Style
		        $this->add_control(
		            'typography_section_title_font_style',
		            [
		                'label' => __('Font Style', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'normal',
		                'options' => [
		                    'normal' => __('Normal', 'custom-elementor'),
		                    'italic' => __('Italic', 'custom-elementor'),
		                    'oblique' => __('Oblique', 'custom-elementor'),
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .wjportal-main-wrapper div#wjportal-job-cp-wrp div.wjportal-cp-sec-title' => 'font-style: {{VALUE}} !important;',
		                    //'{{WRAPPER}} .wjportal-main-wrapper div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title' => 'font-style: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-page-header div.wjportal-page-heading' => 'font-style: {{VALUE}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-right .wjportal-elegant-addon-cp-sec-title' => 'font-style: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-elegant-addon-cp-sec-title' => 'font-style: {{VALUE}} !important;',
		                ],
		            ]
		        );

		        // Line Height
		        $this->add_control(
		            'typography_section_title_line_height',
		            [
		                'label' => __('Line Height', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => ['px', 'em'],
		                'range' => [
		                    'px' => ['min' => 10, 'max' => 100],
		                    'em' => ['min' => 0.5, 'max' => 5],
		                ],
		                'default' => [
		                    'unit' => 'em',
		                    'size' => 1.1,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .wjportal-main-wrapper div#wjportal-job-cp-wrp div.wjportal-cp-sec-title' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} .wjportal-main-wrapper div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-page-header div.wjportal-page-heading' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-right .wjportal-elegant-addon-cp-sec-title' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-elegant-addon-cp-sec-title' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                ],
		            ]
		        );

		        // Letter Spacing
		        $this->add_control(
		            'typography_section_title_letter_spacing',
		            [
		                'label' => __('Letter Spacing', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => ['px', 'em'],
		                'range' => [
		                    'px' => ['min' => -5, 'max' => 20],
		                    'em' => ['min' => -0.5, 'max' => 2],
		                ],
		                'default' => [
		                    'unit' => 'px',
		                    'size' => 1,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .wjportal-main-wrapper div#wjportal-job-cp-wrp div.wjportal-cp-sec-title' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} .wjportal-main-wrapper div#wjportal-emp-cp-wrp div.wjportal-cp-sec-title' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-page-header div.wjportal-page-heading' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-right .wjportal-elegant-addon-cp-sec-title' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-left .wjportal-elegant-addon-cp-sec-title' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                ],
		            ]
		        );


		        $this->add_control(
		            'typography_section_title_description',
		            [
		                'type' => \Elementor\Controls_Manager::RAW_HTML,
		                'raw'  => '<p class="elementor-control-field-description">Typography For Section Titles</p>',
		                'separator' => 'after', // Ensures it's placed below
		                'condition' => $pages_for_styling_array,
		            ]
		        );

        		$this->end_controls_section();
    		// Typography Section Title Section END

    		// Typography Button Section Start

		        $this->start_controls_section(
		            'button_typography_section',
		            [
		                'label' => __('Buttons Styling', 'custom-elementor'),
		                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
		                'condition' => $pages_for_styling_array,
		            ]
		        );



		        $this->add_control(
		            'typography_buttons_description',
		            [
		                'type' => \Elementor\Controls_Manager::RAW_HTML,
		                'raw'  => '<p class="elementor-control-field-description">Typography For Buttons</p>',
		                'separator' => 'after', // Ensures it's placed below
		                'condition' => $pages_for_styling_array,
		            ]
		        );


		        // Font Family
		        $this->add_control(
		            'typography_buttons_font_family',
		            [
		                'label' => __('Font Family', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::FONT,
		                'default' => '',
		                'selectors' => [
		                	'{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'font-family: {{VALUE}} !important;',
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'font-family: {{VALUE}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'font-family: {{VALUE}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'font-family: {{VALUE}} !important;',

		                ],
		            ]
		        );

		        // Font Size
		        $this->add_control(
		            'typography_buttons_font_size',
		            [
		                'label' => __('Font Size', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => ['px', 'em', 'rem'],
		                'range' => [
		                    'px' => ['min' => 10, 'max' => 100],
		                    'em' => ['min' => 0.5, 'max' => 5],
		                    'rem' => ['min' => 0.5, 'max' => 5],
		                ],
		                'default' => [
		                    'unit' => 'px',
		                    'size' => 18,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-apply-btn-wrp a.wjportal-elegant-addon-jobs-apply-btn' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-filter-search-wrp .wjportal-elegant-addon-filter-search-btn-wrp button' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-buttnrow a' => 'font-size: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-sect-wrp .wjportal-elegant-addon-cp-view-btn-wrp a' => 'font-size: {{SIZE}}{{UNIT}} !important;',

		                ],
		            ]
		        );

		        // Font Weight
		        $this->add_control(
		            'typography_buttons_font_weight',
		            [
		                'label' => __('Font Weight', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => '400',
		                'options' => [
		                    '100' => 'Thin',
		                    '200' => 'Extra Light',
		                    '300' => 'Light',
		                    '400' => 'Regular',
		                    '500' => 'Medium',
		                    '600' => 'Semi Bold',
		                    '700' => 'Bold',
		                    '800' => 'Extra Bold',
		                    '900' => 'Black',
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'font-weight: {{VALUE}} !important;',
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'font-weight: {{VALUE}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'font-weight: {{VALUE}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'font-weight: {{VALUE}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-apply-btn-wrp a.wjportal-elegant-addon-jobs-apply-btn' => 'font-weight: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-filter-search-wrp .wjportal-elegant-addon-filter-search-btn-wrp button' => 'font-weight: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-buttnrow a' => 'font-weight: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-sect-wrp .wjportal-elegant-addon-cp-view-btn-wrp a' => 'font-weight: {{VALUE}} !important;',
		                ],
		            ]
		        );

		        // Text Transform
		        $this->add_control(
		            'typography_buttons_text_transform',
		            [
		                'label' => __('Text Transform', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'none',
		                'options' => [
		                    'none' => __('None', 'custom-elementor'),
		                    'uppercase' => __('Uppercase', 'custom-elementor'),
		                    'lowercase' => __('Lowercase', 'custom-elementor'),
		                    'capitalize' => __('Capitalize', 'custom-elementor'),
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'text-transform: {{VALUE}} !important;',
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'text-transform: {{VALUE}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'text-transform: {{VALUE}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'text-transform: {{VALUE}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-apply-btn-wrp a.wjportal-elegant-addon-jobs-apply-btn' => 'text-transform: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-filter-search-wrp .wjportal-elegant-addon-filter-search-btn-wrp button' => 'text-transform: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-buttnrow a' => 'text-transform: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-sect-wrp .wjportal-elegant-addon-cp-view-btn-wrp a' => 'text-transform: {{VALUE}} !important;',
		                ],
		            ]
		        );

		        // Font Style
		        $this->add_control(
		            'typography_buttons_font_style',
		            [
		                'label' => __('Font Style', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SELECT,
		                'default' => 'normal',
		                'options' => [
		                    'normal' => __('Normal', 'custom-elementor'),
		                    'italic' => __('Italic', 'custom-elementor'),
		                    'oblique' => __('Oblique', 'custom-elementor'),
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'font-style: {{VALUE}} !important;',
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'font-style: {{VALUE}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'font-style: {{VALUE}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'font-style: {{VALUE}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-apply-btn-wrp a.wjportal-elegant-addon-jobs-apply-btn' => 'font-style: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-filter-search-wrp .wjportal-elegant-addon-filter-search-btn-wrp button' => 'font-style: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-buttnrow a' => 'font-style: {{VALUE}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-sect-wrp .wjportal-elegant-addon-cp-view-btn-wrp a' => 'font-style: {{VALUE}} !important;',
		                ],
		            ]
		        );

		        // Line Height
		        $this->add_control(
		            'typography_buttons_line_height',
		            [
		                'label' => __('Line Height', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => ['px', 'em'],
		                'range' => [
		                    'px' => ['min' => 10, 'max' => 100],
		                    'em' => ['min' => 0.5, 'max' => 5],
		                ],
		                'default' => [
		                    'unit' => 'em',
		                    'size' => 1.1,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-apply-btn-wrp a.wjportal-elegant-addon-jobs-apply-btn' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-filter-search-wrp .wjportal-elegant-addon-filter-search-btn-wrp button' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-buttnrow a' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-sect-wrp .wjportal-elegant-addon-cp-view-btn-wrp a' => 'line-height: {{SIZE}}{{UNIT}} !important;',
		                ],
		            ]
		        );

		        // Letter Spacing
		        $this->add_control(
		            'typography_buttons_letter_spacing',
		            [
		                'label' => __('Letter Spacing', 'custom-elementor'),
		                'type' => \Elementor\Controls_Manager::SLIDER,
		                'size_units' => ['px', 'em'],
		                'range' => [
		                    'px' => ['min' => -5, 'max' => 20],
		                    'em' => ['min' => -0.5, 'max' => 2],
		                ],
		                'default' => [
		                    'unit' => 'px',
		                    'size' => 1,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div#wjportal-job-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-left div.wjportal-cp-user div.wjportal-cp-user-action a.wjportal-cp-user-act-btn' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    //'{{WRAPPER}} div#wjportal-emp-cp-wrp div.wjportal-cp-right div.wjportal-cp-view-btn-wrp a.wjportal-cp-view-btn' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    // elegent
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-jobs-apply-btn-wrp a.wjportal-elegant-addon-jobs-apply-btn' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-filter-search-wrp .wjportal-elegant-addon-filter-search-btn-wrp button' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-tp-banner .wjportal-elegant-addon-tp-banner-buttnrow a' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                    '{{WRAPPER}} div.wjportal-elegant-addon-main-up-wrapper .wjportal-elegant-addon-main-wrp .wjportal-elegant-addon-cp-sect-wrp .wjportal-elegant-addon-cp-view-btn-wrp a' => 'letter-spacing: {{SIZE}}{{UNIT}} !important;',
		                ],
		            ]
		        );

        		$this->end_controls_section();
        		// Typography button Section end

		// Style Tab End

	}

	protected function render(){
		$settings = $this->get_settings_for_display();

        //echo '<pre>';print_r($settings);echo '</pre>';
		if (!empty($settings['jp_shortcode'])) {
            echo '<div class="custom-shortcode-output job-portal-overrides-wrap-typo">';
            echo do_shortcode('['.$settings['jp_shortcode'].']');
            echo '</div>';
        }

	}

}
