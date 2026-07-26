<?php
if (! defined('WP_DEBUG')) {
	die( 'Direct access forbidden.' );
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'broedertrouw-style',
		get_stylesheet_uri(),
		[ 'ct-main-styles' ],
		wp_get_theme()->get( 'Version' ) ?: filemtime( get_stylesheet_directory() . '/style.css' )
	);
} );
