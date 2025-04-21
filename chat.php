<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$chatFile = 'chat.json';

// Handle new message submission
if (isset($_POST['message'])) {
    $username = $_SESSION['username'];
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($message)) {
    if (strpos($message, '/') === 0) { // If the message starts with '/'
        // Process commands
        $commandParts = explode(' ', $message, 2);
        $command = strtolower($commandParts[0]);
        $argument = $commandParts[1] ?? '';

        switch ($command) {
            case '/anon':
                $username = 'Anonymous';
                $message = $argument;
                break;
            case '/foto':
                $message = '<img src="' . htmlspecialchars($argument) . '" alt="Image" style="max-width: 30%;">';
                break;
            case '/link':
                $message = '<a href="' . htmlspecialchars($argument) . '" target="_blank">' . htmlspecialchars($argument) . '</a>';
                break;
            case '/tellraw':
                $username = '';
                $message = $argument;
                break;
            case '/dellog32768':
                file_put_contents($chatFile, json_encode([['user' => 'Server', 'message' => 'Cleared Chat Log']]));
                exit;
        }
    }

    // Save processed message
    $chatData = file_exists($chatFile) ? json_decode(file_get_contents($chatFile), true) : [];
    $chatData[] = ['user' => $username, 'message' => $message];

    // Limit to 7 messages
    if (count($chatData) > 7) {
        array_shift($chatData);
    }

    file_put_contents($chatFile, json_encode($chatData));
}
    exit;
}

// Load messages for display
$messages = file_exists($chatFile) ? json_decode(file_get_contents($chatFile), true) : [];
?>
	

	
	
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLATFORM Public Chat</title>
    <style>
      body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light background */
    color: #212529; /* Dark text */
}

header {
    background-color: #ffffff; /* Light header */
    padding: 1rem;
    text-align: center;
    color: #000000;
    border-bottom: 1px solid #dee2e6; /* Subtle border */
}

main {
    text-align: center;
    padding: 1rem;
}

.chat-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 1rem;
    background-color: #ffffff; /* Light chat container */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #dee2e6; /* Light border */
}

.chat-display {
    height: 300px;
    overflow-y: auto;
    background-color: #ffffff; /* Light chat background */
    padding: 1rem;
    border: 1px solid #dee2e6; /* Light border */
    border-radius: 4px;
    margin-bottom: 1rem;
    color: #212529;
}

.chat-message {
    margin-bottom: 0.5rem;
    text-align: left;
}

.chat-message span.user {
    font-weight: bold;
    color: #007bff; /* Blue user text */
}

.chat-message span.message {
    color: #212529;
}

.message-form {
    display: flex;
    gap: 1rem;
}

.message-form input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #dee2e6; /* Light border */
    border-radius: 4px;
    background-color: #ffffff; /* Light input background */
    color: #212529;
}

.message-form input:focus {
    outline: none;
    border-color: #80bdff; /* Light blue focus */
}

.message-form button {
    padding: 0.75rem;
    background-color: #007bff; /* Blue button */
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.message-form button:hover {
    background-color: #0056b3; /* Darker blue */
}

nav {
    background-color: #ffffff; /* Light navbar */
    overflow-x: auto;
    white-space: nowrap;
    padding: 16px 0;
    text-align: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 999;
    border-bottom: 1px solid #dee2e6;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: inline-flex;
}

nav ul li {
    margin: 0 15px;
    display: inline-block;
}

nav ul li a {
    text-decoration: none;
    color: #007bff; /* Blue link */
    font-size: 18px;
    font-weight: bold;
    padding: 5px 10px;
    transition: color 0.3s ease, background-color 0.3s ease;
}

nav ul li a:hover {
    color: #0056b3; /* Darker blue */
    background-color: #e9ecef; /* Light hover */
    border-radius: 5px;
}

/* Scrollbar styling */
nav::-webkit-scrollbar {
    height: 6px;
}

nav::-webkit-scrollbar-thumb {
    background-color: #adb5bd; /* Light scrollbar */
    border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
    background-color: #6c757d; /* Darker scrollbar */
}

/* Add padding to the body to prevent content from being hidden under fixed navbar */
body {
    padding-top: 50px;
}
    </style>
</head>
<body>
	   <?php if (isset($_SESSION['username'])): ?>
    <nav>
        <ul>
        	  <li><a href="index.php">Home 🏠</a></li>
            <li><a href="upload.php">Upload 📤</a></li>
            <li><a href="profile.php">Me 👤</a></li>
            <li><a href="search.php">Search 🔍</a></li>
               <li><a href="chat.php">Public Chat 💬</a></li>
                
                  
   <li><a href="subbed.php">Subscriptions 👥</a></li>
     <li><a href="more.php">More ⚙️</a></li>
 <!-- Link to Search Page -->
        </ul>
    </nav>
<?php endif; ?>
	
    <header>
        <h1>PLATFORM Public Chat</h1>
    </header>
         <!-- Navbar for logged-in users -->


    <div id="ping-display" style="position: absolute; top: 5px; right: 10px; color: #ccc; font-size: 14px;  z-index: 1000;">Ping:...</div>
<script>
    setInterval(() => {
        const startTime = performance.now();
        fetch(window.location.href, { method: 'HEAD' })
            .then(() => {
                const ping = Math.round(performance.now() - startTime);
                document.getElementById('ping-display').textContent = `Ping: ${ping}ms`;
            })
            .catch(() => {
                document.getElementById('ping-display').textContent = 'Ping: Error';
            });
    }, 1000);
</script>
    <main>
    	<p>Real-time but Depends on Ping</p>  <a href="slcmd.html" target="_blank"  style="color: #44FF00;" >Slash CMD Info</a>
   
        <div class="chat-container">
            <div class="chat-display" id="chatDisplay">
                <?php foreach ($messages as $msg): ?>
                    <div class="chat-message">
                        <span class="user"><?php echo $msg['user'] ? '[' . $msg['user'] . ']' : ''; ?></span>
                        <span class="message"><?php echo $msg['message']; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <form class="message-form" id="messageForm">
                <input type="text" name="message" id="messageInput" placeholder="Write your message..." maxlength="128" required>
                <button type="submit">Send</button>
            </form>
        </div>
      
 </main>
 
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const chatDisplay = document.getElementById('chatDisplay');

            // Auto-scroll to the bottom on page load
            chatDisplay.scrollTop = chatDisplay.scrollHeight;

            // Refresh chat every 2 seconds
            setInterval(() => {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newChatDisplay = doc.getElementById('chatDisplay').innerHTML;
                        chatDisplay.innerHTML = newChatDisplay;

                        // Scroll to bottom after new messages are loaded
                        chatDisplay.scrollTop = chatDisplay.scrollHeight;
                    });
            }, 1000);

            // Handle message submission
            messageForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(messageForm);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData,
                }).then(() => {
                    messageInput.value = '';
                    messageInput.focus();
                    chatDisplay.scrollTop = chatDisplay.scrollHeight; // Scroll to bottom when a new message is sent
                });
            });
        });
    </script>
   
</body>
</html>       