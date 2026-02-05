<?php
get_header();

while (have_posts()) :
    the_post();
    $trailer = get_post_meta(get_the_ID(), 'cartoondb_trailer_url', true);
    $cast = get_post_meta(get_the_ID(), 'cartoondb_cast', true);
    $rating = get_post_meta(get_the_ID(), 'cartoondb_rating', true);
    $release_year = get_post_meta(get_the_ID(), 'cartoondb_release_year', true);
    $runtime = get_post_meta(get_the_ID(), 'cartoondb_runtime', true);
    $gallery_images = cartoondb_get_gallery_images(get_the_ID());
    $genres = get_the_terms(get_the_ID(), 'movie_genre');
    ?>
    <section class="single-hero">
        <div>
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
            <?php endif; ?>
        </div>
        <div class="single-content">
            <h1><?php the_title(); ?></h1>
            <?php if ($rating) : ?>
                <span class="rating-pill">★ <?php echo esc_html($rating); ?>/10</span>
            <?php endif; ?>
            <p><?php the_content(); ?></p>
            <div class="detail-grid">
                <?php if ($release_year) : ?>
                    <div class="detail-card">
                        <strong><?php esc_html_e('Release Year', 'cartoondb'); ?></strong>
                        <p><?php echo esc_html($release_year); ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($runtime) : ?>
                    <div class="detail-card">
                        <strong><?php esc_html_e('Runtime', 'cartoondb'); ?></strong>
                        <p><?php echo esc_html($runtime); ?></p>
                    </div>
                <?php endif; ?>
                <?php if ($genres && !is_wp_error($genres)) : ?>
                    <div class="detail-card">
                        <strong><?php esc_html_e('Genres', 'cartoondb'); ?></strong>
                        <p>
                            <?php
                            echo esc_html(implode(', ', wp_list_pluck($genres, 'name')));
                            ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($trailer) : ?>
                <div class="movie-actions">
                    <a href="<?php echo esc_url($trailer); ?>" target="_blank" rel="noopener">
                        <?php esc_html_e('Watch Trailer', 'cartoondb'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <div class="section-title">
            <h2><?php esc_html_e('Cast & Crew', 'cartoondb'); ?></h2>
        </div>
        <div class="movie-grid">
            <div class="movie-card">
                <div class="movie-card-body">
                    <p><?php echo esc_html($cast ? $cast : __('Add cast details in the dashboard to show them here.', 'cartoondb')); ?></p>
                </div>
            </div>
        </div>
    </section>

    <?php if ($gallery_images) : ?>
        <section class="section">
            <div class="section-title">
                <h2><?php esc_html_e('Gallery', 'cartoondb'); ?></h2>
            </div>
            <div class="gallery">
                <?php foreach ($gallery_images as $image) : ?>
                    <div class="movie-card">
                        <?php echo wp_get_attachment_image($image->ID, 'medium_large'); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
<?php endwhile; ?>

<?php
get_footer();
