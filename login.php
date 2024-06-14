<?php
// Retrieve POST data from login form
$email = $_POST['email'];
$password = $_POST['password'];

// Perform server-side validation (replace with actual database or user storage logic)
$registeredUsers = array(
    array('email' => 'petrometi2001@gmail.com', 'password' => '*vi0716211054#')
    // Add more registered users as needed
);

// Check if user credentials are valid
$authenticated = false;
foreach ($registeredUsers as $user) {
    if ($user['email'] === $email && $user['password'] === $password) {
        $authenticated = true;
        break;
    }
}

// Return JSON response
if ($authenticated) {
    // If authentication is successful, send a success message
    echo json_encode(array('success' => true, 'message' => 'Login successful.'));
} else {
    // If authentication fails, send an error message
    echo json_encode(array('success' => false, 'message' => 'Invalid email or password.'));
}
?>
