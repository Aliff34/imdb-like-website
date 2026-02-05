<?php
get_header();
?>
<section class="section">
    <div class="section-title">
        <h2><?php esc_html_e('Latest Posts', 'cartoondb'); ?></h2>
    </div>
    <div class="movie-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article class="movie-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large'); ?>
                    <?php endif; ?>
                    <div class="movie-card-body">
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                        <div class="movie-actions">
                            <a href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'cartoondb'); ?></a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e('No posts found.', 'cartoondb'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
