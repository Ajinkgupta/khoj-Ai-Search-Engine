<?php
header("Content-Type: application/json");

// Include the database connection and ApiSiteResultsProvider class
include("../config/config.php"); // Include your database connection file
include("../provider/apisites.php"); // Replace with the path to your ApiSiteResultsProvider class

// Check if the 'term' parameter is set in the URL
if (isset($_GET['term'])) {
    $term = $_GET['term'];

    // Create an instance of the ApiSiteResultsProvider class
    $resultsProvider = new ApiSiteResultsProvider($con);

    // Retrieve the search results in JSON format
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Optional: Allow pagination
    $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 10; // Optional: Set the page size
    $resultsJson = $resultsProvider->getResultsAsJson($page, $pageSize, $term);

    // Output the JSON results
    echo $resultsJson;
} else {
    // If 'term' parameter is not provided, return an error message
    echo json_encode(["error" => "Missing 'term' parameter"]);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);