<?php
get_header();
?>
<section class="section">
    <div class="section-title">
        <h2><?php esc_html_e('All Movies', 'cartoondb'); ?></h2>
    </div>
    <div class="movie-grid">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/movie-card'); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e('No movies found.', 'cartoondb'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php
get_footer();
