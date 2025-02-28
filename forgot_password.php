<?php
include('db_connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();

        $reset_link = "http://localhost/cardatabase/reset_password.php?token=$token";
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kombosawb@gmail.com';
            $mail->Password = 'kyka ypey hfar rjvg';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('kombosawb@gmail.com', 'Serhan Kombos');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link to reset your password: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            $success_message = "Password reset link sent to your email.";
        } catch (Exception $e) {
            $error_message = "Email not sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        $error_message = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
<body>

<div class="container">
    <div class="form-container">
        <h1>SERHAN KOMBOS OTOMOTIV</h1>
        <h2>Forgot Your Password?</h2>

        <?php if (isset($error_message)): ?>
            <div class="message error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="message success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <label for="email">Enter your Email Address:</label>
            <input type="email" name="email" required><br><br>
            <button type="submit" name="submit">Send Reset Link</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        <p>Remember your password? <a href="login.php">Login here</a></p>
    </div>
</div>

</body>
</html>
