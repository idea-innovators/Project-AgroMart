<?php
session_start();
include 'config.php';
include 'navbar.php';

// Fetch all ads from the database
$ads_sql = "
    SELECT ads.*, categories.category_name, 
        (SELECT image_path FROM ad_images WHERE ad_id = ads.ad_id LIMIT 1) AS image 
    FROM ads 
    JOIN categories ON ads.category_id = categories.category_id 
    ORDER BY ads.created_at DESC";
$result = $conn->query($ads_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Ads</title>
    <style>
    /* Card layout for ads */
    .ads-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin: 20px;
    }

    .ad-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        width: 23%;
        /* 4 items per row */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        text-align: center;
        cursor: pointer;
    }

    .ad-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .ad-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
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
        font-size: 14px;
        color: #007b00;
        font-weight: 500;
        margin: 5px 0;
    }

    body,
    html {
        overflow-x: hidden;
    }
    </style>
</head>

<body>

    <div class="container">
        <h2>All Ads</h2>
        <div class="ads-container">
            <?php if ($result->num_rows > 0): ?>
            <?php while ($ad = $result->fetch_assoc()):
                    // Limit the description to 200 characters
                    $description = $ad['description'];
                    if (strlen($description) > 200) {
                        $description = substr($description, 0, 200) . '...';
                    }
                ?>
            <div class="ad-card" onclick="window.location.href='view_ad.php?ad_id=<?= $ad['ad_id']; ?>'">
                <?php if ($ad['image']): ?>
                <img src="<?= htmlspecialchars($ad['image']); ?>" alt="Product Image">

                <?php else: ?>
                <img src="images/placeholder/NO IMAGE AD.png" alt="Product Image">
                <?php endif; ?>

                <h4><?= htmlspecialchars($ad['title']); ?></h4>
                <p><?= htmlspecialchars($description); ?></p>
                <p><strong>Price:</strong> Rs <?= htmlspecialchars($ad['price']); ?></p>
                <p><strong>District:</strong> <?= htmlspecialchars($ad['district']); ?></p>
                <p><strong>Posted on:</strong> <?= date('F j, Y', strtotime($ad['created_at'])); ?></p>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p>No ads found.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>