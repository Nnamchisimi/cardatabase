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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: url('static/images/vl6967y3.png');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
        }

        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9); 
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.3rem;
            margin-bottom: 20px;
        }

        label {
            font-size: 1rem;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        p {
            font-size: 1rem;
            color: #666;
        }

        a {
            color: #2196F3;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            margin-top: 10px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }

    </style>
    
</head>


<div class="container">
    <div class="form-container">
        <h1>SERHAN KOMBOS OTOMOTIV</h1>
        <h2>Reset Password</h2>

        <?php if (isset($error_message)): ?>
            <div class="message error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="message success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <label for="">Enter Your New Password:</label>
            <input type="password" name="new_password" required><br><br>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
        <p>Back To Login? <a href="login.php">Login here</a></p>
        <p>Back to Sign up? <a href="signup.php">Sign-Up here</a></p>
    </div>
</div>

</body>


</html>