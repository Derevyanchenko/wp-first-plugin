<?php
/*
Plugin Name: DD Test Plugin
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: Краткое описание плагина.
Version: 1.0
Author: DD
Author URI: http://страница_автора_плагина
*/

class DD_Test
{
    public function __construct()
    {
        add_filter('acf/settings/path', array($this, 'my_acf_settings_path'));
        add_filter('acf/settings/dir', array($this, 'my_acf_settings_dir'));

        include_once(plugin_dir_path(__FILE__) . 'includes/advanced-custom-fields-pro/acf.php');
        include_once(plugin_dir_path(__FILE__) . 'includes/advanced-custom-fields-pro/acf-fields.php');

        // add_filter( 'acf/settings/show_admin', '__return_false' );

        $this->setup_options();

        acf_add_options_sub_page(array(
            'page_title'    => 'Quotes System Settings',
            'menu_title'    => 'Quotes System Settings',
            'parent_slug'   => 'edit.php?post_type=offers',
            'post_id'       => 'quotesystem_options',
        ));
    }

    public function my_acf_settings_path()
    {
        $path = plugin_dir_path(__FILE__) . 'includes/advanced-custom-fields-pro/';
        return $path;
    }

    public function my_acf_settings_dir($dir)
    {
        $dir = plugin_dir_url(__FILE__) . '/includes/advanced-custom-fields-pro/';
        return $dir;
    }

    public function setup_options()
    {

    }
}
new DD_Test();


add_action("init", "register_post_type_offers");

function register_post_type_offers()
{

    register_post_type('offers', array(
        'labels' => array(
            'name' => 'офферы', // Основное название типа записи
            'singular_name' => 'оффер', // отдельное название записи типа Book
            'add_new' => 'Добавить оффер',
            'add_new_item' => 'Добавить новый оффер',
            'edit_item' => 'Редактировать оффер',
            'new_item' => 'Новый оффер',
            'view_item' => 'Посмотреть оффер',
            'search_items' => 'Найти оффер',
            'not_found' => 'Оффер не найдено',
            'not_found_in_trash' => 'В корзине оффер не найдено',
            'parent_item_colon' => '',
            'menu_name' => 'Офферы'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'taxonomies' => array("filters"),
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'menu_icon' => 'dashicons-media-default',
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail')
    ));
}


function get_offers_function( $atts ) {

    if (!(is_admin()) ) {

        $atts = shortcode_atts( array(
            'count' => 3,
        ), $atts);

        $args = array(
            "post_type" => "offers",
            'numberposts' => $atts['count'],
            "suppress_filters" => true,
        );

        $posts = get_posts($args);
        $output = '<div class="dd_box_wrapper">';

        foreach($posts as $post) {
            setup_postdata($post);

           $output .=
               '<div class="dd_box_item">
                    <div class="dd_box_image">
                        '. get_the_post_thumbnail($post->ID) .'
                    </div>
                    <a href="'. get_the_permalink($post->ID) .'">'. get_the_title($post->ID) .'</a>
                    <p>'. get_the_content() .'</p>
               </div>';

        }
        $output .= '</div>';
        wp_reset_postdata();

        return $output;

    }

    return true;

}

add_shortcode("show_offers", "get_offers_function");

//  [show_offers count="1"]

function dd_styles()
{
    wp_enqueue_style('dd-styles', plugin_dir_url(__FILE__) . 'assets/style.css');
}

add_action("init", "dd_styles");




