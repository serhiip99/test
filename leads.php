<?php
// Функция для получения данных о лидах через API
function getLeads($date_from, $date_to) {
    $apiUrl = 'https://crm.belmar.pro/api/v1/getstatuses';
    $token = 'ba67df6a-a17c-476f-8e95-bcdb75ed3958'; // Замените <token> на реальный токен

    // Параметры запроса
    $requestData = array(
        "date_from" => $date_from,
        "date_to" => $date_to,
        "page" => 0,
        "limit" => 100
    );

    $jsonData = json_encode($requestData);

    // Инициализация cURL
    $ch = curl_init($apiUrl);

    // Настройка параметров cURL
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'token: ' . $token,
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Выполнение cURL-запроса и получение ответа
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Ошибка cURL: ' . curl_error($ch);
        return [];
    } else {
        // Декодирование ответа
        $responseData = json_decode($response, true);
        return $responseData['data'] ?? [];
    }

    // Закрытие cURL
    curl_close($ch);
}

// Установка значений по умолчанию для дат
$date_from = "2022-12-01 00:00:00";
$date_to = "2024-12-31 23:59:59";

// Проверка, если даты были переданы через форму
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty($_GET['startDate'])) {
        $date_from = $_GET['startDate'] . " 00:00:00";
    }
    if (!empty($_GET['endDate'])) {
        $date_to = $_GET['endDate'] . " 23:59:59";
    }
}

// Получение данных о лидах
$leads = getLeads($date_from, $date_to);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статусы лидов</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Статусы лидов</h1>
        <form class="filter-form" method="GET" action="leads.php">
            <label for="startDate">Дата с:</label>
            <input type="date" id="startDate" name="startDate">
            <label for="endDate">Дата по:</label>
            <input type="date" id="endDate" name="endDate">
            <input type="submit" value="Фильтр">
        </form>
        <table id="leadsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>FTD</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($leads)): ?>
                    <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($lead['id']); ?></td>
                            <td><?php echo htmlspecialchars($lead['email']); ?></td>
                            <td><?php echo htmlspecialchars($lead['status']); ?></td>
                            <td><?php echo htmlspecialchars($lead['ftd']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Нет данных для отображения</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="link">
            <a href="/test/main.html">Вернуться к форме</a>
        </div>
    </div>
</body>
</html>
