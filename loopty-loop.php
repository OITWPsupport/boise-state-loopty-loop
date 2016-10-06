<?php
/*
Plugin Name: Boise State Loopty Loop
Description: Loopty Loop Plugin
Version: 1.0.1
Author: Matt Berg, David Lentz
 */

defined( 'ABSPATH' ) or die( 'No hackers' );
if( ! class_exists( 'Boise_State_Loopty_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
}
$updater = new Boise_State_Plugin_Updater( __FILE__ );
$updater->set_username( 'OITWPsupport' );
$updater->set_repository( 'boise-state-loopty-loop' );
$updater->initialize();

add_shortcode('loopty_loop', function($params, $content){
    $query = new WP_Query([
        'cat'            => isset($params['categories']) ? $params['categories'] : null,
        'posts_per_page' => isset($params['max_posts']) ? $params['max_posts'] : null,
        'post_type'      => isset($params['post_type']) ? $params['post_type'] : null,
        'orderby'        => isset($params['orderby']) ? $params['orderby'] : 'menu_order',
        'order'          => isset($params['reverse']) ? $params['reverse'] : null
    ]);
    $output = '';
    while ($query->have_posts()) {
        $query->the_post();
        $output .= do_shortcode($content);
    }

    return $output;
});

add_shortcode('loopty_loop_link', function ($params, $content) {
    return '<a href="' . get_permalink() . '">' . do_shortcode($content) . '</a>';
});

add_shortcode('loopty_loop_title', function () {
    return get_the_title();
});

add_shortcode('loopty_loop_content', function ($params) {
    $content = get_the_content();
    if (isset($params['limit']) && strlen($content) > $params['limit']) {
        $readMoreText = isset($params['link_text']) ? $params['link_text'] : 'Read more <em>' . get_the_title() . '</em>';
        $link = '<a href="' . get_permalink() . '">' . $readMoreText . '</a>';
        // Get full words, approximating limit
        $content = explode("\n", wordwrap($content, $params['limit'], "\n"))[0] . "&hellip; {$link}";
    }
    return $content;
});

add_shortcode('loopty_loop_image', function($params){
    $size = 'full';
    if(isset($params['size'])){
        $size = $params['size'];
    }
    return get_the_post_thumbnail(null, $size);
});
