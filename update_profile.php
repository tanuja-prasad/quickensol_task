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
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];

    
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format";
    } elseif (!preg_match("/^[a-zA-Z0-9]{3,}$/", $new_username)) {
        $message = "Username must be at least 3 characters long and contain only letters and numbers.";
    } else {
        
        $update_query = "UPDATE users SET username='$new_username', email='$new_email' WHERE username='{$_SESSION['username']}'";

        if ($conn->query($update_query) === TRUE) {
            $_SESSION['username'] = $new_username;
            $_SESSION['email'] = $new_email;

            $message = "Profile updated successfully!";
            header("Location: change_pwd.php");
            exit();
        } else {
            $message = "Error updating profile: " . $conn->error;
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
    <title>Update Profile</title>
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
                <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
                <h3>Update Profile</h3>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="Input1" class="form-label">Username: </label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $_SESSION['username']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Input2" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email']; ?>">
                    </div>
                   
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
