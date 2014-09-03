<?php
/*
Plugin Name: Category Thumbnail Excerpt Read more List
Description: Creates a list of thumbnail images with title and excerpt, using the_post_thumbnail() in WordPress 2.9 and up.
Version: 1.0
Author: theajit
Author URI: http://theajit.in
*/
$categoryThumbnailList_Order = stripslashes( get_option( 'category-thumbnail-list_order' ) );
if ($categoryThumbnailList_Order == '') {
	$categoryThumbnailList_Order = 'date';
}
$categoryThumbnailList_OrderType = stripslashes( get_option( 'category-thumbnail-list_ordertype' ) );
if ($categoryThumbnailList_OrderType == '') {
	$categoryThumbnailList_OrderType = 'DESC';
}
    
$categoryThumbnailList_Path = get_option('siteurl')."/wp-content/plugins/categoy-thumbnail-list/";

define("categoryThumbnailList_REGEXP", "/\[getcatlist ([[:print:]]+)\]/");

define("categoryThumbnailList_TARGET", "###CATTHMBLST###");

function categoryThumbnailList_callback($listCatId) {
	global $post;
	global $categoryThumbnailList_Order;
	global $categoryThumbnailList_OrderType;
	$tmp_post = $post; 
	$myposts = get_posts('numberposts=-1&&category='.$listCatId[1].'&&orderby='.$categoryThumbnailList_OrderType.'&&order='.$categoryThumbnailList_Order);
	$output = '<div class="categoryThumbnailList">';
	foreach($myposts as $post) :
		setup_postdata($post);
		if ( has_post_thumbnail() ) {
		$link = get_permalink($post->ID);
		$thmb = get_the_post_thumbnail($post->ID,'thumbnail');
		$title = get_the_title();
		$my= get_the_excerpt();
		$output .= '<div class="categoryThumbnailList_item">';
			$output .= '<div class="figure">';
			$output .= '<a href="' .$link . '" title="' .$title . '">' .$thmb . '</a><br/>';
			$output .= '</div>';
			$output .= '<div class="title">';
			$output .= '<a href="' .$link . '" title="' .$title . '">' .$title . '</a><br/>';
			$output .= '</div>';
			$output .= '<div class="excerpt_container">';
			$output .= '' .$my . '<br />';
			$output .= '</div>';
			$output .= '<div class="button_container">';
			$output .='<a class="button fancy medium cta" href="'.$link.'">Learn more</a>';
			$output .= '</div>';
		$output .= '</div>';
		}
	endforeach;
	$output .= '</div>';
	$output .= '<div class="categoryThumbnailList_clearer"></div>';
	$post = $tmp_post;
	wp_reset_postdata();
	return ($output);
	$output = '';
}

function categoryThumbnailList($content) {
	return (preg_replace_callback(categoryThumbnailList_REGEXP, 'categoryThumbnailList_callback', $content));
}

function categoryThumbnailList_css() {
	global $categoryThumbnailList_Path;
	echo "
<style type=\"text/css\">
	@import url(\"".$categoryThumbnailList_Path."categoy-thumbnail-list.css\");
</style>
";
}
add_action('wp_head', 'categoryThumbnailList_css');
add_filter('the_content', 'categoryThumbnailList',1);
?>
<?php
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
  add_options_page('Category Thumbnail List Options', 'Category Thumbnail List', 'manage_options', 'category-thumbnail-list', 'my_plugin_options');
}

function my_plugin_options() {
	global $categoryThumbnailList_Order;
	global $categoryThumbnailList_OrderType;

	if( $_POST['save_category-thumbnail-list_settings'] ) {
        // update order type
        if( !$_POST['category-thumbnail-list_ordertype'] )
        {
            $_POST['category-thumbnail-list_ordertype'] = 'date';
        }
        update_option('category-thumbnail-list_ordertype', $_POST['category-thumbnail-list_ordertype'] );
        
        // update order
        if( !$_POST['category-thumbnail-list_order'] )
        {
            $_POST['category-thumbnail-list_order'] = 'DESC';
        }
        update_option('category-thumbnail-list_order', $_POST['category-thumbnail-list_order'] );
        
        $categoryThumbnailList_Order = stripslashes( get_option( 'category-thumbnail-list_order' ) );
	$categoryThumbnailList_OrderType = stripslashes( get_option( 'category-thumbnail-list_ordertype' ) );
	
	echo "<div id=\"message\" class=\"updated fade\"><p>Your settings are now updated</p></div>\n";
		
	}	
	?>
  <div class="wrap">
	<h2>Category Thumbnail List Settings</h2>
	<form method="post">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Order by</th>
				<td>
					<select name="category-thumbnail-list_ordertype" id="category-thumbnail-list_ordertype">
							<option <?php if ($categoryThumbnailList_OrderType == 'date') { echo 'selected="selected"'; } ?> value="date">Date</option>
							<option <?php if ($categoryThumbnailList_OrderType == 'title') { echo 'selected="selected"'; } ?> value="title">Title</option>
					</select>
				</td> 
			</tr>
			<tr valign="top">
				<th scope="row">Display order</th>
				<td>
					<select name="category-thumbnail-list_order" id="category-thumbnail-list_order">
							<option <?php if ($categoryThumbnailList_Order == 'DESC') { echo 'selected="selected"'; } ?> value="DESC">Descending (z-a/9-1/2010-2001)</option>
							<option <?php if ($categoryThumbnailList_Order == 'ASC') { echo 'selected="selected"'; } ?> value="ASC">Ascending (a-z/1-9/2001-2010)</option>
					</select>
				</td> 
			</tr>
		</table>

		<div class="submit">
			<!--<input type="submit" name="reset_category-thumbnail-list_settings" value="<?php _e('Reset') ?>" />-->
			<input type="submit" name="save_category-thumbnail-list_settings" value="<?php _e('Save Settings') ?>" class="button-primary" />
		</div>
		<div>
			<a href="options-media.php">Update the thumbnail sizes here</a>
		</div>
		<div>
			<a href="plugin-editor.php?file=categoy-thumbnail-list/categoy-thumbnail-list.css&plugin=categoy-thumbnail-list/categoy-thumbnail-list.php">You may need to update your css when changing the thumbnail size</a>
		</div>
		
	</form>
  </div>
<?php
}
?>
