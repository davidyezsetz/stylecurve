<?php global $smof_data; ?>
<?php
$layout = '';
if(is_archive()) {
	$layout = $smof_data['blog_archive_layout'];
} elseif(is_search()) {

	if ( $smof_data['search_featured_images'] ) {
		return;
	}

	$layout = $smof_data['search_layout'];
} else {
	$layout = $smof_data['blog_layout'];
}
?>
<?php if($layout != 'Grid' && $layout != 'Timeline'): ?>
<style type="text/css">
<?php if(get_post_meta($post->ID, 'pyre_fimg_width', true) && get_post_meta($post->ID, 'pyre_fimg_width', true) != 'auto'): ?>
#post-<?php echo $post->ID; ?> .post-slideshow,
#post-<?php echo $post->ID; ?> .floated-post-slideshow
{max-width:<?php echo get_post_meta($post->ID, 'pyre_fimg_width', true); ?> !important;}
<?php endif; ?>

<?php if(get_post_meta($post->ID, 'pyre_fimg_height', true) && get_post_meta($post->ID, 'pyre_fimg_height', true) != 'auto'): ?>
#post-<?php echo $post->ID; ?> .post-slideshow,
#post-<?php echo $post->ID; ?> .floated-post-slideshow,
#post-<?php echo $post->ID; ?> .post-slideshow .image > img,
#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > img,
#post-<?php echo $post->ID; ?> .post-slideshow .image > a > img,
#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > a > img
{height:<?php echo get_post_meta($post->ID, 'pyre_fimg_height', true); ?> !important;}
<?php endif; ?>

<?php if(get_post_meta($post->ID, 'pyre_fimg_width', true) && get_post_meta($post->ID, 'pyre_fimg_width', true) == 'auto'): ?>
#post-<?php echo $post->ID; ?> .post-slideshow .image > img,
#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > img,
#post-<?php echo $post->ID; ?> .post-slideshow .image > a > img,
#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > a > img{
	width:auto;
}
<?php endif; ?>

<?php if(get_post_meta($post->ID, 'pyre_fimg_height', true) && get_post_meta($post->ID, 'pyre_fimg_height', true) == 'auto'): ?>
#post-<?php echo $post->ID; ?> .post-slideshow .image > img,
#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > img,
#post-<?php echo $post->ID; ?> .post-slideshow .image > a > img,
#post-<?php echo $post->ID; ?> .floated-post-slideshow .image > a > img{
	height:auto;
}
