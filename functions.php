<?php

/**
* Thiết lập các hằng dữ liệu quan trọng
* THEME_URL = get_stylesheet_directory() - đường dẫn tới thư mục theme
* CORE = thư mục /core của theme, chứa các file nguồn quan trọng.
**/
define( 'THEME_URL', get_stylesheet_directory() );
define( 'CORE', THEME_URL . '/core' );

/**
* Load file /core/init.php
* Đây là file cấu hình ban đầu của theme mà sẽ không nên được thay đổi sau này.
**/

require_once( CORE . '/init.php' );

/**
* Thiết lập $content_width để khai báo kích thước chiều rộng của nội dung
**/
if ( ! isset( $content_width ) ) {
  /*
   * Nếu biến $content_width chưa có dữ liệu thì gán giá trị cho nó
   */
  $content_width = 620;
}

/**
* Thiết lập các chức năng sẽ được theme hỗ trợ
**/
if ( ! function_exists( 'oro_theme_setup' ) ) {
  /*
   * Nếu chưa có hàm oro_theme_setup() thì sẽ tạo mới hàm đó
   */
  function oro_theme_setup() {
    /*
    * Thiết lập theme có thể dịch được
    */
    $language_folder = THEME_URL . '/languages';
    load_theme_textdomain( 'oro', $language_folder );

    /*
    * Tự chèn RSS Feed links trong <head>
    */
    add_theme_support( 'automatic-feed-links' );

    /*
    * Thêm chức năng post thumbnail
    */
    add_theme_support( 'post-thumbnails' );

    /*
    * Thêm chức năng title-tag để tự thêm <title>
    */
    add_theme_support( 'title-tag' );

    /*
    * Thêm chức năng post format
    */
    add_theme_support( 'post-formats',
      array(
        'video',
        'image',
        'audio',
        'gallery'
      )
    );

    /*
    * Thêm chức năng custom background
    */
    $default_background = array(
      'default-color' => '#e8e8e8',
    );
    add_theme_support( 'custom-background', $default_background );

    /*
    * Tạo menu cho theme
    */
    register_nav_menu ( 'primary-menu', __('Primary Menu', 'oro') );

    /*
    * Tạo sidebar cho theme
    */
    $sidebar = array(
      'name' => __('Main Sidebar', 'oro'),
      'id' => 'main-sidebar',
      'description' => 'Main sidebar for oro theme',
      'class' => 'main-sidebar',
      'before_title' => '<h3 class="widgettitle">',
      'after_sidebar' => '</h3>'
    );
    register_sidebar( $sidebar );
  }
  add_action ( 'init', 'oro_theme_setup' );

}


function pressroom_taxonomy() {
  $labels = array(
    'name'      => 'Pressroom',
    'singular'  => 'News',
    'menu_name' => 'Category'
  );

  $args = array(
    'labels'            => $labels,
    'hierarchical'      => true,
    'pulic'             => true,
    'show_ui'           => true,
    'show_tagcloud'     => true,
    'query_var'		      => true,
    'show_in_nav_menus' => true,
    'rewrite'           => array( 'slug' => 'press-room' ),
  );

  register_taxonomy( 'pressroom_tax', 'pressroom_post', $args );
}

add_action( 'init', 'pressroom_taxonomy', 0 );


function pressroom_post() {

  $labels = array(
    'name' => 'Press room',
    'singular_name' => 'New'
  );

  $args = array(
    'labels' => $labels,
    'description' => 'Post type for Press room',
    'supports' => array(
      'title',
      'editor',
      'author',
      'thumbnail',
      'revisions',
      'custom-fields'
    ),
    'hierarchical' => false,
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menu' => true,
    'show_in_admin_bar' => true,
    'menu_position' => 7,
    'menu_icon' => 'dashicons-admin-post',
    'can_export' => true,
    'has_archive' => true,
    'exclude_from_search' => false,
    'publicly_queryable' => true,
    'capability_type' => 'post',
    'rewrite' => array( 'slug' => 'press-room/%category%', 'with_front' => false ),
    'query_var'		=> true
  );


  register_post_type( 'pressroom_post', $args );

}

add_action( 'init', 'pressroom_post' );




/* Filter modifies the permaling */

add_filter('post_link', 'category_permalink', 1, 3);
add_filter('post_type_link', 'category_permalink', 1, 3);

function category_permalink($permalink, $post_id, $leavename) {
	//con %category% catturo il rewrite del Custom Post Type
    if (strpos($permalink, '%category%') === FALSE) return $permalink;
        // Get post
        $post = get_post($post_id);
        if (!$post) return $permalink;

        // Get taxonomy terms
        $terms = wp_get_object_terms($post->ID, 'pressroom_tax');
        if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0]))
        	$taxonomy_slug = $terms[0]->slug;
        else $taxonomy_slug = 'no-category';

    return str_replace('%category%', $taxonomy_slug, $permalink);
}
