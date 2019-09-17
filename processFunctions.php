<?php

include 'definedVariables.php';

function lau($var){
  print '<pre>';
  print_r($var);
  print '</pre>';
  exit();
}


function prettify($json)
{
    $array = json_decode($json, true);
    $json = json_encode($array, JSON_PRETTY_PRINT);
    return $json;
}

function callAPI($method, $url, $data, $header_array = array(), $username='', $password=''){
   $curl = curl_init();

   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
         break;
      default:
      	if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   	// OPTIONS:
  curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header_array);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  if($username!='' && $password !=''){
      curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);  
  }
  

   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}


?>