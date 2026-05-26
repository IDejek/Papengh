<?php
/**
 * Gallery Section Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $galleries = new WP_Query( array(
    'post_type'      => 'psi_gallery',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
) );

if ( ! $galleries->have_posts() ) {
    return;
}
?>

<section class="section gallery-section" data-aos="fade-up">
    <div class="container">
        <div class="section-header" style="text-align:center; margin-bottom:48px;">
            <span class="section-label" style="justify-content:center;"><?php esc_html_e( 'Galeri Foto', 'dpw-psi-papeng' ); ?></span>
            <h2 class="heading-section"><?php esc_html_e( 'Dokumentasi Foto Kegiatan', 'dpw-psi-papeng' ); ?></h2>
        </div>

        <div class="gallery-masonry">
            <?php while ( $galleries->have_posts() ) : $galleries->the_post();
                $gallery_ids = get_post_meta( get_the_ID(), '_psi_gallery_ids', true );
                $first_img   = '';
                if ( ! empty( $gallery_ids ) && is_array( $gallery_ids ) ) {
                    $first_img = wp_get_attachment_image_url( absint( $gallery_ids[0] ), 'dpw-gallery' );
                }
                if ( ! $first_img && has_post_thumbnail() ) {
                    $first_img = wp_get_attachment_image_url( get_post_thumbnail_id(), 'dpw-gallery' );
                }
            ?>
                <a href="<?php the_permalink(); ?>" class="gallery-card" data-aos="fade-up">
                    <?php if ( $first_img ) : ?>
                        <div class="gallery-card-img">
                            <img src="<?php echo esc_url( $first_img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                        </div>
                    <?php endif; ?>
                    <div class="gallery-card-body">
                        <h3><?php the_title(); ?></h3>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <div style="text-align:center; margin-top:48px;">
            <a href="<?php echo esc_url( home_url( '/galeri/' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'Lihat Semua Galeri', 'dpw-psi-papeng' ); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
