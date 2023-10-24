<?php 

include("lang/lang.php");
include("config/config.php");
include("provider/sites.php");
include("provider/images.php");
include("provider/github.php");


if (isset($_GET['term']))
    $term = $_GET['term'];
else
    exit("You must enter a search term!");

$type = isset($_GET["type"]) ? $_GET["type"] : "sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

if ($type == "sites") {
    $resultsProvider = new SiteResultsProvider($con);
    $pageSize = 20;
} elseif ($type == "images") {
    $resultsProvider = new ImageResultsProvider($con);
    $pageSize = 30;
} elseif ($type == "github") {
    $resultsProvider = new GitHubResultsProvider($term);
    $pageSize = 20;
} elseif($type == "videos") {
    include("provider/video.php");
// Create an instance of VideoResultsProvider with the API host and key
   $resultsProvider = new VideoResultsProvider("https://youtube-search-results.p.rapidapi.com", "4d5fdb515amsh34d74db809e079ep1408b3jsnb23a68d88dc9");
    $pageSize = 30; 
}

if ($page == 1) {
    $numResults = $resultsProvider->getNumResults($term);
}

$resultsHtml = $resultsProvider->getResultsHtml($page, $pageSize, $term);

if ($page > 1) {
    echo $resultsHtml;
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet">
    <title><?= $translations['title'] ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $translations['description'] ?>">
    <meta name="keywords" content="<?= $translations['keywords'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/grid.css">
    <link rel="stylesheet" type="text/css" href="assets/css/search.css">
    <link rel="stylesheet" type="text/css" href="assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="assets/css/special.css">

    
    <link rel="search" type="application/opensearchdescription+xml" title="KHOJ" href="./opensearch.xml">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script>
        function loadMoreResults() {
            var page = <?php echo $page + 1; ?>;
            var term = "<?php echo $term; ?>";
            var type = "<?php echo $type; ?>";

            var xhr = new XMLHttpRequest();
            xhr.open("GET", `get.php?term=${term}&type=${type}&page=${page}`, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var newResults = xhr.responseText;
                    var mainResultsSection = document.querySelector(".mainResultsSection");
                    mainResultsSection.innerHTML += newResults;
                }
            };

            xhr.send();
        }
    </script>
</head>

