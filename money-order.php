<?php
// Start a session to check for user authentication
session_start();

// Check if the user is not logged in; if so, redirect to the login page
if (!isset($_SESSION['id'])) {
    header('location: login.php'); 
    exit();
}

// Include the database connection file
include('db_con.php');


$submissionSuccess = false;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $senderName = $_POST['sender_name'];
    $senderAddress = $_POST['sender_address'];
    $receiverName = $_POST['receiver_name'];
    $receiverAddress = $_POST['receiver_address'];
    $postOfficeId = $_POST['post_office'];
    $amount = $_POST['amount'];

    // Get user ID from session
    $userId = $_SESSION['id'];

    // Insert money order data into the database
    $sqlInsertMoneyOrder = 'INSERT INTO money_order (user, sender_name, sender_address, receiver_name, receiver_address, receiver_post_office, amount)
                            VALUES (?, ?, ?, ?, ?, ?, ?)';

    if ($stmt = $mysqli->prepare($sqlInsertMoneyOrder)) {
        $stmt->bind_param('issssid', $userId, $senderName, $senderAddress, $receiverName, $receiverAddress, $postOfficeId, $amount);
        if ($stmt->execute()) {
            $submissionSuccess = true;
        } else {
            // Error while inserting money order data
            $errorMessage = 'Error while submitting money order. Please try again later.';
        }

        $stmt->close();
    } else {
        // Error preparing the insert statement
        $errorMessage = 'Error while preparing money order submission.';
    }
}

// Display error message if there was an issue with the form submission
if (isset($errorMessage)) {
    // You can redirect to an error page or display the error message on the same page
    echo $errorMessage;
}


// Retrieve post offices for the dropdown list
$postOfficeQuery = 'SELECT id, post_office, post_code FROM post_offices';
$postOfficeResult = $mysqli->query($postOfficeQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Money Order Form</h2>

        <?php if ($submissionSuccess): ?>
            <div class="alert alert-success">
                Money-Order submitted successfully!
            </div>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <!-- Money Order Form -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label>Sender Name</label>
                <input type="text" class="form-control" name="sender_name" required>
            </div>
            <div class="form-group">
                <label>Sender Address</label>
                <input type="text" class="form-control" name="sender_address" required>
            </div>
            <div class="form-group">
                <label>Receiver Name</label>
                <input type="text" class="form-control" name="receiver_name" required>
            </div>
            <div class="form-group">
                <label>Receiver Address</label>
                <input type="text" class="form-control" name="receiver_address" required>
            </div>
            <div class="form-group">
                <label>Post Office</label>
                <select name="post_office" class="form-control" required>
                    <option value="" selected disabled>Select a Post Office</option>
                    <?php while ($row = $postOfficeResult->fetch_assoc()) : ?>
                        <option value="<?php echo $row['id']; ?>" data-post-code="<?php echo $row['post_code']; ?>"><?php echo $row['post_office']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Post Code</label>
                <input type="text" name="post_code" class="form-control" disabled>
            </div>
            <div class="form-group">
                <label>Amount</label>
                <input type="number" class="form-control" name="amount" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Money Order</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript to update the Post Code field when a Post Office is selected
        document.querySelector('select[name="post_office"]').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const postCodeField = document.querySelector('input[name="post_code"]');
            postCodeField.value = selectedOption.getAttribute('data-post-code');
        });
    </script>
</body>
</html>
