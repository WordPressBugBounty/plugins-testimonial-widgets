<?php
defined('ABSPATH') or die('No script kiddies please!');
$wpttstCurrentStep = isset($_GET['step']) ? intval(sanitize_text_field(wp_unslash($_GET['step']))) : 1;
$page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : '';
$id = isset($_GET['id']) ? intval(sanitize_text_field(wp_unslash($_GET['id']))) : null;
$wpttstNameMissing = isset($_GET['name-required']) ? true : false;
$wpttstSelected = isset($_GET['selected']) ? true : null;
$wpttstStyleId = isset($_GET['style_id']) ? intval(sanitize_text_field(wp_unslash($_GET['style_id']))) : null;
$wpttstScssSet = isset($_GET['scss_set']) ? sanitize_text_field(wp_unslash($_GET['scss_set'])) : null;
$wpttstWidgetSettedUp = isset($_GET['setup_widget']) ? true : null;
wp_enqueue_style("trustindex-widget-preview-css", "https://cdn.trustindex.io/" . "assets/ti-preview-box.css", [], "1.4.14");
global $wpttstPluginManager;
$wpttstWidgetName = null;
if ($wpttstScssSet)
{
$wpttstUseAppearance = $wpttstScssSet;
}
else
{
$wpttstUseAppearance = 'light-background';
}
$wpttstAppearance = array(
'hide_stars' => false,
'hide_image' => false,
'display_type' => 'website',
'date_format' => 'j. F, Y.',
'hover-anim' => filter_var($wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['hover-anim'], FILTER_VALIDATE_BOOLEAN),
'enable-font' => filter_var($wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['enable-font'], FILTER_VALIDATE_BOOLEAN),
'nav' => $wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['nav'],
'dots' => $wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['dots'],
'text-align' => isset($wpttstStyleId) && in_array($wpttstStyleId, ['36','37','38','45','48']) ? 'center' : 'left',
'auto_height' => false,
'review-lines' =>filter_var($wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['review-lines'], FILTER_VALIDATE_INT),
'box-background-color' => rest_parse_hex_color($wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['box-background-color']),
'box-border-color' => rest_parse_hex_color($wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['box-border-color']),
'box-padding' => filter_var(str_replace('px','',$wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['box-padding']), FILTER_VALIDATE_INT),
'box-border-top-width' => filter_var(str_replace('px','',$wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['box-border-top-width']), FILTER_VALIDATE_INT),
'box-border-left-width' => filter_var(str_replace('px','',$wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['box-border-left-width']), FILTER_VALIDATE_INT),
'box-border-right-width' => filter_var(str_replace('px','',$wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['box-border-right-width']), FILTER_VALIDATE_INT),
'box-border-bottom-width' => filter_var(str_replace('px','',$wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['box-border-bottom-width']), FILTER_VALIDATE_INT),
'box-border-radius' => filter_var(str_replace('px','',$wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['box-border-radius']), FILTER_VALIDATE_INT),
'profile-font-size' => filter_var(str_replace('px','',$wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['profile-font-size']), FILTER_VALIDATE_INT),
'profile-color' => rest_parse_hex_color($wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['profile-color']),
'review-font-size' => filter_var(str_replace('px','',$wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['review-font-size']), FILTER_VALIDATE_INT),
'text-color' => rest_parse_hex_color($wpttstPluginManager::$widgetStyles[$wpttstUseAppearance]['_vars']['text-color']),
);
$wpttstNavigation = array(
'navigation_style' => 'arrow',
'slider_interval' => 6,
);
$wpttstWidget = array(
'current' => 1,
'saved' => 0,
'1' => array(
'mode' => 'all',
'selected' => null,
'rating' => 'all',
),
'2' => null,
'3' => null,
'4' => array(
'general' => array(
'order' => 'newest',
),
'appearance' => $wpttstAppearance,
'navigation' => $wpttstNavigation,
'last_saved' => 'general'
),
);
$wpttstCommand = null;
if (isset($_REQUEST['command']))
{
check_admin_referer('ti-save-widget');
$wpttstCommand = sanitize_text_field(wp_unslash($_REQUEST['command']));
}
$wpttstCommandList = [
'save-review-filter',
'save-style',
'save-set',
'save-order',
'save-display-options',
'save-box',
'save-font',
'save-slider-interval',
'save-review-lines',
'save-text-align',
'save-display_type',
'save-dateformat',
'save-navigation',
'setup-widget',
'save-arrow',
'save-dots'
];
if (!in_array($wpttstCommand, $wpttstCommandList)) {
$wpttstCommand = null;
}
if ($wpttstCurrentStep === 1)
{
$wpttstCategories = $wpttstPluginManager->getCategories();
$wpttstReviews = $wpttstPluginManager->getReviews();
$wpttstNextWidgetId = $wpttstPluginManager->getNextWidgetId();
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
if ($wpttstNameMissing == false)
{
$wpttstWidgetName = $wpttstW->name;
}
$wpttstWidgetValue = unserialize($wpttstW->value);
$wpttstWidget = $wpttstWidgetValue;
}
}
else
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidgetValue = unserialize($wpttstW->value);
$wpttstWidget = $wpttstWidgetValue;
$wpttstReviews = [1,2];
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
if ($wpttstCommand == 'save-review-filter') {
$wpttstSuccess = true;
$wpttstWidget['current'] = 2;
$wpttstWidgetName = isset($_POST['widget-name']) ? sanitize_text_field(wp_unslash($_POST['widget-name'])) : null;
if (is_null($wpttstWidgetName) || empty($wpttstWidgetName))
{
if ($id)
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1&id=' . esc_attr($id) . '&name-required"</script>';
exit;
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1&name-required"</script>';
exit;
}
}
$mode = isset($_POST['mode']) ? sanitize_text_field(wp_unslash($_POST['mode'])) : null;
$wpttstWidget['1']['mode'] = $mode;
if ($mode === 'all')
{
$wpttstWidget['1']['selected'] = null;
}
else if ($mode === 'category')
{
$wpttstCategory = isset($_POST['category']) ? sanitize_text_field(wp_unslash($_POST['category'])) : null;
if ($wpttstCategory)
{
$wpttstWidget['1']['selected'] = $wpttstCategory;
}
else
{
$wpttstSuccess = false;
}
}
else if ($mode === 'manual_select')
{
if (isset($_POST['review']) && is_array($_POST['review']))
{
$wpttstRs = array_map( 'sanitize_text_field', wp_unslash($_POST['review']));
}
else
{
$wpttstRs = null;
}
if ($wpttstRs)
{
$wpttstSelectedIds = array();
foreach($wpttstRs as $wpttstK => $wpttstV)
{
$wpttstRId = intval(sanitize_text_field($wpttstV));
if ($wpttstRId !== 0)
{
$wpttstSelectedIds[] = $wpttstRId;
}
}
$wpttstStrSelected = implode(",", $wpttstSelectedIds);
$wpttstWidget['1']['selected'] = $wpttstStrSelected;
}
else
{
$wpttstSuccess = false;
}
}
else
{
$wpttstSuccess = false;
}
if ($mode !== 'manual_select' && $wpttstSuccess)
{
$wpttstRating = isset($_POST['rating']) ? sanitize_text_field(wp_unslash($_POST['rating'])) : null;
if ($wpttstRating)
{
$wpttstWidget['1']['rating'] = $wpttstRating;
}
else
{
$wpttstSuccess = false;
}
}
if ($wpttstSuccess)
{
$wpttstWidget['saved'] = 1;
$wpttstSerializedWidget = serialize($wpttstWidget);
if ($id)
{
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
$id = $wpttstPluginManager->createWidget($wpttstWidgetName, $wpttstSerializedWidget);
}
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=2&id=' . esc_attr($id) . '&selected"</script>';
exit;
}
else
{
if ($id)
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1&id=' . esc_attr($id) . '"</script>';
exit;
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
}
else if ($wpttstCommand == 'save-style')
{
if ($id || $wpttstStyleId)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
if ($wpttstStyleId == '17' || $wpttstStyleId == '21')
{
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 3;
$wpttstWidget['2'] = (string)$wpttstStyleId;
$wpttstWidget['3'] = 'light-background';
$wpttstWidget['4']['appearance'] = $wpttstAppearance;
$wpttstWidget['4']['navigation'] = $wpttstNavigation;
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=4&id=' . esc_attr($id) . '&selected&style_id=' . esc_attr($wpttstStyleId) . '&scss_set=light-background"</script>';
exit;
}
else
{
$wpttstWidget['current'] = 3;
$wpttstWidget['2'] = (string)$wpttstStyleId;
$wpttstWidget['4']['appearance'] = $wpttstAppearance;
$wpttstWidget['4']['navigation'] = $wpttstNavigation;
$wpttstWidget['saved'] = 2;
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
}
else
{
if ($id)
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=2&id=' . esc_attr($id) . '"</script>';
exit;
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
}
else if ($wpttstCommand == 'save-set')
{
if ($id || $wpttstStyleId || $wpttstScssSet)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
if ($wpttstWidget['saved'] < 4)
{
$wpttstWidget['4']['appearance'] = $wpttstAppearance;
$wpttstWidget['4']['navigation'] = $wpttstNavigation;
}
$wpttstWidget['saved'] = 3;
$wpttstWidget['3'] = $wpttstScssSet;
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
if ($id)
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=3&id=' . esc_attr($id) . '"</script>';
exit;
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
}
else if ($wpttstCommand == 'save-order')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['general']['order'] = (isset($_POST['order']) ? sanitize_text_field(wp_unslash($_POST['order'])) : '');
$wpttstWidget['4']['last_saved'] = 'general';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-display-options')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['hide_stars'] = (isset($_POST['hide_stars']) ? sanitize_text_field(wp_unslash($_POST['hide_stars'])) : '');
$wpttstWidget['4']['appearance']['hide_image'] = (isset($_POST['hide_image']) ? sanitize_text_field(wp_unslash($_POST['hide_image'])) : '');
$wpttstWidget['4']['appearance']['auto_height'] = (isset($_POST['auto_height']) ? sanitize_text_field(wp_unslash($_POST['auto_height'])) : '');
$wpttstWidget['4']['appearance']['enable-font'] = (isset($_POST['enable-font']) ? sanitize_text_field(wp_unslash($_POST['enable-font'])) : '');
$wpttstWidget['4']['appearance']['hover-anim'] = (isset($_POST['hover-anim']) ? sanitize_text_field(wp_unslash($_POST['hover-anim'])) : '');
$wpttstWidget['4']['appearance']['nav'] = (isset($_POST['nav']) ? sanitize_text_field(wp_unslash($_POST['nav'])) : '');
$wpttstWidget['4']['appearance']['dots'] = (isset($_POST['dots']) ? sanitize_text_field(wp_unslash($_POST['dots'])) : '');
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-box')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['box-background-color'] = (isset($_POST['box-background-color']) ? sanitize_hex_color(wp_unslash($_POST['box-background-color'])) : null);
$wpttstWidget['4']['appearance']['box-border-color'] = (isset($_POST['box-border-color']) ? sanitize_hex_color(wp_unslash($_POST['box-border-color'])) : null);
$wpttstWidget['4']['appearance']['box-padding'] = (isset($_POST['box-padding']) ? sanitize_text_field(wp_unslash($_POST['box-padding'])) : '');
$wpttstWidget['4']['appearance']['box-border-top-width'] = (isset($_POST['box-border-width']) ? sanitize_text_field(wp_unslash($_POST['box-border-width'])) : '');
$wpttstWidget['4']['appearance']['box-border-left-width'] = (isset($_POST['box-border-width']) ? sanitize_text_field(wp_unslash($_POST['box-border-width'])) : '');
$wpttstWidget['4']['appearance']['box-border-right-width'] = (isset($_POST['box-border-width']) ? sanitize_text_field(wp_unslash($_POST['box-border-width'])) : '');
$wpttstWidget['4']['appearance']['box-border-bottom-width'] = (isset($_POST['box-border-width']) ? sanitize_text_field(wp_unslash($_POST['box-border-width'])) : '');
$wpttstWidget['4']['appearance']['box-border-radius'] = (isset($_POST['box-border-radius']) ? sanitize_text_field(wp_unslash($_POST['box-border-radius'])) : '');
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-font')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['profile-font-size'] = (isset($_POST['profile-font-size']) ? sanitize_text_field(wp_unslash($_POST['profile-font-size'])) : '');
$wpttstWidget['4']['appearance']['profile-color'] = (isset($_POST['profile-color']) ? sanitize_hex_color(wp_unslash($_POST['profile-color'])) : null);
$wpttstWidget['4']['appearance']['review-font-size'] = (isset($_POST['review-font-size']) ? sanitize_text_field(wp_unslash($_POST['review-font-size'])) : '');
$wpttstWidget['4']['appearance']['text-color'] = (isset($_POST['text-color']) ? sanitize_hex_color(wp_unslash($_POST['text-color'])) : null);
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-slider-interval')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['navigation']['slider_interval'] = (isset($_POST['slider-interval']) ? sanitize_text_field(wp_unslash($_POST['slider-interval'])) : '');
$wpttstWidget['4']['last_saved'] = 'navigation';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-review-lines')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['review-lines'] = (isset($_POST['review-lines']) ? sanitize_text_field(wp_unslash($_POST['review-lines'])) : '');
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-display_type')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['display_type'] = (isset($_POST['display_type']) ? sanitize_text_field(wp_unslash($_POST['display_type'])) : '');
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-text-align')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['text-align'] = (isset($_POST['text_align']) ? sanitize_text_field(wp_unslash($_POST['text_align'])) : '');
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-arrow')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['nav'] = (isset($_POST['arrow']) ? sanitize_text_field(wp_unslash($_POST['arrow'])) : '');
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-dots')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['dots'] = (isset($_POST['dots']) ? sanitize_text_field(wp_unslash($_POST['dots'])) : '');
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-dateformat')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['appearance']['date_format'] = (isset($_POST['date_format']) ? sanitize_text_field(wp_unslash($_POST['date_format'])) : '');
$wpttstWidget['4']['last_saved'] = 'appearance';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'save-navigation')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 4;
$wpttstWidget['saved'] = 4;
$wpttstWidget['4']['navigation']['navigation_style'] = (isset($_POST['navigation_style']) ? sanitize_text_field(wp_unslash($_POST['navigation_style'])) : '');
$wpttstWidget['4']['last_saved'] = 'navigation';
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($wpttstCommand == 'setup-widget')
{
if ($id)
{
$wpttstW = $wpttstPluginManager->getWidget($id);
$wpttstWidget = unserialize($wpttstW->value);
$wpttstWidgetName = $wpttstW->name;
$wpttstWidget['current'] = 5;
$wpttstWidget['saved'] = 5;
$wpttstSerializedWidget = serialize($wpttstWidget);
$wpttstPluginManager->saveCss($id, $wpttstWidget);
$wpttstPluginManager->updateWidget($id, $wpttstWidgetName, $wpttstSerializedWidget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}