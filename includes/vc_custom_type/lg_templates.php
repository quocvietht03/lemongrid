<?php
vc_add_shortcode_param('lg_template', 'lgShortcodeTemplate');

function lgShortcodeTemplate( $settings, $value ) 
{
	$shortcode = $settings['shortcode'];
	$plg_dir_temp = TBLG_DIR . 'templates/';
	$theme_dir_temp = get_template_directory() . '/lemongrid_templates/';
	$reg = "/^({$shortcode}\.php|{$shortcode}--.*\.php)/";

	$files = lgFileScanDirectory( $plg_dir_temp, $reg );
	$files = array_merge( $files, lgFileScanDirectory( $theme_dir_temp, $reg ) );
	
	$output = '';
	$output .= "<select name=\"" . esc_attr( $settings['param_name'] ) . "\" class=\"wpb_vc_param_value\">";
    foreach ( $files as $name_file => $dir_file ) :
    	$selected = ( $name_file == esc_attr( $value ) ) ? 'selected' : '';
        $output .= "<option value=\"{$name_file}\" {$selected}>{$name_file}</option>";
    endforeach;
    $output .= "</select>";
   
    return $output;
}