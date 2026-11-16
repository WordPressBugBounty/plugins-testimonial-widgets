<?php
/*
Plugin Name: WP Testimonials
Plugin Title: WP Testimonials Plugin
Plugin URI: https://wordpress.org/plugins/testimonial-widgets/
Description: Display your Testimonials on your website fast and easily. 21 widget types, 25 widget styles available. (Free Plugin)
Tags: reviews, ratings, recommendations, testimonials, widget, slider, review, rating, recommendation, testimonial, customer review
Author: Trustindex.io <support@trustindex.io>
Author URI: https://www.trustindex.io/
Contributors: trustindex
License: GPLv2 or later
Version: 1.4.14
Requires at least: 6.2
Requires PHP: 7.2
Text Domain: testimonial-widgets
Domain Path: /languages/
Donate link: https://www.trustindex.io/prices/
*/
/*
Copyright 2021 Trustindex Kft (email: support@trustindex.io)
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once plugin_dir_path( __FILE__ ) . 'testimonials-plugin.class.php';
$wpttstPluginManager = new TrustindexTestimonialsPlugin("Testimonials", __FILE__, "1.4.14", "WP Testimonials");
add_action('admin_menu', array($wpttstPluginManager, 'addSettingMenu'), 10);
add_filter('parent_file', array($wpttstPluginManager, 'menuHighlight'));
add_filter('plugin_action_links', array($wpttstPluginManager, 'addPluginActionLinks'), 10, 2);
add_filter('plugin_row_meta', array($wpttstPluginManager, 'addPluginMetaLinks'), 10, 2);
/* Add menu to custom post type & category pages
*/
add_action( 'load-edit.php', array($wpttstPluginManager, 'generateCptPageMenu'), 10 );
add_action( 'load-post.php', array($wpttstPluginManager, 'generateCptPageMenu'), 10 );
add_action( 'load-post-new.php', array($wpttstPluginManager, 'generateCptPageMenu'), 10 );
add_action( 'load-edit-tags.php', array($wpttstPluginManager, 'generateCptPageMenu'), 10 );
add_action('admin_enqueue_scripts', array($wpttstPluginManager, 'trustindexTestimonialsAddScripts'));
add_action( 'after_setup_theme', array($wpttstPluginManager, 'setTestimonialImage'));
add_filter( 'manage_edit-wpt-testimonial_columns', array($wpttstPluginManager, 'editColumns' ) );
add_action( 'manage_wpt-testimonial_posts_custom_column', array($wpttstPluginManager, 'customColumns' ) );
add_action('init', array($wpttstPluginManager, 'initShortcode'));
add_action('plugins_loaded', array($wpttstPluginManager, 'pluginLoaded'));
register_activation_hook( __FILE__, array( $wpttstPluginManager, 'pluginActivation' ) );
register_activation_hook( __FILE__, array( $wpttstPluginManager, 'createWidgetsTable' ) );
register_deactivation_hook( __FILE__, array( $wpttstPluginManager, 'pluginDeactivation' ) );
?>
