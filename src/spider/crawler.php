<?php
include("../config/config.php");
include("parser.php");

// Start the session to share language selection with other pages
session_start();

// Define language translations
$languages = [
    'en' => [
        'title' => 'Khoj Crawler',
        'heading' => 'Let us index your website',
        'search_placeholder' => 'Enter the URL to crawl',
        'button_text' => 'Crawl',
    ],
    'hi' => [
        'title' => 'Khoj क्रॉलर',
        'heading' => 'हम आपकी वेबसाइट को इंडेक्स करें',
        'search_placeholder' => 'क्रॉल करने के लिए URL दर्ज करें',
        'button_text' => 'क्रॉल',
    ],
    'mr' => [
        'title' => 'खोज क्रॉलर',
        'heading' => 'आपल्या वेबसाइटची सूची तयार करून द्या',
        'search_placeholder' => 'क्रॉल करण्यासाठी URL प्रविष्ट करा',
        'button_text' => 'क्रॉल',
    ],
];

// Set the selected language based on session or default to English
$selectedLanguage = isset($_SESSION['selectedLanguage']) ? $_SESSION['selectedLanguage'] : 'en';

// Function to set the selected language in session
function setLanguage($selectedLanguage) {
    $_SESSION['selectedLanguage'] = $selectedLanguage;
}

// Initialize arrays to keep track of crawled URLs and found images
$alreadyCrawled = array();
$crawling = array();
$alreadyFoundImages = array();

// Function to check if a link already exists in the database
function linkExists($url) {
    global $con;

    $query = $con->prepare("SELECT * FROM sites WHERE url = :url");
    $query->bindParam(":url", $url);
    $query->execute();

    return $query->rowCount() != 0;
}

// Function to check if an image already exists in the database
function imageExists($src) {
    global $con;

    $query = $con->prepare("SELECT * FROM images WHERE imageUrl = :src");
    $query->bindParam(":src", $src);
    $query->execute();

    return $query->rowCount() != 0;
}

// Function to insert a link into the database
function insertLink($url, $title, $description, $keywords) {
    global $con;

    $query = $con->prepare("INSERT INTO sites(url, title, description, keywords) VALUES(:url, :title, :description, :keywords)");
    $query->bindParam(":url", $url);
    $query->bindParam(":title", $title);
    $query->bindParam(":description", $description);
    $query->bindParam(":keywords", $keywords);

    return $query->execute();
}

// Function to insert an image into the database
function insertImage($url, $src, $alt, $title) {
    global $con;

    $query = $con->prepare("INSERT INTO images(siteUrl, imageUrl, alt, title) VALUES(:siteUrl, :imageUrl, :alt, :title)");
    $query->bindParam(":siteUrl", $url);
    $query->bindParam(":imageUrl", $src);
    $query->bindParam(":alt", $alt);
    $query->bindParam(":title", $title);

    return $query->execute();
}

// Function to convert a relative link to an absolute link
function createLink($src, $url) {
    $scheme = parse_url($url)["scheme"]; // http
    $host = parse_url($url)["host"];

    if (substr($src, 0, 2) == "//")
        $src = $scheme . ":" . $src;
    else if (substr($src, 0, 1) == "/")
        $src = $scheme . "://" . $host . $src;
    else if (substr($src, 0, 2) == "./")
        $src = $scheme . "://" . $host . dirname(parse_url($url)["path"]) . substr($src, 1);
    else if (substr($src, 0, 3) == "../")
        $src = $scheme . "://" . $host . "/" . $src;
    else if (substr($src, 0, 5) != "https" && substr($src, 0, 4) != "http")
        $src = $scheme . "://" . $host . "/" . $src;

    return $src;
}

// Function to retrieve details from a URL
function getDetails($url) {
    global $alreadyFoundImages;

    $parser = new DomDocumentParser($url);

    $titleArray = $parser->getTitleTags();

    if (sizeof($titleArray) == 0 || $titleArray->item(0) == NULL)
        return;

    // Replace line breaks
    $title = $titleArray->item(0)->nodeValue;
    $title = str_replace("\n", "", $title);

    // Return if no <title>
    if ($title == "")
        return;

    $description = "";
    $keywords = "";

    $metasArray = $parser->getMetaTags();

    foreach ($metasArray as $meta) {
        if ($meta->getAttribute("name") == "description")
            $description = $meta->getAttribute("content");

        if ($meta->getAttribute("name") == "keywords")
            $keywords = $meta->getAttribute("content");
    }

    $description = str_replace("\n", "", $description);
    $keywords = str_replace("\n", "", $keywords);

    if (linkExists($url))
        echo "$url already exists<br>";
    else if (insertLink($url, $title, $description, $keywords))
        echo "SUCCESS: $url<br>";
    else
        echo "ERROR: Failed to insert $url<br>";

    $imageArray = $parser->getImages();
    foreach ($imageArray as $image) {
        $src = $image->getAttribute("src");
        $alt = $image->getAttribute("alt");
        $title = $image->getAttribute("title");

        if (!$title && !$alt)
            continue;

        $src = createLink($src, $url);

        if (!in_array($src, $alreadyFoundImages)) {
            $alreadyFoundImages[] = $src;

            if (imageExists($src))
                echo "$src already exists<br>";
            else if (insertImage($url, $src, $alt, $title))
                echo "SUCCESS: $src<br>";
            else
                echo "ERROR: Failed to insert $src<br>";
        }
    }

    echo "<b>URL:</b> $url, <b>Title:</b> $title, <b>Description:</b> $description, <b>Keywords:</b> $keywords<br>";
}

// Function to follow links on a given URL
function followLinks($url) {
    global $alreadyCrawled;
    global $crawling;

    $parser = new DomDocumentParser($url);

    $linkList = $parser->getLinks();

    foreach ($linkList as $link) {
        $href = $link->getAttribute("href");

        // Filter hrefs
        if (strpos($href, "#") !== false)
            continue;
        else if (substr($href, 0, 11) == "javascript:")
            continue;

        $href = createLink($href, $url);

        if (!in_array($href, $alreadyCrawled)) {
            $alreadyCrawled[] = $href;
            $crawling[] = $href;
            getDetails($href);
        }

        echo ($href . "<br>"); // Debugging
    }

    array_shift($crawling);

    foreach ($crawling as $site)
        followLinks($site);
}

// Check if the form is submitted with a URL
if (isset($_POST['url'])) {
    // Get the selected URL
    $startUrl = $_POST['url'];
    followLinks($startUrl);
} else {
    // If the form is not submitted, display the language selection page
    $translations = $languages[$selectedLanguage];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $translations['title'] ?></title>
    <meta name="description" content="<?= $translations['heading'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/design.css">
</head>
<body>
    <div class="home-page">
        <div class="mainSection">
            <div class="logo-home">
                <img src="assets/images/khoj.png" class="logo-home" title="<?= $translations['title'] ?>" alt="<?= $translations['title'] ?>">
            </div>
            <div class="heading">
                <h1><?= $translations['heading'] ?></h1>
            </div>
            
            <div class="searchContainer">
                <form action="crawler.php" method="post">
                    <div class="searchbar">
                        <input class="searchBox" type="text" name="url" required="required" id="crawl-input" placeholder="<?= $translations['search_placeholder'] ?>">
                        <button class="searchButton" type="submit"><?= $translations['button_text'] ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php
}
?>
