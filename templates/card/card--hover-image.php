<?php
/**
 * @var \Archidekt\Model\Archidekt\CardDeckMeta $card_deck_meta
 */
?>
<span class="archidekt-hover-image">
	<span class="card-name"><?= $card_deck_meta->getCardGameplay()->name ?></span>
	<span class="card-image">
		<img src="<?= $card_deck_meta->getCardPrinting()->getScryfallImageUri() ?>" alt="<?= $card_deck_meta->label ?>">
	</span>
</span>
