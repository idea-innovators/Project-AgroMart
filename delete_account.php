<?php
session_start();
include 'config.php';
include 'navbar.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];

    // verify password
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        echo "<script>
        window.onload = function() {
            showAlert('Incorrect password. Account not deleted', 'error', '#ff0000');
        };
        setTimeout(function() {
            window.location.href = 'delete_account.php';
        }, 2000);
        </script>";
        exit();
    }

    // delete user
    $delete_stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $delete_stmt->bind_param("i", $user_id);

    if ($delete_stmt->execute()) {
        session_destroy();
        echo "<script>
        window.onload = function() {
            showAlert('Your account deleted', 'success', '#008000');
        };
        setTimeout(function() {
            window.location.href = 'home.php';
        }, 2000);
        </script>";
    } else {
        echo "<script>
        window.onload = function() {
            showAlert('error deleting account', 'error', '#ff0000');
        };
        setTimeout(function() {
            window.location.href = 'profile.php';
        }, 2000);
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account - AgroMart</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Poppins", Arial, sans-serif;
        position: relative;
        overflow-x: hidden;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

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

    h1 {
        background-color: #dbffc7;
        text-align: center;
        padding: 10px 12.5%;
        font-size: 2rem;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 1;
    }

    .main-content {
        flex: 1;
    }

    .container {
        width: 90%;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 90px);
    }

    form {
        background-color: #e1e1e1;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    p {

        font-size: 1.3rem;
        font-weight: bold;
        color: #b03052;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    .form-group label {
        display: block;
        font-size: 1rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .form-group input[type="password"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .form-group input[type="password"]:focus {
        border-color: #f09319;
    }

    .form-group input[type="password"]::placeholder {
        color: #888;
        font-style: italic;
        font-size: 0.9rem;
    }

    button {
        background-color: #f09319;
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
        background-color: #cb790d;
    }

    /* Mobile Devices*/
    @media screen and (max-width: 480px) {
        h1 {
            font-size: 1.5rem;
            padding: 15px 5%;
        }

        .container {
            width: 95%;
            padding: 10px;
            min-height: calc(100vh - 70px);
        }

        form {
            padding: 15px;
        }

        p {
            font-size: 0.9rem;
        }

        .form-group label {
            font-size: 0.9rem;
        }

        .form-group input[type="password"] {
            font-size: 0.9rem;
            padding: 6px;
        }

        button {
            font-size: 0.9rem;
            padding: 8px 15px;
        }
    }

    /* Tablets*/
    @media screen and (min-width: 481px) and (max-width: 1200px) {
        h1 {
            font-size: 1.8rem;
            padding: 20px 8%;
        }

        .container {
            width: 95%;
            padding: 15px;
        }

        form {
            padding: 20px;
        }

        p {
            font-size: 0.95rem;
        }

        .form-group label {
            font-size: 0.95rem;
        }

        .form-group input[type="password"] {
            font-size: 0.95rem;
            padding: 7px;
        }

        button {
            font-size: 0.95rem;
            padding: 9px 18px;
        }
    }
    </style>
</head>

<body>
    <div class="main-content">
        <h1>Delete My Account</h1>
        <div class="container">
            <form action="delete_account.php" method="post">
                <p>This action cannot be undone. Please confirm your password to proceed.</p>
                <div class="form-group">
                    <label for="password">Enter Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit">Delete Account</button>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src='alertFunction.js'></script>
</body>

</html>