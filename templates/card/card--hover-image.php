<?php
/**
 * @var \Archidekt\Model\Archidekt\CardDeckMeta $card_deck_meta
 */
?>
<span class="archidekt-hover-image">
	<a class="card-name" href="<?= esc_attr($card_deck_meta->getCardPrinting()->scryfall_uri) ?>" target="_blank"><?= $card_deck_meta->getCardGameplay()->name ?></a>
	<span class="card-image">
		<img src="<?= esc_attr($card_deck_meta->getCardPrinting()->getScryfallImageUri()) ?>" alt="<?= esc_attr($card_deck_meta->getCardGameplay()->name) ?>">
	</span>
</span>
