<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Dashboard</h2>
        <div class="list-group">
            <!-- Link to Telemail Management -->
            <a href="telemail.php" class="list-group-item list-group-item-action">Telemail Management</a>

            <!-- Link to Money Order Management -->
            <a href="money-order-management.php" class="list-group-item list-group-item-action">Money Order Management</a>

            <!-- Link to OMT Management -->
            <a href="omt-management.php" class="list-group-item list-group-item-action">OMT Management</a>

            <!-- Link to User Management -->
            <a href="user-management.php" class="list-group-item list-group-item-action">User Management</a>
        </div>

        <!-- Logout button -->
        <form action="logout.php" method="post">
            <button type="submit" class="btn btn-danger mt-3">Logout</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
