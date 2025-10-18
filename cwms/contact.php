<?php
error_reporting(0);
include('includes/config.php');

$successMsg = $errorMsg = "";

if (isset($_POST['submit'])) {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $sql = "INSERT INTO tblenquiry(FullName,EmailId,Subject,Description) 
            VALUES(:name,:email,:subject,:message)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':subject', $subject, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->execute();

    if ($dbh->lastInsertId()) {
        $successMsg = "Your message has been sent successfully!";
    } else {
        $errorMsg = "Something went wrong. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CWMS | Contact Us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Custom CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container text-center">
        <h2>Contact Us</h2>
        <a href="index.php">Home</a> / 
        <a href="contact.php">Contact</a>
    </div>
</div>

<!-- Contact Section -->
<div class="contact py-5">
    <div class="container">
        <div class="section-header text-center mb-4">
            <p>Get In Touch</p>
            <h2>Contact for Any Query</h2>
        </div>

        <?php if ($successMsg): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlentities($successMsg) ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <?php if ($errorMsg): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlentities($errorMsg) ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-4 mb-4">
                <div class="bg-light p-4 rounded shadow-sm h-100">
                    <h4>Quick Contact Info</h4>
                    <?php 
                    $sql = "SELECT * FROM tblpages WHERE PageType='contact'";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    foreach ($results as $result): ?>
                        <p><i class="fa fa-map-marker-alt mr-2 text-danger"></i><?= htmlentities($result->detail); ?></p>
                        <p><i class="fa fa-clock mr-2 text-danger"></i><?= htmlentities($result->openignHrs); ?></p>
                        <p><i class="fa fa-phone-alt mr-2 text-danger"></i>+<?= htmlentities($result->phoneNumber); ?></p>
                        <p><i class="fa fa-envelope mr-2 text-danger"></i><?= htmlentities($result->emailId); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="bg-light p-4 rounded shadow-sm">
                    <form method="post">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Your Full Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" rows="5" class="form-control" placeholder="Message" required></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-danger btn-block">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<script>
    window.addEventListener("load", function () {
        const loader = document.getElementById("loader");
        if (loader) {
            loader.classList.remove("show");
        }
    });
</script>
