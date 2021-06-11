<?php
/**
 * @package GingerSoul
 * @subpackage GinerTHeme
 * @since 2.0
 */

?><!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>
		<?php do_action('soultheme_before_header'); ?>

			<?php do_action('soultheme_do_header'); ?>
		<?php do_action('soultheme_after_header'); ?>
