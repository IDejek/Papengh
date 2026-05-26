<?php
/**
 * Front Page Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main">

    <?php get_template_part( 'template-parts/hero-slider' ); ?>
    <?php get_template_part( 'template-parts/chairman' ); ?>
    <?php get_template_part( 'template-parts/leadership' ); ?>
    <?php get_template_part( 'template-parts/dpd-section' ); ?>
    <?php get_template_part( 'template-parts/statistics' ); ?>
    <?php get_template_part( 'template-parts/latest-news' ); ?>
    <?php get_template_part( 'template-parts/video-section' ); ?>
    <?php get_template_part( 'template-parts/gallery-section' ); ?>
    <?php get_template_part( 'template-parts/cta-registration' ); ?>

</main>

<?php get_footer(); ?>
