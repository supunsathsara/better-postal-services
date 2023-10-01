<?php
// Start a session to check for user authentication
session_start();

// Check if the user is not logged in; if so, redirect to the login page
if (!isset($_SESSION['id'])) {
    header('location: login.php'); 
    exit();
}

// Include the database connection file
include('../db_con.php');

// Get the post_office ID for the logged-in post master
$postMasterId = $_SESSION['id'];
$sqlPostOffice = 'SELECT post_office FROM post_masters WHERE id = ? LIMIT 1';

if ($stmt = $mysqli->prepare($sqlPostOffice)) {
    $stmt->bind_param('i', $postMasterId);
    if ($stmt->execute()) {
        $stmt->bind_result($postOfficeId);
        $stmt->fetch();
        $stmt->close();
    } else {
        $errorMessage = 'Error while fetching post office details.';
    }
} else {
    $errorMessage = 'Error while preparing post office query.';
}

// Check if the form is submitted for status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['money_order_id'], $_POST['new_status_id'])) {
    // Get Money Order ID and new status ID from POST data
    $moneyOrderIdToUpdate = $_POST['money_order_id'];
    $newStatusIdToUpdate = $_POST['new_status_id'];

    // Update the Money Order status in the database
    $sqlUpdateStatus = 'UPDATE money_order SET status = ? WHERE id = ?';

    if ($stmt = $mysqli->prepare($sqlUpdateStatus)) {
        $stmt->bind_param('ii', $newStatusIdToUpdate, $moneyOrderIdToUpdate);
        if ($stmt->execute()) {
            // Status updated successfully
            $updateSuccessMessage = 'Status updated successfully';
        } else {
            // Error updating status
            $updateErrorMessage = 'Error updating status. Please try again later.';
        }

        $stmt->close();
    } else {
        // Error preparing the update statement
        $updateErrorMessage = 'Error preparing the update statement.';
    }
}

// Fetch Money Order records for the specific post office
if (isset($postOfficeId)) {
    $sqlMoneyOrders = 'SELECT id, sender_name, sender_address, receiver_name, receiver_address, amount, submitted_date, status FROM money_order WHERE receiver_post_office = ?';
    $moneyOrders = [];

    if ($stmt = $mysqli->prepare($sqlMoneyOrders)) {
        $stmt->bind_param('i', $postOfficeId);
        if ($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($orderId, $senderName, $senderAddress, $receiverName, $receiverAddress, $amount, $submittedDate, $statusId);

            while ($stmt->fetch()) {
                $moneyOrders[] = [
                    'id' => $orderId,
                    'sender_name' => $senderName,
                    'sender_address' => $senderAddress,
                    'receiver_name' => $receiverName,
                    'receiver_address' => $receiverAddress,
                    'amount' => $amount,
                    'submitted_date' => $submittedDate,
                    'status_id' => $statusId
                ];
            }
        } else {
            $errorMessage = 'Error while fetching Money Order records.';
        }
    } else {
        $errorMessage = 'Error while preparing Money Order query.';
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Order Management</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Money Order Management</h2>
        <p>Post Office: <?php echo isset($postOfficeId) ? $postOfficeId : 'Not found'; ?></p>

        <?php if (isset($moneyOrders) && !empty($moneyOrders)) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sender Name</th>
                        <th>Sender Address</th>
                        <th>Receiver Name</th>
                        <th>Receiver Address</th>
                        <th>Amount</th>
                        <th>Submitted Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($moneyOrders as $order) : ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['sender_name']; ?></td>
                            <td><?php echo $order['sender_address']; ?></td>
                            <td><?php echo $order['receiver_name']; ?></td>
                            <td><?php echo $order['receiver_address']; ?></td>
                            <td><?php echo $order['amount']; ?></td>
                            <td><?php echo $order['submitted_date']; ?></td>
                            <td>
                                <!-- Form to update status (submit normally) -->
                                <form method="post">
                                    <input type="hidden" name="money_order_id" value="<?php echo $order['id']; ?>">
                                    <select class="form-control" name="new_status_id" onchange="this.form.submit()">
                                        <option value="1" <?php echo $order['status_id'] == '1' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="2" <?php echo $order['status_id'] == '2' ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No Money Order records found for this post office.</p>
        <?php endif; ?>

         <!-- Display status update messages -->
         <?php if (isset($updateSuccessMessage)): ?>
            <div class="alert alert-success">
                <?php echo $updateSuccessMessage; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($updateErrorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo $updateErrorMessage; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>