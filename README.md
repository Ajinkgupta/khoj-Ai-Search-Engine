# Khoj: A Search Engine with Crawler Written in PHP

Khoj is a web-based search engine built using PHP, MySQL, and Ajax. It includes a web crawler that can index and retrieve information from websites, as well as a search interface that allows users to search for specific keywords.

## Features
- Web crawler that can index and retrieve information from websites
- Search interface that allows users to search for specific keywords
- Image /Video/Sites
- Lightweight and easy to use
- Uses PHP, MySQL, and Ajax
   ### Upcoming Features
-  Chat interface using langchain + gemini pro api
-  chrome extension



## Requirements
- PHP 7.0 or higher
- MySQL 5.6 or higher
- Apache web server 

## Installation
Clone the repository using the following command:
 
```git clone https://github.com/Ajinkgupta/khoj.git```
Create a MySQL database for Khoj and import the db.sql file included in the repository. You can use the following commands to do so:

 
```mysql -u username -p
CREATE DATABASE khoj;
USE khoj;
source /path/to/db.sql;``` 


Configure the database credentials in the config.php file by modifying the following lines:

```
$host = "localhost";
$user = "username";
$password = "password";
$database = "khoj";```
Replace username and password with your MySQL username and password, respectively.

Upload the project files to your web server.

Open the index.php file in your web browser to use the search engine.
 

## Usage
- Enter a keyword in the search bar and click the search button.
- The search engine will retrieve relevant results from the indexed websites.
- Click on a search result to view the website. 
