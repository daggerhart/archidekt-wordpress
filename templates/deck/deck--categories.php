<?php
/**
 * @var \Archidekt\Model\Archidekt\Deck $deck
 * @var int|string $deck_id
 * @var string $view_mode
 * @var array $categories_with_linked_cards
 */
?>
<div class="archidekt-deck mode-<?= esc_attr($view_mode) ?>">
	<div class="summary">
		<div class="featured-image">
			<img src="<?= $deck->featured ?>" alt="<?= esc_attr($deck->name) ?> featured image">
		</div>
		<div class="details">
			<div class="name"><a href="<?= esc_url($deck->getUrl()) ?>"><?= $deck->name ?></a></div>
			<div class="format"><?= $deck->getFormatName() ?></div>
			<div class="owner"><span>by</span> <span class="owner-name"><?= $deck->getOwner()->username ?></span></div>
			<div class="card-count">Cards: <?= $deck->getCardCount() ?></div>
			<div class="view-count">Views: <?= $deck->viewCount ?></div>
			<div class="last-updated">Last Updated: <?= $deck->getDateUpdated()->format('Y/m/d') ?></div>
			<div class="cost">Price: $<?= $deck->getDeckPrice() ?></div>
			<div class="salt-sum">Salt Sum: <?= $deck->getSaltSum() ?></div>
		</div>
	</div>
	<div class="deck-categories">
		<?php foreach ($categories_with_linked_cards as $category_with_cards) { ?>
			<?= $category_with_cards ?>
		<?php } ?>
	</div>
</div>

