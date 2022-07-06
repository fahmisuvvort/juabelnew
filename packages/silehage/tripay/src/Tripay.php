<?php

namespace Silehage\Tripay;

class Tripay 
{
  protected $apikey;
  protected $private_key;
  protected $merchant_code; 
  protected $url_endpoint;

  public function __construct()
  {

      $this->apikey        = config('tripay.api_key');
      $this->private_key   = config('tripay.private_key');
      $this->merchant_code = config('tripay.merchant_code');
      $this->url_endpoint  = config('tripay.api_url');  

      if(config('tripay.mode') == 'sanbox') {
        $this->url_endpoint  = config('tripay.api_sanbox_url');  
      }

  }

  public function paymentChanels()
  {

    $result = $this->curlGet('/merchant/payment-channel');

    return $result;
  }

  public function createTransaction($payload)
  {
    $payload['callback_url'] = route('tripay.callback');
    $payload['expired_time'] = (time()+(24*60*60)); // 24 jam
    $payload['signature'] = $this->setSignature($payload['merchant_ref'], $payload['amount']);

    $result = $this->curlPost('/transaction/create', $payload);

    return $result;

  }

  public function transactionDetail($payload)
  {
  
    $result = $this->curlGet('/transaction/detail', $payload);

    return $result;
  }

  public function calculatorFee($payload)
  {
    $result = $this->curlGet('/merchant/fee-calculator', $payload);

    return $result;
  }

  protected function curlGet($endpoint, $payload = [])
  {
    $url = rtrim($this->url_endpoint,'/').'/'.ltrim($endpoint,'/');

    if(count($payload) > 0) {
      $url = $url . '?' .http_build_query($payload);
    }

    $curl = curl_init();

    $header = [
      'Authorization: Bearer '.$this->apikey
    ];

    curl_setopt($curl,CURLOPT_FRESH_CONNECT, true);
    curl_setopt($curl,CURLOPT_URL, $url);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
    curl_setopt($curl,CURLOPT_FAILONERROR,false);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    $errno  = curl_errno($curl);

    curl_close($curl);

    if($errno){
      return json_encode([
          'success' => false,
          'message' => $error,
          'connected' => false,
      ]);
    }else{

        return $response;
    }
  }

  protected function curlPost($endpoint,$data)
  {
      $url = rtrim($this->url_endpoint,'/').'/'.ltrim($endpoint,'/');
      
      $header = [
          'Authorization: Bearer '.$this->apikey
      ];
      $curl = curl_init();
      curl_setopt($curl,CURLOPT_FRESH_CONNECT,true);
      curl_setopt($curl,CURLOPT_URL,$url);
      curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
      curl_setopt($curl,CURLOPT_HEADER,false);
      curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
      curl_setopt($curl,CURLOPT_FAILONERROR,false);
      curl_setopt($curl,CURLOPT_POST,true);
      curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($data));
      curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
      curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

      $response = curl_exec($curl);
      $error = curl_error($curl);
      $errno  = curl_errno($curl);
      curl_close($curl);

      if($errno){
          return json_encode([
              'success' => false,
              'message' => $error,
              'connected' => false,
          ]);
      }else{
          return $response;
    }
  }

  public function setSignature($orderRef, $orderTotal)
  {
      return hash_hmac('sha256', $this->merchant_code.$orderRef.$orderTotal, $this->private_key);
  }
}