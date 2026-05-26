<?php
/**
 * CTA Registration Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $reg_link = dpw_psi_get( 'reg_link_cta', 'https://psi.id/daftar-anggota' );
?>

<section class="section cta-section" data-aos="fade-up">
    <div class="container">
        <div class="cta-inner">
            <div class="cta-content">
                <span class="section-label"><?php esc_html_e( 'Bergabunglah', 'dpw-psi-papeng' ); ?></span>
                <h2 class="heading-section" style="color:#fff;"><?php esc_html_e( 'Jadilah Bagian dari Perubahan', 'dpw-psi-papeng' ); ?></h2>
                <p class="cta-desc"><?php esc_html_e( 'Bergabunglah dengan Partai Solidaritas Indonesia dan wujudkan Papua Pegunungan yang lebih baik bersama kami.', 'dpw-psi-papeng' ); ?></p>
                <a href="<?php echo esc_url( $reg_link ); ?>" class="btn btn-cta" target="_blank" rel="noopener">
                    <?php esc_html_e( 'Daftar Sebagai Anggota PSI', 'dpw-psi-papeng' ); ?> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>
