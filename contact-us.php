
function enqueue_contact_form_script() {
   wp_enqueue_script('contact-form-script', get_template_directory_uri() . '/contact-form.js', array('jquery'), null, true);

    wp_localize_script('contact-form-script', 'contactForm', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('contact_form_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_contact_form_script');

// Handle AJAX form submission
function custom_contact_form_handler() {
    check_ajax_referer('contact_form_nonce', 'security');

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    // Create a new post of type 'contact_details'
    $post_id = wp_insert_post([
        'post_title'  => $first_name . ' ' . $last_name,
        'post_content'=> "<b>From- ".$email."</b><br/>".$message,
        'post_type'   => 'contact_details',
        'post_status' => 'publish',
        'meta_input'  => [
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
        ],
    ]);

    if ($post_id) {
        // Send email to admin
        $admin_email = get_option('admin_email');
        $subject_admin = 'New Contact Form Submission';
        $message_admin = "First Name: $first_name\nLast Name: $last_name\nEmail: $email\nMessage: $message";
        wp_mail($admin_email, $subject_admin, $message_admin);

        // Send confirmation email to user
        $subject_user = 'Thank you for contacting us';
        $message_user = "Dear $first_name $last_name,\n\nThank you for your message. We will get back to you shortly.\n\nBest regards,\nYour Company";
        wp_mail($email, $subject_user, $message_user);

        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_submit_contact_form', 'custom_contact_form_handler');
add_action('wp_ajax_nopriv_submit_contact_form', 'custom_contact_form_handler');

// Register custom post type 'contact_details'
function create_contact_details_cpt() {
    register_post_type('contact_details', [
        'labels' => [
            'name' => __('Contact Details'),
            'singular_name' => __('Contact Detail'),
        ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'custom-fields'],
        'show_in_menu' => true,
        'show_in_rest' => true,
    ]);
}
add_action('init', 'create_contact_details_cpt');

// Disable manual post creation for 'contact_details'
function disable_manual_contact_details_creation() {
    remove_submenu_page('edit.php?post_type=contact_details', 'post-new.php?post_type=contact_details');
}
add_action('admin_menu', 'disable_manual_contact_details_creation', 999);
