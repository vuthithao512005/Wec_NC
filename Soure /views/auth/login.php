<!DOCTYPE html>

<html>
<head>
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<!-- Thêm Font Awesome để dùng icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-5 mx-auto"> <!-- GIỮ NGUYÊN WIDTH -->
        <div class="card shadow p-5"> <!-- tăng chiều dài bằng padding -->
            <h3 class="text-center mb-4">Đăng nhập</h3> <!-- tăng margin -->


        <?php if(isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mb-4">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=login">
            <div class="mb-4"> <!-- tăng khoảng cách -->
                <input type="email" name="email" class="form-control py-3" placeholder="Email">
            </div>

            <div class="mb-4 position-relative">
                <input type="password" id="password" name="password" class="form-control py-3" placeholder="Mật khẩu">

                <span onclick="togglePassword()" 
                      style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer;">
                    <i id="eyeIcon" class="fa fa-eye"></i>
                </span>
            </div>

            <button class="btn btn-success w-100 py-3">Đăng nhập</button>
        </form>

        <p class="text-center mt-4">
            Chưa có tài khoản? <a href="index.php?page=register">Đăng ký</a>
        </p>
    </div>
</div>
```

</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>
