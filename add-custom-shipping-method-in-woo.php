
//adding shipping methoud

function wkwc_add_shipping_method( $methods ) {
    $methods['wkwc-shipping-method'] = 'WKWC_Webkul_Shipping_Method';
    return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'wkwc_add_shipping_method' );
if ( ! class_exists( 'WKWC_Webkul_Shipping_Method' ) ) {

    
    // defined( 'ABSPATH' ) || exit;
    class WKWC_Webkul_Shipping_Method extends WC_Shipping_Method {

      /**
       * Shipping class
       */
      public function __construct($instance_id = 0) {
        // These title description are display on the configuration page
        $this->id = 'wkwc-shipping-method';
        $this->instance_id  = absint( $instance_id );
        $this->method_title = esc_html__('Webkul Shipping', 'wkwc-shipping' );
        $this->method_description = esc_html__('Webkul WooCommerce Shipping', 'wkwc-shipping' );
        $this->enabled = "yes";
        //$this->title = "Webkul Shipping";
        $this->title = esc_html__('Webkul Shipping', 'wkwc-shipping' );
        $this->supports = array(
           'shipping-zones',
           'instance-settings',
           'instance-settings-modal',
           'settings',
        );

        // Run the initial method
        $this->init();
        
       }


       /**
        ** Load the settings API
        */
       public function init() {
         // Load the settings API
         $this->init_settings();
         $this->init_form_fields();

        //$this->title = $this->get_option( 'title' );
         add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
       }


       public function init_form_fields() {
         $form_fields = array(
           'title' => array(
              'title'       => esc_html__('Method Title', 'wkwc-shipping'),
              'type'        => 'text',
              'description' => esc_html__('Enter the method title', 'wkwc-shipping'  ),
              'default'     => esc_html__('', 'wkwc-shipping' ),
              'desc_tip'    => true,
           ),

           'description' => array(
              'title'       => esc_html__('Description', 'wkwc-shipping' ),
              'type'        => 'textarea',
              'description' => esc_html__('Enter the Description', 'wkwc-shipping'  ),
              'default'     => esc_html__('', 'wkwc-shipping' ),
              'desc_tip'    => true,
           ),

           'tax_status' => array(
              'title'   => __( 'Tax status', 'wkwc-shipping' ),
              'type'    => 'select',
              'class'   => 'wc-enhanced-select',
              'default' => 'taxable',
              'options' => array(
                  'taxable' => __( 'Taxable', 'wkwc-shipping' ),
                  'none'    => _x( 'None', 'Tax status', 'wkwc-shipping' ),
              ),
            ),

           'cost' => array(
              'title'       => esc_html__('Cost', 'wkwc-shipping' ),
              'type'        => 'number',
              'description' => esc_html__('Add the method cost', 'wkwc-shipping'  ),
              'default'     => esc_html__('', 'wkwc-shipping' ),
              'desc_tip'    => true,
           )
         );
          $this->form_fields = $form_fields;
         

       }
       

       /**
        ** Calculate Shipping rate
        */
       public function calculate_shipping( $package = array() ) {
          $this->add_rate( array(
            'id'     => $this->id,
            'label'  => $this->settings['title'],
            'cost'   => $this->settings['cost']
          ));
       }
    }
}
