<?php
include '../config/config.php';
include '../includes/header.php';

if (isset($_GET['MaSV'])) {
    $MaSV = $_GET['MaSV'];
    $sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NewMaSV = $_POST['MaSV'];
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];
    $Hinh = $row['Hinh']; // Giữ ảnh cũ nếu không thay đổi

    // Xử lý upload ảnh mới nếu có
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
        $target_dir = "../uploads/";
        $Hinh = $target_dir . basename($_FILES["Hinh"]["name"]);
        move_uploaded_file($_FILES["Hinh"]["tmp_name"], $Hinh);
    }

    // Nếu Mã SV thay đổi, cần cập nhật lại khóa chính
    if ($NewMaSV !== $MaSV) {
        $check_sql = "SELECT MaSV FROM SinhVien WHERE MaSV = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $NewMaSV);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            echo "<div class='alert alert-danger'>Mã sinh viên đã tồn tại!</div>";
        } else {
            // Cập nhật mã sinh viên (chú ý update các bảng liên quan nếu có)
            $sql = "UPDATE SinhVien SET MaSV=?, HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $NewMaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $MaSV);
        }
    } else {
        $sql = "UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $MaSV);
    }

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Chỉnh sửa thông tin sinh viên</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="MaSV">Mã SV:</label>
            <input type="text" class="form-control" id="MaSV" name="MaSV" value="<?= htmlspecialchars($row['MaSV']) ?>" required>
        </div>
        <div class="form-group">
            <label for="HoTen">Họ Tên:</label>
            <input type="text" class="form-control" id="HoTen" name="HoTen" value="<?= htmlspecialchars($row['HoTen']) ?>" required>
        </div>
        <div class="form-group">
            <label for="GioiTinh">Giới Tính:</label>
            <select class="form-control" id="GioiTinh" name="GioiTinh">
                <option value="Nam" <?= $row['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                <option value="Nữ" <?= $row['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
            </select>
        </div>
        <div class="form-group">
            <label for="NgaySinh">Ngày Sinh:</label>
            <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" value="<?= htmlspecialchars($row['NgaySinh']) ?>" required>
        </div>
        <div class="form-group">
            <label for="Hinh">Hình:</label>
            <input type="file" class="form-control-file" id="Hinh" name="Hinh">
            <?php if (!empty($row['Hinh'])): ?>
                <img src="<?= htmlspecialchars($row['Hinh']) ?>" alt="Ảnh sinh viên" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="MaNganh">Ngành Học:</label>
            <input type="text" class="form-control" id="MaNganh" name="MaNganh" value="<?= htmlspecialchars($row['MaNganh']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>