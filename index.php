<?php include("lang/lang.php"); ?>

<!DOCTYPE html>
<html>

<head>
    <title><?= $translations['title'] ?></title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $translations['description'] ?>">
    <meta name="keywords" content="<?= $translations['keywords'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/home.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
        rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="wrapper">
        <div class="layout">
            <div class="icon">
                <a href="settings"><i class="fa fa-gear"></i></a>
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
            <div class="logo-home">
                <img src="assets/img/khoj.png" width="400px" class="logo-home" title="<?= $translations['logo_title'] ?>"
                    alt="<?= $translations['logo_alt'] ?>">
                <h1><?= $translations['heading'] ?></h1>
            </div>
            <div class="khoj">
                <center>
                    <form class="search-form" action="search.php" method="get">
                        <input type="text" name="term" autocomplete="off"
                            placeholder="<?= $translations['search_placeholder'] ?>" class="search-input">
                        <button class="search-button" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </center>
            </div>
            <div class="icon-button" style="display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                width: 100%;
                gap: 50px;
                text-align: center;">
                <a href='<?php echo "search.php?term= &type=sites#result"; ?>' class="">
                    <div class="button-icons  <?php echo $type == 'sites' ? 'active' : '' ?>">
                        <i class="fa fa-globe"></i>
                    </div>
                </a>
                <a href='<?php echo "search.php?term=&type=images#result"; ?>' class=" ">
                    <div class="button-icons <?php echo $type == 'images' ? 'active' : '' ?>">
                        <i class="fa  fa-file-image-o"></i>
                    </div>
                </a>
                <a href='<?php echo "news"; ?>' class="  ">
                    <div class="button-icons">
                        <i class="fa fa-newspaper-o"></i>
                    </div>
                </a>
            </div>
            <style>
                .active {
                    background-color: #f4c430;
                    color: black;
                }
            </style>
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
</script>
<style>
    .khoj.fixed {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 999;
    }
</style>
</html>
