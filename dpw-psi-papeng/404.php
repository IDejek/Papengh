<?php
/**
 * 404 Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main">
    <div class="container" style="padding:120px 24px; text-align:center;">
        <h1 class="heading-display" style="font-size:clamp(4rem,10vw,8rem); color:var(--border); margin-bottom:16px;">404</h1>
        <h2 class="heading-section" style="margin-bottom:24px;">Halaman Tidak Ditemukan</h2>
        <p style="color:var(--text-secondary); margin-bottom:40px; max-width:500px; margin-left:auto; margin-right:auto;">
            Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.
        </p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:8px; padding:14px 32px; background:var(--accent); color:#fff; border-radius:var(--radius-sm); font-weight:700; font-size:0.95rem; transition:var(--transition);">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>
</main>

<?php get_footer(); ?>
