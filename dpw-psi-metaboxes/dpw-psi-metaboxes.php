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
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    public function enqueue_admin_assets( $hook ) {
        global $post_type;
        if ( 'psi_gallery' === $post_type && in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
            wp_enqueue_media();
        }
    }

    public function register_metaboxes() {
        add_meta_box(
            'dpw_slide_details',
            'Detail Slide',
            array( $this, 'render_slide_mb' ),
            'psi_slide',
            'normal',
            'high'
        );
        add_meta_box(
            'dpw_video_details',
            'Detail Video',
            array( $this, 'render_video_mb' ),
            'psi_video',
            'normal',
            'high'
        );
        add_meta_box(
            'dpw_gallery_details',
            'Galeri Foto',
            array( $this, 'render_gallery_mb' ),
            'psi_gallery',
            'normal',
            'high'
        );
    }

    public function render_slide_mb( $post ) {
        wp_nonce_field( 'dpw_psi_save_slide', 'dpw_psi_slide_nonce' );
        $btn_text = get_post_meta( $post->ID, '_psi_slide_btn_text', true );
        $btn_url  = get_post_meta( $post->ID, '_psi_slide_btn_url', true );
        ?>
        <p>
            <label for="slide_btn_text"><strong>Teks Tombol</strong></label><br>
            <input type="text" id="slide_btn_text" name="slide_btn_text" value="<?php echo esc_attr( $btn_text ); ?>" class="widefat" style="margin-top:6px;">
        </p>
        <p>
            <label for="slide_btn_url"><strong>URL Tombol</strong></label><br>
            <input type="url" id="slide_btn_url" name="slide_btn_url" value="<?php echo esc_attr( $btn_url ); ?>" class="widefat" style="margin-top:6px;">
        </p>
        <?php
    }

    public function render_video_mb( $post ) {
        wp_nonce_field( 'dpw_psi_save_video', 'dpw_psi_video_nonce' );
        $video_url = get_post_meta( $post->ID, '_psi_video_url', true );
        ?>
        <p>
            <label for="video_url"><strong>URL Video (YouTube / Vimeo)</strong></label><br>
            <input type="url" id="video_url" name="video_url" value="<?php echo esc_attr( $video_url ); ?>" class="widefat" style="margin-top:6px;" placeholder="https://www.youtube.com/watch?v=...">
        </p>
        <?php
    }

    public function render_gallery_mb( $post ) {
        wp_nonce_field( 'dpw_psi_save_gallery', 'dpw_psi_gallery_nonce' );
        $gallery_ids = get_post_meta( $post->ID, '_psi_gallery_ids', true );
        if ( ! is_array( $gallery_ids ) ) {
            $gallery_ids = array();
        }
        $ids_string = implode( ',', $gallery_ids );
        ?>
        <div id="dpw-gallery-mb-wrap">
            <div id="dpw-gallery-preview" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:16px;">
                <?php
                foreach ( $gallery_ids as $gid ) {
                    $img_url = wp_get_attachment_image_url( absint( $gid ), 'thumbnail' );
                    if ( $img_url ) {
                        echo '<div class="dpw-gi" data-id="' . esc_attr( $gid ) . '" style="position:relative;width:120px;height:120px;">';
                        echo '<img src="' . esc_url( $img_url ) . '" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">';
                        echo '<button type="button" class="dpw-gi-rm" data-id="' . esc_attr( $gid ) . '" style="position:absolute;top:4px;right:4px;background:rgba(0,0,0,0.7);color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;font-size:14px;line-height:24px;text-align:center;padding:0;">&times;</button>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
            <input type="hidden" id="dpw-gallery-ids" name="dpw_gallery_ids" value="<?php echo esc_attr( $ids_string ); ?>">
            <button type="button" id="dpw-gallery-add" class="button button-primary">Pilih Foto dari Media</button>
        </div>
        <?php
    }

    public function save_metaboxes( $post_id, $post ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        /* Slide */
        if ( isset( $_POST['dpw_psi_slide_nonce'] ) && wp_verify_nonce( $_POST['dpw_psi_slide_nonce'], 'dpw_psi_save_slide' ) ) {
            if ( isset( $_POST['slide_btn_text'] ) ) {
                update_post_meta( $post_id, '_psi_slide_btn_text', sanitize_text_field( $_POST['slide_btn_text'] ) );
            }
            if ( isset( $_POST['slide_btn_url'] ) ) {
                update_post_meta( $post_id, '_psi_slide_btn_url', esc_url_raw( $_POST['slide_btn_url'] ) );
            }
        }

        /* Video */
        if ( isset( $_POST['dpw_psi_video_nonce'] ) && wp_verify_nonce( $_POST['dpw_psi_video_nonce'], 'dpw_psi_save_video' ) ) {
            if ( isset( $_POST['video_url'] ) ) {
                update_post_meta( $post_id, '_psi_video_url', esc_url_raw( $_POST['video_url'] ) );
            }
        }

        /* Gallery */
        if ( isset( $_POST['dpw_psi_gallery_nonce'] ) && wp_verify_nonce( $_POST['dpw_psi_gallery_nonce'], 'dpw_psi_save_gallery' ) ) {
            if ( isset( $_POST['dpw_gallery_ids'] ) ) {
                $raw  = sanitize_text_field( $_POST['dpw_gallery_ids'] );
                $parts = explode( ',', $raw );
                $ids   = array();
                foreach ( $parts as $p ) {
                    $clean = absint( trim( $p ) );
                    if ( $clean > 0 ) {
                        $ids[] = $clean;
                    }
                }
                update_post_meta( $post_id, '_psi_gallery_ids', array_values( $ids ) );
            }
        }
    }
}

new DPW_PSI_Metaboxes();

/* Gallery JS — loaded only on gallery edit screen via admin_footer */
add_action( 'admin_footer', 'dpw_psi_gallery_admin_js' );
function dpw_psi_gallery_admin_js() {
    global $post_type;
    if ( 'psi_gallery' !== $post_type ) {
        return;
    }
    ?>
    <script>
    (function(){
        var btn = document.getElementById('dpw-gallery-add');
        var input = document.getElementById('dpw-gallery-ids');
        var preview = document.getElementById('dpw-gallery-preview');
        if (!btn || !input || !preview) return;

        var frame = null;

        btn.addEventListener('click', function(e){
            e.preventDefault();
            if (frame) { frame.open(); return; }
            frame = wp.media({
                title: 'Pilih Foto Galeri',
                multiple: true,
                library: { type: 'image' }
            });
            frame.on('select', function(){
                var current = input.value ? input.value.split(',') : [];
                var clean = [];
                for (var i = 0; i < current.length; i++) {
                    var t = current[i].trim();
                    if (t !== '') clean.push(t);
                }
                var sel = frame.state().get('selection').toJSON();
                for (var j = 0; j < sel.length; j++) {
                    var att = sel[j];
                    if (clean.indexOf(att.id.toString()) === -1) {
                        clean.push(att.id.toString());
                        var div = document.createElement('div');
                        div.className = 'dpw-gi';
                        div.setAttribute('data-id', att.id);
                        div.style.cssText = 'position:relative;width:120px;height:120px;';
                        var img = document.createElement('img');
                        img.src = att.sizes.thumbnail.url;
                        img.alt = '';
                        img.style.cssText = 'width:100%;height:100%;object-fit:cover;border-radius:6px;';
                        div.appendChild(img);
                        var rm = document.createElement('button');
                        rm.type = 'button';
                        rm.className = 'dpw-gi-rm';
                        rm.setAttribute('data-id', att.id);
                        rm.innerHTML = '&times;';
                        rm.style.cssText = 'position:absolute;top:4px;right:4px;background:rgba(0,0,0,0.7);color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;font-size:14px;line-height:24px;text-align:center;padding:0;';
                        div.appendChild(rm);
                        preview.appendChild(div);
                    }
                }
                input.value = clean.join(',');
            });
            frame.open();
        });

        preview.addEventListener('click', function(e){
            var rmBtn = e.target.closest('.dpw-gi-rm');
            if (!rmBtn) return;
            e.preventDefault();
            var rid = rmBtn.getAttribute('data-id');
            var item = rmBtn.closest('.dpw-gi');
            if (item) item.remove();
            var current = input.value ? input.value.split(',') : [];
            var clean = [];
            for (var i = 0; i < current.length; i++) {
                var t = current[i].trim();
                if (t !== '' && t !== rid) clean.push(t);
            }
            input.value = clean.join(',');
        });
    })();
    </script>
    <?php
}
