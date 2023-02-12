<?php

$url = "https://gsmarena.com/";

// Inisialisasi cURL
$ch = curl_init();

// Set opsi cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Eksekusi cURL
$data = curl_exec($ch);

// Menutup cURL
curl_close($ch);

// Mencari semua tag HTML dengan class "makers"
preg_match_all("/<li class=\"makers\"><a href=\"(.*?)\"><img src=\"(.*?)\" alt=\"(.*?)\".*?<\/li>/", $data, $matches);

// Mendapatkan URL dan nama dari setiap smartphone
$urls = $matches[1];
$images = $matches[2];
$names = $matches[3];

// Menampilkan nama dan gambar dari setiap smartphone
for ($i = 0; $i < count($urls); $i++) {
    $device_url = "https://gsmarena.com" . $urls[$i];
    $device_data = file_get_contents($device_url);
    
    // Mencari spesifikasi dari setiap smartphone
    preg_match_all("/<td class=\"ttl\"><a href=\"#\".*?>(.*?)<\/a><\/td>.*?<td class=\"nfo\">(.*?)<\/td>/", $device_data, $device_matches);
    
    $device_specs = array();
    for ($j = 0; $j < count($device_matches[1]); $j++) {
        $device_specs[$device_matches[1][$j]] = $device_matches[2][$j];
    }
    
    // Menampilkan nama, gambar, dan spesifikasi dari setiap smartphone
    echo "<h3>" . $names[$i] . "</h3>";
    echo "<img src='" . $images[$i] . "'><br><br>";
    echo "<table>";
    foreach ($device_specs as $spec_name => $spec_value) {
        echo "<tr>";
        echo "<td><b>" . $spec_name . ":</b></td>";
        echo "<td>" . $spec_value . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<br><br>";
}

?>
