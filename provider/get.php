<?php
error_reporting(0);

include("../config/config.php");
include("sites.php");
include("images.php"); 

if(isset($_GET['term']))
    $term = $_GET['term'];
else
    exit("You must enter a search term!");

$type = isset($_GET["type"]) ? $_GET["type"] : "sites";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

// Determine the page size
if ($type == "sites") {
    $resultsProvider = new SiteResultsProvider($con);
    $pageSize = 20;
} elseif ($type == "images") {
    $resultsProvider = new ImageResultsProvider($con);
    $pageSize = 30;
} elseif ($type == "videos") {
    include("includes/Video.php");
    $resultsProvider = 10; // Set the results provider to null
    $pageSize = 30;
    $numResults = 10;
}

// For the initial search, retrieve the number of results
if ($page == 1) {
    $numResults = $resultsProvider->getNumResults($term);
}

// For both initial search and "Load More," get and display the results
$resultsHtml = $resultsProvider->getResultsHtml($page, $pageSize, $term);

// If it's a "Load More" request, return only the results HTML
if ($page > 1) {
    echo $resultsHtml;
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type"  content="text/html; charset=utf-8"> 
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Noto Sans Devanagari', sans-serif; 
}
</style>


    <title><?php if(isset($term) && $term != '') echo($term . ' | '); ?>Khoj Search</title>
    <script>
        function loadMoreResults() {
            var page = <?php echo $page + 1; ?>;
            var term = "<?php echo $term; ?>";
            var type = "<?php echo $type; ?>";
            
            var xhr = new XMLHttpRequest();
            xhr.open("GET", `get.php?term=${term}&type=${type}&page=${page}`, true);
            
            xhr.onload = function () {
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
 
<center>  <form class="search-form" action="search.php" method="GET">
          <input type="text" name="term" autocomplete="off"  class="search-input">
          <button class="search-button">
            <i class="fa fa-search"></i>
          </button> 
        </form> </center>


<div id="result">
    <div class="mainResultsSection">
        <p class="resultsCount"><?php echo $numResults; ?> results found</p>
        <?php echo $resultsHtml; ?>
    </div>
    <div class="paginationContainer">
       <center> <button class="loadMoreButton" onclick="loadMoreResults()">Load More</button></center>
    </div>
</div>

</body>
</html>

<style>
       #result .siteResults {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

       #result .resultContainer {
        
        border: 2px solid #ebecf0;
    background:  #FCEED5;
    box-shadow: -3px -3px 6px 2px #ffffff, 5px 5px 8px 0px rgba(0, 0, 0, 0.17),
      1px 2px 2px 0px rgba(0, 0, 0, 0.1);
      padding:5px!important;
    transition: 0.1s; border-radius: 20px; font-size:20px!important;
            width: calc(50% - 50px); /* Two columns, with spacing */ 
            margin: 10px;
              
            

           
        }

        .title {
            font-size: 18px;
        }

     .url {
    color: #0077cc;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    display: block;
}


        .description {
         display: block;
            color: #555;
            padding:5px 0 50px 0;
        }

        @media (max-width: 468px) {
            .resultContainer {
                width: 100%;
            }
        }
        
         .resultContainer {
            /* Your existing styles */
            position: relative;
            margin-bottom:30px;
            background-color: #FCEED5;
            
        }
            .favicon-container {
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

          .saveButton { 
            float:right;
            background: white;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 30px;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
            color:black;}

.loadMoreButton {  
            
            background: white;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 30px;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.1);
            color:black;}

        .favicon {
            width: 32px;
            height: 32px;
        }


    </style>
    
    
    
    
    
    <script> 

    // Function to add favicon and domain name to a result container
function addFaviconAndDomain(resultContainer) {
    const urlElement = resultContainer.querySelector('.url');
    const url = urlElement.textContent;
    const domain = new URL(url).hostname;
    const faviconURL = `https://${domain}/favicon.ico`;

    // Create a new container for the favicon and domain name
    const faviconContainer = document.createElement('div');
    faviconContainer.classList.add('favicon-container');

    // Create an image element for the favicon
    const faviconImg = document.createElement('img');
    faviconImg.alt = 'Favicon';

    // Set the source of the image and add a fallback on error
    faviconImg.src = faviconURL;
    faviconImg.onerror = () => {
        // Fallback to a default favicon if the favicon is not found
        faviconImg.src = 'http://localhost/khoj/assets/images/khoj.png';
    };

    faviconImg.classList.add('favicon');

    // Create a span for the domain name
    const domainSpan = document.createElement('span');
    domainSpan.textContent = domain;
    domainSpan.classList.add('domain');

    // Append the favicon and domain name to the container
    faviconContainer.appendChild(faviconImg);
    faviconContainer.appendChild(domainSpan);

    // Append the container to the result container
    resultContainer.appendChild(faviconContainer);
}

// Function to create and add the "Save" button to each result container
function addSaveButton(resultContainer) {
    // Create the "Save" button element
    const saveButton = document.createElement('button');
    saveButton.classList.add('saveButton');
    saveButton.textContent = 'Save';

    // Add an event listener to the "Save" button to trigger the saveResult function
    saveButton.addEventListener('click', function() {
        saveResult(resultContainer);
    });

    // Append the "Save" button to the result container
    resultContainer.appendChild(saveButton);
}

// Function to save the result to local storage
function saveResult(resultContainer) {
    // Get the HTML snippet of the result
    const resultSnippet = resultContainer.outerHTML;

    // Check if the result is already saved in local storage
    const savedResults = JSON.parse(localStorage.getItem('savedResults')) || [];

    // If the result is not already saved, add it to the saved results array
    if (!savedResults.includes(resultSnippet)) {
        savedResults.push(resultSnippet);

        // Save the updated saved results array to local storage
        localStorage.setItem('savedResults', JSON.stringify(savedResults));

        // Notify the user that the result has been saved
        alert('Result saved successfully!');
    } else {
        // Notify the user that the result is already saved
        alert('Result is already saved!');
    }
}
 // ...

// JavaScript to add favicons and domain names to the search results
const resultContainers = document.querySelectorAll('.resultContainer');
resultContainers.forEach(addFaviconAndDomain);
resultContainers.forEach(addSaveButton);

let currentPage = <?php echo $page; ?>; // Keep track of the current page

function loadMoreResults() {
    currentPage++; // Increment the page number

    const term = "<?php echo $term; ?>";
    const type = "<?php echo $type; ?>";

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `get.php?term=${term}&type=${type}&page=${currentPage}`, true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            const newResults = xhr.responseText;
            const mainResultsSection = document.querySelector(".mainResultsSection");

            // Create a temporary container to hold the new results
            const tempContainer = document.createElement('div');
            tempContainer.innerHTML = newResults;

            // Add favicon containers and save buttons to the new results
            const newResultContainers = tempContainer.querySelectorAll('.resultContainer');
            newResultContainers.forEach(addFaviconAndDomain);
            newResultContainers.forEach(addSaveButton);

            // Append the new results to the main results section
            mainResultsSection.appendChild(tempContainer);
        }
    };

    xhr.send();
}


</script>