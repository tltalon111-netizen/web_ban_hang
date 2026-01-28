<?php
require 'db.php'; // Đảm bảo file db.php nằm cùng thư mục

$action = $_POST['action'] ?? '';

// 1. Lấy danh mục đổ vào ô chọn
if ($action == 'fetch_danh_muc') {
    $result = $conn->query("SELECT id_danh_muc, ten_danh_muc FROM danh_muc");
    $data = [];
    while ($row = $result->fetch_assoc()) { $data[] = $row; }
    echo json_encode($data);
}

// 2. Thêm mới (Dùng kỹ thuật an toàn của video)
if ($action == 'insert') {
    $stmt = $conn->prepare("INSERT INTO san_pham (ten_san_pham, ma_san_pham, id_danh_muc, don_vi, gia_nhap, gia_ban, so_luong) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisddi", 
        $_POST['ten'], 
        $_POST['ma'], 
        $_POST['id_danh_muc'], 
        $_POST['don_vi'], 
        $_POST['gia_nhap'], 
        $_POST['gia_ban'], 
        $_POST['so_luong']
    );
    echo $stmt->execute() ? "Success" : $stmt->error;
}

// 3. Lấy danh sách sản phẩm hiện ra bảng
if ($action == 'fetch') {
    $sql = "SELECT sp.*, dm.ten_danh_muc 
            FROM san_pham sp 
            LEFT JOIN danh_muc dm ON sp.id_danh_muc = dm.id_danh_muc 
            ORDER BY sp.id_san_pham DESC";
    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) { $data[] = $row; }
    echo json_encode($data);
}

// 4. Cập nhật sản phẩm
if ($action == 'update') {
    $stmt = $conn->prepare("UPDATE san_pham SET ten_san_pham=?, ma_san_pham=?, id_danh_muc=?, don_vi=?, gia_nhap=?, gia_ban=?, so_luong=? WHERE id_san_pham=?");
    $stmt->bind_param("ssisddii", 
        $_POST['ten'], $_POST['ma'], $_POST['id_danh_muc'], 
        $_POST['don_vi'], $_POST['gia_nhap'], $_POST['gia_ban'], 
        $_POST['so_luong'], $_POST['id_san_pham']
    );
    echo $stmt->execute() ? "Success" : $stmt->error;
}

// 5. Xóa sản phẩm
if ($action == 'delete') {
    $stmt = $conn->prepare("DELETE FROM san_pham WHERE id_san_pham=?");
    $stmt->bind_param("i", $_POST['id_san_pham']);
    echo $stmt->execute() ? "Success" : $stmt->error;
}
?>