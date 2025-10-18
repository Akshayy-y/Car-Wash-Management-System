<?php
session_start();
include('includes/config.php');

if (!isset($_SESSION['userlogin'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['userlogin'];
$successMsg = '';
$errorMsg = '';

// Handle form submission
if (isset($_POST['update'])) {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];

    $sql = "UPDATE tblusers SET fullName = :name, email = :email, mobileNumber = :mobile WHERE id = :uid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $fullName);
    $query->bindParam(':email', $email);
    $query->bindParam(':mobile', $mobile);
    $query->bindParam(':uid', $userId);
    
    if ($query->execute()) {
        $successMsg = "Profile updated successfully!";
    } else {
        $errorMsg = "Something went wrong. Please try again.";
    }
}

// Fetch user details
$sql = "SELECT fullName, email, mobileNumber FROM tblusers WHERE id = :uid";
$query = $dbh->prepare($sql);
$query->bindParam(':uid', $userId);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile | DripLab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .profile-box {
            max-width: 500px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }
        .btn-custom {
            background-color: #dc3545;
            color: white;
        }
        .btn-custom:hover {
            background-color: #c82333;
        }
        .page-header {
            background: #202C45;
            padding: 30px 0;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<!-- Page Header Start -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>Edit Profile</h2>
            </div>
            <div class="col-12">
                <a href="index.php">Home</a>
                <a href="profile.php">Profile</a>
                <a href="edit-profile.php">Edit Profile</a>
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<div class="container">
    <div class="profile-box">
        <h4 class="text-center mb-4">Edit Profile</h4>

        <?php if ($successMsg): ?>
            <div class="alert alert-success"><?= $successMsg ?></div>
        <?php elseif ($errorMsg): ?>
            <div class="alert alert-danger"><?= $errorMsg ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullName" class="form-control" value="<?= htmlspecialchars($user['fullName']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" name="mobile" class="form-control" value="<?= htmlspecialchars($user['mobileNumber']) ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" name="update" class="btn btn-custom">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
