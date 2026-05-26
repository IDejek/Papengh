<?php
/**
 * Search Form Template
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text" for="search-field"><?php esc_html_e( 'Cari:', 'dpw-psi-papeng' ); ?></label>
    <div class="search-form-inner">
        <input type="search" id="search-field" class="search-input" placeholder="<?php esc_attr_e( 'Ketik kata kunci...', 'dpw-psi-papeng' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" required>
        <button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Cari', 'dpw-psi-papeng' ); ?>">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>
