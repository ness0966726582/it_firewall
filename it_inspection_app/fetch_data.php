<?php
header('Content-Type: application/json'); 
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET"); 

$config = require 'config.php';
$pdo = $config['pdo']; // 取得 PDO 連線物件

try {
    $whereClauses = [];
    $params = [];

    if (!empty($_GET['ticket_number'])) {
        $whereClauses[] = "ticket_number = :ticket_number";
        $params[':ticket_number'] = $_GET['ticket_number'];
    }
    if (!empty($_GET['request_dept'])) {
        $whereClauses[] = "request_dept = :request_dept";
        $params[':request_dept'] = $_GET['request_dept'];
    }
    if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $whereClauses[] = "request_date BETWEEN :start_date AND :end_date";
        $params[':start_date'] = $_GET['start_date'];
        $params[':end_date'] = $_GET['end_date'];
    }

    $sql = "SELECT * FROM it_firewall_allow";
    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }
    $sql .= " ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $data], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>
