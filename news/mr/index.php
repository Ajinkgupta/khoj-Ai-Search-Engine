<?php
require_once('../../config/config.php'); // Include the config file

// Check if the 'id' parameter is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the main article from the database based on the 'id'
    $sql = "SELECT * FROM news_marathi WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$id]);
    $article = $stmt->fetch();

    // Check if the main article exists
    if ($article) {
        $title = $article['title'];
        $category = $article['category'];
        $author = $article['author'];
        $pubDate = $article['pubDate'];
        $thumbnail_url = $article['thumbnail_url'];
        $description = htmlspecialchars_decode($article['description']);
        $creator = $article['creator'];

        // Fetch three random similar articles (you may need to adjust the query as needed)
        $sqlSimilar = "SELECT * FROM news_marathi WHERE id != ? ORDER BY RAND() LIMIT 3";
        $stmtSimilar = $con->prepare($sqlSimilar);
        $stmtSimilar->execute([$id]);
        $similarArticles = $stmtSimilar->fetchAll();

        // HTML for displaying the article and similar articles with Tailwind CSS
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css'>
    <title>$title</title>
</head>
<body class='bg-gray-100 p-8'>
    <div class='container mx-auto'>
        <div class='flex'>
            <div class='w-3/4'>
                <h1 class='text-3xl font-bold mb-4'>$title</h1>
                <div class='text-sm text-gray-500 mb-4'>
                    Category: $category<br>
                    Author: $author<br>
                    Published Date: $pubDate<br>
                    Creator: $creator
                </div>
                <img src='$thumbnail_url' alt='News Thumbnail' class='w-full h-40 object-cover rounded-lg mb-4'>
                <div class='prose max-w-none'>
                    $description
                </div>
            </div>
            <div class='w-1/4 ml-8'>
                <h2 class='text-xl font-bold mb-4'>Similar Articles</h2>
                <div class='space-y-4'>
                    ";
        foreach ($similarArticles as $similarArticle) {
            $similarTitle = $similarArticle['title'];
            $similarCategory = $similarArticle['category'];
            $similarThumbnail = $similarArticle['thumbnail_url'];
            echo "<div class='bg-white rounded-lg shadow-lg'>
                    <img src='$similarThumbnail' alt='News Thumbnail' class='w-full h-32 object-cover rounded-t-lg'>
                    <div class='p-4'>
                        <span class='text-sm text-gray-500'>$similarCategory</span>
                        <h3 class='text-lg font-semibold mt-2'>$similarTitle</h3>
                    </div>
                </div>";
        }
        echo "
                </div>
            </div>
        </div>
    </div>
</body>
</html>";
    } else {
        echo "Article not found.";
    }
} else {
    echo "Invalid request. Please provide an 'id' parameter.";
}
?>
