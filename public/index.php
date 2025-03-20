<?php
include '../config/config.php';
include '../includes/header.php';

// Truy vấn dữ liệu sinh viên
$sql = "SELECT SinhVien.*, NganhHoc.TenNganh FROM SinhVien 
        LEFT JOIN NganhHoc ON SinhVien.MaNganh = NganhHoc.MaNganh";
$result = $conn->query($sql);

if (!$result) {
    echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Sinh Viên</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">TRANG SINH VIÊN</h2>
        <a href="create.php" class="btn btn-primary mb-3">Thêm Sinh Viên</a>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Mã SV</th>
                    <th>Họ Tên</th>
                    <th>Giới Tính</th>
                    <th>Ngày Sinh</th>
                    <th>Hình</th>
                    <th>Ngành Học</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['MaSV']) ?></td>
                    <td><?= htmlspecialchars($row['HoTen']) ?></td>
                    <td><?= htmlspecialchars($row['GioiTinh']) ?></td>
                    <td><?= htmlspecialchars($row['NgaySinh']) ?></td>
                    <td><img src="../uploads/<?= htmlspecialchars(basename($row['Hinh'])) ?>" width="50"></td>
                    <td><?= htmlspecialchars($row['TenNganh']) ?></td>
                    <td>
                        <a href="edit.php?MaSV=<?= htmlspecialchars($row['MaSV']) ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="detail.php?MaSV=<?= htmlspecialchars($row['MaSV']) ?>" class="btn btn-info btn-sm">Chi Tiết</a>
                        <a href="delete.php?MaSV=<?= htmlspecialchars($row['MaSV']) ?>" class="btn btn-danger btn-sm">Xóa</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>