<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/6/14
 * Time: 7:15 PM
 */

namespace PhpAsana;


class User {
  public $id;
  public $name;
  public $email;

  private $asanaconnection;

  public function __construct(Array $meta, AsanaInterface $asanaconnection){
    $this->id = $meta['id'];
    $this->name = $meta['name'];
    $this->email = $meta['email'];

    $this->asanaconnection = $asanaconnection;
  }
} 