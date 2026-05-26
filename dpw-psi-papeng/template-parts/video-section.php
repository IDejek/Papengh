<?php
/**
 * Video Section Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $videos = new WP_Query( array(
    'post_type'      => 'psi_video',
    'posts_per_page' => 4,
    'post_status'    => 'publish',
) );

if ( ! $videos->have_posts() ) {
    return;
}
?>

<section class="section video-section" data-aos="fade-up">
    <div class="container">
        <div class="section-header" style="text-align:center; margin-bottom:48px;">
            <span class="section-label" style="justify-content:center;"><?php esc_html_e( 'Video Kegiatan', 'dpw-psi-papeng' ); ?></span>
            <h2 class="heading-section"><?php esc_html_e( 'Dokumentasi Video Kegiatan', 'dpw-psi-papeng' ); ?></h2>
        </div>

        <div class="video-grid">
            <?php while ( $videos->have_posts() ) : $videos->the_post();
                $video_url = get_post_meta( get_the_ID(), '_psi_video_url', true );
                $thumb_id  = get_post_thumbnail_id();
                $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'dpw-card' ) : '';

                if ( ! $thumb_url && $video_url ) {
                    $yt_id = '';
                    if ( preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $m ) ) {
                        $yt_id = $m[1];
                    }
                    if ( $yt_id ) {
                        $thumb_url = 'https://img.youtube.com/vi/' . $yt_id . '/hqdefault.jpg';
                    }
                }
            ?>
                <a href="<?php the_permalink(); ?>" class="video-card" data-aos="fade-up">
                    <div class="video-card-thumb">
                        <?php if ( $thumb_url ) : ?>
                            <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                        <?php endif; ?>
                        <div class="video-card-play"><i class="fas fa-play"></i></div>
                    </div>
                    <div class="video-card-body">
                        <h3><?php the_title(); ?></h3>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <div style="text-align:center; margin-top:48px;">
            <a href="<?php echo esc_url( home_url( '/video/' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'Lihat Semua Video', 'dpw-psi-papeng' ); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
