<?php
/* variable */
$social = $atts['social'];
list( $template_name ) = explode( '.', $atts['template'] );
$lemongrid_options = json_encode( array(
	'cell_height'		=> (int) $atts['cell_height'],
	'vertical_margin'	=> (int) $atts['space'],
	'animate'			=> true,
	) );
/**
 * lgItemSocialTemp
 *
 * @param array $data
 * @return HTML
 */
if( ! function_exists( 'lgItemSocialTemp' ) ) :
	function lgItemSocialTemp( $atts )
	{
		$output = '';
		$element_id = $atts['element_id'];
		$social = $atts['social'];
		// $grid = lgGetLayoutLemonGridPerPage( get_the_ID(), $element_id, count( $atts['media'] ) );
		$grid = lbGetLemonGridLayouts( $atts['element_id'], count( $atts['media'] ) ); /* v1.1 */

		foreach( $atts['media'] as $k => $data ) :
			$style = implode( ';', array( 
				"background: url({$data['photo']}) no-repeat center center / cover, #333", 
				) );

			$info = '';
			switch ( $social ) {
				case 'flickr':
					/* title */
					$description = ( isset( $data['title'] ) && ! empty( $data['title'] ) ) 
						? '<div class=\'lemongrid-description\'><p>'. esc_attr( wp_trim_words( $data['title'], 7, $more = '...' ) ) .'</p></div>' 
						: '';

					/* Detail modal */
					$data['detail_modal'] = '
					<div class=\'modal-detail-info '. $social .'\'>
						<div class=\'title lg-animate-fadein\'><h4>'. $data['title'] .'</h4></div>
						<div class=\'description lg-animate-fadein\'><i class=\'fa fa-circle-o-notch fa-spin\'></i></div>
						<p class=\'author lg-animate-fadein\'>- '. $data['username'] .'</p>
						<div class=\'icon-wrap lg-animate-fadein\'>
							<i class=\'fa fa-circle-o-notch fa-spin\'></i>
						</div>
					</div>';

					$info .= '
					<div class=\'lemongrid-info\'>
						<div class=\'lemongrid-icon\'>
							<a href=\'#\' data-flickr=\''. json_encode( str_replace( "'", '&#39;', $data ) ) .'\' class=\'lemongrid-icon-picture\'><i class=\'fa fa-picture-o\'></i></a>
							<a href=\''. $data['link'] .'\' target=\'_blank\' class=\'lemongrid-icon-link\'><i class=\'fa fa-link\'></i></a>
						</div>
						'. $description .'
					</div>';
					break;
				
				default: /* instagram */

					/* description */
					$description = ( isset( $data['description'] ) && ! empty( $data['description'] ) ) 
						? '<div class=\'lemongrid-description\'><p>'. esc_attr( wp_trim_words( $data['description'], 7, $more = '...' ) ) .'</p></div>' 
						: '';	

					/* Detail modal */
					$data['detail_modal'] = '
					<div class=\'modal-detail-info '. $social .'\'>
						<div class=\'description lg-animate-fadein\'>'. $data['description'] .'</div>
						<p class=\'author lg-animate-fadein\'>- '. $data['full_name'] .'</p>
						<div class=\'icon-wrap\'>
							<span class=\'icon-likes lg-animate-fadein\'><i class=\'ion-android-favorite\'></i> '. $data['likes'] .'</span>
							<span class=\'icon-comments lg-animate-fadein\'><i class=\'ion-android-textsms\'></i> '. $data['comments'] .'</span>
							<span class=\'icon-time lg-animate-fadein\'><i class=\'ion-ios-clock\'></i> '. $data['time'] .'</span>
						</div>
					</div>';

					$icon_class = ( 'video' == $data['type'] ) ? 'fa-play' : 'fa-picture-o';

					$info .= '
					<div class=\'lemongrid-info\'>
						<div class=\'lemongrid-icon\'>
							<a href=\'#\' data-instagram=\''. json_encode( str_replace( "'", '&#39;', $data ) ) .'\' class=\'lemongrid-icon-picture\'><i class=\'fa '. $icon_class .'\'></i></a>
							<a href=\''. $data['link'] .'\' target=\'_blank\' class=\'lemongrid-icon-link\'><i class=\'fa fa-link\'></i></a>
						</div>
						'. $description .'
					</div>';
					break;
			}

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
<div class="lemongrid-wrap <?php esc_attr_e( $atts['class_id'] ); ?> lemongrid--element social-<?php esc_attr_e( $social ); ?> <?php esc_attr_e( $template_name ) ?> <?php esc_attr_e( $atts['class'] ); ?>">
	<?php echo apply_filters( 'lemongrid_toolbar_frontend', lgToolbarFrontend( array( 'atts' => $atts ) ), array() ); ?>
	<?php echo apply_filters( 'lemongrid_before_content', '', array() ); ?>
	<div class="lemongrid-inner grid-stack" data-lemongrid-options="<?php esc_attr_e( $lemongrid_options ); ?>">
		<?php 
		if( is_array( $atts['media'] ) && count( $atts['media'] ) > 0 ) :
			_e( call_user_func( 'lgItemSocialTemp', $atts ) );
		else :
			_e( '...', TBLG_NAME );
		endif;
		?>
	</div>
	<?php echo apply_filters( 'lemongrid_after_content', '', array() ); ?>
</div>
