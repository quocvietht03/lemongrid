<?php
vc_map(
	array(
		"name" => __( "Post LemonGrid", TBLG_NAME ),
	    "base" => "post_lemongrid",
	    "class" => "vc-post-lemongrid",
	    "category" => __("LemonGrid Shortcodes", TBLG_NAME),
	    "params" => array(
	    	array(
				'type' => 'el_id',
				'param_name' => 'element_id',
				'settings' => array(
					'auto_generate' => true,
				),
				'heading' => __( 'Element ID', TBLG_NAME ),
				'description' => __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', TBLG_NAME ),
				'group' => __( 'Source Settings', TBLG_NAME ),
				),
	    	array(
	            "type" => "loop",
	            "heading" => __( "Source",TBLG_NAME ),
	            "param_name" => "source",
	            'settings' => array(
	                'size' => array( 'hidden' => false, 'value' => 10 ),
	                'order_by' => array( 'value' => 'date' )
	            	),
	            "group" => __( "Source Settings", TBLG_NAME ),
	        	),
	    	array(
	        	'type' => 'textfield',
	        	'heading' => __( 'Cell Height', TBLG_NAME ),
	        	'param_name' => 'cell_height',
	        	'value' => 120,
	        	'group' => __( 'Grid', TBLG_NAME ),
	        	),
	        array(
	        	'type' => 'textfield',
	        	'heading' => __( 'Space', TBLG_NAME ),
	        	'param_name' => 'space',
	        	'value' => 20,
	        	'group' => __( 'Grid', TBLG_NAME ),
	        	),
	    	array(
	            'type' => 'textfield',
	            'heading' => __( 'Extra Class',TBLG_NAME ),
	            'param_name' => 'class',
	            'value' => '',
	            'description' => __( '',TBLG_NAME ),
	            'group' => __( 'Template', TBLG_NAME )
	        ),
	    	/* array(
	            'type' => 'lg_template',
	            'heading' => __( 'Template', TBLG_NAME ),
	            'param_name' => 'template',
	            'shortcode' => 'post_lemongrid',
	            'group' => __( 'Template', TBLG_NAME ),
	        	),
	    	) */
			array(
	            'type' => 'lg_supper_template',
	            'heading' => __( 'Template', TBLG_NAME ),
	            'param_name' => 'template',
	            'shortcode' => 'post_lemongrid',
	            'group' => __( 'Template', TBLG_NAME ),
	        	),
	    	)
		)
	);

class WPBakeryShortCode_post_lemongrid extends WPBakeryShortCode
{
	protected function content( $atts, $content = null )
	{
		$atts = shortcode_atts( array(
				'element_id'	=> '',
				'source'		=> '',
				'cell_height'	=> 120,
				'space'			=> 20,
				'template'		=> '',
				'class' 		=> '',
			    ), $atts);
		
		/**
		 * Enqueue script
		 */
		// LemonGrid::include_script();

		$templateParams = json_decode( $atts['template'], true );
		$atts['class_id'] = 'lemon_grid_id_' . $atts['element_id'];
		$atts['template'] = $templateParams['template'];
		$atts['template_params'] = $templateParams;

		/**
		 * wp_query
		 */
		list( $args, $wp_query ) = vc_build_loop_query( $atts['source'] );
        $paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	    if( $paged > 1 ){
	    	$args['paged'] = $paged;
	    	$wp_query = new WP_Query( $args );
	    }
	    $atts['posts'] = $wp_query;

		do_action( 'tblg_include_script_inline', renderGridCustomSpaceCss( $atts['class_id'], $atts['space'] ) );
		
		return lgLoadTemplate( $atts, $content );
	}
}

/**
 * lgPostLemongridLayout2Params
 */
function lgPostLemongridLayout2Params()
{
	return array(
		array(
			'name' => 'style',
			'title' => __( 'Style', TBLG_NAME ),
			'type' => 'select',
			'value' => 'default',
			'options' => array(
				array(
					'value' => 'default',
					'text' => __( 'Default', TBLG_NAME ),
					),
				array(
					'value' => 'linestyle',
					'text' => __( 'lineStyle', TBLG_NAME ),
					),
				)
			)
		);
}

/**
 * lgPostLemongridFilterParams
 */
function lgPostLemongridFilterParams()
{
	return array(
		array(
			'name' => 'taxonomy',
			'title' => __( 'Taxonomy', TBLG_NAME ),
			'type' => 'text',
			'value' => 'category',
			'description' => __( '<i>Note: Use for custom post type. (default: category)</i>', TBLG_NAME ),
			),
		array(
			'name' => 'style_filter_header',
			'title' => __( 'Style Filter Header', TBLG_NAME ),
			'type' => 'select',
			'value' => 'default',
			'options' => array(
				array(
					'value' => 'default',
					'text' => __( 'Default', TBLG_NAME ),
					),
				)
			),
		array(
			'name' => 'align_filter_header',
			'title' => __( 'Align Filter Header', TBLG_NAME ),
			'type' => 'select',
			'value' => 'center',
			'options' => array(
				array(
					'value' => 'left',
					'text' => __( 'Left', TBLG_NAME ),
					),
				array(
					'value' => 'right',
					'text' => __( 'Right', TBLG_NAME ),
					),
				array(
					'value' => 'center',
					'text' => __( 'Center', TBLG_NAME ),
					),
				)
			),
		array(
			'name' => 'animate_filter',
			'title' => __( 'Animate Filter', TBLG_NAME ),
			'type' => 'select',
			'value' => 'flip',
			'options' => array(
				array(
					'value' => 'flip',
					'text' => __( 'Flip', TBLG_NAME ),
					),
				array(
					'value' => 'scale',
					'text' => __( 'Scale', TBLG_NAME ),
					),
				array(
					'value' => 'flip-scale',
					'text' => __( 'Flip & Scale', TBLG_NAME ),
					),
				)
			),
		);
}