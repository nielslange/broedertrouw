<?php
/**
 * Third footer menu element view.
 *
 * Reuses Blocksy's footer menu view with the child theme's own location, so
 * markup and styling stay identical to the built-in footer menus.
 *
 * @package broedertrouw
 *
 * @var array $atts Element settings.
 * @var array $attr Container attributes.
 */

defined( 'ABSPATH' ) || exit;

blocksy_companion_render_view_e(
	get_template_directory() . '/inc/panel-builder/footer/menu/view.php',
	array(
		'atts'     => $atts,
		'attr'     => $attr,
		'class'    => 'footer-menu-inline menu-container',
		'id'       => 'footer-menu-3',
		'location' => 'footer_3',
	)
);
