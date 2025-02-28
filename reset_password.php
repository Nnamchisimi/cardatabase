<?php
include('db_connection.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if (isset($_POST['reset_password'])) {
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $row = $result->fetch_assoc();
            $email = $row['email'];

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $new_password, $email);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            echo "Password reset successfully!";
        }
    } else {
        echo "Invalid or expired token!";
    }
} else {
    echo "No token provided!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
</head>
<body>
<h2>Reset Password</h2>
<form method="POST" action="">
    <label>New Password:</label>
    <input type="password" name="new_password" required>
    <button type="submit" name="reset_password">Reset Password</button>
</form>
</body>
</html>
