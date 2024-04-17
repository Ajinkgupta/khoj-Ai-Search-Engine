<?php 

include("lang/lang.php");
include("config/config.php");
include("provider/sites.php"); 

if (isset($_GET['term']))
    $term = $_GET['term'];
else
    exit("You must enter a search term!");

$type = isset($_GET["type"]) ? $_GET["type"] : "sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

if ($type == "sites") {
    $resultsProvider = new SiteResultsProvider($con);
    $pageSize = 20;
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
    <meta charset="UTF-8">
    <meta name="description" content="<?= $translations['description'] ?>">
    <meta name="keywords" content="<?= $translations['keywords'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/grid.css">
    <link rel="stylesheet" type="text/css" href="assets/css/search.css">
    <link rel="stylesheet" type="text/css" href="assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="assets/css/special.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="search" type="application/opensearchdescription+xml" title="KHOJ" href="./opensearch.xml">
    <style>
        .resultcontain  {
            border: 2px solid #ebecf0;
            background: linear-gradient(160deg, #f0f1f4 0%, #e4e6eb 100%);
            box-shadow: -3px -3px 6px 2px #ffffff, 5px 5px 8px 0px rgba(0, 0, 0, 0.17),
            1px 2px 2px 0px rgba(0, 0, 0, 0.1);
            padding:5px!important;
            transition: 0.1s; 
            border-radius: 20px; 
            font-size:20px!important;
            width: 92%;
            margin: 4%; 
        }

        .resultcontain .url {
            position: absolute;
            bottom: 10;
            left:10;
            display: flex;
            align-items: center;
            background: white;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 30px;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
            color:black;
        }

        .resultcontain .description {
            display: block;
            color: #555;
            padding:50px 0px 50px 10px;
        }

        .resultcontain .description ul {
            list-style-type: disc; 
            margin-left: 20px; 
            padding-left: 0; 
            font-size: 16px; 
        }

        .resultcontain .description ul li {
            margin-bottom: 8px; 
        }

        .modal {
            display: none; 
            position: fixed; 
            z-index: 9999; 
            left: 5%;
            top:5%;
            width: 90%;
            height: 90%;
            border-radius:10px;
            border:1px solid black;
            overflow: auto; 
            background-color:white; 
        }

        .modal-content {
            max-width:100%;
        }

        .close {
            color: black;
            position: absolute;
            top: 10px;
            border:1px solid blue;
            padding:3px 15px;
            border-radius:10px;
            right: 25px;
            font-size: 35px;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        .inco-button {
            background-color: white;
            float:right; 
            margin-right:10px;
            padding:5px; 
            border-radius:10px;
            border:0px;
        }
    </style>
    <title><?= $translations['title'] ?></title>
</head>
<body>
    <div class="wrapper">
        <div class="layout">
            <div id="result">
                <div class="mainResultsSection" id="search-results">
                    <p class="resultsCount" style="text-align:center;font-weight:900;font-size:20px;"><?php echo $numResults; ?> <?= $translations['result_count'] ?></p>
                    <?php include 'provider/special.php'; ?>
                    <?php echo $resultsHtml; ?>
                </div>
                <div class="paginationContainer" style="padding:50px;">
                    <center> <button class="loadMoreButton" onclick="loadMoreResults()"> <?= $translations['load_more'] ?></button></center>
                </div>
            </div>
        </div>
       <!-- Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>
    </div>
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
            xhr.open("GET", `get.php?term=${term}&type=${type}&page=${currentPage}`, true);

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

        function openModalWithImage(imageURL) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("modalImage");
            modal.style.display = "block";
            modalImg.src = imageURL;

            var span = document.getElementsByClassName("close")[0];
            span.onclick = function() {
                modal.style.display = "none";
            }
        }

        function addIncoButtons() {
            var resultContainers = document.querySelectorAll('.resultContainer');
            resultContainers.forEach(function(container) {
                var incoButton = document.createElement('button');
                incoButton.textContent = 'Inco';
                incoButton.classList.add('inco-button');

                incoButton.onclick = function() {
                    var url = container.querySelector('.url').textContent;
                    var imageURL = `https://api.apiflash.com/v1/urltoimage?access_key=15ab100e065441d990be79554fcef441&url=${encodeURIComponent(url)}&format=webp&fresh=true&full_page=true&scroll_page=true&response_type=image`;
                    openModalWithImage(imageURL);
                };

                container.appendChild(incoButton);
            });
        }

        addIncoButtons();
    </script>
</body>
</html>
