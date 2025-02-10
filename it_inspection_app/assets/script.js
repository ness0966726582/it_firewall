$(document).ready(function() {
    // 機房下拉選單邏輯
    $('#room_name').change(function() {
        const name = $(this).val();
        if (name) {
            $.ajax({
                url: 'fetch_data.php',
                type: 'GET',
                data: { type: 'room', name: name },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.error) {
                            alert(data.error);
                            $('#room_id').text('-');
                            $('#room_coordinate').val('');
                        } else {
                            $('#room_id').text(data.id);
                            $('#room_coordinate').val(data.coordinate);
                        }
                    } catch (error) {
                        alert('回應解析錯誤');
                    }
                },
                error: function() {
                    alert('請求失敗，請稍後再試');
                }
            });
        } else {
            $('#room_id').text('-');
            $('#room_coordinate').val('');
        }
    });

    // 員工下拉選單邏輯
    $('#staff_name').change(function() {
        const name = $(this).val();
        if (name) {
            $.ajax({
                url: 'fetch_data.php',
                type: 'GET',
                data: { type: 'staff', name: name },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.error) {
                            alert(data.error);
                            $('#work_id').text('-');
                        } else {
                            $('#work_id').text(data.work_id);
                        }
                    } catch (error) {
                        alert('回應解析錯誤');
                    }
                },
                error: function() {
                    alert('請求失敗，請稍後再試');
                }
            });
        } else {
            $('#work_id').text('-');
        }
    });
});
