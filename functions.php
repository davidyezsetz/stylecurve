<?php 
//add_action( 'wp_enqueue_scripts', 'my_child_theme_scripts' );
function my_child_theme_scripts() {
    wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
}


function remove_featured_post($qry) {
   if ( $qry->is_main_query() && is_home() ) {
     $qry->set('tag__not_in','59, 60, 61');
   }
}
add_action('pre_get_posts','remove_featured_post');
function fb_add_custom_user_profile_fields( $user ) {
?>
	<h3><?php _e('Extra Profile Information', 'stylecurve'); ?></h3>
	
	<table class="form-table">
		<tr>
			<th>
				<label for="indispensable"><?php _e('Indispensable', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="indispensable" id="indispensable" value="<?php echo esc_attr( get_the_author_meta( 'indispensable', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('An item that is indispensable.', 'stylecurve'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="fashion_icon"><?php _e('Fashion icon', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="fashion_icon" id="fashion_icon" value="<?php echo esc_attr( get_the_author_meta( 'fashion_icon', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Your fashion icons.', 'stylecurve'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="favourite_drink"><?php _e('Favourite drink', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="favourite_drink" id="favourite_drink" value="<?php echo esc_attr( get_the_author_meta( 'favourite_drink', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Your favourite drink.', 'stylecurve'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="favourite_movie"><?php _e('Favourite movie', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="favourite_movie" id="favourite_movie" value="<?php echo esc_attr( get_the_author_meta( 'favourite_movie', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Your favourite movie.', 'stylecurve'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="job"><?php _e('Best part about my job', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="job" id="job" value="<?php echo esc_attr( get_the_author_meta( 'job', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('The best part about your job.', 'stylecurve'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="quote"><?php _e('Quote', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="quote" id="quote" value="<?php echo esc_attr( get_the_author_meta( 'quote', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('A favourite quote or one form yourself.', 'stylecurve'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="quote_author"><?php _e('Quote author', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="quote_author" id="quote_author" value="<?php echo esc_attr( get_the_author_meta( 'quote_author', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('The author of the quote', 'stylecurve'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="spotify"><?php _e('Spotify playlist', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="spotify" id="spotify" value="<?php echo esc_attr( get_the_author_meta( 'spotify', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Spotify playlist url', 'stylecurve'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="pinterest"><?php _e('Pinterst Board', 'stylecurve'); ?>
			</label></th>
			<td>
				<input type="text" name="pinterest" id="pinterest" value="<?php echo esc_attr( get_the_author_meta( 'pinterest', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Pinterest board url', 'stylecurve'); ?></span>
			</td>
		</tr>
	</table>
<?php }
function fb_save_custom_user_profile_fields( $user_id ) {
	
	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;
	
	update_usermeta( $user_id, 'indispensable', $_POST['indispensable'] );
	update_usermeta( $user_id, 'fashion_icon', $_POST['fashion_icon'] );
	update_usermeta( $user_id, 'favourite_drink', $_POST['favourite_drink'] );
	update_usermeta( $user_id, 'favourite_movie', $_POST['favourite_movie'] );
	update_usermeta( $user_id, 'job', $_POST['job'] );
	update_usermeta( $user_id, 'quote', $_POST['quote'] );
	update_usermeta( $user_id, 'quote_author', $_POST['quote_author'] );
	update_usermeta( $user_id, 'spotify', $_POST['spotify'] );
	update_usermeta( $user_id, 'pinterest', $_POST['pinterest'] );
}

add_action( 'show_user_profile', 'fb_add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'fb_add_custom_user_profile_fields' );

add_action( 'personal_options_update', 'fb_save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'fb_save_custom_user_profile_fields' );
