<?php
// Start a session to check for user authentication
session_start();

// Check if the user is not logged in; if so, redirect to the login page
if (!isset($_SESSION['id'])) {
    header('location: login.php'); // Replace 'login.php' with the appropriate URL
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['telemail_id'], $_POST['new_status_id'])) {
    // Get Telemail ID and new status ID from POST data
    $telemailIdToUpdate = $_POST['telemail_id'];
    $newStatusIdToUpdate = $_POST['new_status_id'];

    // Update the Telemail status in the database
    $sqlUpdateStatus = 'UPDATE telemails SET status = ? WHERE id = ?';

    if ($stmt = $mysqli->prepare($sqlUpdateStatus)) {
        $stmt->bind_param('ii', $newStatusIdToUpdate, $telemailIdToUpdate);
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


// Fetch Telemail records for the specific post office
if (isset($postOfficeId)) {
    $sqlTelemails = 'SELECT id, sender_name, sender_address, receiver_name, receiver_address, status, message FROM telemails WHERE receiver_post_office = ?';
    $telemails = [];

    if ($stmt = $mysqli->prepare($sqlTelemails)) {
        $stmt->bind_param('i', $postOfficeId);
        if ($stmt->execute()) {
            $stmt->store_result();
            $stmt->bind_result($telemailId, $senderName, $senderAddress, $receiverName, $receiverAddress, $statusId, $message);

            while ($stmt->fetch()) {
                $telemails[] = [
                    'id' => $telemailId,
                    'sender_name' => $senderName,
                    'sender_address' => $senderAddress,
                    'receiver_name' => $receiverName,
                    'receiver_address' => $receiverAddress,
                    'status_id' => $statusId,
                    'message' => $message,
                ];
            }
        } else {
            $errorMessage = 'Error while fetching Telemail records.';
        }
    } else {
        $errorMessage = 'Error while preparing Telemail query.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telemail Management</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Telemail Management</h2>
        <p>Post Office: <?php echo isset($postOfficeId) ? $postOfficeId : 'Not found'; ?></p>

        <?php if (isset($telemails) && !empty($telemails)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sender Name</th>
                        <th>Sender Address</th>
                        <th>Receiver Name</th>
                        <th>Receiver Address</th>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($telemails as $telemail): ?>
                        <tr>
                            <td><?php echo $telemail['id']; ?></td>
                            <td><?php echo $telemail['sender_name']; ?></td>
                            <td><?php echo $telemail['sender_address']; ?></td>
                            <td><?php echo $telemail['receiver_name']; ?></td>
                            <td><?php echo $telemail['receiver_address']; ?></td>
                            <td>
                                <!-- Display the current status -->
                                <?php echo $telemail['status_id'] == '1' ? 'Pending' : 'Delivered'; ?>
                            </td>
                            <td><?php echo $telemail['message']; ?></td>
                            <td>
                                <!-- Form to update status (submit normally) -->
                                <form method="post">
                                    <input type="hidden" name="telemail_id" value="<?php echo $telemail['id']; ?>">
                                    <select class="form-control" name="new_status_id" onchange="this.form.submit()">
                                        <option value="1" <?php echo $telemail['status_id'] == '1' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="2" <?php echo $telemail['status_id'] == '2' ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No Telemail records found for this post office.</p>
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

    <!-- JavaScript function to update Telemail status -->
    <script>
        function updateStatus(selectElement, telemailId) {
            const newStatusId = selectElement.value;

            // Send an AJAX request to update the status
            $.ajax({
                type: 'POST',
                url: '/post-office/dashboard/telemail', 
                data: { telemailId: telemailId, newStatusId: newStatusId },
                success: function(response) {
                    // You can handle the response (e.g., display a success message)
                    console.log('Status updated successfully');
                },
                error: function(error) {
                    // Handle any errors
                    console.error('Error updating status: ' + error);
                }
            });
        }
    </script>
</body>
</html>
