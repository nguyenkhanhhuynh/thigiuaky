<?php
include '../config/config.php';
include '../includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaSV = trim($_POST['MaSV']);
    $HoTen = trim($_POST['HoTen']);
    $GioiTinh = trim($_POST['GioiTinh']);
    $NgaySinh = trim($_POST['NgaySinh']);
    $MaNganh = trim($_POST['MaNganh']);

    // Xử lý file ảnh
    $Hinh = "";
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $Hinh = $target_dir . basename($_FILES["Hinh"]["name"]);
        if (!move_uploaded_file($_FILES["Hinh"]["tmp_name"], $Hinh)) {
            echo "<div class='alert alert-danger'>Lỗi tải lên tệp.</div>";
            exit();
        }
    }

    // Kiểm tra dữ liệu đầu vào
    if (empty($MaSV) || empty($HoTen) || empty($NgaySinh) || empty($MaNganh)) {
        echo "<div class='alert alert-danger'>Vui lòng nhập đầy đủ thông tin!</div>";
    } else {
        // Kiểm tra mã sinh viên đã tồn tại
        $check_sql = "SELECT MaSV FROM SinhVien WHERE MaSV = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $MaSV);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<div class='alert alert-danger'>Mã sinh viên đã tồn tại!</div>";
        } else {
            // Kiểm tra khóa ngoại
            $check_sql = "SELECT MaNganh FROM NganhHoc WHERE MaNganh = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $MaNganh);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows == 0) {
                echo "<div class='alert alert-danger'>Mã ngành không tồn tại!</div>";
            } else {
                $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $MaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh);

                if ($stmt->execute()) {
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Lỗi: " . $conn->error . "</div>";
                }
            }
        }
    }
}
?>

<div class="container mt-5">
    <h2 class="mb-4">THÊM SINH VIÊN</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="MaSV">Mã SV:</label>
            <input type="text" class="form-control" id="MaSV" name="MaSV" required>
        </div>
        <div class="form-group">
            <label for="HoTen">Họ Tên:</label>
            <input type="text" class="form-control" id="HoTen" name="HoTen" required>
        </div>
        <div class="form-group">
            <label for="GioiTinh">Giới Tính:</label>
            <select class="form-control" id="GioiTinh" name="GioiTinh">
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>
        </div>
        <div class="form-group">
            <label for="NgaySinh">Ngày Sinh:</label>
            <input type="date" class="form-control" id="NgaySinh" name="NgaySinh" required>
        </div>
        <div class="form-group">
            <label for="Hinh">Hình:</label>
            <input type="file" class="form-control-file" id="Hinh" name="Hinh">
        </div>
        <div class="form-group">
            <label for="MaNganh">Ngành Học:</label>
            <input type="text" class="form-control" id="MaNganh" name="MaNganh" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>