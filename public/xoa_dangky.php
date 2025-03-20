<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['MaHP'])) {
    $MaSV = $_SESSION['MaSV'];
    $MaHP = $_GET['MaHP'];

    // Xóa học phần đã đăng ký
    $sql = "DELETE ctdk FROM ChiTietDangKy ctdk
            JOIN DangKy dk ON ctdk.MaDK = dk.MaDK
            WHERE dk.MaSV = ? AND ctdk.MaHP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $MaSV, $MaHP);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Xóa học phần thành công.</div>";
        header("refresh:2;url=hocphan_dadangky.php"); // Chuyển hướng sau 2 giây
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Mã học phần không hợp lệ!</div>";
}

include '../includes/footer.php';
?>