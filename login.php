<?php
include('db_con.php');

// Initialize variables to store user input and error messages
$email = $password = $name = '';
$email_error = $password_error = '';

// Process the login form when it's submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_error = 'Please enter your email.';
    } else {
        $email = trim($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_error = 'Please enter your password.';
    } else {
        $password = trim($_POST['password']);
    }

    // If there are no validation errors, check the user's credentials
    if (empty($email_error) && empty($password_error)) {
        $sql = 'SELECT id, email, password, role, name FROM auth WHERE email = ?';

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param('s', $email);
            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $email, $hashed_password, $role, $name);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION['id'] = $id;
                            $_SESSION['email'] = $email;
                            $_SESSION['role'] = $role;
                            $_SESSION['name'] = $name;

                            // Redirect based on the user's role
                            if ($role != 3) {
                                header('location: admin/dashboard.php');
                            } else {
                                header('location: ./');
                            }
                        } else {
                            // Password is not valid
                            $password_error = 'The password you entered is incorrect.';
                        }
                    }
                } else {
                    // No user found with the given email
                    $email_error = 'No account found with that email.';
                }
            } else {
                echo 'Something went wrong. Please try again later.';
            }
            $stmt->close();
        }
    }

    // Close the database connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_error; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_error)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_error; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
