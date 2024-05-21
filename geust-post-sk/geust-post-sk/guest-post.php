<?php
/*
Plugin Name: Guest Post
Description: A plugin to manage guest posts with a custom post type "Book".
Version: 1.0
Author: Shyam
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Register Custom Post Type
function guest_post_register_post_type() {
    $labels = array(
        'name'                  => _x('Books', 'Post Type General Name', 'text_domain'),
        'singular_name'         => _x('Book', 'Post Type Singular Name', 'text_domain'),
        'menu_name'             => __('Books', 'text_domain'),
        'name_admin_bar'        => __('Book', 'text_domain'),
        'archives'              => __('Book Archives', 'text_domain'),
        'attributes'            => __('Book Attributes', 'text_domain'),
        'parent_item_colon'     => __('Parent Book:', 'text_domain'),
        'all_items'             => __('All Books', 'text_domain'),
        'add_new_item'          => __('Add New Book', 'text_domain'),
        'add_new'               => __('Add New', 'text_domain'),
        'new_item'              => __('New Book', 'text_domain'),
        'edit_item'             => __('Edit Book', 'text_domain'),
        'update_item'           => __('Update Book', 'text_domain'),
        'view_item'             => __('View Book', 'text_domain'),
        'view_items'            => __('View Books', 'text_domain'),
        'search_items'          => __('Search Book', 'text_domain'),
        'not_found'             => __('Not found', 'text_domain'),
        'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
        'featured_image'        => __('Featured Image', 'text_domain'),
        'set_featured_image'    => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image'    => __('Use as featured image', 'text_domain'),
        'insert_into_item'      => __('Insert into book', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this book', 'text_domain'),
        'items_list'            => __('Books list', 'text_domain'),
        'items_list_navigation' => __('Books list navigation', 'text_domain'),
        'filter_items_list'     => __('Filter books list', 'text_domain'),
    );

    $args = array(
        'label'                 => __('Book', 'text_domain'),
        'description'           => __('A custom post type for books', 'text_domain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'taxonomies'            => array('category', 'post_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );

    register_post_type('book', $args);
}
add_action('init', 'guest_post_register_post_type', 0);

// Add Custom Meta Boxes
function guest_post_add_meta_boxes() {
    add_meta_box(
        'book_details_meta_box', // $id
        'Book Details', // $title
        'guest_post_build_meta_box', // $callback
        'book', // $screen
        'normal', // $context
        'high' // $priority
    );
}
add_action('add_meta_boxes', 'guest_post_add_meta_boxes');

function guest_post_build_meta_box($post) {
    // Nonce field for security
    wp_nonce_field(basename(__FILE__), 'book_details_nonce');

    // Retrieve existing values from the database
    $author = get_post_meta($post->ID, '_book_author', true);
    $reading_time = get_post_meta($post->ID, '_book_reading_time', true);
    $price = get_post_meta($post->ID, '_book_price', true);
    $book_link = get_post_meta($post->ID, '_book_link', true);

    ?>
    <table class="form-table">
        <tr>
            <th><label for="book_author">Author</label></th>
            <td><input type="text" name="book_author" id="book_author" value="<?php echo esc_attr($author); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="book_reading_time">Reading Time</label></th>
            <td><input type="text" name="book_reading_time" id="book_reading_time" value="<?php echo esc_attr($reading_time); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="book_price">Price</label></th>
            <td><input type="text" name="book_price" id="book_price" value="<?php echo esc_attr($price); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="book_link">Book Link</label></th>
            <td><input type="text" name="book_link" id="book_link" value="<?php echo esc_attr($book_link); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Save Custom Meta Box Data
function guest_post_save_meta_box_data($post_id) {
    // Check for nonce to secure data
    if (!isset($_POST['book_details_nonce']) || !wp_verify_nonce($_POST['book_details_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Save/Update meta data
    $fields = ['book_author', 'book_reading_time', 'book_price', 'book_link'];
    foreach ($fields as $field) {
        $new_value = (isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : '');
        $meta_key = '_' . $field;

        $meta_value = get_post_meta($post_id, $meta_key, true);
        if ($new_value && $new_value !== $meta_value) {
            update_post_meta($post_id, $meta_key, $new_value);
        } elseif ('' === $new_value && $meta_value) {
            delete_post_meta($post_id, $meta_key);
        }
    }
}
add_action('save_post', 'guest_post_save_meta_box_data');



// Filter single template for custom post type
function guest_post_single_template($single_template) {
    global $post;

    if ($post->post_type == 'book') {
        if (file_exists(plugin_dir_path(__FILE__) . 'single-book.php')) {
            return plugin_dir_path(__FILE__) . 'single-book.php';
        }
    }

    return $single_template;
}
add_filter('single_template', 'guest_post_single_template');

// Filter archive template for custom post type
function guest_post_archive_template($archive_template) {
    if (is_post_type_archive('book')) {
        if (file_exists(plugin_dir_path(__FILE__) . 'archive-book.php')) {
            return plugin_dir_path(__FILE__) . 'archive-book.php';
        }
    }

    return $archive_template;
}
add_filter('archive_template', 'guest_post_archive_template');
