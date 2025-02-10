<?php
require_once 'config.php';

// 建立資料庫連線
try {
    $dsn = "pgsql:host=" . $_ENV['N_POSTGRES_SERVER'] . ";port=" . $_ENV['N_POSTGRES_PORT'] . ";dbname=" . $_ENV['N_POSTGRES_DB'];
    $pdo = new PDO($dsn, $_ENV['N_POSTGRES_USER'], $_ENV['N_POSTGRES_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// 從資料庫獲取資料
$query = "SELECT * FROM it_firewall_allow ORDER BY id ASC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Firewall Allow Table</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script>
        function showPopup(action, id = null) {
            const popup = document.getElementById('popup');
            const form = document.getElementById('popupForm');
            form.reset();

            if (action === 'add') {
                document.getElementById('popupTitle').innerText = '新增記錄';
                form.action = 'upload.php';
            } else if (action === 'edit') {
                document.getElementById('popupTitle').innerText = '編輯記錄';
                form.action = `update.php?id=${id}`;
                // 預先填入資料
                const row = document.getElementById(`row-${id}`);
                form.ticket_number.value = row.dataset.ticket_number;
                form.request_date.value = row.dataset.request_date;
                form.request_dept.value = row.dataset.request_dept;
                form.request_name.value = row.dataset.request_name;
                form.approved_by.value = row.dataset.approved_by;
                form.source.value = row.dataset.source;
                form.destination.value = row.dataset.destination;
                form.protocol.value = row.dataset.protocol;
                form.start_date.value = row.dataset.start_date;
                form.end_date.value = row.dataset.end_date;
                form.purpose.value = row.dataset.purpose;
            } else if (action === 'delete') {
                if (confirm('確定要刪除這條記錄嗎？')) {
                    window.location.href = `delete.php?id=${id}`;
                    return;
                }
            }

            popup.style.display = 'block';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</head>
<body>
    <h1>IT Firewall Allow Table</h1>
    <button onclick="showPopup('add')">新增</button>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ticket Number</th>
                <th>Request Date</th>
                <th>Request Dept</th>
                <th>Request Name</th>
                <th>Approved By</th>
                <th>Source</th>
                <th>Destination</th>
                <th>Protocol</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Purpose</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row): ?>
                <tr id="row-<?= $row['id'] ?>"
                    data-ticket_number="<?= htmlspecialchars($row['ticket_number']) ?>"
                    data-request_date="<?= htmlspecialchars($row['request_date']) ?>"
                    data-request_dept="<?= htmlspecialchars($row['request_dept']) ?>"
                    data-request_name="<?= htmlspecialchars($row['request_name']) ?>"
                    data-approved_by="<?= htmlspecialchars($row['approved_by']) ?>"
                    data-source="<?= htmlspecialchars($row['source']) ?>"
                    data-destination="<?= htmlspecialchars($row['destination']) ?>"
                    data-protocol="<?= htmlspecialchars($row['protocol']) ?>"
                    data-start_date="<?= htmlspecialchars($row['start_date']) ?>"
                    data-end_date="<?= htmlspecialchars($row['end_date']) ?>"
                    data-purpose="<?= htmlspecialchars($row['purpose']) ?>"
                >
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['ticket_number']) ?></td>
                    <td><?= htmlspecialchars($row['request_date']) ?></td>
                    <td><?= htmlspecialchars($row['request_dept']) ?></td>
                    <td><?= htmlspecialchars($row['request_name']) ?></td>
                    <td><?= htmlspecialchars($row['approved_by']) ?></td>
                    <td><?= htmlspecialchars($row['source']) ?></td>
                    <td><?= htmlspecialchars($row['destination']) ?></td>
                    <td><?= htmlspecialchars($row['protocol']) ?></td>
                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td><?= htmlspecialchars($row['updated_at']) ?></td>
                    <td>
                        <button onclick="showPopup('edit', <?= $row['id'] ?>)">編輯</button>
                        <button onclick="showPopup('delete', <?= $row['id'] ?>)">刪除</button>
                        <button onclick="window.location.href='view.php?id=<?= $row['id'] ?>'">顯示</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 彈出視窗 -->
    <div id="popup" style="display:none; position:fixed; top:20%; left:30%; background:white; border:1px solid #ccc; padding:20px;">
        <h2 id="popupTitle"></h2>
        <form id="popupForm" method="POST">
            <label>Ticket Number: <input type="text" name="ticket_number" required></label><br>
            <label>Request Date: <input type="date" name="request_date" required></label><br>
            <label>Request Dept: <input type="text" name="request_dept" required></label><br>
            <label>Request Name: <input type="text" name="request_name" required></label><br>
            <label>Approved By: <input type="text" name="approved_by"></label><br>
            <label>Source: <input type="text" name="source"></label><br>
            <label>Destination: <input type="text" name="destination"></label><br>
            <label>Protocol: <input type="text" name="protocol"></label><br>
            <label>Start Date: <input type="date" name="start_date"></label><br>
            <label>End Date: <input type="date" name="end_date"></label><br>
            <label>Purpose: <textarea name="purpose"></textarea></label><br>
            <button type="submit">提交</button>
            <button type="button" onclick="closePopup()">取消</button>
        </form>
    </div>
</body>
</html>
