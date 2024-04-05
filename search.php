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
              
              <div>
                <div class='resultcontain'>
			 
				<span class='url'> <img src="https://cdn.create.vista.com/api/media/small/296354738/stock-vector-cute-white-modern-levitating-robot-waving-hand-and-with-happy-face-flat-vector-illustration-isolated" width="30px">KHOJ AI  &nbsp;  </span>
				<span class='description'>
                 <?php

// Check if query parameter is set
if(isset($_GET['term'])) {
    // Get the query from the URL
    $query = urlencode($_GET['term']); // Encode query parameter
    
    // Call the API with the query
    $api_url = "https://8000-vickygpt-khojaisearchen-qnocvmusi3p.ws-us110.gitpod.io/api/?q=$query";
    
    // Fetch JSON response from the API
    $json_response = file_get_contents($api_url);
    
    if ($json_response === false) {
        // Output error message if unable to fetch API response
        echo "Error: Unable to fetch API response.";
    } else {
        // Decode JSON response
        $data = json_decode($json_response, true);
        
        // Check if the JSON decoding was successful and model_response exists
        if ($data !== null && isset($data['model_response'])) {
            // Output HTML response
            echo $data['model_response'];
        } else {
            // Output error message if model_response is missing
            echo "Error: Model response not found in API.";
        }
    }
} else {
    // Output error message if query parameter is missing
    echo "Error: Query parameter missing in URL.";
}

?>
                </span>
 			</div>
            
    <style>
             .resultcontain  {
border: 2px solid #ebecf0;
background: linear-gradient(160deg, #f0f1f4 0%, #e4e6eb 100%);
box-shadow: -3px -3px 6px 2px #ffffff, 5px 5px 8px 0px rgba(0, 0, 0, 0.17),
  1px 2px 2px 0px rgba(0, 0, 0, 0.1);
  padding:5px!important;
transition: 0.1s; border-radius: 20px; font-size:20px!important;
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
  list-style-type: disc; /* Change bullet point style */
  margin-left: 20px; /* Adjust left margin */
  padding-left: 0; /* Remove default padding */
    font-size: 16px; /* Adjust font size */

}

.resultcontain .description ul li {
  margin-bottom: 8px; /* Adjust spacing between list items */
}


     
   
    
    </style>
              </div>
              
              
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
  
  
   <!-- Modal -->
    <div id="myModal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
        // JavaScript code for new feature
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

        // Call the function to add inco buttons
        addIncoButtons();
    </script>
  
  <style>
    
    

/* Modal */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 9999; /* Sit on top */
    left: 5%;
    top:5% ;
    width: 90%;
    height: 90%;
  border-radius:10px;
  border:1px solid black;
    overflow: auto; /* Enable scrolling if the content exceeds the viewport */
    background-color:white; /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  max-width:100%;
 }


/* Close Button */
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
</body>
</html>
