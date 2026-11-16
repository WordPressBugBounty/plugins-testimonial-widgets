<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
exit;
}
require_once plugin_dir_path( __FILE__ ) . 'testimonials-plugin.class.php';
$wpttstPluginManager = new TrustindexTestimonialsPlugin("Testimonial Widgets", __FILE__, "1.4.14", "Testimonial Widgets");
$wpttstPluginManager->pluginUninstall();
?>