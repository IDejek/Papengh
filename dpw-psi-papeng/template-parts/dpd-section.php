<?php
/**
 * DPD Section Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $dpd = new WP_Query( array(
    'post_type'      => 'psi_dpd',
    'posts_per_page' => 6,
    'orderby'        => 'title',
    'order'          => 'ASC',
) );

if ( ! $dpd->have_posts() ) {
    return;
}
?>

<section class="section dpd-section" data-aos="fade-up">
    <div class="container">
        <div class="section-header" style="text-align:center; margin-bottom:48px;">
            <span class="section-label" style="justify-content:center;"><?php esc_html_e( 'DPD Wilayah', 'dpw-psi-papeng' ); ?></span>
            <h2 class="heading-section"><?php esc_html_e( 'DPD PSI se-Papua Pegunungan', 'dpw-psi-papeng' ); ?></h2>
            <p class="section-desc" style="margin:0 auto;"><?php esc_html_e( 'Kehadiran PSI di seluruh wilayah Papua Pegunungan melalui Dewan Pimpinan Daerah.', 'dpw-psi-papeng' ); ?></p>
        </div>

        <div class="dpd-grid">
            <?php while ( $dpd->have_posts() ) : $dpd->the_post(); ?>
                <div class="dpd-card" data-aos="fade-up">
                    <div class="dpd-card-img">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'dpw-card' ); ?>
                        <?php else : ?>
                            <div class="dpd-card-placeholder"><i class="fas fa-map-marker-alt"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="dpd-card-body">
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                        <a href="<?php the_permalink(); ?>" class="dpd-card-link"><?php esc_html_e( 'Selengkapnya', 'dpw-psi-papeng' ); ?> <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <div style="text-align:center; margin-top:48px;">
            <a href="<?php echo esc_url( home_url( '/dpd/' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'Lihat Seluruh DPD', 'dpw-psi-papeng' ); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
