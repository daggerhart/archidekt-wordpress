<?php
/**
 * @var \Archidekt\Model\Archidekt\Deck $deck
 * @var int|string $deck_id
 * @var string $view_mode
 */
?>
<div class="archidekt-deck mode-<?= esc_attr($view_mode) ?>">
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
<?php foreach ($deck->getCategoriesWithCards() as $category) { ?>
	<div class="archidekt-category">
		<div class="category-name"><?= $category->name ?></div>
		<ul class="category-cards">
			<?php foreach ($category->getCards() as $card) { ?>
				<li><?= $card->getCardGameplay()->name ?></li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>
