<?php

if (!defined('ABSPATH')) {
    exit;
}

function cartoondb_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption']);
    register_nav_menus([
        'primary' => __('Primary Menu', 'cartoondb'),
    ]);
}
add_action('after_setup_theme', 'cartoondb_theme_setup');

function cartoondb_enqueue_assets() {
    wp_enqueue_style('cartoondb-style', get_stylesheet_uri(), [], '1.1.0');
    wp_enqueue_style('cartoondb-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap', [], null);
}
add_action('wp_enqueue_scripts', 'cartoondb_enqueue_assets');

function cartoondb_register_movie_cpt() {
    $labels = [
        'name' => __('Movies', 'cartoondb'),
        'singular_name' => __('Movie', 'cartoondb'),
        'add_new' => __('Add New Movie', 'cartoondb'),
        'add_new_item' => __('Add New Movie', 'cartoondb'),
        'edit_item' => __('Edit Movie', 'cartoondb'),
        'new_item' => __('New Movie', 'cartoondb'),
        'view_item' => __('View Movie', 'cartoondb'),
        'search_items' => __('Search Movies', 'cartoondb'),
        'not_found' => __('No movies found', 'cartoondb'),
        'menu_name' => __('Movies', 'cartoondb'),
    ];

    register_post_type('movie', [
        'labels' => $labels,
        'public' => true,
        'menu_icon' => 'dashicons-video-alt2',
        'has_archive' => true,
        'rewrite' => ['slug' => 'movies'],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('movie_genre', 'movie', [
        'label' => __('Genres', 'cartoondb'),
        'rewrite' => ['slug' => 'genre'],
        'hierarchical' => true,
        'show_in_rest' => true,
    ]);
}
add_action('init', 'cartoondb_register_movie_cpt');

function cartoondb_register_movie_meta() {
    register_post_meta('movie', 'cartoondb_trailer_url', [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'esc_url_raw',
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
    register_post_meta('movie', 'cartoondb_trailer_video_id', [
        'single' => true,
        'type' => 'number',
        'show_in_rest' => true,
        'sanitize_callback' => 'absint',
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
    register_post_meta('movie', 'cartoondb_cast_json', [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'wp_kses_post',
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
    register_post_meta('movie', 'cartoondb_rating', [
        'single' => true,
        'type' => 'number',
        'show_in_rest' => true,
        'sanitize_callback' => 'floatval',
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
    register_post_meta('movie', 'cartoondb_release_year', [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
    register_post_meta('movie', 'cartoondb_runtime', [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
    register_post_meta('movie', 'cartoondb_gallery_ids', [
        'single' => true,
        'type' => 'string',
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => function () {
            return current_user_can('edit_posts');
        },
    ]);
}
add_action('init', 'cartoondb_register_movie_meta');

function cartoondb_add_movie_metaboxes() {
    add_meta_box(
        'cartoondb_movie_details',
        __('Movie Details', 'cartoondb'),
        'cartoondb_render_movie_metabox',
        'movie',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'cartoondb_add_movie_metaboxes');

function cartoondb_admin_assets($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'movie') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'cartoondb-admin',
        get_template_directory_uri() . '/assets/js/admin-media.js',
        ['jquery'],
        '1.1.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'cartoondb_admin_assets');

function cartoondb_render_movie_metabox($post) {
    wp_nonce_field('cartoondb_save_movie_meta', 'cartoondb_movie_meta_nonce');

    $trailer = get_post_meta($post->ID, 'cartoondb_trailer_url', true);
    $trailer_video_id = get_post_meta($post->ID, 'cartoondb_trailer_video_id', true);
    $cast_json = get_post_meta($post->ID, 'cartoondb_cast_json', true);
    $rating = get_post_meta($post->ID, 'cartoondb_rating', true);
    $release_year = get_post_meta($post->ID, 'cartoondb_release_year', true);
    $runtime = get_post_meta($post->ID, 'cartoondb_runtime', true);
    $gallery_ids = get_post_meta($post->ID, 'cartoondb_gallery_ids', true);
    $cast_items = $cast_json ? json_decode($cast_json, true) : [];
    if (!is_array($cast_items)) {
        $cast_items = [];
    }

    ?>
    <p>
        <label for="cartoondb_trailer_url"><strong><?php esc_html_e('Trailer URL (YouTube/Vimeo)', 'cartoondb'); ?></strong></label>
        <input type="url" name="cartoondb_trailer_url" id="cartoondb_trailer_url" value="<?php echo esc_attr($trailer); ?>" class="widefat" />
    </p>
    <p>
        <label for="cartoondb_trailer_video_id"><strong><?php esc_html_e('Trailer Video Upload', 'cartoondb'); ?></strong></label>
        <input type="text" name="cartoondb_trailer_video_id" id="cartoondb_trailer_video_id" value="<?php echo esc_attr($trailer_video_id); ?>" class="widefat" />
        <button type="button" class="button" id="cartoondb_trailer_upload"><?php esc_html_e('Select Trailer Video', 'cartoondb'); ?></button>
        <small><?php esc_html_e('Upload or select a video from the media library.', 'cartoondb'); ?></small>
    </p>
    <p>
        <label for="cartoondb_release_year"><strong><?php esc_html_e('Release Year', 'cartoondb'); ?></strong></label>
        <input type="text" name="cartoondb_release_year" id="cartoondb_release_year" value="<?php echo esc_attr($release_year); ?>" class="widefat" />
    </p>
    <p>
        <label for="cartoondb_runtime"><strong><?php esc_html_e('Runtime', 'cartoondb'); ?></strong></label>
        <input type="text" name="cartoondb_runtime" id="cartoondb_runtime" value="<?php echo esc_attr($runtime); ?>" class="widefat" />
    </p>
    <p>
        <label for="cartoondb_rating"><strong><?php esc_html_e('Rating (0-10)', 'cartoondb'); ?></strong></label>
        <input type="number" step="0.1" min="0" max="10" name="cartoondb_rating" id="cartoondb_rating" value="<?php echo esc_attr($rating); ?>" class="widefat" />
    </p>

    <div class="cartoondb-metabox-section">
        <h4><?php esc_html_e('Cast & Crew', 'cartoondb'); ?></h4>
        <input type="hidden" id="cartoondb_cast_json" name="cartoondb_cast_json" value="<?php echo esc_attr($cast_json); ?>" />
        <div id="cartoondb_cast_list">
            <?php foreach ($cast_items as $index => $item) : ?>
                <div class="cartoondb-cast-item">
                    <div class="cartoondb-cast-fields">
                        <input type="text" class="cartoondb-cast-name" placeholder="<?php esc_attr_e('Name', 'cartoondb'); ?>" value="<?php echo esc_attr($item['name'] ?? ''); ?>" />
                        <input type="text" class="cartoondb-cast-role" placeholder="<?php esc_attr_e('Role', 'cartoondb'); ?>" value="<?php echo esc_attr($item['role'] ?? ''); ?>" />
                        <input type="hidden" class="cartoondb-cast-image-id" value="<?php echo esc_attr($item['image_id'] ?? ''); ?>" />
                    </div>
                    <div class="cartoondb-cast-media">
                        <?php
                        $image_url = '';
                        if (!empty($item['image_id'])) {
                            $image_url = wp_get_attachment_image_url((int) $item['image_id'], 'thumbnail');
                        }
                        ?>
                        <img class="cartoondb-cast-preview" src="<?php echo esc_url($image_url); ?>" alt="" style="<?php echo $image_url ? '' : 'display:none;'; ?>" />
                        <button type="button" class="button cartoondb-cast-upload"><?php esc_html_e('Add Photo', 'cartoondb'); ?></button>
                        <button type="button" class="button-link cartoondb-cast-remove"><?php esc_html_e('Remove', 'cartoondb'); ?></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button" id="cartoondb_cast_add"><?php esc_html_e('Add Cast Member', 'cartoondb'); ?></button>
    </div>

    <p>
        <label for="cartoondb_gallery_ids"><strong><?php esc_html_e('Gallery Images', 'cartoondb'); ?></strong></label>
        <input type="text" name="cartoondb_gallery_ids" id="cartoondb_gallery_ids" value="<?php echo esc_attr($gallery_ids); ?>" class="widefat" />
        <button type="button" class="button" id="cartoondb_gallery_upload"><?php esc_html_e('Select Gallery Images', 'cartoondb'); ?></button>
        <small><?php esc_html_e('Selected image IDs will appear above.', 'cartoondb'); ?></small>
    </p>

    <script type="text/template" id="cartoondb_cast_template">
        <div class="cartoondb-cast-item">
            <div class="cartoondb-cast-fields">
                <input type="text" class="cartoondb-cast-name" placeholder="<?php esc_attr_e('Name', 'cartoondb'); ?>" />
                <input type="text" class="cartoondb-cast-role" placeholder="<?php esc_attr_e('Role', 'cartoondb'); ?>" />
                <input type="hidden" class="cartoondb-cast-image-id" />
            </div>
            <div class="cartoondb-cast-media">
                <img class="cartoondb-cast-preview" src="" alt="" style="display:none;" />
                <button type="button" class="button cartoondb-cast-upload"><?php esc_html_e('Add Photo', 'cartoondb'); ?></button>
                <button type="button" class="button-link cartoondb-cast-remove"><?php esc_html_e('Remove', 'cartoondb'); ?></button>
            </div>
        </div>
    </script>
    <?php
}

function cartoondb_save_movie_meta($post_id) {
    if (!isset($_POST['cartoondb_movie_meta_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['cartoondb_movie_meta_nonce'], 'cartoondb_save_movie_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = [
        'cartoondb_trailer_url' => 'esc_url_raw',
        'cartoondb_trailer_video_id' => 'absint',
        'cartoondb_cast_json' => 'wp_kses_post',
        'cartoondb_rating' => 'floatval',
        'cartoondb_release_year' => 'sanitize_text_field',
        'cartoondb_runtime' => 'sanitize_text_field',
        'cartoondb_gallery_ids' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitizer) {
        if (isset($_POST[$field])) {
            $value = call_user_func($sanitizer, wp_unslash($_POST[$field]));
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post_movie', 'cartoondb_save_movie_meta');

function cartoondb_get_gallery_images($post_id) {
    $ids = get_post_meta($post_id, 'cartoondb_gallery_ids', true);
    if (!$ids) {
        return [];
    }

    $ids_array = array_filter(array_map('absint', explode(',', $ids)));
    if (!$ids_array) {
        return [];
    }

    return get_posts([
        'post_type' => 'attachment',
        'post__in' => $ids_array,
    ]);
}

function cartoondb_get_cast_members($post_id) {
    $cast_json = get_post_meta($post_id, 'cartoondb_cast_json', true);
    if (!$cast_json) {
        return [];
    }

    $decoded = json_decode($cast_json, true);
    if (!is_array($decoded)) {
        return [];
    }

    return array_filter($decoded, function ($member) {
        return !empty($member['name']) || !empty($member['role']) || !empty($member['image_id']);
    });
}
