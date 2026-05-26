<?php
/**
 * SEO & Google Search Console Integration
 *
 * @package DPW_PSI_Papeng
 */

defined( 'ABSPATH' ) || exit;

class DPW_PSI_SEO {

    public function __construct() {
        add_action( 'init', array( $this, 'add_sitemap_rewrite' ) );
        add_filter( 'query_vars', array( $this, 'register_query_vars' ) );
        add_action( 'template_redirect', array( $this, 'render_sitemap' ) );
        add_filter( 'robots_txt', array( $this, 'robots_txt_sitemap' ), 10, 2 );
        add_action( 'wp_head', array( $this, 'output_preconnect' ), 1 );
        add_action( 'wp_head', array( $this, 'output_head_meta' ), 5 );
        add_action( 'wp_head', array( $this, 'output_schema' ), 99 );
    }

    /* ── Sitemap Rewrite ── */
    public function add_sitemap_rewrite() {
        add_rewrite_rule( '^sitemap\.xml$', 'index.php?dpw_sitemap=1', 'top' );
    }

    public function register_query_vars( $vars ) {
        $vars[] = 'dpw_sitemap';
        return $vars;
    }

    /* ── Render XML Sitemap ── */
    public function render_sitemap() {
        if ( ! get_query_var( 'dpw_sitemap' ) ) {
            return;
        }

        header( 'Content-Type: application/xml; charset=UTF-8' );
        header( 'X-Robots-Tag: noindex, follow' );

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $this->output_url( home_url( '/' ), $this->get_latest_post_date(), 'daily', '1.0' );

        $pages = get_posts( array(
            'post_type'      => 'page',
            'posts_per_page' => 50,
            'post_status'    => 'publish',
        ) );
        if ( is_array( $pages ) ) {
            foreach ( $pages as $p ) {
                $this->output_url( get_permalink( $p ), get_post_modified_time( 'Y-m-d', false, $p ), 'monthly', '0.7' );
            }
        }

        $post_types = array(
            'psi_news'      => array( 'priority' => '0.8', 'freq' => 'daily' ),
            'psi_video'     => array( 'priority' => '0.7', 'freq' => 'weekly' ),
            'psi_gallery'   => array( 'priority' => '0.6', 'freq' => 'weekly' ),
            'psi_structure' => array( 'priority' => '0.6', 'freq' => 'monthly' ),
            'psi_dpd'       => array( 'priority' => '0.6', 'freq' => 'monthly' ),
        );

        foreach ( $post_types as $pt => $settings ) {
            $posts = get_posts( array(
                'post_type'      => $pt,
                'posts_per_page' => 200,
                'post_status'    => 'publish',
            ) );
            if ( is_array( $posts ) ) {
                foreach ( $posts as $p ) {
                    $this->output_url( get_permalink( $p ), get_post_modified_time( 'Y-m-d', false, $p ), $settings['freq'], $settings['priority'] );
                }
            }
        }

        $taxonomies = array( 'news_category', 'video_category', 'gallery_category', 'structure_position' );
        foreach ( $taxonomies as $tax ) {
            $terms = get_terms( array( 'taxonomy' => $tax, 'hide_empty' => true, 'number' => 100 ) );
            if ( is_array( $terms ) && ! is_wp_error( $terms ) ) {
                foreach ( $terms as $t ) {
                    $this->output_url( get_term_link( $t ), '2025-01-01', 'weekly', '0.5' );
                }
            }
        }

        echo '</urlset>';
        exit;
    }

    private function output_url( $loc, $lastmod, $freq, $priority ) {
        echo '<url>' . "\n";
        echo '<loc>' . esc_url( $loc ) . '</loc>' . "\n";
        if ( $lastmod ) {
            echo '<lastmod>' . esc_html( $lastmod ) . '</lastmod>' . "\n";
        }
        echo '<changefreq>' . esc_html( $freq ) . '</changefreq>' . "\n";
        echo '<priority>' . esc_html( $priority ) . '</priority>' . "\n";
        echo '</url>' . "\n";
    }

    private function get_latest_post_date() {
        $latest = get_posts( array(
            'post_type'      => 'any',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'orderby'        => 'modified',
            'order'          => 'DESC',
        ) );
        if ( ! empty( $latest ) ) {
            return get_post_modified_time( 'Y-m-d', false, $latest[0] );
        }
        return gmdate( 'Y-m-d' );
    }

    /* ── Robots.txt ── */
    public function robots_txt_sitemap( $output, $public ) {
        if ( '0' === $public ) {
            return $output;
        }
        $output .= "\nSitemap: " . esc_url( home_url( '/sitemap.xml' ) ) . "\n";
        return $output;
    }

