<?php
include("../lang/lang.php");
require_once('../config/config.php');
 
 
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $translations['news_title'] ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $translations['description'] ?>">
    <meta name="keywords" content="<?= $translations['keywords'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../assets/css/home.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
    integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="layout">
            <div class="icon">
                <a href="../settings" ><i class="fa fa-gear"></i> </a>
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
                    <form class="search-form"  action="search.php" method="get">
                        <input type="text" name="term" autocomplete="off" placeholder="<?= $translations['search_placeholder'] ?>" class="search-input">
                        <button class="search-button" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </center>
            </div>
            <div class="icon-button" style="display: flex; flex-direction: row; justify-content: center; align-items: center; width: 100%; gap: 50px; text-align: center;">
                <a href='<?php echo "search.php?term= &type=sites#result"; ?>' class="">
                    <div class="button-icons  <?php echo $type == 'sites' ? 'active' : '' ?>"  >
                        <i class="fa fa-globe"></i>
                    </div>
                </a>
                <a href='<?php echo "search.php?term=&type=images#result"; ?>' class=" ">
                    <div class="button-icons <?php echo $type == 'images' ? 'active' : '' ?>" >
                        <i class="fa fa-file-image-o"></i>
                    </div>
                </a>
                <a href='<?php echo "news"; ?>' class="  ">
                    <div class="button-icons" >
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                </a>
            </div>
            <style>
            .active{  background-color: #f4c430; color:black;}
            </style>
             <?php
            // Define the table names for each language
            $tables = [
                'en' => 'news_english',
                'mr' => 'news_marathi',
                'hi' => 'news_hindi',
                'sa' => 'news_hindi', // Display Hindi news when Sanskrit is selected
            ];

            // Check if the selected language is in the list and display news accordingly
            if (isset($tables[$selectedLanguage])) {
                $tableName = $tables[$selectedLanguage];

                // Fetch news data from the corresponding table
                $sql = "SELECT * FROM $tableName ORDER BY pubDate DESC"; // Adjust the query as needed
                $stmt = $con->prepare($sql);
                $stmt->execute();
                $news = $stmt->fetchAll();

                // Define the number of initial news items to display and the increment for "Load More"
                $initialItems = 12;
                $increment = 12;

                // Check if there are news items
                if (!empty($news)) {
                    echo "<div class='container mx-auto' style='width: 90%; margin: auto;'>";
                    echo "<h1 class='text-3xl font-bold mb-4'>$translations[latest_news]</h1>";
                    echo "<div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4'>";
                    for ($i = 0; $i < $initialItems && $i < count($news); $i++) {
                        $item = $news[$i];
                        $thumbnail_url = $item['thumbnail_url'];
                        $title = $item['title'];
                        $id = $item['id'];
                        echo "<a href='$selectedLanguage/?id=$id' class='bg-white rounded-lg shadow-lg transition-transform transform hover:scale-105'>";
                        echo "<img src='$thumbnail_url' alt='News Thumbnail' class='w-full h-40 object-cover rounded-t-lg'>";
                        echo "<div class='p-4'>";
                        echo "<h2 class='text-xl font-semibold mt-2'>$title</h2>";
                        echo "</div></a>";
                    }
                    echo "</div></div>";

                    // If there are more news items, display the "Load More" button
                    if (count($news) > $initialItems) {
                        echo "<div class='container mx-auto text-center mt-4 ' style='padding:20px;'>";
                        echo "<button id='loadMoreButton' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded'>$translations[load_more]</button>";
                        echo "</div>";
                    }
                } else {
                    echo "$translations[no_news]";
                }
            }
            ?>

            
        </div>
    </div>
</body> 
<script>
    window.onscroll = function() {
        var khoj = document.querySelector('.khoj');
        if (window.scrollY > 200) {
            khoj.classList.add('fixed');
        } else {
            khoj.classList.remove('fixed');
        }
    };

    // JavaScript for "Load More" functionality
    $(document).ready(function() {
        var initialItems = <?= $initialItems ?>;
        var increment = <?= $increment ?>;
        var currentItems = initialItems;
        var totalItems = <?= count($news) ?>;

        $("#loadMoreButton").on('click', function() {
            if (currentItems + increment <= totalItems) {
                currentItems += increment;
                for (var i = currentItems - increment; i < currentItems; i++) {
                    var item = <?= json_encode($news) ?>[i];
                    if (item) {
                        var thumbnail_url = item['thumbnail_url'];
                        var title = item['title'];
                        var id = item['id'];
                        var newsItemHtml = "<a href='<?= $selectedLanguage ?>/?id=" + id + "' class='bg-white rounded-lg shadow-lg transition-transform transform hover:scale-105'><img src='" + thumbnail_url + "' alt='News Thumbnail' class='w-full h-40 object-cover rounded-t-lg'><div class='p-4'><h2 class='text-xl font-semibold mt-2'>" + title + "</h2></div></a>";
                        $(".grid").append(newsItemHtml);
                    }
                }

                if (currentItems >= totalItems) {
                    $("#loadMoreButton").hide();
                }
            }
        });
    });
</script>

<style>.khoj.fixed {
    position: fixed;
    top: 0;
    left: 0;
    right:0;
    z-index:999;
}</style>
</html>

