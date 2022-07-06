<?php

namespace Silehage\Starsender;

class Starsender 
{
  protected $apikey;
  protected $url_endpoint;

  public function __construct()
  {
      $this->apikey = config('starsender.apikey');
      $this->url_endpoint = config('starsender.endpoint');
  }

  public function sendText($destination, $message)
  {
      $payload = [
          'tujuan' => $destination,
          'message' => rawurlencode($message)
      ];

      $response = $this->curlPost('/sendText', $payload);


      return $response;
  }

  protected function curlPost($endpoint, $payload = [])
  {
      $url = rtrim($this->url_endpoint,'/').'/'.ltrim($endpoint,'/');

      
      if(count($payload) > 0) {
          $url = $url . '?' .http_build_query($payload);
      }

      $header = [
          'apikey: '.$this->apikey
      ];

      // return $url;

      $curl = curl_init();
      curl_setopt($curl,CURLOPT_FRESH_CONNECT,true);
      curl_setopt($curl,CURLOPT_URL,$url);
      curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($curl,CURLOPT_HEADER,false);
      curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
      curl_setopt($curl,CURLOPT_FAILONERROR,false);
      curl_setopt($curl,CURLOPT_POST,true);
      curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
      curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
      curl_setopt($curl,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4);

      $response = curl_exec($curl);
      $error = curl_error($curl);
      $errno  = curl_errno($curl);
      curl_close($curl);

      if($errno){
          return json_encode([
              'status' => false,
              'message' => $error,
              'connected' => false,
          ]);
      }else{
          return $response;
      } 

  }
}