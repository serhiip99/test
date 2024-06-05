<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    
    $box_id = 28;
    $offer_id = 5;
    $countryCode = 'GB';
    $language = 'en';
    $password = 'qwerty12';
    $landingUrl = $_SERVER['HTTP_REFERER'];
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $data = array(
        "firstName" => $firstName,
        "lastName" => $lastName,
        "phone" => $phone,
        "email" => $email,
        "countryCode" => $countryCode,
        "box_id" => $box_id,
        "offer_id" => $offer_id,
        "landingUrl" => $landingUrl,
        "ip" => $ip,
        "password" => $password,
        "language" => $language
    );
    
    $jsonData = json_encode($data);
    $ch = curl_init('https://crm.belmar.pro/api/v1/addlead');
    
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'token: ba67df6a-a17c-476f-8e95-bcdb75ed3958', // Замените <token> на реальный токен
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo 'Ошибка cURL: ' . curl_error($ch);
    } else {
        $responseData = json_decode($response, true);
        echo 'Ответ API: ' . print_r($responseData, true);
    }
    
    curl_close($ch);
} else {
    echo "Неверный метод запроса.";
}
?>
