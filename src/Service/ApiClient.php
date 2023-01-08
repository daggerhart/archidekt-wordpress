<?php

namespace Archidekt\Service;

class ApiClient {

  /**
   * @param int $deck_id
   *
   * @return false|mixed
   */
  public function get(int $deck_id) {
    $response = wp_remote_get("https://archidekt.com/api/decks/{$deck_id}/", [
      'headers' => [
        'content-type' => 'application/json',
      ],
    ]);
    
    if (isset($response['response']['code']) && $response['response']['code'] === 200) {
      return \json_decode($response['body'], TRUE);
    }
    
    return FALSE;
  }
  
}