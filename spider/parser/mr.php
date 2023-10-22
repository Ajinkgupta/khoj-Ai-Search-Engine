<?php
// Define your MySQL database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "khoj";



include("../protected.php");
// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the RSS URL from the form
    $rss_url = $_POST["rss_url"];

    // Set a User-Agent header to mimic a web browser request
    $options = stream_context_create([
        'http' => [
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
        ],
    ]);

    // Fetch the RSS feed
    $rss_feed = file_get_contents($rss_url, false, $options);

    // Parse the RSS feed
    $xml = simplexml_load_string($rss_feed);

    if ($xml) {
        $items = $xml->channel->item;

        foreach ($items as $item) {
            $title = $conn->real_escape_string($item->title);
            $category = $conn->real_escape_string($item->category);
            $pubDate = date('Y-m-d H:i:s', strtotime($item->pubDate));

            // Fetch the thumbnail_url for each item within the loop
            $mediaThumbnail = $item->children('media', true)->thumbnail;
            $thumbnail_url = $conn->real_escape_string((string) $mediaThumbnail->attributes()->url);
            
            $description = $conn->real_escape_string($item->description);
            $creator = $conn->real_escape_string($item->children('dc', true)->creator);

            $sql = "INSERT INTO news_marathi (title, category, author, pubDate, thumbnail_url, description, creator) VALUES ('$title', '$category', '$creator', '$pubDate', '$thumbnail_url', '$description', '$creator')";

            if ($conn->query($sql) === TRUE) {
                echo "Item inserted successfully<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Failed to parse the RSS feed.";
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Marathi news Parser</title>
</head>
<body>
    <h1>Marathi news Parser</h1>
    <form method="post">
        <label for="rss_url">Enter RSS URL: </label>
        <input type="text" name="rss_url" id="rss_url" required>
        <input type="submit" value="Store Data">
    </form>
</body>
</html>
