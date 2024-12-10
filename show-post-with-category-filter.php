<?php 
function wp_category_filter_shortcode($atts) {
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'taxonomy' => 'category', // Default to 'category', can be replaced with 'tag' or custom taxonomy
    ), $atts);

    $taxonomy = $atts['taxonomy'];

    // Get taxonomy terms
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    ));

    if (empty($terms) || is_wp_error($terms)) {
        return '<p>No terms found.</p>';
    }

    // Query posts
    $posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
    ));

    ob_start();
    ?>

    <div class="filter-wrapper">
        <!-- Display the category list -->
        <ul class="category-filter">
            <li data-category="all" class="filter-item active">All</li>
            <?php foreach ($terms as $term): ?>
                <li data-category="<?php echo esc_attr($term->slug); ?>" class="filter-item">
                    <?php echo esc_html($term->name); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Display posts -->
        <div class="posts-container">
            <?php foreach ($posts as $post): 
                $post_terms = wp_get_post_terms($post->ID, $taxonomy, array('fields' => 'slugs'));
                ?>
                <div class="post-item" data-terms="<?php echo esc_attr(implode(' ', $post_terms)); ?>">
                    <h2><?php echo esc_html($post->post_title); ?></h2>
                    <p><?php echo esc_html(wp_trim_words($post->post_content, 20)); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        .filter-wrapper {
            margin: 20px 0;
        }
        .category-filter {
            display: flex;
            list-style: none;
            gap: 10px;
            padding: 0;
            margin-bottom: 20px;
        }
        .filter-item {
            cursor: pointer;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .filter-item.active {
            background-color: #0073aa;
            color: #fff;
        }
        .post-item {
            margin-bottom: 20px;
            display: none;
        }
        .post-item.active {
            display: block;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterItems = document.querySelectorAll('.filter-item');
            const posts = document.querySelectorAll('.post-item');

            filterItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all filter items
                    filterItems.forEach(el => el.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');

                    const category = this.getAttribute('data-category');

                    posts.forEach(post => {
                        if (category === 'all' || post.getAttribute('data-terms').includes(category)) {
                            post.classList.add('active');
                        } else {
                            post.classList.remove('active');
                        }
                    });
                });
            });

            // Trigger initial "All" filter
            document.querySelector('.filter-item[data-category="all"]').click();
        });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('category_filter', 'wp_category_filter_shortcode');
