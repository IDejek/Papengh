<?php
/**
 * Chairman Welcome Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $name    = dpw_psi_get( 'chairman_name', '' );
 $title   = dpw_psi_get( 'chairman_title', 'Ketua DPW PSI Papua Pegunungan' );
 $message = dpw_psi_get( 'chairman_message', '' );

if ( ! $name && ! $message ) {
    return;
}
?>

<section class="section chairman-section" data-aos="fade-up">
    <div class="container">
        <div class="chairman-grid">
            <div class="chairman-content">
                <span class="section-label"><?php esc_html_e( 'Sambutan Ketua', 'dpw-psi-papeng' ); ?></span>
                <h2 class="heading-section"><?php echo esc_html( $name ); ?></h2>
                <p class="chairman-role"><?php echo esc_html( $title ); ?></p>
                <div class="chairman-message">
                    <?php echo wp_kses_post( $message ); ?>
                </div>
            </div>
            <div class="chairman-photo">
                <?php
                $chairman_id = 0;
                $chairman_query = new WP_Query( array(
                    'post_type'      => 'psi_structure',
                    'posts_per_page' => 1,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'structure_position',
                            'field'    => 'slug',
                            'terms'    => 'ketua',
                        ),
                    ),
                ) );
                if ( $chairman_query->have_posts() ) {
                    $chairman_query->the_post();
                    $chairman_id = get_the_ID();
                    if ( has_post_thumbnail() ) {
                        the_post_thumbnail( 'dpw-leader' );
                    }
                    wp_reset_postdata();
                } else {
                    echo '<div class="chairman-photo-placeholder"><i class="fas fa-user-tie"></i></div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>
