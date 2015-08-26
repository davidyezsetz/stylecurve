<?php
/**
 * Render the blog layouts
 *
 * @author 		ThemeFusion
 * @package 	Avada/Templates
 * @version     1.0
 */
 
fusion_block_direct_access();

global $wp_query, $smof_data;

// Set the correct post container layout classes
$blog_layout = 	avada_get_blog_layout();
$post_class = sprintf( 'fusion-post-%s', $blog_layout );
if ( $blog_layout == 'grid' ) {
	$container_class = sprintf( 'fusion-blog-layout-%s fusion-blog-layout-%s-%s isotope ', $blog_layout, $blog_layout, $smof_data['blog_grid_columns'] );
} else {
	$container_class = sprintf( 'fusion-blog-layout-%s ', $blog_layout );
}

// Set class for scrolling type
if ( $smof_data['blog_pagination_type'] == 'Infinite Scroll' || 
	 $smof_data['blog_pagination_type'] == 'load_more_button'
) {
	$container_class .= 'fusion-blog-infinite fusion-posts-container-infinite ';
} else {
	$container_class .= 'fusion-blog-pagination ';
}

if ( ! $smof_data['featured_images'] ) {
	$container_class .= 'fusion-blog-no-images ';
}

// Add the timeline icon
if ( $blog_layout == 'timeline' ) {
	echo '<div class="fusion-timeline-icon"><i class="fusion-icon-bubbles"></i></div>';
}

if ( is_search() && 
	 $smof_data['search_results_per_page'] 
) {
	$number_of_pages = ceil( $wp_query->found_posts / $smof_data['search_results_per_page'] );
} else {
	$number_of_pages = $wp_query->max_num_pages;
}

// Add featured post
$feature_cat;
switch (ICL_LANGUAGE_CODE) {
  case 'de':
    $feature_cat = 'tag=featured';
    break;
  case 'fr':
    $feature_cat = 'tag=featured-fr';
    break;
  case 'en':
    $feature_cat = 'tag=featured-en';
    break;
}
if( is_home() ) {
	$feature_query = new WP_Query( $feature_cat );
	while ( $feature_query->have_posts() ): $feature_query->the_post();
		if($feature_query->current_post === 0) {
			echo '<section class="featured-post">';
				$featured_category_detail=get_the_category( get_the_ID() );//$post->ID
				$featured_category = "";
				foreach($featured_category_detail as $cd){
					$featured_category .= $cd->cat_name . " ";
				}
				$featured_category = str_replace('featured', '', $featured_category);
				echo '<div class="featured-post__content">';
					echo '<h2 class="feaured-post__subline">' . $featured_category . '</h2>';
					echo '<h1 class="featured-post__headline"><a href=" ' . get_permalink() . '">' . get_the_title() . '</a></h1>';
					echo '<p class="featured-post__excerpt">' . get_the_excerpt()  . '</p>';
					echo '<p class="featured-post__editor"><a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . __('Editor', 'stylecurve') . ': ' . get_the_author()  . '</a></p>';
				echo '</div>';
				echo get_the_post_thumbnail();
			echo '</section>';
		}
	endwhile; // end have_posts()
}
?>
<h1 class="blog-headline"><?php _e('Latest posts', 'stylecurve'); ?></h1> 
<?php
if( is_home() ) {
	// Add category nav
	echo '<nav class="category-nav__wrapper" aria-describedby="cat-nav"><ul class="category-nav">';
	echo '<h2 class="category-nav__headline" id="cat-nav">' . __('Filter by','stylecurve') . '</h2>';
	echo get_search_form( $echo );
	$cat_args = array('hide_empty' => 0);
	foreach(get_categories($cat_args) as $cat) {
	    if ($cat->cat_name == 'Allgemein' || $cat->cat_name == 'featured') {
	    } else {
		echo '<li class="category-nav__entry"><a class="category-nav__link" href="' . get_category_link($cat->term_id) . '">' . $cat->cat_name . '</a></li>';
	   }
	}
	echo '</ul></nav>';
}

