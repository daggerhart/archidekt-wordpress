<?php
/**
 * @var array $field
 */
?>
<select id="<?= $field['id'] ?>" name="archidekt_settings[<?= $field['id'] ?>]">
	<?php foreach ($field['options'] as $option_value => $option_label) { ?>
		<option value="<?= $option_value ?>" <?php selected($option_value, $field['value']) ?>>
			<?= $option_label ?>
		</option>
	<?php } ?>
</select>

<?php if (!empty($field['description'])) { ?>
	<p class="description"><?= $field['description'] ?></p>
<?php } ?>
