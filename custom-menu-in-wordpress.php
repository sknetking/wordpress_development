<?php 
//code goes to functions.php file

// Register Footer 1 Menu Location
function register_footer_menu() {
    register_nav_menus(
        array(
            'footer-1' => __('Footer 1')
        )
    );
}
add_action('init', 'register_footer_menu');

//menu walker for classes 
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
    private $ul_class = 'footer-menu';
    private $li_class_main = 'main-menu-item';
    private $li_class_sub = 'sub-menu-item';
    private $a_class_main = 'main-menu-link';
    private $a_class_sub = 'sub-menu-link';

    // Add classes to ul sub-menus
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $classes = array('sub-menu');
        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $output .= "\n$indent<ul$class_names>\n";
    }

    // Add main/sub classes to li's and link (a)
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Custom class for li
        $li_class = 'menu-item ' . (($depth == 0) ? $this->li_class_main : $this->li_class_sub);

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        // Custom class for li tag
        $output .= $indent . '<li' . $class_names . ' class="' . $li_class . '">';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';

        // Custom class for a tag
        $link_class = 'menu-link ' . (($depth == 0) ? $this->a_class_main : $this->a_class_sub);

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . ' class="' . $link_class . '">';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}


// show menu code goes to header or footer or any place where you want show menu - 
?>
<div class="footer-menu">
		<h3 class="footer-heading">Navigation</h3>
	<?php
        // Display Footer 1 Menu
        if (has_nav_menu('footer-1')) {
            wp_nav_menu(array(
                'theme_location' => 'footer-1',
                'container' => 'ul',
                'menu_class' => 'footer-links list-unstyled', // Class for the <ul> tag
                'link_before'		=> '<i class="bi bi-chevron-right"></i>',
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 1, // Adjust this value to change the depth of the menu
                'walker' => new Custom_Walker_Nav_Menu() // Use a custom walker
            ));
        } else {
            echo '<ul class="footer-menu"><li>' . __('<a href="'.site_url().'/wp-admin/nav-menus.php">Menu not set</a>') . '</li></ul>';
        }
    ?>
</div>
