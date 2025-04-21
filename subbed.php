<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "You must be logged in to view your subscriptions.";
    exit;
}

$logged_in_user = $_SESSION['username'];

// Find all users' data files
$directory = 'tbusersdat/';
$subscribed_videos = [];

// Loop through all user files
foreach (glob($directory . "*.json") as $file) {
    $user_data = json_decode(file_get_contents($file), true);
    
    // Check if the logged-in user is in the subscriber list of each user
    if (isset($user_data['subscribers']) && in_array($logged_in_user, $user_data['subscribers'])) {
        // Get the videos uploaded by this user
        $user_videos = [];
        $video_directory = 'tbvidsdat/';

        foreach (glob($video_directory . "*.json") as $video_file) {
            $video_data = json_decode(file_get_contents($video_file), true);
            if ($video_data['username'] == $user_data['username']) {
                $user_videos[] = $video_data;
            }
        }
        
        // Add videos from this user to the list
        $subscribed_videos = array_merge($subscribed_videos, $user_videos);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subbed Profiles</title>
<style>
   body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light grey background */
    color: #212529; /* Dark text color */
    margin: 0;
    padding: 0;
    text-align: left;
}

header {
    background-color: #ffffff; /* White background */
    color: #212529; /* Dark text */
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #dee2e6; /* Light border */
}

header h1 {
    margin: 0;
    font-size: 24px;
}

/* Navbar styling */
nav {
    background-color: #ffffff; /* White navbar */
    overflow-x: auto;
    white-space: nowrap;
    padding: 16px 0;
    text-align: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
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
    color: #007bff; /* Blue text */
    font-size: 18px;
    font-weight: bold;
    padding: 5px 10px;
    transition: color 0.3s ease, background-color 0.3s ease;
}

nav ul li a:hover {
    color: #0056b3; /* Darker blue */
    background-color: #e9ecef; /* Light hover background */
    border-radius: 5px;
}

/* Scrollbar styling */
nav::-webkit-scrollbar {
    height: 6px;
}

nav::-webkit-scrollbar-thumb {
    background-color: #ced4da; /* Light grey scrollbar */
    border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
    background-color: #007bff; /* Blue scrollbar hover */
}

/* Prevent content from being hidden under the fixed navbar */
body {
    padding-top: 50px;
}

/* User Videos */
.user-videos {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding: 20px;
}

.video-item {
    background-color: #ffffff; /* White background */
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    width: calc(33.33% - 20px);
    box-sizing: border-box;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}

.video-item img {
    width: 100%;
    height: auto;
    aspect-ratio: 16/9;
    object-fit: cover;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.video-item h3 {
    text-align: left;
    margin: 5px 0;
    color: #212529; /* Dark text */
}

/* Responsive Video Item */
@media (max-width: 768px) {
    .video-item {
        width: calc(50% - 20px);
    }
}

@media (max-width: 480px) {
    .video-item {
        width: 100%;
    }
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
        <h1>Videos from Subscribed Profiles</h1>
    </header>


    <div class="user-videos">
        <?php
        if (count($subscribed_videos) > 0) {
            foreach ($subscribed_videos as $video) {
                $video_filename = basename($video['video_title']) . '.json';
                echo "<div class='video-item'>";
                echo "<a href='view_video.php?video=" . urlencode($video_filename) . "'>";
                echo "<img src='" . htmlspecialchars($video['thumbnail_url']) . "' alt='Thumbnail' width='100'>";
                echo "<h3>" . htmlspecialchars($video['video_title']) . "</h3>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<p>You are not subscribed to any users with videos.</p>";
        }
        ?>
    </div>

</body>
</html>