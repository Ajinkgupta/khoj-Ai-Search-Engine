<?php
class ApiSiteResultsProvider
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function getResultsAsJson($page, $pageSize, $term)
    {
        // Calculate the starting point for results
        $fromLimit = ($page - 1) * $pageSize;

        // Prepare the database query with pagination
        $query = $this->con->prepare("SELECT * 
            FROM sites WHERE title LIKE :term 
            OR url LIKE :term 
            OR keywords LIKE :term 
            OR description LIKE :term
            ORDER BY clicks DESC
            LIMIT :fromLimit, :pageSize");

        $searchTerm = "%" . $term . "%";
        $query->bindParam(":term", $searchTerm);
        $query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
        $query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
        $query->execute();

        $results = [];
        $id = 1; // Start with ID 1

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $url = stripslashes($row["url"]); // Remove backslashes
            $title = $row["title"];
            $description = $row["description"];

            $resultItem = [
                "id" => $id,
                "url" => $url,
                "title" => $title,
                "description" => $description,
            ];

            $results[] = $resultItem;
            $id++;
        }

        return json_encode($results, JSON_PRETTY_PRINT);
    }
}
?>
