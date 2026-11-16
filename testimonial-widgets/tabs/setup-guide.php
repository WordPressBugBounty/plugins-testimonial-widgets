<?php
defined('ABSPATH') or die('No script kiddies please!');
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
<?php
$wpttstReviewsReached = 3 <= count($wpttstPluginManager->getReviews()) ? true : false;
$wpttstHasWidget = count($wpttstPluginManager->getWidgets()) ? true : false;
$wpttstVisitedGetReviews = (get_option( 'wp-testimonials-visited-get-reviews') === 'yes');
?>

<div class="ti-box">
<div class="ti-header text-center"><?php echo esc_html(sprintf(/* translators: %s: plugin name */ __('Welcome to the %s plugin guide.', 'testimonial-widgets'), "WP Testimonials")); ?></div>
<div class="ti-testimonials-setup-guide">
<a href="edit.php?post_type=wpt-testimonial">
<div class="ti-step<?php if ($wpttstReviewsReached) : ?> done<?php endif; ?>">
<div class="ti-icon">1</div>
<div class="ti-details">
<h2><?php echo esc_html__("Upload your customer's testimonials", 'testimonial-widgets'); ?></h2>
<p><?php echo esc_html__("Write at least 3 testimonials to display them in your website nicely", 'testimonial-widgets'); ?></p>
</div>
</div>
</a>
<a href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/create-widget.php'; ?>">
<div class="ti-step<?php if ($wpttstHasWidget) : ?> done<?php endif; ?><?php if (!$wpttstReviewsReached) : ?> disabled<?php endif; ?>">
<div class="ti-icon">2</div>
<div class="ti-details">
<h2><?php echo esc_html__('Display your testimonials', 'testimonial-widgets'); ?></h2>
<p><?php echo esc_html(sprintf(/* translators: 1: number of widget types, 2: number of widget styles */ __('You can select from %1$s widget type and %2$s widget style', 'testimonial-widgets'), "21", "25")); ?></p>
</div>
</div>
</a>
<a href="https://www.trustindex.io/ti-redirect.php?a=wagner2&c=wp-testi-1">
<div class="ti-step<?php if ($wpttstVisitedGetReviews && $wpttstHasWidget && $wpttstReviewsReached) : ?> done<?php endif; ?><?php if (!$wpttstHasWidget || !$wpttstReviewsReached) : ?> disabled<?php endif; ?>">
<div class="ti-icon">3</div>
<div class="ti-details">
<h2><?php echo esc_html__('Create testimonials widget with real customer reviews', 'testimonial-widgets'); ?></h2>
<p><?php echo esc_html(sprintf(/* translators: 1: list of review platforms, 2: number of review platforms */ __('Add reviews from %1$s and other platforms to your website. %2$s review platforms to choose from. Create an account and make your first widget.', 'testimonial-widgets'), 'Google, Facebook, Trustpilot, Airbnb, Booking, Tripadvisor', "137")); ?></p>
</div>
</div>
</a>
</div>
</div>
</div>
</div>
</div>