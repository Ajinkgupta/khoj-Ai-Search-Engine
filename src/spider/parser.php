<?php

class DomDocumentParser 
{
    private $doc;

    public function __construct($url) 
    {
        $options = array(
            'http' => array(
                'method' => "GET",
                'header' => "User-Agent: Khojbot/0.1\n"
            )
        );
        $context = stream_context_create($options);

        // Create a new DOMDocument instance
        $this->doc = new DOMDocument();

        // Load the HTML content from the given URL using the context
        // Note: Error suppression (@) is unnecessary in PHP 7.0+ since it supports HTML5
        $this->doc->loadHTML(file_get_contents($url, false, $context));
    }

    public function getLinks() 
    {
        // Get all anchor (a) tags from the loaded document
        return $this->doc->getElementsByTagName("a");
    }

    public function getTitleTags() 
    {
        // Get all title tags from the loaded document
        return $this->doc->getElementsByTagName("title");
    }

    public function getMetaTags() 
    {
        // Get all meta tags from the loaded document
        return $this->doc->getElementsByTagName("meta");
    }

    public function getImages() 
    {
        // Get all image (img) tags from the loaded document
        return $this->doc->getElementsByTagName("img");
    }
}

?>
