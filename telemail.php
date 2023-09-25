<?php
// Start a session to check for user authentication
session_start();

// Check if the user is not logged in; if so, redirect to the login page
if (!isset($_SESSION['id'])) {
    header('location: login.php'); // Replace 'login.php' with the appropriate URL
    exit();
}

include('db_con.php');

// Initialize variables for form fields
$senderName = $senderAddress = $receiverName = $receiverAddress = $postOffice = $message= '';
$status = '1'; // Default status when submitting

$submissionSuccess = false;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $senderName = $_POST['sender_name'];
    $senderAddress = $_POST['sender_address'];
    $receiverName = $_POST['receiver_name'];
    $receiverAddress = $_POST['receiver_address'];
    $postOffice = $_POST['post_office'];
    $message = $_POST['message'];

    // Insert the Telemail into the database
    $sql = 'INSERT INTO telemails (user, sender_name, sender_address, receiver_name, receiver_address, receiver_post_office, message)
            VALUES (?, ?, ?, ?, ?, ?, ?)';

    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param('ssssss', $_SESSION['id'], $senderName, $senderAddress, $receiverName, $receiverAddress, $postOffice, $message);

        if ($stmt->execute()) {
            // Telemail submitted successfully, you can redirect to a success page if needed
            //header('location: success.php');
            //display a success message
            $submissionSuccess = true;
        } else {
            $errorMessage = 'Error while submitting Telemail. Please try again later.';
        }

        $stmt->close();
    } else {
        //$errorMessage = 'Error while preparing the Telemail submission.';
        $errorMessage = 'Error while preparing the Telemail submission: ' . $mysqli->error;
    }
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
    <title>Telemail Submission</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Telemail Submission Form</h2>
         <?php if ($submissionSuccess): ?>
            <div class="alert alert-success">
                Telemail submitted successfully!
            </div>
        <?php endif; ?>
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label>Sender Name</label>
                <input type="text" name="sender_name" class="form-control" required value="<?php echo $senderName; ?>">
            </div>
            <div class="form-group">
                <label>Sender Address</label>
                <input type="text" name="sender_address" class="form-control" required value="<?php echo $senderAddress; ?>">
            </div>
            <div class="form-group">
                <label>Receiver Name</label>
                <input type="text" name="receiver_name" class="form-control" required value="<?php echo $receiverName; ?>">
            </div>
            <div class="form-group">
                <label>Receiver Address</label>
                <input type="text" name="receiver_address" class="form-control" required value="<?php echo $receiverAddress; ?>">
            </div>
            <div class="form-group">
                <label>Post Office</label>
                <select name="post_office" class="form-control" required>
                    <option value="" selected disabled>Select a Post Office</option>
                    <<?php while ($row = $postOfficeResult->fetch_assoc()) : ?> <option value="<?php echo $row['id']; ?>" data-post-code="<?php echo $row['post_code']; ?>"><?php echo $row['post_office']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Post Code</label>
                <input type="text" name="post_code" class="form-control" disabled>
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" rows="5" class="form-control" required><?php echo $message; ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit Telemail">
            </div>
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