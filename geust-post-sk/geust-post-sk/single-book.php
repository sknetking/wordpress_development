<?php
get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <div class="entry-content">
                    <?php
					 if (has_post_thumbnail()) {
                        the_post_thumbnail('large'); // You can use different sizes like 'thumbnail', 'medium', 'large', 'full', etc.
                    }
					
                    the_content();
                    
                    // Display custom fields
                    $author = get_post_meta(get_the_ID(), '_book_author', true);
                    $reading_time = get_post_meta(get_the_ID(), '_book_reading_time', true);
                    $price = get_post_meta(get_the_ID(), '_book_price', true);
                    $book_link = get_post_meta(get_the_ID(), '_book_link', true);
                    ?>
                    <p><strong>Author:</strong> <?php echo esc_html($author); ?></p>
                    <p><strong>Reading Time:</strong> <?php echo esc_html($reading_time); ?></p>
                    <p><strong>Price:</strong> <?php echo esc_html($price); ?></p>
                    <p><strong>Book Link:</strong> <a href="<?php echo esc_url($book_link); ?>" target="_blank">Purchase Here</a></p>
                </div>
            </article>
            <?php
        endwhile;
        ?>
    </main>
</div>

<?php
get_footer();
