<?php
defined('ABSPATH') or die('No script kiddies please!');

function wpttstGetPost( $post ) {
$custom = get_post_custom( $post->ID );
$fields = wpttstGetCustomFields();
foreach ( $fields as $key => $field ) {
$name = $field['name'];
if ( 'featured_image' == $name ) {
$post->thumbnail_id = get_post_thumbnail_id( $post->ID );
}
else {
if ( isset( $custom[ $name ] ) ) {
$post->$name = $custom[ $name ][0];
}
else {
$post->$name = '';
}
}
}
return $post;
}

function wpttstGetCustomFields() {
$allFields = array();
$forms = get_option( 'wpttst_custom_forms' );
if ( ! $forms ) {
return $allFields;
}
$fields = $forms[1]['fields'];
if ( ! $fields ) {
return $allFields;
}
foreach ( $fields as $field ) {
if ( 'post' != $field['record_type'] ) {
$allFields[ $field['name'] ] = $field;
}
}
foreach ( $forms as $form ) {
$customFields = array();
$fields = $form['fields'];
foreach ( $fields as $field ) {
if ( 'post' != $field['record_type'] ) {
$customFields[ $field['name'] ] = $field;
}
}
$allFields = array_merge( $allFields, $customFields );
}
return $allFields;
}
function wpttstGetThumbnail( $size = null ) {
if (! is_admin() ) {
return '';
}
$size = array(50,50);

$id = get_the_ID();
$img = '';
if ( has_post_thumbnail( $id ) ) {
$img = get_the_post_thumbnail( $id, $size );

} else {
}

return apply_filters( 'wpttst_thumbnail_img', $img, $id, $size );
}
function wpttstGetField( $field, $args = array() ) {
if ( ! $field ) {
return '';
}

global $post;

switch ( $field ) {
case 'truncated' :
$html = wpmtst_truncate( $post->post_content, $args['char_limit'] );
break;
default :
$html = get_post_meta( $post->ID, $field, true );

}

return $html;
}
function wpttstGetAllFields() {
$forms = get_option( 'wpttst_custom_forms' );
$allFields = array();


$fields = $forms[1]['fields'];
foreach ( $fields as $field ) {
$allFields[ $field['name'] ] = $field;
}
foreach ( $forms as $form ) {
$customFields = array();
$fields = $form['fields'];
foreach ( $fields as $field ) {
$customFields[ $field['name'] ] = $field;
}
$allFields = array_merge( $allFields, $customFields );
}

return $allFields;
}
?>