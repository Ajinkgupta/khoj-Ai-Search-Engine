
<?php
error_reporting(0);

include("connect.php");
include("includes/web.php");
include("includes/Image.php"); 

if(isset($_GET['term']))
    $term = $_GET['term'];
else
    exit("You must enter a search term!");

$type = isset($_GET["type"]) ? $_GET["type"] : "sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php if(isset($term) && $term != '') echo($term . ' | '); ?>Khoj Search</title>

    <meta name="description" content="Search the web for sites and images.">
    <meta name="keywords" content="Search engine, khoj, websites">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/design.css">
</head>
<body>

<div class="home-page">

    <div class="mainSection">
        <div class="logo-home">
            <img src="assets/images/khoj.png" class="logo-home" title="Logo of our site" alt="Site logo">
        </div>
        <div class="heading">
            <h1>The Search Engine You Control</h1>
        </div>

        <div class="searchContainer">
            <form action="search.php" method="GET">
                <div class="searchbar">
                    <input class="searchBox" type="text" name="term" value="<?php echo $term; ?>" autocomplete="off" placeholder="Search">
                    <input class="searchButton" type="submit" value="&#128269;">
                </div>
            </form>
        </div>

    </div>
</div>

<div id="result">

    <div class="menu">
        <a href='<?php echo "search.php?term=$term&type=sites#result"; ?>' class="menu-item <?php echo $type == 'sites' ? 'active' : '' ?>">Web</a>
        <a href='<?php echo "search.php?term=$term&type=images#result"; ?>' class="menu-item <?php echo $type == 'images' ? 'active' : '' ?>">Image</a>
        <a href='<?php echo "search.php?term=$term&type=videos#result"; ?>' class="menu-item <?php echo $type == 'videos' ? 'active' : '' ?>">Video</a>
    </div>

    <hr class="hr-neon" />

    <div class="mainResultsSection">
        <?php
        if($type == "sites") 
        {
            $resultsProvider = new SiteResultsProvider($con);
            $pageSize = 20;
        }
        else if ($type == "images") {
            $resultsProvider = new ImageResultsProvider($con);
            $pageSize = 30;
        }
        else if ($type == "videos") {
            
          include("includes/Video.php");
		  $resultsProvider = 0; // Set the results provider to null

		  $pageSize = 30;
		  $numResults = 0;


        }

        $numResults = $resultsProvider->getNumResults($term);

        echo "<p class='resultsCount'>$numResults results found</p>";
        echo $resultsProvider->getResultsHtml($page, $pageSize, $term);
        ?>
    </div>

    <div class="paginationContainer">
        <?php
        $pagesToShow = 10;
        $numPages = ceil($numResults / $pageSize);
        $pagesLeft = min($pagesToShow, $numPages);

        $currentPage = $page - floor($pagesToShow / 2);

        if($currentPage < 1)
            $currentPage = 1;

        if($currentPage + $pagesLeft > $numPages + 1)
            $currentPage = $numPages + 1 - $pagesLeft;

        while($pagesLeft != 0 && $currentPage <= $numPages) 
        {
            if($currentPage == $page) 
            {
                echo "<span class='pageNumber'>$currentPage</span>";
            }
            else 
            {
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
