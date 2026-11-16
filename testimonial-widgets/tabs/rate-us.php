<?php
defined('ABSPATH') or die('No script kiddies please!');
wp_enqueue_script('trustindex_testimonials_loader_js', 'https://cdn.trustindex.io/' . 'loader.js', [], '1.4.14', true);
?>
<div id="testimonial-widgets-plugin-settings-page" class="ti-toggle-opacity">
<h1 class="ti-free-title">
<?php echo esc_html("WP Testimonials"); ?>
<a href="https://www.trustindex.io" target="_blank" title="Trustindex" class="ti-pull-right">
<img src="<?php echo esc_url($wpttstPluginManager->getPluginFileUrl('static/img/trustindex.svg')); ?>" />
</a>
</h1>
<div class="container_wrapper">
<div class="container_cell" id="container-main">
<?php $wpttstPluginManager->generatePageMenu(); ?>
<div class="ti-box rate-us en">
<div class="ti-box-head">
<div class="ti-row">
<div class="ti-col">
<span class="wp-heading-inline"><?php echo esc_html__('Please help us by reviewing our Plugin.', 'testimonial-widgets'); ?></span>
<p><?php echo esc_html__("We've spent a lot of time developing this plugin. If you use the plugin, you can still support us by leaving a review!", 'testimonial-widgets'); ?></p>
<p><?php echo esc_html__('Thank you in advance!', 'testimonial-widgets'); ?></p>
</div>
<div class="ti-col-auto rate-us-wrapper">
<a class="btn-text btn-lg" href="https://wordpress.org/support/plugin/<?php echo esc_attr($wpttstPluginManager->getPluginSlug()); ?>/reviews/?rate=5#new-post" target="_blank"><?php echo esc_html__('Click here to rate us!', 'testimonial-widgets'); ?></a>
</div>
</div>
</div>
<hr>
<div class="ti-row">
<div class="ti-col-12">
<div src="<?php echo 'https://cdn.trustindex.io/' . 'loader.js?1f2d73951f873073bd54ce1956'; ?>"></div>
</div>
</div>
</div>
</div>
</div>
</div>