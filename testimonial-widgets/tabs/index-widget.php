<?php
defined('ABSPATH') or die('No script kiddies please!');
include(plugin_dir_path(__FILE__) . "index-widget-header.php");
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
<div class="ti-box">
<form id="posts-filter" method="get">
<div class="ti-box-head">
<div class="ti-row">
<div class="ti-col">
<span class="wp-heading-inline"><?php echo esc_html__('Create Widgets', 'testimonial-widgets'); ?></span>
</div>
</div>
<div class="ti-row">
<div class="ti-col">
<a href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/create-widget.php'; ?>" class="btn-text btn-refresh"><?php echo esc_html__('Add New', 'testimonial-widgets'); ?></a>
</div>
</div>
<br />
<div class="ti-row">
<div class="ti-col">
<a class="current" href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php'; ?>"><?php echo esc_html__('All', 'testimonial-widgets'); ?> <span class="count">(<?php echo esc_attr(count($wpttstWidgets)); ?>)</span></a>
</div>
<div class="ti-col">
<p class="search-box">
<label class="screen-reader-text" for="post-search-input"><?php echo esc_html__('Search', 'testimonial-widgets'); ?></label>
<input type="search" id="post-search-input" name="search" value="">
<input type="submit" id="search-submit" class="button" value="Search">
<input type="hidden" name="page" value="testimonial-widgets/tabs/index-widget.php">
</p>
</div>
</div>
</div>
<div class="ti-box-head">
<div class="ti-row">
<table class="wp-list-table widefat fixed striped wpm-testimonial_page_testimonial-views">
<thead>
<tr>
<th scope="col" id="id" class="manage-column column-id <?php echo $wpttstOrderSetting['id']['active'] ? ($wpttstOrderSetting['id']['order'] == 'asc' ? 'sorted desc' : 'sorted asc') : ($wpttstOrderSetting['id']['order'] == 'asc' ? 'sortable asc' : 'sortable desc'); ?>"><a href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php&order_by=id&order=' . esc_attr($wpttstOrderSetting['id']['order']); ?>"><span><?php echo esc_html__('ID', 'testimonial-widgets'); ?></span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="name" class="manage-column column-name <?php echo $wpttstOrderSetting['name']['active'] ? ($wpttstOrderSetting['id']['order'] == 'asc' ? 'sorted desc' : 'sorted asc') : ($wpttstOrderSetting['id']['order'] == 'asc' ? 'sortable asc' : 'sortable desc'); ?>"><a href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php&order_by=name&order=' . esc_attr($wpttstOrderSetting['name']['order']); ?>"><span><?php echo esc_html__('Name', 'testimonial-widgets'); ?></span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="shortcode" class="manage-column column-shortcode"><?php echo esc_html__('Shortcode', 'testimonial-widgets'); ?></th>
</tr>
</thead>
<tbody id="the-list">
<?php foreach ($wpttstWidgets as $wpttstWidget) : ?>
<?php
$wpttstWidgetValue = unserialize($wpttstWidget->value);
$wpttstParams = '&id=' . $wpttstWidget->id . '&step=' . $wpttstWidgetValue['current'];
if ($wpttstWidgetValue['current'] > 1)
{
$wpttstParams .= '&selected';
}
if ($wpttstWidgetValue['current'] > 2)
{
$wpttstParams .= '&style_id=' . $wpttstWidgetValue['2'];
}
if ($wpttstWidgetValue['current'] > 3)
{
$wpttstParams .= '&command=save-set&_wpnonce=' . wp_create_nonce('ti-save-widget') . '&scss_set=' . $wpttstWidgetValue['3'];
}
if ($wpttstWidgetValue['current'] > 4)
{
$wpttstParams .= '&setup_widget';
}
?>
<tr>
<td class="id column-id"><?php echo esc_attr($wpttstWidget->id); ?></td>
<td class="name column-name"><a class="row-title" href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/create-widget.php' . esc_attr($wpttstParams); ?>"><?php echo esc_attr($wpttstWidget->name); ?></a>
<div class="row-actions">
<span class="edit">
<a href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/create-widget.php' . esc_attr($wpttstParams); ?>"><?php echo esc_html__('Edit', 'testimonial-widgets'); ?></a> |
</span>
<span class="duplicate">
<a href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php&duplicate_widget=' . esc_attr($wpttstWidget->id); ?>"><?php echo esc_html__('Duplicate', 'testimonial-widgets'); ?></a> |
</span>
<span class="delete">
<a class="submitdelete" href="<?php echo esc_url(wp_nonce_url('admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php&delete_widget=' . esc_attr($wpttstWidget->id), 'delete_widget', 'delete_nonce')); ?>" onclick="if ( confirm('<?php echo esc_js(__('Delete', 'testimonial-widgets') . ' ' . $wpttstWidget->name . '?'); ?>') ) { return true;} return false;"><?php echo esc_html__('Delete', 'testimonial-widgets'); ?></a>
</span>
</div>
</td>
<td class="shortcode column-shortcode">[wp-testimonials widget-id="<?php echo esc_attr($wpttstWidget->id); ?>"]</td>
</tr>
<?php endforeach; ?>
</tbody>
<tfoot>
<tr>
<th scope="col" id="id" class="manage-column column-id <?php echo $wpttstOrderSetting['id']['active'] ? ($wpttstOrderSetting['id']['order'] == 'asc' ? 'sorted desc' : 'sorted asc') : ($wpttstOrderSetting['id']['order'] == 'asc' ? 'sortable asc' : 'sortable desc'); ?>"><a href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php&order_by=id&order=' . esc_attr($wpttstOrderSetting['id']['order']); ?>"><span><?php echo esc_html__('ID', 'testimonial-widgets'); ?></span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="name" class="manage-column column-name <?php echo $wpttstOrderSetting['name']['active'] ? ($wpttstOrderSetting['id']['order'] == 'asc' ? 'sorted desc' : 'sorted asc') : ($wpttstOrderSetting['id']['order'] == 'asc' ? 'sortable asc' : 'sortable desc'); ?>"><a href="<?php echo 'admin.php?page=' . esc_attr($wpttstPluginManager->getPluginSlug()) . '/tabs/index-widget.php&order_by=name&order=' . esc_attr($wpttstOrderSetting['name']['order']); ?>"><span><?php echo esc_html__('Name', 'testimonial-widgets'); ?></span><span class="sorting-indicator"></span></a></th>
<th scope="col" id="shortcode" class="manage-column column-shortcode"><?php echo esc_html__('Shortcode', 'testimonial-widgets'); ?></th>
</tr>
</tfoot>
</table>
<div class="clear"></div>
</div>
</div>
</form>
</div>
</div>
</div>
</div>