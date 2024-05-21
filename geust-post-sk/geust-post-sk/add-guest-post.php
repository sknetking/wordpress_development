<?php 
//guest post adding from here
// Shortcode to display the post submission form
function guest_post_submission_form() {
    if (!is_user_logged_in()) {
        return '<p>You need to be logged in to submit a book.</p>';
    }

    ob_start();
    
    if (isset($_POST['guest_post_submit'])) {
        guest_post_handle_submission();
    }

    ?>
    <form id="guest-post-form" method="POST">
        <p>
            <label for="book_title">Book Title</label>
            <input type="text" id="book_title" name="book_title" required>
        </p>
        <p>
            <label for="book_author">Author</label>
            <input type="text" id="book_author" name="book_author" required>
        </p>
        <p>
            <label for="book_reading_time">Reading Time</label>
            <input type="text" id="book_reading_time" name="book_reading_time" required>
        </p>
        <p>
            <label for="book_price">Price</label>
            <input type="text" id="book_price" name="book_price" required>
        </p>
        <p>
            <label for="book_link">Book Link</label>
            <input type="url" id="book_link" name="book_link" required>
        </p>
        <p>
            <label for="book_category">Category</label>
            <select id="book_category" name="book_category" required>
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'category',
                    'hide_empty' => false,
                ));
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                }
                ?>
            </select>
        </p>
        <p>
            <input type="submit" name="guest_post_submit" value="Submit Book">
        </p>
    </form>
    <?php

    return ob_get_clean();
}
add_shortcode('guest_post_form', 'guest_post_submission_form');

// Handle the form submission
function guest_post_handle_submission() {
    if (!isset($_POST['guest_post_submit'])) {
        return;
    }

    // Check for duplicate title
    $title = sanitize_text_field($_POST['book_title']);
    if (post_exists($title)) {
        echo '<p>A post with this title already exists. Please choose a different title.</p>';
        return;
    }

    // Prepare the post data
    $post_data = array(
        'post_title'    => $title,
        'post_content'  => '', // You can add a field for post content if needed
        'post_status'   => 'pending',
        'post_type'     => 'book',
        'post_author'   => get_current_user_id(),
        'post_category' => array(intval($_POST['book_category'])),
    );

    // Insert the post
    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Add custom meta data
        update_post_meta($post_id, '_book_author', sanitize_text_field($_POST['book_author']));
        update_post_meta($post_id, '_book_reading_time', sanitize_text_field($_POST['book_reading_time']));
        update_post_meta($post_id, '_book_price', sanitize_text_field($_POST['book_price']));
        update_post_meta($post_id, '_book_link', esc_url($_POST['book_link']));

        echo '<p>Your book has been submitted successfully and is awaiting review.</p>';
    } else {
        echo '<p>There was an error submitting your book. Please try again.</p>';
    }
}