    /* ── Preconnect ── */
    public function output_preconnect() {
        $domains = array(
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
            'https://cdnjs.cloudflare.com',
            'https://cdn.jsdelivr.net',
        );
        foreach ( $domains as $d ) {
            echo '<link rel="preconnect" href="' . esc_url( $d ) . '" crossorigin>' . "\n";
        }
    }

    /* ── Head Meta ── */
    public function output_head_meta() {

        $gsc_code = get_theme_mod( 'gsc_verification', '' );
        if ( $gsc_code ) {
            echo '<meta name="google-site-verification" content="' . esc_attr( $gsc_code ) . '">' . "\n";
        }

        $canonical = $this->get_canonical();
        if ( $canonical ) {
            echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
        }

        $robots = $this->get_robots_meta();
        if ( $robots ) {
            echo '<meta name="robots" content="' . esc_attr( $robots ) . '">' . "\n";
        }

        $desc = $this->get_meta_description();
        if ( $desc ) {
            echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
        }

        $this->output_og_tags();
        $this->output_twitter_tags();
    }

    private function get_canonical() {
        if ( is_singular() ) {
            return get_permalink();
        }
        if ( is_home() || is_front_page() ) {
            return home_url( '/' );
        }
        if ( is_category() || is_tax() ) {
            $term = get_queried_object();
            if ( $term && ! is_wp_error( $term ) ) {
                return get_term_link( $term );
            }
        }
        if ( is_post_type_archive() ) {
            $obj = get_queried_object();
            if ( $obj && isset( $obj->name ) ) {
                return get_post_type_archive_link( $obj->name );
            }
        }
        $request = isset( $GLOBALS['wp'] ) ? $GLOBALS['wp']->request : '';
        if ( $request ) {
            return home_url( '/' . $request . '/' );
        }
        return home_url( '/' );
    }

    private function get_robots_meta() {
        if ( is_404() || is_search() ) {
            return 'noindex, nofollow';
        }
        if ( is_paged() ) {
            return 'noindex, follow';
        }
        return 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    }

    private function get_meta_description() {
        $desc = '';

        if ( is_singular() ) {
            $post = get_queried_object();
            if ( $post && ! empty( $post->post_excerpt ) ) {
                $desc = $post->post_excerpt;
            } elseif ( $post && ! empty( $post->post_content ) ) {
                $desc = wp_trim_words( strip_tags( $post->post_content ), 30, '' );
            }
        } elseif ( is_front_page() || is_home() ) {
            $desc = get_bloginfo( 'description' );
            if ( ! $desc ) {
                $desc = dpw_psi_get( 'footer_desc', '' );
            }
        } elseif ( is_category() || is_tax() ) {
            $term = get_queried_object();
            if ( $term ) {
                $desc = ! empty( $term->description ) ? $term->description : $term->name . ' - DPW PSI Papua Pegunungan';
            }
        } elseif ( is_post_type_archive() ) {
            $obj = get_queried_object();
            if ( $obj && isset( $obj->name ) ) {
                $labels = array(
                    'psi_news'      => 'Berita terkini dari DPW PSI Papua Pegunungan',
                    'psi_video'     => 'Video kegiatan DPW PSI Papua Pegunungan',
                    'psi_gallery'   => 'Galeri foto kegiatan DPW PSI Papua Pegunungan',
                    'psi_structure' => 'Struktur organisasi DPW PSI Papua Pegunungan',
                    'psi_dpd'       => 'Daftar DPD PSI se-Papua Pegunungan',
                );
                $desc = isset( $labels[ $obj->name ] ) ? $labels[ $obj->name ] : '';
            }
        }

        if ( $desc ) {
            $desc = strip_tags( $desc );
            $desc = str_replace( array( "\r", "\n", "\t" ), ' ', $desc );
            $desc = preg_replace( '/\s+/', ' ', $desc );
            $desc = trim( $desc );
            if ( mb_strlen( $desc ) > 160 ) {
                $desc = mb_substr( $desc, 0, 157 ) . '...';
            }
        }

        return $desc;
    }

