<?php
get_header();

$latest_movies = new WP_Query([
    'post_type' => 'movie',
    'posts_per_page' => 8,
]);

$featured_movie = new WP_Query([
    'post_type' => 'movie',
    'posts_per_page' => 1,
]);
?>
<section class="hero">
    <div class="hero-content">
        <div>
            <h1><?php esc_html_e('Welcome to CartoonDB', 'cartoondb'); ?></h1>
            <p><?php esc_html_e('Discover the latest animated worlds, curated cast insights, ratings, and trailers—all in one cinematic hub.', 'cartoondb'); ?></p>
            <a class="hero-cta" href="<?php echo esc_url(get_post_type_archive_link('movie')); ?>">
                <?php esc_html_e('Browse all movies', 'cartoondb'); ?>
            </a>
        </div>
        <div class="featured-panel">
            <h3><?php esc_html_e('Featured Spotlight', 'cartoondb'); ?></h3>
            <?php if ($featured_movie->have_posts()) : ?>
                <?php while ($featured_movie->have_posts()) : $featured_movie->the_post(); ?>
                    <h2><?php the_title(); ?></h2>
                    <p><?php echo esc_html(get_the_excerpt()); ?></p>
                    <div class="movie-actions">
                        <a href="<?php the_permalink(); ?>"><?php esc_html_e('View details', 'cartoondb'); ?></a>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p><?php esc_html_e('Add a movie to feature it here.', 'cartoondb'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-title">
        <h2><?php esc_html_e('New Releases', 'cartoondb'); ?></h2>
        <a href="<?php echo esc_url(get_post_type_archive_link('movie')); ?>"><?php esc_html_e('See all', 'cartoondb'); ?></a>
    </div>
    <div class="movie-grid">
        <?php if ($latest_movies->have_posts()) : ?>
            <?php while ($latest_movies->have_posts()) : $latest_movies->the_post(); ?>
                <?php get_template_part('template-parts/movie-card'); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e('No movies yet. Add your first CartoonDB entry from the dashboard.', 'cartoondb'); ?></p>
        <?php endif; ?>
    </div>
    <?php wp_reset_postdata(); ?>
</section>
<?php
get_footer();
