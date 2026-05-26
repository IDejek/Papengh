<?php
/**
 * Single Post Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();

function dpw_psi_breadcrumbs() {
    if ( function_exists( 'yoast_breadcrumb' ) ) {
        yoast_breadcrumb( '<div class="breadcrumbs">', '</div>' );
        return;
    }
    $post_type = get_post_type();
    $labels    = array(
        'psi_news'      => array( 'archive' => 'Berita', 'slug' => 'berita' ),
        'psi_video'     => array( 'archive' => 'Video', 'slug' => 'video' ),
        'psi_gallery'   => array( 'archive' => 'Galeri', 'slug' => 'galeri' ),
        'psi_structure' => array( 'archive' => 'Struktur', 'slug' => 'struktur' ),
        'psi_dpd'       => array( 'archive' => 'DPD', 'slug' => 'dpd' ),
        'post'          => array( 'archive' => 'Blog', 'slug' => '' ),
    );
    $info = isset( $labels[ $post_type ] ) ? $labels[ $post_type ] : $labels['post'];

    echo '<div class="breadcrumbs"><a href="' . esc_url( home_url( '/' ) ) . '">Beranda</a>';
    if ( $info['slug'] ) {
        echo ' <span>/</span> <a href="' . esc_url( home_url( '/' . $info['slug'] . '/' ) ) . '">' . esc_html( $info['archive'] ) . '</a>';
    }
    echo ' <span>/</span> <span>' . esc_html( get_the_title() ) . '</span></div>';
}
?>

<main id="primary" class="site-main">
    <div class="container" style="padding:60px 24px; max-width:860px; margin:0 auto;">

        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class( 'single-article' ); ?>>

                    <?php dpw_psi_breadcrumbs(); ?>

                    <h1 class="single-title"><?php the_title(); ?></h1>

                    <?php if ( in_array( get_post_type(), array( 'psi_news' ), true ) ) : ?>
                        <div class="single-meta">
                            <span><i class="far fa-calendar"></i> <?php echo esc_html( get_the_date() ); ?></span>
                            <span><i class="far fa-user"></i> <?php echo esc_html( get_the_author() ); ?></span>
                            <?php
                            $cats = get_the_terms( get_the_ID(), 'news_category' );
                            if ( is_array( $cats ) && ! empty( $cats ) ) {
                                foreach ( $cats as $cat ) {
                                    echo '<a href="' . esc_url( get_term_link( $cat ) ) . '" class="single-cat">' . esc_html( $cat->name ) . '</a>';
                                }
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( get_post_type() === 'psi_video' ) : ?>
                        <?php
                        $video_url = get_post_meta( get_the_ID(), '_psi_video_url', true );
                        if ( $video_url ) {
                            $embed = wp_oembed_get( esc_url( $video_url ), array( 'width' => 860 ) );
                            if ( $embed ) {
                                echo '<div class="single-video-embed">' . $embed . '</div>';
                            }
                        }
                        ?>
                    <?php endif; ?>

                    <?php if ( has_post_thumbnail() && get_post_type() !== 'psi_video' ) : ?>
                        <div class="single-featured">
                            <?php the_post_thumbnail( 'large' ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="single-content">
                        <?php the_content(); ?>
                    </div>

                    <?php if ( get_post_type() === 'psi_gallery' ) : ?>
                        <?php
                        $gallery_ids = get_post_meta( get_the_ID(), '_psi_gallery_ids', true );
                        if ( ! empty( $gallery_ids ) && is_array( $gallery_ids ) ) :
                        ?>
                            <div class="single-gallery-grid">
                                <?php foreach ( $gallery_ids as $gid ) :
                                    $img_url = wp_get_attachment_image_url( absint( $gid ), 'dpw-gallery' );
                                    $full_url = wp_get_attachment_image_url( absint( $gid ), 'full' );
                                    if ( $img_url ) :
                                ?>
                                    <a href="<?php echo esc_url( $full_url ); ?>" class="single-gallery-item" data-lightbox="gallery">
                                        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( get_post_meta( absint( $gid ), '_wp_attachment_image_alt', true ) ); ?>" loading="lazy">
                                    </a>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ( get_post_type() === 'psi_news' ) : ?>
                        <div class="single-share">
                            <span><?php esc_html_e( 'Bagikan:', 'dpw-psi-papeng' ); ?></span>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( get_permalink() ); ?>" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url( get_permalink() ); ?>&text=<?php echo esc_attr( get_the_title() ); ?>" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://wa.me/?text=<?php echo esc_attr( get_the_title() . ' ' . get_permalink() ); ?>" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i></a>
                        </div>

                        <?php
                        $related = new WP_Query( array(
                            'post_type'      => 'psi_news',
                            'posts_per_page' => 3,
                            'post__not_in'   => array( get_the_ID() ),
                            'orderby'        => 'rand',
                        ) );
                        if ( $related->have_posts() ) :
                        ?>
                            <div class="single-related" style="margin-top:60px;">
                                <h3 class="section-label" style="margin-bottom:24px;"><?php esc_html_e( 'Berita Terkait', 'dpw-psi-papeng' ); ?></h3>
                                <div class="news-grid news-grid-3">
                                    <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                        <?php get_template_part( 'template-parts/content', 'archive' ); ?>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </article>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
