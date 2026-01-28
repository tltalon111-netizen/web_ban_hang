<?php
// 1. Khai báo các thông số kết nối
$host = "localhost";    // Thường là localhost
$user = "root";         // Tài khoản mặc định của XAMPP là root
$pass = "";             // Mật khẩu mặc định của XAMPP thường để trống
$db   = "quan_ly_ban_hang"; // Tên Database mới theo sơ đồ của em

// 2. Lệnh tạo kết nối
$conn = new mysqli($host, $user, $pass, $db);

// 3. Thiết lập font chữ Tiếng Việt để không bị lỗi hiển thị
$conn->set_charset("utf8");

// 4. Kiểm tra xem kết nối có thành công hay không
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
} 
// Nếu chạy trang này mà thấy trắng tinh là đã kết nối THÀNH CÔNG!
?>