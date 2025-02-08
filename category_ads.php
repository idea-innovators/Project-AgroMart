<?php
session_start();
include 'config.php';
include 'navbar.php'; 

if (isset($_GET['category_id_qp'])) {
    $category_id = $_GET['category_id_qp'];

    $ad_sql = "SELECT ads.*, categories.category_name 
           FROM ads 
           JOIN categories ON ads.category_id = categories.category_id 
           WHERE ads.category_id = ?";
    $stmt = $conn->prepare($ad_sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Category Ads</title>
        <style>
            .ad-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
                margin: 20px auto;
                max-width: 1200px;
            }

            .ad-card {
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 8px;
                overflow: hidden;
                width: 18%;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s, box-shadow 0.2s;
                text-align: center;
                padding: 10px;
            }

            .ad-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            }

            .ad-card img {
                width: 100%;
                height: 150px;
                object-fit: cover;
                border-radius: 8px;
            }

            .ad-card h4 {
                font-size: 18px;
                color: #333;
                margin: 10px 0 5px 0;
                font-weight: 600;
                text-transform: capitalize;
            }

            .ad-card p {
                font-size: 16px;
                color: #007b00;
                font-weight: 500;
            }

            @media (max-width: 768px) {
                .ad-card {
                    width: 45%;
                }
            }

            @media (max-width: 480px) {
                .ad-card {
                    width: 90%;
                }
            }
        </style>
    </head>
    <body>

        <div class="container">
            <h2>Ads for Category</h2>
            <div class="ad-container">
                <?php
                if ($result->num_rows > 0) {
                    while ($ad = $result->fetch_assoc()) {
                        $ad_id = $ad['ad_id'];

                        $img_sql = "SELECT image_path FROM ad_images WHERE ad_id = ? LIMIT 1";
                        $stmt_img = $conn->prepare($img_sql);
                        $stmt_img->bind_param("i", $ad_id);
                        $stmt_img->execute();
                        $img_result = $stmt_img->get_result();
                        $image = $img_result->fetch_assoc();
                        ?>
                        <div class="ad-card">
                            <a href="view_ad.php?ad_id=<?= $ad_id; ?>">
                                <?php if ($image): ?>
                                    <img src="<?= htmlspecialchars($image['image_path']); ?>" alt="<?= htmlspecialchars($ad['title']); ?>">
                                <?php endif; ?>
                                <h4><?= htmlspecialchars($ad['title']); ?></h4>
                                <p>Price: Rs <?= htmlspecialchars($ad['price']); ?></p>
                                <p>District: <?= htmlspecialchars($ad['district']); ?></p>
                                <p>Posted on: <?= date('F j, Y', strtotime($ad['created_at'])); ?></p>
                            </a>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No ads found in this category.</p>";
                }
                ?>
            </div>
        </div>

    </body>
    </html>

    <?php
} else {
    echo "No category selected.";
}
?>
