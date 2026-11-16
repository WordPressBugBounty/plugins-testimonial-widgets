<?php
defined('ABSPATH') or die('No script kiddies please!');
include(plugin_dir_path(__FILE__) . "create-widget-header.php");
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
<div class="tab-setup_no_reg">
<ul class="ti-free-steps">
<li class="<?php echo $wpttstSelected || $wpttstWidget['saved'] >= 1 ? "done" : "active"; ?><?php if ($wpttstCurrentStep == 1) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=1&id=<?php echo esc_attr($id); ?>">
<span>1</span>
<?php echo esc_html__('Select testimonials', 'testimonial-widgets'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $wpttstStyleId || $wpttstWidget['saved'] >= 2 ? "done" : ($wpttstSelected || $wpttstWidget['saved'] == 1 ? "active" : ""); ?><?php if ($wpttstCurrentStep == 2) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=2&id=<?php echo esc_attr($id); ?>&selected">
<span>2</span>
<?php echo esc_html__('Select Layout', 'testimonial-widgets'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo ($wpttstWidget['2'] == '17' || $wpttstWidget['2'] == '21') ? '' : ($wpttstScssSet || $wpttstWidget['saved'] >= 3 ? "done" : ($wpttstStyleId || $wpttstWidget['saved'] == 2 ? "active" : "")); ?><?php if ($wpttstCurrentStep == 3) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=3&id=<?php echo esc_attr($id); ?>&selected<?php echo $wpttstWidget['2'] ? "&style_id=" . esc_attr($wpttstWidget['2']) : ""; ?>">
<span>3</span>
<?php echo esc_html__('Select Style', 'testimonial-widgets'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $wpttstWidgetSettedUp || $wpttstWidget['saved'] >= 4 ? "done" : ($wpttstScssSet || $wpttstWidget['saved'] == 3 ? "active" : ""); ?><?php if ($wpttstCurrentStep == 4) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=4&id=<?php echo esc_attr($id); ?>&selected<?php echo $wpttstWidget['2'] ? "&style_id=" . esc_attr($wpttstWidget['2']) : ""; ?><?php echo $wpttstWidget['3'] ? "&scss_set=" . esc_attr($wpttstWidget['3']) : ""; ?>">
<span>4</span>
<?php echo esc_html__('Setup widget', 'testimonial-widgets'); ?>
</li>
<span class="ti-free-arrow"></span>
<li class="<?php echo $wpttstWidget['saved'] == 5 ? "done" : ($wpttstWidgetSettedUp || $wpttstWidget['saved'] == 4 ? "active" : ""); ?><?php if ($wpttstCurrentStep == 5) : ?> current<?php endif; ?>" href="?page=<?php echo esc_attr($page); ?>&step=5&&id=<?php echo esc_attr($id); ?>selected&setup_widget<?php echo $wpttstWidget['2'] ? "&style_id=" . esc_attr($wpttstWidget['2']) : ""; ?><?php echo $wpttstWidget['3'] ? "&scss_set=" . esc_attr($wpttstWidget['3']) : ""; ?>">
<span>5</span>
<?php echo esc_html__('Get shortcode', 'testimonial-widgets'); ?>
</li>
</ul>
<?php if ($wpttstCurrentStep == 1) : ?>
<hr>
<h1 class="ti-free-title">
1. <?php echo esc_html__('Select testimonials', 'testimonial-widgets'); ?>
</h1>
<div class="ti-box">
<br />
<div class="ti-row">
<div class="ti-col">
<?php /*
<?php if ($current_step == 1) : ?>
*/ ?>
<span class="wp-heading-inline"><?php echo esc_html__('Add widget name', 'testimonial-widgets'); ?></span>

<?php /*
<?php else : ?>
<span class="wp-heading-inline"><?php echo TrustindexTestimonialsPlugin::___('Add widget name'); ?></span>
 <a href="<?php echo esc_url('admin.php?page=' . $trustindex_testimonials_pm->get_plugin_slug() . '/tabs/create-widget.php'); ?>" class="button btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Add new"); ?></a>
<a href="/wp-admin/admin.php?page=testimonial-widgets/tabs/index-widget.php" class="button btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Back to list"); ?></a>
<a href="<?php echo esc_url('admin.php?page=' . $trustindex_testimonials_pm->get_plugin_slug() . '/tabs/index-widget.php&duplicate_widget=' . $id); ?>" class="button btn-refresh ti-pull-right" data-loading-text="<?php echo TrustindexTestimonialsPlugin::___("Loading"); ?>"><?php echo TrustindexTestimonialsPlugin::___("Duplicate widget"); ?></a>
<?php endif; ?>
*/ ?>
</div>
</div>
<br />
<div class="ti-row">
<div class="ti-col">
<input class="form-control" placeholder="<?php echo 'Widget - ' . esc_attr($wpttstNextWidgetId); ?>" id="widget-name" type="text" data-default-name="<?php echo 'Widget - ' . esc_attr($wpttstNextWidgetId); ?>" value="<?php echo ($id && $wpttstWidgetName) ? esc_attr($wpttstWidgetName) : ''; ?>" <?php echo $wpttstCurrentStep > 1 ? 'disabled' : ''; ?>>
</div>
</div>
<?php if ($wpttstNameMissing): ?>
<div class="ti-row">
<div class="ti-col">
<div class="wp-warning-box">
<span class='dashicons dashicons-warning'></span>
<span><?php echo esc_html__("Widget title required!", 'testimonial-widgets'); ?></span>
</div>
</div>
</div>
<?php endif; ?>
</div>
<form method="post" action="#">
<input type="hidden" name="command" value="save-review-filter" /><?php wp_nonce_field('ti-save-widget'); ?>
<input type="hidden" name="widget-name" value="<?php echo 'Widget - ' . esc_attr($wpttstNextWidgetId); ?>" />
<div class="ti-box">
<?php if (count($wpttstReviews) < 3): ?>
<div class="ti-row">
<div class="ti-col">
<div class="wp-info-box">
<span class='dashicons dashicons-info'></span>
<strong><?php echo esc_html__("INFO", 'testimonial-widgets'); ?></strong><br />
<?php echo esc_html__('Please upload at least 3 testimonials to display them in your website nicely!', 'testimonial-widgets'); ?>
<a href="edit.php?post_type=wpt-testimonial"> <?php echo esc_html__('Upload your testimonials here!', 'testimonial-widgets'); ?></a>
</div>
</div>
</div>
<?php endif; ?>
<p><?php echo esc_html__("Display testimonials", 'testimonial-widgets'); ?>:</p>
<input type="radio" id="display_testimonials_all" name="mode" value="all" <?php echo $wpttstWidget['1']['mode'] == 'all' ? 'checked' : '' ?>>
<label for="display_testimonials_all"><?php echo esc_html__("All", 'testimonial-widgets'); ?></label><br>
<?php if (count($wpttstCategories)): ?>
<input type="radio" id="display_testimonials_categories" name="mode" value="category" <?php echo $wpttstWidget['1']['mode'] == 'category' ? 'checked' : '' ?>>
<label for="display_testimonials_categories"><?php echo esc_html__("Categories", 'testimonial-widgets'); ?></label>
<br>
<div id="category_select" <?php echo $wpttstWidget['1']['mode'] !== 'category' ? 'style="display: none;"' : '' ?>>
<hr>
<div class="ti-input-row">
<label><?php echo esc_html__('Select category', 'testimonial-widgets'); ?></label>
<select class="form-control" name="category" id="ti-category-id">
<?php foreach ($wpttstCategories as $wpttstCategory) : ?>
<option value="<?php echo esc_attr($wpttstCategory['term_id']); ?>" <?php echo $wpttstWidget['1']['mode'] == 'category' ? ($wpttstWidget['1']['selected'] == $wpttstCategory['term_id'] ? 'selected' : '') : '' ?>><?php echo esc_attr($wpttstCategory['name']); ?></option>
<?php endforeach; ?>
</select>
</div>
<hr>
</div>
<?php endif; ?>
<input type="radio" id="display_testimonials_manual" name="mode" value="manual_select" <?php echo $wpttstWidget['1']['mode'] == 'manual_select' ? 'checked' : '' ?>>
<label for="display_testimonials_manual"><?php echo esc_html__("Select manually", 'testimonial-widgets'); ?></label>
<div id="review_select" <?php echo $wpttstWidget['1']['mode'] !== 'manual_select' ? 'style="display: none;"' : '' ?>>
<hr>
<div class="ti-input-row">
<label><?php echo esc_html__('Select testimonials', 'testimonial-widgets'); ?></label>
<?php foreach ($wpttstReviews as $wpttstK => $wpttstReview) : ?>
<input type="checkbox" id="review_<?php echo esc_attr($wpttstReview['id']); ?>" name="review[<?php echo esc_attr($wpttstReview['id']); ?>]" value="<?php echo esc_attr($wpttstReview['id']); ?>" <?php echo $wpttstWidget['1']['mode'] == 'manual_select' ? (in_array($wpttstReview['id'], explode(',', $wpttstWidget['1']['selected'])) ? 'checked' : '') : '' ?>>
<label for="review_<?php echo esc_attr($wpttstReview['id']); ?>" class="ti-review-box">
<div class="ti-row">
<div class="ti-col">
<span style="font-size:16px;"><b><?php echo esc_attr($wpttstReview['title']); ?></b></span>
</div>
<div class="ti-col">
<span class="strong-rating">
<?php
for ($wpttstI = 1; $wpttstI <= 5; $wpttstI++) {
if ($wpttstI <= $wpttstReview['star_rating']) {
echo '<img height="20px" width="20px" src=' . esc_url($wpttstPluginManager->getFullStar()) . ' alt="full star"></img>';
} else {
echo '<img height="20px" width="20px" src=' . esc_url($wpttstPluginManager->getEmptyStar()) . ' alt="empty star"></img>';
}
}
?>
</span>
</div>
<div class="ti-col">
<span class="ti-pull-right"><?php echo esc_attr($wpttstReview['date']); ?></span>
</div>
</div>
<div class="ti-row">
<?php if ($wpttstReview['photo'][120]): ?>
<span style="max-width: 120px; padding: 10px;"><img src="<?php echo esc_url($wpttstReview['photo'][120]); ?>" alt="photo-<?php echo esc_attr($wpttstReview['client_name']); ?>"></span>
<?php endif; ?>
<div class="ti-col">
<p><?php echo wp_kses_post($wpttstReview['content']); ?></p>
</div>
</div>
<div class="ti-row">
<?php if ($wpttstReview['client_name']) : ?>
<div class="ti-col">
<span><?php echo esc_html__('Client name', 'testimonial-widgets'); ?>: <?php echo esc_attr($wpttstReview['client_name']); ?></span>
</div>
<?php endif; ?>
<?php if ($wpttstReview['company_name']) : ?>
<div class="ti-col">
<span><?php echo esc_html__('Company name', 'testimonial-widgets'); ?>: <?php echo esc_attr($wpttstReview['company_name']); ?></span>
</div>
<?php endif; ?>
<?php if ($wpttstReview['company_website']) : ?>
<div class="ti-col">
<span><?php echo esc_html__('Website', 'testimonial-widgets'); ?>: <?php echo esc_attr($wpttstReview['company_website']); ?></span>
</div>
<?php endif; ?>
</div>
</label>
<?php endforeach; ?>
</div>
<hr>
</div>
</div>
<div id="star_select" <?php echo $wpttstWidget['1']['mode'] == 'manual_select' ? 'style="display: none;"' : '' ?>>
<div class="ti-box">
<p><?php echo esc_html__('Rating filter', 'testimonial-widgets'); ?>:</p>
<select class="form-control" name="rating" id="ti-rating-filter-id">
<option value="all" <?php echo $wpttstWidget['1']['rating'] == 'all' ? 'selected' : ''; ?>><?php echo esc_html__('Show all', 'testimonial-widgets'); ?></option>
<option value="only_5" <?php echo $wpttstWidget['1']['rating'] == 'only_5' ? 'selected' : ''; ?>><?php echo esc_html__('Only ', 'testimonial-widgets'); ?>&starf;&starf;&starf;&starf;&starf;</option>
<option value="min_4" <?php echo $wpttstWidget['1']['rating'] == 'min_4' ? 'selected' : ''; ?>><?php echo esc_html__('Minimum ', 'testimonial-widgets'); ?>&starf;&starf;&starf;&starf;</option>
<option value="min_3" <?php echo $wpttstWidget['1']['rating'] == 'min_3' ? 'selected' : ''; ?>><?php echo esc_html__('Minimum ', 'testimonial-widgets'); ?>&starf;&starf;&starf;</option>
<option value="max_3" <?php echo $wpttstWidget['1']['rating'] == 'max_3' ? 'selected' : ''; ?>><?php echo esc_html__('Maximum ', 'testimonial-widgets'); ?>&starf;&starf;&starf;</option>
</select>
</div>
</div>
<input type="submit" class="btn-text btn-refresh ti-pull-right" value="<?php echo esc_attr__("Next", 'testimonial-widgets'); ?>">
</form>
<?php elseif ($wpttstCurrentStep == 2 || !$wpttstStyleId) : ?>
<h1 class="ti-free-title">
2. <?php echo esc_html__('Select Layout', 'testimonial-widgets'); ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=1&id=<?php echo esc_attr($id); ?>" class="ti-back-icon"><?php echo esc_html__('Back', 'testimonial-widgets'); ?></a>
</h1>
<?php if (!count($wpttstReviews)) : ?>
<div class="notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo esc_html__('There are no testimonials', 'testimonial-widgets'); ?>
</p>
</div>
<?php else : ?>
<div class="ti-filter-row">
<label><?php echo esc_html__('Layout', 'testimonial-widgets'); ?>:</label>
<span class="ti-checkbox">
<input type="radio" name="layout-select" value="" data-ids="" checked>
<label><?php echo esc_html__('All', 'testimonial-widgets'); ?></label>
</span>
<?php foreach (TrustindexTestimonialsPlugin::$widgetTemplates['categories'] as $wpttstCategory => $wpttstIds) : ?>
<span class="ti-checkbox">
<input type="radio" name="layout-select" value="<?php echo esc_attr($wpttstCategory); ?>" data-ids="<?php echo esc_attr($wpttstIds); ?>">
<label><?php echo esc_html(ucfirst($wpttstCategory)); ?></label>
</span>
<?php endforeach; ?>
</div>
<form action="">
<div class="ti-preview-boxes-container">
<input type="hidden" name="widget-name" value="" />
<?php foreach (TrustindexTestimonialsPlugin::$widgetTemplates['templates'] as $wpttstStyleId => $wpttstTemplate) : ?>
<?php
$wpttstClassName = 'ti-full-width';
if (in_array($wpttstTemplate['type'], ['badge', 'button', 'floating', 'popup', 'sidebar', 'top-rated-badge'])) {
$wpttstClassName = 'ti-half-width';
}
?>
<div class="<?php echo esc_attr($wpttstClassName); ?>">
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo esc_attr($wpttstStyleId); ?>" data-set-id="light-background">
<div class="ti-header">
<span class="ti-header-layout-text">
<?php echo esc_html__('Layout', 'testimonial-widgets'); ?>:
<strong><?php echo esc_html($wpttstTemplate['name']); ?></strong>
</span>
<a href="?page=<?php echo esc_attr($page); ?>&step=3&id=<?php echo esc_attr($id); ?>&command=save-style&_wpnonce=<?php echo esc_attr(wp_create_nonce('ti-save-widget')); ?>&selected&style_id=<?php echo esc_attr($wpttstStyleId); ?>" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo esc_html__("Loading", 'testimonial-widgets'); ?>"><?php echo esc_html__("Select", 'testimonial-widgets'); ?></a>
<?php if ($wpttstTemplate['image'] > 120): ?>
<br />
<span class="ti-header-layout-text">
<?php echo esc_html(sprintf(/* translators: 1: minimum image width in pixels, 2: minimum image height in pixels */ __('Recommended minimum image size is %1$s x %2$s pixel', 'testimonial-widgets'), esc_attr($wpttstTemplate['image']), esc_attr($wpttstTemplate['image']))); ?>
</span>
<?php endif; ?>
<div class="clear"></div>
</div>
<div class="preview">
<?php echo wp_kses_post($wpttstPluginManager->getNoregListReviews($id, true, $wpttstStyleId, 'light-background', true)); ?>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
</form>
<?php endif; ?>
<?php elseif ($wpttstCurrentStep == 3 || !$wpttstScssSet) : ?>
<h1 class="ti-free-title">
3. <?php echo esc_html__('Select Style', 'testimonial-widgets'); ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=2&id=<?php echo esc_attr($id); ?>&selected" class="ti-back-icon"><?php echo esc_html__('Back', 'testimonial-widgets'); ?></a>
</h1>
<?php if (!count($wpttstReviews)) : ?>
<div class="notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo esc_html__('There are no testimonials', 'testimonial-widgets'); ?>
</p>
</div>
<?php else : ?>
<?php
$wpttstClassName = 'ti-full-width';
if (in_array(TrustindexTestimonialsPlugin::$widgetTemplates['templates'][$wpttstStyleId]['type'], ['badge', 'button', 'floating', 'popup', 'sidebar', 'top-rated-badge'])) {
$wpttstClassName = 'ti-half-width';
}
?>
<div class="ti-preview-boxes-container">
<?php foreach (TrustindexTestimonialsPlugin::$widgetStyles as $wpttstScssId => $wpttstStyle) : ?>
<div class="<?php echo esc_attr($wpttstClassName); ?>">
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo esc_attr($wpttstStyleId); ?>" data-set-id="<?php echo esc_attr($wpttstScssId); ?>">
<div class="ti-header">
<span class="ti-header-layout-text">
<?php echo esc_html__('Style', 'testimonial-widgets'); ?>:
<strong><?php echo esc_html($wpttstStyle['name']); ?></strong>
</span>
<a href="?page=<?php echo esc_attr($page); ?>&step=4&id=<?php echo esc_attr($id); ?>&command=save-set&_wpnonce=<?php echo esc_attr(wp_create_nonce('ti-save-widget')); ?>&selected&style_id=<?php echo esc_attr($wpttstStyleId); ?>&scss_set=<?php echo esc_attr($wpttstScssId); ?>" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo esc_html__("Loading", 'testimonial-widgets'); ?>"><?php echo esc_html__("Select", 'testimonial-widgets'); ?></a>
<div class="clear"></div>
</div>
<div class="preview">
<?php echo wp_kses_post($wpttstPluginManager->getNoregListReviews($id, true, $wpttstStyleId, $wpttstScssId, true)); ?>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php elseif ($wpttstCurrentStep == 4 || !$wpttstWidgetSettedUp) : ?>
<?php
$wpttstWidgetType = TrustindexTestimonialsPlugin::$widgetTemplates['templates'][$wpttstStyleId]['type'];
$wpttstWidgetHasReviews = !in_array($wpttstWidgetType, ['button', 'badge', 'top-rated-badge']) || in_array($wpttstStyleId, [23, 30, 32]);
?>
<h1 class="ti-free-title">
4. <?php echo esc_html__('Set up widget', 'testimonial-widgets'); ?>
<?php if ($wpttstWidget['2'] == '17' || $wpttstWidget['2'] == '21'): ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=2&id=<?php echo esc_attr($id); ?>&selected" class="ti-back-icon"><?php echo esc_html__('Back', 'testimonial-widgets'); ?></a>
<?php else: ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=3&id=<?php echo esc_attr($id); ?>&selected&style_id=<?php echo esc_attr($wpttstStyleId); ?>" class="ti-back-icon"><?php echo esc_html__('Back', 'testimonial-widgets'); ?></a>
<?php endif; ?>
</h1>
<?php if (!count($wpttstReviews)) : ?>
<div class="notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo esc_html__('There are no testimonials', 'testimonial-widgets'); ?>
</p>
</div>
<?php else : ?>
<div class="ti-box ti-preview-boxes" data-layout-id="<?php echo esc_attr($wpttstStyleId); ?>" data-set-id="<?php echo esc_attr($wpttstScssSet); ?>">
<div class="ti-header">
<?php echo esc_html__('Widget Preview', 'testimonial-widgets'); ?>
<?php if (!in_array($wpttstStyleId, [17, 21])) : ?>
<span class="ti-header-layout-text ti-pull-right">
<?php echo esc_html__('Style', 'testimonial-widgets'); ?>:
<strong><?php echo esc_html(TrustindexTestimonialsPlugin::$widgetStyles[$wpttstScssSet]['name']); ?></strong>
</span>
<?php endif; ?>
<span class="ti-header-layout-text ti-pull-right">
<?php echo esc_html__('Layout', 'testimonial-widgets'); ?>:
<strong><?php echo esc_html(TrustindexTestimonialsPlugin::$widgetTemplates['templates'][$wpttstStyleId]['name']); ?></strong>
</span>
</div>
<div class="preview">
<div id="ti-review-list"><?php echo wp_kses_post($wpttstPluginManager->getNoregListReviews($id, true, $wpttstStyleId, $wpttstScssSet)); ?></div>
<div style="display: none; text-align: center">
<?php echo wp_kses_post(__("You do not have reviews with the current filters. <br />Change your filters if you would like to display reviews on your page!", 'testimonial-widgets')); ?>
</div>
</div>
</div>
<div class="ti-box">
<div class="ti-header"><?php echo esc_html__('Widget Settings', 'testimonial-widgets'); ?></div>
<?php /*
<div class="tab">
<button class="tablinks" id="<?php echo $widget['4']['last_saved'] == 'general' ? 'active-tab' : '' ?>" onclick="openTab(event, 'General')"><?php echo TrustindexTestimonialsPlugin::___("General"); ?></button>
<button class="tablinks" id="<?php echo $widget['4']['last_saved'] == 'appearance' ? 'active-tab' : '' ?>" onclick="openTab(event, 'Appearance')"><?php echo TrustindexTestimonialsPlugin::___("Appearance"); ?></button>
<button class="tablinks" id="<?php echo $widget['4']['last_saved'] == 'navigation' ? 'active-tab' : '' ?>" onclick="openTab(event, 'Navigation')"><?php echo TrustindexTestimonialsPlugin::___("Navigation"); ?></button>
</div>
*/ ?>
<p class="ti-settings-title"><?php echo esc_html__('General', 'testimonial-widgets'); ?></p>
<!-- <div id="General" class="tabcontent"> -->
<div class="ti-input-row">
<label><?php echo esc_html__('Order', 'testimonial-widgets'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-order" /><?php wp_nonce_field('ti-save-widget'); ?>
<select class="form-control" name="order" id="ti-order-id">
<option value="newest" <?php echo $wpttstWidget['4']['general']['order'] == 'newest' ? 'selected' : ''; ?>><?php echo esc_html__('Newest', 'testimonial-widgets'); ?></option>
<option value="oldest" <?php echo $wpttstWidget['4']['general']['order'] == 'oldest' ? 'selected' : ''; ?>><?php echo esc_html__('Oldest', 'testimonial-widgets'); ?></option>
<option value="random" <?php echo $wpttstWidget['4']['general']['order'] == 'random' ? 'selected' : ''; ?>><?php echo esc_html__('Random', 'testimonial-widgets'); ?></option>
</select>
</form>
</div>
<form method="post" action="" id="ti-widget-options">
<input type="hidden" name="command" value="save-display-options" /><?php wp_nonce_field('ti-save-widget'); ?>
<span class="ti-checkbox row">
<input type="checkbox" name="hide_stars" value="1" <?php echo (isset($wpttstWidget['4']['appearance']['hide_stars']) && $wpttstWidget['4']['appearance']['hide_stars']) ? 'checked' : ''; ?>>
<label><?php echo esc_html__("Hide stars", 'testimonial-widgets'); ?></label>
</span>
<span class="ti-checkbox row">
<input type="checkbox" name="hide_image" value="1" <?php echo (isset($wpttstWidget['4']['appearance']['hide_image']) && $wpttstWidget['4']['appearance']['hide_image']) ? 'checked' : ''; ?>>
<label><?php echo esc_html__("Hide user image", 'testimonial-widgets'); ?></label>
</span>
<?php if ($wpttstWidget['2'] != '17' && $wpttstWidget['2'] != '21'): ?>
<span class="ti-checkbox row">
<input type="checkbox" name="auto_height" value="1" <?php echo (isset($wpttstWidget['4']['appearance']['auto_height']) && $wpttstWidget['4']['appearance']['auto_height']) ? 'checked' : ''; ?>>
<label><?php echo esc_html__("Review height auto", 'testimonial-widgets'); ?></label>
</span>
<?php endif; ?>
<span class="ti-checkbox row">
<input type="checkbox" name="enable-font" value="1" <?php echo (isset($wpttstWidget['4']['appearance']['enable-font']) && $wpttstWidget['4']['appearance']['enable-font']) ? 'checked' : ''; ?>>
<label><?php echo esc_html__("Use site's font", 'testimonial-widgets'); ?></label>
</span>
<span class="ti-checkbox row">
<input type="checkbox" name="hover-anim" value="1" <?php echo (isset($wpttstWidget['4']['appearance']['hover-anim']) && $wpttstWidget['4']['appearance']['hover-anim']) ? 'checked' : ''; ?>>
<label><?php echo esc_html__("Enable mouse over anim", 'testimonial-widgets'); ?></label>
</span>
</form>
<?php if (in_array($wpttstWidget['2'],array('10','16', '17', '18', '19', '21', '31', '33', '37', '38', '48')) == false ): ?>
<br />
<div class="ti-input-row" id="arrow-selector">
<label><?php echo esc_html__('Navigation arrow', 'testimonial-widgets'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-arrow" /><?php wp_nonce_field('ti-save-widget'); ?>
<select class="form-control" name="arrow" id="ti-arrow-id">
<option value="false" <?php echo (isset($wpttstWidget['4']['appearance']['nav']) && $wpttstWidget['4']['appearance']['nav'] == 'false') ? 'selected' : ''; ?>><?php echo esc_html__('Hide', 'testimonial-widgets'); ?></option>
<option value="mobile" <?php echo (isset($wpttstWidget['4']['appearance']['nav']) && $wpttstWidget['4']['appearance']['nav'] == 'mobile') ? 'selected' : ''; ?>><?php echo esc_html__('Mobile only', 'testimonial-widgets'); ?></option>
<option value="desktop" <?php echo (isset($wpttstWidget['4']['appearance']['nav']) && $wpttstWidget['4']['appearance']['nav'] == 'desktop') ? 'selected' : ''; ?>><?php echo esc_html__('Desktop only', 'testimonial-widgets'); ?></option>
<option value="true" <?php echo (isset($wpttstWidget['4']['appearance']['nav']) && $wpttstWidget['4']['appearance']['nav'] == 'true') ? 'selected' : ''; ?>><?php echo esc_html__('Show', 'testimonial-widgets'); ?></option>
</select>
</form>
</div>
<?php endif; ?>
<?php if (in_array($wpttstWidget['2'],array('10','16', '17', '18', '19', '21', '31', '33', '37', '38', '48')) == false ): ?>
<br />
<div class="ti-input-row" id="dots-selector">
<label><?php echo esc_html__('Navigation dots', 'testimonial-widgets'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-dots" /><?php wp_nonce_field('ti-save-widget'); ?>
<select class="form-control" name="dots" id="ti-dots-id">
<option value="false" <?php echo (isset($wpttstWidget['4']['appearance']['dots']) && $wpttstWidget['4']['appearance']['dots'] == 'false') ? 'selected' : ''; ?>><?php echo esc_html__('Hide', 'testimonial-widgets'); ?></option>
<option value="mobile" <?php echo (isset($wpttstWidget['4']['appearance']['dots']) && $wpttstWidget['4']['appearance']['dots'] == 'mobile') ? 'selected' : ''; ?>><?php echo esc_html__('Mobile only', 'testimonial-widgets'); ?></option>
<option value="true" <?php echo (isset($wpttstWidget['4']['appearance']['dots']) && $wpttstWidget['4']['appearance']['dots'] == 'true') ? 'selected' : ''; ?>><?php echo esc_html__('Show', 'testimonial-widgets'); ?></option>
</select>
</form>
</div>
<?php endif; ?>
<br />
<div class="ti-input-row" id="dateformat-selector">
<label><?php echo esc_html__('Select date format', 'testimonial-widgets'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-dateformat" /><?php wp_nonce_field('ti-save-widget'); ?>
<select class="form-control" name="date_format" id="ti-dateformat-id">
<?php foreach (TrustindexTestimonialsPlugin::$widgetDateformats as $wpttstFormat) : ?>
<option value="<?php echo esc_attr($wpttstFormat); ?>" <?php echo $wpttstWidget['4']['appearance']['date_format'] == $wpttstFormat ? 'selected' : ''; ?>><?php echo esc_attr(gmdate($wpttstFormat)); ?></option>
<?php endforeach; ?>
</select>
</form>
</div>
<?php if (in_array($wpttstWidget['2'],array('4','5','13','15','19','34','36','37','39','44','45','46','47')) == true ): ?>
<br />
<div class="ti-input-row">
<form method="post" action="" id="ti-slider-interval">
<label><?php echo esc_html__("Slider interval", 'testimonial-widgets'); ?></label>
<input type="hidden" name="command" value="save-slider-interval" /><?php wp_nonce_field('ti-save-widget'); ?>
<div class="ti-input-group">
<input type="number" min="0" max="20" name="slider-interval" class="ti-form-control" value="<?php echo isset($wpttstWidget['4']['navigation']['slider_interval']) ? esc_attr($wpttstWidget['4']['navigation']['slider_interval']) : esc_attr($wpttstNavigation['slider_interval']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">sec</span>
</div>
</div>
</form>
</div>
<?php endif;?>
<!-- </div> -->
<br />
<p class="ti-settings-title"><?php echo esc_html__('Review box', 'testimonial-widgets'); ?></p>
<div class="ti-input-row">
<form method="post" action="" id="ti-box">
<input type="hidden" name="command" value="save-box" /><?php wp_nonce_field('ti-save-widget'); ?>
<div class="ti-row">
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Background color", 'testimonial-widgets'); ?></label>
<input type="color" name="box-background-color" value="<?php echo isset($wpttstWidget['4']['appearance']['box-background-color']) ? esc_attr($wpttstWidget['4']['appearance']['box-background-color']) : esc_attr($wpttstAppearance['box-background-color']); ?>" >
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Border color", 'testimonial-widgets'); ?></label>
<input type="color" name="box-border-color" value="<?php echo isset($wpttstWidget['4']['appearance']['box-border-color']) ? esc_attr($wpttstWidget['4']['appearance']['box-border-color']) : esc_attr($wpttstAppearance['box-border-color']); ?>" >
</div>
</div>
</div>
<br />
<div class="ti-row">
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Border padding", 'testimonial-widgets'); ?></label>
<div class="ti-input-group">
<input type="number" min="0" max="100" name="box-padding" class="ti-form-control" value="<?php echo isset($wpttstWidget['4']['appearance']['box-padding']) ? esc_attr($wpttstWidget['4']['appearance']['box-padding']) : esc_attr($wpttstAppearance['box-padding']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Border width", 'testimonial-widgets'); ?></label>
<div class="ti-input-group">
<input type="number" min="0" max="20" name="box-border-width" class="ti-form-control" value="<?php echo isset($wpttstWidget['4']['appearance']['box-border-top-width']) ? esc_attr($wpttstWidget['4']['appearance']['box-border-top-width']) : esc_attr($wpttstAppearance['box-border-top-width']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Border radius", 'testimonial-widgets'); ?></label>
<div class="ti-input-group">
<input type="number" min="0" max="100" name="box-border-radius" class="ti-form-control" value="<?php echo isset($wpttstWidget['4']['appearance']['box-border-radius']) ? esc_attr($wpttstWidget['4']['appearance']['box-border-radius']) : esc_attr($wpttstAppearance['box-border-radius']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
</div>
</form>
</div>
<br />
<p class="ti-settings-title"><?php echo esc_html__('Font settings', 'testimonial-widgets'); ?></p>
<div class="ti-input-row">
<form method="post" action="" id="ti-font">
<input type="hidden" name="command" value="save-font" /><?php wp_nonce_field('ti-save-widget'); ?>
<div class="ti-row">
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Name font size", 'testimonial-widgets'); ?></label>
<div class="ti-input-group">
<input type="number" min="6" max="72" name="profile-font-size" class="ti-form-control" value="<?php echo isset($wpttstWidget['4']['appearance']['profile-font-size']) ? esc_attr($wpttstWidget['4']['appearance']['profile-font-size']) : esc_attr($wpttstAppearance['profile-font-size']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Name font color", 'testimonial-widgets'); ?></label>
<input type="color" name="profile-color" value="<?php echo isset($wpttstWidget['4']['appearance']['profile-color']) ? esc_attr($wpttstWidget['4']['appearance']['profile-color']) : esc_attr($wpttstAppearance['profile-color']); ?>" >
</div>
</div>
</div>
<br />
<div class="ti-row">
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Review font size", 'testimonial-widgets'); ?></label>
<div class="ti-input-group">
<input type="number" min="6" max="72" name="review-font-size" class="ti-form-control" value="<?php echo isset($wpttstWidget['4']['appearance']['review-font-size']) ? esc_attr($wpttstWidget['4']['appearance']['review-font-size']) : esc_attr($wpttstAppearance['review-font-size']); ?>" >
<div class="ti-input-group-append">
<span class="ti-input-group-text">px</span>
</div>
</div>
</div>
</div>
<div class="ti-col-auto">
<div class="ti-input-row">
<label><?php echo esc_html__("Review font color", 'testimonial-widgets'); ?></label>
<input type="color" name="text-color" value="<?php echo isset($wpttstWidget['4']['appearance']['text-color']) ? esc_attr($wpttstWidget['4']['appearance']['text-color']) : esc_attr($wpttstAppearance['text-color']); ?>" >
</div>
</div>
</div>
</form>
</div>
<?php /*
<br />
<div class="ti-input-row" id="review-card-selector">
<label><?php echo TrustindexTestimonialsPlugin::___('Edit review card'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-review-card" />
<div class="ti-row">
<div class="ti-col">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Info color"); ?></label>
<input type="color" name="profile-color" value="<?php echo isset($widget['4']['appearance']['profile-color']) ? esc_attr($widget['4']['appearance']['profile-color']) : esc_attr($appearance['profile-color']); ?>" >
</div>
</div>
<div class="ti-col">
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Review color"); ?></label>
<input type="color" name="text-color" value="<?php echo isset($widget['4']['appearance']['text-color']) ? esc_attr($widget['4']['appearance']['text-color']) : esc_attr($appearance['text-color']);?>" >
</div>
</div>
</div>
</form>
</div>
*/ ?>
<?php /*
<br />
<div class="ti-input-row">
<label><?php echo TrustindexTestimonialsPlugin::___("Display type"); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-display_type" />
<select class="form-control" name="display_type" id="ti-display-type-id">
<option value="website" <?php echo $widget['4']['appearance']['display_type'] === "website" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Website'); ?></option>
<option value="company" <?php echo $widget['4']['appearance']['display_type'] === "company" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Company'); ?></option>
<option value="date" <?php echo $widget['4']['appearance']['display_type'] === "date" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Date'); ?></option>
</select>
</form>
</div>
*/ ?>
<br />
<div class="ti-input-row">
<form method="post" action="" id="ti-review-lines">
<label><?php echo esc_html__("Review lines", 'testimonial-widgets'); ?></label>
<input type="hidden" name="command" value="save-review-lines" /><?php wp_nonce_field('ti-save-widget'); ?>
<div class="ti-input-group">
<input type="number" min="0" max="20" name="review-lines" class="ti-form-control" value="<?php echo isset($wpttstWidget['4']['appearance']['review-lines']) ? esc_attr($wpttstWidget['4']['appearance']['review-lines']) : esc_attr($wpttstAppearance['review-lines']); ?>" >
</div>
</form>
</div>
<br />
<?php if ($wpttstWidget['2'] != '17' && $wpttstWidget['2'] != '21'): ?>
<div class="ti-input-row" id="text-align-selector">
<label><?php echo esc_html__('Align', 'testimonial-widgets'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-text-align" /><?php wp_nonce_field('ti-save-widget'); ?>
<select class="form-control" name="text_align" id="ti-text-align-id">
<option value="left" <?php echo (isset($wpttstWidget['4']['appearance']['text-align']) && $wpttstWidget['4']['appearance']['text-align'] == 'left') ? 'selected' : ''; ?>><?php echo esc_html__('Left', 'testimonial-widgets'); ?></option>
<option value="center" <?php echo (isset($wpttstWidget['4']['appearance']['text-align']) && $wpttstWidget['4']['appearance']['text-align'] == 'center') ? 'selected' : ''; ?>><?php echo esc_html__('Center', 'testimonial-widgets'); ?></option>
<option value="right" <?php echo (isset($wpttstWidget['4']['appearance']['text-align']) && $wpttstWidget['4']['appearance']['text-align'] == 'right') ? 'selected' : ''; ?>><?php echo esc_html__('Right', 'testimonial-widgets'); ?></option>
<option value="justify" <?php echo (isset($wpttstWidget['4']['appearance']['text-align']) && $wpttstWidget['4']['appearance']['text-align'] == 'justify') ? 'selected' : ''; ?>><?php echo esc_html__('Justify', 'testimonial-widgets'); ?></option>
</select>
</form>
</div>
<?php endif; ?>
<!-- </div> -->
<?php /*
<div id="Navigation" class="tabcontent">
<br />
<div class="ti-input-row">
<label><?php echo TrustindexPlugin::___('Navigation style'); ?></label>
<form method="post" action="">
<input type="hidden" name="command" value="save-navigation" />
<select class="form-control" name="navigation_style" id="ti-navigation-id">
<option value="arrow" <?php echo $widget['4']['navigation']['navigation_style'] === "arrow" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Arrow'); ?></option>
<option value="dots" <?php echo $widget['4']['navigation']['navigation_style'] === "dots" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Dots'); ?></option>
<option value="rotate_interval" <?php echo $widget['4']['navigation']['navigation_style'] === "rotate_interval" ? 'selected' : ''; ?>><?php echo TrustindexTestimonialsPlugin::___('Rotate interval'); ?></option>
</select>
</form>
</div>
</div>
<div class="clear"></div>
*/ ?>
<div class="ti-footer">
<a href="?page=<?php echo esc_attr($page); ?>&step=5&id=<?php echo esc_attr($id); ?>&command=setup-widget&_wpnonce=<?php echo esc_attr(wp_create_nonce('ti-save-widget')); ?>&setup_widget&selected&style_id=<?php echo esc_attr($wpttstStyleId); ?>&scss_set=<?php echo esc_attr($wpttstScssSet); ?>" class="btn-text btn-refresh ti-pull-right" data-loading-text="<?php echo esc_html__("Loading", 'testimonial-widgets'); ?>"><?php echo esc_html__("Save and get code", 'testimonial-widgets'); ?></a>
<div class="clear"></div>
</div>
</div>
<?php endif; ?>
<?php else : ?>
<h1 class="ti-free-title">
5. <?php echo esc_html__('Get shortcode', 'testimonial-widgets'); ?>
<a href="?page=<?php echo esc_attr($page); ?>&step=4&id=<?php echo esc_attr($id); ?>&selected&style_id=<?php echo esc_attr($wpttstStyleId); ?>&scss_set=<?php echo esc_attr($wpttstScssSet); ?>" class="ti-back-icon"><?php echo esc_html__('Back', 'testimonial-widgets'); ?></a>
</h1>
<?php if (!count($wpttstReviews)) : ?>
<div class="notice notice-warning" style="margin: 0 0 15px 0">
<p>
<?php echo esc_html__('There are no testimonials', 'testimonial-widgets'); ?>
</p>
</div>
<?php else : ?>
<div class="ti-box">
<div class="ti-header"><?php echo esc_html__('Insert this shortcode into your website', 'testimonial-widgets'); ?></div>
<div class="ti-input-row" style="margin-bottom: 2px">
<label><?php echo esc_html__('Shortcode', 'testimonial-widgets'); ?></label>
<code class="code-shortcode">[wp-testimonials widget-id=<?php echo esc_attr($id); ?>]</code>
<a href=".code-shortcode" class="btn-text btn-copy2clipboard"><?php echo esc_html__("Copy to clipboard", 'testimonial-widgets'); ?></a>
</div>
<?php echo esc_html__('Copy and paste this shortcode into post, page or widget.', 'testimonial-widgets'); ?>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
</div>
</div>
</div>
<div id="ti-loading">
<div class="ti-loading-effect">
<div></div>
<div></div>
<div></div>
</div>
</div>