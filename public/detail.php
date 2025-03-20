<?php
include '../config/config.php';
include '../includes/header.php';

if (isset($_GET['MaSV'])) {
    $MaSV = $_GET['MaSV'];
    $sql = "SELECT SinhVien.*, NganhHoc.TenNganh FROM SinhVien 
            LEFT JOIN NganhHoc ON SinhVien.MaNganh = NganhHoc.MaNganh
            WHERE MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $MaSV);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<div class='alert alert-danger'>Không tìm thấy sinh viên!</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>Mã sinh viên không hợp lệ!</div>";
    exit();
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Chi tiết sinh viên</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Mã SV:</strong> <?= htmlspecialchars($row['MaSV']) ?></p>
            <p><strong>Họ Tên:</strong> <?= htmlspecialchars($row['HoTen']) ?></p>
            <p><strong>Giới Tính:</strong> <?= htmlspecialchars($row['GioiTinh']) ?></p>
            <p><strong>Ngày Sinh:</strong> <?= htmlspecialchars($row['NgaySinh']) ?></p>
            <p><strong>Ngành Học:</strong> <?= htmlspecialchars($row['TenNganh']) ?></p>
            <p><strong>Hình:</strong> 
                <?php if (!empty($row['Hinh'])): ?>
                    <img src="<?= htmlspecialchars($row['Hinh']) ?>" alt="Ảnh sinh viên" width="150">
                <?php else: ?>
                    Không có ảnh
                <?php endif; ?>
            </p>
            <a href="index.php" class="btn btn-primary">Quay lại danh sách</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>