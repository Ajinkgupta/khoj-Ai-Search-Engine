<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari&display=swap" rel="stylesheet">
    <title>khoj</title>
    <meta charset="UTF-8">
    <meta name="description" content="<?= $translations['description'] ?>">
    <meta name="keywords" content="<?= $translations['keywords'] ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/grid.css">
    <link rel="stylesheet" type="text/css" href="assets/css/search.css">
    <link rel="stylesheet" type="text/css" href="assets/css/home.css">
    <link rel="stylesheet" type="text/css" href="assets/css/special.css">

    <link rel="search" type="application/opensearchdescription+xml" title="KHOJ" href="./opensearch.xml">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    
    <style>
        /* Your CSS styles remain unchanged */

        :root {
            --color1: #d64e45;
            --color2: #f7f2a3;
            --color3: #c9d893;
            --color4: #398d70;
            --color5: #3e5040;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Chat message */
        .chat-message {
            background-color: var(--color2);
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Card column */
        .card-column {
            display: flex;
            flex-direction: column;
        }

        /* Cards */
        .card {
            background-color: var(--color1);
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        /* UI layout */
        .container {
            display: flex;
            height: 100vh;
        }

         /* Full screen container */
         .container {
            width: 100vw;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }


        .first-part {
            width: 40%;
            background-color: #f0f0f0;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .second-part {
            width: 60%;
            padding: 0px;
            position: relative;
            overflow: hidden;
        }

        /* Button */
        .color-change-btn {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        /* Dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
        
        .chat-container {
            max-height: calc(100vh - 60px);
            overflow-y: scroll;
            padding: 0px;
            margin-top: -20px;
        }
        
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 8px;
            margin: 5px;
        }
        
        .user-message {
            background-color: #95d7ae;
            align-self: flex-end;
            box-shadow: -3px -3px 6px 2px #ffffff, 5px 5px 8px 0px rgba(0, 0, 0, 0.17), 1px 2px 2px 0px rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 20px;
        }
        
        .ai-message {
            background-color: #b3cde0;
            align-self: flex-start;
            box-shadow: -3px -3px 6px 2px #ffffff, 5px 5px 8px 0px rgba(0, 0, 0, 0.17), 1px 2px 2px 0px rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 20px;
        }
         
        .chatbox-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
        }
        
        .send-button {
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            background-color: #4caf50;
            color: #fff;
            border-radius: 20px;
            cursor: pointer;
        }
   
        .chatbox-container {
            width: calc(100%);
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="first-part">
            <div class="chat-container" id="chat-container">
                <!-- Chat messages will be displayed here -->
            </div>
            <div class="chatbox-container">
                <input type="text" id="user-message" class="chatbox-input" placeholder="Type your message...">
                <button onclick="sendMessage()" class="send-button">Send</button>
            </div>
        </div>
        <div class="second-part">
            <iframe id="getPhpFrame" src="get.php" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
    </div>

    <script>
        function updateChat(message, type) {
            var chatContainer = document.getElementById('chat-container');
            var messageDiv = document.createElement('div');
            messageDiv.className = 'message ' + type + '-message';
            messageDiv.textContent = message;
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function sendMessage() {
            var userMessage = document.getElementById('user-message').value.trim();
            if (userMessage !== '') {
                updateChat(userMessage, 'user');
                document.getElementById('user-message').value = '';
                
                // Fetch data from process_message.php
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var aiMessage = xhr.responseText;
                            updateChat(aiMessage, 'ai');
                            document.getElementById('getPhpFrame').src = 'get.php?term=' + encodeURIComponent(userMessage);
                        } else {
                            updateChat('Error: Unable to process your request.', 'ai');
                        }
                    }
                };
                xhr.open('POST', 'process_message.php');
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('user_message=' + encodeURIComponent(userMessage));
            }
        }

        // Load chat history from local storage on page load
        document.addEventListener('DOMContentLoaded', function() {
            var chatHistory = JSON.parse(localStorage.getItem('chatHistory')) || [];
            chatHistory.forEach(function(message) {
                updateChat(message.message, message.type);
            });
        });

        // Save chat history to local storage on page unload
        window.addEventListener('beforeunload', function() {
            var chatHistory = document.querySelectorAll('.message');
            var chatHistoryArray = [];
            chatHistory.forEach(function(messageDiv) {
                var type = messageDiv.classList.contains('user-message') ? 'user' : 'ai';
                var message = messageDiv.innerHTML;
                chatHistoryArray.push({ type: type, message: message });
            });
            localStorage.setItem('chatHistory', JSON.stringify(chatHistoryArray));
        });
    </script>
</body>
</html>
