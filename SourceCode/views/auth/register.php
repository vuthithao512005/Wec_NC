<!DOCTYPE html>

<html>
<head>
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-5 mx-auto">
        <div class="card shadow p-4">
            <h3 class="text-center mb-3">Đăng ký</h3>


        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=register" onsubmit="return validateForm()">
            <div class="mb-3">
                <input type="text" id="name" name="name" class="form-control" placeholder="Họ tên">
            </div>

            <div class="mb-3">
                <input type="email" id="email" name="email" class="form-control" placeholder="Email">
            </div>

            <div class="mb-3 position-relative">
                <input type="password" id="password" name="password" class="form-control" placeholder="Mật khẩu">
                <span onclick="toggle('password','eye1')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">
                    <i id="eye1" class="fa fa-eye"></i>
                </span>
            </div>

            <div class="mb-3 position-relative">
                <input type="password" id="confirm" name="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu">
                <span onclick="toggle('confirm','eye2')" style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer;">
                    <i id="eye2" class="fa fa-eye"></i>
                </span>
            </div>

            <button class="btn btn-primary w-100">Đăng ký</button>
        </form>

        <p class="text-center mt-3">
            Đã có tài khoản? <a href="index.php?page=login">Đăng nhập</a>
        </p>
    </div>
</div>


</div>

<script>
function toggle(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    }
}

function validateForm() {
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value;
    let confirm = document.getElementById("confirm").value;

    if (!name || !email || !password || !confirm) {
        alert("Vui lòng nhập đầy đủ thông tin");
        return false;
    }

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Email không hợp lệ");
        return false;
    }

    if (password.length < 6) {
        alert("Mật khẩu phải >= 6 ký tự");
        return false;
    }

    if (password !== confirm) {
        alert("Mật khẩu không khớp");
        return false;
    }

    return true;
}
</script>

</body>
</html>
