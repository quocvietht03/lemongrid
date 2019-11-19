<?php 
/**
 * Layout Name: Creative
 * Thumbnail: #
 * Author: BEARS Theme
 * Author URI: http://themebears.com
 * Param: lgPostLemongridLayout2Params
 * Description: #
 */

/* variable */
list( $template_name ) = explode( '.', $atts['template'] );
$lemongrid_options = json_encode( array(
	'cell_height'		=> (int) $atts['cell_height'],
	'vertical_margin'	=> (int) $atts['space'],
	'animate'			=> true,
	) );

/**
 * lgItemPostTemp
 *
 * @param array $atts
 * @return HTML
 */
if( ! function_exists( 'lgItemPostTemp2' ) ) :
	function lgItemPostTemp2( $atts )
	{
		$output = '';
		// $grid = lgGetLayoutLemonGridPerPage( get_the_ID(), $atts['element_id'], count( $atts['posts']->posts ) );
		$grid = lbGetLemonGridLayouts( $atts['element_id'], count( $atts['posts']->posts ) ); /* v1.1 */
		$posts = $atts['posts'];
		$k = 0;

		while( $posts->have_posts() ) : 
			$posts->the_post();

			if( has_post_thumbnail() ):
                $thumbnail_data = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
            	$thumbnail = $thumbnail_data[0];
            else:
                $thumbnail = '';
            endif;
			$style = implode( ';', array( 
				"background: url({$thumbnail}) no-repeat center center / cover, #333", 
				) );

			/**
			 * title, cat
			 */
			$_title = sprintf( '<a href="%s"><h2 class=\'title\' title=\'%s\'>%s</h2></a>', 
				get_permalink(), 
				get_the_title(), 
				get_the_title() );
			$_cat = get_the_category_list( ', ' );
			$_title_cat = sprintf( '
				%s %s', '<i class="fa fa-thumb-tack cat-icon"></i>' . $_cat, $_title );

			/**
			 * author, date
			 */
			$_author_date = sprintf( '
				<span class=\'author\'>%s</span>, 
				<span class=\'date\'>%s</span>', get_the_author(), get_the_date( 'M d Y' ) );

			/**
			 * Icon
			 */
			$_icon = '';
			switch ( $atts['template_params']['style'] ) {
				case 'linestyle':
					/* Zoom thumb */
					$_icon .= sprintf( '
						<a title=\'%s\' href=\'%s\' data-imagelightbox=\'%s\'>
							<i class=\'fa fa-picture-o\'></i>
						</a>', __( 'view thumb', TBLG_NAME ), $thumbnail, $atts['element_id'] );
					break;
				
				default:
					/* Link */
					$_icon .= sprintf( '
						<a title=\'%s\' href=\'%s\'>
							<i class=\'fa fa-file-text-o\'></i>
						</a>', get_the_title(), get_permalink() );

					/* Zoom thumb */
					$_icon .= sprintf( '
						<a title=\'%s\' href=\'%s\' data-imagelightbox=\'%s\'>
							<i class=\'fa fa-picture-o\'></i>
						</a>', __( 'view thumb', TBLG_NAME ), $thumbnail, $atts['element_id'] );
					break;
			}

			$info = '
			<div class=\'lemongrid-info\'>
				<div class=\'info-text-top\'>
					'. $_title_cat .'
				</div>
				<div class=\'info-text-bottom\'>
					'. $_author_date .'
				</div>
				<div class=\'lemongrid-icon '. $atts['template_params']['style'] .'\'>
					'. $_icon .'
				</div>
			</div>';

			$output .= '
				<div class=\'lemongrid-item lg-animate-fadein grid-stack-item\' data-gs-x=\''. esc_attr( $grid[$k]['x'] ) .'\' data-gs-y=\''. esc_attr( $grid[$k]['y'] ) .'\' data-gs-width=\''. esc_attr( $grid[$k]['w'] ) .'\' data-gs-height=\''. esc_attr( $grid[$k]['h'] ) .'\'>
					<div class=\'grid-stack-item-content\' style=\''. esc_attr( $style ) .'\'>
						'. $info .'
					</div>
				</div>';

			$k += 1;
		endwhile;
		wp_reset_postdata();
		return $output;
	}
endif;
?>
<div class="lemongrid-wrap <?php esc_attr_e( $atts['class_id'] ); ?> lemongrid--element <?php esc_attr_e( $template_name ) ?> <?php esc_attr_e( $atts['class'] ); ?>">
	<?php echo apply_filters( 'lemongrid_toolbar_frontend', lgToolbarFrontend( array( 'atts' => $atts ) ), array() ); ?>
	<?php echo apply_filters( 'lemongrid_before_content', '', array() ); ?>
	<div class="lemongrid-inner grid-stack" data-lemongrid-options="<?php esc_attr_e( $lemongrid_options ); ?>">
		<?php 
		if( isset( $atts['posts']->posts ) && ( count( $atts['posts']->posts ) > 0 ) ) :
			_e( call_user_func( 'lgItemPostTemp2', $atts ) );
		else :
			_e( '...', TBLG_NAME );
		endif;
		?>
	</div>
	<?php echo apply_filters( 'lemongrid_after_content', '', array() ); ?>
</div>