<?php 
/* variable */	
list( $template_name ) = explode( '.', $atts['template'] );
$lemongrid_options = json_encode( array(
	'cell_height'		=> (int) $atts['cell_height'],
	'vertical_margin'	=> (int) $atts['space'],
	'animate'			=> true,
	) );

/**
 * lgItemGalleryTemp
 *
 * @param array $atts
 * @return HTML
 */
if( ! function_exists( 'lgItemGalleryTemp' ) ) :
	function lgItemGalleryTemp( $atts )
	{
		$output = '';
		$images = explode( ',', $atts['images'] );
		// $grid = lgGetLayoutLemonGridPerPage( get_the_ID(), $atts['element_id'], count( $images ) );
		$grid = lbGetLemonGridLayouts( $atts['element_id'], count( $images ) ); /* v1.1 */

		foreach( $images as $k => $image_id ) :
			$data_img = wp_get_attachment_image_src( $image_id, 'full' );
			$style = implode( ';', array( 
				"background: url({$data_img[0]}) no-repeat center center / cover, #333", 
				) );

			$info = '
			<div class=\'lemongrid-info\'>
				<div class=\'lemongrid-icon\'>
					<a href=\''. $data_img[0] .'\' data-imagelightbox=\''. $atts['element_id'] .'\'><i class=\'fa fa-expand\'></i></a>
				</div>
			</div>';

			$output .= '
				<div class=\'lemongrid-item lg-animate-fadein grid-stack-item\' data-gs-x=\''. esc_attr( $grid[$k]['x'] ) .'\' data-gs-y=\''. esc_attr( $grid[$k]['y'] ) .'\' data-gs-width=\''. esc_attr( $grid[$k]['w'] ) .'\' data-gs-height=\''. esc_attr( $grid[$k]['h'] ) .'\'>
					<div class=\'grid-stack-item-content\' style=\''. esc_attr( $style ) .'\'>
						'. $info .'
					</div>
				</div>';
		endforeach;

		return $output;
	}
endif;
?>
<div class="lemongrid-wrap <?php esc_attr_e( $atts['class_id'] ); ?> lemongrid--element <?php esc_attr_e( $template_name ) ?> <?php esc_attr_e( $atts['class'] ); ?>">
	<?php echo apply_filters( 'lemongrid_toolbar_frontend', lgToolbarFrontend( array( 'atts' => $atts ) ), array() ); ?>
	<?php echo apply_filters( 'lemongrid_before_content', '', array() ); ?>
	<div class="lemongrid-inner grid-stack" data-lemongrid-options="<?php esc_attr_e( $lemongrid_options ); ?>">
		<?php 
		if( ! empty( $atts['images'] ) ) :
			_e( call_user_func( 'lgItemGalleryTemp', $atts ) );
		else :
			_e( '...', TBLG_NAME );
		endif;
		?>
	</div>
	<?php echo apply_filters( 'lemongrid_after_content', '', array() ); ?>
</div>