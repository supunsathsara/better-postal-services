<?php
include('db_con.php');

// Initialize variables to store user input and error messages
$email = $password = $confirm_password = $name = '';
$email_error = $password_error = $confirm_password_error = $name_error = '';

// Process the form when it's submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_error = 'Please enter your email.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate name
    if (empty(trim($_POST['name']))) {
        $name_error = 'Please enter your name.';
    } else {
        $name = trim($_POST['name']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_error = 'Please enter a password.';
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_error = 'Password must have at least 6 characters.';
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_error = 'Please confirm your password.';
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($password != $confirm_password) {
            $confirm_password_error = 'Passwords do not match.';
        }
    }

    if (empty($email_error) && empty($password_error) && empty($confirm_password_error)) {
        $sql = 'INSERT INTO auth (email, password,name) VALUES (?, ?, ?)';
        if ($stmt = $mysqli->prepare($sql)) {
            // Hash the password before storing it in the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Bind variables to the prepared statement as parameters
            $stmt->bind_param('sss', $email, $hashed_password, $name);

            if ($stmt->execute()) {
                // Redirect to a success page or perform other actions
                header('location: success.php');
            } else {
                echo 'Something went wrong. Please try again later.';
            }

            $stmt->close();
        }
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_error; ?></span>
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_error; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_error; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_error; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Sign Up">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