echo sprintf( '<div id="posts-container" class="%sfusion-blog-archive fusion-clearfix" data-pages="%s">', $container_class, $number_of_pages );

	if( $blog_layout == 'timeline' ) {
		// Initialize the time stamps for timeline month/year check
		$post_count = 1;
		$prev_post_timestamp = null;
		$prev_post_month = null;
		$prev_post_year = null;
		$first_timeline_loop = false;
		
		// Add the container that holds the actual timeline line
		echo '<div class="fusion-timeline-line"></div>';
	}

	// Start the main loop
	//$new_query = new WP_Query( 'cat=-13,-14,-15' );
	while ( $wp_query->have_posts() ): $wp_query->the_post();
		// Set the time stamps for timeline month/year check
		$alignment_class = '';
		if( $blog_layout == 'timeline' ) {
			$post_timestamp = get_the_time( 'U' );
			$post_month = date( 'n', $post_timestamp );
			$post_year = get_the_date( 'o' );
			$current_date = get_the_date( 'o-n' );
			
			// Set the correct column class for every post
			if( $post_count % 2 ) {
				$alignment_class = 'fusion-left-column';
			} else {
				$alignment_class = 'fusion-right-column';
			}
	
			// Set the timeline month label
			if ( $prev_post_month != $post_month || 
				 $prev_post_year != $post_year 
			) {
			
				if( $post_count > 1 ) {
					echo '</div>';
				}
				echo sprintf( '<h3 class="fusion-timeline-date">%s</h3>', get_the_date( $smof_data['timeline_date_format'] ) ); 
				echo '<div class="fusion-collapse-month">';
			}
		}
		
		// Set the has-post-thumbnail if a video is used. This is needed if no featured image is present.
		$thumb_class = '';
		if ( get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
			$thumb_class = ' has-post-thumbnail';
		}
		
		$post_classes = sprintf( '%s %s %s post fusion-clearfix', $post_class, $alignment_class, $thumb_class ); 
		ob_start();
		post_class( $post_classes );
		$post_classes = ob_get_clean();
		
		echo sprintf( '<div id="post-%s" %s>', get_the_ID(), $post_classes );
			// Add an additional wrapper for grid layout border
			$category_detail=get_the_category( get_the_ID() );//$post->ID
			$categories = "blog-category ";
			foreach($category_detail as $cd){
				$categories .= $cd->slug . " ";
			}
			if ( $blog_layout == 'grid' ) {
				echo '<div class="fusion-post-wrapper '. $categories  .'">';
			}
			
				// Get featured images for all but large-alternate layout
				if ( $smof_data['featured_images'] && 
					 $blog_layout == 'large-alternate' 
				) {
					get_template_part( 'new-slideshow' );				
				}			

				// Get the post date and format box for alternate layouts
				if ( $blog_layout == 'large-alternate' || 
					 $blog_layout == 'medium-alternate' 
				) {
					echo '<div class="fusion-date-and-formats">';
					
						/**
						 * avada_blog_post_date_adn_format hook
						 *
						 * @hooked avada_render_blog_post_date - 10 (outputs the HTML for the date box)
						 * @hooked avada_render_blog_post_format - 15 (outputs the HTML for the post format box)
						 */						
						do_action( 'avada_blog_post_date_and_format' );	
					
					echo '</div>';
				}

				// Get featured images for all but large-alternate layout
				if ( $smof_data['featured_images'] &&
					 $blog_layout != 'large-alternate'
				) {
					get_template_part( 'new-slideshow' );
				}
				
				// post-content-wrapper only needed for grid and timeline
				if ( $blog_layout == 'grid' || 
					 $blog_layout == 'timeline' 
				) { 
					echo '<div class="fusion-post-content-wrapper">';
				}
				
					// Add the circles for timeline layout
					if ( $blog_layout == 'timeline' ) {
						echo '<div class="fusion-timeline-circle"></div>';
						echo '<div class="fusion-timeline-arrow"></div>';
					}
					
					echo '<div class="fusion-post-content">';
						// Render post meta for grid and timeline layouts
						if ( $blog_layout == 'grid' || 
							 $blog_layout == 'timeline'
						) {
							echo avada_render_post_metadata( 'grid_timeline' );

						// Render post meta for alternate layouts
						} elseif( $blog_layout == 'large-alternate' || 
								  $blog_layout == 'medium-alternate' 
						) {
							echo avada_render_post_metadata( 'alternate' );
						}
						
						echo '<div class="fusion-post-content-container">';

						// Render the post title
						echo avada_render_post_title( get_the_ID() );
					
						
							/**
							 * avada_blog_post_content hook
							 *
							 * @hooked avada_render_blog_post_content - 10 (outputs the post content wrapped with a container)
							 */						
							echo '<a href="' . get_permalink( get_the_ID() ) . '">';
							do_action( 'avada_blog_post_content' );
						     echo '</a>';	
							
						echo ' ></div>';
					echo '</div>'; // end post-content
					
					if( $blog_layout == 'medium' || 
						$blog_layout == 'medium-alternate' 
					) {
						echo '<div class="fusion-clearfix"></div>';
					}
					
					// Render post meta data according to layout
					if ( $smof_data['post_meta'] ) {
						echo '<div class="fusion-meta-info">';
							if ( $blog_layout == 'grid' || 
								 $blog_layout == 'timeline' 
							) {
								// Render read more for grid/timeline layouts
								echo '<div class="fusion-alignleft">';
									if ( ! $smof_data['post_meta_read'] ) {
										$link_target = '';
										if( fusion_get_page_option( 'link_icon_target', get_the_ID() ) == 'yes' ||
											fusion_get_page_option( 'post_links_target', get_the_ID() ) == 'yes' ) {
											$link_target = ' target="_blank"';
										}
										echo sprintf( '<a href="%s" class="fusion-read-more"%s>%s</a>', get_permalink(), $link_target, apply_filters( 'avada_blog_read_more_link', __( 'Read More', 'Avada' ) ) );
									}
								echo '</div>';
							
								// Render comments for grid/timeline layouts
								echo '<div class="fusion-alignright">';
									if ( ! $smof_data['post_meta_comments'] ) { 
										if( ! post_password_required( get_the_ID() ) ) {
											comments_popup_link('<i class="fusion-icon-bubbles"></i>&nbsp;' . __( '0', 'Avada' ), '<i class="fusion-icon-bubbles"></i>&nbsp;' . __( '1', 'Avada' ), '<i class="fusion-icon-bubbles"></i>&nbsp;' . '%' );
										} else {
											echo sprintf( '<i class="fusion-icon-bubbles"></i>&nbsp;%s', __( 'Protected', 'Avada' ) );
										}
									}
								echo '</div>';
							} else {
								// Render all meta data for medium and large layouts
								if ( $blog_layout == 'large' || $blog_layout == 'medium' ) {
									echo avada_render_post_metadata( 'standard' );
								}
								
								// Render read more for medium/large and medium/large alternate layouts
								echo '<div class="fusion-alignright">';
									if ( ! $smof_data['post_meta_read'] ) {
										$link_target = '';
										if( fusion_get_page_option( 'link_icon_target', get_the_ID() ) == 'yes' ||
											fusion_get_page_option( 'post_links_target', get_the_ID() ) == 'yes' ) {
											$link_target = ' target="_blank"';
										}
										echo sprintf( '<a href="%s" class="fusion-read-more"%s>%s</a>', get_permalink(), $link_target, __( 'Read More', 'Avada' ) );
									}
								echo '</div>';
							}
						echo '</div>'; // end meta-info
					}
				if ( $blog_layout == 'grid' || 
					 $blog_layout == 'timeline' 
				) { 					
					echo '</div>'; // end post-content-wrapper
				}
			if ( $blog_layout == 'grid' ) {
				echo '</div>'; // end post-wrapper
			}
		echo '</div>'; // end post
		
		// Adjust the timestamp settings for next loop
		if ( $blog_layout == 'timeline' ) {
			$prev_post_timestamp = $post_timestamp;
			$prev_post_month = $post_month;
			$prev_post_year = $post_year;
			$post_count++;
		}
	endwhile; // end have_posts()
	
	if ( $blog_layout == 'timeline' &&
		 $post_count > 1 
	) {
		echo '</div>';
	}
echo '</div>'; // end posts-container

// If infinite scroll with "load more" button is used
if ( $smof_data['blog_pagination_type'] == 'load_more_button' ) {
	echo sprintf( '<div class="fusion-load-more-button fusion-clearfix">%s</div>', __( 'Load More Posts', 'Avada' ) );
}

// Get the pagination
fusion_pagination( $pages = '', $range = 2 );

wp_reset_query();


