<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    die("ID is required");
}

try {
    // 建立資料庫連線
    $dsn = "pgsql:host=" . $_ENV['N_POSTGRES_SERVER'] . ";port=" . $_ENV['N_POSTGRES_PORT'] . ";dbname=" . $_ENV['N_POSTGRES_DB'];
    $pdo = new PDO($dsn, $_ENV['N_POSTGRES_USER'], $_ENV['N_POSTGRES_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 查詢對應 ID 的資料
    $stmt = $pdo->prepare("SELECT * FROM it_firewall_allow WHERE id = :id");
    $stmt->execute([':id' => $_GET['id']]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        die("Record not found");
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>防火牆規則申請單</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20mm 15mm;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f5f5f5;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
        }
        .footer span {
            display: inline-block;
            width: 45%;
        }
        @media print {
            .button-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <h1>防火牆規則申請單 (Firewall Request Form)</h1>
    <table>
        <tr>
            <th colspan="2">需求單位資訊 (Request Info)</th>
        </tr>
        <tr>
            <td>申請單據 (Ticket Number)</td>
            <td><?= htmlspecialchars($record['ticket_number']) ?></td>
        </tr>
        <tr>
            <td>申請日期 (Request Date)</td>
            <td><?= htmlspecialchars($record['request_date']) ?></td>
        </tr>
        <tr>
            <td>申請部門 (Request Dept)</td>
            <td><?= htmlspecialchars($record['request_dept']) ?></td>
        </tr>
        <tr>
            <td>申請人 (Request Name)</td>
            <td><?= htmlspecialchars($record['request_name']) ?></td>
        </tr>
        <tr>
            <td>申請需求核准人 (Approved By)</td>
            <td><?= htmlspecialchars($record['approved_by']) ?></td>
        </tr>
        <tr>
            <td>來源 (Source)</td>
            <td><?= htmlspecialchars($record['source']) ?></td>
        </tr>
        <tr>
            <td>目標 (Destination)</td>
            <td><?= htmlspecialchars($record['destination']) ?></td>
        </tr>
        <tr>
            <td>協議/端口 (Protocol/Port)</td>
            <td><?= htmlspecialchars($record['protocol']) ?></td>
        </tr>
        <tr>
            <td>有效期間 (Effective Date)</td>
            <td>
                生效時間: <?= htmlspecialchars($record['start_date']) ?><br>
                失效時間: <?= htmlspecialchars($record['end_date']) ?>
            </td>
        </tr>
        <tr>
            <td>需求目的及備註 (Purpose and Remarks)</td>
            <td><?= nl2br(htmlspecialchars($record['purpose'])) ?></td>
        </tr>
    </table>

    <table>
        <tr>
            <th colspan="2">IT 單位資訊 (IT Internal Info)</th>
        </tr>
        <tr>
            <td>Firewall Policy ID</td>
            <td><?= htmlspecialchars($record['id']) ?></td>
        </tr>
        <tr>
            <td>SD WAN Rules ID</td>
            <td><?= htmlspecialchars($record['sd_wan_rules_id'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td>VPN</td>
            <td><?= htmlspecialchars($record['vpn'] ?? 'N/A') ?></td>
        </tr>
        <tr>
            <td>備註 (Remarks)</td>
            <td><?= htmlspecialchars($record['remarks'] ?? '') ?></td>
        </tr>
    </table>
    <div class="footer">
		<br><br><br>
		<span>執行人 Checked by: ________________</span>
        <span>主管簽名 Approved by: ________________</span>
    </div>
	
    <p>*主管簽名後列入 GOOGLE SHEET 紀錄 (After approved by IT head, record in Google sheet)</p>
</body>
</html>
