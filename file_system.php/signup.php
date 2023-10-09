<?php
require_once('includes/db_connect.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input data
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if required fields are not empty
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $response['success'] = false;
        $response['message'] = 'Please fill in all the required fields.';
    } else {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['success'] = false;
            $response['message'] = 'Invalid email format.';
        } else {
            // Check if password and confirm password match
            if ($password !== $confirmPassword) {
                $response['success'] = false;
                $response['message'] = 'Password and Confirm Password do not match.';
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert user data into the database
                $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
                if (mysqli_query($conn, $query)) {
                    $response['success'] = true;
                    $response['message'] = 'User registered successfully.';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Error: ' . mysqli_error($conn);
                }
            }
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid request.';
}

echo json_encode($response);
mysqli_close($conn);
?>
