<?php
/**
 * Plugin Name: DPW PSI Metaboxes
 * Description: Custom metaboxes for DPW PSI Papua Pegunungan theme.
 * Version: 1.0.0
 * Author: Iqbal Tombinawa
 * Text Domain: dpw-psi-papeng
 * Requires PHP: 8.0
 */

defined( 'ABSPATH' ) || exit;

class DPW_PSI_Metaboxes {

    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'register_metaboxes' ) );
        add_action( 'save_post', array( $this, 'save_metaboxes' ), 10, 2 );
    }

    public function register_metaboxes() {

        /* Slide Metabox */
        add_meta_box( 'dpw_slide_details', __( 'Detail Slide', 'dpw-psi-papeng' ), array( $this, 'render_slide_mb' ), 'psi_slide', 'normal', 'high' );

        /* Video Metabox */
        add_meta_box( 'dpw_video_details', __( 'Detail Video', 'dpw-psi-papeng' ), array( $this, 'render_video_mb' ), 'psi_video', 'normal', 'high' );

        /* Gallery Metabox */
        add_meta_box( 'dpw_gallery_details', __( 'Galeri Foto', 'dpw-psi-papeng' ), array( $this, 'render_gallery_mb' ), 'psi_gallery', 'normal', 'high' );
    }

    /* ── Slide Render ── */
    public function render_slide_mb( $post ) {
        wp_nonce_field( 'dpw_psi_save_mb', 'dpw_psi_slide_nonce' );
        $btn_text = get_post_meta( $post->ID, '_psi_slide_btn_text', true );
        $btn_url  = get_post_meta( $post->ID, '_psi_slide_btn_url', true );
        ?>
        <p>
            <label for="slide_btn_text"><strong><?php esc_html_e( 'Teks Tombol', 'dpw-psi-papeng' ); ?></strong></label><br>
            <input type="text" id="slide_btn_text" name="slide_btn_text" value="<?php echo esc_attr( $btn_text ); ?>" class="widefat" style="margin-top:6px;">
        </p>
        <p>
            <label for="slide_btn_url"><strong><?php esc_html_e( 'URL Tombol', 'dpw-psi-papeng' ); ?></strong></label><br>
            <input type="url" id="slide_btn_url" name="slide_btn_url" value="<?php echo esc_attr( $btn_url ); ?>" class="widefat" style="margin-top:6px;">
        </p>
        <?php
    }

    /* ── Video Render ── */
    public function render_video_mb( $post ) {
        wp_nonce_field( 'dpw_psi_save_mb', 'dpw_psi_video_nonce' );
        $video_url = get_post_meta( $post->ID, '_psi_video_url', true );
        ?>
        <p>
            <label for="video_url"><strong><?php esc_html_e( 'URL Video (YouTube/Vimeo)', 'dpw-psi-papeng' ); ?></strong></label><br>
            <input type="url" id="video_url" name="video_url" value="<?php echo esc_attr( $video_url ); ?>" class="widefat" style="margin-top:6px;" placeholder="https://www.youtube.com/watch?v=...">
        </p>
        <?php
    }

    /* ── Gallery Render ── */
    public function render_gallery_mb( $post ) {
        wp_nonce_field( 'dpw_psi_save_mb', 'dpw_psi_gallery_nonce' );
        $gallery_ids = get_post_meta( $post->ID, '_psi_gallery_ids', true );
        if ( ! is_array( $gallery_ids ) ) {
            $gallery_ids = array();
        }
        ?>
        <div id="gallery-metabox-wrapper">
            <div id="gallery-images-preview" style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px;">
                <?php foreach ( $gallery_ids as $gid ) :
                    $img_url = wp_get_attachment_image_url( absint( $gid ), 'thumbnail' );
                    if ( $img_url ) :
                ?>
                    <div class="gallery-preview-item" data-id="<?php echo esc_attr( $gid ); ?>" style="position:relative; width:120px; height:120px;">
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="" style="width:100%; height:100%; object-fit:cover; border-radius:6px;">
                        <button type="button" class="gallery-remove-btn" data-id="<?php echo esc_attr( $gid ); ?>" style="position:absolute; top:4px; right:4px; background:rgba(0,0,0,0.7); color:#fff; border:none; border-radius:50%; width:24px; height:24px; cursor:pointer; font-size:12px; display:flex; align-items:center; justify-content:center;">&times;</button>
                    </div>
                <?php
                    endif;
                endforeach;
                ?>
            </div>
            <input type="hidden" id="gallery_ids_input" name="gallery_ids" value="<?php echo esc_attr( implode( ',', $gallery_ids ) ); ?>">
            <button type="button" id="gallery-upload-btn" class="button button-primary"><?php esc_html_e( 'Pilih Foto dari Media', 'dpw-psi-papeng' ); ?></button>
        </div>
        <script>
        (function(){
            var frame;
            var btn = document.getElementById('gallery-upload-btn');
            var input = document.getElementById('gallery_ids_input');
            var preview = document.getElementById('gallery-images-preview');

            if (!btn || !input || !preview) return;

            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (frame) { frame.open(); return; }
                frame = wp.media({
                    title: 'Pilih Foto Galeri',
                    multiple: true,
                    library: { type: 'image' }
                });
                frame.on('select', function() {
                    var ids = input.value ? input.value.split(',').filter(function(v){return v.trim()!=='';}) : [];
                    var attachments = frame.state().get('selection').toJSON();
                    attachments.forEach(function(att) {
                        if (ids.indexOf(att.id.toString()) === -1) {
                            ids.push(att.id.toString());
                            var div = document.createElement('div');
                            div.className = 'gallery-preview-item';
                            div.setAttribute('data-id', att.id);
                            div.style.cssText = 'position:relative;width:120px;height:120px;';
                            div.innerHTML = '<img src="' + att.sizes.thumbnail.url + '" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">' +
                                '<button type="button" class="gallery-remove-btn" data-id="' + att.id + '" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,0.7);color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;font-size:12px;display:flex;align-items:center;justify-content:center;">&times;</button>';
                            preview.appendChild(div);
                        }
                    });
                    input.value = ids.join(',');
                });
                frame.open();
            });

            preview.addEventListener('click', function(e) {
                if (e.target.classList.contains('gallery-remove-btn') || e.target.closest('.gallery-remove-btn')) {
                    var btnEl = e.target.classList.contains('gallery-remove-btn') ? e.target : e.target.closest('.gallery-remove-btn');
                    var rid = btnEl.getAttribute('data-id');
                    var item = btnEl.closest('.gallery-preview-item');
                    if (item) item.remove();
                    var ids = input.value ? input.value.split(',').filter(function(v){return v.trim()!=='';}) : [];
                    ids = ids.filter(function(v){return v !== rid;});
                    input.value = ids.join(',');
                }
            });
        })();
        </script>
        <?php
    }

    /* ── Save ── */
    public function save_metaboxes( $post_id, $post ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        /* Slide */
        if ( isset( $_POST['dpw_psi_slide_nonce'] ) && wp_verify_nonce( $_POST['dpw_psi_slide_nonce'], 'dpw_psi_save_mb' ) ) {
            if ( isset( $_POST['slide_btn_text'] ) ) {
                update_post_meta( $post_id, '_psi_slide_btn_text', sanitize_text_field( $_POST['slide_btn_text'] ) );
            }
            if ( isset( $_POST['slide_btn_url'] ) ) {
                update_post_meta( $post_id, '_psi_slide_btn_url', esc_url_raw( $_POST['slide_btn_url'] ) );
            }
        }

        /* Video */
        if ( isset( $_POST['dpw_psi_video_nonce'] ) && wp_verify_nonce( $_POST['dpw_psi_video_nonce'], 'dpw_psi_save_mb' ) ) {
            if ( isset( $_POST['video_url'] ) ) {
                update_post_meta( $post_id, '_psi_video_url', esc_url_raw( $_POST['video_url'] ) );
            }
        }

        /* Gallery */
        if ( isset( $_POST['dpw_psi_gallery_nonce'] ) && wp_verify_nonce( $_POST['dpw_psi_gallery_nonce'], 'dpw_psi_save_mb' ) ) {
            if ( isset( $_POST['gallery_ids'] ) ) {
                $raw_ids = sanitize_text_field( $_POST['gallery_ids'] );
                $ids = array_filter( array_map( 'absint', explode( ',', $raw_ids ) ) );
                update_post_meta( $post_id, '_psi_gallery_ids', array_values( $ids ) );
            }
        }
    }
}

new DPW_PSI_Metaboxes();
