<?php 
/**
 * Layout Name: Filter
 * Author: BEARS Theme
 * Author URI: http://themebears.com
 * Param: lgPostLemongridFilterParams
 */

/* variable */
list( $template_name ) = explode( '.', $atts['template'] );
$lemongrid_options = json_encode( array(
	'cell_height'		=> (int) $atts['cell_height'],
	'vertical_margin'	=> (int) $atts['space'],
	'animate'			=> true,
	) );
$lemongrid_layout_opts = json_encode( array(
	'filter-animate' => $atts['template_params']['animate_filter'],
	) );

/**
 * lgItemPostFilterHeader
 *
 * @param array $atts
 * @return HTML
 */
if( ! function_exists( 'lgItemPostFilterHeader' ) ) :
	function lgItemPostFilterHeader( $atts )
	{ 
		$filterItemHtml = sprintf( '<li class="lemongrid-nav-filter-item lg-filter-current"><a href="#" data-lemonfiltertitle="all"><span>%s</span></a></li>', __( 'All', TBLG_NAME ) );
		$filterData = array();
		$posts = $atts['posts'];

		while( $posts->have_posts() ) : 
			$posts->the_post();
			$cats = wp_get_post_terms( get_the_ID(), $atts['template_params']['taxonomy'] );
			if( ! empty( $cats ) && count( $cats ) > 0 ) :
				foreach( $cats as $cat_item ) : 
					if( ! isset( $filterData[$cat_item->slug] ) ) :
						$filterData[$cat_item->slug] = array( 'name' => $cat_item->name, 'count' => 1 );
					else :
						$filterData[$cat_item->slug]['count'] = $filterData[$cat_item->slug]['count'] += 1;
					endif;
				endforeach;
 			endif;
		endwhile;

		if( count( $filterData ) > 0 ) :
			foreach( $filterData as $filterKey => $filterItem ) :
				$filterItemHtml .= sprintf( '<li class="lemongrid-nav-filter-item">
						<a href="#" data-lemonfiltertitle=\'%s\'>
							<span>%s</span>
							<sup>%s</sup>
						</a>
					</li>', $filterKey, $filterItem['name'], $filterItem['count'] );
			endforeach;
		endif;

		wp_reset_postdata();
		return sprintf( 
			'<ul class="lemongrid-nav-filter %s">%s</ul>', 
			implode( ' ', array( 
				'lemongrid-filter-header-style-' . $atts['template_params']['style_filter_header'],
				'lg-align-' . $atts['template_params']['align_filter_header'] ) ), 
			$filterItemHtml );
	}
endif;

/**
 * lgItemPostTemp
 *
 * @param array $atts
 * @return HTML
 */
if( ! function_exists( 'lgItemPostFilterItems' ) ) :
	function lgItemPostFilterItems( $atts )
	{
		$output = '';
		$grid = lbGetLemonGridLayouts( $atts['element_id'], count( $atts['posts']->posts ) ); /* v1.1 */
		$posts = $atts['posts'];
		$k = 0;

		while( $posts->have_posts() ) : 
			$posts->the_post();

			/* get thumbnail */
			if( has_post_thumbnail() ):
                $thumbnail_data = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
            	$thumbnail = $thumbnail_data[0];
            else:
                $thumbnail = ''; /* image default */
            endif;

			$style = implode( ';', array( 
				"background: url({$thumbnail}) no-repeat center center / cover, #333", 
				) );

			/* get category */
			$cats = wp_get_post_terms( get_the_ID(), $atts['template_params']['taxonomy'] );
			$cat_filter = array_map( function( $item ) { return $item->slug; }, $cats );
			array_push( $cat_filter, 'all' );

			/**
			 * title, cat
			 */
			$_title = sprintf( '<a href="%s"><h2 class=\'title\' title=\'%s\'>%s</h2></a>', get_permalink(), get_the_title(), get_the_title() );
			$_cat = get_the_term_list( get_the_ID(), $atts['template_params']['taxonomy'], '', ' / ' );

			/* inner HTML */
			$info = sprintf( '
				<div class="lemongrid-info">
					<div class="lemongrid-info-inner">
						<div class="info-text-top">
							%s
						</div>
						<div class="info-text-bottom">
							%s
						</div>
					</div>
				</div>', $_cat, $_title );

			$output .= '
				<div class=\'lemongrid-item lg-animate-fadein grid-stack-item\' 
				data-lemonfilter=\''. esc_attr( implode( ',', $cat_filter ) ) .'\' 
				data-gs-x=\''. esc_attr( $grid[$k]['x'] ) .'\' 
				data-gs-y=\''. esc_attr( $grid[$k]['y'] ) .'\' 
				data-gs-width=\''. esc_attr( $grid[$k]['w'] ) .'\' 
				data-gs-height=\''. esc_attr( $grid[$k]['h'] ) .'\'>
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
<div data-lemongrid-layout="filter" data-lemongrid-layout-opts='<?php esc_attr_e( $lemongrid_layout_opts ); ?>' class="lemongrid-wrap <?php esc_attr_e( $atts['class_id'] ); ?> lemongrid--element <?php esc_attr_e( $template_name ) ?> <?php esc_attr_e( $atts['class'] ); ?>">
	<?php echo apply_filters( 'lemongrid_toolbar_frontend', lgToolbarFrontend( array( 'atts' => $atts ) ), array() ); ?>
	<?php echo apply_filters( 'lemongrid_before_content', '', array() ); ?>
	
	<!-- Filter header -->
	<?php _e( call_user_func( 'lgItemPostFilterHeader', $atts ) ); ?>

	<!-- Filter items -->
	<div class="lemongrid-inner grid-stack" data-lemongrid-options="<?php esc_attr_e( $lemongrid_options ); ?>">
		<?php 
		if( isset( $atts['posts']->posts ) && ( count( $atts['posts']->posts ) > 0 ) ) :
			_e( call_user_func( 'lgItemPostFilterItems', $atts ) );
		else :
			_e( '...', TBLG_NAME );
		endif;
		?>
	</div>
	<?php echo apply_filters( 'lemongrid_after_content', '', array() ); ?>
</div>