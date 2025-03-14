<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = null;
}




// Query to count unread notifications
$sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE user_id = ? AND status = 'unread'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$unread_count = $row['unread_count'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
    /* General navbar styling */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    nav {
        display: flex;
        flex-direction: column;
        width: 100%;
        background-color: #006400;
        /* Green background */
        padding: 15px 12.5%;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* 1 Row */
    .nav-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .logo a {
        font-size: 1.5rem;
        font-weight: bold;
        color: white;
        text-decoration: none;
    }

    .nav-bottom {
        width: 100%;
        margin-top: 10px;
        display: flex;
        justify-content: center;
    }

    .nav-right {
        display: flex;
        list-style: none;
        padding: 0;
    }

    .nav-right li {
        margin: 0 5px;
    }

    .nav-right a {
        text-decoration: none;
        color: white;
        font-weight: bold;
        padding: 10px 15px;
        border-radius: 5px;
        transition: 0.3s;
    }

    .nav-right a:hover {
        background-color: #228B22;
        /* Darker green */
    }

    /* Special styling for Post Ads button */
    .place-ad {
        background-color: #006400;
        animation: blink 2.5s infinite;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.9;
            background-color: rgb(255, 153, 0);
        }

        100% {
            opacity: 1;
        }
    }

    /* Notification Icon */
    .nav-right img {
        width: 24px;
    }

    .badge {
        background: red;
        color: white;
        font-size: 12px;
        padding: 3px 6px;
        border-radius: 50%;
        position: relative;
        top: -10px;
        left: -5px;
    }


    /* 2 row */
    .search-container {
        position: relative;
    }

    .search-container input {
        margin-top: 20px;
        margin-bottom: 20px;
        width: 50vw;
        height: 50px;
        padding: 8px;
        border: none;
        border-radius: 5px;
    }

    /* Search results dropdown */
    .search-results {
        position: absolute;
        top: 35px;
        left: 0;
        width: 250px;
        background: white;
        border: 1px solid #ccc;
        display: none;
    }


    /* Responsive Design */
    @media (max-width: 768px) {
        nav {
            padding: 15px;
        }

        .nav-top {
            flex-direction: column;
            text-align: center;
        }

        .search-container input {
            width: 100%;
        }

        .nav-bottom {
            margin-top: 10px;
        }

        .nav-right {
            flex-wrap: wrap;
            justify-content: center;
            text-align: center;
        }

        .nav-right li {
            margin: 5px;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src='alertFunction.js'></script>

</head>

<body>

    <script>
    function confirmLogout() {
        var confirmAction = confirm("Are you sure you want to log out?");
        if (confirmAction) {
            window.location.href = "logout.php";
        }
    }

    function searchProducts(query) {
        const results = document.getElementById("search-results");
        if (query.length === 0) {
            results.style.display = "none";
            results.innerHTML = "";
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("GET", "search_products.php?q=" + query, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                results.innerHTML = xhr.responseText;
                results.style.display = "block";
            }
        };
        xhr.send();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-container input[type="text"]');
        const searchResults = document.getElementById('search-results');
        const hamburger = document.querySelector('.hamburger');
        const navRight = document.querySelector('.nav-right');

        // Search functionality
        searchInput.addEventListener('focus', () => {
            if (searchInput.value.trim() !== '') {
                searchResults.style.display = 'block';
            }
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.search-container')) {
                searchResults.style.display = 'none';
            }
        });

        searchInput.addEventListener('input', () => {
            if (searchInput.value.trim() !== '') {
                searchResults.style.display = 'block';
            } else {
                searchResults.style.display = 'none';
            }
        });

        // Hamburger menu toggle
        hamburger.addEventListener('click', () => {
            navRight.classList.toggle('active');
        });
    });
    </script>

    <nav>
        <div class="nav-top">
            <div class="logo">
                <a href="home.php">AgroMart</a>
            </div>
            <ul class="nav-right">
                <?php if (!isset($_SESSION['username'])): ?>
                <li><a href="#" onclick="showAlert('Please login to add a product request','warning','red')">Request
                        Products</a></li>
                <li><a href="#" onclick="showAlert('Please login to post an Ad','warning','red')" class="place-ad">POST
                        AD FREE</a></li>
                <li><a href="#" onclick="showAlert('Please login to see Wishlist','warning','red')"><i
                            class="fas fa-heart" title="Wishlist"></i></a></li>
                <li><a href="#" onclick="showAlert('Please login to see Notifications','warning','red')">
                        <i class="fa fa-bell" aria-hidden="true"></i>
                        <?php if ($unread_count > 0): ?>
                        <span class="badge" id="notif_count"><?= $unread_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php else: ?>
                <li><a href="post_request.php">Request Products</a></li>
                <li><a href="post_ad.php" class="place-ad">POST AD FREE</a></li>
                <li><a href="wishlist.php"><i class="fas fa-heart" title="Wishlist"></i></a></li>
                <li><a href="notifications.php">
                        <i class="fa fa-bell" aria-hidden="true"></i>
                        <?php if ($unread_count > 0): ?>
                        <span class="badge" id="notif_count"><?= $unread_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <?php endif; ?>


                <?php if (isset($_SESSION['username'])): ?>
                <li><a href="profile.php"><i class="fas fa-user"></i> &nbsp; Account</a></li>
                <li><a href="logout.php" onclick="confirmLogout()">LogOut</a></li>
                <?php else: ?>
                <li><a href="login.php">LOGIN</a></li>
                <li><a href="register.php">REGISTER</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="nav-bottom">
            <div class="search-container">
                <input type="text" placeholder="Search what you want" onkeyup="searchProducts(this.value)">
                <div class="search-results" id="search-results"></div>
            </div>
        </div>
    </nav>

</body>

</html>