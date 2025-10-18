<?php
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['userlogin'])) {
    header('Location: login.php');
    exit;
}

// Pre-selected plan from URL
$selectedPlan = isset($_GET['plan']) ? $_GET['plan'] : '';

// Fetch washing points from database
$sql = "SELECT id, washingPointName FROM tblwashingpoints";
$query = $dbh->prepare($sql);
$query->execute();
$locations = $query->fetchAll(PDO::FETCH_OBJ);

// Fetch logged-in user details
$userId = $_SESSION['userlogin'];
$userSql = "SELECT FullName, Email, MobileNumber FROM tblusers WHERE id = :userid";
$userQuery = $dbh->prepare($userSql);
$userQuery->bindParam(':userid', $userId, PDO::PARAM_INT);
$userQuery->execute();
$userData = $userQuery->fetch(PDO::FETCH_ASSOC);


// Handle booking form submission
if (isset($_POST['submit'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $vehicle  = $_POST['vehicle'];
    $plan     = $_POST['plan'];
    $date     = $_POST['date'];
    $time     = $_POST['time'];
    $location = $_POST['location'];
    $message  = trim($_POST['message']);
    $userid   = $_SESSION['userlogin'];

    // Plan pricing
    switch ($plan) {
        case 'Basic Wash':
            $amount = 1000;
            break;
        case 'Premium Wash':
            $amount = 1800;
            break;
        case 'Interior Detailing':
            $amount = 2700;
            break;
        default:
            $amount = 0.00;
    }

    // Validate date (cannot be past)
    if (strtotime($date) < strtotime(date('Y-m-d'))) {
        $errorMsg = "You cannot book a wash for a past date.";
    } else {
        // Generate allowed time slots
        $allowedSlots = [];
        $start = strtotime('09:00');
        $end   = strtotime('21:00');
        while ($start < $end) {
            $allowedSlots[] = date('H:i', $start);
            $start = strtotime('+30 minutes', $start);
        }

        if (!in_array($time, $allowedSlots)) {
            $errorMsg = "Invalid time. Choose between 09:00 and 21:00 in 30-min intervals.";
        } else {
            // Get washing point ID
            $locStmt = $dbh->prepare("SELECT id FROM tblwashingpoints WHERE washingPointName = :location");
            $locStmt->bindParam(':location', $location, PDO::PARAM_STR);
            $locStmt->execute();
            $locRow = $locStmt->fetch(PDO::FETCH_ASSOC);

            if (!$locRow) {
                $errorMsg = "Invalid washing point selected.";
            } else {
                $locationId = $locRow['id'];

                // Check slot conflict
                $checkSql = "SELECT id FROM tblbooking 
                             WHERE WashDate = :date AND WashTime = :time AND carWashPoint = :locationId";
                $checkQuery = $dbh->prepare($checkSql);
                $checkQuery->bindParam(':date', $date);
                $checkQuery->bindParam(':time', $time);
                $checkQuery->bindParam(':locationId', $locationId, PDO::PARAM_INT);
                $checkQuery->execute();

                if ($checkQuery->rowCount() > 0) {
                    $errorMsg = "This slot at the selected location is already booked. Please choose another.";
                } else {
                    // Insert booking
                    $sql = "INSERT INTO tblbooking 
                        (UserId, FullName, Email, PhoneNumber, VehicleType, WashingPlan, WashDate, WashTime, carWashPoint, Message, amount)
                        VALUES 
                        (:userid, :name, :email, :phone, :vehicle, :plan, :date, :time, :locationId, :message, :amount)";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':userid', $userid, PDO::PARAM_INT);
                    $query->bindParam(':name', $name);
                    $query->bindParam(':email', $email);
                    $query->bindParam(':phone', $phone);
                    $query->bindParam(':vehicle', $vehicle);
                    $query->bindParam(':plan', $plan);
                    $query->bindParam(':date', $date);
                    $query->bindParam(':time', $time);
                    $query->bindParam(':locationId', $locationId, PDO::PARAM_INT);
                    $query->bindParam(':message', $message);
                    $query->bindParam(':amount', $amount, PDO::PARAM_STR);
                    $query->execute();

                    if ($dbh->lastInsertId()) {
                        // 1) Notify the user
                        $notifSqlUser = "INSERT INTO tblnotifications (userId, message, target) VALUES (:userid, :message, 'user')";
                        $notifQueryUser = $dbh->prepare($notifSqlUser);
                        $notifQueryUser->bindParam(':userid', $userid, PDO::PARAM_INT);
                        $notifMsgUser = "Your booking for {$plan} on {$date} at {$time} has been placed successfully!";
                        $notifQueryUser->bindParam(':message', $notifMsgUser, PDO::PARAM_STR);
                        $notifQueryUser->execute();

                        // final user feedback & redirect
                        $_SESSION['successMsg'] = "Booking successful!";
                        header("Location: booking.php");
                        exit;
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Car Wash Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <?php include_once('includes/header.php'); ?>

    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Book Appointment</h2>
                </div>
                <div class="col-12"><a href="index.php">Home</a> / <a href="booking.php">Booking</a></div>
            </div>
        </div>
    </div>

    <div class="container mt-3">
        <?php if (isset($errorMsg)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlentities($errorMsg) ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['successMsg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlentities($_SESSION['successMsg']) ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['successMsg']); ?>
        <?php endif; ?>
    </div>

    <div class="contact">
        <div class="container">
            <div class="section-header text-center">
                <p>Book Your Wash</p>
                <h2>Schedule a Service</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="contact-form">
                        <form method="post">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control"
                                    value="<?= htmlentities($userData['FullName']); ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control"
                                    value="<?= htmlentities($userData['Email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="phone" class="form-control"
                                    value="<?= htmlentities($userData['MobileNumber']); ?>" required>
                            </div>


                            <div class="form-group">
                                <select name="vehicle" class="form-control" required>
                                    <option value="">Select Vehicle Type</option>
                                    <option value="Two Wheeler">Two Wheeler</option>
                                    <option value="Four Wheeler">Four Wheeler</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="plan" class="form-control" required>
                                    <option value="">Select Washing Plan</option>
                                    <option value="Basic Wash" <?= ($selectedPlan == 'Basic Wash') ? 'selected' : '' ?>>Basic Wash(₹
                                        1000)</option>
                                    <option value="Premium Wash" <?= ($selectedPlan == 'Premium Wash') ? 'selected' : '' ?>>Premium Wash(₹
                                        1800)</option>
                                    <option value="Interior Detailing" <?= ($selectedPlan == 'Interior Detailing') ? 'selected' : '' ?>>Interior Detailing(₹
                                        2700)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="date" name="date" class="form-control" required min="<?= date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                <select name="time" class="form-control" required>
                                    <option value="">Select Time Slot</option>
                                    <?php
                                    $start = strtotime('09:00');
                                    $end   = strtotime('21:00');
                                    while ($start < $end) {
                                        $slot = date('H:i', $start);
                                        echo "<option value='$slot'>$slot</option>";
                                        $start = strtotime('+30 minutes', $start);
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="location" class="form-control" required>
                                    <option value="">Select Location</option>
                                    <?php foreach ($locations as $loc): ?>
                                        <option value="<?= htmlentities($loc->washingPointName); ?>">
                                            <?= htmlentities($loc->washingPointName); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea name="message" class="form-control" placeholder="Additional Message (optional)"></textarea>
                            </div>
                            <button type="submit" name="submit" class="btn btn-custom">Book Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>