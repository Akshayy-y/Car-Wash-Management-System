<?php
session_start();
include('includes/config.php');

$error = "";

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT id, fullName, password FROM tblusers WHERE email = :email";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email);
  $query->execute();

  if ($query->rowCount() == 1) {
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, $user['password'])) {
      $_SESSION['userlogin'] = $user['id'];
      $_SESSION['username'] = $user['fullName'];
      header("Location: booking.php");
      exit;
    } else {
      $error = "Invalid password";
    }
  } else {
    $error = "No user found with that email";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Car Wash Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      background: url('img/bglogin.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
    }

    .auth-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 30px 25px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
      backdrop-filter: blur(8px);
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

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.25);
    }

    .password-wrapper {
      position: relative;
    }

    .password-wrapper i {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
    }
  </style>
</head>

<body>

  <div class="auth-container">
    <h4 class="text-center mb-4">Login to Book Appointment</h4>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> <?= htmlentities($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="password" class="form-control" id="passwordInput" required />
          <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
            <i class="fa-solid fa-eye"></i>
          </span>
        </div>
      </div>

      <div class="d-grid">
        <button type="submit" name="login" class="btn btn-red-animated">Login</button>
      </div>
      <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a></p>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle password visibility
    const toggle = document.getElementById("togglePassword");
    const password = document.getElementById("passwordInput");

    toggle.addEventListener("click", () => {
      const inputType = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", inputType);

      const icon = toggle.querySelector("i");
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
    });

    // Auto-dismiss alert
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