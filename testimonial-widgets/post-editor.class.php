<?php
defined('ABSPATH') or die('No script kiddies please!');
class TrustindexTestimonialsPostEditor {

public function __construct() {
}

public static function init() {
self::addActions();
}

public static function addActions() {
add_action( 'add_meta_boxes_wpt-testimonial', array( __CLASS__, 'addMetaBoxes' ) );
add_filter('hidden_meta_boxes', array( __CLASS__, 'hideComment' ), 10, 2);
add_action( 'save_post_wpt-testimonial', array( __CLASS__, 'saveDetails' ) );
add_action( 'wp_ajax_wpttst_edit_rating', array( __CLASS__, 'editRating' ) );
add_filter( 'wp_insert_post_data', array( __CLASS__, 'preventShortcode' ), 10, 2 );
}

public static function hideComment($hidden, $screen) {
$postType= $screen->id;
if ($postType == 'wpt-testimonial')
{
$hidden[]= 'commentstatusdiv';
$hidden[]= 'commentsdiv';
}
return $hidden;
}

public static function addMetaBoxes() {
add_meta_box(
'details',
esc_html_x( 'Client Details', 'post editor', 'testimonial-widgets' ),
array( __CLASS__, 'metaOptions' ),
'wpt-testimonial',
'normal',
'high'
);
}

public static function metaOptions() {
global $post, $pagenow;
$post = wpttstGetPost( $post );
$fields = wpttstGetCustomFields();
$isNew = ( 'post-new.php' == $pagenow );
 wp_nonce_field ( plugin_basename(__FILE__), 'wpttst_metabox_nonce');
?>
<?php do_action( 'wpttst_before_client_fields_table' ); ?>
 <table class="options">
 <tr>
 <td colspan="2">
 <p><?php esc_html_x( 'To add a photo or logo, use the Reviewer\'s photo option.', 'post editor', 'testimonial-widgets' ); ?></p>
 </td>
 </tr>
<?php
do_action( 'wpttst_before_client_fields' );
foreach ( $fields as $key => $field ) {
if ( 'category' == strtok( $field['input_type'], '-' ) ) {
continue;
}
?>
 <tr>
 <th>
 <label for="<?php echo esc_attr( $field['name'] ); ?>">
<?php echo esc_html( apply_filters( 'wpttst_l10n', $field['label'], 'testimonial-widgets-form-fields', $field['name'] . ' : label' ) ); ?>
 </label>
 </th>
 <td>
 <div class="<?php echo esc_attr( $field['input_type'] ); ?>">
<?php self::metaOption( $field, $post, $isNew ); ?>
 </div>
 </td>
 </tr>
<?php
}
do_action( 'wpttst_after_client_fields' );
?>
 </table>
<?php
do_action( 'wpttst_after_client_fields_table' );
}

public static function metaOption( $field, $post, $isNew ) {
if ( isset( $field['action_input'] ) && $field['action_input'] ) {
self::metaOptionAction( $field, $post, $isNew );
}
else {
switch ( $field['input_type'] ) {
case 'rating' :
self::metaOptionRating( $field, $post, $isNew );
break;
case 'url' :
self::metaOptionUrl( $field, $post, $isNew );
break;
case 'checkbox' :
self::metaOptionCheckbox( $field, $post, $isNew );
break;
case 'shortcode' :
self::metaOptionShortcode( $field, $post, $isNew );
break;
case 'textarea' :
self::metaOptionTextarea( $field, $post, $isNew );
break;
default :
self::metaOptionText( $field, $post, $isNew );
}
}
}

private static function metaOptionAction( $field, $post, $isNew ) {
if ( isset( $field['action_input'] ) && $field['action_input'] ) {
do_action( $field['action_input'], $field, $post->{$field['name']} );
}
}

private static function metaOptionText( $field, $post, $isNew ) {
printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s">', esc_attr( $field['input_type'] ), esc_attr( $field['name'] ), esc_attr( $post->{$field['name']} ) );
}

private static function metaOptionTextarea( $field, $post, $isNew ) {
printf(
'<textarea id="%1$s" name="custom[%1$s]" class="custom-input">%2$s</textarea>',
esc_attr( $field['name'] ),
wp_kses_post( $post->{$field['name']} )
);
}

private static function metaOptionUrl( $field, $post, $isNew ) {
?>
 <div class="input-url">
<?php printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" size="">', esc_attr( $field['input_type'] ), esc_html( $field['name'] ), esc_attr( $post->{$field['name']} ) ); ?>
 </div>
 <div class="input-links">
 <div class="input-nofollow">
 <label for="custom_nofollow"><code>rel="nofollow"</code></label>
 <select id="custom_nofollow" name="custom[nofollow]">
 <option value="default" <?php selected( $post->nofollow, 'default' ); ?>><?php esc_html_e( 'default', 'testimonial-widgets' ); ?></option>
 <option value="yes" <?php selected( $post->nofollow, 'yes' ); ?>><?php esc_html_e( 'yes', 'testimonial-widgets' ); ?></option>
 <option value="no" <?php selected( $post->nofollow, 'no' ); ?>><?php esc_html_e( 'no', 'testimonial-widgets' ); ?></option>
 </select>
 </div>
 <div class="input-noopener">
 <label for="custom_noopener"><code>rel="noopener"</code></label>
 <select id="custom_noopener" name="custom[noopener]">
 <option value="default" <?php selected( $post->noopener, 'default' ); ?>><?php esc_html_e( 'default', 'testimonial-widgets' ); ?></option>
 <option value="yes" <?php selected( $post->noopener, 'yes' ); ?>><?php esc_html_e( 'yes', 'testimonial-widgets' ); ?></option>
 <option value="no" <?php selected( $post->noopener, 'no' ); ?>><?php esc_html_e( 'no', 'testimonial-widgets' ); ?></option>
 </select>
 </div>
 <div class="input-noreferrer">
 <label for="custom_noreferrer"><code>rel="noreferrer"</code></label>
 <select id="custom_noopener" name="custom[noreferrer]">
 <option value="default" <?php selected( $post->noreferrer, 'default' ); ?>><?php esc_html_e( 'default', 'testimonial-widgets' ); ?></option>
 <option value="yes" <?php selected( $post->noreferrer, 'yes' ); ?>><?php esc_html_e( 'yes', 'testimonial-widgets' ); ?></option>
 <option value="no" <?php selected( $post->noreferrer, 'no' ); ?>><?php esc_html_e( 'no', 'testimonial-widgets' ); ?></option>
 </select>
 </div>
<?php
}

private static function metaOptionCheckbox( $field, $post, $isNew ) {
printf( '<input id="%2$s" type="%1$s" class="custom-input" name="custom[%2$s]" value="%3$s" %4$s>', esc_attr( $field['input_type'] ), esc_attr( $field['name'] ), 1, checked( $post->{$field['name']}, 1, false ) );
}

private static function metaOptionRating( $field, $post, $isNew ) {
$rating = get_post_meta( $post->ID, $field['name'], true );
if ( ! $rating || $isNew ) {
$rating = 5;
}
?>
 <div class="edit-rating-box hide-if-no-js" data-field="<?php echo esc_attr( $field['name'] ); ?>">
<?php wp_nonce_field( 'editrating', "edit-{$field['name']}-nonce", false ); ?>
 <input type="hidden" class="current-rating" value="<?php echo esc_attr( $rating ); ?>">
 <!-- form -->
 <div class="rating-form">
 <span class="inner">
 <?php wpttstStarRatingForm( $field, $rating, 'in-metabox', true, 'custom' ); ?>
 </span>
 </div>
 </div>
<?php
}

public static function metaOptionShortcode( $field, $post, $isNew ) {
 $shortcode = str_replace( array( '[', ']' ), array( '', '' ), $field['shortcode_on_display'] );
 if ( shortcode_exists( $shortcode ) ) {
 echo do_shortcode( esc_attr($field['shortcode_on_display']) );
 } else {
 /* translators: %s: shortcode name */
 echo '<div class="custom-input not-found">' . sprintf( esc_html__( 'shortcode %s not found', 'testimonial-widgets' ), '<code>' . esc_html($field['shortcode_on_display']) . '</code>' ) . '</div>';
 }
}

public static function saveDetails() {
if ( ! isset( $_POST['custom'], $_POST['wpttst_metabox_nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpttst_metabox_nonce'] ) ), plugin_basename(__FILE__))) {
return;
}
$postId = isset( $_POST['post_ID'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['post_ID'] ) ) ) : 0;
if (is_array($_POST['custom']))
{
$custom = array_map( 'sanitize_text_field', wp_unslash( $_POST['custom'] ) );
}
else
{
$custom = null;
}
$customFields = wpttstGetCustomFields();
$checkboxes = array();
foreach ( $customFields as $key => $field ) {
if ( 'checkbox' == $field['input_type'] ) {
$checkboxes[ $key ] = 0;
}
}
if ( $checkboxes ) {
$custom = array_merge( $checkboxes, $custom );
}
$customFields['nofollow']['input_type'] = '';
$customFields['noopener']['input_type'] = '';
 $customFields['noreferrer']['input_type'] = '';
 
