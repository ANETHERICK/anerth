<?php
// Set the content type header as JSON
header('Content-Type: application/json');

require_once('includes/db_connect.php');

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the username and password from the request
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($username) && !empty($password)) {
        // Query the database to retrieve the hashed password for the given username
        $query = "SELECT password FROM users WHERE username = '$username'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['password'];

            // Compare the hashed password with the user input using password_verify()
            if (password_verify($password, $hashedPassword)) {
                // Passwords match, login successful
                $response['success'] = true;
                $response['message'] = 'Login successful';
            } else {
                // Passwords don't match
                $response['success'] = false;
                $response['message'] = 'Invalid username or password';
            }
        } else {
            // No user found with the given username
            $response['success'] = false;
            $response['message'] = 'Invalid username or password';
        }
    } else {
        // Username or password is empty
        $response['success'] = false;
        $response['message'] = 'Please provide both username and password';
    }
} else {
    // Invalid request method
    $response['success'] = false;
    $response['message'] = 'Invalid request.';
}

// Output the JSON response
echo json_encode($response);
exit;
?>
