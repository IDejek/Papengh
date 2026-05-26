<?php
/**
 * No Content Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="no-results" style="text-align:center; padding:80px 24px;">
    <h2 class="heading-section"><?php esc_html_e( 'Tidak Ada Konten', 'dpw-psi-papeng' ); ?></h2>
    <p style="color:var(--text-secondary); margin-top:12px;"><?php esc_html_e( 'Maaf, belum ada konten yang tersedia saat ini.', 'dpw-psi-papeng' ); ?></p>
    <?php get_search_form(); ?>
</section>
