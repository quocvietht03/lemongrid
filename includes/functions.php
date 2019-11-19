<?php 
/**
 * lgFileScanDirectory
 *
 * @param string $dir
 * @param string $reg
 */
function lgFileScanDirectory( $dir, $reg ) 
{
	$result_files = array();

	if( is_dir( $dir ) && $files = scandir( $dir ) ) :
		
		$files = array_diff( $files, array( '.', '..' ) );

		if( count( $files ) <= 0 ) 
			return $result_files;

		foreach( $files as $filename )
			if( TRUE == preg_match( $reg, $filename ) ) 
				$result_files[$filename] = str_replace( '\\', '/', $dir ) . $filename;

	endif;

	return $result_files;
}

/**
 * lgShortcodeContent
 * 
 * @param array $attr
 * @param string $content
 */
function lgLoadTemplate( $atts, $content = null )
{	
	$plg_dir_temp = TBLG_DIR . 'templates/';
	$theme_dir_temp = get_template_directory() . '/lemongrid_templates/';

	/**
	 * Set template path
	 */
	$template_path = ( is_file( $theme_dir_temp . $atts['template'] ) ) 
		? $theme_dir_temp . $atts['template']
		: $plg_dir_temp . $atts['template']; 

	/**
	 * Check template path exist
	 */
	
	if ( is_file( $template_path ) ) :
		ob_start(); include $template_path; return ob_get_clean();
	else :
		return __( 'Template not exist!', TBLG_NAME );
	endif;
}

/**
 * lg_widget_templates
 */
function lg_widget_templates()
{
	$prefix = 'widget';
	$plg_dir_temp = TBLG_DIR . 'templates/';
	$theme_dir_temp = get_template_directory() . '/lemongrid_templates/';
	$reg = "/^({$prefix}\.php|{$prefix}--.*\.php)/";

	$files = lgFileScanDirectory( $plg_dir_temp, $reg );
	$files = array_merge( $files, lgFileScanDirectory( $theme_dir_temp, $reg ) );

	return $files;
}

/**
 * renderGridDefault
 *
 * @param int $count
 * @return array
 */
function lgRenderGridDefault( $count = 0 )
{	
	$grid = array(); $col = 12; $x = 0; $y = 0;
	if( $count == 0 ) return $grid;

	while( $count > 0 ) {
	    array_push( $grid, array( 'x' => $x, 'y' => $y, 'w' => 4, 'h' => 2 ) );
	    $x += 4;
	    if( $x >= 12 ) : $x = 0; $y += 2; endif;
	    $count -= 1;
	} 

	return $grid;
}

/**
 * lgToolbarFrontend
 * 
 * @param array $params
 *
 * @return HTML 
 */
