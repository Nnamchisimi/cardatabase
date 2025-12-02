<?php
session_start();


if (!isset($_SESSION['user_id'])) {
   
    header("Location: login.php");
    exit; 
}

$username = $_SESSION['username'] ?? 'User';  


$role = $_SESSION['role'] ?? 'user'; // Default to 'user' if role is not set


$viewCarsLink = ($role === 'admin') ? 'display.php' : 'userdisplay.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Management System</title>
    <link rel="stylesheet" href="styles.css"> 
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
        .header-with-actions {
    background-color: #000000;
    padding: 20px;
    border-bottom: 2px solid #ccc;
    font-family: Arial, sans-serif;
    position:absolute;
    width:100%;
}

.header-with-actions h1 {
    height: 10px; /* adjust as needed */
    margin: 0 0 10px 0;
    font-size: 28px;
    text-align: center;
    color: white;
}

.header-row {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
}

.page-title {
    grid-column: 2;
    margin: 0;
    font-size: 20px;
    text-align: center;
    color: #444;
}

.action-links {
    grid-column: 3;
    justify-self: end;
    display: flex;
    gap: 15px;
}

.action-link {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    color: #333;
    font-weight: bold;
}

.action-link img {
    height: 20px;
    width: 20px;
    margin-right: 6px;
}
        .action-links a {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 5px;
            transition: background-color 0.5s ease;
        }


        /* Container settings */
        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

   
        .home-container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px; 
        }


        h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #333;
        }
    
          h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #333;
        }

 
        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #666;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

 
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

      
        .add-car-btn {
            background-color: black; 
        }

        .view-cars-btn {
            background-color: black; 
        }

  
        .signout-btn {
            background-color: #d9534f; 

        .btn-home:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

      
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

<header class="header-with-actions">
    <h1>SERHAN KOMBOS OTOMOTIV</h1>
      <a href="login.php?logout=true" class="btn-home signout-btn">
                        <img src="logoutt.png" alt="Log-Out" style="height: 20px; vertical-align: middle; margin-right: 8px;">
                    </a>

</header>
    <div class="container">
        <div class="home-container">
             <h2>Hello <?php echo htmlspecialchars($username); ?>!</h2>
            <h1>Welcome to the Car Management System</h1>
            <p>Manage your car details with ease. Select an option below:</p>
            
            <div class="button-container">
                    <a href="index.php" class="btn-home add-car-btn">
                         <img src="add.png" alt="Add New Car" style="height: 20px; vertical-align: middle; margin-right: 8px;">Add New Car
                    </a>

                    <a href="<?php echo $viewCarsLink; ?>" class="btn-home view-cars-btn">
                        <img src="carr.png" alt="View Car Details" style="height: 20px; vertical-align: middle; margin-right: 8px;">View Car Details
                      </a>
          
                   
            </div>

        </div>
    </div>
</body>
</html>
