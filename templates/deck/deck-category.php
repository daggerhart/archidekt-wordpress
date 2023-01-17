<?php
/**
 * @var \Archidekt\Shortcode\Deck $deck
 * @var string|int $deck_id
 * @var \Archidekt\Model\Archidekt\Category $category
 * @var string $content
 * @var array $linked_cards
 */
?>
<div class="archidekt-deck-category">
	<div class="category-name"><?= $category->name ?></div>
	<div class="archidekt-category">
		<ul class="category-cards">
			<li><?= implode("</li><li>", $linked_cards) ?></li>
		</ul>
		<?php if ($content) { ?>
			<div class="content">
				<?= $content ?>
			</div>
		<?php } ?>
	</div>
</div>
