<?php
/**
 * @var \Archidekt\Shortcode\Deck $deck
 * @var string|int $deck_id
 * @var \Archidekt\Model\Archidekt\Category $category
 * @var string $content
 */
if (!$category) {
	return "<!-- Category not found: -->";
}
?>
<div class="archidekt-deck-category">
	<div class="category-name"><?= $category->name ?></div>
	<div class="archidekt-category">
		<ul class="category-cards">
			<?php foreach ($category->getCards() as $card) { ?>
				<li><?= $card->getCardGameplay()->name ?></li>
			<?php } ?>
		</ul>
		<?php if ($content) { ?>
			<div class="content">
				<?= $content ?>
			</div>
		<?php } ?>
	</div>
</div>
