<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('includes/config.php');
?>

<!-- Top Bar Start -->
<div class="top-bar py-3 bg-light border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-lg-4 col-md-12 text-center text-lg-start mb-2 mb-lg-0">
                <div class="logo">
                    <a href="index.php" style="text-decoration: none;">
                        <h1 class="m-0 fw-bold text-dark">Drip <span class="text-danger">Lab</span></h1>
                    </a>
                </div>
            </div>

            <!-- Contact Info -->
            <?php
            $sql = "SELECT * FROM tblpages WHERE PageType = 'contactus' LIMIT 1";
            $query = $dbh->prepare($sql);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);

            if ($result):
            ?>
                <div class="col-lg-8 col-md-12">
                    <div class="row text-center text-md-start g-3">
                        <div class="col-md-4">
                            <div class="top-bar-item d-flex align-items-center">
                                <div class="top-bar-icon me-2 text-danger fs-4">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div class="top-bar-text">
                                    <h6 class="mb-0 fw-bold">Opening Hour</h6>
                                    <small><?= htmlentities($result->openingHrs); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="top-bar-item d-flex align-items-center">
                                <div class="top-bar-icon me-2 text-danger fs-4">
                                    <i class="fa fa-phone-alt"></i>
                                </div>
                                <div class="top-bar-text">
                                    <h6 class="mb-0 fw-bold">Call Us</h6>
                                    <small>+<?= htmlentities($result->phoneNumber); ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="top-bar-item d-flex align-items-center">
                                <div class="top-bar-icon me-2 text-danger fs-4">
                                    <i class="far fa-envelope"></i>
                                </div>
                                <div class="top-bar-text">
                                    <h6 class="mb-0 fw-bold">Email Us</h6>
                                    <small><?= htmlentities($result->emailId); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Top Bar End -->

<!-- Nav Bar Start -->
<div class="nav-bar">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded shadow-sm px-3">
            <a href="#" class="navbar-brand d-lg-none">MENU</a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav me-auto">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.php" class="nav-item nav-link">About</a>
                    <a href="washing-plans.php" class="nav-item nav-link">Washing Plans</a>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                    <a href="admin" class="nav-item nav-link">Admin</a>
                    <?php if (isset($_SESSION['userlogin'])): ?>
                        <a href="profile.php" class="nav-item nav-link">My Profile</a>
                        <a href="my-bookings.php" class="nav-item nav-link">My Bookings</a>
                    <?php endif; ?>
                </div>
                <?php
                if (isset($_SESSION['userlogin'])) {
                    $uid = $_SESSION['userlogin'];
                    $notifStmt = $dbh->prepare("SELECT id, message, created_at FROM tblnotifications WHERE userId = :uid ORDER BY id DESC LIMIT 5");
                    $notifStmt->bindParam(':uid', $uid, PDO::PARAM_INT);
                    $notifStmt->execute();
                    $notifs = $notifStmt->fetchAll(PDO::FETCH_OBJ);

                    $notifCountStmt = $dbh->prepare("SELECT COUNT(*) FROM tblnotifications WHERE userId = :uid AND is_read = 0");
                    $notifCountStmt->bindParam(':uid', $uid, PDO::PARAM_INT);
                    $notifCountStmt->execute();
                    $notifCount = $notifCountStmt->fetchColumn();
                }
                ?>

                <!-- Notification Bell -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown">
                        🔔 <span class="badge badge-danger"><?= $notifCount ?? 0 ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notifDropdown">
                        <?php if (!empty($notifs)): ?>
                            <?php foreach ($notifs as $n): ?>
                                <a class="dropdown-item"><?= htmlentities($n->message) ?><br>
                                    <small class="text-muted"><?= $n->created_at ?></small>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a class="dropdown-item text-muted">No notifications</a>
                        <?php endif; ?>
                    </div>
                </li>

                <div class="navbar-nav ms-auto">
                    <?php if (!isset($_SESSION['userlogin'])): ?>
                        <a href="login.php" class="btn btn-outline-light btn-sm">Get Appointment</a>
                    <?php else: ?>
                        <a href="booking.php" class="btn btn-outline-success btn-sm me-2">Get Appointment</a>
                        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Nav Bar End -->

<!-- Bootstrap & Font Awesome (if not already included) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>