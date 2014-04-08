<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/7/14
 * Time: 8:44 PM
 */

class AsanaTester implements \PhpAsana\AsanaInterface {
  public $requests;

  public function asanaRequest($method, $url, $payload = NULL) {
    $this->requests[] = $method . ':' . $url;
  }
}