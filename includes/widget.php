<?php 
/**
 * Lemongrid Widget
 * Author: BEARS Themes
 * Author Url: http://themebears.com
 */

add_action( 'widgets_init', 'register_lemongrid_widget' );

/**
 * register_lemongrid_widget
 */
function register_lemongrid_widget()
{
	register_widget( 'lemongrid_widget' );
}

/**
 * Class lemongrid_widget
 */
class lemongrid_widget extends WP_Widget 
{
	public function __construct() {

		parent::__construct( 'lemongrid_widget', __( 'Lemon Grid', TBLG_NAME ), array(
				'classname'   => 'lemongrid_widget',
				'description' => __( 'A grid use for Post, Gallery, Social (Instagram, Flickr)', TBLG_NAME ),
				) );
	}

	/**
	 * widget
	 *
	 * @param array $args
	 * @param array $instance
	 * @return HTML
	 */
	public function widget( $args, $instance )
	{
		extract( $args );

		switch ( $instance['type'] ) {
			case 'social':
				
				/* Social */
				if( $instance['social'] == 'instagram' ) :
					require_once TBLG_INCLUDES . 'socials/instagram.class.php';
					$insta = new LG_Instagram();
					$insta->username = $instance['username'];
					$insta->client_id = $instance['apikey']; // '2a87113cbe65405aa10b491fc6e39242';
					$insta->slice = (int) $instance['count'];
					$media = $insta->getMedia();
				elseif( $instance['social'] == 'flickr' ) :
					require_once TBLG_INCLUDES . 'socials/flickr.class.php';
					$flickr = new LG_Flickr();
					$flickr->username = $instance['username'];
					$flickr->key = $instance['apikey']; // 'f668d07759169ca3db29e9a60bff128d';
					$flickr->slice = (int) $instance['count'];
					$media = $flickr->getMedia();
				endif;

				$instance['media'] = ( isset( $media ) ) ? $media : array();
				break;
			
			case 'gallery':
				# code...
				break;

			default: /* POST */
				# code...
				break;
		}

		$instance['element_id'] = $instance['wg_id'];
		$instance['class_id'] = 'lemon_grid_id_' . $instance['element_id'];

		/**
		 * Enqueue script
		 */
		// LemonGrid::include_script();

		/**
		 * Lib JS Imagelightbox
		 */
		wp_enqueue_script( 'imagelightbox', TBLG_JS . 'imagelightbox.min.js', array( 'jquery' ), '1.0.0', true );

		do_action( 'tblg_include_script_inline', renderGridCustomSpaceCss( $instance['class_id'], $instance['space'] ) );
		
		$content =  lgLoadTemplate( $instance );

		echo sprintf( '
			%s %s %s', 
			$before_widget, 
			$content, 
			$after_widget );
	}

	/**
	 * update
	 *
	 * @param array $new_instance New widget instance.
	 * @param array $instance     Original widget instance.
	 * @return array Updated widget instance.
	 */
	function update( $new_instance, $old_instance ) 
	{
	    $instance = $old_instance;
	 
	    //Strip tags from title and name to remove HTML
	    $instance['wg_id']  		= strip_tags( $new_instance['wg_id'] );
	    $instance['title']  		= strip_tags( $new_instance['title'] );
	    $instance['type']  			= strip_tags( $new_instance['type'] );
	    $instance['social']  		= strip_tags( $new_instance['social'] );
	    $instance['username']  		= strip_tags( $new_instance['username'] );
	    $instance['apikey']  		= strip_tags( $new_instance['apikey'] );
	    $instance['count']  		= strip_tags( $new_instance['count'] );
	    $instance['media_ids']  	= strip_tags( $new_instance['media_ids'] );
	    $instance['cell_height']	= strip_tags( $new_instance['cell_height'] );
	    $instance['space'] 	 		= strip_tags( $new_instance['space'] );
	    $instance['template'] 		= strip_tags( $new_instance['template'] );
	    $instance['extra_class'] 	= strip_tags( $new_instance['extra_class'] );
	 
	    return $instance;
	}

