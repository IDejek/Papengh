<?php
/**
 * Theme Setup
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

class DPW_PSI_Theme_Setup {

    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
        add_filter( 'script_loader_tag', array( $this, 'defer_scripts' ), 10, 3 );
    }

    public function setup() {
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );

        add_image_size( 'dpw-hero', 1920, 900, true );
        add_image_size( 'dpw-card', 600, 400, true );
        add_image_size( 'dpw-leader', 400, 500, true );
        add_image_size( 'dpw-gallery', 600, 600, true );

        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ) );

        add_theme_support( 'custom-logo', array(
            'height'      => 80,
            'width'       => 80,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

        add_theme_support( 'custom-background' );

        register_nav_menus( array(
            'primary' => esc_html__( 'Primary Menu', 'dpw-psi-papeng' ),
            'footer'  => esc_html__( 'Footer Menu', 'dpw-psi-papeng' ),
        ) );

        if ( ! isset( $content_width ) ) {
            $content_width = 1320;
        }
    }

    public function register_sidebars() {
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Column 1', 'dpw-psi-papeng' ),
            'id'            => 'footer-1',
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Column 2', 'dpw-psi-papeng' ),
            'id'            => 'footer-2',
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        ) );
        register_sidebar( array(
            'name'          => esc_html__( 'Footer Column 3', 'dpw-psi-papeng' ),
            'id'            => 'footer-3',
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        ) );
    }

    public function defer_scripts( $tag, $handle, $src ) {
        $defer_handles = array( 'dpw-psi-main', 'dpw-psi-aos', 'dpw-psi-swiper' );
        if ( in_array( $handle, $defer_handles, true ) ) {
            return str_replace( ' src', ' defer src', $tag );
        }
        return $tag;
    }
}
