<?php 

class GitHubResultsProvider
{
    private $githubResults;

    public function __construct($term)
    {
        $this->searchRepositories($term);
    }

    private function searchRepositories($term)
    {
        // URL for the GitHub API with the search term
        $apiUrl = "https://api.github.com/search/repositories?q=" . urlencode($term);

        // Set up cURL to make the HTTP request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'User-Agent: KHOJ', // Add a user agent header
        ]);

        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $this->githubResults = false;
        }

        // Close the cURL handle
        curl_close($ch);

        // Decode the JSON response
        $data = json_decode($response, true);

        if (!$data) {
            $this->githubResults = false;
        } else {
            // Extract and format the data you need
            $results = [];
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $result = [
                        'name' => $item['name'],
                        'html_url' => $item['html_url'],
                        'description' => $item['description'],
                    ];
                    $results[] = $result;
                }
            }
            $this->githubResults = $results;
        }
    }

    public function getResultsHtml()
    {
        if ($this->githubResults) {
            $resultsHtml = "<div class='siteResults'>";
            foreach ($this->githubResults as $result) {
                $name = $result['name'];
                $url = $result['html_url'];
                $description = $result['description'];

                $resultHtml = "<div class='resultContainer'>
                    <h3 class='title'>
                        <a class='result' href='$url' >
                            $name
                        </a>
                    </h3>
                    <span class='url'>$url</span>
                    <span class='description'>$description</span>
                </div>";

                $resultsHtml .= $resultHtml;
            }
            $resultsHtml .= "</div>";
        } else {
            $resultsHtml = "Error retrieving GitHub data.";
        }

        return $resultsHtml;
    }

    public function getNumResults()
    {
        // The number of GitHub search results is the count of items in the result
        return count($this->githubResults);
    }
}
?>