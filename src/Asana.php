<?php
/**
 * Created by PhpStorm.
 * User: martijn
 * Date: 4/4/14
 * Time: 12:57 PM
 */

namespace PhpAsana;


class Asana {
  private $apiKey;
  private $oathToken;
  private $baseurl = 'https://app.asana.com/api/1.0/';

  public function loginApiKey($key){
    $this->apiKey = $key;
  }

  public function loginOauthToken($token){
    $this->oathToken = $token;
  }

  public function getWorkspaces(){
    $response = $this->asanaRequest('GET', 'workspaces');
    $workspaces = array();
    foreach($response['data'] as $workspace){
      $workspaces[$workspace['name']] = new WorkSpace($workspace);
    }
    return $workspaces;
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
      'CURLOPT_URL' => $this->baseurl . $url,
      'CURLOPT_RETURNTRANSFER' => true,
      'CURLOPT_FAILONERROR' => true,
      'CURLOPT_SSL_VERIFYPEER' => false,
      'CURLOPT_SSL_VERIFYHOST' => false,
      'CURLOPT_HTTPHEADER' => $headers,
    );
    curl_setopt_array($curl, $curlopt);
    if($method == 'POST'){
      curl_setopt($curl, CURLOPT_POST, true);
    }elseif($method == 'PUT'){
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    }
    if($payload != null && in_array($method, array('POST', 'PUT'))){
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
    }

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
  }
} 