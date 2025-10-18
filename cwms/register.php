<?php
session_start();
include('includes/config.php');

$success = "";
$error = "";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if email already exists
    $check = $dbh->prepare("SELECT id FROM tblusers WHERE email = :email");
    $check->bindParam(':email', $email);
    $check->execute();

    if ($check->rowCount() > 0) {
        $error = "Email already registered. Please use another email.";
    } else {
        $sql = "INSERT INTO tblusers (fullName, email, mobileNumber, password) 
                VALUES (:name, :email, :mobile, :password)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name);
        $query->bindParam(':email', $email);
        $query->bindParam(':mobile', $mobile);
        $query->bindParam(':password', $password);

        if ($query->execute()) {
            $success = "Registration successful! Redirecting to login...";
            echo "<script>setTimeout(() => window.location='login.php', 2000);</script>";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register | Car Wash Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: url('img/bglogin.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: Arial, sans-serif;
    }
    .auth-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 30px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
    }
    .btn-red-animated {
      background-color: #e63946;
      color: #fff;
      font-weight: 600;
      border: none;
      padding: 12px;
      border-radius: 6px;
      transition: all 0.3s ease;
    }
    .btn-red-animated:hover {
      background-color: #d62828;
      transform: translateY(-2px) scale(1.03);
      box-shadow: 0 6px 15px rgba(230, 57, 70, 0.4);
    }
  </style>
</head>
<body>
<div class="auth-container">
  <h4 class="text-center mb-4">Create an Account</h4>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlentities($success); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlentities($error); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label>Full Name</label>
      <input type="text" name="name" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>Phone Number</label>
      <input type="text" name="phone" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>Password</label>
      <input type="password" name="password" class="form-control" required />
    </div>
    <div class="d-grid">
      <button type="submit" name="register" class="btn btn-red-animated">Register</button>
    </div>
    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Auto-dismiss alerts after 5 seconds
  setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
      alert.classList.remove('show');
      alert.classList.add('fade');
    }
  }, 5000);
</script>
</body>
</html>
