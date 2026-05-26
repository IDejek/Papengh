<?php
/**
 * Latest News Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $news = new WP_Query( array(
    'post_type'      => 'psi_news',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
) );

if ( ! $news->have_posts() ) {
    return;
}
?>

<section class="section news-section" data-aos="fade-up">
    <div class="container">
        <div class="section-header" style="text-align:center; margin-bottom:48px;">
            <span class="section-label" style="justify-content:center;"><?php esc_html_e( 'Berita Terkini', 'dpw-psi-papeng' ); ?></span>
            <h2 class="heading-section"><?php esc_html_e( 'Informasi & Berita Terbaru', 'dpw-psi-papeng' ); ?></h2>
        </div>

        <div class="news-grid" id="news-grid">
            <?php while ( $news->have_posts() ) : $news->the_post(); ?>
                <?php get_template_part( 'template-parts/content', 'archive' ); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php
        $total = wp_count_posts( 'psi_news' )->publish;
        if ( $total > 6 ) :
        ?>
            <div style="text-align:center; margin-top:48px;">
                <a href="<?php echo esc_url( home_url( '/berita/' ) ); ?>" class="btn btn-outline">
                    <?php esc_html_e( 'Lihat Semua Berita', 'dpw-psi-papeng' ); ?> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
