<?php
/**
 * Custom Post Types & Taxonomies
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

class DPW_PSI_Custom_Post_Types {

    public function __construct() {
        add_action( 'init', array( $this, 'register_post_types' ), 10 );
        add_action( 'init', array( $this, 'register_taxonomies' ), 20 );
    }

    public function register_post_types() {

        register_post_type( 'psi_news', array(
            'labels'       => array(
                'name'               => __( 'Berita', 'dpw-psi-papeng' ),
                'singular_name'      => __( 'Berita', 'dpw-psi-papeng' ),
                'add_new_item'       => __( 'Tambah Berita Baru', 'dpw-psi-papeng' ),
                'edit_item'          => __( 'Edit Berita', 'dpw-psi-papeng' ),
                'all_items'          => __( 'Semua Berita', 'dpw-psi-papeng' ),
                'search_items'       => __( 'Cari Berita', 'dpw-psi-papeng' ),
                'not_found'          => __( 'Tidak ada berita ditemukan', 'dpw-psi-papeng' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'rewrite'      => array( 'slug' => 'berita', 'with_front' => false ),
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
            'menu_icon'    => 'dashicons-media-document',
            'show_in_rest' => true,
        ) );

        register_post_type( 'psi_video', array(
            'labels'       => array(
                'name'               => __( 'Video', 'dpw-psi-papeng' ),
                'singular_name'      => __( 'Video', 'dpw-psi-papeng' ),
                'add_new_item'       => __( 'Tambah Video Baru', 'dpw-psi-papeng' ),
                'edit_item'          => __( 'Edit Video', 'dpw-psi-papeng' ),
                'all_items'          => __( 'Semua Video', 'dpw-psi-papeng' ),
                'search_items'       => __( 'Cari Video', 'dpw-psi-papeng' ),
                'not_found'          => __( 'Tidak ada video ditemukan', 'dpw-psi-papeng' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'rewrite'      => array( 'slug' => 'video', 'with_front' => false ),
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'menu_icon'    => 'dashicons-video-alt3',
            'show_in_rest' => true,
        ) );

        register_post_type( 'psi_gallery', array(
            'labels'       => array(
                'name'               => __( 'Galeri', 'dpw-psi-papeng' ),
                'singular_name'      => __( 'Galeri', 'dpw-psi-papeng' ),
                'add_new_item'       => __( 'Tambah Galeri Baru', 'dpw-psi-papeng' ),
                'edit_item'          => __( 'Edit Galeri', 'dpw-psi-papeng' ),
                'all_items'          => __( 'Semua Galeri', 'dpw-psi-papeng' ),
                'search_items'       => __( 'Cari Galeri', 'dpw-psi-papeng' ),
                'not_found'          => __( 'Tidak ada galeri ditemukan', 'dpw-psi-papeng' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'rewrite'      => array( 'slug' => 'galeri', 'with_front' => false ),
            'supports'     => array( 'title', 'editor', 'thumbnail' ),
            'menu_icon'    => 'dashicons-format-gallery',
            'show_in_rest' => true,
        ) );

        register_post_type( 'psi_structure', array(
            'labels'       => array(
                'name'               => __( 'Struktur Organisasi', 'dpw-psi-papeng' ),
                'singular_name'      => __( 'Anggota Struktur', 'dpw-psi-papeng' ),
                'add_new_item'       => __( 'Tambah Anggota Struktur', 'dpw-psi-papeng' ),
                'edit_item'          => __( 'Edit Anggota Struktur', 'dpw-psi-papeng' ),
                'all_items'          => __( 'Semua Anggota', 'dpw-psi-papeng' ),
                'search_items'       => __( 'Cari Anggota', 'dpw-psi-papeng' ),
                'not_found'          => __( 'Tidak ada data ditemukan', 'dpw-psi-papeng' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'rewrite'      => array( 'slug' => 'struktur', 'with_front' => false ),
            'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'menu_icon'    => 'dashicons-groups',
            'show_in_rest' => true,
        ) );

        register_post_type( 'psi_dpd', array(
            'labels'       => array(
                'name'               => __( 'DPD Wilayah', 'dpw-psi-papeng' ),
                'singular_name'      => __( 'DPD Wilayah', 'dpw-psi-papeng' ),
                'add_new_item'       => __( 'Tambah DPD Baru', 'dpw-psi-papeng' ),
                'edit_item'          => __( 'Edit DPD', 'dpw-psi-papeng' ),
                'all_items'          => __( 'Semua DPD', 'dpw-psi-papeng' ),
                'search_items'       => __( 'Cari DPD', 'dpw-psi-papeng' ),
                'not_found'          => __( 'Tidak ada DPD ditemukan', 'dpw-psi-papeng' ),
            ),
            'public'       => true,
            'has_archive'  => true,
            'rewrite'      => array( 'slug' => 'dpd', 'with_front' => false ),
            'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
            'menu_icon'    => 'dashicons-location-alt',
            'show_in_rest' => true,
        ) );

        register_post_type( 'psi_slide', array(
            'labels'       => array(
                'name'               => __( 'Hero Slides', 'dpw-psi-papeng' ),
                'singular_name'      => __( 'Slide', 'dpw-psi-papeng' ),
                'add_new_item'       => __( 'Tambah Slide Baru', 'dpw-psi-papeng' ),
                'edit_item'          => __( 'Edit Slide', 'dpw-psi-papeng' ),
                'all_items'          => __( 'Semua Slide', 'dpw-psi-papeng' ),
            ),
            'public'       => false,
            'show_ui'      => true,
            'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'menu_icon'    => 'dashicons-slides',
            'show_in_rest' => true,
        ) );
    }

    public function register_taxonomies() {

        register_taxonomy( 'news_category', 'psi_news', array(
            'labels'       => array(
                'name'          => __( 'Kategori Berita', 'dpw-psi-papeng' ),
                'singular_name' => __( 'Kategori', 'dpw-psi-papeng' ),
            ),
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => array( 'slug' => 'kategori-berita', 'with_front' => false ),
            'show_in_rest' => true,
        ) );

        register_taxonomy( 'news_tag', 'psi_news', array(
            'labels'       => array(
                'name'          => __( 'Tag Berita', 'dpw-psi-papeng' ),
                'singular_name' => __( 'Tag', 'dpw-psi-papeng' ),
            ),
            'hierarchical' => false,
            'public'       => true,
            'rewrite'      => array( 'slug' => 'tag-berita', 'with_front' => false ),
            'show_in_rest' => true,
        ) );

        register_taxonomy( 'video_category', 'psi_video', array(
            'labels'       => array(
                'name'          => __( 'Kategori Video', 'dpw-psi-papeng' ),
                'singular_name' => __( 'Kategori', 'dpw-psi-papeng' ),
            ),
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => array( 'slug' => 'kategori-video', 'with_front' => false ),
            'show_in_rest' => true,
        ) );

        register_taxonomy( 'gallery_category', 'psi_gallery', array(
            'labels'       => array(
                'name'          => __( 'Kategori Galeri', 'dpw-psi-papeng' ),
                'singular_name' => __( 'Kategori', 'dpw-psi-papeng' ),
            ),
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => array( 'slug' => 'kategori-galeri', 'with_front' => false ),
            'show_in_rest' => true,
        ) );

        register_taxonomy( 'structure_position', 'psi_structure', array(
            'labels'       => array(
                'name'          => __( 'Jabatan', 'dpw-psi-papeng' ),
                'singular_name' => __( 'Jabatan', 'dpw-psi-papeng' ),
            ),
            'hierarchical' => true,
            'public'       => true,
            'rewrite'      => array( 'slug' => 'jabatan', 'with_front' => false ),
            'show_in_rest' => true,
        ) );
    }
}
