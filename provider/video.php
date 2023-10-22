<?php

class VideoResultsProvider
{
    private $videos;

    public function __construct($apiHost, $apiKey)
    {
        $this->searchVideos($apiHost, $apiKey);
    }

    private function searchVideos($apiHost, $apiKey)
    {
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
                "X-RapidAPI-Host: https://youtube-search-results.p.rapidapi.com",
                "X-RapidAPI-Key: 4d5fdb515amsh34d74db809e079ep1408b3jsnb23a68d88dc9"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            $this->videos = false;
        } else {
            $videos = json_decode($response, true);

            if (isset($videos['items']) && is_array($videos['items'])) {
                $this->videos = $videos['items'];
            } else {
                $this->videos = false;
            }
        }
    }

    public function getResultsHtml()
    {
        if ($this->videos) {
            $resultsHtml = "<div class='videoResults'>";
            foreach ($this->videos as $video) {
                $title = $video['title'];
                $url = "https://www.youtube.com/watch?v=" . $video['id'];
                $thumbnail = $video['bestThumbnail']['url'];

                $resultHtml = "<div class='resultContainer'>
                    <h3 class='title'>
                        <a class='result' href='$url'>
                            $title
                        </a>
                    </h3>
                    <span class='url'>$url</span>
                    <img src='$thumbnail' alt='Video Thumbnail' class='thumbnail'>
                </div>";

                $resultsHtml .= $resultHtml;
            }
            $resultsHtml .= "</div>";
        } else {
            $resultsHtml = "No videos found.";
        }

        return $resultsHtml;
    }

    public function getNumResults()
    {
        if (is_array($this->videos)) {
            return count($this->videos);
        } else {
            return 0;
        }
    }
}
?>
