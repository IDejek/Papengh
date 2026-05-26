<?php
/**
 * Footer Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $footer_desc  = dpw_psi_get( 'footer_desc', 'Dewan Pimpinan Wilayah Partai Solidaritas Indonesia Provinsi Papua Pegunungan.' );
 $copyright    = dpw_psi_get( 'footer_copyright', '' );
 $address      = dpw_psi_get( 'address', 'Papua Pegunungan, Indonesia' );
 $email        = dpw_psi_get( 'email', 'info@psipapeng.id' );
 $phone        = dpw_psi_get( 'phone', '' );
 $current_year = gmdate( 'Y' );
 $wa_link      = dpw_psi_get( 'wa_link', 'https://wa.me/6282267218125' );
 $wa_num       = dpw_psi_get( 'wa_number', '+62 822 6721 8125' );

 $social_icons = array(
    'facebook'  => 'fab fa-facebook-f',
    'instagram' => 'fab fa-instagram',
    'twitter'   => 'fab fa-x-twitter',
    'youtube'   => 'fab fa-youtube',
    'tiktok'    => 'fab fa-tiktok',
);
?>

<footer class="footer" id="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col footer-col-about">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <div class="logo-mark" style="background:var(--accent);width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-family:var(--font-display);font-size:20px;font-weight:700;">P</div>
                            <div class="logo-text">
                                <span class="logo-text-main" style="color:#fff;">PSI</span>
                                <span class="logo-text-sub" style="color:rgba(255,255,255,0.5);">Papua Pegunungan</span>
                            </div>
                        <?php endif; ?>
                    </a>
                    <p class="footer-about-text"><?php echo wp_kses_post( $footer_desc ); ?></p>
                    <div class="footer-social">
                        <?php
                        foreach ( $social_icons as $key => $icon ) {
                            $url = dpw_psi_get( $key, '#' );
                            if ( '#' !== $url ) {
                                echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( ucfirst( $key ) ) . '"><i class="' . esc_attr( $icon ) . '"></i></a>';
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="footer-col">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    <?php else : ?>
                        <h4 class="footer-widget-title"><?php esc_html_e( 'Tautan Cepat', 'dpw-psi-papeng' ); ?></h4>
                        <?php
                        if ( has_nav_menu( 'footer' ) ) {
                            wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'container'      => false,
                                'items_wrap'     => '<ul class="footer-links">%3$s</ul>',
                                'depth'          => 1,
                            ) );
                        } else {
                            $quick_links = array(
                                array( 'label' => 'Beranda', 'url' => home_url( '/' ) ),
                                array( 'label' => 'Struktur Organisasi', 'url' => home_url( '/struktur/' ) ),
                                array( 'label' => 'Berita', 'url' => home_url( '/berita/' ) ),
                                array( 'label' => 'Video', 'url' => home_url( '/video/' ) ),
                                array( 'label' => 'Galeri', 'url' => home_url( '/galeri/' ) ),
                            );
                            echo '<ul class="footer-links">';
                            foreach ( $quick_links as $link ) {
                                echo '<li><a href="' . esc_url( $link['url'] ) . '">' . esc_html( $link['label'] ) . '</a></li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                    <?php endif; ?>
                </div>

                <div class="footer-col">
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    <?php else : ?>
                        <h4 class="footer-widget-title"><?php esc_html_e( 'Kontak', 'dpw-psi-papeng' ); ?></h4>
                        <ul class="footer-contact">
                            <?php if ( $address ) : ?>
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo esc_html( $address ); ?></span>
                                </li>
                            <?php endif; ?>
                            <?php if ( $email ) : ?>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if ( $phone ) : ?>
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <p class="footer-copyright">
                    <?php if ( $copyright ) : ?>
                        <?php echo esc_html( $copyright ); ?>
                    <?php else : ?>
                        &copy; <?php echo esc_html( $current_year ); ?> DPW PSI Papua Pegunungan. All rights reserved.
                    <?php endif; ?>
                </p>
                <p class="footer-credit">Developed by <strong>Iqbal Tombinawa</strong></p>
            </div>
        </div>
    </div>
</footer>

<a href="<?php echo esc_url( $wa_link ); ?>" class="floating-wa" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp <?php echo esc_attr( $wa_num ); ?>">
    <i class="fab fa-whatsapp"></i>
</a>

<?php wp_footer(); ?>
</body>
</html>
