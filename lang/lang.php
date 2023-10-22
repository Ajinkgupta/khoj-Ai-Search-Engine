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

// Include all language files
$availableLanguages = ['en', 'mr', 'hi', 'sa']; // Add more languages as needed
foreach ($availableLanguages as $language) {
    if ($selectedLanguage === $language) {
        include("$language.php");
        break; // Include the selected language and exit the loop
    }
}
 
?>
 