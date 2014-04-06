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
    $this->createdAt = new \DateTime($alldata['data']['created_at']);
    $this->modifiedAt = new \DateTime($alldata['data']['modified_at']);
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

  public function delete(){
    $this->asanaconnection->asanaRequest('DELETE', 'projects/' . $this->id);
  }

  public function getTasks($filters = array()){
    $url = 'projects/' . $this->id . '/tasks';
    if(count($filters) > 0){
      if(isset($filters['completed_since'])){
        if(!($filters['completed_since'] instanceof \DateTime)){
          $filters['completed_since'] = new \DateTime($filters['completed_since']);
        }
        $filters['completed_since'] = $filters['completed_since']->format("c");
      }
      if(isset($filters['modified_since'])){
        if(!($filters['modified_since'] instanceof \DateTime)){
          $filters['modified_since'] = new \DateTime($filters['modified_since']);
        }
        $filters['modified_since'] = $filters['modified_since']->format("c");
      }
      $url .= '?' . http_build_query($filters);
    }
    $response = $this->asanaconnection->asanaRequest('GET', $url);
    $tasks = array();
    foreach($response['data'] as $task){
      $tasks[$task['name']] = new Task($task, $this->asanaconnection);
    }
    return $tasks;
  }

  public function createTask(){
    //TODO: implement createTask()
  }
} 