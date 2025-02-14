<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            header("Location: home.php");

            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f3f4f6;
            font-family: 'Poppins', sans-serif;
        }

        /* Centered Wrapper */
        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }

        /* Login Container Styling */
        .login-container {
            align-items: center;
            display: flex;
            gap: 10px;
            background-color: #e2e6eb;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 50%;
        }

        .plant-image {
            width: 300px;
            height: 300px;
            background-image: url("images/login image.png"); /* Replace with actual image path */
            background-size: cover;
            background-position: center;
        }

        .login-form {
            flex: 1;
            max-width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: auto;
        }

        .login-form h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .login-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .login-form input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-form button {
            background-color: #007a33;
            color: #fff;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-form button:hover {
            background-color: #005922;
        }

        p {
            font-size: 15px;
            color: black;
            margin-top: 10px;
        }

        .link {
            text-decoration: none;
            color: #0917ee;
            font-weight: bold;
        }

    </style>

</head>

<body>
    <div class="wrapper">
        <div class="login-container">
            <div class="plant-image"></div>
            <div class="login-form">
                <h2>Welcome Back</h2>
                <form action="login.php" method="post">
                    <input type="email" name="email" placeholder="Email" required><br>
                    <input type="password" name="password" placeholder="Password" required><br>
                    <button type="submit" name="login">Login</button>
                </form>
                <p><a class="Forgot_Password" href="forgotpw.php">Forgot Password?</a> &ensp; <a class ="link" href="forgotpw.php">Reset here</a>.</p></p>
                <p><h class="p1">Haven't an account yet? &ensp; <a class="link" href="register.php">Register here</a>.</p>
            </div>   
        </div>
    </div> 
</body>

</html>