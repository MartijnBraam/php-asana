<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/4/14
 * Time: 12:53 PM
 */

namespace PhpAsana;


class Task {
  public $id;
  public $name;
  public $isLabel = false;

  private $loaded = false;
  private $assignee;
  private $createdAt;
  private $isCompleted;
  private $completedAt;
  private $dueOn;
  private $followers;
  private $modifiedAt;
  private $notes;
  private $parentTask;

  private $asanaconnection;

  private $lazyloaded = array('assignee', 'createdAt', 'isCompleted', 'completedAt', 'dueOn', 'followers', 'modifiedAt',
    'notes', 'parentTask');

  public function __construct(Array $meta, AsanaInterface $asanaconnection){
    $this->id = $meta['id'];
    $this->name = $meta['name'];
    if(substr($this->name, -1) == ':'){
        $this->isLabel = true;
    }
    $this->asanaconnection = $asanaconnection;
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
    $alldata = $this->asanaconnection->asanaRequest('GET', 'tasks/' . $this->id);
    $this->createdAt = new \DateTime($alldata['data']['created_at']);
    $this->modifiedAt = new \DateTime($alldata['data']['modified_at']);
    $this->completedAt = new \DateTime($alldata['data']['completed_at']);
    $this->dueOn = new \DateTime($alldata['data']['due_on']);
    $this->assignee = $this->asanaconnection->userInfo[$alldata['data']['assignee']['id']];
    $this->isCompleted = $alldata['data']['completed'];
    $this->followers = $alldata['data']['followers'];
    $this->notes = $alldata['data']['notes'];
    if($alldata['data']['parent']===null){
      $this->parentTask = null;
    }else{
      $this->parentTask = new Task($alldata['data']['parent'], $this->asanaconnection);
    }
    $this->loaded = true;
  }


  public function save(){
    //TODO: implement save()
  }

  public function assignTo(){
    //TODO: implement assignTo()
  }

  public function delete(){
    //TODO: implement delete()
  }
} 