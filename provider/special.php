<?php

function generateQRCode($urlToEncode) {
    $qrCodeImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($urlToEncode);
    echo '<div class="special-results">';
    echo '<h2>Generated QR Code</h2>';
    echo '<img src="' . $qrCodeImageUrl . '" alt="Generated QR Code">';
    echo '</div>';
}

function getCurrentDateTime($timezone) {
    date_default_timezone_set($timezone);
    $currentDateTime = date('Y-m-d H:i:s');
    echo '<div class="special-results">';
    echo '<h2>Date and Time</h2>';
    echo '<p>Current date and time: ' . $currentDateTime . '</p>';
    echo '</div>';
}

function defineWord($word) {
    $url = "https://api.dictionaryapi.dev/api/v2/entries/en/" . urlencode($word);
    $response = file_get_contents($url);

    if ($response) {
        $data = json_decode($response);

        if (is_array($data) && count($data) > 0) {
            $entry = $data[0];
            echo '<div class="special-results">';
            echo '<h2>Word Definition</h2>';
            echo '<p>Word: ' . $entry->word . '</p>';

            if (isset($entry->phonetics) && is_array($entry->phonetics)) {
                echo '<p>Phonetics:</p>';
                foreach ($entry->phonetics as $phonetic) {
                    echo '<p>Pronunciation: ' . $phonetic->text . '</p>';
                    if (!empty($phonetic->audio)) {
                        echo '<audio controls>';
                        echo '<source src="' . $phonetic->audio . '" type="audio/mpeg">';
                        echo 'Your browser does not support the audio element.';
                        echo '</audio>';
                    }
                }
            }

            if (isset($entry->meanings) && is_array($entry->meanings)) {
                echo '<p>Meanings:</p>';
                foreach ($entry->meanings as $meaning) {
                    echo '<p>Part of Speech: ' . $meaning->partOfSpeech . '</p>';
                    if (isset($meaning->definitions) && is_array($meaning->definitions)) {
                        foreach ($meaning->definitions as $definition) {
                            echo '<p>Definition: ' . $definition->definition . '</p>';
                            if (isset($definition->example)) {
                                echo '<p>Example: ' . $definition->example . '</p>';
                            }
                        }
                    }
                }
            }

            echo '</div>';
        } else {
            echo '<div class="special-results">';
            echo '<h2>Word Definition</h2>';
            echo '<p>No definition found for: ' . $word . '</p>';
            echo '</div>';
        }
    } else {
        echo 'Unable to retrieve word definition.';
    }
}

function getRandomQuote() {
    $url = "https://api.quotable.io/random";

    $data = json_decode(file_get_contents($url));

    if (isset($data->content) && isset($data->author)) {
        $quote = $data->content;
        $author = $data->author;
        echo '<div class="special-results">';
        echo '<h2>Random Quote</h2>';
        echo '<p>' . $quote . '</p>';
        echo '<p>- ' . $author . '</p>';
        echo '</div>';
    }
}

if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];

    if (strpos($searchTerm, "qrcode:") === 0) {
        $urlToEncode = trim(substr($searchTerm, strlen("qrcode:")));
        generateQRCode($urlToEncode);
    } elseif (strpos($searchTerm, "datetime:") === 0) {
        getCurrentDateTime($searchTerm);
    } elseif (strpos($searchTerm, "define:") === 0) {
        $wordToDefine = trim(substr($searchTerm, strlen("define:")));
        defineWord($wordToDefine);
    } elseif (strpos($searchTerm, "quote:") === 0) {
        getRandomQuote();
    } else {
         
    }
}

?>
