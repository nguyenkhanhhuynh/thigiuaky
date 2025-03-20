<?php
include '../config/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaSV = trim($_POST['MaSV']);

    // Kiểm tra mã sinh viên
    $sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $MaSV);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['MaSV'] = $MaSV;
        header("Location: index.php");
        exit();
    } else {
        $error = "Mã sinh viên không hợp lệ!";
    }

    $stmt->close();
}

include '../includes/header.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">ĐĂNG NHẬP</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label for="MaSV">Mã SV:</label>
            <input type="text" class="form-control" id="MaSV" name="MaSV" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>