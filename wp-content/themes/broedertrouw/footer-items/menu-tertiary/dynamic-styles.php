<?php

if (! defined('ABSPATH')) {
	exit;
}

blocksy_companion_get_variables_from_file(
	get_template_directory() . '/inc/panel-builder/footer/menu/dynamic-styles.php',
	[],
	[
		'css' => $css,
		'tablet_css' => $tablet_css,
		'mobile_css' => $mobile_css,
		'atts' => $atts,
		'root_selector' => $root_selector,
		'item' => $item
	]
);

