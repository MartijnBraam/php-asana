<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/4/14
 * Time: 12:44 PM
 */

namespace PhpAsana;


class WorkSpace {
  private $id;
  private $name;
  private $isBusiness;

  public function __construct($meta){
    $this->id = $meta['id'];
    $this->name = $meta['name'];
    $this->isBusiness = $meta['is_business'];
  }
} 