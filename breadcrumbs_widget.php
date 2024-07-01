<?php
/*
*Just post and paste this code in your php file in theme dir or in plugin then - 
*include this in your theme functions.php file or plugin main file --- 
*  function register_contact_form_widget {require_once( __DIR__ . '/elementor-widget/breadcrumbs.php' );
* $widgets_manager->register( new \breadcrumbs_widget() ); 
*   }
*  add_action( 'elementor/widgets/register', 'register_contact_form_widget' );
*/

class breadcrumbs_widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'breadcrumbs_widget';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumb', 'sknetking' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'Breadcrumb', 'yoast','header','bread' ];
	}

	public function get_custom_help_url() {
		return 'https://www.sknetking.online';
	}
	
	protected function get_upsale_data() {
		return [
			'condition' => ! \Elementor\Utils::has_pro(),
			'image' => esc_url( ELEMENTOR_ASSETS_URL . 'images/go-pro.svg' ),
			'image_alt' => esc_attr__( 'Visit', 'sknetking' ),
			'title' => esc_html__( 'Subscribe us on Youtube', 'sknetking' ),
			'description' => esc_html__( 'Subscribe and get pro version widget free.', 'sknetking' ),
			'upgrade_url' => esc_url( 'https://youtube.com/sknetking/' ),
			'upgrade_text' => esc_html__( 'Subscribe Now', 'sknetking' ),
		];
	}


	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'sknetking' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'base_text',
			[
				'label' => esc_html__( 'Edit title', 'sknetking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Home', 'sknetking' ),
			]
		);
		$this->add_control(
			'separator_text',
			[
				'label' => esc_html__( 'Separator text', 'sknetking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '/', 'sknetking' ),
			]
		);

			$this->add_control(
			'custom_panel_notice',
			[
				'type' => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'warning',
				'dismissible' => true,
				'heading' => esc_html__( 'Notice', 'sknetking' ),
				'content' => esc_html__( 'Margin, Padding Background border and many option you can set form Style tab & advance tab.', 'sknetking' ),
			]
		);

		$this->end_controls_section();

		// Content Tab End
		
// Register the custom control


		// Style Tab Start

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'sknetking' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'style_tabs'
		);

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'sknetking' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'sknetking' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'a_color',
			[
				'label' => esc_html__( 'link Color', 'sknetking' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .breadcrumbs a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .breadcrumbs,breadcrumbs a',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'sknetking' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .separator' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'separator_typography',
				'selector' => '{{WRAPPER}} .separator',
			]
		);
		$this->add_control(
			'separator_margin',
			[
				'label' => esc_html__( 'Separator Margin', 'sknetking' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

$this->end_controls_tab();

$this->start_controls_tab(
	'style_hover_tab',
	[
		'label' => esc_html__( 'Hover', 'sknetking' ),
	]
);

$this->add_control(
	'ha_color',
	[
		'label' => esc_html__( 'link Color', 'sknetking' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .breadcrumbs a:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'htitle_color',
	[
		'label' => esc_html__( 'Text Color', 'sknetking' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .breadcrumbs:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'hseparator_color',
	[
		'label' => esc_html__( 'Separator Color', 'sknetking' ),
		'type' => \Elementor\Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .separator:hover' => 'color: {{VALUE}};',
		],
	]
);


$this->end_controls_tab();
$this->end_controls_tabs();

$this->end_controls_section();

}// Style Tab End

	

protected function render() {
	$settings = $this->get_settings_for_display();

global $post;
$separator = "<span class='separator'>".$settings['separator_text']."</span>"; 
$home_title = $settings['base_text'];
// Check if it's not the homepage
if (!is_front_page()) {
    // Start the breadcrumb with a link to the homepage
    echo '<nav class="breadcrumbs"><ol>';
    echo '<li><a href="' . home_url() . '">' . $home_title . '</a></li>';

    if (is_category() || is_single()) {
        // Category or Single Post
        echo $separator;
        the_category(' </li><li> ');
        if (is_single()) {
            echo $separator . '<li class="current">' . get_the_title() . '</li>';
        }
    } elseif (is_page()) {
        // Static Page
        if ($post->post_parent) {
            $anc = get_post_ancestors($post->ID);
            $anc = array_reverse($anc);
            foreach ($anc as $ancestor) {
                echo $separator . '<li><a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
            }

        }
        echo  $separator.'<li class="current">' . get_the_title() . '</li>';
    } elseif (is_tag()) {
        // Tag Archive
        echo $separator . '<li class="current">' . single_tag_title('', false) . '</li>';
    } elseif (is_day()) {
        // Daily Archive
        echo $separator . '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
        echo $separator . '<li><a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a></li>';
        echo $separator . '<li class="current">' . get_the_time('d') . '</li>';
    } elseif (is_month()) {
        // Monthly Archive
        echo $separator . '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a></li>';
        echo $separator . '<li class="current">' . get_the_time('F') . '</li>';
    } elseif (is_year()) {
        // Yearly Archive
        echo $separator . '<li class="current">' . get_the_time('Y') . '</li>';
    } elseif (is_author()) {
        // Author Archive
        echo $separator . '<li class="current">' . get_the_author() . '</li>';
    } elseif (is_search()) {
        // Search Results
        echo $separator . '<li class="current">Search results for: ' . get_search_query() . '</li>';
    } elseif (is_404()) {
        // 404 Page
        echo $separator . '<li class="current">Error 404</li>';
    }
	if (is_archive()) {
       
        echo $separator . '<li class="current">Archives</li>';
    }

    echo '</ol></nav>';
}

	}

	
}
