<?php
/**
 * Customizer Settings
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

class DPW_PSI_Customizer {

    public function __construct() {
        add_action( 'customize_register', array( $this, 'register_sections' ) );
    }

    public function register_sections( $wp_customize ) {

        $wp_customize->add_panel( 'dpw_psi_panel', array(
            'title'    => __( 'DPW PSI Settings', 'dpw-psi-papeng' ),
            'priority' => 30,
        ) );

        /* Hero */
        $wp_customize->add_section( 'dpw_hero_section', array(
            'title' => __( 'Hero Slider', 'dpw-psi-papeng' ),
            'panel' => 'dpw_psi_panel',
        ) );

        $wp_customize->add_setting( 'hero_enabled', array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ) );
        $wp_customize->add_control( 'hero_enabled', array(
            'label'   => __( 'Aktifkan Hero Slider', 'dpw-psi-papeng' ),
            'section' => 'dpw_hero_section',
            'type'    => 'checkbox',
        ) );

        $wp_customize->add_setting( 'hero_count', array(
            'default'           => 5,
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control( 'hero_count', array(
            'label'       => __( 'Jumlah Slide', 'dpw-psi-papeng' ),
            'section'     => 'dpw_hero_section',
            'type'        => 'number',
            'input_attrs' => array( 'min' => 1, 'max' => 20 ),
        ) );

        /* Chairman */
        $wp_customize->add_section( 'dpw_chairman_section', array(
            'title' => __( 'Ketua DPW', 'dpw-psi-papeng' ),
            'panel' => 'dpw_psi_panel',
        ) );

        $wp_customize->add_setting( 'chairman_name', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'chairman_name', array(
            'label'   => __( 'Nama Ketua', 'dpw-psi-papeng' ),
            'section' => 'dpw_chairman_section',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'chairman_title', array(
            'default'           => 'Ketua DPW PSI Papua Pegunungan',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'chairman_title', array(
            'label'   => __( 'Jabatan', 'dpw-psi-papeng' ),
            'section' => 'dpw_chairman_section',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'chairman_message', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        ) );
        $wp_customize->add_control( 'chairman_message', array(
            'label'       => __( 'Pesan Sambutan', 'dpw-psi-papeng' ),
            'section'     => 'dpw_chairman_section',
            'type'        => 'textarea',
            'textarea_rows' => 6,
        ) );

        /* Statistics */
        $wp_customize->add_section( 'dpw_stats_section', array(
            'title' => __( 'Statistik', 'dpw-psi-papeng' ),
            'panel' => 'dpw_psi_panel',
        ) );

        $stat_items = array(
            'stat_members'    => __( 'Total Anggota', 'dpw-psi-papeng' ),
            'stat_dpd'        => __( 'Total DPD', 'dpw-psi-papeng' ),
            'stat_activities' => __( 'Total Kegiatan', 'dpw-psi-papeng' ),
            'stat_cadres'     => __( 'Total Kader', 'dpw-psi-papeng' ),
        );

        foreach ( $stat_items as $key => $label ) {
            $wp_customize->add_setting( $key, array(
                'default'           => '0',
                'sanitize_callback' => 'sanitize_text_field',
            ) );
            $wp_customize->add_control( $key, array(
                'label'   => $label,
                'section' => 'dpw_stats_section',
                'type'    => 'text',
            ) );
        }

        /* Contact & Social */
        $wp_customize->add_section( 'dpw_contact_section', array(
            'title' => __( 'Kontak & Sosial Media', 'dpw-psi-papeng' ),
            'panel' => 'dpw_psi_panel',
        ) );

        $contact_fields = array(
            'wa_number' => array( 'label' => 'Nomor WhatsApp', 'default' => '+62 822 6721 8125', 'type' => 'text' ),
            'wa_link'   => array( 'label' => 'WhatsApp Link', 'default' => 'https://wa.me/6282267218125', 'type' => 'url' ),
            'email'     => array( 'label' => 'Email', 'default' => 'info@psipapeng.id', 'type' => 'text' ),
            'phone'     => array( 'label' => 'Telepon', 'default' => '', 'type' => 'text' ),
            'address'   => array( 'label' => 'Alamat', 'default' => 'Papua Pegunungan, Indonesia', 'type' => 'textarea' ),
            'facebook'  => array( 'label' => 'Facebook URL', 'default' => '#', 'type' => 'url' ),
            'instagram' => array( 'label' => 'Instagram URL', 'default' => '#', 'type' => 'url' ),
            'twitter'   => array( 'label' => 'Twitter/X URL', 'default' => '#', 'type' => 'url' ),
            'youtube'   => array( 'label' => 'YouTube URL', 'default' => '#', 'type' => 'url' ),
            'tiktok'    => array( 'label' => 'TikTok URL', 'default' => '#', 'type' => 'url' ),
        );

        foreach ( $contact_fields as $key => $field ) {
            $sanitize = ( 'url' === $field['type'] ) ? 'esc_url_raw' : 'sanitize_text_field';
            if ( 'textarea' === $field['type'] ) {
                $sanitize = 'wp_kses_post';
            }
            $wp_customize->add_setting( $key, array(
                'default'           => $field['default'],
                'sanitize_callback' => $sanitize,
            ) );
            $ctrl = array(
                'label'   => $field['label'],
                'section' => 'dpw_contact_section',
                'type'    => $field['type'],
            );
            if ( 'textarea' === $field['type'] ) {
                $ctrl['textarea_rows'] = 3;
            }
            $wp_customize->add_control( $key, $ctrl );
        }

        /* Registration Links */
        $wp_customize->add_section( 'dpw_reg_section', array(
            'title' => __( 'Link Pendaftaran', 'dpw-psi-papeng' ),
            'panel' => 'dpw_psi_panel',
        ) );

        $wp_customize->add_setting( 'reg_link_menu', array(
            'default'           => 'https://psi.id/menjadi-anggota/',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'reg_link_menu', array(
            'label'   => __( 'Link "Daftar Anggota" Menu', 'dpw-psi-papeng' ),
            'section' => 'dpw_reg_section',
            'type'    => 'url',
        ) );

        $wp_customize->add_setting( 'reg_link_cta', array(
            'default'           => 'https://psi.id/daftar-anggota',
            'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'reg_link_cta', array(
            'label'   => __( 'Link CTA Pendaftaran', 'dpw-psi-papeng' ),
            'section' => 'dpw_reg_section',
            'type'    => 'url',
        ) );

        /* Footer */
        $wp_customize->add_section( 'dpw_footer_section', array(
            'title' => __( 'Footer', 'dpw-psi-papeng' ),
            'panel' => 'dpw_psi_panel',
        ) );

        $wp_customize->add_setting( 'footer_desc', array(
            'default'           => 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Provinsi Papua Pegunungan.',
            'sanitize_callback' => 'wp_kses_post',
        ) );
        $wp_customize->add_control( 'footer_desc', array(
            'label'         => __( 'Deskripsi Organisasi', 'dpw-psi-papeng' ),
            'section'       => 'dpw_footer_section',
            'type'          => 'textarea',
            'textarea_rows' => 4,
        ) );

        $wp_customize->add_setting( 'footer_copyright', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'footer_copyright', array(
            'label'   => __( 'Teks Copyright (kosongkan = otomatis)', 'dpw-psi-papeng' ),
            'section' => 'dpw_footer_section',
            'type'    => 'text',
        ) );
    }
}
