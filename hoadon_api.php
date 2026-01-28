<?php
require 'db.php'; 

$action = $_POST['action'] ?? '';

// 1. Lấy danh sách hóa đơn hiện ra bảng
if ($action == 'fetch') {
    $sql = "SELECT h.id_hoa_don, h.ngay_ban, h.tong_tien, n.ten_dang_nhap 
            FROM hoa_don h 
            INNER JOIN nguoi_dung n ON h.id_nguoi_dung = n.id_nguoi_dung 
            ORDER BY h.id_hoa_don DESC";
    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) { $data[] = $row; }
    echo json_encode($data);
}

// 2. Lấy danh sách Người dùng & Sản phẩm để hiện lên ô chọn
if ($action == 'fetch_setup') {
    $users = $conn->query("SELECT id_nguoi_dung, ten_dang_nhap FROM nguoi_dung")->fetch_all(MYSQLI_ASSOC);
    $prods = $conn->query("SELECT id_san_pham, ten_san_pham, gia_ban FROM san_pham")->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['users' => $users, 'prods' => $prods]);
}

// 3. LƯU HÓA ĐƠN MỚI (Lưu vào 2 bảng)
if ($action == 'insert') {
    $id_user = $_POST['id_nguoi_dung'];
    $id_sp = $_POST['id_san_pham'];
    $sl = $_POST['so_luong'];
    
    // Lấy giá bán của sản phẩm để tính tiền
    $sp = $conn->query("SELECT gia_ban FROM san_pham WHERE id_san_pham = $id_sp")->fetch_assoc();
    $gia = $sp['gia_ban'];
    $thanh_tien = $gia * $sl;

    // BƯỚC A: Lưu vào bảng hoa_don
    $stmt1 = $conn->prepare("INSERT INTO hoa_don (ngay_ban, id_nguoi_dung, tong_tien) VALUES (NOW(), ?, ?)");
    $stmt1->bind_param("id", $id_user, $thanh_tien);
    $stmt1->execute();
    $id_hd_vua_tao = $conn->insert_id; // Lấy ID hóa đơn vừa tạo tự động

    // BƯỚC B: Lưu vào bảng chi_tiet_hoa_don
    $stmt2 = $conn->prepare("INSERT INTO chi_tiet_hoa_don (id_hoa_don, id_san_pham, so_luong, don_gia, thanh_tien) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("iiidd", $id_hd_vua_tao, $id_sp, $sl, $gia, $thanh_tien);
    
    echo $stmt2->execute() ? "Success" : $conn->error;
}

// 4. Lấy chi tiết hóa đơn (như cũ)
if ($action == 'fetch_detail') {
    $id_hd = $_POST['id_hoa_don'];
    $sql = "SELECT c.id_chi_tiet_hoa_don, s.ten_san_pham, c.so_luong, c.don_gia, c.thanh_tien 
            FROM chi_tiet_hoa_don c INNER JOIN san_pham s ON c.id_san_pham = s.id_san_pham 
            WHERE c.id_hoa_don = $id_hd";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
?>