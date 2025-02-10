document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = "api.php";

    // 從 API 獲取數據並顯示
    function fetchData() {
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                document.getElementById("response").innerText = JSON.stringify(data, null, 2);
            })
            .catch(error => console.error("❌ API 錯誤:", error));
    }

    // 處理表單提交
    document.getElementById("firewallForm").addEventListener("submit", function (event) {
        event.preventDefault();

        // 收集表單數據
        const formData = {
            ticket_number: document.getElementById("ticket_number").value,
            request_date: document.getElementById("request_date").value,
            request_dept: document.getElementById("request_dept").value,
            request_name: document.getElementById("request_name").value,
            approved_by: document.getElementById("approved_by").value,
            source: document.getElementById("source").value,
            destination: document.getElementById("destination").value,
            protocol: ["TCP", "UDP", "ICMP"].filter(id => document.getElementById(id.toLowerCase()).checked).join(", "),
            start_date: document.getElementById("start_date").value,
            end_date: document.getElementById("end_date").value,
            purpose: document.getElementById("purpose").value,
            sd_wan_rules_id: document.getElementById("sd_wan_rules_id").value,
            vpn: document.getElementById("vpn").value,
            remarks: document.getElementById("remarks").value
        };

        // 發送表單數據到 API
        fetch(apiUrl, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => alert(data.message))
            .catch(error => console.error("❌ API 錯誤:", error));
    });

    // 將 fetchData 函數綁定到 window 以便手動刷新數據
    window.fetchData = fetchData;
});
