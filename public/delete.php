<?php
include '../config/config.php';
include '../includes/header.php';

if (isset($_GET['MaSV'])) {
    $MaSV = $_GET['MaSV'];

    // Xóa các bản ghi liên quan trong bảng DangKy trước
    $sql_dangky = "DELETE FROM DangKy WHERE MaSV = ?";
    $stmt_dangky = $conn->prepare($sql_dangky);
    $stmt_dangky->bind_param("s", $MaSV);
    $stmt_dangky->execute();

    // Sau đó xóa sinh viên trong bảng SinhVien
    $sql = "DELETE FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $MaSV);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Xóa sinh viên thành công.</div>";
        header("refresh:2;url=index.php"); // Chuyển hướng sau 2 giây
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Mã sinh viên không hợp lệ!</div>";
}

include '../includes/footer.php';
?>