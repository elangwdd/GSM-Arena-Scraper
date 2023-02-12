<?php

function getDeviceSpecs($device_name) {
  $device_name = str_replace(" ", "_", $device_name);
  $url = "https://m.gsmarena.com/" . $device_name . "-3903.php";
  $html = file_get_contents($url);
  $dom = new DOMDocument();
  libxml_use_internal_errors(TRUE);
  if(!empty($html)){
    $dom->loadHTML($html);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);
    $row = $xpath->query('//table[@data-spec="display"]/tr[3]/td[2]');
    $display = $row->item(0)->nodeValue;
    $row = $xpath->query('//table[@data-spec="camera"]/tr[2]/td[2]');
    $camera = $row->item(0)->nodeValue;
    $row = $xpath->query('//table[@data-spec="battery"]/tr[2]/td[2]');
    $battery = $row->item(0)->nodeValue;
    return [
      "Display" => $display,
      "Camera" => $camera,
      "Battery" => $battery
    ];
  }
  return null;
}

// Telegram Bot API Key
$apiKey = "your_api_key_here";

// Update URL
$update = file_get_contents("https://api.telegram.org/bot" . $apiKey . "/getUpdates");
$update = json_decode($update, true);

// Get the chat_id and message
$chat_id = $update["result"][count($update["result"]) - 1]["message"]["chat"]["id"];
$message = $update["result"][count($update["result"]) - 1]["message"]["text"];

// Send response
$device_name = trim($message);
$device_specs = getDeviceSpecs($device_name);
if ($device_specs) {
  $response = "Here are the specifications for the device: " . $device_name . "\n\n";
  foreach ($device_specs as $key => $value) {
    $response .= $key . ": " . $value . "\n";
  }
} else {
  $response = "Sorry, I couldn't find any specifications for the device: " . $device_name;
}

file_get_contents("https://api.telegram.org/bot" . $apiKey . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($response));

?>
