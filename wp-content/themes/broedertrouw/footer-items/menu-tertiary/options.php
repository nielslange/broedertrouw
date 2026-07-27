<?php

if (! defined('ABSPATH')) {
	exit;
}

$options = blocksy_companion_get_options(
	get_template_directory() . '/inc/panel-builder/footer/menu/options.php',
	[
		'location' => 'Footer Menu 3'
	],
	false
);

