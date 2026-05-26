<?php
/**
 * Search Results Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main">
    <div class="container" style="padding:60px 24px 80px;">
        <div class="archive-header-section" style="margin-bottom:40px;">
            <h1 class="archive-title">
                <?php
                printf(
                    esc_html__( 'Hasil Pencarian untuk: %s', 'dpw-psi-papeng' ),
                    '<span>' . esc_html( get_search_query() ) . '</span>'
                );
                ?>
            </h1>
        </div>

        <?php get_search_form(); ?>

        <div style="margin-top:40px;">
            <?php if ( have_posts() ) : ?>
                <div class="news-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php get_template_part( 'template-parts/content', 'archive' ); ?>
                    <?php endwhile; ?>
                </div>
                <div class="pagination" style="margin-top:40px;">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '<i class="fas fa-chevron-left"></i>',
                        'next_text' => '<i class="fas fa-chevron-right"></i>',
                    ) );
                    ?>
                </div>
            <?php else : ?>
                <p style="text-align:center; color:var(--text-secondary); padding:60px 0;">
                    <?php esc_html_e( 'Tidak ada hasil yang ditemukan untuk kata kunci tersebut.', 'dpw-psi-papeng' ); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
