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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_POST['email'];
    $password = $_POST['password'];

    
    $check_query = "SELECT * FROM users WHERE email='$user_email'";
    $result = $conn->query($check_query);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $message = 'Logged in successful!!!';
            header("Location: update_profile.php");
            exit();
        } else {
            $message = "Incorrect password";
        }
    } else {
        echo "User not found";
    }
}

$conn->close();
?>