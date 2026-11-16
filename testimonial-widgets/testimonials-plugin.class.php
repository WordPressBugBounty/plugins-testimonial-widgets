<?php
class TrustindexTestimonialsPlugin
{
private $pluginFilePath;
private $pluginName;
private $platformName;
public $shortname;
private $version;
public function __construct($shortname, $pluginFilePath, $version, $pluginName)
{
$this->shortname = $shortname;
$this->pluginFilePath = $pluginFilePath;
$this->version = $version;
$this->pluginName = $pluginName;
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once plugin_dir_path(__FILE__) . 'functions.php';
require_once plugin_dir_path(__FILE__) . 'functions-rating.php';
require_once plugin_dir_path(__FILE__) . 'post-types.php';
require_once plugin_dir_path(__FILE__) . 'post-editor.class.php';
}
public function getShortName()
{
return $this->shortname;
}
public function getPluginDir()
{
return plugin_dir_path($this->pluginFilePath);
}
public function getPluginFileUrl($file, $addVersioning = true)
{
$url = plugins_url($file, $this->pluginFilePath);
if ($addVersioning) {
$appendMark = strpos($url, "?") === FALSE ? "?" : "&";
$url .= $appendMark . 'ver=' . $this->version;
}
return $url;
}
public function getPluginSlug()
{
return basename($this->getPluginDir());
}
public function getFullStar()
{
return $this->getPluginFileUrl('static/svg/f.svg');
}
public function getEmptyStar()
{
return $this->getPluginFileUrl('static/svg/e.svg');
}
public static $thumbnailSizes = array(
60,
80,
100,
120,
140,
200,
360,
390,
460,
554
);
public function setTestimonialImage()
{
add_theme_support( 'post-thumbnails');
foreach (TrustindexTestimonialsPlugin::$thumbnailSizes as $thumbnailSize)
{
add_image_size('wpt-testimonial-thumbnail-' . $thumbnailSize, $thumbnailSize, $thumbnailSize, true);
}
}

public function getMenuData()
{
$menuData = array(
"menu" => array(
__('Setup Guide', 'testimonial-widgets') => array(
"url" => $this->getPluginSlug() . "/tabs/setup-guide.php",
"name" => "setup-guide",
"title" => "WP Testimonials - Setup Guide",
"is_custom" => true,
"hide" => false,
),
__('My Testimonials', 'testimonial-widgets') => array(
"url" => "edit.php?post_type=wpt-testimonial",
"title" => "WP Testimonials - My Testimonials",
"name" => "my-reviews",
"is_custom" => false,
"hide" => false,
),
__('Create Widgets', 'testimonial-widgets') => array(
"url" => $this->getPluginSlug() . "/tabs/index-widget.php",
"title" => "WP Testimonials - Create Widgets",
"name" => "index-widget",
"is_custom" => true,
"hide" => false,
),
__('hidden Create Widget', 'testimonial-widgets') => array(
"url" => $this->getPluginSlug() . "/tabs/create-widget.php",
"title" => "WP Testimonials - Create Widgets",
"name" => "create-widget",
"is_custom" => true,
"hide" => true,
"parent" => "index-widget",
),
__('Categories', 'testimonial-widgets') => array(
"url" => "edit-tags.php?taxonomy=wpt-testimonial-category&post_type=wpt-testimonial",
"name" => "categories",
"title" => "WP Testimonials - Categories",
"is_custom" => false,
"hide" => false,
),
__('Rate Us', 'testimonial-widgets') => array(
"url" => $this->getPluginSlug() . "/tabs/rate-us.php",
"name" => "rate-us",
"title" => "WP Testimonials - Rate Us",
"is_custom" => true,
"hide" => false,
)
),
"home_page" => $this->getPluginSlug() . "/tabs/setup-guide.php",
"default_tab" => $this->getPluginSlug() . "/tabs/setup-guide.php",
);
return $menuData;
}
public function addSettingMenu()
{
add_menu_page(
$this->pluginName,
$this->pluginName,
'manage_options',
$this->getPluginSlug() . "/tabs/setup-guide.php",
'',
$this->getPluginFileUrl('static/img/trustindex-sign-logo.png')
);
$permission = 'manage_options';
$menu = $this->getMenuData();
foreach ($menu["menu"] as $k => $v) {
add_submenu_page(
$v["hide"] ? "" : $menu["home_page"],
$v["title"],
$k,
$permission,
$v["url"]
);
}
}
public function menuHighlight($parentFile)
{
global $plugin_page, $submenu_file, $post_type, $taxonomy, $pagenow;
if ('wpt-testimonial' == $post_type) {
if ($taxonomy == 'wpt-testimonial-category') {
$plugin_page = 'edit-tags.php?taxonomy=wpt-testimonial-category&post_type=wpt-testimonial';
$submenu_file = 'edit-tags.php?taxonomy=wpt-testimonial-category&post_type=wpt-testimonial';
}
else if ($pagenow === 'post.php' || $pagenow === 'post-new.php')
{
$plugin_page = 'edit.php?post_type=wpt-testimonial';
$submenu_file = 'edit.php?post_type=wpt-testimonial';
}
else if (isset($plugin_page))
{
$page = sanitize_text_field($plugin_page);
$selectedTab = substr($page, strrpos($page, '/') + 1);
$selectedTab = str_replace(".php", "", $selectedTab);
if ($selectedTab === 'create-widget')
{
$plugin_page = $this->getPluginSlug() . "/tabs/index-widget.php";
$submenu_file = $this->getPluginSlug() . "/tabs/index-widget.php";
}
}
}
return $parentFile;
}
public function generateCptPageMenu()
{
$screen = get_current_screen();
if ('edit-wpt-testimonial' === $screen->id || 'wpt-testimonial' === $screen->id)
{
add_action( 'all_admin_notices', function(){
$menu = $this->getMenuData();
$selectedTab = 'my-reviews';
ob_start(); ?>
<div id="testimonial-widgets-plugin-settings-page" class="ti-toggle-opacity">
<h1 class="ti-free-title">
<?php echo esc_attr($this->pluginName); ?>
<a href="https://www.trustindex.io" target="_blank" title="Trustindex" class="ti-pull-right"><img src="<?php echo esc_url($this->getPluginFileUrl('static/img/trustindex.svg')); ?>" /></a>
</h1>
<div class="container_wrapper">
<div class="container_cell" id="container-main">
<div class="nav-tab-wrapper">
<?php foreach ($menu["menu"] as $tabName => $tab) : ?>
<?php $isActive = $selectedTab == $tab["name"];
$action = $tab["name"];
$isRight = $tab["name"] == "rate-us";
?>
<?php if ($tab["hide"] == false) : ?>
<a id="link-tab-<?php echo esc_attr($action); ?>" class="nav-tab<?php if ($isActive) : ?> nav-tab-active<?php endif; ?> <?php if ($isRight) : ?> nav-tab-right<?php endif; ?>" href="<?php echo esc_url($tab["is_custom"] ? admin_url('admin.php?page=' . $tab["url"]) : $tab["url"]); ?>"><?php echo esc_html($tabName); ?></a>
<?php endif; ?>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
<?php
$html = ob_get_contents();
ob_end_clean();
$allowedHtml = array(
'div' => array(
'id' => array(),
'class' => array()
),
'h1' => array(
'class' => array()
),
'a' => array(
'id' => array(),
'class' => array(),
'href' => array(),
),
'img' => array(
'src' => array()
)
);
echo wp_kses($html, $allowedHtml);
});
}
else if ('edit-wpt-testimonial-category' === $screen->id)
{
add_action( 'all_admin_notices', function(){
$menu = $this->getMenuData();
$selectedTab = 'categories';
ob_start(); ?>
<div id="testimonial-widgets-plugin-settings-page" class="ti-toggle-opacity">
<h1 class="ti-free-title">
<?php echo esc_attr($this->pluginName); ?>
<a href="https://www.trustindex.io" target="_blank" title="Trustindex" class="ti-pull-right"><img src="<?php echo esc_url($this->getPluginFileUrl('static/img/trustindex.svg')); ?>" /></a>
</h1>
<div class="container_wrapper">
<div class="container_cell" id="container-main">
<div class="nav-tab-wrapper">
<?php foreach ($menu["menu"] as $tabName => $tab) : ?>
<?php $isActive = $selectedTab == $tab["name"];
$action = $tab["name"];
$isRight = $tab["name"] == "rate-us";
?>
<?php if ($tab["hide"] == false) : ?>
<a id="link-tab-<?php echo esc_attr($action); ?>" class="nav-tab<?php if ($isActive) : ?> nav-tab-active<?php endif; ?> <?php if ($isRight) : ?> nav-tab-right<?php endif; ?>" href="<?php echo esc_url($tab["is_custom"] ? admin_url('admin.php?page=' . $tab["url"]) : $tab["url"]); ?>"><?php echo esc_html($tabName); ?></a>
<?php endif; ?>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
<?php
$html = ob_get_contents();
ob_end_clean();
$allowedHtml = array(
'div' => array(
'id' => array(),
'class' => array()
),
'h1' => array(
'class' => array()
),
'a' => array(
'id' => array(),
'class' => array(),
'href' => array(),
),
'img' => array(
'src' => array()
)
);
echo wp_kses($html, $allowedHtml);
});
}
}
public function generatePageMenu()
{
$menu = $this->getMenuData();
$defaultTab = $menu['default_tab'];
$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
$selectedTab = substr($page, strrpos($page, '/') + 1);
$selectedTab = str_replace(".php", "", $selectedTab);
$found = false;
foreach ($menu["menu"] as $tab) {
if ($tab["hide"] && $selectedTab == $tab["name"]) {
$selectedTab = $tab["parent"];
$found = true;
break;
}
if ($selectedTab == $tab["name"]) {
$found = true;
break;
}
}
if (!$found) {
$selectedTab = $defaultTab;
}
ob_start(); ?>
<div class="nav-tab-wrapper">
<?php foreach ($menu["menu"] as $tabName => $tab) : ?>
<?php
$isActive = $selectedTab == $tab["name"];
$isRight = $tab["name"] == "rate-us";
$action = $tab["name"];
?>
<?php if ($tab["hide"] == false) : ?>
<a id="link-tab-<?php echo esc_attr($action); ?>" class="nav-tab<?php if ($isActive) : ?> nav-tab-active<?php endif; ?> <?php if ($isRight) : ?> nav-tab-right<?php endif; ?>" href="<?php echo esc_url($tab["is_custom"] ? admin_url('admin.php?page=' . $tab["url"]) : $tab["url"]); ?>"><?php echo esc_html($tabName); ?></a>
<?php endif; ?>
<?php endforeach; ?>
</div>
<?php
$html = ob_get_contents();
ob_end_clean();
$allowedHtml = array(
'div' => array(
'id' => array(),
'class' => array()
),
'h1' => array(
'class' => array()
),
'a' => array(
'id' => array(),
'class' => array(),
'href' => array(),
),
'img' => array(
'src' => array()
)
);
echo wp_kses($html, $allowedHtml);
}

public function addPluginActionLinks($links, $file)
{
$pluginFile = basename($this->pluginFilePath);
if (basename($file) == $pluginFile) {
$newItem2 = '<a target="_blank" href="https://www.trustindex.io" target="_blank">by <span style="background-color: #4067af; color: white; font-weight: bold; padding: 1px 8px;">Trustindex.io</span></a>';
$newItem1 = '<a href="' . admin_url('admin.php?page=' . $this->getPluginSlug() . '/tabs/setup-guide.php') . '">' . __('Settings', 'testimonial-widgets') . '</a>';
array_unshift($links, $newItem2, $newItem1);
}
return $links;
}
public function addPluginMetaLinks($meta, $file)
{
$pluginFile = basename($this->pluginFilePath);
if (basename($file) == $pluginFile) {
$meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/" . $this->getPluginSlug() . "' target='_blank' rel='noopener noreferrer' title='" . __('Rate our plugin', 'testimonial-widgets') . ': ' . $this->pluginName . "'>" . __('Rate our plugin', 'testimonial-widgets') . '</a>';
}
return $meta;
}

/* I18N
* make sure you do not use any translatable string function calls before the call to our ‘load_plugin_textdomain’
*/
public function loadI18N()
{
load_plugin_textdomain('testimonial-widgets', false, $this->getPluginSlug() . '/languages');
}

public function trustindexTestimonialsAddScripts($hook)
{
$wpPage = false;
$postType = isset($_GET['post_type']) ? sanitize_text_field(wp_unslash($_GET['post_type'])) : null;
$taxonomy = isset($_GET['taxonomy']) ? sanitize_text_field(wp_unslash($_GET['taxonomy'])) : null;
$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : null;
global $pagenow;
if ($pagenow === 'post.php' || $pagenow === 'post-new.php' || $pagenow === 'edit.php' || $pagenow === 'edit-tags.php' || $pagenow === 'term.php')
{
if (file_exists($this->getPluginDir() . 'static/js/admin-testimonial-edit.js')) {
wp_enqueue_script('trustindex_testimonials_edit_script' . $this->shortname, $this->getPluginFileUrl('static/js/admin-testimonial-edit.js'), [], $this->version, false);
}
}
if ($postType && $postType == "wpt-testimonial") {
if ($pagenow === 'post-new.php' || $pagenow === 'post.php')
{
if (file_exists($this->getPluginDir() . 'static/css/admin-page-menu-for-post.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname, $this->getPluginFileUrl('static/css/admin-page-menu-for-post.css'), [], $this->version);
}
}
else if ($pagenow === 'edit.php')
{
$wpPage = true;
if (file_exists($this->getPluginDir() . 'static/js/admin-testimonial-index.js')) {
wp_enqueue_script('trustindex_testimonials_index_script' . $this->shortname, $this->getPluginFileUrl('static/js/admin-testimonial-index.js'), [], $this->version, false);
}
if (file_exists($this->getPluginDir() . 'static/css/admin-page-default-wp-settings.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname . '_default_wp', $this->getPluginFileUrl('static/css/admin-page-default-wp-settings.css'), [], $this->version);
}
}
else if ($taxonomy && (($pagenow === 'edit-tags.php' && $taxonomy === "wpt-testimonial-category") || ($pagenow === 'term.php' && $taxonomy === "wpt-testimonial-category")))
{
$wpPage = true;
if (file_exists($this->getPluginDir() . 'static/css/admin-page-default-wp-settings.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname . '_default_wp', $this->getPluginFileUrl('static/css/admin-page-default-wp-settings.css'), [], $this->version);
}
}
}
else if ($pagenow === 'post.php') {
if (file_exists($this->getPluginDir() . 'static/css/admin-page-menu-for-post.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname, $this->getPluginFileUrl('static/css/admin-page-menu-for-post.css'), [], $this->version);
}
}
if ($wpPage == false)
{
$tmp = explode('/', $this->pluginFilePath);
$pluginSlug = preg_replace('/\.php$/', '', array_pop($tmp));
$tmp = explode('/', $hook);
$currentSlug = array_shift($tmp);
}
if ($wpPage || $pluginSlug == $currentSlug) {
if (file_exists($this->getPluginDir() . 'static/css/admin-page-settings.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname, $this->getPluginFileUrl('static/css/admin-page-settings.css'), [], $this->version);
}
if (file_exists($this->getPluginDir() . 'static/js/admin-page-settings-common.js')) {
wp_enqueue_script('trustindex_testimonials_settings_script_common_' . $this->shortname, $this->getPluginFileUrl('static/js/admin-page-settings-common.js'), [], $this->version, false);
}
}
if ($page)
{
$selectedTab = substr($page, strrpos($page, '/') + 1);
$selectedTab = str_replace(".php", "", $selectedTab);
if ($selectedTab === 'create-widget')
{
if (file_exists($this->getPluginDir() . 'static/js/admin-page-create-widget.js')) {
wp_enqueue_script('trustindex_testimonials_create_widget_script_' . $this->shortname, $this->getPluginFileUrl('static/js/admin-page-create-widget.js'), [], $this->version, false);
}
}
if ($selectedTab === 'setup-guide')
{
if (file_exists($this->getPluginDir() . 'static/css/setup-guide.css')) {
wp_enqueue_style('trustindex_testimonials_setup-guide_style_' . $this->shortname, $this->getPluginFileUrl('static/css/setup-guide.css'), [], $this->version);
}
}
}

wp_register_style('wpttst-post-editor', $this->getPluginFileUrl('static/css/post-editor.css'), [], $this->version);
wp_enqueue_style('wpttst-post-editor');

wp_register_style('wpttst-rating-display', $this->getPluginFileUrl('static/css/rating-display.css'), [], $this->version);
wp_register_style('wpttst-rating-form', $this->getPluginFileUrl('static/css/rating-form.css'), [], $this->version);
wp_register_script('wpttst-rating-script', $this->getPluginFileUrl('static/js/rating-edit.js'), array('jquery'), $this->version, true);
wp_enqueue_style('wpttst-rating-form');
wp_enqueue_style('wpttst-rating-display');
wp_enqueue_script('wpttst-rating-script');
}

private $previewContent = null;
private $templateCache = null;
public function getNoregListReviews(int $widgetId, $listAll = false, $defaultStyleId = 4, $defaultSetId = 'light-background', $onlyPreview = false)
{
$w = $this->getWidget($widgetId);
$content = $w->review_content;
$widget = unserialize($w->value);
$filter = array();
$filter['mode'] = $widget['1']['mode'];
$filter['rating'] = $widget['1']['rating'];
if ($filter['mode'] === 'category')
{
$filter['category'] = $widget['1']['selected'];
}
else if ($filter['mode'] === 'manual_select')
{
$filter['ids'] = explode(',', $widget['1']['selected']);
$filter['rating'] = 'all';
}
$filter['order'] = $widget['4']['general']['order'];
$reviews = $this->getReviews($filter);
if (count($reviews) == 0)
{
$html = $this->getAlertbox('warning', '<br />' . __('You do not have reviews with the current filters. <br /> Change your filters if you would like to display reviews on your page!', 'testimonial-widgets'));
$allowedHtml = array(
'div' => array(
'style' => array()
),
'span' => array(
'class' => array()
),
'br' => array()
);
echo wp_kses($html, $allowedHtml);
}
$language = self::getWidgetLanguage(get_locale());
$settings = array(
'avatar_url' => 'https://cdn.trustindex.io/' . 'companies/default_avatar.jpg',
'rating_number' => 3,
'rating_score' => wp_rand(4, 5),
'style_id' => isset($widget['2']) ? $widget['2'] : $defaultStyleId,
'set_id' => isset($widget['3']) ? $widget['3'] : $defaultSetId,
'display_type' => $widget['4']['appearance']['display_type'],
'date_format' => $widget['4']['appearance']['date_format'],
'show_logos' => 0,
'show_stars' => $widget['4']['appearance']['hide_stars'],
'show-review-images' => !$widget['4']['appearance']['hide_image'],
'auto_height' => $widget['4']['appearance']['auto_height'],
'slider_interval' => isset($widget['4']['navigation']['slider_interval']) ? $widget['4']['navigation']['slider_interval'] : 6,
'no_rating_text' => 1,
'language' => $language,
);
$needToParse = true;
if ($onlyPreview) {
$content = false;
$settings['style_id'] = $defaultStyleId;
$settings['set_id'] = $defaultSetId;
$settings['show_logos'] = 0;
$settings['show_stars'] = 0;
$settings['show-review-images'] = 1;
$settings['auto_height'] = 0;
$settings['slider_interval'] = 6;
}
if (is_null($settings['no_rating_text']))
{
$settings['no_rating_text'] = in_array($settings['style_id'], [ 15, 19, 36, 38, 39, 44 ]) ? 1 : 0;
if(self::$widgetStyles[$settings['set_id']]['_vars']['dots'] === 'true')
{
$settings['no_rating_text'] = 1;
}
}
$scriptName = 'trustindex-js';
if (!wp_script_is($scriptName, 'enqueued')) {
wp_enqueue_script($scriptName, 'https://cdn.trustindex.io/' . 'loader.js', [], $this->version, true);
}
$layoutId = '';
if (!empty($w->review_content) && is_string($w->review_content)) {
$start = strpos($w->review_content, 'data-layout-id="');
$length = $start !== false ? strpos($w->review_content, '" data-set-id', $start) - $start : 0;
$layoutId = $length > 0 ? substr($w->review_content, $start, $length) : '';
}
if ($content === false || empty($content) || $layoutId != $settings['style_id'] || (strpos($content, '<!-- R-LIST -->') === false && $needToParse)) {
if (!$this->templateCache) {
$response = wp_remote_get("https://cdn.trustindex.io/" . "widget-assets/template/v2/{$settings['language']}.json");
if (is_wp_error($response)) {
$html = $this->getAlertbox('error', '<br />' . __('Could not download the template for the widget.<br />Please reload the page.<br />If the problem persists, please write an e-mail to support@trustindex.io.', 'testimonial-widgets'));
$allowedHtml = array(
'div' => array(
'style' => array()
),
'span' => array(
'class' => array()
),
'br' => array()
);
echo wp_kses($html, $allowedHtml);
die;
}
$response['body'] = preg_replace('/<!-- VERIFIED BY TRUSTINDEX START.*?VERIFIED BY TRUSTINDEX END -->/s', '', $response['body']);
$response['body'] = preg_replace('/<img\s+class=\\\"ti-platform-icon\\\"[^\"]*src=\\\"[^\"]*\\\"[^\"]*alt=\\\"[^\"]*\\\"[^\"]*width=\\\"20\\\"[^\"]*height=\\\"20\\\"[^\"]*loading=\\\"lazy\\\"[^\"]*\/>/', '', $response['body']);
$data = json_decode($response['body'], true);
foreach ($data as &$html) {
$html = preg_replace(
'/<div\s*class="ti-platform-icon\s+ti-with-tooltip"[^>]*>.*?<img[^>]*width="20"[^>]*height="20"[^>]*loading="lazy"[^>]*>\s*<\/div>/su',
'',
$html
);
}
$this->templateCache = $data;
}
$content = $this->templateCache[$settings['style_id']];
$this->updateWidgetReviewContent($widgetId, $content);
}
if ($settings['slider_interval']) {
$content = preg_replace('/data-pager-autoplay-timeout=[\'"][^\'"]*[\'"]/m', 'data-pager-autoplay-timeout="' . $settings['slider_interval'] . '"', $content);
}
$content = preg_replace('/data-set[_-]id=[\'"][^\'"]*[\'"]/m', 'data-set-id="' . $settings['set_id'] . '"', $content);
$content = preg_replace('/class="\s*ti-widget\s*"/', 'class="ti-widget"', $content);
if ($needToParse) {
$content = $this->parseNoregListReviews([
'content' => $content,
'reviews' => $reviews,
'settings' => $settings,
]);
$this->previewContent = [
'id' => $settings['style_id'],
'content' => $content
];
}
$classAppends = ['ti-wp-testimonial-' . $w->id, 'ti-no-logo'];
if ($settings['show_logos']) {
$classAppends []= 'ti-no-logo';
}
if ($settings['show_stars']) {
$classAppends []= 'ti-no-stars';
}
if (!$settings['show-review-images']) {
$classAppends []= 'ti-no-review-images';
}
if ($settings['auto_height']) {
$classAppends []= 'ti-auto-height';
}
if ($w->css && !$onlyPreview)
{
wp_register_style("testimonial-widgets-css-live-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], false, [], $this->version);
wp_enqueue_style( "testimonial-widgets-css-live-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'] );
wp_add_inline_style("testimonial-widgets-css-live-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], $w->css );
}
else
{
wp_enqueue_style("testimonial-widgets-css-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], "https://cdn.trustindex.io/" . "assets/widget-presetted-css/v2/" . $settings['style_id'] . "-" . $settings['set_id'] . ".css", [], $this->version);
if (in_array($settings['style_id'], array(36,37,38,39)))
{
$content = str_replace('class="ti-widget"', 'id="override-preset-'. $settings['style_id'] .'" class="ti-widget"', $content);
$inlineStyle = "#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img img {
width: 120px !important;
height: 120px !important;
border-radius: 60px !important;
}";
if ($settings['style_id'] == 36 || $settings['style_id'] == 37 || $settings['style_id'] == 38)
{
$inlineStyle = $inlineStyle . "#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img {
margin: 0 !important;
margin-top: -75px !important;
margin-bottom: 15px !important;
align-self: center;
}";
if ($settings['style_id'] == 38)
{
$inlineStyle = $inlineStyle . "#override-preset-". $settings['style_id'] ." .ti-review-item {
padding-top: 75px;
}";
}
else
{
$inlineStyle = $inlineStyle . "#override-preset-". $settings['style_id'] ." .ti-reviews-container-wrapper {
padding-top: 75px;
}";
}
}
else if ($settings['style_id'] == 39)
{
$inlineStyle = $inlineStyle . "#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img {
margin: 20px 0 0 !important;
margin-bottom: 10px !important;
align-self: center;
}
#override-preset .ti-reviews-container-wrapper {
padding-top: 8px;
}";
}
wp_add_inline_style("testimonial-widgets-css-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], $inlineStyle);
}
else if ($settings['style_id'] == 44)
{
$content = str_replace('class="ti-widget"', 'id="override-preset-'. $settings['style_id'] .'" class="ti-widget"', $content);
$inlineStyle = "#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img img {
width: 100px !important;
height: 100px !important;
border-radius: 50px !important;
}
#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img {
margin-left: -65px !important;
}
#override-preset-". $settings['style_id'] ." .ti-review-item>.ti-inner {
padding-right: 40px !important;
padding-bottom: 40px !important;
}
#override-preset-". $settings['style_id'] ." .ti-review-item {
padding-left: 58px;
}
";
wp_add_inline_style("testimonial-widgets-css-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], $inlineStyle);
}
}
if ($classAppends) {
$content = str_replace('class="ti-widget" data-layout-id=', 'class="ti-widget ' . implode(' ', $classAppends) . '" data-layout-id=', $content);
}
return $content;
}
public function parseNoregListReviews($array = [])
{
preg_match('/<!-- R-LIST -->(.*)<!-- R-LIST -->/', $array['content'], $matches);
if (isset($matches[1])) {
$reviewContent = "";
if ($array['reviews'] && count($array['reviews'])) {
foreach ($array['reviews'] as $r) {
$date = "&nbsp;";
if ($r['date'] && $r['date'] != '0000-00-00') {
$date = str_replace(self::$widgetMonthNames['en'], self::$widgetMonthNames[$array['settings']['language']], gmdate($array['settings']['date_format'], strtotime($r['date'])));
}
if ($r['company_website'])
{
$input = trim($r['company_website'], '/');
if (!preg_match('#^http(s)?://#', $input)) {
$input = 'http://' . $input;
}
$urlParts = wp_parse_url($input);
if (isset($urlParts['host'])) {
$scheme = isset($urlParts['scheme']) ? $urlParts['scheme'] . '://' : 'http://';
$host = preg_replace('/^www\./', '', $urlParts['host']);
$path = isset($urlParts['path']) ? $urlParts['path'] : '';
$fullUrl = $scheme . $host . $path;
if ($r['company_name']) {
$info = '<a href="' . esc_url($fullUrl) . '" target="_blank">' . esc_html($r['company_name']) . '</a>';
} else {
$info = '<a href="' . esc_url($fullUrl) . '" target="_blank">' . esc_html($fullUrl) . '</a>';
}
} else {
$info = isset($r['company_name']) ? esc_html($r['company_name']) : $date;
}
} else {
$info = isset($r['company_name']) ? esc_html($r['company_name']) : $date;
}
$ratingContent = $this->getRatingStars($r['star_rating']);
$platformName = ucfirst($this->getShortName());
if (!$array['settings']['show-review-images']) {
if (in_array($array['settings']['style_id'], [45, 46, 47, 48]))
{
$matches[1] = str_replace('<div class="ti-profile-img-square"> <img src="%reviewer_photo%" alt="%reviewer_name%" /> </div>', '', $matches[1]);
}
else
{
$matches[1] = str_replace('<div class="ti-profile-img"> <img src="%reviewer_photo%" alt="%reviewer_name%" /> </div>', '', $matches[1]);
}
}
if ($r['photo'][TrustindexTestimonialsPlugin::$widgetTemplates['templates'][$array['settings']['style_id']]['image']])
{
$reviewerPhoto = $r['photo'][TrustindexTestimonialsPlugin::$widgetTemplates['templates'][$array['settings']['style_id']]['image']];
}
else
{
$reviewerPhoto = $this->getPluginFileUrl('static/img/noprofile.svg');
}
$reviewContent .= strtr($matches[1], [
'%platform%' => $platformName,
'%reviewer_photo%' => esc_url($reviewerPhoto),
'%reviewer_name%' => esc_attr($r['client_name']),
'%created_at%' => $info,
'%text%' => preg_replace('/\r\n|\r|\n/', "\n", wp_kses_post((string) $r['content'])),
'<!-- STARS-CONTENT -->' => $ratingContent,
]);
$reviewContent = str_replace('<div></div>', '', $reviewContent);
}
$array['content'] = str_replace($matches[0], $reviewContent, $array['content']);
}
}
if ($array['settings']['no_rating_text']) {
if (in_array($array['settings']['style_id'], [6, 7])) {
$array['content'] = preg_replace('/<div class="ti-footer">.*<\/div>/mU', '<div class="ti-footer"></div>', $array['content']);
} else if (in_array($array['settings']['style_id'], [31, 33])) {
$array['content'] = preg_replace('/<div class="ti-header source-.*<\/div>\s?<div class="ti-reviews-container">/mU', '<div class="ti-reviews-container">', $array['content']);
} else if (in_array($array['settings']['style_id'], [10])) {
$array['content'] = preg_replace('/<div class="ti-header source-.*<\/div>\s?<div class="ti-reviews-container-wrapper">/mU', '<div class="ti-reviews-container">', $array['content']);
}
else if ($array['settings']['style_id'] == 11) {
$array['content'] = preg_replace('/<div class="ti-text">.*<\/div>/mU', '', $array['content']);
} else {
$array['content'] = preg_replace('/<div class="ti-rating-text">.*<\/div>/mU', '', $array['content']);
}
}
if ($array['settings']['auto_height'] && $array['settings']['style_id'] == 33) {
$array['content'] = preg_replace('/<a href="" class="ti-read-more".*<\/a>/mU', '', $array['content']);
}
preg_match('/src="([^"]+logo[^\.]*\.svg)"/m', $array['content'], $matches);
if (isset($matches[1]) && !empty($matches[1])) {
$array['content'] = str_replace($matches[0], $matches[0] . ' width="150" height="25"', $array['content']);
}
return $array['content'];
}
public function getShortcodeName()
{
return 'wp-testimonials';
}
public function initShortcode()
{
if (!shortcode_exists($this->getShortcodeName())) {
add_shortcode($this->getShortcodeName(), [$this, 'shortcodeFunc']);
}
}
public function shortcodeFunc($atts)
{
$atts = shortcode_atts(
array(
'widget-id' => null
),
$atts
);
if (isset($atts["widget-id"]) && $atts["widget-id"]) {
$widget = $this->getWidget($atts["widget-id"]);
if ($widget)
{
$val = unserialize($widget->value);
return $this->getNoregListReviews($widget->id, true, $val['2'], $val['3']);
}
else
{
return self::getAlertbox(
"error",
/* translators: %s: plugin name */
" @ <strong>" . sprintf(__('%s plugin', 'testimonial-widgets'), esc_attr($this->pluginName)) . "</strong><br /><br />"
/* translators: %s: widget ID given in the shortcode */
. sprintf(__('Widget ID %s does not exists!', 'testimonial-widgets'), esc_html($atts["widget-id"])),
false
);
}
}
else
{
return self::getAlertbox(
"error",
/* translators: %s: plugin name */
" @ <strong>" . sprintf(__('%s plugin', 'testimonial-widgets'), esc_attr($this->pluginName)) . "</strong><br /><br />"
/* translators: %s: plugin name */
. sprintf(__('Your shortcode is deficient: %s ID is empty! Example: ', 'testimonial-widgets'), esc_attr($this->pluginName)) . '<br /><code>[' . $this->getShortcodeName() . ' widget-id="12"]</code>',
false
);
}
}
public static function getAlertbox($type, $content, $newlineContent = true)
{
$types = array(
"warning" => array(
"css" => "color: #856404; background-color: #fff3cd; border-color: #ffeeba;",
"icon" => "dashicons-warning",
"label" => __('warning', 'testimonial-widgets')
),
"info" => array(
"css" => "color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb;",
"icon" => "dashicons-info",
"label" => __('info', 'testimonial-widgets')
),
"error" => array(
"css" => "color: #721c24; background-color: #f8d7da; border-color: #f5c6cb;",
"icon" => "dashicons-info",
"label" => __('error', 'testimonial-widgets')
)
);
return "<div style='margin:20px 0px; padding:10px; " . $types[$type]['css'] . " border-radius: 5px;'>"
. "<span class='dashicons " . $types[$type]['icon'] . "'></span> <strong>" . strtoupper($types[$type]['label']) . "</strong>"
. ($newlineContent ? "<br />" : "")
. $content
. "</div>";
}
public function getRatingStars($ratingScore)
{
$text = "";
if (!is_numeric($ratingScore)) {
return $text;
}
$fullStarUrl = '<img class="ti-star" src="' . esc_url($this->getPluginFileUrl('static/img/star/f.svg', false)) . '" alt="Google" width="17" height="17" loading="lazy" />';
for ($si = 1; $si <= $ratingScore; $si++) {
$text .= $fullStarUrl;
}
$fractional = $ratingScore - floor($ratingScore);
if (0.25 <= $fractional) {
if ($fractional < 0.75) {
$text .= preg_replace('/f(\.svg)?"/', 'h$1"', $fullStarUrl);
} else {
$text .= $fullStarUrl;
}
$si++;
}
for (; $si <= 5; $si++) {
$text .= preg_replace('/f(\.svg)?"/', 'e$1"', $fullStarUrl);
}
return $text;
}
public function saveCss($id, $widget, $return = false)
{
$styleId = $widget['2'];
$setId = $widget['3'];
add_filter('https_ssl_verify', '__return_false');
add_filter('block_local_requests', '__return_false');
$params = [
'platform' => 'google',
'layout_id' => $styleId,
'set_id' => $setId
];
foreach ($widget['4']['appearance'] as $name => $value)
{
if ($name == 'text-align')
{
if(in_array($styleId, [ 36, 37, 38, 39, 45, 48 ]))
{
$name = 'content-align';
}
}
if (isset($value) && $value != "")
{
$pxArray = array('profile-font-size','review-font-size','box-border-top-width','box-border-bottom-width','box-border-left-width','box-border-right-width','box-padding','box-border-radius');
if (in_array($name, $pxArray))
{
$value = $value . 'px';
}
$params['overrides'][$name] = $value;
}
}
if (TrustindexTestimonialsPlugin::$widgetTemplates['templates'][$styleId]['generate'])
{
$params['overrides']['ti-profile-img-size'] = TrustindexTestimonialsPlugin::$widgetTemplates['templates'][$styleId]['image'];
}
$url = 'https://admin.trustindex.io/' . 'api/getLayoutScss?' . http_build_query($params);
$serverOutput = $this->postRequest($url, [
'timeout' => '20',
'redirection' => '5',
'blocking' => true
]);
if ($serverOutput[0] !== '[' && $serverOutput[0] !== '{') {
$serverOutput = substr($serverOutput, strpos($serverOutput, '('));
$serverOutput = trim($serverOutput, '();');
}
$serverOutput = json_decode($serverOutput, true);
if ($serverOutput['css']) {
if ($styleId == 17 || $styleId == 21) {
$serverOutput['css'] .= '.ti-preview-box { position: initial !important }';
}
$serverOutput['css'] = str_replace('.source-Google', '.source-' . ucfirst($this->getShortName()), $serverOutput['css']);
if ($return)
{
$serverOutput['css'] = str_replace('.ti-widget.', '.ti-widget[data-layout-id="' . $styleId . '"][data-set-id="' . $setId . '"].', $serverOutput['css']);
$serverOutput['css'] = str_replace('.ti-goog', '', $serverOutput['css']);
return $serverOutput['css'];
}
else
{
$serverOutput['css'] = str_replace('.ti-goog', '.ti-wp-testimonial-' . $id, $serverOutput['css']);
$serverOutput['css'] .= $this->getAdditionalWidgetStyle($id, $styleId);
$this->updateWidgetCss($id, $serverOutput['css']);
}
}
}
private function getAdditionalWidgetStyle($id, $styleId)
{
$style = ".ti-widget.ti-wp-testimonial-" . $id . ".ti-no-review-images .ti-profile-img{display: none;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . ".ti-no-stars .ti-stars .ti-star{display: none;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . ".ti-no-stars .ti-review-item .ti-stars .ti-star.f{display: none;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . ".ti-no-stars .ti-review-item .ti-stars .ti-star.e{display: none;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . ".ti-no-stars .ti-review-item .ti-stars .ti-star.h{display: none;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . " .ti-widget-container .ti-review-item .ti-profile-details .ti-date a {text-decoration: none !important; font-size: 12px;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . " .ti-widget-container .ti-review-item .ti-profile-details .ti-date a:hover{text-decoration: underline !important; font-size: 12px;} ";
if ($styleId == 4 || $styleId == 16 || $styleId == 19 || $styleId == 36 || $styleId == 37 || $styleId == 38 || $styleId == 45 || $styleId == 46 || $styleId == 47 || $styleId == 48)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content {height: auto !important;} ";
}
else if ($styleId == 6)
{
$style .= ".ti-review-content .inner .ti-review-text, .ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content .ti-inner .ti-review-text {max-height: unset !important; -webkit-line-clamp: unset !important;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content .ti-inner {height: auto !important;} ";
}
else if ($styleId == 7)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content .ti-inner {height: auto !important;} ";
}
else if ($styleId == 15)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content .ti-review-text {height: auto !important;} ";
}
else if ($styleId == 18)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content .ti-inner {max-height: unset !important;} ";
}
else if ($styleId == 19)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content [class$=inner] .ti-review-text {max-height: unset !important;} ";
}
else if ($styleId == 31)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content .ti-inner {max-height: unset !important; -webkit-line-clamp: unset !important;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-item .ti-read-more {display: none !important;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . " .ti-widget-container {margin: unset; display: block;max-width: 100%;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . " .ti-reviews-container-wrapper {overflow: hidden !important;} ";
}
else if ($styleId == 33)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-content .ti-inner {max-height: unset !important; -webkit-line-clamp: unset !important;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $styleId . "'].ti-auto-height .ti-review-item .ti-read-more {display: none;} ";
}
return $style;
}
private function postRequest($url, $args)
{
$response = wp_remote_post($url, $args);
if (is_wp_error($response)) {
$html = $this->getAlertbox('error', '<br />Error with wp_remote_post, error message: <br /><b>' . $response->get_error_message() . '</b>');
$allowedHtml = array(
'div' => array(
'style' => array()
),
'span' => array(
'class' => array()
),
'br' => array()
);
echo wp_kses($html, $allowedHtml);
die;
}
return wp_remote_retrieve_body($response);
}

static function pluginActivation()
{
wpttstRegisterCpt();
update_option('wpttst_custom_forms', self::updateCustomForms());
if (get_option('wp-testimonials-visited-get-reviews') === false)
{
add_option( 'wp-testimonials-visited-get-reviews', 'no');
}
flush_rewrite_rules();
}
static function pluginDeactivation()
{
delete_option('wpttst_custom_forms');
delete_option('wp-testimonials-visited-get-reviews');
flush_rewrite_rules();
}
function pluginUninstall()
{
$this->removeWidgetsTable();
$this->removeTestimonials();
$this->removeTestimonialCategories();
}
public function pluginLoaded()
{
$this->loadI18N();
}

public static function editColumns( $columns ) {
$fields = wpttstGetAllFields();
unset( $columns['testimonial-widgets_thumbnail'], $columns['date'] );
$key = 'title';
$offset = array_search( $key, array_keys( $columns ) ) + 1;
foreach ( $fields as $key => $field ) {
if ( $field['admin_table'] ) {
if ( 'post_title' == $field['name'] ) {
continue;
} elseif ( 'featured_image' == $field['name'] ) {
$fieldsToAdd['testimonial-widgets_thumbnail'] = __('Thumbnail', 'testimonial-widgets');
} elseif ( 'rating' == $field['input_type'] ) {
$fieldsToAdd[ $field['name'] ] = __('Rating', 'testimonial-widgets');
} else {
$fieldsToAdd[ $field['name'] ] = apply_filters( 'wpttst_l10n', $field['label'], 'testimonial-widgets-form-fields', $field['name'] . ' : label' );
}
}
}
if ( count( get_terms( array( 'taxonomy' => 'wpt-testimonial-category', 'hide_empty' => false ) ) )) {
$fieldsToAdd['category'] = __('Categories', 'testimonial-widgets');
}
$fieldsToAdd['date'] = __('Date', 'testimonial-widgets');
$columns = array_merge(
array_slice( $columns, 0, $offset ),
$fieldsToAdd,
array_slice( $columns, $offset, null )
);
return $columns;
}
public static function customColumns( $column ) {
global $post;
switch ( $column ) {
case 'post_id' :
echo absint( esc_attr($post->ID) );
break;
case 'post_content' :
echo esc_html( mb_substr( $post->post_content, 0, 100 ) ) . '&hellip;';
break;
case 'testimonial-widgets_thumbnail' :
echo wp_kses_post( wpttstGetThumbnail( array( 60, 60 ) ) );
break;
case 'category' :
$categories = get_the_terms( 0, 'wpt-testimonial-category' );
if ( $categories && ! is_wp_error( $categories ) ) {
$list = array();
foreach ( $categories as $cat ) {
$list[] = $cat->name;
}
echo esc_attr(implode( ", ", $list ));
}
break;
default :
$custom = get_post_custom();
$fields = wpttstGetCustomFields();
if ( isset( $custom[ $column ] ) && $custom[ $column ][0] ) {
if ( isset( $fields[ $column ] ) ) {
switch ( $fields[ $column ]['input_type'] ) {
case 'rating' :
wpttstStarRatingDisplay( $custom[ $column ][0], 'in-table-list' );
break;
case 'checkbox' :
echo $custom[ $column ][0] ? 'yes' : 'no';
break;
default :
echo esc_attr($custom[ $column ][0]);
}
}
} else {
if ( isset( $fields[ $column ] ) ) {
if ( 'checkbox' == $fields[ $column ]['input_type'] ) {
echo 'no';
} else {
}
}
}
}
}
public static function updateCustomForms()
{
$customForms = get_option('wpttst_custom_forms');
if (!$customForms) {
return self::getCustomForms();
}
foreach ($customForms as $formId => $formProperties) {
foreach ($formProperties['fields'] as $key => $formField) {
/*
* Convert categories to category-selector.
* @since 2.17.0
*/
if ('categories' == $formField['input_type']) {
$customForms[$formId]['fields'][$key]['input_type'] = 'category-selector';
}
/*
* Unset `show_default_options` for rating field. Going from 0 to 1.
* @since 2.21.0
*/
if ('rating' == $formField['input_type']) {
unset($formField['show_default_options']);
}
/*
* Add `show_required_option` to shortcode field. Initial value is false.
* @since 2.22.0
*/
if ('shortcode' == $formField['input_type']) {
$formField['show_required_option'] = false;
}
/*
* Add `show_default_options` to checkbox field.
*
* @since 2.27.0
*/
if ('checkbox' == $formField['input_type']) {
$formField['show_default_options'] = 1;
}
/*
* Merge in new default.
* Custom fields are in display order (not associative) so we must find them by `input_type`.
* @since 2.21.0 Using default fields instead of default form as source
*/
$newDefault = array();
$fields = get_option('wpttst_fields', array());
foreach ($fields['field_types'] as $fieldTypeGroupKey => $fieldTypeGroup) {
foreach ($fieldTypeGroup as $fieldTypeKey => $fieldTypeField) {
if ($fieldTypeField['input_type'] == $formField['input_type']) {
$newDefault = $fieldTypeField;
break;
}
}
}
if ($newDefault) {
$customForms[$formId]['fields'][$key] = array_merge($newDefault, $formField);
}
}
}
return $customForms;
}
public static function getCustomForms()
{
$baseForms = self::getBaseForms();
$forms[1] = array(
'name' => 'custom',
'label' => __('Custom Form', 'testimonial-widgets'),
'readonly' => 0,
'fields' => $baseForms['default']['fields'],
);
return apply_filters('wpttst_update_custom_form', $forms);
}
public static function getBaseForms()
{
$defaultFields = self::getFields();
$forms = array(
'default' => array(
'name' => 'default',
'label' => __('Default Form', 'testimonial-widgets'),
'readonly' => 1,
'fields' => array(
0 => array(
'record_type' => 'custom',
'name' => 'client_name',
'label' => __('Full Name', 'testimonial-widgets'),
'input_type' => 'text',
'required' => 1,
'after' => __('What is your full name?', 'testimonial-widgets'),
'admin_table' => 1,
),
1 => array(
'record_type' => 'custom',
'name' => 'company_name',
'label' => __('Company Name', 'testimonial-widgets'),
'input_type' => 'text',
'after' => __('What is your company name?', 'testimonial-widgets'),
),
2 => array(
'record_type' => 'custom',
'name' => 'company_website',
'label' => __('Company Website', 'testimonial-widgets'),
'input_type' => 'text',
'after' => __('Does your company have a website?', 'testimonial-widgets'),
),
5 => array(
'record_type' => 'post',
'name' => 'post_title',
'label' => __('Heading', 'testimonial-widgets'),
'input_type' => 'text',
'required' => 0,
'after' => __('A headline for your testimonial.', 'testimonial-widgets'),
'max_length' => ''
),
6 => array(
'record_type' => 'post',
'name' => 'post_content',
'label' => __('Testimonial', 'testimonial-widgets'),
'input_type' => 'textarea',
'required' => 1,
'after' => __('What do you think about us?', 'testimonial-widgets'),
'max_length' => ''
),
7 => array(
'record_type' => 'post',
'name' => 'featured_image',
'label' => __('Photo', 'testimonial-widgets'),
'input_type' => 'file',
'after' => __('Would you like to include a photo?', 'testimonial-widgets'),
'admin_table' => 1,
),
8 => array(
'record_type' => 'optional',
'name' => 'star_rating',
'label' => __('Star rating', 'testimonial-widgets'),
'input_type' => 'rating',
'required' => 0,
'after' => __('Would you like to include star rating?', 'testimonial-widgets')
),
),
),
);
$forms['minimal'] = array(
'name' => 'minimal',
'label' => __('Minimal Form', 'testimonial-widgets'),
'readonly' => 1,
'fields' => array(
0 => array(
'record_type' => 'custom',
'name' => 'client_name',
'label' => __('Name', 'testimonial-widgets'),
'input_type' => 'text',
'required' => 1,
'after' => '',
'admin_table' => 1,
),
1 => array(
'record_type' => 'post',
'name' => 'post_content',
'label' => __('Testimonial', 'testimonial-widgets'),
'input_type' => 'textarea',
'required' => 1,
'after' => '',
),
),
);
foreach ($forms as $formName => $form) {
foreach ($form['fields'] as $key => $array) {
if ('post' == $array['record_type']) {
$forms[$formName]['fields'][$key] = array_merge($defaultFields['field_types']['post'][$array['name']], $array);
} elseif ('custom' == $array['record_type']) {
$forms[$formName]['fields'][$key] = array_merge($defaultFields['field_types']['custom'][$array['input_type']], $array);
} else {
$forms[$formName]['fields'][$key] = array_merge($defaultFields['field_types']['optional'][$array['input_type']], $array);
}
}
}
return $forms;
}
public static function getFields()
{
$fieldBase = self::getFieldBase();
$fieldTypes = array();
/*
* Assemble post field types
*/
$fieldTypes['post'] = array(
'post_title' => array(
'input_type' => 'text',
'option_label' => __('Testimonial Title', 'testimonial-widgets'),
'map' => 'post_title',
'show_default_options' => 0,
'admin_table' => 1,
'admin_table_option' => 0,
'show_admin_table_option' => 0,
'name_mutable' => 0,
'show_length_option' => 1
),
'post_content' => array(
'input_type' => 'textarea',
'option_label' => __('Testimonial Content', 'testimonial-widgets'),
'map' => 'post_content',
'required' => 1,
'show_default_options' => 0,
'core' => 0,
'admin_table' => 0,
'show_admin_table_option' => 0,
'name_mutable' => 0,
'show_length_option' => 1
),
'featured_image' => array(
'input_type' => 'file',
'option_label' => __('Reviewer\'s photo', 'testimonial-widgets'),
'map' => 'featured_image',
'show_default_options' => 0,
'show_placeholder_option' => 0,
'admin_table' => 0,
'name_mutable' => 0,
)
);
foreach ($fieldTypes['post'] as $key => $array) {
$fieldTypes['post'][$key] = array_merge($fieldBase, $array);
}
/*
* Assemble custom field types
*/
$fieldTypes['custom'] = array(
'text' => array(
'input_type' => 'text',
'option_label' => __('text', 'testimonial-widgets'),
),
'url' => array(
'input_type' => 'url',
'option_label' => __('URL', 'testimonial-widgets'),
'show_default_options' => 0,
),
'checkbox' => array(
'input_type' => 'checkbox',
'option_label' => __('checkbox', 'testimonial-widgets'),
'show_text_option' => 1,
'show_placeholder_option' => 0,
),
);
foreach ($fieldTypes['custom'] as $key => $array) {
$fieldTypes['custom'][$key] = array_merge($fieldBase, $array);
}
/*
* Assemble special field types (FKA Optional)
*
* @since 1.18
* @since 2.2.2 Fix bug caused by localizing 'categories'
*/
$fieldTypes['optional'] = array(
'category-selector' => array(
'input_type' => 'category-selector',
'option_label' => __('category selector', 'testimonial-widgets'),
'show_default_options' => 0,
'show_placeholder_option' => 0,
'show_admin_table_option' => 0,
'name_mutable' => 0,
),
'category-checklist' => array(
'input_type' => 'category-checklist',
'option_label' => __('category checklist', 'testimonial-widgets'),
'show_default_options' => 0,
'show_placeholder_option' => 0,
'show_admin_table_option' => 0,
'name_mutable' => 0,
),
'shortcode' => array(
'input_type' => 'shortcode',
'option_label' => __('shortcode', 'testimonial-widgets'),
'show_label' => 0,
'required' => 0,
'show_required_option' => 0,
'show_default_options' => 0,
'show_placeholder_option' => 0,
'show_admin_table_option' => 0,
'show_shortcode_options' => 1,
),
'rating' => array(
'input_type' => 'rating',
'option_label' => __('star rating', 'testimonial-widgets'),
'show_placeholder_option' => 0,
'admin_table' => 1,
'admin_table_option' => 1,
'show_admin_table_option' => 1,
),
);
/*
* Merge each one onto base field
*/
foreach ($fieldTypes['optional'] as $key => $array) {
$fieldTypes['optional'][$key] = array_merge($fieldBase, $array);
}
/*
* Assemble all fields
*/
$defaultFields = array(
'field_base' => $fieldBase,
'field_types' => $fieldTypes,
);
return apply_filters('wpttst_default_fields', $defaultFields);
}
public static function getFieldBase()
{
return apply_filters('wpttst_field_base', array(
'name' => '',
'name_mutable' => 1,
'label' => '',
'show_label' => 1,
'input_type' => '',
'action_input' => '',
'action_output' => '',
'text' => '',
'show_text_option' => 0,
'required' => 0,
'show_required_option' => 1,
'default_form_value' => '',
'default_display_value' => '',
'show_default_options' => 1,
'error' => __('This field is required.', 'testimonial-widgets'),
'placeholder' => '',
'show_placeholder_option' => 1,
'before' => '',
'after' => '',
'admin_table' => 0,
'admin_table_option' => 1,
'show_admin_table_option' => 1,
'shortcode_on_form' => '',
'shortcode_on_display' => '',
'show_shortcode_options' => 0,
'show_length_option' => 0,
'max_length' => ''
));
}

public static $widgetTemplates = array(
'categories' =>
array(
'slider' => '4,5,13,15,19,34,36,37,39,44,45,46,47',
'sidebar' => '6,7,8,9,10,18',
'list' => '33',
'grid' => '16,31,38,48',
'floating' => '17,21',
),
'templates' =>
array(
4 =>
array(
'name' => 'Slider I.',
'type' => 'slider',
'image' => 60,
'generate' => false,
),
15 =>
array(
'name' => 'Slider II.',
'type' => 'slider',
'image' => 60,
'generate' => false,
),
36 =>
array(
'name' => 'Slider I. - centered',
'type' => 'slider',
'image' => 120,
'generate' => true,
),
39 =>
array(
'name' => 'Slider II. - centered',
'type' => 'slider',
'image' => 120,
'generate' => true,
),
45 =>
array(
'name' => 'Slider I. - Large picture',
'type' => 'slider',
'image' => 460,
'generate' => false,
),
46 =>
array(
'name' => 'Slider II. - Large picture',
'type' => 'slider',
'image' => 554,
'generate' => false,
),
44 =>
array(
'name' => 'Slider VI.',
'type' => 'slider',
'image' => 100,
'generate' => true,
),
47 =>
array(
'name' => 'Slider III. - Large picture',
'type' => 'slider',
'image' => 554,
'generate' => false,
),
37 =>
array(
'name' => 'Slider V.',
'type' => 'slider',
'image' => 120,
'generate' => true,
),
19 =>
array(
'name' => 'Slider IV.',
'type' => 'slider',
'image' => 60,
'generate' => false,
),
38 =>
array(
'name' => 'Grid II.',
'type' => 'grid',
'image' => 120,
'generate' => true,
),
48 =>
array(
'name' => 'Grid I. - Large picture',
'type' => 'grid',
'image' => 554,
'generate' => false,
),
16 =>
array(
'name' => 'Grid',
'type' => 'grid',
'image' => 60,
'generate' => false,
),
33 =>
array(
'name' => 'List I.',
'type' => 'list',
'image' => 60,
'generate' => false,
),
31 =>
array(
'name' => 'Masonry grid',
'type' => 'grid',
'image' => 60,
'generate' => false,
),
6 =>
array(
'name' => 'Sidebar slider I.',
'type' => 'sidebar',
'image' => 60,
'generate' => false,
),
7 =>
array(
'name' => 'Sidebar slider II.',
'type' => 'sidebar',
'image' => 60,
'generate' => false,
),
18 =>
array(
'name' => 'Full sidebar I. - without header',
'type' => 'sidebar',
'image' => 60,
'generate' => false,
),
10 =>
array(
'name' => 'Full sidebar III.',
'type' => 'sidebar',
'image' => 60,
'generate' => false,
),
17 =>
array(
'name' => 'Floating',
'type' => 'floating',
'image' => 60,
'generate' => false,
),
21 =>
array(
'name' => 'Floating II.',
'type' => 'floating',
'image' => 60,
'generate' => false,
),
),
);
public static $widgetStyles = array(
'light-background' =>
array(
'id' => 'light-background',
'name' => 'Light background',
'position' => 0,
'select-position' => 0,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#efefef',
'box-border-color' => '#efefef',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-background-large' =>
array(
'id' => 'light-background-large',
'name' => 'Light background - large',
'position' => 0,
'select-position' => 0,
'reviewer-photo' => true,
'verified-icon' => false,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#f8f9f9',
'box-border-color' => '#f8f9f9',
'box-border-radius' => '12px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#c3c3c3',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'ligth-border' =>
array(
'id' => 'ligth-border',
'name' => 'Light border',
'position' => 0,
'select-position' => 1,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-border"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#e5e5e5',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'ligth-border-3d-large' =>
array(
'id' => 'ligth-border-3d-large',
'name' => 'Light border - 3D - large',
'position' => 0,
'select-position' => 1,
'reviewer-photo' => false,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-border-3d-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#efefef',
'box-border-radius' => '10px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#b4b4b4',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '1px',
'box-border-bottom-width' => '4px',
'box-border-left-width' => '1px',
'box-border-right-width' => '4px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'ligth-border-large' =>
array(
'id' => 'ligth-border-large',
'name' => 'Light border - large',
'position' => 0,
'select-position' => 1,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => true,
'hide-stars' => true,
'_vars' =>
array(
'style_id' => '"light-border-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#e2e2e2',
'box-border-radius' => '4px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#cccccc',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '1px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '1px',
'box-border-right-width' => '1px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'ligth-border-large-red' =>
array(
'id' => 'ligth-border-large-red',
'name' => 'Light border - large - red',
'position' => 0,
'select-position' => 1,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-border-large-red"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#d93623',
'box-border-radius' => '0px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#8d8d8d',
'arrow-color' => '#8d8d8d',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '3px',
'box-border-bottom-width' => '3px',
'box-border-left-width' => '3px',
'box-border-right-width' => '3px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'drop-shadow' =>
array(
'id' => 'drop-shadow',
'name' => 'Drop shadow',
'position' => 0,
'select-position' => 2,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"drop-shadow"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '5px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'false',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'drop-shadow-large' =>
array(
'id' => 'drop-shadow-large',
'name' => 'Drop shadow - large',
'position' => 0,
'select-position' => 2,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"drop-shadow-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#444444',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '12px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#939393',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.1',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-minimal' =>
array(
'id' => 'light-minimal',
'name' => 'Minimal',
'position' => 0,
'select-position' => 3,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-minimal"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#efefef',
'box-border-radius' => '0px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-minimal-large' =>
array(
'id' => 'light-minimal-large',
'name' => 'Minimal - large',
'position' => 0,
'select-position' => 3,
'reviewer-photo' => false,
'verified-icon' => false,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-minimal-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '0px',
'box-padding' => '20px',
'scroll' => 'false',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'soft' =>
array(
'id' => 'soft',
'name' => 'Soft',
'position' => 1,
'select-position' => 4,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"soft"',
'bg-color' => '#e4e4e4',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#b7b7b7',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-clean' =>
array(
'id' => 'light-clean',
'name' => 'Light clean',
'position' => 0,
'select-position' => 5,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-clean"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '14px',
'review-font-size' => '13px',
'rating-text' => '14px',
'company-font-size' => '15px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#dddddd',
'box-border-radius' => '0px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '1px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '1px',
'box-border-right-width' => '1px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-square' =>
array(
'id' => 'light-square',
'name' => 'Clean dark',
'position' => 0,
'select-position' => 6,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-square"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '14px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '15px',
'review-lines' => '4',
'box-background-color' => '#f3f3f3',
'box-border-color' => '#dddddd',
'box-border-radius' => '0px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '3px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-background-border' =>
array(
'id' => 'light-background-border',
'name' => 'Light background border',
'position' => 0,
'select-position' => 7,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background-border"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#efefef',
'box-border-color' => '#cccccc',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'blue' =>
array(
'id' => 'blue',
'name' => 'Blue',
'position' => 0,
'select-position' => 8,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"blue"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#365899',
'profile-font-size' => '14px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '15px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#dddfe2',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '1px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '1px',
'box-border-right-width' => '1px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-background-large-purple' =>
array(
'id' => 'light-background-large-purple',
'name' => 'Light background - large - purple',
'position' => 0,
'select-position' => 9,
'reviewer-photo' => true,
'verified-icon' => false,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background-large-purple"',
'bg-color' => '#ffffff',
'text-color' => '#593072',
'outside-text-color' => '#593072',
'profile-color' => '#593072',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#f6f1f9',
'box-border-color' => '#fbf9fc',
'box-border-radius' => '15px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#593072',
'arrow-color' => '#593072',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '5px',
'box-border-bottom-width' => '5px',
'box-border-left-width' => '5px',
'box-border-right-width' => '5px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-background-image' =>
array(
'id' => 'light-background-image',
'name' => 'Light background image',
'position' => 0,
'select-position' => 9,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background-image"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '14px',
'review-font-size' => '14px',
'rating-text' => '15px',
'company-font-size' => '18px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '8px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#999999',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'false',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.05',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0.3',
'box-backdrop-blur' => '5px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '15px',
),
),
'light-contrast' =>
array(
'id' => 'light-contrast',
'name' => 'Light contrast',
'position' => 0,
'select-position' => 10,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-contrast"',
'bg-color' => '#ffffff',
'text-color' => '#ffffff',
'outside-text-color' => '#000000',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#222222',
'box-border-color' => '#222222',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#555555',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-contrast-large' =>
array(
'id' => 'light-contrast-large',
'name' => 'Light contrast - large',
'position' => 0,
'select-position' => 10,
'reviewer-photo' => false,
'verified-icon' => false,
'hide-logos' => true,
'hide-stars' => true,
'_vars' =>
array(
'style_id' => '"light-contrast-large"',
'bg-color' => '#ffffff',
'text-color' => '#ffffff',
'outside-text-color' => '#252c44',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#252c44',
'box-border-color' => '#252c44',
'box-border-radius' => '0px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#ffffff',
'arrow-color' => '#252c44',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#252c44',
'box-shadow-opacity' => '0.45',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-contrast-large-blue' =>
array(
'id' => 'light-contrast-large-blue',
'name' => 'Light contrast - large - blue',
'position' => 0,
'select-position' => 10,
'reviewer-photo' => true,
'verified-icon' => false,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-contrast-large-blue"',
'bg-color' => '#ffffff',
'text-color' => '#ffffff',
'outside-text-color' => '#252c44',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#242f62',
'box-border-color' => '#2aa8d7',
'box-border-radius' => '0px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#ffffff',
'arrow-color' => '#242f62',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#252c44',
'box-shadow-opacity' => '0.45',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '10px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-background' =>
array(
'id' => 'dark-background',
'name' => 'Dark background',
'position' => 1,
'select-position' => 11,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-background"',
'bg-color' => '#222222',
'text-color' => '#ffffff',
'outside-text-color' => '#ffffff',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#303030',
'box-border-color' => '#303030',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#666666',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-minimal' =>
array(
'id' => 'dark-minimal',
'name' => 'Minimal dark',
'position' => 0,
'select-position' => 11,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-minimal"',
'bg-color' => '#000000',
'text-color' => '#ffffff',
'outside-text-color' => '#ffffff',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#444444',
'box-border-radius' => '0px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-border' =>
array(
'id' => 'dark-border',
'name' => 'Dark border',
'position' => 1,
'select-position' => 12,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-border"',
'bg-color' => '#222222',
'text-color' => '#ffffff',
'outside-text-color' => '#ffffff',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#222222',
'box-border-color' => '#444444',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#444444',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-contrast' =>
array(
'id' => 'dark-contrast',
'name' => 'Dark contrast',
'position' => 1,
'select-position' => 14,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-contrast"',
'bg-color' => '#222222',
'text-color' => '#000000',
'outside-text-color' => '#ffffff',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#ffffff',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-background-image' =>
array(
'id' => 'dark-background-image',
'name' => 'Dark background image',
'position' => 0,
'select-position' => 15,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-background-image"',
'bg-color' => '#ffffff',
'text-color' => '#ffffff',
'outside-text-color' => '#ffffff',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '15px',
'rating-text' => '15px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#000000',
'box-border-color' => '#000000',
'box-border-radius' => '5px',
'box-padding' => '20px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'false',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.20',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0.3',
'box-backdrop-blur' => '5px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '15px',
),
),
);
public static $widgetLanguages = [
'ar' => "العربية",
'zh' => "简体中文",
'zh_TW' => "繁體中文",
'cs' => "Čeština",
'da' => "Dansk",
'nl' => "Nederlands",
'en' => "English",
'et' => "Eestlane",
'fi' => "Suomi",
'fr' => "Français",
'de' => "Deutsch",
'el' => "Ελληνικά",
'hi' => "हिन्दी",
'hu' => "Magyar",
'it' => "Italiano",
'no' => "Norsk",
'pl' => "Polski",
'pt' => "Português",
'ro' => "Română",
'ru' => "Русский",
'sk' => "Slovenčina",
'es' => "Español",
'sv' => "Svenska",
'tr' => "Türkçe",
'gd' => 'Gàidhlig na h-Alba',
'hr' => 'Hrvatski',
'id' => 'Bahasa Indonesia',
'is' => 'Íslensku',
'he' => 'עִברִית',
'ja' => '日本',
'ko' => '한국어',
'lt' => 'Lietuvių',
'ms' => 'Bahasa Melayu',
'sl' => 'Slovenščina',
'sr' => 'Српски',
'th' => 'ไทย',
'uk' => 'Українська',
'vi' => 'Tiếng Việt',
'mk' => 'Македонски',
'bg' => 'български',
'sq' => 'Shqip',
'af' => 'Afrikaans',
'az' => 'Azərbaycan dili',
'bn' => 'বাংলা',
'bs' => 'Bosanski',
'cy' => 'Cymraeg',
'fa' => 'فارسی'
];

public static function getWidgetLanguage($locale)
{
$locale = str_replace('-', '_', strtolower((string) $locale));
if (strpos($locale, 'zh') === 0) {
$lang = preg_match('/_(hant|tw|hk|mo)$/', $locale) ? 'zh_TW' : 'zh';
} else {
$lang = substr($locale, 0, 2);
}
return isset(self::$widgetLanguages[$lang]) ? $lang : 'en';
}
public static $widgetDateformats = ['j. F, Y.', 'F j, Y.', 'Y.m.d.', 'Y-m-d', 'd/m/Y'];
private static $widgetMonthNames = array(
'en' =>
array(
0 => 'January',
1 => 'February',
2 => 'March',
3 => 'April',
4 => 'May',
5 => 'June',
6 => 'July',
7 => 'August',
8 => 'September',
9 => 'October',
10 => 'November',
11 => 'December',
),
'et' =>
array(
0 => 'jaanuar',
1 => 'veebruar',
2 => 'märts',
3 => 'aprill',
4 => 'mai',
5 => 'juuni',
6 => 'juuli',
7 => 'august',
8 => 'september',
9 => 'oktoober',
10 => 'november',
11 => 'detsember',
),
'ar' =>
array(
0 => 'يناير',
1 => 'فبراير',
2 => 'مارس',
3 => 'أبريل',
4 => 'مايو',
5 => 'يونيو',
6 => 'يوليه',
7 => 'أغسطس',
8 => 'سبتمبر',
9 => 'أكتوبر',
10 => 'نوفمبر',
11 => 'ديسمبر',
),
'zh' =>
array(
0 => '一月',
1 => '二月',
2 => '三月',
3 => '四月',
4 => '五月',
5 => '六月',
6 => '七月',
7 => '八月',
8 => '九月',
9 => '十月',
10 => '十一月',
11 => '十二月',
),
'cs' =>
array(
0 => 'Leden',
1 => 'Únor',
2 => 'Březen',
3 => 'Duben',
4 => 'Květen',
5 => 'Červen',
6 => 'Červenec',
7 => 'Srpen',
8 => 'Září',
9 => 'Říjen',
10 => 'Listopad',
11 => 'Prosinec',
),
'da' =>
array(
0 => 'januar',
1 => 'februar',
2 => 'marts',
3 => 'april',
4 => 'maj',
5 => 'juni',
6 => 'juli',
7 => 'august',
8 => 'september',
9 => 'oktober',
10 => 'november',
11 => 'december',
),
'nl' =>
array(
0 => 'Januari',
1 => 'Februari',
2 => 'Maart',
3 => 'April',
4 => 'Mei',
5 => 'Juni',
6 => 'Juli',
7 => 'Augustus',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'December',
),
'fi' =>
array(
0 => 'Tammikuu',
1 => 'Helmikuu',
2 => 'Maaliskuu',
3 => 'Huhtikuu',
4 => 'Toukokuu',
5 => 'Kesäkuu',
6 => 'Heinäkuu',
7 => 'Elokuu',
8 => 'Syyskuu',
9 => 'Lokakuu',
10 => 'Marraskuu',
11 => 'Joulukuu',
),
'fr' =>
array(
0 => 'Janvier',
1 => 'Février',
2 => 'Mars',
3 => 'Avril',
4 => 'Mai',
5 => 'Juin',
6 => 'Juillet',
7 => 'Août',
8 => 'Septembre',
9 => 'Octobre',
10 => 'Novembre',
11 => 'Décembre',
),
'de' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'März',
3 => 'April',
4 => 'Mai',
5 => 'Juni',
6 => 'Juli',
7 => 'August',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Dezember',
),
'el' =>
array(
0 => 'Ιανουαρίου',
1 => 'Φεβρουαρίου',
2 => 'Μαρτίου',
3 => 'Απριλίου',
4 => 'Μαΐου',
5 => 'Ιουνίου',
6 => 'Ιουλίου',
7 => 'Αυγούστου',
8 => 'Σεπτεμβρίου',
9 => 'Οκτωβρίου',
10 => 'Νοεμβρίου',
11 => 'Δεκεμβρίου',
),
'he' =>
array(
0 => 'ינואר',
1 => 'פברואר',
2 => 'מרץ',
3 => 'אפריל',
4 => 'מאי',
5 => 'יוני',
6 => 'יולי',
7 => 'אוגוסט',
8 => 'ספטמבר',
9 => 'אוקטובר',
10 => 'נובמבר',
11 => 'דצמבר',
),
'hi' =>
array(
0 => 'जनवरी',
1 => 'फ़रवरी',
2 => 'मार्च',
3 => 'अप्रैल',
4 => 'मई',
5 => 'जून',
6 => 'जुलाई',
7 => 'अगस्त',
8 => 'सितंबर',
9 => 'अक्टूबर',
10 => 'नवंबर',
11 => 'दिसंबर',
),
'hu' =>
array(
0 => 'Január',
1 => 'Február',
2 => 'Március',
3 => 'Április',
4 => 'Május',
5 => 'Június',
6 => 'Július',
7 => 'Augusztus',
8 => 'Szeptember',
9 => 'Október',
10 => 'November',
11 => 'December',
),
'it' =>
array(
0 => 'Gennaio',
1 => 'Febbraio',
2 => 'Marzo',
3 => 'Aprile',
4 => 'Maggio',
5 => 'Giugno',
6 => 'Luglio',
7 => 'Agosto',
8 => 'Settembre',
9 => 'Ottobre',
10 => 'Novembre',
11 => 'Dicembre',
),
'ja' =>
array(
0 => '1月',
1 => '2月',
2 => '3月',
3 => '4月',
4 => '5月',
5 => '6月',
6 => '7月',
7 => '8月',
8 => '9月',
9 => '10月',
10 => '11月',
11 => '12月',
),
'no' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'Mars',
3 => 'April',
4 => 'Mai',
5 => 'Juni',
6 => 'Juli',
7 => 'August',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Desember',
),
'pl' =>
array(
0 => 'Styczeń',
1 => 'Luty',
2 => 'Marzec',
3 => 'Kwiecień',
4 => 'Maj',
5 => 'Czerwiec',
6 => 'Lipiec',
7 => 'Sierpień',
8 => 'Wrzesień',
9 => 'Październik',
10 => 'Listopad',
11 => 'Grudzień',
),
'pt' =>
array(
0 => 'Janeiro',
1 => 'Fevereiro',
2 => 'Março',
3 => 'Abril',
4 => 'Maio',
5 => 'Junho',
6 => 'Julho',
7 => 'Agosto',
8 => 'Setembro',
9 => 'Outubro',
10 => 'Novembro',
11 => 'Dezembro',
),
'ro' =>
array(
0 => 'Ianuarie',
1 => 'Februarie',
2 => 'Martie',
3 => 'Aprilie',
4 => 'Mai',
5 => 'Iunie',
6 => 'Iulie',
7 => 'August',
8 => 'Septembrie',
9 => 'Octombrie',
10 => 'Noiembrie',
11 => 'Decembrie',
),
'ru' =>
array(
0 => 'январь',
1 => 'февраль',
2 => 'март',
3 => 'апрель',
4 => 'май',
5 => 'июнь',
6 => 'июль',
7 => 'август',
8 => 'сентябрь',
9 => 'октябрь',
10 => 'ноябрь',
11 => 'декабрь',
),
'sk' =>
array(
0 => 'Január',
1 => 'Február',
2 => 'Marec',
3 => 'Apríl',
4 => 'Máj',
5 => 'Jún',
6 => 'Júl',
7 => 'August',
8 => 'September',
9 => 'Október',
10 => 'November',
11 => 'December',
),
'sl' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'Marec',
3 => 'April',
4 => 'Maj',
5 => 'Junij',
6 => 'Julij',
7 => 'Avgust',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'December',
),
'es' =>
array(
0 => 'Enero',
1 => 'Febrero',
2 => 'Marzo',
3 => 'Abril',
4 => 'Mayo',
5 => 'Junio',
6 => 'Julio',
7 => 'Agosto',
8 => 'Septiembre',
9 => 'Octubre',
10 => 'Noviembre',
11 => 'Diciembre',
),
'sv' =>
array(
0 => 'Januari',
1 => 'Februari',
2 => 'Mars',
3 => 'April',
4 => 'Maj',
5 => 'Juni',
6 => 'Juli',
7 => 'Augusti',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'December',
),
'tr' =>
array(
0 => 'Ocak',
1 => 'Şubat',
2 => 'Mart',
3 => 'Nisan',
4 => 'Mayis',
5 => 'Haziran',
6 => 'Temmuz',
7 => 'Ağustos',
8 => 'Eylül',
9 => 'Ekim',
10 => 'Kasım',
11 => 'Aralık',
),
'uk' =>
array(
0 => 'Січня',
1 => 'Лютий',
2 => 'Березень',
3 => 'квітень',
4 => 'травень',
5 => 'червень',
6 => 'липень',
7 => 'серпень',
8 => 'вересень',
9 => 'жовтень',
10 => 'листопад',
11 => 'грудень',
),
'gd' =>
array(
0 => 'am Faoilleach',
1 => 'an Gearran',
2 => 'am Màrt',
3 => 'an Giblean',
4 => 'an Cèitean',
5 => 'an t-Ògmhios',
6 => 'an t-luchar',
7 => 'an Lùnastal',
8 => 'an t-Sultain',
9 => 'an Dàmhair',
10 => 'an t-Samhain',
11 => 'an Dùbhlachd',
),
'hr' =>
array(
0 => 'Siječanj',
1 => 'Veljača',
2 => 'Ožujak',
3 => 'Travanj',
4 => 'Svibanj',
5 => 'Lipanj',
6 => 'Srpanj',
7 => 'Kolovoz',
8 => 'Rujan',
9 => 'Listopad',
10 => 'Studeni',
11 => 'Prosinac',
),
'id' =>
array(
0 => 'Januari',
1 => 'Februari',
2 => 'Maret',
3 => 'April',
4 => 'Mei',
5 => 'Juni',
6 => 'Juli',
7 => 'Agustus',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Desember',
),
'is' =>
array(
0 => 'Janúar',
1 => 'Febrúar',
2 => 'Mars',
3 => 'April',
4 => 'Maí',
5 => 'Júní',
6 => 'Júlí',
7 => 'Ágúst',
8 => 'September',
9 => 'Október',
10 => 'Nóvember',
11 => 'Desember',
),
'ko' =>
array(
0 => '일월',
1 => '이월',
2 => '삼월',
3 => '사월',
4 => '오월',
5 => '유월',
6 => '칠월',
7 => '팔월',
8 => '구월',
9 => '시월',
10 => '십일월',
11 => '십이월',
),
'lt' =>
array(
0 => 'Sausis',
1 => 'Vasaris',
2 => 'Kovas',
3 => 'Balandis',
4 => 'Gegužė',
5 => 'Birželis',
6 => 'Liepa',
7 => 'Rugpjūtis',
8 => 'Rugsėjis',
9 => 'Spalis',
10 => 'Lapkritis',
11 => 'Gruodis',
),
'ms' =>
array(
0 => 'Januari',
1 => 'Februari',
2 => 'Mac',
3 => 'April',
4 => 'Mei',
5 => 'Jun',
6 => 'Julai',
7 => 'Ogos',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Disember',
),
'sr' =>
array(
0 => 'Јануар',
1 => 'Фебруар',
2 => 'Март',
3 => 'Април',
4 => 'Mај',
5 => 'Јуни',
6 => 'Јул',
7 => 'Август',
8 => 'Cептембар',
9 => 'Октобар',
10 => 'Новембар',
11 => 'Децембар',
),
'th' =>
array(
0 => 'มกราคม',
1 => 'กุมภาพันธ์',
2 => 'มีนาคม',
3 => 'เมษายน',
4 => 'พฤษภาคม',
5 => 'มิถุนายน',
6 => 'กรกฎาคม',
7 => 'สิงหาคม',
8 => 'กันยายน',
9 => 'ตุลาคม',
10 => 'พฤศจิกายน',
11 => 'ธันวาคม',
),
'vi' =>
array(
0 => 'tháng một',
1 => 'tháng hai',
2 => 'tháng ba',
3 => 'tháng tư',
4 => 'tháng năm',
5 => 'tháng sáu',
6 => 'tháng bảy',
7 => 'tháng tám',
8 => 'tháng chín',
9 => 'tháng mười',
10 => 'tháng mười một',
11 => 'tháng mười hai',
),
'mk' =>
array(
0 => 'Jануари',
1 => 'февруари',
2 => 'март',
3 => 'април',
4 => 'мај',
5 => 'јуни',
6 => 'јули',
7 => 'август',
8 => 'септември',
9 => 'октомври',
10 => 'ноември',
11 => 'декември',
),
'bg' =>
array(
0 => 'Януари',
1 => 'февруари',
2 => 'Март',
3 => 'Aприл',
4 => 'май',
5 => 'юни',
6 => 'юли',
7 => 'Август',
8 => 'Септември',
9 => 'Октомври',
10 => 'Ноември',
11 => 'Декември',
),
'sq' =>
array(
0 => 'Janar',
1 => 'Shkurt',
2 => 'Mars',
3 => 'Prill',
4 => 'Maj',
5 => 'Qershor',
6 => 'Korrik',
7 => 'Gusht',
8 => 'Shtator',
9 => 'Tetor',
10 => 'Nëntor',
11 => 'Dhjetor',
),
'af' =>
array(
0 => 'Januarie',
1 => 'Februarie',
2 => 'Maart',
3 => 'April',
4 => 'Mei',
5 => 'Junie',
6 => 'Julie',
7 => 'Augustus',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Desember',
),
'az' =>
array(
0 => 'Yanvar',
1 => 'Fevral',
2 => 'Mart',
3 => 'Aprel',
4 => 'May',
5 => 'İyun',
6 => 'İyul',
7 => 'Avqust',
8 => 'Sentyabr',
9 => 'Oktyabr',
10 => 'Noyabr',
11 => 'Dekabr',
),
'bn' =>
array(
0 => 'জানুয়ারি',
1 => 'ফেব্রুয়ারি',
2 => 'মার্চ',
3 => 'এপ্রিল',
4 => 'মে',
5 => 'জুন',
6 => 'জুলাই',
7 => 'আগস্ট',
8 => 'সেপ্টেম্বর',
9 => 'অক্টোবর',
10 => 'নভেম্বর',
11 => 'ডিসেম্বর',
),
'bs' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'Mart',
3 => 'April',
4 => 'Maj',
5 => 'Jun',
6 => 'Jul',
7 => 'Avgust',
8 => 'Septembar',
9 => 'Oktobar',
10 => 'Novembar',
11 => 'Decembar',
),
'cy' =>
array(
0 => 'Ionawr',
1 => 'Chwefror',
2 => 'Mawrth',
3 => 'Ebrill',
4 => 'Mai',
5 => 'Mehefin',
6 => 'Gorffennaf',
7 => 'Awst',
8 => 'Medi',
9 => 'Hydref',
10 => 'Tachwedd',
11 => 'Rhagfyr',
),
'fa' =>
array(
0 => 'ژانویه',
1 => 'فوریه',
2 => 'مارس',
3 => 'آوریل',
4 => 'ممکن است',
5 => 'ژوئن',
6 => 'جولای',
7 => 'اوت',
8 => 'سپتامبر',
9 => 'اکتبر',
10 => 'نوامبر',
11 => 'دسامبر',
),
);

public static function getWidgetTablename()
{
global $wpdb;
return $wpdb->prefix . "trustindex_testimonials_widgets";
}
public function createWidgetsTable()
{
$dbtable = $this->getWidgetTablename();
$sql = "CREATE TABLE IF NOT EXISTS $dbtable (
id TINYINT(2) NOT NULL AUTO_INCREMENT,
name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
value TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
review_content TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
css TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
created_at DATETIME DEFAULT NULL,
updated_at DATETIME DEFAULT NULL,
PRIMARY KEY (id)
);";
dbDelta($sql);
}
static function removeWidgetsTable()
{
global $wpdb;
$dbtable = TrustindexTestimonialsPlugin::getWidgetTablename();
$wpdb->query($wpdb->prepare('DROP TABLE IF EXISTS %i', $dbtable));
}
public function createWidget($name, $widgetSettings)
{
$dbtable = $this->getWidgetTablename();
global $wpdb;
$result = $wpdb->insert(
$dbtable,
array(
'name' => $name,
'value' => $widgetSettings,
'review_content' => null,
'css' => null,
'created_at' => gmdate('Y-m-d H:i:s'),
'updated_at' => gmdate('Y-m-d H:i:s'),
),
array('%s', '%s', '%s', '%s', '%s')
);
if ($result)
{
return $wpdb->insert_id;
}
else
{
return false;
}
}
public function updateWidget($id, $name, $widgetSettings)
{
$dbtable = $this->getWidgetTablename();
global $wpdb;
$result = $wpdb->update(
$dbtable,
array(
'name' => $name,
'value' => $widgetSettings,
'updated_at' => gmdate('Y-m-d H:i:s'),
),
array('id' => $id),
array('%s', '%s', '%s'),
array('%d')
);
return $result;
}
public function updateWidgetReviewContent($id, $reviewContent)
{
$dbtable = $this->getWidgetTablename();
global $wpdb;
$result = $wpdb->update(
$dbtable,
array(
'review_content' => $reviewContent,
),
array('id' => $id),
array('%s'),
array('%d')
);
return $result;
}
public function updateWidgetCss($id, $css)
{
$dbtable = $this->getWidgetTablename();
global $wpdb;
$result = $wpdb->update(
$dbtable,
array(
'css' => $css,
),
array('id' => $id),
array('%s'),
array('%d')
);
return $result;
}
public function getNextWidgetId()
{
$dbtable = $this->getWidgetTablename();
global $wpdb;
$result = $wpdb->get_results($wpdb->prepare('SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = %s', $dbtable));
return $result[0]->auto_increment;
}
public function getWidget($id)
{
$widgetId = (int)$id;
$dbtable = $this->getWidgetTablename();
global $wpdb;
$result = $wpdb->get_row($wpdb->prepare('SELECT * FROM %i WHERE id = %d', $dbtable, $widgetId));
return $result;
}
public function getWidgets($orderBy = "id", $order = "asc", $search = null)
{
$dbtable = $this->getWidgetTablename();
global $wpdb;
$orderColumn = 'name' === $orderBy ? 'name' : 'id';
if ($search)
{
$results = $wpdb->get_results($wpdb->prepare('SELECT * FROM %i WHERE name LIKE %s ORDER BY %i', $dbtable, '%' . $wpdb->esc_like($search) . '%', $orderColumn));
}
else
{
$results = $wpdb->get_results($wpdb->prepare('SELECT * FROM %i ORDER BY %i', $dbtable, $orderColumn));
}
return 'desc' === $order ? array_reverse($results) : $results;
}
public function deleteWidget($id)
{
$dbtable = $this->getWidgetTablename();
global $wpdb;
$result = $wpdb->delete($dbtable, array('id' => $id), array( '%d' ));
return $result;
}
public function duplicateWidget($id)
{
$widgetId = (int)$id;
$dbtable = $this->getWidgetTablename();
global $wpdb;
$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM %i WHERE id = %d', $dbtable, $widgetId));
$name = $row->name . '-' . __('copy', 'testimonial-widgets');
$result = $wpdb->insert(
$dbtable,
array(
'name' => $name,
'value' => $row->value,
'review_content' => $row->review_content,
'created_at' => gmdate('Y-m-d H:i:s'),
'updated_at' => $row->updated_at
),
array('%s', '%s', '%s', '%s', '%s')
);
$newId = $wpdb->insert_id;
$css = $this->copyCss($row->css, $id, $newId);
$this->updateWidgetCss($newId, $css);
return $result;
}
private function copyCss($css, $fromId, $toId)
{
return str_replace('.ti-wp-testimonial-'.$fromId, '.ti-wp-testimonial-' . $toId, $css);
}
public function getCategories()
{
$result = array();
$terms = get_terms('wpt-testimonial-category');
if ($terms && !is_wp_error($terms))
{
foreach($terms as $term)
{
$result[] = array(
'term_id' => $term->term_id,
'name' => $term->name
);
}
}
return $result;
}
public function getReviews($filter = null)
{
$result = array();
if (isset($filter) && $filter['mode'] == 'manual_select')
{
foreach ($filter['ids'] as $id)
$result[] = $this->getReviewData($id);
if (isset($filter['order']) && $filter['order'] == 'newest')
{
usort($result, function ($a, $b) { return (strtotime($b["date"]) - strtotime($a["date"])); });
}
else if (isset($filter['order']) && $filter['order'] == 'oldest')
{
usort($result, function ($a, $b) { return (strtotime($a["date"]) - strtotime($b["date"])); });
}
else if (isset($filter['order']) && $filter['order'] == 'random')
{
shuffle($result);
}
}
else
{
$args = array(
'post_type' => 'wpt-testimonial',
'post_status' => 'publish',
'numberposts' => -1,
);
if (isset($filter) && $filter['mode'] == 'category')
{
$args['tax_query'] = array(
array(
'taxonomy' => 'wpt-testimonial-category',
'field' => 'term_id',
'terms' => (int)$filter['category'],
)
);
}
if (isset($filter) && isset($filter['order']))
{
if ($filter['order'] == 'newest')
{
$args['orderby'] = 'date';
$args['order'] = 'DESC';
}
else if ($filter['order'] == 'oldest')
{
$args['orderby'] = 'date';
$args['order'] = 'ASC';
}
else if ($filter['order'] == 'random')
{
$args['orderby'] = 'rand';
}
}
if (isset($filter) && $filter['rating'] !== 'all')
{
$rs = $this->getReviewsData($args);
$acceptedRatings = null;
if ($filter['rating'] === 'only_5')
{
$acceptedRatings = [5];
}
else if ($filter['rating'] === 'min_4')
{
$acceptedRatings = [4,5];
}
else if ($filter['rating'] === 'min_3')
{
$acceptedRatings = [3,4,5];
}
else if ($filter['rating'] === 'max_3')
{
$acceptedRatings = [1,2,3];
}
foreach ($rs as $r)
{
if (in_array($r['star_rating'], $acceptedRatings))
{
$result[] = $r;
}
}
}
else
{
$result = $this->getReviewsData($args);
}
}
return $result;
}
private function getReviewsData($args)
{
$result = array();
$metaKeys = array(
'client_name',
'company_name',
'company_website',
'star_rating'
);
$posts = get_posts($args);
if ($posts && !is_wp_error($posts))
{
foreach($posts as $post)
{
$tmp = array();
$tmp['id'] = $post->ID;
if(!empty($post->post_date))
{
$date = strtotime($post->post_date);
$tmp['date'] = gmdate('Y-m-d', $date);
}
else
{
$tmp['date'] = null;
}
$tmp['content'] = !empty($post->post_content) ? $post->post_content : null;
$tmp['title'] = !empty($post->post_title) ? $post->post_title : null;
foreach($metaKeys as $key)
{
$value = $this->getPostMetaValue($post->ID, $key);
if (!empty($value))
{
$tmp[$key] = $this->getPostMetaValue($post->ID, $key);
}
else
{
$tmp[$key] = null;
}
}
$tmp['photo'] = array();
foreach (TrustindexTestimonialsPlugin::$thumbnailSizes as $thumbnailSize)
{
$tmp['photo'][$thumbnailSize] = get_the_post_thumbnail_url($post->ID, 'wpt-testimonial-thumbnail-' . $thumbnailSize);
}
$result[] = $tmp;
}
}
return $result;
}
private function getReviewData($id)
{
$result = array();
$metaKeys = array(
'client_name',
'company_name',
'company_website',
'star_rating'
);
$post = get_post($id);
if ($post && !is_wp_error($post))
{
$result['id'] = $post->ID;
if(!empty($post->post_date))
{
$date = strtotime($post->post_date);
$result['date'] = gmdate('Y-m-d', $date);
}
else
{
$result['date'] = null;
}
$result['content'] = !empty($post->post_content) ? $post->post_content : null;
$result['title'] = !empty($post->post_title) ? $post->post_title : null;
foreach($metaKeys as $key)
{
$value = $this->getPostMetaValue($post->ID, $key);
if (!empty($value))
{
$result[$key] = $this->getPostMetaValue($post->ID, $key);
}
else
{
$result[$key] = null;
}
}
$result['photo'] = array();
foreach (TrustindexTestimonialsPlugin::$thumbnailSizes as $thumbnailSize)
{
$result['photo'][$thumbnailSize] = get_the_post_thumbnail_url($post->ID, 'wpt-testimonial-thumbnail-' . $thumbnailSize);
}
}
return $result;
}
private function getPostMetaValue($id, $key)
{
return get_post_meta($id, $key, true);
}
static function removeTestimonials()
{
$testimonials = get_posts(
array(
'numberposts' => -1,
'post_type' => 'wpt-testimonial',
'post_status' => get_post_types(),
)
);
if ($testimonials && !is_wp_error($testimonials))
{
foreach ( $testimonials as $t )
{
wp_delete_post( $t->ID, true);
}
}
}
public function removeTestimonialCategories()
{
global $wpdb;
$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->term_taxonomy} WHERE taxonomy = %s", 'wpt-testimonial-category'));
$termIds = array();
$termTaxonomyIds = array();
foreach ($results as $result)
{
$termTaxonomyIds[] = $result->term_taxonomy_id;
$termIds[] = $result->term_id;
}
if ( is_array( $termIds ) && !empty( $termIds ) )
{
$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->terms} WHERE term_id IN (" . implode( ',', array_fill( 0, count( $termIds ), '%d' ) ) . ')', $termIds ) );
$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->term_taxonomy} WHERE term_id IN (" . implode( ',', array_fill( 0, count( $termIds ), '%d' ) ) . ')', $termIds ) );
}
if ( is_array( $termTaxonomyIds ) && !empty( $termTaxonomyIds ) )
{
$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN (" . implode( ',', array_fill( 0, count( $termTaxonomyIds ), '%d' ) ) . ')', $termTaxonomyIds ) );
}
}

public function getCategoryTablename()
{
global $wpdb;
return $wpdb->prefix . "terms";
}
}
