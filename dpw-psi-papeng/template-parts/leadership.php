<?php
/**
 * Leadership Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $positions = array( 'ketua', 'sekretaris', 'bendahara' );
 $leaders = new WP_Query( array(
    'post_type'      => 'psi_structure',
    'posts_per_page' => 3,
    'tax_query'      => array(
        array(
            'taxonomy' => 'structure_position',
            'field'    => 'slug',
            'terms'    => $positions,
        ),
    ),
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
) );

if ( ! $leaders->have_posts() ) {
    return;
}
?>

<section class="section leadership-section" data-aos="fade-up">
    <div class="container">
        <div class="section-header" style="text-align:center; margin-bottom:48px;">
            <span class="section-label" style="justify-content:center;"><?php esc_html_e( 'Pimpinan', 'dpw-psi-papeng' ); ?></span>
            <h2 class="heading-section"><?php esc_html_e( 'Pimpinan DPW PSI Papua Pegunungan', 'dpw-psi-papeng' ); ?></h2>
        </div>

        <div class="leadership-grid">
            <?php while ( $leaders->have_posts() ) : $leaders->the_post(); ?>
                <div class="leader-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="leader-card-img">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'dpw-leader' ); ?>
                        <?php else : ?>
                            <div class="leader-card-placeholder"><i class="fas fa-user"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="leader-card-body">
                        <h3><?php the_title(); ?></h3>
                        <?php
                        $roles = get_the_terms( get_the_ID(), 'structure_position' );
                        if ( is_array( $roles ) && ! empty( $roles ) ) {
                            echo '<p class="leader-role">' . esc_html( $roles[0]->name ) . '</p>';
                        }
                        ?>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <div style="text-align:center; margin-top:48px;">
            <a href="<?php echo esc_url( home_url( '/struktur/' ) ); ?>" class="btn btn-outline">
                <?php esc_html_e( 'Lihat Struktur Lengkap', 'dpw-psi-papeng' ); ?> <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
