<?php
/**
 * Asset Loader
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

class DPW_PSI_Asset_Loader {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
        add_action( 'wp_head', array( $this, 'inline_critical_css' ), 1 );
    }

    public function register_assets() {

        wp_enqueue_style(
            'dpw-psi-fonts',
            'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap',
            array(),
            null
        );

        wp_enqueue_style(
            'dpw-psi-fa',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
            array(),
            '6.5.1'
        );

        wp_enqueue_style(
            'dpw-psi-aos',
            'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css',
            array(),
            '2.3.4'
        );

        wp_enqueue_script(
            'dpw-psi-aos',
            'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
            array(),
            '2.3.4',
            true
        );

        wp_enqueue_style(
            'dpw-psi-swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0.0'
        );

        wp_enqueue_script(
            'dpw-psi-swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0.0',
            true
        );

        wp_enqueue_style(
            'dpw-psi-style',
            DPW_PSI_URI . '/assets/css/main.css',
            array( 'dpw-psi-fonts', 'dpw-psi-fa', 'dpw-psi-aos', 'dpw-psi-swiper' ),
            DPW_PSI_VERSION
        );

        wp_enqueue_script(
            'dpw-psi-main',
            DPW_PSI_URI . '/assets/js/main.js',
            array( 'dpw-psi-swiper', 'dpw-psi-aos' ),
            DPW_PSI_VERSION,
            true
        );

        wp_localize_script( 'dpw-psi-main', 'dpwPsi', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'dpw_psi_nonce' ),
        ) );

        $tz_papua  = 'Asia/Jayapura';
        $tz_jakarta = 'Asia/Jakarta';
        $js_tz = 'window.DPW_TZ_PAPUA="' . esc_js( $tz_papua ) . '";window.DPW_TZ_JAKARTA="' . esc_js( $tz_jakarta ) . '";';
        wp_add_inline_script( 'dpw-psi-main', $js_tz, 'before' );
    }

    public function inline_critical_css() {
        echo '<style id="dpw-critical-css">';
        echo ':root{--primary:#0b1a30;--accent:#1d4ed8;--bg:#fff;--font-display:"Cormorant Garamond",Georgia,serif;--font-body:"Plus Jakarta Sans",sans-serif}';
        echo '*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}';
        echo 'html{scroll-behavior:smooth}';
        echo 'body{font-family:var(--font-body);color:var(--primary);background:var(--bg);line-height:1.7;-webkit-font-smoothing:antialiased;overflow-x:hidden}';
        echo 'img{max-width:100%;height:auto;display:block}';
        echo 'a{text-decoration:none;color:inherit;transition:color .2s}';
        echo 'ul,ol{list-style:none}';
        echo '.preloader{position:fixed;inset:0;z-index:99999;background:#fff;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:20px;transition:opacity .5s,visibility .5s}';
        echo '.preloader.hidden{opacity:0;visibility:hidden;pointer-events:none}';
        echo '</style>' . "\n";
    }
}
