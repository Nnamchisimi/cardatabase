<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in to view car details.";
    exit;
}
$username = $_SESSION['username'] ?? 'User';  // Default to 'User' if username is not set

// Retrieve the user's role
$role = $_SESSION['role'] ?? 'user'; // Default to 'user' if role is not set

// Determine where to redirect based on role
$viewCarsLink = ($role === 'admin') ? 'display.php' : 'userdisplay.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Management System</title>
    <link rel="stylesheet" href="styles.css"> <!-- Keep this if you want additional external styles -->
    <style>
        /* Inline CSS for Background Image */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: url('static/images/vl6967y3.png'); /* Path to your background image */
            background-size: cover; /* Ensures the image covers the entire screen */
            background-position: center center; /* Centers the image */
            background-attachment: fixed; /* Keeps the image fixed when scrolling */
        }

        /* Container settings */
        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Home container (central content) */
        .home-container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Ensure it doesn't stretch too wide */
        }

        /* Header text */
        h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #333;
        }
          /* Header text */
          h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

        /* Paragraph text */
        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #666;
        }

        /* Button container styles */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        /* Common button styles */
        .btn-home {
            display: inline-block;
            text-decoration: none;
            padding: 12px 24px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            transition: all 0.3s ease;
        }

        /* Add car button styles */
        .add-car-btn {
            background-color: #4CAF50; /* Green for adding a car */
        }

        /* View car details button styles */
        .view-cars-btn {
            background-color: #2196F3; /* Blue for viewing car details */
        }

        /* Sign out button styles */
        .signout-btn {
            background-color: #d9534f; /* Red for signing out */
        }

        /* Hover effects */
        .btn-home:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

        /* Responsive styles for mobile */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            p {
                font-size: 1rem;
            }

            .home-container {
                padding: 20px;
            }

            .btn-home {
                font-size: 1rem;
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="home-container">
             <h2>Hello <?php echo htmlspecialchars($username); ?>!</h2>
            <h1>Welcome to the Car Management System</h1>
            <p>Manage your car details with ease. Select an option below:</p>
            
            <div class="button-container">
                <a href="index.php" class="btn-home add-car-btn">➕ Add New Car</a>
                <a href="<?php echo $viewCarsLink; ?>" class="btn-home view-cars-btn">🚗 View Car Details</a>
                <a href="login.php" class="btn-home signout-btn">🚪 Sign Out</a>
            </div>
        </div>
    </div>
</body>
</html>
