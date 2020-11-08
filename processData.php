<?php

header('Content-type:text/json; charset=utf-8');

include 'GenericDate.php';

try {
    $date = $_POST['date']; // 15/01/2007 13:22
    $op = $_POST['op'];
    $value = $_POST['value'];

    $genericDate = new GenericDate();
    $resultDate = $genericDate->changeDate($date, $op, $value);

    echo json_encode([
        'success' => true,
        'data' => [
            'date' => $date,
            'resultDate' => $resultDate
        ]
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
