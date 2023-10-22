<?php
include("../lang/lang.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
    $_SESSION = [];
    echo '<script>window.localStorage.clear();</script>';
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $translations['settings'] ?></title>
    <link rel="stylesheet" type="text/css" href="../assets/css/settings.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4"><?= $translations['settings'] ?></h1>

        <div class="bg-white rounded-lg shadow-md p-4">
            <form method="post" class="mb-4">
                <p class="mb-4"><?= $translations['settings_text'] ?> :</p>
                <button type="submit" name="clear" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600"><?= $translations['end'] ?></button>
            </form>

            <form method="POST">
                <div><?= $translations['choose_lang'] ?> :
                    <select class="dropdown" name="language" onchange="this.form.submit()">
                        <option value="en" <?= $selectedLanguage === 'en' ? 'selected' : '' ?>>English</option>
                        <option value="hi" <?= $selectedLanguage === 'hi' ? 'selected' : '' ?>>हिंदी</option>
                        <option value="mr" <?= $selectedLanguage === 'mr' ? 'selected' : '' ?>>मराठी</option>
                        <option value="sa" <?= $selectedLanguage === 'sa' ? 'selected' : '' ?>>संस्कृत</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="container mx-auto mt-8 mx-auto p-4">
        <h1 class="text-3xl font-semibold mb-4"><?= $translations['saved'] ?></h1>

        <div class="mb-4">
            <button class="clear-button px-4 py-2 text-white rounded-md mr-4" onclick="clearLocalStorage()"><?= $translations['delete_all'] ?></button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- JavaScript will populate the saved results here -->
        </div>
    </div>

    <script>
        function loadSavedResults() {
            const savedResults = JSON.parse(localStorage.getItem('savedResults')) || [];
            const savedResultsContainer = document.querySelector('.grid');

            if (savedResults.length === 0) {
                savedResultsContainer.innerHTML = '<p><?= $translations['not_saved_found'] ?></p>';
            } else {
                savedResults.forEach(function(savedResult) {
                    const resultContainer = document.createElement('div');
                    resultContainer.classList.add('resultContainer', 'border', 'bg-white', 'shadow-md', 'p-4', 'rounded-lg', 'hover:shadow-lg', 'transition-transform', 'transform', 'hover:scale-105');

                    const reason = savedResult.reason;
                    if (reason) {
                        const reasonElement = document.createElement('p');
                        reasonElement.textContent = `Reason: ${reason}`;
                        reasonElement.classList.add('reason');
                        resultContainer.appendChild(reasonElement);
                    }

                    resultContainer.innerHTML += savedResult.result;
                    savedResultsContainer.appendChild(resultContainer);
                });
            }
        }

        function clearLocalStorage() {
            localStorage.clear();
            location.reload();
        }

        loadSavedResults();
    </script>
</body>
</html>