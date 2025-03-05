<?php 
class Template_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'Template_Widget';
    }
    
	public function get_title() {
		return esc_html__( 'Form Builder', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'basic'];
	}

	public function get_keywords() {
		return [ 'royal', 'cf7', 'contact form 7', 'caldera forms', 'ninja forms'];
	}


    public function get_custom_help_url() {
    	return 'https://wordpress.org/support/plugin/';
    }

   
    protected function register_controls(): void {

		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Style Section', 'textdomain' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);



	$this->end_controls_section();
    }
    function render() {
		echo "hello";
	}
}