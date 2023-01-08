<?php
/**
 * @var array $field
 */
?>
<input type="hidden" value="0" name="archidekt_settings[<?= $field['id'] ?>]">
<input
	id="<?= $field['id'] ?>"
	name="archidekt_settings[<?= $field['id'] ?>]"
	type="checkbox"
	value="1"
	<?php checked($field['value']) ?>
>
<?php if (!empty($field['description'])) { ?>
	<p class="description"><?= $field['description'] ?></p>
<?php } ?>
