<?php 
// Just copy and past this code in your functions.php file unclock your custom css for free elementor 
// Add a filter to Elementor widget controls
function inject_custom_control( $element, $section_id, $args ) {
  
	if ( 'section_custom_css_pro' === $section_id ) {
           $element->add_control(
			'custom_html',
			[
				'label' => esc_html__( 'Custom HTML', 'sknetking' ),
				'type' => \Elementor\Controls_Manager::CODE,
				'language' => 'css',
				'rows' => 30,
			]
		);

	}

}
add_action( 'elementor/element/after_section_start', 'inject_custom_control', 10, 3 );

// Example of saving the custom HTML data to post meta

function change_heading_widget_content( $widget_content, $widget ) {

	$settings = $widget->get_settings();

		if ( ! empty( $settings['custom_html'] ) ) {
			$widget_content.="<style>". $settings['custom_html']."</style>";
		}

	return $widget_content;

}
add_filter( 'elementor/widget/render_content', 'change_heading_widget_content', 10, 2 );
