<?php
add_action( 'wp_enqueue_scripts', 'my_child_theme_scripts' );
function my_child_theme_scripts() {
  wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
}

function remove_featured_post($qry) {
   if ( $qry->is_main_query() && is_home() ) {
     $qry->set('cat','-13,-14,-15');
   }
}
add_action('pre_get_posts','remove_featured_post');