function lgToolbarFrontend( $params ) 
{
	/**
	 * Check admin login
	 */
	if( ! is_super_admin() ) return;

	$toolArr = apply_filters( 'lemongrid_toolbar_frontend', array(
		array(
			'tag' => 'a',
			'attrs' => array( 
				'class' => 'lg-toolbar-icon lg-toolbar-icon--save-layout ', 
				'href' => '#', 
				'title' => __( 'Save layout', TBLG_NAME ), 
				// 'data-grid-name' => $params['atts']['grid_template'], 
				'data-grid-elementid' => $params['atts']['element_id'],
				'data-pageid' => get_the_ID() ),
			'content' => sprintf( '<i class=\'fa fa-floppy-o\'></i>' ),
			),
		/*array(
			'tag' => 'a',
			'attrs' => array( 
				'class' => 'lg-toolbar-icon lg-toolbar-icon--save-as-layout', 
				'href' => '#', 
				'title' => __( 'Save as layout', TBLG_NAME ) ),
			'content' => sprintf( '<i class=\'ion-ios-grid-view\'></i>' ),
			),*/
		), $params );

	$output = '';
	foreach( $toolArr as $item ) :
		/**
	     * Build attr element
	     */
		$attrArr = array();
		if( count( $item['attrs'] ) > 0 )
			foreach( $item['attrs'] as $attr => $data )
				array_push( $attrArr, "{$attr}='{$data}'" );

		$output .= "<li class='lemongrid-toolbar-item'><{$item['tag']} ". implode( ' ', $attrArr ) .">{$item['content']}</{$item['tag']}></li>";
	endforeach;

	return sprintf( '
		<ul class=\'lemongrid-toolbar\'>
			%s
		</ul>', $output );
}

/**
 * lbGetLemonGridLayouts
 */
function lbGetLemonGridLayouts( $name = '', $count = 0 ) 
{
	$lemongrid_grid_layouts = get_option( 'lemongrid_grid_layouts', json_encode( array() ) );
	$layoutArr = json_decode( $lemongrid_grid_layouts, true );

	if( ! empty( $name ) ) :
		$result = isset( $layoutArr[$name] ) ? $layoutArr[$name] : lgRenderGridDefault( $count );

		/**
		 * NOTE: Need update next version
		 */
		if( $count > count( $result ) ) :
			$_ceil = ceil( $count / count( $result ) );
			$_source_result = $result;
			$_high_y = 0;

			/* Push more grid */
			for( $i = 1; $i <= $_ceil; $i++ ) :

				/* Set high y */
				foreach( $result as $_g ) :
					if( $_high_y < ( $_g['y'] + $_g['h'] ) ) 
						$_high_y = $_g['y'] + $_g['h'] + 1;
				endforeach;

				/* Push item */
				foreach( $_source_result as $_g ) :
					array_push( $result, array(
						'x' => $_g['x'], 
						'y' => $_high_y + $_g['y'] * $i, 
						'w' => $_g['w'], 
						'h' => $_g['h'],
					) );
				endforeach;
			endfor;
		endif;

	else :
		$result = $layoutArr;
	endif;

	return $result;
}

/**
 * lgApplyLemonGrid
 */
function lgApplyLemonGrid() 
{
	$layout_arr = lbGetLemonGridLayouts();
	$layout_arr[$_POST['name']] = $_POST['gridMap'];

	update_option( 'lemongrid_grid_layouts', json_encode( $layout_arr ) );
	exit();
}
add_action( 'wp_ajax_lgApplyLemonGrid', 'lgApplyLemonGrid' );
add_action( 'wp_ajax_nopriv_lgApplyLemonGrid', 'lgApplyLemonGrid' );

/**
 * lgSaveLayoutLemonGrid
 */
function lgSaveLayoutLemonGrid()
{
	$lgMetaData = lgGetLayoutLemonGridPerPage( $_POST['pageID'] );
	$lgMetaData[$_POST['elemID']] = $_POST['gridMap'];

	update_post_meta( $_POST['pageID'], '_lemongrid_meta_post', json_encode( $lgMetaData ) );
	exit;
}
add_action( 'wp_ajax_lgSaveLayoutLemonGrid', 'lgSaveLayoutLemonGrid' );
add_action( 'wp_ajax_nopriv_lgSaveLayoutLemonGrid', 'lgSaveLayoutLemonGrid' );

/**
 * lgGetLayoutLemonGridPerPage
 *
 * @param int $pageID
 * @param int $gridID
 * @param int $count (total item)
 *
 * @return array
 */
function lgGetLayoutLemonGridPerPage( $pageID, $gridID = '', $count = 0 ) 
{	
	$lemongrid_meta_post = get_post_meta( $pageID, '_lemongrid_meta_post', json_encode( array() ) );
	$result = '';

	/**
	 * Check exist $lemongrid_meta_post
	 */
	if( $lemongrid_meta_post == 'null' ) return array();

	$lgData = json_decode( $lemongrid_meta_post, true );

	/**
	 * Check exist $gridID
	 */
	if( empty( $gridID ) ) return $lgData;

	if( isset( $lgData[$gridID] ) ) :
		return $lgData[$gridID];
	else :
		return lgRenderGridDefault( $count );
	endif;
}

/**
 * lgUpdateInfoFlickr
 */
function lgUpdateInfoFlickr() 
{
	require_once TBLG_INCLUDES . 'socials/flickr.class.php';
	
	$result = LG_Flickr::getInfo( $_POST['data']['api_key'], $_POST['data']['id'], $_POST['data']['secret'] );
	echo json_encode( $result );
	exit();
}
add_action( 'wp_ajax_lgUpdateInfoFlickr', 'lgUpdateInfoFlickr' );
add_action( 'wp_ajax_nopriv_lgUpdateInfoFlickr', 'lgUpdateInfoFlickr' );

/**
 * renderGridCustomSpaceCss
 *
 * @param string $contentID
 * @param int $space
 *
 * @return Css string
 */
function renderGridCustomSpaceCss( $contentID, $space = 0 ) 
{
	$output = '';
	$gridWidth = array(  
		'8.33333333%', '16.66666667%', '25%', '33.33333333%', '41.66666667%', '50%', 
		'58%', '66.66666667%', '75%', '83.33333333%', '91.66666667%', '100%',
		);

	$output .= sprintf( '.lemongrid-wrap.%s .grid-stack .grid-stack-placeholder > .placeholder-content{ left: 0; right: 0; transform: translateX(%spx); -webkit-transform: translateX(%spx); }', $contentID, $space, $space );
	$output .= sprintf( '.lemongrid-wrap.%s .grid-stack > .grid-stack-item{ min-width: calc( %s - %spx ); }', $contentID, '8.33333%', $space );
	$output .= sprintf( '.lemongrid-wrap.%s .grid-stack > .grid-stack-item > .ui-resizable-se{ bottom: 5px; right: 5px; }', $contentID );
	$output .= sprintf( '.lemongrid-wrap.%s .grid-stack > .grid-stack-item > .grid-stack-item-content{ left: 0px; right: 0px; }', $contentID );
	$output .= sprintf( '.lemongrid-wrap.%s .lemongrid-inner{ margin-left: -%spx; }', $contentID, $space );
	$output .= sprintf( '.lemongrid-wrap.%s .lemongrid-inner .lemongrid-item{ margin: 0 0 %spx %spx; }', $contentID, $space, $space );
	foreach( $gridWidth as $k => $itemWidth ) :
		$output .= sprintf( '.lemongrid-wrap.%s .grid-stack > .grid-stack-item[data-gs-width=\'%s\'] {width: calc( %s - %spx );}', $contentID, $k + 1, $itemWidth, $space );
		$output .= sprintf( '.lemongrid-wrap.%s .grid-stack > .grid-stack-item[data-gs-min-width=\'%s\'] {min-width: calc( %s - %spx );}', $contentID, $k + 1, $itemWidth, $space );
		$output .= sprintf( '.lemongrid-wrap.%s .grid-stack > .grid-stack-item[data-gs-max-width=\'%s\'] {max-width: calc( %s - %spx );}', $contentID, $k + 1, $itemWidth, $space );
		// $output .= sprintf( '.lemongrid-wrap.%s .grid-stack > .grid-stack-item[data-gs-x=\'%s\'] {left: calc( %s + %spx );}', $contentID, $k + 1, $itemWidth, $space );
	endforeach;

	return $output;
}

/**
 * multi-purpose function to calculate the time elapsed between $start and optional $end
 * @param string|null $start the date string to start calculation
 * @param string|null $end the date string to end calculation
 * @param string $suffix the suffix string to include in the calculated string
 * @param string $format the format of the resulting date if limit is reached or no periods were found
 * @param string $separator the separator between periods to use when filter is not true
 * @param null|string $limit date string to stop calculations on and display the date if reached - ex: 1 month
 * @param bool|array $filter false to display all periods, true to display first period matching the minimum, or array of periods to display ['year', 'month']
 * @param int $minimum the minimum value needed to include a period
 * @return string
 */
function lgElapsedTimeString( $start, $end = null, $limit = null, $filter = true, $suffix = 'ago', $format = 'Y-m-d', $separator = ' ', $minimum = 1 )
{
    $dates = (object) array(
        'start' => new DateTime($start ? '' : __( 'now', TBLG_NAME ) ),
        'end' => new DateTime($end ? '' : __( 'now', TBLG_NAME ) ),
        'intervals' => array('y' => __( 'year', TBLG_NAME ), 'm' => __( 'month', TBLG_NAME ), 'd' => __( 'day', TBLG_NAME ), 'h' => __( 'hour', TBLG_NAME ), 'i' => __( 'minute', TBLG_NAME ), 's' => __( 'second', TBLG_NAME ) ),
        'periods' => array()
    );
    $elapsed = (object) array(
        'interval' => $dates->start->diff($dates->end),
        'unknown' => 'unknown'
    );
    if ($elapsed->interval->invert === 1) {
        return trim('0 seconds ' . $suffix);
    }
    if (false === empty($limit)) {
        $dates->limit = new DateTime($limit);
        if (date_create()->add($elapsed->interval) > $dates->limit) {
            return $dates->start->format($format) ? '' : $elapsed->unknown;
        }
    }
    if (true === is_array($filter)) {
        $dates->intervals = array_intersect($dates->intervals, $filter);
        $filter = false;
    }
    foreach ($dates->intervals as $period => $name) {
        $value = $elapsed->interval->$period;
        if ($value >= $minimum) {
            $dates->periods[] = vsprintf('%1$s %2$s%3$s', array($value, $name, ($value !== 1 ? 's' : '')));
            if (true === $filter) {
                break;
            }
        }
    }
    if (false === empty($dates->periods)) {
        return trim(vsprintf('%1$s %2$s', array(implode($separator, $dates->periods), $suffix)));
    }

    return $dates->start->format($format) ? '' : $elapsed->unknown;
}

function lgCustomNumberFormat( $num ) 
{
	if( $num >= 1000 )
		$num = number_format( $num / 1000 ) . 'k' ;

	return $num;
}

/**
 * lgGetComments
 *
 * @param string $filename 
 */
function lgGetComments( $filename )
{
	$comments = array();
	$params = array();
	$expr = "/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/";
	
    $file = fopen( $filename, "r" );
    $length = filesize( $filename );
    $comments = fread( $file, $length );
    fclose($file);

    preg_match_all($expr, $comments, $matches);
    $comments = $matches[0][0];

	if( empty( $comments ) ) return;

	/* filter string */
	$comments = str_replace( array( '/*', '/**', '*/', '**/', '*' ) , '', $comments );

	/*  */
	$segments = explode( chr(10), $comments );

	/* build params */
	if( count( $segments ) == 0 ) return;
	foreach( $segments as $segment ) {
		$segment = trim( $segment );
		if( ! empty( $segment ) ) { 
			$_arr = explode( ':', $segment, 2 );
			if( count( $_arr ) == 2 )
				$params[strtolower( $_arr[0] )] = ltrim( $_arr[1] );
		}
	}

    return( $params );
}

/**
 * lgRenderFieldTemplate
 *
 * @param array $field
 * @return HTML
 */
function lgRenderFieldTemplate( $field )
{
	extract( $field );
	$output = '';

	switch ( $type ) {
		case 'select':
			$_options = '';
			$_value = isset( $value ) ? $value : '';
			foreach( $options as $o ) :
				$selected = ( $_value == $o['value'] ) ? 'selected' : '';
				$_options .= sprintf( '<option value="%s" %s>%s</option>', $o['value'], $selected, $o['text'] );
			endforeach;

			$output .= sprintf( '
				<div class="lg-group-field-param">
					<label class="wpb_element_label">%s</label>
					<select name="%s">
						%s
					</select>
					<p>%s</p>
				</div>', $title, $name, $_options, isset( $description ) ? $description : '' );
			break;
		
		case 'message':

			$output .= sprintf( '<p>%s</p>', $text );
			break;

		default:
			$output .= sprintf( '
				<div class="lg-group-field-param">
					<label class="wpb_element_label">%s</label>
					<input name="%s" type="%s" value="%s" />
					<p>%s</p>
				</div>', $title, $name, $type, $value, isset( $description ) ? $description : '' );
			break;
	}

	return $output;
}

/**
 * include_script_inline
 *
 * @param string $script_str
 */
function lgIncludeScriptInline( $script_str )
{
	_e( sprintf( '<style scoped>%s</style>', $script_str ) );
}
add_action( 'tblg_include_script_inline', 'lgIncludeScriptInline' );