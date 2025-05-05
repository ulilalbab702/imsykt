<?php
session_start();
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query user by username
    $stmt = $conn->prepare("SELECT * FROM ims_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (md5($password) === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin/index.php");
            } elseif ($user['role'] === 'staff') {
                header("Location: user/index.php");
            } else {
                echo "<script>alert('Unknown role detected.'); window.location.href='login.php';</script>";
            }
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username not found!'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - YKT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #a30000, #000);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 2%;
            position: relative;
            overflow: hidden;
        }

        .circle-bg::before,
        .circle-bg::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .circle-bg::before {
            width: 300px;
            height: 300px;
            top: 10%;
            left: 5%;
        }

        .circle-bg::after {
            width: 500px;
            height: 500px;
            bottom: 0;
            right: 0;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .circle1 { width: 200px; height: 200px; top: 20%; left: 15%; }
        .circle2 { width: 300px; height: 300px; top: 40%; left: 60%; }
        .circle3 { width: 150px; height: 150px; bottom: 10%; left: 30%; }
        .circle4 { width: 100px; height: 100px; top: 70%; right: 10%; }

        .main-wrapper {
            z-index: 1;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }

        .logo-text {
            color: white;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .login-box {
            background: white;
            border-radius: 25px;
            padding: 40px 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .form-control {
            height: 45px;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 13px;
            color: #888;
        }

        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 40px;
        }

        .btn-login {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="circle-bg"></div>
<div class="circle circle1"></div>
<div class="circle circle2"></div>
<div class="circle circle3"></div>
<div class="circle circle4"></div>

<div class="main-wrapper">
    <div class="logo">
        <img src="img/logo-ykt.png" alt="YKT Logo">
    </div>
    <div class="logo-text">YKT</div>

    <div class="login-box">
        <h2>Login</h2>
        <form action="" method="POST">
            <div class="form-group input-icon mb-3">
                <i class="fa fa-user"></i>
                <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group input-icon mb-3">
                <i class="fa fa-lock"></i>
                <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>
            <div class="form-check d-flex justify-content-between mb-3">
                <div>
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <div>
                    <a href="#">Forgot your password?</a>
                </div>
            </div>
            <button type="submit" class="btn btn-dark w-100 btn-login">Sign In</button>
            <div class="text-center mt-3">
                <a href="register.php">Don't have an account? Register here</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
