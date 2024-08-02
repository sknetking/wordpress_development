<?php
/* Template Name: Post Grid */

get_header();

// Get all categories
$categories = get_categories();

// Get the current page number and category from the URL parameters
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$posts_per_page = isset($_GET['posts_per_page']) ? intval($_GET['posts_per_page']) : 6;
$selected_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';

?>

<div class="container" style="margin-top:100px;">
    <div class="row my-4">
        <div class="col-md-4 offset-md-8">
            <form method="GET" id="category-filter-form">
                <select name="category" id="category-filter" class="form-control" onchange="document.getElementById('category-filter-form').submit();">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $category) : ?>
                        <option value="<?php echo $category->slug; ?>" <?php selected($selected_category, $category->slug); ?>>
                            <?php echo $category->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="paged" value="1" />
                <input type="hidden" name="posts_per_page" value="<?php echo $posts_per_page; ?>" />
            </form>
        </div>
    </div>

    <div id="post-grid" class="row">
        <?php
        // Query arguments
        $args = array(
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
        );

        if (!empty($selected_category)) {
            $args['category_name'] = $selected_category;
        }

        // Query posts
        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $post_categories = get_the_category();
                $post_category_slugs = array_map(function($cat) { return $cat->slug; }, $post_categories);
                $post_category_slugs_string = implode(' ', $post_category_slugs);
                ?>
                <div class="col-md-4 mb-4 post-item" data-category="<?php echo $post_category_slugs_string; ?>">
                    <div class="card">
                        <?php if(has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                            <p class="card-text"><?php the_excerpt(); ?></p>
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No posts found</p>';
        endif;
        ?>
    </div>

    <?php if ($query->max_num_pages > 1) : ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php
                $big = 999999999; // need an unlikely integer
                echo paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $query->max_num_pages,
                    'add_args' => array(
                        'category' => $selected_category,
                        'posts_per_page' => $posts_per_page
                    ),
                ));
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
