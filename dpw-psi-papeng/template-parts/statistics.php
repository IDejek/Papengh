<?php
/**
 * Statistics Template Part
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

 $stats = array(
    array( 'value' => dpw_psi_get( 'stat_members', '2500' ), 'label' => __( 'Total Anggota', 'dpw-psi-papeng' ), 'icon' => 'fas fa-users' ),
    array( 'value' => dpw_psi_get( 'stat_dpd', '8' ), 'label' => __( 'Total DPD', 'dpw-psi-papeng' ), 'icon' => 'fas fa-building' ),
    array( 'value' => dpw_psi_get( 'stat_activities', '150' ), 'label' => __( 'Total Kegiatan', 'dpw-psi-papeng' ), 'icon' => 'fas fa-calendar-check' ),
    array( 'value' => dpw_psi_get( 'stat_cadres', '800' ), 'label' => __( 'Total Kader', 'dpw-psi-papeng' ), 'icon' => 'fas fa-user-graduate' ),
);
?>

<section class="section stats-section">
    <div class="container">
        <div class="stats-grid">
            <?php foreach ( $stats as $i => $stat ) : ?>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="<?php echo esc_attr( $i * 100 ); ?>">
                    <div class="stat-icon"><i class="<?php echo esc_attr( $stat['icon'] ); ?>"></i></div>
                    <div class="stat-number" data-target="<?php echo esc_attr( $stat['value'] ); ?>">0</div>
                    <div class="stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
