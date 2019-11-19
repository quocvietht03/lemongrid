<?php
if( function_exists( 'vc_add_shortcode_param' ) )
	vc_add_shortcode_param('lg_supper_template', 'lgShortcodeSupperTemplate');
else
	add_shortcode_param('lg_supper_template', 'lgShortcodeSupperTemplate');

function lgShortcodeSupperTemplate( $settings, $value ) 
{
	$shortcode = $settings['shortcode'];
	$plg_dir_temp = TBLG_DIR . 'templates/';
	$theme_dir_temp = get_template_directory() . '/lemongrid_templates/'; 
	$reg = "/^({$shortcode}\.php|{$shortcode}--.*\.php)/";
	$valueArr = json_decode( $value, true );
	$setting_name = $settings['param_name'];

	$files = lgFileScanDirectory( $plg_dir_temp, $reg );
	$files = array_merge( $files, lgFileScanDirectory( $theme_dir_temp, $reg ) );
	
	$output = '';
	$output .= "<select data-loadparambytemplate-lg name=\"" . esc_attr( $setting_name ) . "\">";
    foreach ( $files as $name_file => $dir_file ) :
    	$params = lgGetComments( $dir_file );
    	$field_HTML = isset( $params['param'] ) ? lgFieldTemplate( $params['param'], $valueArr ) : '';
    	$selected = ( $name_file == $valueArr["{$setting_name}"] ) ? 'selected' : '';

        $output .= sprintf( '<option data-fieldhtml=\'%s\' value="%s" %s>%s</option>', 
        	$field_HTML, 
        	$name_file, 
        	$selected, 
        	isset( $params['layout name'] ) ? $params['layout name'] . " ({$name_file})" : $name_file );
    endforeach;
    $output .= "</select>";
    $output .= sprintf( '<div class="lg-params-container"></div>' );

    return sprintf( '
    	<div class="lg-shortcode-supper-template">
    		<textarea class="lg-hidden wpb_vc_param_value" name="%s" data-jsoncontent>%s</textarea>
    		<div class="lg-template-group-field">
				%s
    		</div>
    	</div>', esc_attr( $settings['param_name'] ), $value, $output );
}

/**
 * lgFieldTemplate
 * 
 * @param String $func_name
 */
function lgFieldTemplate( $func_name, $value = array() )
{
	if( ! function_exists( $func_name ) )
		return;

	$output = '';
	$fields = call_user_func( $func_name );
	if( ! empty( $fields ) && is_array( $fields ) ) :
		foreach( $fields as $field ) :
			/* Set value */
			if( isset( $value[$field['name']] ) )
				$field['value'] = $value[$field['name']];
			
			$output .= lgRenderFieldTemplate( $field );
		endforeach;
	endif;

	return sprintf( '
		<div class="lg-params-container-inner">
			%s
		</div>', 
		! empty( $output ) ? $output : __( 'Empty...!' ) );
}