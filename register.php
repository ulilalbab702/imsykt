<?php
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT * FROM ims_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username is already registered!'); window.location.href='register.php';</script>";
    } elseif ($password !== $confirm) {
        echo "<script>alert('Password confirmation does not match!'); window.location.href='register.php';</script>";
    } else {
        $hashed_password = md5($password);

        $stmt = $conn->prepare("INSERT INTO ims_user (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Registration failed! Please try again.'); window.location.href='register.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - YKT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #a30000, #000);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            font-family: 'Arial', sans-serif;
        }
        .circle { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.1); z-index: 0; }
        .circle1 { width: 300px; height: 300px; top: 10%; left: 5%; }
        .circle2 { width: 500px; height: 500px; bottom: 0; right: 0; }
        .register-box {
            background: white;
            border-radius: 25px;
            padding: 40px 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            z-index: 1;
            width: 100%;
            max-width: 400px;
        }
        .register-box h2 { text-align: center; font-weight: bold; margin-bottom: 25px; }
        .form-control { height: 45px; }
        .form-group i {
            position: absolute;
            left: 15px;
            top: 13px;
            color: #888;
        }
        .input-icon { position: relative; }
        .input-icon input { padding-left: 40px; }
        .btn-register { font-weight: bold; }
    </style>
</head>
<body>

<div class="circle circle1"></div>
<div class="circle circle2"></div>

<div class="register-box">
    <h2>Register</h2>
    <form action="" method="POST">
        <div class="form-group input-icon mb-3">
            <i class="fa fa-user"></i>
            <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
        </div>
        <div class="form-group input-icon mb-3">
            <i class="fa fa-lock"></i>
            <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
        </div>
        <div class="form-group input-icon mb-3">
            <i class="fa fa-lock"></i>
            <input type="password" class="form-control" name="confirm" placeholder="Confirm your password" required>
        </div>
        <div class="form-group mb-3">
            <select name="role" class="form-control" required>
                <option value="">-- Select Role --</option>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-dark w-100 btn-register">Sign Up</button>
        <div class="text-center mt-3">
            <a href="login.php">Already have an account? Login here</a>
        </div>
    </form>
</div>

</body>
</html>