    private function output_og_tags() {
        $title = $this->get_og_title();
        $desc  = $this->get_meta_description();
        $url   = $this->get_canonical();
        $image = $this->get_og_image();
        $type  = is_singular( 'psi_news' ) ? 'article' : 'website';

        echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
        if ( $desc ) {
            echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
        }
        if ( $url ) {
            echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
        }
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
        echo '<meta property="og:locale" content="id_ID">' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
        if ( $image['url'] ) {
            echo '<meta property="og:image" content="' . esc_url( $image['url'] ) . '">' . "\n";
            if ( $image['width'] ) {
                echo '<meta property="og:image:width" content="' . esc_attr( $image['width'] ) . '">' . "\n";
            }
            if ( $image['height'] ) {
                echo '<meta property="og:image:height" content="' . esc_attr( $image['height'] ) . '">' . "\n";
            }
            if ( $image['alt'] ) {
                echo '<meta property="og:image:alt" content="' . esc_attr( $image['alt'] ) . '">' . "\n";
            }
        }

        if ( is_singular( 'psi_news' ) ) {
            $post = get_queried_object();
            if ( $post ) {
                echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c', $post ) ) . '">' . "\n";
                $mod = get_post_modified_time( 'c', false, $post );
                $pub = get_the_date( 'c', $post );
                if ( $pub !== $mod ) {
                    echo '<meta property="article:modified_time" content="' . esc_attr( $mod ) . '">' . "\n";
                }
                echo '<meta property="article:author" content="' . esc_attr( get_the_author_meta( 'display_name', $post->post_author ) ) . '">' . "\n";
                $cats = get_the_terms( $post, 'news_category' );
                if ( is_array( $cats ) && ! empty( $cats ) ) {
                    echo '<meta property="article:section" content="' . esc_attr( $cats[0]->name ) . '">' . "\n";
                }
                $tags = get_the_terms( $post, 'news_tag' );
                if ( is_array( $tags ) && ! empty( $tags ) ) {
                    foreach ( $tags as $t ) {
                        echo '<meta property="article:tag" content="' . esc_attr( $t->name ) . '">' . "\n";
                    }
                }
            }
        }
    }

    private function get_og_title() {
        if ( is_singular() ) {
            return get_the_title() . ' — ' . get_bloginfo( 'name' );
        }
        if ( is_front_page() || is_home() ) {
            $desc = get_bloginfo( 'description' );
            if ( $desc ) {
                return get_bloginfo( 'name' ) . ' — ' . $desc;
            }
            return get_bloginfo( 'name' );
        }
        if ( is_category() || is_tax() ) {
            $term = get_queried_object();
            if ( $term ) {
                return $term->name . ' — ' . get_bloginfo( 'name' );
            }
        }
        if ( is_post_type_archive() ) {
            $obj = get_queried_object();
            if ( $obj && isset( $obj->name ) ) {
                $labels = array(
                    'psi_news' => 'Berita',
                    'psi_video' => 'Video',
                    'psi_gallery' => 'Galeri',
                    'psi_structure' => 'Struktur Organisasi',
                    'psi_dpd' => 'DPD Wilayah',
                );
                $name = isset( $labels[ $obj->name ] ) ? $labels[ $obj->name ] : $obj->labels->name;
                return $name . ' — ' . get_bloginfo( 'name' );
            }
        }
        if ( is_404() ) {
            return 'Halaman Tidak Ditemukan — ' . get_bloginfo( 'name' );
        }
        return get_bloginfo( 'name' );
    }

    private function get_og_image() {
        $image = array( 'url' => '', 'width' => '', 'height' => '', 'alt' => '' );
        if ( is_singular() ) {
            $thumb_id = get_post_thumbnail_id();
            if ( $thumb_id ) {
                $img_data = wp_get_attachment_image_src( $thumb_id, 'full' );
                if ( $img_data ) {
                    $image['url']    = $img_data[0];
                    $image['width']  = $img_data[1];
                    $image['height'] = $img_data[2];
                    $image['alt']    = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
                }
            }
        }
        return $image;
    }

