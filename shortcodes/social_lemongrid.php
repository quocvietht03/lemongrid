<?php
vc_map(
	array(
		'name' => __( 'Social LemonGrid', TBLG_NAME ),
	    'base' => 'social_lemongrid',
	    'class' => 'vc-social-lemongrid',
	    'category' => __('LemonGrid Shortcodes', TBLG_NAME),
	    'show_settings_on_create' => true,
	    'params' => array(
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
	            'type' => 'dropdown',
	            'heading' => __( 'Social', TBLG_NAME ),
	            'param_name' => 'social',
	            'std' => 'instagram',
	            'value' => array(
	            	'Instagram' => 'instagram',
	            	'Flickr' => 'flickr',
	            	),
	            'group' => __( 'Source Settings', TBLG_NAME )
	        	),
	    	array(
	        	'type' => 'textfield',
	        	'heading' => __( 'User Name', TBLG_NAME ),
	        	'param_name' => 'username',
	        	'value' => '',
	        	'group' => __( 'Source Settings', TBLG_NAME ),
	        	'description' => __( 'Ex: muradosmann, laurenconrad, ... ', TBLG_NAME )
	        	),
	    	array(
	        	'type' => 'textfield',
	        	'heading' => __( 'API Key', TBLG_NAME ),
	        	'param_name' => 'api_key',
	        	'value' => '',
	        	'group' => __( 'Source Settings', TBLG_NAME ),
	        	'description' => __( 'Instagram: Client Id / Flickr: Key', TBLG_NAME )
	        	),
	    	array(
	        	'type' => 'textfield',
	        	'heading' => __( 'Count', TBLG_NAME ),
	        	'param_name' => 'count',
	        	'value' => 9,
	        	'group' => __( 'Source Settings', TBLG_NAME ),
	        	'description' => __( 'Default: 9 items', TBLG_NAME )
	        	),
	        /* array(
	        	'type' => 'lg_grid_template',
	        	'heading' => __( 'Grid Template', TBLG_NAME ),
	        	'param_name' => 'grid_template',
	        	'value' => __( '', TBLG_NAME ),
	        	'group' => __( 'Grid', TBLG_NAME ),
	        	), */
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
	            'shortcode' => 'social_lemongrid',
	            'group' => __( 'Template', TBLG_NAME ),
	        	), */
	    	array(
	            'type' => 'lg_supper_template',
	            'heading' => __( 'Template', TBLG_NAME ),
	            'param_name' => 'template',
	            'shortcode' => 'social_lemongrid',
	            'group' => __( 'Template', TBLG_NAME ),
	        	),
	    	)
		)
	);

class WPBakeryShortCode_social_lemongrid extends WPBakeryShortCode
{
	protected function content( $atts, $content = null )
	{ 
		$atts = shortcode_atts( array(
				'element_id'	=> '',
				'social' 		=> 'instagram',
				'username'		=> '',
				'api_key'		=> '', 
				'count' 		=> 9,
				'cell_height'	=> 120,
				'space'			=> 20,
				'template'		=> '',
				'class' 		=> '',
			    ), $atts);

		/**
		 * Enqueue script
		 */
		// LemonGrid::include_script();

		/**
		 * Setup social
		 */
		switch( $atts['social'] ) {
			case 'instagram':
				require_once TBLG_INCLUDES . 'socials/instagram.class.php';
				$insta = new LG_Instagram();
				$insta->username = $atts['username'];
				$insta->client_id = $atts['api_key']; // '2a87113cbe65405aa10b491fc6e39242';
				$insta->slice = (int) $atts['count'];
				$media = $insta->getMedia();
				break;
			case 'flickr':
				require_once TBLG_INCLUDES . 'socials/flickr.class.php';
				$flickr = new LG_Flickr();
				$flickr->username = $atts['username'];
				$flickr->key = $atts['api_key']; // 'f668d07759169ca3db29e9a60bff128d';
				$flickr->slice = (int) $atts['count'];
				$media = $flickr->getMedia();
				break;
		}
		
		$templateParams = json_decode( $atts['template'], true );
		$atts['class_id'] = 'lemon_grid_id_' . $atts['element_id'];
		$atts['template'] = $templateParams['template'];
		$atts['template_params'] = $templateParams;
		
		$atts['media'] = ( isset( $media ) ) ? $media : array();
		$atts['class_id'] = 'lemon_grid_id_' . $atts['element_id'];

		do_action( 'tblg_include_script_inline', renderGridCustomSpaceCss( $atts['class_id'], $atts['space'] ) );
		
		return lgLoadTemplate( $atts, $content );
	}
}