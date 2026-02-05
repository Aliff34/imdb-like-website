<?php
get_header();

while (have_posts()) :
    the_post();
    $trailer = get_post_meta(get_the_ID(), 'cartoondb_trailer_url', true);
    $trailer_video_id = get_post_meta(get_the_ID(), 'cartoondb_trailer_video_id', true);
    $rating = get_post_meta(get_the_ID(), 'cartoondb_rating', true);
    $release_year = get_post_meta(get_the_ID(), 'cartoondb_release_year', true);
    $runtime = get_post_meta(get_the_ID(), 'cartoondb_runtime', true);
    $gallery_images = cartoondb_get_gallery_images(get_the_ID());
    $genres = get_the_terms(get_the_ID(), 'movie_genre');
    $cast_members = cartoondb_get_cast_members(get_the_ID());
    ?>
    <section class="single-hero">
        <div class="single-poster">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
            <?php endif; ?>
        </div>
        <div class="single-content">
            <div class="single-header">
                <h1><?php the_title(); ?></h1>
                <?php if ($rating) : ?>
                    <span class="rating-pill">★ <?php echo esc_html($rating); ?>/10</span>
                <?php endif; ?>
            </div>
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
            <div class="movie-actions">
                <?php if ($trailer) : ?>
                    <a href="<?php echo esc_url($trailer); ?>" target="_blank" rel="noopener">
                        <?php esc_html_e('Watch Trailer', 'cartoondb'); ?>
                    </a>
                <?php endif; ?>
                <?php if ($trailer_video_id) : ?>
                    <a href="#trailer-video">
                        <?php esc_html_e('Play Trailer', 'cartoondb'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if ($trailer_video_id) : ?>
        <section class="section" id="trailer-video">
            <div class="section-title">
                <h2><?php esc_html_e('Trailer Video', 'cartoondb'); ?></h2>
            </div>
            <div class="featured-panel">
                <?php echo wp_video_shortcode(['id' => (int) $trailer_video_id]); ?>
            </div>
        </section>
    <?php endif; ?>

    <section class="section">
        <div class="section-title">
            <h2><?php esc_html_e('Cast & Crew', 'cartoondb'); ?></h2>
        </div>
        <div class="cast-grid">
            <?php if ($cast_members) : ?>
                <?php foreach ($cast_members as $member) : ?>
                    <div class="cast-card">
                        <div class="cast-photo">
                            <?php if (!empty($member['image_id'])) : ?>
                                <?php echo wp_get_attachment_image((int) $member['image_id'], 'thumbnail'); ?>
                            <?php else : ?>
                                <span class="cast-placeholder">★</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <strong><?php echo esc_html($member['name'] ?? ''); ?></strong>
                            <?php if (!empty($member['role'])) : ?>
                                <p><?php echo esc_html($member['role']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="cast-empty">
                    <p><?php esc_html_e('Add cast details in the dashboard to show them here.', 'cartoondb'); ?></p>
                </div>
            <?php endif; ?>
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
