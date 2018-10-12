<?php
/*
 Plugin Name: WW Comment Geolocation
 Plugin URI: 
 Description: Adds "IP Geolocation" column to "Manage Comments" screen
 Author: Volodymyr Kolesnykov
 Version: 1.0.0
 License: MIT
 Network:
*/

defined('ABSPATH') || die();

if (defined('VENDOR_PATH')) {
	require VENDOR_PATH . '/vendor/autoload.php';
}
elseif (file_exists(__DIR__ . '/vendor/autoload.php')) {
	require __DIR__ . '/vendor/autoload.php';
}
elseif (file_exists(ABSPATH . 'vendor/autoload.php')) {
	require ABSPATH . 'vendor/autoload.php';
}

WildWolf\CommentGeolocation\Plugin::instance();
