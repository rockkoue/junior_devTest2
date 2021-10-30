<?php

$url = "http://localhost/testembauche/book.php";

$arraydata = array(
    'event_id' => 'zss',
    'event_date' => '02/02/2020',
    'ticket_adult_price' => '256250',
    'ticket_adult_quantity' => '5',
    'ticket_kid_quantity' => '0',
    'ticket_kid_price' => '0',

);
$data = http_build_query($arraydata);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
if (curl_exec($ch)) {

    $decoded = json_encode($res);
    echo $decoded;
} else {

    echo 'url not found';
}

curl_close($ch);
