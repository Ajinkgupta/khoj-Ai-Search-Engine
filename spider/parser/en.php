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
            $guid = $conn->real_escape_string((string) $item->guid);
            $title = $conn->real_escape_string((string) $item->title);
            $link = $conn->real_escape_string((string) $item->link);
            $pubDate = date('Y-m-d H:i:s', strtotime((string) $item->pubDate));

            $mediaContent = $item->children('media', true)->content;
            $thumbnail_url = $conn->real_escape_string((string) $mediaContent->attributes()->url);

            $description = $conn->real_escape_string((string) $item->description);

            $contentEncoded = $conn->real_escape_string((string) $item->children('content', true)->encoded);

            $sql = "INSERT INTO news_english (guid, title, link, pubDate, thumbnail_url, description, content_encoded) VALUES ('$guid', '$title', '$link', '$pubDate', '$thumbnail_url', '$description', '$contentEncoded')";

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
    <title>English news Parser</title>
</head>
<body>
    <h1>English news Parser</h1>
    <form method="post">
        <label for="rss_url">Enter RSS URL: </label>
        <input type="text" name="rss_url" id="rss_url" required>
        <input type="submit" value="Store Data">
    </form>
</body>
</html>
