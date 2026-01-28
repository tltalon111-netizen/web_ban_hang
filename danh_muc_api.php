<?php
require 'db.php'; // Gọi file kết nối ở Bước 1

$action = $_POST['action'] ?? '';

// 1. Lấy danh sách (Fetch)
if ($action == 'fetch') {
    // CẬP NHẬT: Đổi 'id' thành 'id_danh_muc'
    $sql = "SELECT * FROM danh_muc ORDER BY id_danh_muc DESC"; 
    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

// 2. Thêm mới (Insert)
if ($action == 'insert') {
    $ten = $_POST['ten'];
    $mota = $_POST['mota'];
    $sql = "INSERT INTO danh_muc (ten_danh_muc, mo_ta) VALUES ('$ten', '$mota')";
    echo $conn->query($sql) ? "Success" : "Error";
}

// 3. Cập nhật (Update)
if ($action == 'update') {
    $id = $_POST['id_danh_muc']; // Dùng id_danh_muc theo sơ đồ
    $ten = $_POST['ten'];
    $mota = $_POST['mota'];
    $sql = "UPDATE danh_muc SET ten_danh_muc='$ten', mo_ta='$mota' WHERE id_danh_muc=$id";
    echo $conn->query($sql) ? "Success" : "Error";
}

// 4. Xóa (Delete)
if ($action == 'delete') {
    $id = $_POST['id_danh_muc'];
    $sql = "DELETE FROM danh_muc WHERE id_danh_muc=$id";
    echo $conn->query($sql) ? "Success" : "Error";
}
?>