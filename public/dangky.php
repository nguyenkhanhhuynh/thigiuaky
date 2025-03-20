<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];

if (isset($_GET['MaHP'])) {
    $MaHP = $_GET['MaHP'];

    // Kiểm tra xem sinh viên đã có đăng ký chưa
    $sql = "SELECT MaDK FROM DangKy WHERE MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $MaSV);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Nếu chưa có đăng ký, tạo mới
        $sql = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (NOW(), ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $MaSV);
        $stmt->execute();
        $MaDK = $stmt->insert_id;
    } else {
        // Nếu đã có đăng ký, lấy MaDK hiện tại
        $row = $result->fetch_assoc();
        $MaDK = $row['MaDK'];
    }

    // Kiểm tra xem sinh viên đã đăng ký học phần này chưa
    $sql = "SELECT * FROM ChiTietDangKy WHERE MaDK = ? AND MaHP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $MaDK, $MaHP);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Nếu chưa đăng ký, thêm vào ChiTietDangKy
        $sql = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $MaDK, $MaHP);
        $stmt->execute();
    }

    header("Location: hocphan_dadangky.php");
    exit();
} else {
    echo "Mã học phần không hợp lệ!";
}
?>
