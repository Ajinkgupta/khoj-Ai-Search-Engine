<?php
error_reporting(0);
include("src/config/config.php");
include("src/provider/sites.php");
include("src/provider/images.php"); 

// Start the session to share language selection with other pages
session_start();

if(isset($_GET['term'])) {
    $term = $_GET['term'];
} else {
    exit("You must enter a search term!");
}

$type = isset($_GET["type"]) ? $_GET["type"] : "sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

// Define language translations
$languages = [
    'en' => [
        'title' => 'Khoj Search',
        'description' => 'Search the web for sites and images.',
        'keywords' => 'Search engine, khoj, websites',
        'search_placeholder' => 'Search',
        'search_button' => '&#128269;',
        'web_menu' => 'Web',
        'image_menu' => 'Image',
        'video_menu' => 'Video',
        'results_found' => 'results found',
        'results_per_page' => 20,
        'results_per_page_images' => 30,
        'results_per_page_videos' => 30,
        'the_search_engine' => 'The Search Engine You Control',
    ],
    'hi' => [
        'title' => 'खोज खोज',
        'description' => 'साइट्स और छवियों के लिए वेब खोजें।',
        'keywords' => 'खोज इंजन, खोज, वेबसाइट',
        'search_placeholder' => 'खोज',
        'search_button' => '&#128269;',
        'web_menu' => 'वेब',
        'image_menu' => 'छवि',
        'video_menu' => 'वीडियो',
        'results_found' => 'परिणाम मिले',
        'results_per_page' => 20,
        'results_per_page_images' => 30,
        'results_per_page_videos' => 30,
        'the_search_engine' => 'जिसे आप नियंत्रण करते हैं खोज इंजन',
    ],
    'mr' => [
        'title' => 'खोज सोफटवेअर',
        'description' => 'साइट्स आणि चित्रे शोधा.',
        'keywords' => 'शोध इंजिन, खोज, वेबसाइट्स',
        'search_placeholder' => 'शोध',
        'search_button' => '&#128269;',
        'web_menu' => 'वेब',
        'image_menu' => 'चित्र',
        'video_menu' => 'व्हिडिओ',
        'results_found' => 'परिणाम सापडले',
        'results_per_page' => 20,
        'results_per_page_images' => 30,
        'results_per_page_videos' => 30,
        'the_search_engine' => 'तुम्हाला नियंत्रित करणारे शोध इंजिन',
    ],
];
 

// Set the selected language based on session or default to English
$selectedLanguage = isset($_SESSION['selectedLanguage']) ? $_SESSION['selectedLanguage'] : 'en';

// Function to set the selected language in session
function setLanguage($selectedLanguage) {
    $_SESSION['selectedLanguage'] = $selectedLanguage;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php if(isset($term) && $term != '') echo($term . ' | '); echo $languages[$selectedLanguage]['title']; ?></title>
    <meta name="description" content="<?php echo $languages[$selectedLanguage]['description']; ?>">
    <meta name="keywords" content="<?php echo $languages[$selectedLanguage]['keywords']; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="src/assets/design.css">
</head>
<body>
<div class="home-page">
    <div class="mainSection">
        <div class="logo-home">
            <img src="src/assets/logo.png"  width="200" height="200" class="logo-home" title="Logo of our site" alt="Site logo">
        </div>
        <div class="heading">
            <h1><?php echo $languages[$selectedLanguage]['the_search_engine']; ?></h1>
        </div>
        <div class="searchContainer">
            <form action="search.php" method="GET">
                <div class="searchbar">
                    <input class="searchBox" type="text" name="term" value="<?php echo $term; ?>" autocomplete="off"
                           placeholder="<?php echo $languages[$selectedLanguage]['search_placeholder']; ?>">
                    <input class="searchButton" type="submit"
                           value="<?php echo $languages[$selectedLanguage]['search_button']; ?>">
                </div>
            </form>
        </div>
    </div>
</div>
<div id="result">
    <div class="menu">
        <a href='<?php echo "search.php?term=$term&type=sites#result"; ?>'
           class="menu-item <?php echo $type == 'sites' ? 'active' : '' ?>"><?php echo $languages[$selectedLanguage]['web_menu']; ?></a>
        <a href='<?php echo "search.php?term=$term&type=images#result"; ?>'
           class="menu-item <?php echo $type == 'images' ? 'active' : '' ?>"><?php echo $languages[$selectedLanguage]['image_menu']; ?></a>
        <a href='<?php echo "search.php?term=$term&type=videos#result"; ?>'
           class="menu-item <?php echo $type == 'videos' ? 'active' : '' ?>"><?php echo $languages[$selectedLanguage]['video_menu']; ?></a>
    </div>
    <hr class="hr-neon"/>
    <div class="mainResultsSection">
        <?php
        if ($type == "sites") {
            $resultsProvider = new SiteResultsProvider($con);
            $pageSize = $languages[$selectedLanguage]['results_per_page'];
        } else if ($type == "images") {
            $resultsProvider = new ImageResultsProvider($con);
            $pageSize = $languages[$selectedLanguage]['results_per_page_images'];
        } else if ($type == "videos") {
            include("includes/Video.php");
            $resultsProvider = 0; // Set the results provider to null
            $pageSize = $languages[$selectedLanguage]['results_per_page_videos'];
            $numResults = 0;
        }
        $numResults = $resultsProvider->getNumResults($term);
        echo "<p class='resultsCount'>$numResults " . $languages[$selectedLanguage]['results_found'] . "</p>";
        echo $resultsProvider->getResultsHtml($page, $pageSize, $term);
        ?>
    </div>
    <div class="paginationContainer">
        <?php
        $pagesToShow = 10;
        $numPages = ceil($numResults / $pageSize);
        $pagesLeft = min($pagesToShow, $numPages);
        $currentPage = $page - floor($pagesToShow / 2);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage + $pagesLeft > $numPages + 1)
            $currentPage = $numPages + 1 - $pagesLeft;
        while ($pagesLeft != 0 && $currentPage <= $numPages) {
            if ($currentPage == $page) {
                echo "<span class='pageNumber'>$currentPage</span>";
            } else {
                echo "<a href='search.php?term=$term&type=$type&page=$currentPage'><span class='pageNumber active'>$currentPage</span></a>";
            }
            $currentPage++;
            $pagesLeft--;
        }
        ?>
    </div>
</div>
</body>
</html>
