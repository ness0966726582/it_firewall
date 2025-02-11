<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    try {
        // 建立資料庫連線
        $dsn = "pgsql:host=" . $_ENV['N_POSTGRES_SERVER'] . ";port=" . $_ENV['N_POSTGRES_PORT'] . ";dbname=" . $_ENV['N_POSTGRES_DB'];
        $pdo = new PDO($dsn, $_ENV['N_POSTGRES_USER'], $_ENV['N_POSTGRES_PASSWORD']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 更新資料
        $stmt = $pdo->prepare("
            UPDATE it_firewall_allow 
            SET 
                ticket_number = :ticket_number,
                request_date = :request_date,
                request_dept = :request_dept,
                request_name = :request_name,
                approved_by = :approved_by,
                source = :source,
                destination = :destination,
                protocol = :protocol,
                start_date = :start_date,
                end_date = :end_date,
                purpose = :purpose,
                sd_wan_rules_id = :sd_wan_rules_id,
                vpn = :vpn,
                remarks = :remarks,
                updated_at = NOW()
            WHERE id = :id
        ");

        // 綁定參數並執行
        $stmt->execute([
            ':id' => $_GET['id'],
            ':ticket_number' => $_POST['ticket_number'],
            ':request_date' => $_POST['request_date'],
            ':request_dept' => $_POST['request_dept'],
            ':request_name' => $_POST['request_name'],
            ':approved_by' => $_POST['approved_by'] ?? null,
            ':source' => $_POST['source'] ?? null,
            ':destination' => $_POST['destination'] ?? null,
            ':protocol' => $_POST['protocol'] ?? null,
            ':start_date' => $_POST['start_date'] ?? null,
            ':end_date' => $_POST['end_date'] ?? null,
            ':purpose' => $_POST['purpose'] ?? null,
            ':sd_wan_rules_id' => $_POST['sd_wan_rules_id'] ?? null,
            ':vpn' => $_POST['vpn'] ?? null,
            ':remarks' => $_POST['remarks'] ?? null,
        ]);

        // 更新完成後返回主頁
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
