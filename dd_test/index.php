<?php
/*
Plugin Name: DD Test Plugin
Plugin URI: http://страница_с_описанием_плагина_и_его_обновлений
Description: Краткое описание плагина.
Version: 1.0
Author: DD
Author URI: http://страница_автора_плагина
*/

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
        'supports' => array('title', 'editor')
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
        $output = '<ul>';

        foreach($posts as $post) {
            setup_postdata($post);

           $output .=
               '<div>
                    <a href="'. get_the_permalink($post->ID) .'">'. get_the_title($post->ID) .'</a>
                    <div>'. get_the_content() .'</div>
               </div>';

        }
        $output .= '</ul>';
        wp_reset_postdata();

        return $output;

    }

    return true;

}

add_shortcode("show_offers", "get_offers_function");

//  [show_offers count="1"]