<body>
    <div class="wrapper">
        <div class="layout">
            <div class="icon">
                <a href="settings"><i class="fa fa-gear"></i> </a>
            </div>
            <div class="icon-2">
                <form method="POST">
                    <div>
                        <select class="dropdown" name="language" onchange="this.form.submit()">
                            <option value="en" <?= $selectedLanguage === 'en' ? 'selected' : '' ?>>English</option>
                            <option value="hi" <?= $selectedLanguage === 'hi' ? 'selected' : '' ?>>हिंदी</option>
                            <option value="mr" <?= $selectedLanguage === 'mr' ? 'selected' : '' ?>>मराठी</option>
                            <option value="sa" <?= $selectedLanguage === 'sa' ? 'selected' : '' ?>>संस्कृत</option>
                            <option value="sa" <?= $selectedLanguage === 'kd' ? 'selected' : '' ?>>ಕನ್ನಡ</option>

                        </select>
                    </div>
                </form>
            </div>
            <div class="khoj">
                <center>
                    <form class="search-form" action="search.php" method="GET">
                        <input type="text" value="<?php echo $term; ?>" name="term" autocomplete="off" placeholder="<?= $translations['search_placeholder'] ?>" class="search-input">
                        <button class="search-button" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </center>
            </div>
            <div class="icon-button" style="display: flex; flex-direction: row; justify-content: center; align-items: center; width: 100%; gap: 50px; text-align: center; ">
                <a href='<?php echo "search.php?term=$term&type=sites#result"; ?>' class="">
                    <div class="button-icons <?php echo $type == 'sites' ? 'active' : '' ?>">
                        <i class="fa fa-globe"></i>
                    </div>
                </a>
                <a href='<?php echo "search.php?term=$term&type=images#result"; ?>' class=" ">
                    <div class="button-icons <?php echo $type == 'images' ? 'active' : '' ?>">
                        <i class="fa fa-file-image-o"></i>
                    </div>
                </a>
                <a href='<?php echo "search.php?term=$term&type=github#result"; ?>' class=" ">
                    <div class="button-icons <?php echo $type == 'github' ? 'active' : '' ?>">
                        <i class="fa fa-github"></i>
                    </div>
                </a>
                
                <a href='<?php echo "news"; ?>' class="  ">
                    <div class="button-icons">
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                </a>

            </div>
            <div id="result">
                <div class="mainResultsSection" id="search-results">
                <?php
        // Include the commands file to process special commands
        include 'provider/special.php';
        ?>

                    <p class="resultsCount" style="text-align:center;font-weight:900;font-size:20px;"><?php echo $numResults; ?> <?= $translations['result_count'] ?></p>
                    <?php echo $resultsHtml; ?>
                </div>
                <div class="paginationContainer" style="padding:50px;">
                    <center> <button class="loadMoreButton" onclick="loadMoreResults()"> <?= $translations['load_more'] ?></button></center>
                </div>
            </div>
        </div>
    </div>
    <script>
        function addFaviconAndDomain(resultContainer) {
            const urlElement = resultContainer.querySelector('.url');
            const url = urlElement.textContent;
            const domain = new URL(url).hostname;
            const faviconURL = `https://${domain}/favicon.ico`;

            const faviconContainer = document.createElement('div');
            faviconContainer.classList.add('favicon-container');

            const faviconImg = document.createElement('img');
            faviconImg.alt = 'Favicon';

            faviconImg.src = faviconURL;
            faviconImg.onerror = () => {
                faviconImg.src = 'http://localhost/khoj/assets/images/khoj.png';
            };

            faviconImg.classList.add('favicon');

            const domainSpan = document.createElement('span');
            domainSpan.textContent = domain;
            domainSpan.classList.add('domain');

            faviconContainer.appendChild(faviconImg);
            faviconContainer.appendChild(domainSpan);

            resultContainer.appendChild(faviconContainer);
        }

        function addSaveButton(resultContainer) {
            const saveButton = document.createElement('button');
            saveButton.classList.add('saveButton');
            saveButton.textContent = '<?= $translations['save'] ?>';

            saveButton.addEventListener('click', function() {
                saveResult(resultContainer);
            });

            resultContainer.appendChild(saveButton);
        }

        function saveResult(resultContainer) {
            const reason = prompt("Enter a reason for saving this result:");

            if (reason !== null) {
                const resultSnippet = resultContainer.outerHTML;
                const savedResults = JSON.parse(localStorage.getItem('savedResults')) || [];

                if (!savedResults.some(result => result.includes(resultSnippet))) {
                    savedResults.push({
                        result: resultSnippet,
                        reason
                    });

                    localStorage.setItem('savedResults', JSON.stringify(savedResults));

                    alert('Result saved successfully!');
                } else {
                    alert('Result is already saved!');
                }
            }
        }

        const resultContainers = document.querySelectorAll('.resultContainer');
        resultContainers.forEach(addFaviconAndDomain);
        resultContainers.forEach(addSaveButton);

        let currentPage = <?php echo $page; ?>;

        function loadMoreResults() {
            currentPage++;

            const term = "<?php echo $term; ?>";
            const type = "<?php echo $type; ?>";

            const xhr = new XMLHttpRequest();
            xhr.open("GET", `search.php?term=${term}&type=${type}&page=${currentPage}`, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const newResults = xhr.responseText;
                    const mainResultsSection = document.querySelector(".mainResultsSection");
                    const tempContainer = document.createElement('div');
                    tempContainer.innerHTML = newResults;

                    const newResultContainers = tempContainer.querySelectorAll('.resultContainer');
                    newResultContainers.forEach(addFaviconAndDomain);
                    newResultContainers.forEach(addSaveButton);

                    mainResultsSection.appendChild(tempContainer);
                }
            };

            xhr.send();
        }

        window.onscroll = function() {
            var khoj = document.querySelector('.khoj');
            if (window.scrollY > 20) {
                khoj.classList.add('fixed');
            } else {
                khoj.classList.remove('fixed');
            }
        };
    </script>
</body>
</html>
