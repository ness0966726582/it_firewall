<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firewall Request Form</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <h1>Firewall Request Form</h1>
    <form action="save_to_db.php" method="POST">
        <label>申請單號:</label> <input type="text" name="ticketNumber" required><br>
        <label>申請日期:</label> <input type="date" name="requestDate" required><br>
        <label>申請部門:</label> <input type="text" name="requestDept" required><br>
        <label>申請人:</label> <input type="text" name="requestName" required><br>
        <label>核准人:</label> <input type="text" name="approvedBy"><br>
        <label>來源:</label> <input type="text" name="source"><br>
        <label>目標:</label> <input type="text" name="destination"><br>
        <label>協議與端口:</label>
        <input type="checkbox" name="protocol[]" value="TCP"> TCP
        <input type="checkbox" name="protocol[]" value="UDP"> UDP
        <input type="checkbox" name="protocol[]" value="ICMP"> ICMP
        <input type="text" name="protocol[]" placeholder="Other"><br>
        <label>有效時間開始:</label> <input type="date" name="startDate"><br>
        <label>有效時間結束:</label> <input type="date" name="endDate"><br>
        <label>需求目的及備註:</label> <textarea name="purpose"></textarea><br>
        <button type="submit">提交</button>
        <button type="reset">清除</button>
    </form>
</body>
</html>
