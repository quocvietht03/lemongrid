<?php
vc_map(
	array(
		"name" => __( "Gallery LemonGrid", TBLG_NAME ),
	    "base" => "gallery_lemongrid",
	    "class" => "vc-gallery-lemongrid",
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
				'type' => 'attach_images',
				'heading' => __( 'Images', TBLG_NAME ),
				'param_name' => 'images',
				'value' => '',
				'description' => __( 'Select images from media library.', TBLG_NAME ),
				'group' => __( 'Source Settings', TBLG_NAME ),
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
	            'shortcode' => 'gallery_lemongrid',
	            'group' => __( 'Template', TBLG_NAME ),
	        	), */
	    	array(
	            'type' => 'lg_supper_template',
	            'heading' => __( 'Template', TBLG_NAME ),
	            'param_name' => 'template',
	            'shortcode' => 'gallery_lemongrid',
	            'group' => __( 'Template', TBLG_NAME ),
	        	),
	    	)
		)	
	);

class WPBakeryShortCode_gallery_lemongrid extends WPBakeryShortCode
{
	protected function content( $atts, $content = null )
	{
		$atts = shortcode_atts( array(
				'element_id'	=> '',
				'images' 		=> '',
				'cell_height'	=> 120,
				'space'			=> 20,
				'template'		=> '',
				'class' 		=> '',
			    ), $atts);

		$templateParams = json_decode( $atts['template'], true );
		$atts['class_id'] = 'lemon_grid_id_' . $atts['element_id'];
		$atts['template'] = $templateParams['template'];
		$atts['template_params'] = $templateParams;

		/**
		 * Lib JS Imagelightbox
		 */
		wp_enqueue_script( 'imagelightbox', TBLG_JS . 'imagelightbox.min.js', array( 'jquery' ), '1.0.0', true );

		$atts['class_id'] = 'lemon_grid_id_' . $atts['element_id'];
	
		do_action( 'tblg_include_script_inline', renderGridCustomSpaceCss( $atts['class_id'], $atts['space'] ) );

		return lgLoadTemplate( $atts, $content );
	}
}