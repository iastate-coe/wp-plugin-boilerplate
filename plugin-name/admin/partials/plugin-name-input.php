<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://iastate.edu
 * @since      1.0.0
 *
 * @package    Automated_Page_Redirect
 * @subpackage admin/partials
 *
 */

/** Should contain an array sent from {@link load_template()}. */
if ( empty( $args ) ) {
	$args = array();
}

$options      = shortcode_atts(
	array(
		'name'          => '',
		'label'         => '',
		'input_label'   => '',
		'screen_reader' => '',
		'description'   => '',
		'type'          => 'text',
		'input_class'   => 'regular-text',
	),
	$args
);
$option_id    = Plugin_Name::get_plugin_name() . '_' . $options['name'];
$option_value = ( Plugin_Name_Options::get_instance() )->get( $options['name'] );
?>
<fieldset>
	<legend class="screen-reader-text">
		<span><?php echo esc_html( $options['screen_reader'] ); ?></span>
	</legend>
	<label for="<?php echo esc_attr( $option_id ); ?>">
		<?php esc_html( $options['label'] ); ?>
	</label>
	<input type="<?php echo esc_attr( $options['type'] ); ?>" class="<?php echo esc_attr( $options['input_class'] ); ?>"
				 name="<?php echo esc_attr( $option_id ); ?>" id="<?php echo esc_attr( $option_id ); ?>"
				 value="<?php echo esc_attr( $option_value ); ?>"/>
	<?php
	if ( $options['input_label'] ) {
		echo esc_html( $options['input_label'] );
	}
	?>
	<?php if ( $options['description'] ) : ?>
		<p class="description"><?php echo esc_html( $options['description'] ); ?></p>
	<?php endif; ?>
</fieldset>
