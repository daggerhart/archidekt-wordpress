<?php
/**
 * @var array $message_groups
 */
?>
<?php foreach ($message_groups as $type => $messages) { ?>
	<?php foreach ($messages as $message) { ?>
		<div class='notice <?= esc_attr($type) ?> settings-error is-dismissible'>
			<p><strong><?= $message ?></strong></p>
		</div>
	<?php } ?>
<?php } ?>
