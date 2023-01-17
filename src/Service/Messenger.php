<?php

namespace Archidekt\Service;

/**
 * Messenger service.
 */
class Messenger {

	/**
	 * @var Cache
	 */
	private Cache $cache;

	/**
	 * @param Cache $cache
	 */
	public function __construct(Cache $cache) {
		$this->cache = $cache;
	}

	/**
	 * Get settings messages.
	 *
	 * @return array
	 */
	public function getMessages(): array {
		return $this->cache->getCache('messages') ?: [];
	}

	/**
	 * Add a settings message.
	 *
	 * @param string $message
	 * @param string $type
	 *
	 * @return void
	 */
	public function addMessage(string $message, string $type = 'updated') {
		$messages = $this->getMessages();
		$messages[$type][] = $message;
		$this->cache->setCache('messages', $messages, 30);
	}

	/**
	 * Delete settings messages.
	 *
	 * @return void
	 */
	public function deleteMessages() {
		$this->cache->deleteCache('messages');
	}

}
