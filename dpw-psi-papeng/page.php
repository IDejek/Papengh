<?php
/**
 * Page Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main">
    <div class="container" style="padding:60px 24px; max-width:900px; margin:0 auto;">
        <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( 'single-article' ); ?>>
                <h1 class="single-title"><?php the_title(); ?></h1>
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="single-featured">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>
                <div class="single-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
