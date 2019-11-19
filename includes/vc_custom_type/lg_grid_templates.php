<?php
vc_add_shortcode_param('lg_grid_template', 'lgShortcodeGridTemplate');

function lgShortcodeGridTemplate( $settings, $value ) 
{
	$gridLayouts = lbGetLemonGridLayouts();

	$output = '';
	$output .= "<select name=\"" . esc_attr( $settings['param_name'] ) . "\" class=\"wpb_vc_param_value\">";
    foreach ( $gridLayouts as $gridName => $gridMap ) :
    	$selected = ( $gridName == esc_attr( $value ) ) ? 'selected' : '';
        $output .= "<option value=\"{$gridName}\" {$selected}>{$gridName}</option>";
    endforeach;
    $output .= "</select>";
    $output .= sprintf( '<small><i>%s %s <a href=\'#\' target=\'_blank\'>%s</a></i></small>', 
    		__( ( count( $gridLayouts ) <= 0 ) ? 'not available grid template,' : '', TBLG_NAME ),
			__( 'if you don\'t know create grid template, please watch', TBLG_NAME ), 
			__( 'tutorial.', TBLG_NAME ));
   
    return $output;
}