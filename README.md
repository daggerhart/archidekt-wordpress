# Archidekt for WordPress

A plugin for embedding Archidekt decks and data into a WordPress site.

## Shortcodes

* `[deck id="3181074"]` - Embed a deck summary on a WordPress page.

## Templates

This plugin's templates can be overridden in the current theme by playing the file in one of these locations:

* `themes/<your theme>/` - Root of theme.
* `themes/<your theme>/templates` - Templates folder.
* `themes/<your theme>/templates/archidekt` - Archidekt specific template folder.

All templates must be organized as the plugin organizes them. For example, if you want to override the `deck--summary.php` template, you must place it inside of a folder named `deck` so that its file path is one of these options:

* `themes/<your theme>/deck/deck--summary.php`
* `themes/<your theme>/templates/deck/deck--summary.php`
* `themes/<your theme>/templates/archidekt/deck/deck--summary.php`

## Card Object Data

Card objects contain a hierarchy of objects for various types of data.

* `CardDeckMeta` - Contains information about the card in the Archidekt deck such as number of this card printing in the deck. Is the parent of a `CardPrinting` object.
	* `CardPrinting` - Contains information about the card printing such as set and flavor text. Is the parent of a `CardGameplay` object.
		* `CardGameplay` - Contains information about the card relative to gameplay such as power, toughness, card types, etc. Is the parent of 1+ `CardFace` objects.
			* `CardFace` - Contains gameplay information about one of the faces of the card. Most cards have 1 face, but some cards such as werewolves and MDFC will have two or more faces.  
