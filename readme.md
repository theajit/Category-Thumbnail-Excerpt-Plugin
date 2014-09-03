
Lists a category with thumbnails,title,excerpt with a read more link

== Description ==

These must be in function.php in the theme for the plugin to work for thumbnail and excerpt:

add_theme_support( 'post-thumbnails' );

set_post_thumbnail_size( form_option('thumbnail_size_w&&echo=false'), form_option('thumbnail_size_h&&echo=false'), true );

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
function custom_excerpt_more( $more ) {
	return '';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

You'll also need to add thumbnails to the posts you want visible in the list.


After that, all you need to do is to add the hook [getcatlist 1] (where 1 is a categoy id) to your post.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload "categoy-thumbnail-list" to the "/wp-content/plugins/" directory
2. Add the following rows to your themes functions.php
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( form_option('thumbnail_size_w&&echo=false'), form_option('thumbnail_size_h&&echo=false'), true );
3. Activate the plugin through the "Plugins" menu in WordPress
4. Add the hook in a post. Example: [getcatlist 3]

== Frequently Asked Questions ==

None, yet.

== Screenshots ==

1. The plugin in action.

== Changelog ==

= 1.0 =
* Creation


