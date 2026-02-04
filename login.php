<?php include 'koneksi.php';
if (isset($_POST['login'])) {
    $user = $_POST['user']; $pass = $_POST['pass'];
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user' AND password='$pass'");
    if (mysqli_num_rows($query) > 0) { $_SESSION['admin'] = $user; header("Location: index.php"); }
    else { $error = "Duh, salah nih! Cek lagi ya."; }
} ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Gemoy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #ffe9f3; height: 100vh; display: flex; align-items: center; font-family: 'Poppins', sans-serif; }
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 20px #fbc2eb; }
        .btn-pink { background: #ff9a9e; color: white; border-radius: 10px; border: none; }
        .btn-pink:hover { background: #fecfef; color: #ff6a95; }
    </style>
</head>
<body>
    <div class="container col-md-4">
        <div class="card p-4 text-center">
            <h3 style="color: #ff6a95;"> Login </h3>
            <?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
            <form method="POST">
                <input type="text" name="user" class="form-control mb-3" placeholder="Username" required>
                <input type="password" name="pass" class="form-control mb-3" placeholder="Password" required>
                <button type="submit" name="login" class="btn btn-pink w-100">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>