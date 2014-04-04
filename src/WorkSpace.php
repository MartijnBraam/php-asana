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
  private $asanaconnection;

  public function __construct(Array $meta, Asana $asanaconnection){
    $this->id = $meta['id'];
    $this->name = $meta['name'];
    $this->asanaconnection = $asanaconnection;
  }

  public function rename($name){
    $newdata = $this->asanaconnection->asanaRequest('PUT', 'workspace/' . $this->id, array(
      'name' => $name
    ));
    $this->name = $newdata['data']['name'];
  }

  public function getProjects(){
    $response = $this->asanaconnection->asanaRequest('GET', 'workspaces/' . $this->id . '/projects');
    $projects = array();
    foreach($response['data'] as $project){
      $projects[$project['name']] = new Project($project, $this->asanaconnection);
    }
    return $projects;
  }

  public function createProject($name){
    //TODO: implement createProject()
  }
} 