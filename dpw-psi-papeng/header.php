<?php
/**
 * Header Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="preloader" id="preloader">
    <div class="preloader-logo">P</div>
    <div class="preloader-bar"></div>
</div>

<div class="topbar">
    <div class="topbar-inner">
        <div class="topbar-clocks">
            <div class="topbar-clock">
                <span class="topbar-clock-label">Jayapura</span>
                <div class="topbar-clock-dot"></div>
                <span class="topbar-clock-time" id="clock-papua">--:--:--</span>
            </div>
            <div class="topbar-clock">
                <span class="topbar-clock-label">Jakarta</span>
                <div class="topbar-clock-dot"></div>
                <span class="topbar-clock-time" id="clock-jakarta">--:--:--</span>
            </div>
        </div>
        <div class="topbar-social">
            <?php
            $social_icons = array(
                'facebook'  => 'fab fa-facebook-f',
                'instagram' => 'fab fa-instagram',
                'twitter'   => 'fab fa-x-twitter',
                'youtube'   => 'fab fa-youtube',
                'tiktok'    => 'fab fa-tiktok',
            );
            foreach ( $social_icons as $key => $icon ) {
                $url = dpw_psi_get( $key, '#' );
                if ( '#' !== $url ) {
                    echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( ucfirst( $key ) ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
                }
            }
            ?>
        </div>
    </div>
</div>

<header class="header" id="site-header">
    <div class="header-inner">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <div class="logo-mark">P</div>
                <div class="logo-text">
                    <span class="logo-text-main">PSI</span>
                    <span class="logo-text-sub">Papua Pegunungan</span>
                </div>
            <?php endif; ?>
        </a>

        <nav class="nav" id="main-nav" aria-label="<?php esc_attr_e( 'Navigasi Utama', 'dpw-psi-papeng' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'fallback_cb'    => 'dpw_psi_fallback_menu',
                'depth'          => 1,
            ) );
            $reg_link = dpw_psi_get( 'reg_link_menu', 'https://psi.id/menjadi-anggota/' );
            ?>
            <a href="<?php echo esc_url( $reg_link ); ?>" class="nav-link nav-cta" target="_blank" rel="noopener"><?php esc_html_e( 'Daftar Anggota', 'dpw-psi-papeng' ); ?></a>
        </nav>

        <button class="hamburger" id="hamburger" aria-label="<?php esc_attr_e( 'Buka Menu', 'dpw-psi-papeng' ); ?>" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>

<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'items_wrap'     => '%3$s',
        'fallback_cb'    => 'dpw_psi_fallback_menu',
        'depth'          => 1,
    ) );
    ?>
    <a href="<?php echo esc_url( $reg_link ); ?>" class="mobile-menu-cta" target="_blank" rel="noopener"><?php esc_html_e( 'Daftar Anggota', 'dpw-psi-papeng' ); ?></a>
</div>

<?php
function dpw_psi_fallback_menu() {
    $items = array(
        array( 'label' => 'Beranda', 'url' => home_url( '/' ) ),
        array( 'label' => 'Struktur', 'url' => home_url( '/struktur/' ) ),
        array( 'label' => 'Berita', 'url' => home_url( '/berita/' ) ),
        array( 'label' => 'Video', 'url' => home_url( '/video/' ) ),
        array( 'label' => 'Galeri', 'url' => home_url( '/galeri/' ) ),
    );
    foreach ( $items as $item ) {
        echo '<a href="' . esc_url( $item['url'] ) . '" class="nav-link">' . esc_html( $item['label'] ) . '</a>';
    }
}
?>
