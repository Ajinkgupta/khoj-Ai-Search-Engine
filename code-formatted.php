<?php include("lang/lang.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $translations['title'] ?></title>
    <meta name="description" content="<?= $translations['description'] ?>">
    <meta name="keywords" content="<?= $translations['keywords'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/home.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
    integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body>
<div class="wrapper">
    <div class="layout">
      <div class="icon">
       <a href="settings" ><i class="fa fa-gear"></i> </a>
      </div>
      <div class="icon-2">
        <form method="POST">
            <div  >
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
        <div class="logo-home">
            <img src="assets/img/khoj.png" width="400px" class="logo-home" title="<?= $translations['logo_title'] ?>" alt="<?= $translations['logo_alt'] ?>">
        </div>
        <h1><?= $translations['heading'] ?></h1>

       <center>  <form class="search-form" action="search.php" method="GET">
          <input type="text" name="term" autocomplete="off" placeholder="<?= $translations['search_placeholder'] ?>" class="search-input">
          <button class="search-button">
            <i class="fa fa-search"></i>
          </button> 
        </form> </center>


      </div>
      <div class="icon-button">
        <div class="button-icons">
          <i class="fa fa-fighter-jet"></i>
          <p>
            Flight</p>
        </div>
        <div class="button-icons">
          <i class="fa fa-twitter"></i>
          <p>
            Twitter</p>
        </div>
        <div class="button-icons">
          <i class="fa fa-subway"></i>
          <p>
            Flight</p>
        </div>
        <div class="button-icons">
          <i class="fa fa-taxi"></i>
          <p>
            Taxi</p>
        </div>
        <div class="button-icons">
          <i class="fa fa-android"></i>
          <p>
            Android</p>
        </div>
        <div class="button-icons">
          <i class="fa fa-envelope"></i>
          <p>
            Gmail</p>
        </div>
        <div class="button-icons">
          <i class="fa fa-hotel"></i>
          <p>
            Hotels</p>
        </div>
        <div class="button-icons">
          <i class="fa fa-calendar"></i>
          <p>
            Events</p>
        </div>
        
      </div>
    </div>
  </div>

</body>
</html>