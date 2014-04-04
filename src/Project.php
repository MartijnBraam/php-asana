<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/4/14
 * Time: 12:44 PM
 */

namespace PhpAsana;


class Project {
  public $id;
  public $name;
  private $loaded = false;
  private $notes = "";
  private $createdAt = null;
  private $modifiedAt = null;
  private $isPublic = false;
  private $isArchived = false;
  private $color = "";
  private $members = array();
  private $followers = array();
  private $asanaconnection;

  private $lazyloaded = array('loaded', 'notes', 'createdAt', 'modifiedAt', 'isPublic', 'isArchived', 'color',
                              'members', 'followers');

  public function __construct(Array $meta, Asana $asanaconnection){
    $this->id = $meta['id'];
    $this->name = $meta['name'];
    $this->asanaconnection = $asanaconnection;

    $this->createdAt = new \DateTime();
    $this->modifiedAt = new \DateTime();
  }

  public function __get($name){
    if(in_array($name, $this->lazyloaded)){
      if($this->loaded){
        return $this->$name;
      }else{
        $this->load();
        return $this->$name;
      }
    }
    return null;
  }

  public function __set($name, $value){
    if(in_array($name, $this->lazyloaded)){
      $this->$name = $value;
    }
  }

  private function load(){
    $alldata = $this->asanaconnection->asanaRequest('GET', 'projects/' . $this->id);
    $this->createdAt->setTimestamp((int) $alldata['data']['created_at']);
    $this->modifiedAt->setTimestamp((int) $alldata['data']['modified_at']);
    $this->notes = $alldata['data']['notes'];
    $this->isPublic = $alldata['data']['public'];
    $this->isArchived = $alldata['data']['archived'];
    $this->color = $alldata['data']['color'];
    $this->followers = $alldata['data']['followers'];
    $this->members = $alldata['data']['members'];

    $this->loaded = true;
  }

  public function save(){
    $this->asanaconnection->asanaRequest('PUT', 'projects/' . $this->id, array(
      'name' => $this->name,
      'notes' => $this->notes,
      'public' => $this->isPublic,
      'archived' => $this->isArchived,
      'color' => $this->color
    ));
  }

  public function getProjects(){
    $response = $this->asanaconnection->asanaRequest('GET', 'workspaces/' . $this->id . '/projects');
    $projects = array();
    foreach($response['data'] as $project){
      $projects[$project['name']] = new Project($project, $this);
    }
    return $projects;
  }
} 