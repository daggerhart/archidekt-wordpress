<?php

namespace Archidekt\Model\Archidekt;

use Archidekt\Model\ApiObjectBase;
use Archidekt\Model\Archidekt\CardDeckMeta;

/**
 * @property int $id Deck Id.
 * @property string $name Deck name.
 * @property string $createdAt Created datetime stamp.
 * @property string $updatedAt Updated datetime stamp.
 * @property int $deckFormat Deck format id number.
 * @property string $description Description.
 * @property string $featured Featured image url.
 * @property string $customFeatured Customized featured image url.
 * @property null $game Game type Id.
 * @property bool $private Whether deck is private.
 * @property int $viewCount Number of times deck has been viewed.
 * @property int $points Number of likes the deck has.
 * @property int $userInput ???
 * @property int $commentRoot Comment root id.
 * @property int[] $editors Array of user Ids that can edit the deck.
 * @property int $parentFolder Id of the folder the deck is in.
 */
class Deck extends ApiObjectBase {

	/**
	 * @var CardDeckMeta[]
	 */
	private array $cardObjects = [];

	/**
	 * @var Category[]
	 */
	private array $categoryObjects = [];

	/**
	 * Hard coded in the order expected.
	 * @todo - Can this be dynamic?
	 */
	public const FORMATS = [
		'Unknown',
		'Standard',
		'Modern',
		'Commander / EDH',
		'Legacy',
		'Vintage',
		'Pauper',
		'Custom',
		'Frontier',
		'Future Standard',
		'Penny Dreadful',
		'1v1 Commander',
		'Duel Commander',
		'Brawl',
		'Oathbreaker',
		'Pioneer',
		'Historic',
		'Pauper EDH',
		'Alchemy',
		'Explorer',
		'Historic Brawl',
		'Gladiator',
		'Premodern',
	];

	/**
	 * Get the archidekt url for this deck.
	 *
	 * @return string
	 */
	public function getUrl(): string {
		return "https://archidekt.com/decks/{$this->id}";
	}

	/**
	 * @return \DateTime
	 * @throws \Exception
	 */
	public function getDateCreated(): \DateTime {
		return new \DateTime($this->createdAt);
	}

	/**
	 * @return \DateTime
	 * @throws \Exception
	 */
	public function getDateUpdated(): \DateTime {
		return new \DateTime($this->updatedAt);
	}

	/**
	 * Get deck format name.
	 *
	 * @return string
	 */
	public function getFormatName(): string {
		return static::FORMATS[$this->deckFormat] ?? static::FORMATS[0];
	}

	/**
	 * Get the deck owner object.
	 *
	 * @return Owner
	 */
	public function getOwner(): Owner {
		return new Owner($this->data['owner'] ?? []);
	}

	/**
	 * Get collection of cards in the deck.
	 *
	 * @return CardDeckMeta[]
	 */
	public function getCards(): array {
		if (empty($this->cardObjects)) {
			$this->cardObjects = array_map(function($card) {
				return new CardDeckMeta($card);
			}, $this->data['cards'] ?? []);
		}

		return $this->cardObjects;
	}

	/**
	 * Number of cards in the deck.
	 *
	 * @return int
	 */
	public function getCardCount(): int {
		return count($this->getCards());
	}

	/**
	 * Get category objects in this deck.
	 *
	 * @return Category[]
	 */
	public function getCategories(): array {
		if (empty($this->categoryObjects)) {
			$this->categoryObjects = array_map(function($card) {
				return new Category($card);
			}, $this->data['categories'] ?? []);

			// Sort Premier to top, and alphabetically by default.
			usort($this->categoryObjects, function($a, $b) {
				/**
				 * @var $a Category
				 * @var $b Category
				 */
				if ($a->isPremier) {
					return -1;
				}
				if ($b->isPremier) {
					return 1;
				}

				return $a->name <=> $b->name;
			});
		}

		return $this->categoryObjects;
	}

	/**
	 * Get categories populated with cards in the category.
	 *
	 * @return Category[]
	 */
	public function getCategoriesWithCards(bool $include_empty = false): array {
		$categories = $this->getCategories();
		$cards = $this->getCards();

		foreach ($categories as $category) {
			$category_cards = [];
			foreach ($cards as $card) {
				if (in_array($category->name, $card->categories)) {
					$category_cards[] = $card;
				}
			}
			$category->setCards($category_cards);
		}

		if (!$include_empty) {
			$categories = array_filter($categories, fn($category) => $category->hasCards());
		}

		return $categories;
	}

	/**
	 * Get all cards that are in categories that are themselves included in the deck.
	 *
	 * @return CardDeckMeta[]
	 */
	public function getCardsInDeck(): array {
		$categories = array_filter($this->getCategories(), function (Category $category) {
			return $category->includedInDeck;
		});

		return $this->filterCardsInCategories($this->getCards(), $categories);
	}

	/**
	 * Get all the cards that are in categories that are themselves included in the price.
	 *
	 * @return CardDeckMeta[]
	 */
	public function getCardsInPrice(): array {
		$categories = array_filter($this->getCategories(), function (Category $category) {
			return $category->includedInPrice;
		});

		return $this->filterCardsInCategories($this->getCards(), $categories);
	}

	/**
	 * @param string $source
	 *
	 * @return string
	 */
	public function getDeckPrice(string $source = 'tcg'): string {
		$price = array_reduce($this->getCardsInPrice(), function($carry, CardDeckMeta $card_wrapper) use ($source) {
			return $carry + $card_wrapper->getCardPrinting()->getPrice($source);
		}, 0);

		return number_format($price, 2);
	}

	/**
	 * @return string
	 */
	public function getSaltSum(): string {
		$salt = array_reduce($this->getCardsInDeck(), function($carry, CardDeckMeta $card_wrapper) {
			return $carry + $card_wrapper->getCardPrinting()->getCardGameplay()->salt;
		}, 0);

		return number_format($salt, 2);
	}

	/**
	 * Filter the given list of cards by cards that are only in the given list of categories.
	 *
	 * @param CardDeckMeta[] $cards
	 * @param Category[] $categories
	 *
	 * @return CardDeckMeta[]
	 */
	private function filterCardsInCategories(array $cards, array $categories): array {
		return array_filter($cards, function(CardDeckMeta $card_wrapper) use ($categories) {
			foreach ($categories as $category) {
				if (in_array($category->name, $card_wrapper->categories)) {
					return TRUE;
				}
			}

			return FALSE;
		});
	}

}
