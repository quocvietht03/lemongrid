<?php
/**
 * Layout Name: Widget
 * 
 */

list( $template_name ) = explode( '.', $atts['template'] );
$lemongrid_options = json_encode( array(
	'cell_height'		=> (int) $atts['cell_height'],
	'vertical_margin'	=> (int) $atts['space'],
	'animate'			=> true,
	) );

/* Title */
echo ( ! empty( $atts['title'] ) ) ? '<h2 class="wg-title widget-title">'. $atts['title'] .'</h2>' : '';

/**
 * lgItemWidgetSocialTemp
 */
if( ! function_exists( 'lgItemWidgetSocialTemp' ) ) :
	function lgItemWidgetSocialTemp( $atts )
	{
		$output = '';
		$grid = lbGetLemonGridLayouts( $atts['element_id'], count( $atts['media'] ) ); /* v1.1 */

		foreach( $atts['media'] as $k => $data ) :
			$urlEx = explode( '?' , $data['photo'] );

			$style = implode( ';', array( 
				"background: url({$urlEx[0]}) no-repeat center center / cover, #333", 
				) );

			$output .= '
					<div class=\'lemongrid-item lg-animate-fadein grid-stack-item\' data-gs-x=\''. esc_attr( $grid[$k]['x'] ) .'\' data-gs-y=\''. esc_attr( $grid[$k]['y'] ) .'\' data-gs-width=\''. esc_attr( $grid[$k]['w'] ) .'\' data-gs-height=\''. esc_attr( $grid[$k]['h'] ) .'\'>
						<div class=\'grid-stack-item-content\' style=\''. esc_attr( $style ) .'\'>
							<a class="lg-touch-open-lightbox" href=\''. $urlEx[0] .'\' data-imagelightbox=\''. $atts['element_id'] .'\'></a>
						</div>
					</div>';
		endforeach;

		return $output;
	}
endif;
?>
<div class="lemongrid-wrap lemongrid-widget <?php esc_attr_e( $atts['class_id'] ); ?> lemongrid--element social-<?php esc_attr_e( $atts['social'] ); ?> <?php esc_attr_e( $template_name ) ?> <?php esc_attr_e( $atts['extra_class'] ); ?>">
	<?php echo apply_filters( 'lemongrid_toolbar_frontend', lgToolbarFrontend( array( 'atts' => $atts ) ), array() ); ?>
	<?php echo apply_filters( 'lemongrid_before_content', '', array() ); ?>
	<div class="lemongrid-inner grid-stack" data-lemongrid-options="<?php esc_attr_e( $lemongrid_options ); ?>">
		<?php 
		if( is_array( $atts['media'] ) && count( $atts['media'] ) > 0 ) :
			_e( call_user_func( 'lgItemWidgetSocialTemp', $atts ) );
		else :
			_e( '...', TBLG_NAME );
		endif;
		?>
	</div>
	<?php echo apply_filters( 'lemongrid_after_content', '', array() ); ?>
</div>