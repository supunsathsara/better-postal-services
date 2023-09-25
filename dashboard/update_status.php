<?php
// Include the database connection file
include('db_con.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the Telemail ID and new status ID from the POST data
    $telemailId = $_POST['telemailId'];
    $newStatusId = $_POST['newStatusId'];

    // Update the Telemail status in the database
    $sqlUpdateStatus = 'UPDATE telemails SET status = ? WHERE id = ?';

    if ($stmt = $mysqli->prepare($sqlUpdateStatus)) {
        $stmt->bind_param('ii', $newStatusId, $telemailId);
        if ($stmt->execute()) {
            // Status updated successfully
            echo 'Status updated successfully';
        } else {
            // Error updating status
            echo 'Error updating status. Please try again later.';
        }

        $stmt->close();
    } else {
        // Error preparing the update statement
        echo 'Error preparing the update statement.';
    }
} else {
    // Invalid request method
    echo 'Invalid request method.';
}
?>
