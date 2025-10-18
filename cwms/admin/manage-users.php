<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

// Delete user
if (isset($_GET['del']) && is_numeric($_GET['del'])) {
    $id = intval($_GET['del']);
    $sql = "DELETE FROM tblusers WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $_SESSION['msg'] = "User deleted successfully.";
    header('location:manage-users.php');
    exit;
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Registered Users | CWMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel='stylesheet'/>
    <link href="css/style.css" rel='stylesheet'/>
    <link href="css/font-awesome.css" rel="stylesheet">
    <style>
        .search-box { max-width: 300px; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="page-container">
    <div class="left-content">
        <div class="mother-grid-inner">
            <?php include('includes/header.php'); ?>

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a> <i class="fa fa-angle-right"></i> Registered Users</li>
            </ol>

            <div class="agile-grids">
                <div class="agile-tables">
                    <div class="w3l-table-info">
                        <h2>Registered Users</h2>

                        <!-- Search Box -->
                        <input type="text" id="searchInput" class="form-control search-box" placeholder="Search by name, email, phone">

                        <?php if (isset($_SESSION['msg'])): ?>
                            <div class="alert alert-success"><?= htmlentities($_SESSION['msg']); unset($_SESSION['msg']); ?></div>
                        <?php endif; ?>

                        <table class="table table-bordered table-striped" id="usersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Reg Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "SELECT id, fullName, email, mobileNumber, regDate FROM tblusers ORDER BY regDate DESC";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;

                            if ($query->rowCount() > 0) {
                                foreach ($results as $row) {
                            ?>
                                <tr>
                                    <td><?= $cnt++; ?></td>
                                    <td><?= htmlentities($row->fullName); ?></td>
                                    <td><?= htmlentities($row->email); ?></td>
                                    <td><?= htmlentities($row->mobileNumber); ?></td>
                                    <td><?= htmlentities($row->regDate); ?></td>
                                    <td>
                                        <a href="manage-users.php?del=<?= $row->id; ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to delete this user?');">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php } } else { ?>
                                <tr><td colspan="6" class="text-center">No registered users found.</td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>
        </div>
    </div>

    <?php include('includes/sidebarmenu.php'); ?>
</div>

<script src="js/jquery-2.1.4.min.js"></script>
<script>
    // Live search filter
    document.getElementById("searchInput").addEventListener("keyup", function () {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#usersTable tbody tr");

        rows.forEach(row => {
            let match = false;
            row.querySelectorAll("td").forEach(cell => {
                if (cell.textContent.toLowerCase().includes(value)) {
                    match = true;
                }
            });
            row.style.display = match ? "" : "none";
        });
    });
</script>
</body>
</html>
