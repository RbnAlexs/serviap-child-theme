<?php

/** Minify HTML */

class FLHM_HTML_Compression
{
    protected $flhm_compress_css = true;
    protected $flhm_compress_js = true;
    protected $flhm_info_comment = true;
    protected $flhm_remove_comments = true;
    protected $html;
    public function __construct($html)
    {
        if (!empty($html))
        {
            $this->flhm_parseHTML($html);
        }
    }
    public function __toString()
    {
        return $this->html;
    }
    protected function flhm_bottomComment($raw, $compressed)
    {
        $raw = strlen($raw);
        $compressed = strlen($compressed);
        $savings = ($raw - $compressed) / $raw * 100;
        $savings = round($savings, 2);
        return '<!--Copyright © 2022 Serviap Global. All Rights Reserved.-->';
    }
    protected function flhm_minifyHTML($html)
    {
        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding = false;
        $raw_tag = false;
        $html = '';
        foreach ($matches as $token)
        {
            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
            $content = $token[0];
            if (is_null($tag))
            {
                if (!empty($token['script']))
                {
                    $strip = $this->flhm_compress_js;
                }
                else if (!empty($token['style']))
                {
                    $strip = $this->flhm_compress_css;
                }
                else if ($content == '<!--wp-html-compression no compression-->')
                {
                    $overriding = !$overriding;
                    continue;
                }
                else if ($this->flhm_remove_comments)
                {
                    if (!$overriding && $raw_tag != 'textarea')
                    {
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            }
            else
            {
                if ($tag == 'pre' || $tag == 'textarea')
                {
                    $raw_tag = $tag;
                }
                else if ($tag == '/pre' || $tag == '/textarea')
                {
                    $raw_tag = false;
                }
                else
                {
                    if ($raw_tag || $overriding)
                    {
                        $strip = false;
                    }
                    else
                    {
                        $strip = true;
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }
            if ($strip)
            {
                $content = $this->flhm_removeWhiteSpace($content);
            }
            $html .= $content;
        }
        return $html;
    }
    public function flhm_parseHTML($html)
    {
        $this->html = $this->flhm_minifyHTML($html);
        if ($this->flhm_info_comment)
        {
            $this->html .= "\n" . $this->flhm_bottomComment($html, $this->html);
        }
    }
    protected function flhm_removeWhiteSpace($str)
    {
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n", '', $str);
        $str = str_replace("\r", '', $str);
        $str = str_replace("// The customizer requires postMessage and CORS (if the site is cross domain).", '', $str);
        while (stristr($str, '  '))
        {
            $str = str_replace('  ', ' ', $str);
        }
        return $str;
    }
}
function flhm_wp_html_compression_finish($html)
{
    return new FLHM_HTML_Compression($html);
}
function flhm_wp_html_compression_start()
{
    ob_start('flhm_wp_html_compression_finish');
}
add_action('get_header', 'flhm_wp_html_compression_start');

/* Current tags from category (shortcode) 

function get_tags_in_use($category_ID, $type = 'name'){
    // Set up the query for our posts
    $my_posts = new WP_Query(array(
      'cat' => $category_ID, // Your category id
      'posts_per_page' => -1 // All posts from that category
    ));

    // Initialize our tag arrays
    $tags_by_id = array();
    $tags_by_name = array();
    $tags_by_slug = array();

    // If there are posts in this category, loop through them
    if ($my_posts->have_posts()): while ($my_posts->have_posts()): $my_posts->the_post();

      // Get all tags of current post
      $post_tags = wp_get_post_tags($my_posts->post->ID);

      // Loop through each tag
      foreach ($post_tags as $tag):

        // Set up our tags by id, name, and/or slug
        $tag_id = $tag->term_id;
        $tag_name = $tag->name;
        $tag_slug = $tag->slug;

        // Push each tag into our main array if not already in it
        if (!in_array($tag_id, $tags_by_id))
          array_push($tags_by_id, $tag_id);

        if (!in_array($tag_name, $tags_by_name))
          array_push($tags_by_name, $tag_name);

        if (!in_array($tag_slug, $tags_by_slug))
          array_push($tags_by_slug, $tag_slug);

      endforeach;
    endwhile; endif;

    // Return value specified
    if ($type == 'id')
        return $tags_by_id;

    if ($type == 'name')
        return $tags_by_name;

    if ($type == 'slug')
        return $tags_by_slug;
}

function tag_cloud_by_category($atts){
	ob_start();
    // Get our tag array
	
	$category_ID = shortcode_atts(array( 'cat_id' => ' '), $atts);

	$tags = get_tags_in_use($category_ID['cat_id'], 'id');

    // Start our output variable
    echo '<div class="tag-cloud">';

    // Cycle through each tag and set it up
    foreach ($tags as $tag):
        // Get our count
        $term = get_term_by('id', $tag, 'post_tag');
        $count = $term->count;

        // Get tag name
        $tag_info = get_tag($tag);
        $tag_name = $tag_info->name;

        // Get tag link
        $tag_link = get_tag_link($tag);

        // Set up our font size based on count
        $size = 8 + $count;

        echo '<span style="font-size:'.$size.'px;">';
        echo '<a href="'.$tag_link.'">'.$tag_name.'</a>';
        echo ' </span>';

    endforeach;

    echo '</div>';

    return ob_get_clean();
}

add_shortcode ('etiquetas-cat','tag_cloud_by_category');*/

/**
 * This function modifies the main WordPress query to remove 
 * pages from search results.
 *
 * @param object $query The main WordPress query.
 */
function tg_exclude_pages_from_search_results( $query ) {
    if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
        $query->set( 'post_type', array( 'post' ) );
    }    
}
add_action( 'pre_get_posts', 'tg_exclude_pages_from_search_results' );


/**
 * Featured image to RSS Feed.
 */

function featuredtoRSS($content) {
    global $post;
    if ( has_post_thumbnail( $post->ID ) ){
    $content = '<div>' . get_the_post_thumbnail( $post->ID, 'medium', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
    }
    return $content;
    }
    
    add_filter('the_excerpt_rss', 'featuredtoRSS');
    add_filter('the_content_feed', 'featuredtoRSS');

/* ----------------------------------------*
 * GENERAL *
 *----------------------------------------*/ 

function page_tagcat_settings() {
    // Add tag metabox to page
     register_taxonomy_for_object_type('post_tag', 'page');
    // Add category metabox to page
    //register_taxonomy_for_object_type('category', 'page');
}
// Add to the admin_init hook of your theme functions.php file
add_action( 'init', 'page_tagcat_settings' );

// Crear child theme y definir CSS dir 
function serviap_child_enqueue_styles() {
	$parent_style = 'hello-elementor';
    wp_enqueue_style( $parent_style, get_template_directory_uri().'/style.css' );
	//Flags conuntries
	wp_enqueue_style( 'flags', get_stylesheet_directory_uri().'/css/flag-icons.min.css');
    wp_enqueue_style( 'serviap-child-style',
        get_stylesheet_directory_uri().'/css/style.min.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'serviap_child_enqueue_styles' );



// Remover logo Wordpress de admin 
function example_admin_bar_remove_logo() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action( 'wp_before_admin_bar_render', 'example_admin_bar_remove_logo', 0 );

// Ocultar versión de Wordpress
function wpbeginner_remove_version() {
    return '';
    }
add_filter('the_generator', 'wpbeginner_remove_version');

// Remover acentos en los archivos nuevos
function wpartisan_sanitize_file_name( $filename ) {
    $sanitized_filename = remove_accents( $filename ); // Convert to ASCII
    // Standard replacements
    $invalid = array(
        ' '   => '-',
        '%20' => '-',
        '_'   => '-',
    );
    $sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );
    $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
    $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
    $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
    $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
    $sanitized_filename = strtolower( $sanitized_filename ); // Lowercase
    return $sanitized_filename;
} 
add_filter( 'sanitize_file_name', 'wpartisan_sanitize_file_name', 10, 1 );

// Cambiar styles del login de Wordpress 
function style_personalizado() { ?> 
    <style type="text/css"> 
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri()?>/images/logo-b.png);
            width:320px;
            height:145px !important; 
            background-size: contain;
        }
        .wp-core-ui .button-primary {
            background: #0d6cbc !important;
            border-color: #a29475 !important;
        }
        #backtoblog, .message{
            display: none
        }
        .login #nav {
            margin: 10px 0 0 0;
            text-align: center;
        }
        input[type=checkbox]:focus, input[type=color]:focus, input[type=date]:focus, input[type=datetime-local]:focus, input[type=datetime]:focus, input[type=email]:focus, input[type=month]:focus, input[type=number]:focus, input[type=password]:focus, input[type=radio]:focus, input[type=search]:focus, input[type=tel]:focus, input[type=text]:focus, input[type=time]:focus, input[type=url]:focus, input[type=week]:focus, select:focus, textarea:focus {
            border-color: #00468b !important;
            box-shadow: 0 0 0 1px #00468b !important;
        }
        
    </style>
     <?php 
    } 
add_action( 'login_enqueue_scripts', 'style_personalizado' );

// Disable emoji js
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/*Remover Default Wordpress JQuery en el Front
function my_jquery_enqueue() {
    wp_deregister_script('jquery');
 }
 if (!is_admin()) add_action("wp_enqueue_scripts", "my_jquery_enqueue", 11);
 */
 //Librería JS personalizada
 function scripts_personalizados () {
     wp_enqueue_script('scripts_personalizados_js', get_stylesheet_directory_uri() . '/js/front-custom.js', array('jquery'));
 }
 add_action('wp_enqueue_scripts', 'scripts_personalizados');

//Desabilitar comentarios
add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page and option page in menu 
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
});

// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

// Desabilitar comentarios globalmente en media
function filter_media_comment_status( $open, $post_id ) {
    $post = get_post( $post_id );
    if( $post->post_type == 'attachment' ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );

// Remove Lost Password Link
function vpsb_remove_lostpassword_text ( $text ) {
    if ($text == 'Lost your password?'){$text = '';}
           return $text;
    }
add_filter( 'gettext', 'vpsb_remove_lostpassword_text' );


//Desactivar actualizaciones automaticas de plugins
add_filter( 'auto_update_plugin', '__return_false' );
?>
