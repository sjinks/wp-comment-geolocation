<?php
/**
 * Plugin Name: WW Comment Geolocation
 * Plugin URI: https://github.com/sjinks/wp-comment-geolocation
 * Description: Adds "IP Geolocation" column to "Manage Comments" screen
 * Author: Volodymyr Kolesnykov
 * Version: 2.0.1
 * License: MIT
 */

use WildWolf\WordPress\CommentGeolocation\Plugin;

defined( 'ABSPATH' ) || die();

if ( defined( 'VENDOR_PATH' ) ) {
	/** @psalm-suppress UnresolvableInclude */
	require_once VENDOR_PATH . '/vendor/autoload.php';
} elseif ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} elseif ( file_exists( ABSPATH . 'vendor/autoload.php' ) ) {
	/** @psalm-suppress UnresolvableInclude */
	require_once ABSPATH . 'vendor/autoload.php';
}

Plugin::instance();
