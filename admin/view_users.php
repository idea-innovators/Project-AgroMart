<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];

 
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    if ($stmt->execute()) {
        echo "User deleted successfully!";
    } else {
        echo "Failed to delete user.";
    }
}


$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }

       
        .container {
            max-width: 100%;
            margin: 55px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

      
        h1 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
        }

      
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

      
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            text-align: center;
            background-color: #f2f2f2;
            font-weight: bold;
        }

      
        tr:hover {
            background-color: #f5f5f5;
        }

     
        .delete-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            display: inline-block;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
        }

        
        .delete-button:hover {
            background-color: #d32f2f;
        }

       
        @media screen and (max-width: 768px) {
            .container {
                padding: 15px;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }

            .delete-button {
                font-size: 12px;
                padding: 6px 12px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result->fetch_assoc()) { ?>
                <tr>
                  
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['contact_number']) ?></td>
                    <td><?= htmlspecialchars($user['address']) ?></td>
                    <td>
                        
                        <a href="view_users.php?delete_user=<?= $user['user_id'] ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>