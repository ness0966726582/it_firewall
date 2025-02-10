<?php
require_once 'config.php';

if (!isset($_GET['type']) || empty($_GET['type']) || !isset($_GET['name']) || empty($_GET['name'])) {
    echo json_encode(["error" => "參數缺失"]);
    exit;
}

$type = $_GET['type'];
$name = $_GET['name'];

try {
    if ($type === 'room') {
        $stmt = $pdo->prepare("SELECT id, coordinate FROM it_rooms WHERE name = :name");
    } elseif ($type === 'staff') {
        $stmt = $pdo->prepare("SELECT work_id FROM it_staff WHERE name = :name");
    } else {
        echo json_encode(["error" => "無效的類型"]);
        exit;
    }

    $stmt->execute(['name' => $name]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(["error" => "找不到對應的資料"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "資料庫查詢失敗：" . $e->getMessage()]);
}
