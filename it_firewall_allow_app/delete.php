<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    try {
        $dsn = "pgsql:host=" . $_ENV['N_POSTGRES_SERVER'] . ";port=" . $_ENV['N_POSTGRES_PORT'] . ";dbname=" . $_ENV['N_POSTGRES_DB'];
        $pdo = new PDO($dsn, $_ENV['N_POSTGRES_USER'], $_ENV['N_POSTGRES_PASSWORD']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("DELETE FROM it_firewall_allow WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