	/**
	 * form
	 *
	 * @param array $instance
	 */
	function form( $instance ) {
		$wg_id  		= empty( $instance['wg_id'] ) ? date( 'Ymd_' ) . rand( 1, 99999 ) : esc_attr( $instance['wg_id'] );
		$title  		= empty( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$type  			= empty( $instance['type'] ) ? 'social' : esc_attr( $instance['type'] );
		$social  		= empty( $instance['social'] ) ? 'instagram' : esc_attr( $instance['social'] );
		$username  		= empty( $instance['username'] ) ? '' : esc_attr( $instance['username'] );
		$apikey  		= empty( $instance['apikey'] ) ? '' : esc_attr( $instance['apikey'] );
		$count  		= empty( $instance['count'] ) ? 9 : esc_attr( $instance['count'] );
		$media_ids  	= empty( $instance['media_ids'] ) ? '' : esc_attr( $instance['media_ids'] );
		$cell_height	= empty( $instance['cell_height'] ) ? 30 : esc_attr( $instance['cell_height'] );
		$space 			= empty( $instance['space'] ) ? 5 : esc_attr( $instance['space'] );
		$template 		= empty( $instance['template'] ) ? '' : esc_attr( $instance['template'] );
		$extra_class 	= empty( $instance['extra_class'] ) ? '' : esc_attr( $instance['extra_class'] );
 
		$types = array( 
			'social' => __( 'Social', TBLG_NAME ),
			// 'post' => __( 'Post', TBLG_NAME ),
			'gallery' => __( 'Gallery', TBLG_NAME ) );

		$socials = array(
			'instagram' => __( 'Instagram', TBLG_NAME ),
			'flickr' => __( 'Flickr', TBLG_NAME ) );

		$templates = lg_widget_templates();

		ob_start();
		?>
		<!-- wg_id -->
		<p>
			<label for="<?php esc_attr_e( $this->get_field_id( 'wg_id' ) ); ?>"><?php _e( 'Widget ID:', TBLG_NAME ); ?></label>
			<input id="<?php esc_attr_e( $this->get_field_id( 'wg_id' ) ); ?>" class="widefat" name="<?php esc_attr_e( $this->get_field_name( 'wg_id' ) ); ?>" type="text" value="<?php esc_attr_e( $wg_id ); ?>">
		</p>
		<!-- title -->
		<p>
			<label for="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', TBLG_NAME ); ?></label>
			<input id="<?php esc_attr_e( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php esc_attr_e( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php esc_attr_e( $title ); ?>">
		</p>
		<!-- Type -->
		<p>
			<label for="<?php esc_attr_e( $this->get_field_id( 'type' ) ); ?>"><?php _e( 'Type:', TBLG_NAME ); ?></label>
			<select data-widget-switch-group id="<?php esc_attr_e( $this->get_field_id( 'type' ) ); ?>" class="widefat" name="<?php esc_attr_e( $this->get_field_name( 'type' ) ); ?>">
				<?php foreach( $types as $type_name => $type_text ) :
					$selected = ( $type == $type_name ) ? 'selected' : '';
					echo sprintf( '<option value="%s" %s>%s</option>', $type_name, $selected, $type_text );
				endforeach; ?>
			</select>
		</p>
		<!-- Group field filter field type -->
		<?php foreach( $types as $type_name => $type_text ) :
			$display = ( $type == $type_name ) ? 'block' : 'none';
			echo '<div style="display: '. $display .'" data-group="'. esc_attr( $type_name ) .'" class="lg-group-field group-type-'. esc_attr( $type_name ) .'">';
			switch( $type_name ) {
				case 'social' :
					$social_html = '';
					foreach( $socials as $social_name => $social_text ) :
						$checked = ( $social == $social_name ) ? 'selected' : '';
						$social_html .= '<option value="'.$social_name .'" '. $checked .'>'. $social_text .'</option>';
					endforeach;

					/* social  */
					echo sprintf( '
						<p>
							<label for="%s">%s</label>
							<select class="widefat" name="%s">%s</select>
						</p>', 
						$this->get_field_id( 'social' ), 
						__( 'Social', TBLG_NAME ), 
						$this->get_field_name( 'social' ), 
						$social_html );
					
					/* username */
					echo sprintf( '
						<p>
							<label for="%s">%s</label>
							<input id="%s" type="text" name="%s" value="%s" class="widefat" />
						</p>', 
						$this->get_field_id( 'username' ), 
						__( 'Username', TBLG_NAME ), 
						$this->get_field_id( 'username' ), 
						$this->get_field_name( 'username' ), 
						$username );

					/* apikey */
					echo sprintf( '
						<p>
							<label for="%s">%s</label>
							<input id="%s" type="text" name="%s" value="%s" class="widefat" />
						</p>', 
						$this->get_field_id( 'apikey' ), 
						__( 'API Key', TBLG_NAME ), 
						$this->get_field_id( 'apikey' ), 
						esc_attr( $this->get_field_name( 'apikey' ) ), 
						$apikey );

					/* count */
					echo sprintf( '
						<p>
							<label for="%s">%s</label>
							<input id="%s" type="text" name="%s" value="%s" class="widefat" />
						</p>', 
						$this->get_field_id( 'count' ), 
						__( 'Count', TBLG_NAME ), 
						$this->get_field_id( 'count' ), 
						$this->get_field_name( 'count' ), 
						$count );
					break;

				case 'post' :

					echo sprintf( '<p>%s</p>', __( 'Coming soon...!', TBLG_NAME ) );
					break;

				case 'gallery':

					/* media ids */
					echo sprintf( '
						<p>
							<label for="%s">%s</label>
							<input id="%s" type="text" name="%s" value="%s" class="widefat" />
							<small>Ex: 1,2,3,5</small>
						</p>', 
						$this->get_field_id( 'media_ids' ), 
						__( 'Media IDs', TBLG_NAME ), 
						$this->get_field_id( 'media_ids' ), 
						$this->get_field_name( 'media_ids' ), 
						$media_ids );
					break;
			}
			echo '</div>';
		endforeach; ?>
		<!-- Cell height -->
		<p>
			<label for="<?php esc_attr_e( $this->get_field_id( 'cell_height' ) ); ?>"><?php _e( 'Cell Height:', TBLG_NAME ); ?></label>
			<input id="<?php esc_attr_e( $this->get_field_id( 'cell_height' ) ); ?>" class="widefat" name="<?php esc_attr_e( $this->get_field_name( 'cell_height' ) ); ?>" type="text" value="<?php esc_attr_e( $cell_height ); ?>">
		</p>
		<!-- Space -->
		<p>
			<label for="<?php esc_attr_e( $this->get_field_id( 'space' ) ); ?>"><?php _e( 'Space:', TBLG_NAME ); ?></label>
			<input id="<?php esc_attr_e( $this->get_field_id( 'space' ) ); ?>" class="widefat" name="<?php esc_attr_e( $this->get_field_name( 'space' ) ); ?>" type="text" value="<?php esc_attr_e( $space ); ?>">
		</p>
		<!-- Template -->
		<p>
			<label for="<?php esc_attr_e( $this->get_field_id( 'template' ) ); ?>"><?php _e( 'Template:', TBLG_NAME ); ?></label>
			<select id="<?php esc_attr_e( $this->get_field_id( 'template' ) ); ?>" class="widefat" name="<?php esc_attr_e( $this->get_field_name( 'template' ) ); ?>">
				<?php foreach ( $templates as $name_file => $dir_file ) :
			    	$selected = ( $name_file == esc_attr( $template ) ) ? 'selected' : '';
			        echo sprintf( '<option value="%s" %s>%s</option>', $name_file, $selected, $name_file );
			    endforeach; ?>
			</select>
		</p>
		<!-- Extra class -->
		<p>
			<label for="<?php esc_attr_e( $this->get_field_id( 'extra_class' ) ); ?>"><?php _e( 'Extra Class:', TBLG_NAME ); ?></label>
			<input id="<?php esc_attr_e( $this->get_field_id( 'extra_class' ) ); ?>" class="widefat" name="<?php esc_attr_e( $this->get_field_name( 'extra_class' ) ); ?>" type="text" value="<?php esc_attr_e( $extra_class ); ?>">
		</p>
		<?php
		echo ob_get_clean();
	}
}