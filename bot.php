<?php
    date_default_timezone_set("Asia/kolkata");
    //Data From Webhook
    $content = file_get_contents("php://input");
    $update = json_decode($content, true);
    $chat_id = $update["message"]["chat"]["id"];
    $message = $update["message"]["text"];
    $id = $update["message"]["from"]["id"];
    $username = $update["message"]["from"]["username"];
    $firstname = $update["message"]["from"]["first_name"];
    $message_id = $upadte["message"]["message_id"];

    //Start message
    if($message == "/start"){
        send_message($chat_id, "HEY $firstname  \nUSE /BIN xxxxxx TO CHECK BINS  \nBOT BY @sohamnavale1 ");
    }



//Bin Lookup
   if(strpos($message, "/bin") === 0){
        $bin = substr($message, 5);
   $curl = curl_init();
   curl_setopt_array($curl, [
	CURLOPT_URL => "https://lookup.binlist.net/".$bin,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"authority: lookup.binlist.net",
		"accept: application/json",
		"accept-language: en-GB,en-US;q=0.9,en;q=0.8,hi;q=0.7",
		"origin: https://binlist.net",
		"https://binlist.net/",
		"sec-fetch-dest: empty",
		"sec-fetch-site: same-site"
	],
]);

$result = curl_exec($curl);
curl_close($curl);
$data = json_decode($result, true);
$bank = strtoupper($data['bank']['name']);
$country = strtoupper($data['country']['alpha2']);
$currency = strtoupper($data['country']['currency']);
$emoji = strtoupper($data['country']['emoji']);
$scheme = strtoupper($data['scheme']);
$Brand = strtoupper($data['brand']);
$type = strtoupper($data['type']);
  if ($scheme != null) {
        send_MDmessage($chat_id, "***
    BIN: $bin
TYPE: $scheme
BRAND : $Brand
BANK: $bank
COUNTRY: $country $emoji
CURRENCY: $currency
CREDIT/DEBIT: $type
CHECKED BY @$username ***");
    }
else {
    send_MDmessage($chat_id, "ENTER VALID BIN");
}
   }
    






    
//Send Messages with Markdown (Global)
      function send_MDmessage($chat_id, $message){
       $apiToken = "1437183449:AAH4ERjxJ-h46ePLwdLI4ukisXkQqiQYPYg";
        $text = urlencode($message);
        file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?chat_id=$chat_id&text=$text&parse_mode=Markdown");
    }
    
?>
