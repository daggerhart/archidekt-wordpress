<?php
/**
 * @var array $field
 */
?>
<input type="hidden" value="0" name="archidekt_settings[<?= esc_attr($field['id']) ?>]">
<input
	id="<?= esc_attr($field['id']) ?>"
	name="archidekt_settings[<?= esc_attr($field['id']) ?>]"
	type="checkbox"
	value="1"
	<?php checked($field['value']) ?>
>
<?php if (!empty($field['description'])) { ?>
	<p class="description"><?= esc_html($field['description']) ?></p>
<?php } ?>
