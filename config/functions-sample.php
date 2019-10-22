<?php
function randomString()
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < 10; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
// This function uses intouchsms API
// Update Sender Name, username, password
function send_message($receiver,$message)
{
    return;
    $data = array(      
        "sender"=>"Sender Name",
        "recipients"=>$receiver,
        "message"=>$message,        
    );
    $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
    
    $data = http_build_query ($data);
    $username="username";
    $password="password";
    
    //open connection
    $ch = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
    //execute post
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //close connection
    curl_close($ch);
    
    //echo $result;
    //echo $httpcode;
    //sudo apt-get install php5-curl
    //sudo service apache2 restart
}
