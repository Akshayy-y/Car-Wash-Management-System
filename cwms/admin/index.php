<?php
session_start();
include('includes/config.php');
if (isset($_POST['login'])) {
    $uname = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT UserName,Password FROM admin WHERE UserName=:uname and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>CWMS | Admin Login</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/font-awesome.css" />

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364, #3a1c71, #d76d77, #ffaf7b);
            background-size: 600% 600%;
            animation: gradientBG 18s ease infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .overlay-gloss {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at top left, rgba(255, 255, 255, 0.05), transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .login-card {
            background: rgba(0, 0, 0, 0.85);
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 0 25px #000;
            width: 100%;
            max-width: 400px;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .login-card h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 28px;
            text-align: center;
            color: #00d4ff;
            margin-bottom: 25px;
        }

        .form-control {
            height: 45px;
            border: none;
            border-radius: 4px;
            background-color: #f5f5f5;
        }

        .btn-login {
            background-color: #00d4ff;
            border: none;
            color: #000;
            font-weight: bold;
            width: 100%;
            transition: 0.3s ease-in-out;
        }

        .btn-login:hover {
            background-color: #009ec3;
            color: #fff;
        }

        .form-group label {
            color: #ccc;
        }

        .car-icon {
            font-size: 50px;
            color: #00d4ff;
            display: block;
            text-align: center;
            margin-bottom: 15px;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #00d4ff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .password-toggle {
            position: relative;
        }

        .toggle-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #333;
        }
    </style>
</head>

<body>
    <!-- Gloss overlay for subtle lighting effect -->
    <div class="overlay-gloss"></div>

    <div class="login-card">
        <i class="fa fa-car car-icon"></i>
        <h2>Admin Sign In</h2>
        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Enter Username" />
            </div>

            <div class="form-group mt-3 password-toggle">
                <label>Password</label>
                <input type="password" name="password" id="password" class="form-control" required placeholder="Enter Password" />
                <span toggle="#password" class="fa fa-eye toggle-icon" id="togglePassword"></span>
            </div>

            <button type="submit" name="login" class="btn btn-login mt-4">Login</button>
        </form>

        <div class="back-link">
            <a href="../index.php"><i class="fa fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>

    <!-- JS for Show/Hide Password -->
    <script>
        const toggle = document.getElementById("togglePassword");
        const password = document.getElementById("password");

        toggle.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>
