<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/4/14
 * Time: 12:57 PM
 */

namespace PhpAsana;


class Asana implements AsanaInterface {
  private $apiKey;
  private $oathToken;
  private $baseurl = 'https://app.asana.com/api/1.0/';
  public $userInfo;

  public function loginApiKey($key){
    $this->apiKey = $key;
  }

  public function loginOauthToken($token){
    $this->oathToken = $token;
  }

  public function getWorkspaces(){
    $response = $this->asanaRequest('GET', 'workspaces');
    $this->userInfo = $this->getUsers();
    $workspaces = array();
    foreach($response['data'] as $workspace){
      $workspaces[$workspace['name']] = new WorkSpace($workspace, $this);
    }
    return $workspaces;
  }

  public function getWorkspaceById($id){
    $response = $this->asanaRequest('GET', 'workspaces/' . $id);
    $this->userInfo = $this->getUsers();
    return new WorkSpace($response['data'], $this);
  }

  public function getUsers(){
    $response = $this->asanaRequest('GET', 'users?opt_fields=name,email');
    $users = array();
    foreach($response['data'] as $user){
      $users[$user['id']] = new User($user, $this);
    }
    return $users;
  }

  public function asanaRequest($method, $url, $payload = null){
    $curl = curl_init();
    $headers = array();
    if(isset($this->apiKey)){
      curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($curl, CURLOPT_USERPWD, $this->apiKey);
    }elseif(isset($this->oathToken)){
      $headers[] = 'Authorization: Bearer ' . $this->oathToken;
    }else{
      throw new \Exception("Login information not set");
    }

    $headers[] = 'Content-type: application/json';

    $curlopt = array(
      CURLOPT_URL => $this->baseurl . $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FAILONERROR => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
      CURLOPT_HTTPHEADER => $headers,
    );
    curl_setopt_array($curl, $curlopt);
    if($method == 'POST'){
      curl_setopt($curl, CURLOPT_POST, true);
    }elseif($method == 'PUT'){
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    }elseif($method == 'DELETE'){
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    if($payload != null && in_array($method, array('POST', 'PUT'))){
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    }

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
  }
} 