<?php
/**
 * Archive Content Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class( 'news-card' ); ?> data-aos="fade-up">
    <a href="<?php the_permalink(); ?>" class="news-card-link">
        <div class="news-card-img">
            <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'dpw-card' ); ?>
            <?php else : ?>
                <div class="news-card-placeholder"><i class="fas fa-newspaper"></i></div>
            <?php endif; ?>
        </div>
        <div class="news-card-body">
            <?php
            $cats = get_the_terms( get_the_ID(), 'news_category' );
            if ( is_array( $cats ) && ! empty( $cats ) ) {
                echo '<span class="news-card-cat">' . esc_html( $cats[0]->name ) . '</span>';
            }
            ?>
            <h3 class="news-card-title"><?php the_title(); ?></h3>
            <p class="news-card-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
            <div class="news-card-meta">
                <span><i class="far fa-calendar"></i> <?php echo esc_html( get_the_date() ); ?></span>
            </div>
        </div>
    </a>
</article>
