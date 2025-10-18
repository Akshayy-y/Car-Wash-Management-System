<?php
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['userlogin'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['userlogin'];

// Cancel booking
if (isset($_GET['cancel']) && is_numeric($_GET['cancel'])) {
    $bookingId = $_GET['cancel'];
    $cancelQuery = $dbh->prepare("UPDATE tblbooking SET status = 'Cancelled' WHERE id = :id AND userId = :uid AND status = 'New'");
    $cancelQuery->bindParam(':id', $bookingId);
    $cancelQuery->bindParam(':uid', $userId);
    $cancelQuery->execute();
}

// Fetch bookings with location (WashingPointName)
$sql = "SELECT b.*, w.WashingPointName AS Location 
        FROM tblbooking b 
        JOIN tblwashingpoints w ON b.carWashPoint = w.id 
        WHERE b.userId = :uid 
        ORDER BY b.BookingDate DESC";
$query = $dbh->prepare($sql);
$query->bindParam(':uid', $userId);
$query->execute();
$bookings = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings | Drip Lab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap & FontAwesome -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .status-badge.New { background-color: #ffc107; color: #000; }
        .status-badge.Completed { background-color: #28a745; color: #fff; }
        .status-badge.Cancelled { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>

<?php include_once('includes/header.php'); ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-12"><h2>My Bookings</h2></div>
            <div class="col-12">
                <a href="index.php">Home</a>
                <a href="my-bookings.php">Bookings</a>
            </div>
        </div>
    </div>
</div>

<!-- Bookings -->
<div class="contact">
    <div class="container">
        <div class="section-header text-center">
            <p>Your Wash History</p>
            <h2>All Booked Appointments</h2>
            <a href="track-booking.php" class="btn btn-outline-primary mt-3">
                <i class="fas fa-search"></i> Track a Booking
            </a>
        </div>

        <?php if (count($bookings) === 0): ?>
            <div class="alert alert-info text-center">You have not made any bookings yet.</div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($bookings as $booking): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>#<?= htmlentities($booking->id) ?></strong>
                            <span class="badge status-badge <?= htmlentities($booking->status) ?>">
                                <?= htmlentities($booking->status) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <p><strong>Plan:</strong> <?= htmlentities($booking->WashingPlan) ?></p>
                            <p><strong>Date:</strong> <?= htmlentities($booking->WashDate) ?></p>
                            <p><strong>Time:</strong> <?= htmlentities($booking->WashTime) ?></p>
                            <p><strong>Location:</strong> <?= htmlentities($booking->Location) ?></p>
                            <p><strong>Vehicle:</strong> <?= htmlentities($booking->VehicleType) ?></p>
                            <p><strong>Booked On:</strong> <?= htmlentities($booking->BookingDate) ?></p>

                            <?php if ($booking->status === 'New'): ?>
                                <a href="?cancel=<?= $booking->id ?>" class="btn btn-sm btn-danger mt-2" onclick="return confirm('Cancel this booking?')">Cancel</a>
                            <?php endif; ?>

                            <?php if ($booking->status === 'Completed'):
                                $check = $dbh->prepare("SELECT id FROM tblreviews WHERE userId = :uid AND bookingId = :bid");
                                $check->bindParam(':uid', $userId);
                                $check->bindParam(':bid', $booking->id);
                                $check->execute();
                                if ($check->rowCount() == 0): ?>
                                    <button class="btn btn-sm btn-primary mt-2" data-toggle="modal" data-target="#reviewModal<?= $booking->id ?>">
                                        Give Review
                                    </button>

                                    <!-- Review Modal -->
                                    <div class="modal fade" id="reviewModal<?= $booking->id ?>" tabindex="-1" role="dialog">
                                      <div class="modal-dialog" role="document">
                                        <form method="POST" action="submit-review.php">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title">Review Booking #<?= $booking->id ?></h5>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                              <input type="hidden" name="bookingId" value="<?= $booking->id ?>">
                                              <div class="form-group">
                                                <label>Rating (1 to 5)</label>
                                                <select name="rating" class="form-control" required>
                                                  <option value="">Select</option>
                                                  <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <option value="<?= $i ?>"><?= str_repeat("★", $i) . str_repeat("☆", 5 - $i) ?></option>
                                                  <?php endfor; ?>
                                                </select>
                                              </div>
                                              <div class="form-group">
                                                <label>Review</label>
                                                <textarea name="review" class="form-control" rows="3" required></textarea>
                                              </div>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="submit" name="submitReview" class="btn btn-success">Submit</button>
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            </div>
                                          </div>
                                        </form>
                                      </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
