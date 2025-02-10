<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dsn = "pgsql:host=" . $_ENV['N_POSTGRES_SERVER'] . ";port=" . $_ENV['N_POSTGRES_PORT'] . ";dbname=" . $_ENV['N_POSTGRES_DB'];
        $pdo = new PDO($dsn, $_ENV['N_POSTGRES_USER'], $_ENV['N_POSTGRES_PASSWORD']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("
            INSERT INTO it_firewall_allow 
            (ticket_number, request_date, request_dept, request_name, approved_by, source, destination, protocol, start_date, end_date, purpose) 
            VALUES 
            (:ticket_number, :request_date, :request_dept, :request_name, :approved_by, :source, :destination, :protocol, :start_date, :end_date, :purpose)
        ");

        $stmt->execute([
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
        ]);

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
