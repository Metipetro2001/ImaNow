<?php
session_start();

// Check if form is submitted for user registration process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $firstName = validateInput($_POST['firstName']);
    $lastName = validateInput($_POST['lastName']);
    $country = validateInput($_POST['country']);
    $phoneNumber = validateInput($_POST['phoneNumber']);
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash password for security
    $humanVerification = validateInput($_POST['humanVerification']);

    // Validate human verification
    if ($humanVerification !== '4') {
        echo json_encode(array("message" => "Incorrect human verification answer. Please try again."));
        exit();
    }

    // Create connection to your database (replace with your database credentials)
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "imadatabase";
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input data
    $firstName = $conn->real_escape_string($firstName);
    $lastName = $conn->real_escape_string($lastName);
    $country = $conn->real_escape_string($country);
    $phoneNumber = $conn->real_escape_string($phoneNumber);
    $email = $conn->real_escape_string($email);

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(array("message" => "Account with this email address already exists."));
        exit();
    }

    // Check if IP address already has an account
    $sql = "SELECT * FROM users WHERE ip_address = '$_SERVER[REMOTE_ADDR]'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(array("message" => "You have already registered from this IP address."));
        exit();
    }

    // Insert new user into database
    $sql = "INSERT INTO users (first_name, last_name, country, phone_number, email, password, ip_address)
            VALUES ('$firstName', '$lastName', '$country', '$phoneNumber', '$email', '$hashedPassword', '$_SERVER[REMOTE_ADDR]')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Registration successful. You can now log in."));
    } else {
        echo json_encode(array("message" => "Error: " . $sql . "<br>" . $conn->error));
    }

    $conn->close();
}

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
