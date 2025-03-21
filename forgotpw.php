<?php
session_start();
ob_start();
include 'config.php';
include 'navbar.php';

function isValidPassword($password)
{
    return preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password);
}

if (isset($_POST['submit_email'])) {
    $email = $_POST['email'];
    if (empty($email)) {
        echo "<script>
        window.onload = function() {
            showAlert('Please enter your email!', 'error', '#ff0000');
        };
        </script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
        window.onload = function() {
            showAlert('Please enter valid email!', 'error', '#ff0000');
        };
        </script>";
    } else {

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['reset_email'] = $email;
            header("Location: forgotpw.php?reset_password=true");
            exit;
        } else {
            echo "<script>
        window.onload = function() {
            showAlert('Email not found!', 'error', '#ff0000');
        };
        </script>";
        }
    }
}

if (isset($_POST['reset_password'])) {
    if (isset($_SESSION['reset_email'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];
        if (empty($new_password)) {
            echo "<script>
            window.onload = function() {
                showAlert('Please enter new password!', 'error', '#ff0000');
            };
            </script>";
        } elseif (!isValidPassword($_POST['new_password'])) {
            echo "<script>
            window.onload = function() {
                showAlert('Password must contain at least 8 characters, one uppercase letter, one number and one special character!', 'error', '#ff0000');
            };
            
            </script>";
        } else {
            $sql = "UPDATE users SET password = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $new_password, $email);
            $stmt->execute();

            unset($_SESSION['reset_email']);
            echo "<script>
        window.onload = function() {
            showAlert('Password has been reset', 'success', '#008000', 'login.php');
        };
        setTimeout(function() {
            window.location.href = 'login.php';
        }, 2000);
        </script>";
        }
    } else {
        echo "<script>
        window.onload = function() {
            showAlert('Session expired or no reset request found', 'warning', '#ff0000');
        };
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - AgroMart</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Poppins', Arial, sans-serif;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        position: relative;
    }

    /* Add background image */
    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("images/B1.jpg");
        background-size: cover;
        opacity: 0.5;
        z-index: -1;
    }

    /* Centered Wrapper */
    .wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 20px;
        width: 75%;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .login-box {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        text-align: center;
    }

    .login-box h2 {
        font-size: 1.5rem;
        margin-bottom: 20px;
        text-transform: capitalize;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .form-group label {
        display: block;
        font-size: 1rem;
        color: #333;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus {
        border-color: #f09319;
    }

    .form-group input::placeholder {
        color: #888;
        font-style: italic;
        font-size: 0.9rem;
    }

    button {
        background-color: #007a33;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.2s ease;
    }

    button:hover {
        background-color: #005922;
    }

    .links {
        margin-top: 20px;
        font-size: 0.9rem;
        color: #666;
    }

    .links a {
        color: #006400;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .links a:hover {
        color: #f09319;
    }

    /* Mobile Devices */
    @media screen and (max-width: 480px) {
        .wrapper {
            width: 95%;
            min-height: 60vh;
            padding: 10px;
        }

        .login-box {
            padding: 20px;
        }

        .login-box h2 {
            font-size: 1.2rem;
        }

        .form-group label {
            font-size: 0.9rem;
        }

        .form-group input {
            font-size: 0.9rem;
            padding: 8px;
        }

        button {
            font-size: 0.9rem;
            padding: 8px 15px;
        }

        .links {
            font-size: 0.85rem;
        }
    }

    #icon {
        color: rgb(114, 114, 114);
        margin: 7px 7px 7px 0px;
    }

    span {
        font-size: 0.8rem;
        color: rgb(114, 114, 114);
        align-self: flex-start;
        margin-bottom: 10px;
    }

    /* Tablets */
    @media screen and (min-width: 481px) and (max-width: 1200px) {
        .wrapper {
            width: 85%;
            min-height: 60vh;
        }

        .login-box {
            padding: 25px;
        }

        .login-box h2 {
            font-size: 1.4rem;
        }

        .form-group label {
            font-size: 0.95rem;
        }

        .form-group input {
            font-size: 0.95rem;
            padding: 9px;
        }

        button {
            font-size: 0.95rem;
            padding: 9px 18px;
        }
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="login-box">
            <h2>Forgot Password</h2>
            <?php if (!isset($_GET['reset_password'])): ?>
            <form action="forgotpw.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="Enter your email">
                </div>
                <button type="submit" name="submit_email">Submit</button>
                <div class="links">
                    <p>Remembered your password? <a href="login.php">Login here</a></p>
                </div>
            </form>
            <?php else: ?>
            <form action="forgotpw.php" method="POST">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password">
                    <span> <i class="fa fa-exclamation-circle" aria-hidden="true" id="icon"></i>Password must contain at
                        least 8
                        characters, one
                        uppercase letter, one number and one special character</span>
                </div>
                <button type="submit" name="reset_password">Reset Password</button>
                <div class="links">
                    <p>Back to <a href="login.php">Login</a></p>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src='alertFunction.js'></script>
</body>

</html>