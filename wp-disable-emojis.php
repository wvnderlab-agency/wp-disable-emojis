<?php

/*
 * Plugin Name:     Disable Emojis
 * Plugin URI:      https://github.com/wvnderlab-agency/wp-disable-emojis/
 * Author:          Wvnderlab Agency
 * Author URI:      https://wvnderlab.com
 * Text Domain:     wvnderlab-disable-emojis
 * Version:         0.1.0
 */

/*
 *  ################
 *  ##            ##    Copyright (c) 2025 Wvnderlab Agency
 *  ##
 *  ##   ##  ###  ##    ✉️ moin@wvnderlab.com
 *  ##    #### ####     🔗 https://wvnderlab.com
 *  #####  ##  ###
 */

declare(strict_types=1);

namespace WvnderlabAgency\DisableEmojis;

defined( 'ABSPATH' ) || die;

// Return early if running in WP-CLI context.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	return;
}

/**
 * Filter: Disable Emojis Enabled
 *
 * @param bool $enabled Whether to enable the disable emojis functionality. Default true.
 * @return bool
 */
if ( ! apply_filters( 'wvnderlab/disable-emojis/enabled', true ) ) {
	return;
}

/**
 * Remove emoji action and filter hooks
 *
 * @link   https://developer.wordpress.org/reference/hooks/init/
 * @hooked action init
 *
 * @return void
 */
function remove_action_and_filter_hooks(): void {
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}

add_action( 'init', __NAMESPACE__ . '\\remove_action_and_filter_hooks', PHP_INT_MAX );

/**
 * Remove TinyMCE emoji plugin
 *
 * @link   https://developer.wordpress.org/reference/hooks/tiny_mce_plugins/
 * @hooked filter tiny_mce_plugins
 *
 * @param array $plugins Existing TinyMCE plugins.
 * @return array
 */
function remove_tiny_mce_plugin( array $plugins ): array {

	return array_diff( $plugins, array( 'wpemoji' ) );
}

add_filter( 'tiny_mce_plugins', __NAMESPACE__ . '\\remove_tiny_mce_plugin', PHP_INT_MAX );
