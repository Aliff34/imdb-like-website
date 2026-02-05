<?php
$rating = get_post_meta(get_the_ID(), 'cartoondb_rating', true);
$release_year = get_post_meta(get_the_ID(), 'cartoondb_release_year', true);
$runtime = get_post_meta(get_the_ID(), 'cartoondb_runtime', true);
?>
<article class="movie-card">
    <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('large'); ?>
    <?php endif; ?>
    <div class="movie-card-body">
        <h3><?php the_title(); ?></h3>
        <div class="movie-meta">
            <?php if ($release_year) : ?>
                <span><?php echo esc_html($release_year); ?></span>
            <?php endif; ?>
            <?php if ($runtime) : ?>
                <span>• <?php echo esc_html($runtime); ?></span>
            <?php endif; ?>
        </div>
        <?php if ($rating) : ?>
            <span class="rating-pill">★ <?php echo esc_html($rating); ?>/10</span>
        <?php endif; ?>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
        <div class="movie-actions">
            <a href="<?php the_permalink(); ?>"><?php esc_html_e('Details', 'cartoondb'); ?></a>
        </div>
    </div>
</article>
