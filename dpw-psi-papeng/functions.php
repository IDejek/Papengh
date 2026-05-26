<?php
/**
 * DPW PSI Papua Pegunungan Theme Functions
 *
 * @package DPW_PSI_Papeng
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

define( 'DPW_PSI_VERSION', '1.0.0' );
define( 'DPW_PSI_DIR', get_template_directory() );
define( 'DPW_PSI_URI', get_template_directory_uri() );

require_once DPW_PSI_DIR . '/inc/class-asset-loader.php';
require_once DPW_PSI_DIR . '/inc/class-theme-setup.php';
require_once DPW_PSI_DIR . '/inc/class-custom-post-types.php';
require_once DPW_PSI_DIR . '/inc/class-customizer.php';
require_once DPW_PSI_DIR . '/inc/class-seo.php';

new DPW_PSI_Asset_Loader();
new DPW_PSI_Theme_Setup();
new DPW_PSI_Custom_Post_Types();
new DPW_PSI_Customizer();
new DPW_PSI_SEO();

/* ── AJAX: Load More News ── */
add_action( 'wp_ajax_dpw_psi_load_more_news', 'dpw_psi_load_more_news_cb' );
add_action( 'wp_ajax_nopriv_dpw_psi_load_more_news', 'dpw_psi_load_more_news_cb' );
function dpw_psi_load_more_news_cb() {
    check_ajax_referer( 'dpw_psi_nonce', 'nonce' );
    $page     = absint( $_POST['page'] ?? 1 );
    $per_page = absint( get_option( 'posts_per_page', 6 ) );
    $args     = array(
        'post_type'      => 'psi_news',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
    );
    $q = new WP_Query( $args );
    if ( ! $q->have_posts() ) {
        wp_send_json_success( array( 'html' => '', 'found' => false ) );
    }
    ob_start();
    while ( $q->have_posts() ) {
        $q->the_post();
        get_template_part( 'template-parts/content', 'archive' );
    }
    wp_reset_postdata();
    wp_send_json_success( array(
        'html'  => ob_get_clean(),
        'found' => ( $page * $per_page ) < $q->found_posts,
    ) );
}

/* ── Helper ── */
function dpw_psi_get( $key, $default = '' ) {
    return get_theme_mod( $key, $default );
}

/* ── Excerpt ── */
add_filter( 'excerpt_length', function () { return 20; } );
add_filter( 'excerpt_more', function () { return '...'; } );
