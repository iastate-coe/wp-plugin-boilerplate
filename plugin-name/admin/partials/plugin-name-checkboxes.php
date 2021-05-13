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
if (empty($args))
{
	$args = [];
}

$options = shortcode_atts(
	[
		'group' => Plugin_Name::get_plugin_name(),
		'name' => '',
		'screen_reader' => '',
		'description' => '',
		'options' => [],
	],
	$args
);
$option = get_option($options['group'] . '_' . $options['name']);
$option_name = $options['group'] . '_' . $options['name'] . '[]';
?>
<fieldset>
	<legend class="screen-reader-text">
		<span><?php echo esc_html($options['screen_reader']); ?></span>
	</legend>
	<?php
	foreach ($options['options'] as $key => $value) :
		$option_id = $options['group'] . '_' . $options['name'] . '_' . $key;
		?>
		<label for="<?php echo esc_attr($option_id); ?>">
			<input type="checkbox" name="<?php echo esc_attr($option_name); ?>" id="<?php echo esc_attr($option_id); ?>" value="<?php echo esc_attr($key); ?>" <?php checked(true, in_array($key, $option, true)); ?>>
			<?php echo esc_html(ucfirst($key)); ?>
		</label>
		<br>
	<?php endforeach; ?>
	<p class="description"><?php echo esc_html($options['description']); ?></p>
</fieldset>
