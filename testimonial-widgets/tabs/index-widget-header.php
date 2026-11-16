<?php
defined('ABSPATH') or die('No script kiddies please!');
$search = null;
if(isset($_GET['search']))
{
$search = sanitize_text_field(wp_unslash($_GET['search']));
}
if(isset($_GET['order_by']) && isset($_GET['order']))
{
$wpttstOrderBy = sanitize_text_field(wp_unslash($_GET['order_by']));
$order = sanitize_text_field(wp_unslash($_GET['order']));
if (($wpttstOrderBy == 'id' || $wpttstOrderBy == 'name') && ($order == 'asc' || $order == 'desc'))
{
$wpttstWidgets = $wpttstPluginManager->getWidgets($wpttstOrderBy, $order, $search);
$wpttstOrderSetting = array(
'id' => array(
'active' => $wpttstOrderBy == 'id' ? true : false,
'order' => ($wpttstOrderBy == 'id' && $order == 'asc') ? 'desc' : 'asc',
),
'name' => array(
'active' => $wpttstOrderBy == 'name' ? true : false,
'order' => ($wpttstOrderBy == 'name' && $order == 'asc') ? 'desc' : 'asc',
)
);
}
else
{
}
}
else
{
$wpttstWidgets = $wpttstPluginManager->getWidgets('id', 'desc', $search);
$wpttstOrderSetting = array(
'id' => array(
'active' => true,
'order' => 'asc',
),
'name' => array(
'active' => false,
'order' => 'asc',
)
);
}
if(isset($_GET['delete_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['delete_nonce'])), 'delete_widget') && is_user_logged_in() && isset($_GET['delete_widget']))
{
$id = sanitize_text_field(wp_unslash($_GET['delete_widget']));
$wpttstSuccess = $wpttstPluginManager->deleteWidget($id);
if ($wpttstSuccess)
{
echo '<script>location.href="admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php"</script>';
exit;
}
else
{
}
}
if(isset($_GET['duplicate_widget']))
{
$id = sanitize_text_field(wp_unslash($_GET['duplicate_widget']));
$wpttstSuccess = $wpttstPluginManager->duplicateWidget($id);
if ($wpttstSuccess)
{
echo '<script>location.href="admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php"</script>';
exit;
}
else
{
}
}