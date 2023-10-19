<?php
session_start();

// Function to set the selected language in session and cookie
function setLanguage($selectedLanguage) {
    $_SESSION['selectedLanguage'] = $selectedLanguage;
    setcookie('selectedLanguage', $selectedLanguage, time() + (365 * 24 * 60 * 60), '/');
}

// Check if the language is selected and set it in the session and cookie
if (isset($_POST['language'])) {
    $selectedLanguage = $_POST['language'];
    setLanguage($selectedLanguage);
} elseif (isset($_COOKIE['selectedLanguage'])) {
    $selectedLanguage = $_COOKIE['selectedLanguage'];
} else {
    // Default to English if no language is set
    $selectedLanguage = 'en';
    setLanguage($selectedLanguage);
}

// Define translations for each supported language
$translations = [
    'en' => [
        'title' => 'Khoj - The Search Engine You Control',
        'heading' => 'The Search Engine You Control',
        'search_placeholder' => 'Search',
        'logo_title' => 'Logo of our site',
        'logo_alt' => 'Site logo',
        'description' => 'Search the web for sites and images.',
        'keywords' => 'search engine, Khoj, websites',
    ],
    'hi' => [
        'title' => 'खोज - आपका नियंत्रण करने वाला खोज इंजन',
        'heading' => 'आपका नियंत्रण करने वाला खोज इंजन',
        'search_placeholder' => 'खोजें',
        'logo_title' => 'हमारी साइट का लोगो',
        'logo_alt' => 'साइट लोगो',
        'description' => 'साइट्स और छवियों के लिए वेब खोजें।',
        'keywords' => 'खोज इंजन, खोज, वेबसाइट',
    ],
    'mr' => [
        'title' => 'खोज - आपल्याला नियंत्रित करता येणारा शोध इंजन',
        'heading' => 'आपल्याला नियंत्रित करता येणारा खोज इंजन',
        'search_placeholder' => 'शोध',
        'logo_title' => 'आमच्या साइटचा लोगो',
        'logo_alt' => 'साइट लोगो',
        'description' => 'साइट्स आणि छवियां शोधा.',
        'keywords' => 'शोध इंजन, खोज, वेबसाइट',
    ],
];

// Get the selected translations
$selectedTranslations = $translations[$selectedLanguage];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $selectedTranslations['title'] ?></title>
    <meta name="description" content="<?= $selectedTranslations['description'] ?>">
    <meta name="keywords" content="<?= $selectedTranslations['keywords'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="src/assets/design.css">
    <style>
        .welcome-message {
            text-align: center;
            font-size: 24px;
            margin: 20px;
        }

        .language-selection {
            display: block;
            text-align: center;
            margin: 20px;
        }

        .language-buttons {
            display: inline-block;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            margin: 10px;
        }

        .language-buttons:hover {
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="welcome-message">
        <p>We welcome you on platform KHOJ - Private search engine</p>
    </div>

    <div class="language-selection">
        <p>Select your preferred language:</p>
        <form method="POST">
            <button class="language-buttons" type="submit" name="language" value="en" <?= $selectedLanguage === 'en' ? 'disabled' : '' ?>>English</button>
            <button class="language-buttons" type="submit" name="language" value="hi" <?= $selectedLanguage === 'hi' ? 'disabled' : '' ?>>Hindi</button>
            <button class="language-buttons" type="submit" name="language" value="mr" <?= $selectedLanguage === 'mr' ? 'disabled' : '' ?>>Marathi</button>
        </form>
    </div>

    <div class="home-page">
        <div class="mainSection">
            <div class="logo-home">
                <img src="src/assets/logo.png" class="logo-home" title="<?= $selectedTranslations['logo_title'] ?>" alt="<?= $selectedTranslations['logo_alt'] ?>">
            </div>
            <div class="heading">
                <h1><?= $selectedTranslations['heading'] ?></h1>
            </div>
            <div class="searchContainer">
                <form action="search.php" method="GET">
                    <div class="searchbar">
                        <input class="searchBox" type="text" name="term" autocomplete="off" placeholder="<?= $selectedTranslations['search_placeholder'] ?>" >
                        <input class="searchButton" type="submit" value="&#128269;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
