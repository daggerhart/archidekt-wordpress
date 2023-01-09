<?php
/**
 * @var \Archidekt\Model\Archidekt\Deck $deck
 */
?>
<div class="archidekt-deck mode-default">
	<div class="featured-image">
		<img src="<?= $deck->featured ?>" alt="<?= esc_attr($deck->name) ?> featured image">
	</div>
	<div class="details">
		<div class="name"><a href="<?= $deck->getUrl() ?>"><?= $deck->name ?></a></div>
		<div class="format"><?= $deck->getFormatName() ?></div>
		<div class="owner"><span>by</span> <span class="owner-name"><?= $deck->getOwner()->username ?></span></div>
		<div class="view-count">Views: <?= $deck->viewCount ?></div>
		<div class="last-updated">Last Updated: <?= $deck->getDateUpdated()->format('Y/m/d') ?></div>
		<div class="cost">Price: $<?= $deck->getDeckPrice() ?></div>
		<div class="salt-sum">Salt Sum: <?= $deck->getSaltSum() ?></div>
	</div>
</div>
