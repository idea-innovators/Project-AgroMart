<?php
session_start();
include 'config.php'; 
include 'navbar.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT ads.*, GROUP_CONCAT(ad_images.image_path) AS images 
        FROM ads 
        LEFT JOIN ad_images ON ads.ad_id = ad_images.ad_id 
        WHERE ads.user_id = ? 
        GROUP BY ads.ad_id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ads</title>
    <style>

    /* Container width */

.title {
  background-color: #dbffc7;
  text-align: left;
  text-transform: capitalize;
  padding: 20px 12.5%;
  font-size: 2.2rem;
}

.container {
  width: 75%; /* Changed to 90% for better responsiveness */
  margin: 0 auto;
}

/* Card layout for ads */
.ads-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
  margin: 35px 0;
}

.ad-card {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 10px;
  overflow: hidden;
  width: calc(25% - 20px); /* 4 cards per row with gap consideration */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  padding: 15px;
}

.ad-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.ad-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  mix-blend-mode: multiply;
  border-radius: 8px;
}

.ad-card h4 {
  font-size: 16px;
  color: #333;
  margin: 10px 0 5px 0;
  font-weight: 600;
  text-transform: capitalize;
}

.ad-card p {
  line-height: 1.4;
  font-size: 14px;
  color: #555;
  text-align: left;
  display: -webkit-box;
  -webkit-line-clamp: 4; /* Limit to 10 lines */
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  margin: 10px 10px;
}

.ad-card .price {
  font-weight: 700;
  color: #b03052;
  margin: 10px 10px;
}

/* Button styles */
.ad-buttons {
  display: flex;
  justify-content: center;
}
.btn {
  text-decoration: none;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  background-color: #28a745;
  color: white;
  cursor: pointer;
  margin: 5px; /* Add margin for spacing */
  display: inline-block; /* To align them in a row */
}

.btn:hover {
  background-color: #218838;
}

.btn-danger {
  background-color: red;
  cursor: pointer;
}

.btn-danger:hover {
  background-color: darkred;
}

.no-ads {
  text-align: center;
  font-size: 1.5rem;
  margin-top: 50px;
}

/* Pagination styling */
.pagination {
  text-align: center;
  margin: 20px 0;
}

.pagination a {
  margin: 0 5px;
  padding: 8px 12px;
  text-decoration: none;
  color: #333;
  border: 1px solid #ddd;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.pagination a.active {
  background-color: #333;
  color: #fff;
  border-color: #333;
}

.pagination a:hover {
  background-color: #555;
  color: #fff;
}
    </style>

    <script>

    function confirmDelete(adId) {
        if (confirm("Are you sure you want to delete this ad?")) {
            window.location.href = "delete_ad.php?ad_id=" + adId;
        }
    }
    </script>
</head>

<body>

<h2 class="title">My Ads</h2>
<div class="container">
    

    <div class="ads-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $images = explode(',', $row['images']);
                $first_image = !empty($images[0]) ? $images[0] : 'default_image.jpg';
            ?>
                <div class="ad-card">
                    <div class="details">
                        <img src="<?= htmlspecialchars($first_image) ?>" alt="Ad Image">
                        <h4><?= htmlspecialchars($row['title']) ?></h4>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <p class="price" >Price: Rs <?= number_format($row['price'], 2) ?></p>
                    </div>
                    <div class="ad-buttons" style="margin-top: 10px;">
                        <a href="view_ad.php?ad_id=<?= $row['ad_id'] ?>" class="btn">View Ad</a>
                        <a href="edit_ad.php?ad_id=<?= $row['ad_id'] ?>" class="btn">Edit Ad</a>
                        <button class="btn btn-danger" onclick="confirmDelete(<?= $row['ad_id'] ?>)">Delete Ad</button>
                    </div>
                </div>
                    
                    <!-- Buttons positioned inside the ad card, below the content -->
                
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-ads">You haven't placed any ads yet!</p>
        <?php endif; ?>
    </div>

    <!-- Pagination Links Below the Ads Container -->
    <div class="pagination">
        <?php for ($page = 1; $page <= $total_pages; $page++): ?>
            <a href="?page=<?= $page; ?>" class="<?= $page == $current_page ? 'active' : ''; ?>">
                <?= $page; ?>
            </a>
        <?php endfor; ?>
    </div>
</div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>