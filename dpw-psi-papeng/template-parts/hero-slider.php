<?php
/**
 * Hero Slider Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $enabled = dpw_psi_get( 'hero_enabled', true );
if ( ! $enabled ) {
    return;
}

 $count = absint( dpw_psi_get( 'hero_count', 5 ) );

 $slides = new WP_Query( array(
    'post_type'      => 'psi_slide',
    'posts_per_page' => $count,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
) );

if ( ! $slides->have_posts() ) {
    return;
}
?>

<section class="hero-slider" aria-label="Hero Slider">
    <div class="swiper hero-swiper">
        <div class="swiper-wrapper">
            <?php while ( $slides->have_posts() ) : $slides->the_post(); ?>
                <div class="swiper-slide">
                    <div class="hero-slide">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="hero-slide-bg">
                                <?php the_post_thumbnail( 'dpw-hero' ); ?>
                            </div>
                        <?php else : ?>
                            <div class="hero-slide-bg hero-slide-placeholder"></div>
                        <?php endif; ?>
                        <div class="hero-slide-overlay"></div>
                        <div class="container">
                            <div class="hero-slide-content">
                                <h1 class="hero-slide-title"><?php the_title(); ?></h1>
                                <?php if ( get_the_content() ) : ?>
                                    <div class="hero-slide-text"><?php the_content(); ?></div>
                                <?php endif; ?>
                                <?php
                                $slide_btn_text  = get_post_meta( get_the_ID(), '_psi_slide_btn_text', true );
                                $slide_btn_url   = get_post_meta( get_the_ID(), '_psi_slide_btn_url', true );
                                if ( $slide_btn_text && $slide_btn_url ) :
                                ?>
                                    <a href="<?php echo esc_url( $slide_btn_url ); ?>" class="hero-slide-btn" <?php echo ( strpos( $slide_btn_url, 'http' ) === 0 ) ? 'target="_blank" rel="noopener"' : ''; ?>>
                                        <?php echo esc_html( $slide_btn_text ); ?> <i class="fas fa-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <div class="swiper-pagination hero-pagination"></div>
        <div class="swiper-button-prev hero-nav-prev"><i class="fas fa-chevron-left"></i></div>
        <div class="swiper-button-next hero-nav-next"><i class="fas fa-chevron-right"></i></div>
    </div>
</section>
