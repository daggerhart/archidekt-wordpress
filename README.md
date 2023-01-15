# Archidekt for WordPress

A plugin for embedding Archidekt decks and data into a WordPress site.

## Shortcodes

* `[deck id="3181074"]` - Embed a deck summary on a WordPress page.

## Card Object Data

Card objects contain a hierarchy of objects for various types of data.

* `CardDeckMeta` - Contains information about the card in the Archidekt deck such as number of this card printing in the deck. Is the parent of a `CardPrinting` object.
* `CardPrinting` - Contains information about the card printing such as set and flavor text. Is the parent of a `CardGameplay` object.
* `CardGameplay` - Contains information about the card relative to gameplay such as power, toughness, card types, etc. Is the parent of 1+ `CardFace` objects.
* `CardFace` - Contains gameplay information about one of the faces of the card. Most cards have 1 face, but some cards such as werewolves and MDFC will have two or more faces.  