foreach ( $custom as $key => $value ) {
 $action = 'update';
 $sanitizedValue = '';
 if ( isset( $customFields[ $key ] ) ) {
if ( 'rating' == $customFields[ $key ]['input_type'] && !$value ) {
$action = 'delete';
}
}
if ( isset($customFields[ $key ]['input_type']) && 'text' == $customFields[ $key ]['input_type'] ) {
$sanitizedValue = wp_kses_post( $value );
}elseif ( isset($customFields[ $key ]['input_type']) && 'email' == $customFields[ $key ]['input_type'] ) {
$sanitizedValue = sanitize_email( $value );
}elseif ( isset($customFields[ $key ]['input_type']) && 'url' == $customFields[ $key ]['input_type'] ) {
$sanitizedValue = esc_url_raw( $value );
}else{
$sanitizedValue = sanitize_text_field( $value );
}
if ( 'update' == $action ) {
update_post_meta( $postId, $key, wp_slash( $sanitizedValue ) );
}
else {
delete_post_meta( $postId, $key );
}
}
}

public static function preventShortcode( $data, $postarr ) {
 if ( 'wpt-testimonial' == $data['post_type'] ) {
 $data['post_content'] = preg_replace( "/\[testimonial_view (.*)\]/", '', $data['post_content'] );
 }
 return $data;
}

public static function editRating() {
check_ajax_referer( 'editrating', 'editratingnonce' );
$postId = isset( $_POST['post_id'] ) ? intval(sanitize_text_field(wp_unslash($_POST['post_id']))) : 0;
$rating = isset( $_POST['rating'] ) ? intval(sanitize_text_field(wp_unslash($_POST['rating']))) : 0;
$name = isset( $_POST['field_name'] ) ? sanitize_text_field(wp_unslash($_POST['field_name'])) : 'rating';
if ($rating == 0)
{
$rating = 5;
}
if ( $postId ) {
if ( $rating ) {
update_post_meta( $postId, $name, $rating );
} else {
delete_post_meta( $postId, $name );
}
}
}
}
TrustindexTestimonialsPostEditor::init();