    private function output_twitter_tags() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $this->get_og_title() ) . '">' . "\n";
        $desc = $this->get_meta_description();
        if ( $desc ) {
            echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
        }
        $image = $this->get_og_image();
        if ( $image['url'] ) {
            echo '<meta name="twitter:image" content="' . esc_url( $image['url'] ) . '">' . "\n";
        }
    }

    /* ── Schema Markup ── */
    public function output_schema() {
        $this->output_organization_schema();

        if ( is_front_page() || is_home() ) {
            $this->output_website_schema();
        }

        if ( ! is_front_page() && ! is_home() && ! is_404() ) {
            $this->output_breadcrumb_schema();
        }

        if ( is_singular( 'psi_news' ) ) {
            $this->output_article_schema();
        }

        if ( is_singular( 'psi_video' ) ) {
            $this->output_video_schema();
        }
    }

    private function output_json_ld( $data ) {
        if ( empty( $data ) ) {
            return;
        }
        $json = wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
        if ( $json ) {
            echo '<script type="application/ld+json">' . $json . '</script>' . "\n";
        }
    }

    private function output_organization_schema() {
        $data = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => get_bloginfo( 'name' ),
            'url'      => home_url( '/' ),
            'logo'     => $this->get_site_logo_url(),
            'description' => get_bloginfo( 'description' ),
            'address'  => array(
                '@type'          => 'PostalAddress',
                'addressRegion'  => 'Papua Pegunungan',
                'addressCountry' => 'ID',
            ),
            'sameAs'   => $this->get_social_urls(),
        );
        $this->output_json_ld( $data );
    }

    private function output_website_schema() {
        $data = array(
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => get_bloginfo( 'name' ),
            'url'      => home_url( '/' ),
            'inLanguage' => 'id-ID',
            'potentialAction' => array(
                '@type'       => 'SearchAction',
                'target'      => home_url( '/?s={search_term_string}' ),
                'query-input' => 'required name=search_term_string',
            ),
        );
        $this->output_json_ld( $data );
    }

    private function output_breadcrumb_schema() {
        $items = array(
            array( 'name' => 'Beranda', 'url' => home_url( '/' ) ),
        );

        if ( is_singular() ) {
            $post_type = get_post_type();
            $labels = array(
                'psi_news'      => 'Berita',
                'psi_video'     => 'Video',
                'psi_gallery'   => 'Galeri',
                'psi_structure' => 'Struktur',
                'psi_dpd'       => 'DPD',
                'post'          => 'Blog',
            );
            if ( isset( $labels[ $post_type ] ) ) {
                $archive_url = get_post_type_archive_link( $post_type );
                if ( $archive_url ) {
                    $items[] = array( 'name' => $labels[ $post_type ], 'url' => $archive_url );
                }
            }
            $items[] = array( 'name' => get_the_title(), 'url' => get_permalink() );
        } elseif ( is_category() || is_tax() ) {
            $term = get_queried_object();
            if ( $term ) {
                $items[] = array( 'name' => $term->name, 'url' => get_term_link( $term ) );
            }
        } elseif ( is_post_type_archive() ) {
            $obj = get_queried_object();
            if ( $obj && isset( $obj->name ) ) {
                $items[] = array( 'name' => $obj->labels->name, 'url' => get_post_type_archive_link( $obj->name ) );
            }
        }

        $schema_items = array();
        $position = 1;
        foreach ( $items as $item ) {
            $entry = array(
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => $item['name'],
            );
            if ( ! empty( $item['url'] ) ) {
                $entry['item'] = $item['url'];
            }
            $schema_items[] = $entry;
            $position++;
        }

        $this->output_json_ld( array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $schema_items,
        ) );
    }

    private function output_article_schema() {
        $post = get_queried_object();
        if ( ! $post ) {
            return;
        }

        $thumb_id  = get_post_thumbnail_id( $post );
        $image_url = '';
        if ( $thumb_id ) {
            $img = wp_get_attachment_image_src( $thumb_id, 'full' );
            if ( $img ) {
                $image_url = $img[0];
            }
        }

        $data = array(
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'headline'         => get_the_title( $post ),
            'description'      => $this->get_meta_description(),
            'datePublished'    => get_the_date( 'c', $post ),
            'dateModified'     => get_post_modified_time( 'c', false, $post ),
            'author'           => array(
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name', $post->post_author ),
            ),
            'publisher'        => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'logo'  => array(
                    '@type' => 'ImageObject',
                    'url'   => $this->get_site_logo_url(),
                ),
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink( $post ),
            ),
        );

        if ( $image_url ) {
            $data['image'] = $image_url;
        }

        $this->output_json_ld( $data );
    }

    private function output_video_schema() {
        $post = get_queried_object();
        if ( ! $post ) {
            return;
        }

        $video_url = get_post_meta( $post->ID, '_psi_video_url', true );
        if ( ! $video_url ) {
            return;
        }

        $thumb_id  = get_post_thumbnail_id( $post );
        $thumb_url = '';
        if ( $thumb_id ) {
            $img = wp_get_attachment_image_src( $thumb_id, 'full' );
            if ( $img ) {
                $thumb_url = $img[0];
            }
        }

        $embed_url = '';
        if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $m ) ) {
            $embed_url = 'https://www.youtube.com/embed/' . $m[1];
        } elseif ( preg_match( '/vimeo\.com\/(\d+)/', $video_url, $m ) ) {
            $embed_url = 'https://player.vimeo.com/video/' . $m[1];
        }

        $data = array(
            '@context'     => 'https://schema.org',
            '@type'        => 'VideoObject',
            'name'         => get_the_title( $post ),
            'description'  => $this->get_meta_description(),
            'uploadDate'   => get_the_date( 'c', $post ),
            'thumbnailUrl' => $thumb_url,
        );

        if ( $embed_url ) {
            $data['contentUrl'] = $embed_url;
        }

        $this->output_json_ld( $data );
    }

    private function get_site_logo_url() {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $img = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            if ( $img ) {
                return $img[0];
            }
        }
        return '';
    }

    private function get_social_urls() {
        $urls = array();
        $keys = array( 'facebook', 'instagram', 'twitter', 'youtube', 'tiktok' );
        foreach ( $keys as $key ) {
            $url = dpw_psi_get( $key, '' );
            if ( $url && '#' !== $url ) {
                $urls[] = $url;
            }
        }
        return $urls;
    }
}
