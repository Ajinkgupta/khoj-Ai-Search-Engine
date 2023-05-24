<?php

$searchTerm = isset($_GET['term']) ? $_GET['term'] : "";
$searchTermEncoded = urlencode($searchTerm);

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://youtube-search-results.p.rapidapi.com/youtube-search/?q=$searchTermEncoded",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Host: youtube-search-results.p.rapidapi.com",
        "X-RapidAPI-Key: 4d5fdb515amsh34d74db809e079ep1408b3jsnb23a68d88dc9"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$resultsProvider= 30;
curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $videos = json_decode($response, true);

    if (isset($videos['items']) && is_array($videos['items'])) {
        echo '<div class="grid">';

        foreach ($videos['items'] as $video) {
            $title = $video['title'];
            $url = "https://www.youtube.com/watch?v=" . $video['id'];
            $thumbnail = $video['bestThumbnail']['url'];

            echo '<div class="item">';
            echo '<a href="' . $url . '">';
            echo '<img src="' . $thumbnail . '">';
            echo '<h3>' . $title . '</h3>';
            echo '</a>';
            echo '</div>';
        }

        echo '</div>';
    } else {
        echo "No videos found.";
    }
}
?>
