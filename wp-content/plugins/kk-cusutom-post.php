<?php
/*
Plugin Name: My Custom Post Types
Description: Add post types for movies and movie reviews
Author: Liam Carberry
*/
 
// Hook <strong>lc_custom_post_movie()</strong> to the init action hook
add_action( 'init', 'kk_alertHello' );
 
// The custom function to say hello

function kk_alertHello(){
    //echo "<script>alert('hello')</script>";
}

do_action('save_post', $post->ID, $post);


add_action('save_post', 'wporg_custom', 10, 2);

function wporg_custom($post_id, $post)
{
    echo $post->title;
    echo "<script>alert(".$post_id.")</script>";
}

?>




