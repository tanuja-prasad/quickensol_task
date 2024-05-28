<?php

$servername = "localhost";
$username = "root";  
$password = "root123";    
$dbname = "taskdb";  

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $email =$_POST['email'];
    $pass = $_POST['password'];

    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    if (!preg_match("/^[a-zA-Z0-9]{3,}$/", $user)) {
        die("Username must be at least 3 characters long and contain only letters and numbers.");
    }

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $pass)) {
        die("Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, and a number.");
    }

    
    $check_query = "SELECT * FROM users WHERE username='$user' OR email='$email'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        die("Username or email already exists");
    }

    
    $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

    
    $insert_query = "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$hashed_password')";

    if ($conn->query($insert_query) === TRUE) {
        header("Location: login.html");
        $message= "Registration successful!";
        exit();
        
    } else {
        $message= "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    
</body>
</html>
