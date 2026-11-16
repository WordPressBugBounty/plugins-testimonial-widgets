<?php
defined('ABSPATH') or die('No script kiddies please!');
function wpttstStarRatingForm( $field, $value, $class, $echo = true, $fieldArray = '' ) {
 $value = (int) $value;
if ( $field && is_array( $field ) && isset( $field['name'] ) ) {
$name = $field['name'];
if ( $fieldArray ) {
 $name = $fieldArray . '[' . $name . ']';
}
} else {
$name = 'rating';
}
ob_start(); ?>
<div class="strong-rating-wrapper field-wrap <?php echo esc_attr( $class ); ?>"><!-- cheap trick to collapse whitespace around inline-blocks
--><fieldset contenteditable=false
 id="wpttst_<?php echo esc_attr( $field['name'] ); ?>"
 name="<?php echo esc_attr( $field['name'] ); ?>"
 class="strong-rating"
 data-field-type="rating"
 tabindex="0">
 <legend><?php esc_html_e('rating fields', 'testimonial-widgets' ) ?></legend><!--
--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star1" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $value, 1 ); ?> /><!--
--><label for="<?php echo esc_attr( $field['name'] ); ?>-star1" title="1 star"></label><!--
--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star2" name="<?php echo esc_attr( $name ); ?>" value="2" <?php checked( $value, 2 ); ?> /><!--
--><label for="<?php echo esc_attr( $field['name'] ); ?>-star2" title="2 stars"></label><!--
--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star3" name="<?php echo esc_attr( $name ); ?>" value="3" <?php checked( $value, 3 ); ?> /><!--
--><label for="<?php echo esc_attr( $field['name'] ); ?>-star3" title="3 stars"></label><!--
--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star4" name="<?php echo esc_attr( $name ); ?>" value="4" <?php checked( $value, 4 ); ?> /><!--
--><label for="<?php echo esc_attr( $field['name'] ); ?>-star4" title="4 stars"></label><!--
--><input type="radio" id="<?php echo esc_attr( $field['name'] ); ?>-star5" name="<?php echo esc_attr( $name ); ?>" value="5" <?php checked( $value, 5 ); ?> /><!--
--><label for="<?php echo esc_attr( $field['name'] ); ?>-star5" title="5 stars"></label><!--
--></fieldset><!--
--></div>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = preg_replace( '/<!--(.|\s)*?-->/', '', $html );
if ( $echo ) {
$allowedHtml = array(
'div' => array(
'class' => array(),
),
'fieldset' => array(
'contenteditable' => array(),
'id' => array(),
'name' => array(),
'class' => array(),
'data-field-type' => array(),
'tabindex' => array(),
),
'input' => array(
'type' => array(),
'id' => array(),
'name' => array(),
'value' => array(),
'checked' => array(),
),
'label' => array(
'for' => array(),
'title' => array(),
),
'legend' => array(
'value' => array(),
)
);
echo wp_kses($html, $allowedHtml);
return true;
}
 return $html;
}
function wpttstStarRatingDisplay( $value = 0, $class = 'in-view', $echo = true ) {
 $value = (int) $value;
ob_start(); ?>
<span class="strong-rating-wrapper <?php echo esc_attr( $class ); ?>">
<span class="strong-rating"><!-- cheap trick to collapse whitespace around inline-blocks
--><span class="star<?php echo ( 0 == $value ) ? ' current' : '' ; ?> nodisplay" ></span><!--
--><span class="star<?php echo ( 1 == $value ) ? ' current' : '' ; ?>"></span><!--
--><span class="star<?php echo ( 2 == $value ) ? ' current' : '' ; ?>"></span><!--
--><span class="star<?php echo ( 3 == $value ) ? ' current' : '' ; ?>"></span><!--
--><span class="star<?php echo ( 4 == $value ) ? ' current' : '' ; ?>"></span><!--
--><span class="star<?php echo ( 5 == $value ) ? ' current' : '' ; ?>"></span><!--
--></span>
</span>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = preg_replace( '/<!--(.|\s)*?-->/', '', $html );
if ( $echo ) {
$allowedHtml = array(
'span' => array(
'class' => array()
 ),
);
 echo wp_kses($html, $allowedHtml);
 return true;
}
 return $html;
}
