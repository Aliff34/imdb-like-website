<?php
$rating = get_post_meta(get_the_ID(), 'cartoondb_rating', true);
$release_year = get_post_meta(get_the_ID(), 'cartoondb_release_year', true);
$runtime = get_post_meta(get_the_ID(), 'cartoondb_runtime', true);
$poster_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';
?>
<article class="movie-card" style="<?php echo $poster_url ? 'background-image:url(' . esc_url($poster_url) . ');' : ''; ?>">
    <a class="movie-card-link" href="<?php the_permalink(); ?>">
        <div class="movie-card-overlay"></div>
        <div class="movie-card-content">
            <div class="movie-meta">
                <?php if ($release_year) : ?>
                    <span><?php echo esc_html($release_year); ?></span>
                <?php endif; ?>
                <?php if ($runtime) : ?>
                    <span>• <?php echo esc_html($runtime); ?></span>
                <?php endif; ?>
            </div>
            <h3><?php the_title(); ?></h3>
            <?php if ($rating) : ?>
                <span class="rating-pill">★ <?php echo esc_html($rating); ?>/10</span>
            <?php endif; ?>
        </div>
    </a>
</article>
