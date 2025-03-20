<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];

// Lấy danh sách học phần đã đăng ký
$sql = "SELECT hp.MaHP, hp.TenHP, hp.SoTinChi 
        FROM ChiTietDangKy ctdk 
        JOIN HocPhan hp ON ctdk.MaHP = hp.MaHP
        JOIN DangKy dk ON ctdk.MaDK = dk.MaDK
        WHERE dk.MaSV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $MaSV);
$stmt->execute();
$result = $stmt->get_result();

include '../includes/header.php';
?>

<div class="container">
    <h2>ĐĂNG KÝ HỌC PHẦN</h2>
    <table border="1">
        <tr>
            <th>Mã Học Phần</th>
            <th>Tên Học Phần</th>
            <th>Số Tín Chỉ</th>
            <th>Hành Động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['MaHP']) ?></td>
                <td><?= htmlspecialchars($row['TenHP']) ?></td>
                <td><?= htmlspecialchars($row['SoTinChi']) ?></td>
                <td>
                    <a href="xoa_dangky.php?MaHP=<?= $row['MaHP'] ?>" class="btn btn-danger">Xóa</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
