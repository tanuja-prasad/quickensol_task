<?php
session_start();


$servername = "localhost";
$username = "root";  
$password = "root123";     
$dbname = "taskdb";  

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $new_password)) {
        $message = "New password must be at least 8 characters long and include an uppercase letter, a lowercase letter, and a number.";
    } elseif ($new_password !== $confirm_password) {
        $message = "New password and confirm password do not match.";
    } else {
        // Check if current password matches the one in the database
        $check_query = "SELECT * FROM users WHERE username='{$_SESSION['username']}'";
        $result = $conn->query($check_query);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($current_password, $user['password'])) {
                
                $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update user's password in the database
                $update_query = "UPDATE users SET password='$hashed_new_password' WHERE username='{$_SESSION['username']}'";

                if ($conn->query($update_query) === TRUE) {
                    $message = "Password changed successfully!";
                } else {
                    $message = "Error changing password: " . $conn->error;
                }
            } else {
                $message = "Current password is incorrect.";
            }
        } else {
            $message = "User not found.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Change Password</title>
    <script>
        window.onload = function() {
            var message = "<?php echo $message; ?>";
            if (message.trim() !== "") {
                alert(message);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 mx-auto mt-5 shadow p-3">
                <h2>Change Password</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="Input1" class="form-label">Current Password: </label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="mb-3">
                        <label for="Input2" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="mb-3">
                        <label for="Input2" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
