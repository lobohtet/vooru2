<?php
session_start();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	
    
<meta name="description" content="Lightweight Video Community">
<meta name="keywords" content="video, community, sharing">
<meta name="robots" content="index, follow">
    	<meta property="og:title" content="PLATFORM Video Community">
<meta property="og:description" content="PLATFORM is a small video community built with PHP">
<meta property="og:image" content="https://files.catbox.moe/71up63.jpg">
<meta property="og:url" content="https://dawolf.infy.uk">

<style>
	
   /* General body styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light background */
    color: #212529; /* Dark text for readability */
    margin: 0;
    padding: 0;
}

.divider {
    height: 1px;
    background-color: #dee2e6; /* Light gray divider */
    margin: 20px 0;
    width: 100%;
}

/* Header styling */
header {
    background-color: #ffffff; /* Light header background */
    color: #000000;
    padding: 10px;
    border-bottom: 1px solid #dee2e6; /* Subtle shadow effect */
}

header h1 {
    margin: 0;
    font-size: 24px;
    display: inline-block;
}

/* Navbar styling */
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
    color: #007bff; /* Blue links */
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
    background-color: #6c757d; /* Darker scrollbar on hover */
}

/* Add padding to prevent content from being hidden under the navbar */
body {
    padding-top: 50px;
}

/* Videos container styling */
.videos {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding: 20px;
}

.videos a {
    display: flex;
    flex-direction: column;
    width: calc(33.333% - 20px);
    max-width: 300px;
    color: #212529;
    text-decoration: none;
    background-color: #ffffff;
    border-radius: 8px;
    padding: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: left;
    border: 1px solid #dee2e6; /* Light border */
}

.videos img {
    width: 100%;
    height: auto;
    aspect-ratio: 16/9;
    object-fit: cover;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Lighter shadow */
}

.videos h3, .video-info {
    text-align: left;
    margin: 2px 0;
}

@media (max-width: 900px) {
    .videos a {
        width: calc(50% - 20px);
    }
}

@media (max-width: 600px) {
    .videos a {
        width: 100%;
    }
}

.videos a:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2); /* Light hover shadow */
}

/* Video info text */
.video-info {
    margin: 2px 0;
    font-size: 14px;
    color: #495057; /* Muted dark text */
    text-align: left;
}

/* Footer styling */
footer {
    background-color: #ffffff;
    color: #212529;
    padding: 10px;
    text-align: center;
    border-top: 1px solid #dee2e6;
}

/* Button styling */
button {
    background-color: #dc3545; /* Red button */
    color: #ffffff;
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    font-size: 12px;
    text-align: right;
}

/* Profile picture for videos */
.video-pfp {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #adb5bd; /* Light border */
    margin-right: 8px;
}

</style>
    <title> PLATFORM Video Community</title>
        <link rel="icon" href="favicon.ico" type="image/x-icon"> 
</head>
<body>
	  <!-- Navbar for logged-in users -->
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
	
    <!-- Header -->
    <header>
    	<hr style="border: 0; height: 1px; background: #444; opacity: 0.6; margin: 20px 0;">
        <h1> PLATFORM </h1>
        
        <hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
        	
        <?php if (isset($_SESSION['username'])): ?>
            <p>Hey, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
         
        <?php else: ?>
            <a href="login.php"><button>Log In 🧩</button></a>
        <?php endif; ?>
        

    </header>

  
    <!-- Videos Section -->
<div class="videos">
    <?php
    $videos = [];
    $directory = 'tbvidsdat/';

    // Scan for all JSON files in the directory
    foreach (glob($directory . "*.json") as $file) {
        $video_data = json_decode(file_get_contents($file), true);
        $videos[] = $video_data;
    }

    // Shuffle the videos array
    shuffle($videos);

    // Limit the number of videos to 15
    $videos = array_slice($videos, 0, 15);
    ?>

    <?php foreach ($videos as $index => $video): ?>
        <a href="view_video.php?video=<?php echo urlencode(basename($video['video_title']) . '.json'); ?>">
            <img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" alt="Thumbnail">
            <h3><?php echo htmlspecialchars($video['video_title']); ?></h3>
            <p class="video-info"><?php echo htmlspecialchars($video['video_description']); ?></p>
            <p class="video-info">Uploaded by: <?php echo htmlspecialchars($video['username']); ?></p>
            <div class="video-info">
                <span class="upload-date"><?php echo htmlspecialchars($video['upload_date']); ?></span>
             
            </div>
               
            
        </a>
    <?php endforeach; ?>
</div>



<!-- Refresh Button -->
<div class="refresh-container">
    <button id="refreshButton" class="refresh-button">Fresh 🔃</button>
</div>


	
<!-- Modal and additional styling for button -->
<style>
    .refresh-container {
        text-align: center;
        margin-top: 20px;
    }

    .refresh-button {
        background-color: #32a4ff; /* Primary blue background */
        color: #ffffff; /* White text */
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .refresh-button:hover {
        background-color: #2486cc; /* Slightly darker blue on hover */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Subtle hover shadow */
    }
</style>

<!-- JavaScript for Refresh Button -->
<script>
    // When the user clicks the refresh button, reload the page
    document.getElementById("refreshButton").onclick = function() {
        location.reload();
    };
</script>



</body>
</html>