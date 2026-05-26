<?php
/**
 * Archive Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();

 $post_type   = get_post_type();
 $type_labels = array(
    'psi_news'      => 'Berita',
    'psi_video'     => 'Video',
    'psi_gallery'   => 'Galeri',
    'psi_structure' => 'Struktur Organisasi',
    'psi_dpd'       => 'DPD Wilayah',
);
 $archive_title = isset( $type_labels[ $post_type ] ) ? $type_labels[ $post_type ] : get_the_archive_title();
?>

<main id="primary" class="site-main">
    <section class="archive-header-section">
        <div class="container">
            <h1 class="archive-title"><?php echo esc_html( $archive_title ); ?></h1>
            <?php
            $term_desc = term_description();
            if ( $term_desc ) {
                echo '<p class="archive-desc">' . wp_kses_post( $term_desc ) . '</p>';
            }
            ?>
        </div>
    </section>

    <div class="container" style="padding:40px 24px 80px;">
        <?php if ( have_posts() ) : ?>

            <?php if ( $post_type === 'psi_structure' ) : ?>
                <div class="structure-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="structure-card" data-aos="fade-up">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="structure-card-img">
                                    <?php the_post_thumbnail( 'dpw-leader' ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="structure-card-body">
                                <h3><?php the_title(); ?></h3>
                                <?php
                                $positions = get_the_terms( get_the_ID(), 'structure_position' );
                                if ( is_array( $positions ) && ! empty( $positions ) ) {
                                    echo '<p class="structure-card-role">';
                                    $role_names = array();
                                    foreach ( $positions as $p ) {
                                        $role_names[] = esc_html( $p->name );
                                    }
                                    echo implode( ', ', $role_names );
                                    echo '</p>';
                                }
                                ?>
                                <div class="structure-card-excerpt"><?php the_excerpt(); ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            <?php elseif ( $post_type === 'psi_dpd' ) : ?>
                <div class="dpd-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="dpd-card" data-aos="fade-up">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="dpd-card-img">
                                    <?php the_post_thumbnail( 'dpw-card' ); ?>
                                </div>
                            <?php endif; ?>
                            <div class="dpd-card-body">
                                <h3><?php the_title(); ?></h3>
                                <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
                                <a href="<?php the_permalink(); ?>" class="dpd-card-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

            <?php elseif ( $post_type === 'psi_video' ) : ?>
                <div class="video-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php
                        $video_url = get_post_meta( get_the_ID(), '_psi_video_url', true );
                        $thumb_id  = get_post_thumbnail_id();
                        $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'dpw-card' ) : '';
                        if ( ! $thumb_url && $video_url ) {
                            $thumb_url = 'https://img.youtube.com/vi/' . dpw_psi_extract_yt_id( $video_url ) . '/hqdefault.jpg';
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
                    <?php endwhile; ?>
                </div>

            <?php elseif ( $post_type === 'psi_gallery' ) : ?>
                <div class="gallery-masonry">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php
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
                    <?php endwhile; ?>
                </div>

            <?php else : ?>
                <div class="news-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/content', 'archive' ); ?>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <div class="pagination">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                ) );
                ?>
            </div>

        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </div>
</main>

<?php
/* Helper: extract YouTube video ID */
function dpw_psi_extract_yt_id( $url ) {
    $id = '';
    if ( preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m ) ) {
        $id = $m[1];
    }
    return $id;
}

get_footer();
?>
