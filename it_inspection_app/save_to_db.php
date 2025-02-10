<?php
$config = require 'config.php';

try {
    $conn = new PDO(
        "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}",
        $config['user'],
        $config['password']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql = "INSERT INTO it_firewall_allow (
                    ticket_number, request_date, request_dept, request_name,
                    approved_by, source, destination, protocol,
                    start_date, end_date, purpose
                ) VALUES (
                    :ticket_number, :request_date, :request_dept, :request_name,
                    :approved_by, :source, :destination, :protocol,
                    :start_date, :end_date, :purpose
                );";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':ticket_number' => $_POST['ticketNumber'],
            ':request_date' => $_POST['requestDate'],
            ':request_dept' => $_POST['requestDept'],
            ':request_name' => $_POST['requestName'],
            ':approved_by' => $_POST['approvedBy'] ?? null,
            ':source' => $_POST['source'] ?? null,
            ':destination' => $_POST['destination'] ?? null,
            ':protocol' => isset($_POST['protocol']) ? implode(', ', $_POST['protocol']) : null,
            ':start_date' => $_POST['startDate'] ?? null,
            ':end_date' => $_POST['endDate'] ?? null,
            ':purpose' => $_POST['purpose'] ?? null
        ]);

        echo "✅ 資料已成功存入資料庫";
    }
} catch (Exception $e) {
    echo "❌ 發生錯誤: " . $e->getMessage();
}
?>
