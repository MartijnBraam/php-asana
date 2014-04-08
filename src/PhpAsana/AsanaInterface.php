<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/7/14
 * Time: 8:45 PM
 */
namespace PhpAsana;

interface AsanaInterface {
  public function asanaRequest($method, $url, $payload = NULL);
}