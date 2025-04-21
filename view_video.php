	<?php session_start(); ?>

<!-- Nav bar visible only for logged-in users -->
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
	
	

<?php
if (isset($_GET['video'])) {
    $video_file = 'tbvidsdat/' . basename($_GET['video']);
    if (file_exists($video_file)) {
        $video = json_decode(file_get_contents($video_file), true);
        
        // Increment view count and save it back to the file
        $video['views'] = isset($video['views']) ? $video['views'] + 1 : 1;
        file_put_contents($video_file, json_encode($video, JSON_PRETTY_PRINT));

        // Display video details
        echo "<div class='video-detail'>";
        echo "<video controls autoplay><source src='" . htmlspecialchars($video['video_url']) . "' type='video/mp4'></video>";
        echo "<h1>" . htmlspecialchars($video['video_title']) . "</h1>";
        echo "<p>" . htmlspecialchars($video['video_description']) . "</p>";
        echo "<p>Uploaded by <a href='public.php?user=" . urlencode($video['username']) . ".json'><b>" . htmlspecialchars($video['username']) . "</b></a> on <i>" . htmlspecialchars($video['upload_date']) . "</i></p>";
        echo "<p>Views: " . htmlspecialchars($video['views']) . "</p>";

        // Copy Link Button (placed below view count)
        echo "<button id='copyLinkButton'>Copy Link 📋</button>";
        echo "</div>";

        // Comment form
        echo "<form method='POST' action='comment_submit.php'>";
        echo "<textarea name='comment_text' placeholder='Write a comment' maxlength='128' required></textarea><br>";
        echo "<input type='hidden' name='video' value='" . htmlspecialchars($_GET['video']) . "'>";
        echo "<input type='submit' value='Submit'>";
        echo "</form>";

        // Display Comments
        echo "<div class='comments-section'>";
        echo "<h2>Comments</h2>";
        
        // Check if there are comments and display them
        if (isset($video['comments']) && count($video['comments']) > 0) {
            // Show comments, most recent on top
            foreach (array_reverse($video['comments']) as $comment) {
                echo "<div class='comment'>";
                echo "<p><strong>" . htmlspecialchars($comment['cmter']) . "</strong> commented:</p>";
                echo "<p>" . htmlspecialchars($comment['cmt']) . "</p>";
                echo "<hr>";
                echo "</div>";
            }
        } else {
            echo "<p>No comments yet.</p>";
        }

        echo "</div>";
    } else {
        echo "<p>Video not found.</p>";
    }
} else {
    echo "<p>No video specified.</p>";
}
?>

<!-- JavaScript to copy the current URL -->
<script>
    document.getElementById("copyLinkButton").addEventListener("click", function() {
        // Create a temporary input element to hold the URL
        var tempInput = document.createElement("input");
        tempInput.value = window.location.href; // Get current URL
        document.body.appendChild(tempInput); // Append to body
        tempInput.select(); // Select the input field
        document.execCommand("copy"); // Copy the text to clipboard
        document.body.removeChild(tempInput); // Remove the temporary input element

        // Optional: Alert or notify the user
        alert("Link copied to clipboard!");
    });
</script>
	<style>
    /* General body styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4; /* Light grey background */
    color: #2c2f37; /* Dark text color */
    margin: 0;
    padding: 0;
    text-align: left;
}

/* Header */
header {
    background-color: #ffffff; /* White header */
    color: #2c2f37;
    padding: 10px;
    text-align: center;
    border-bottom: 2px solid #ddd; /* Light grey border */
}

header h1 {
    margin: 0;
    font-size: 24px;
}

/* Links in header */
a {
    color: #0078d4; /* Blue links */
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Footer */
footer {
    background-color: #ffffff; /* White footer */
    color: #2c2f37;
    padding: 10px;
    text-align: center;
    position: fixed;
    bottom: 0;
    width: 100%;
    border-top: 2px solid #ddd; /* Light grey border */
}

/* Button Styling */
button {
    background-color: #0078d4; /* Blue button */
    color: #fff;
    border: none;
    padding: 10px 15px;
    font-size: 12px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #005ea6; /* Darker blue */
}

/* Video content styling */
.video-content {
    background-color: #ffffff; /* White box */
    border-radius: 8px;
    padding: 15px;
    margin: 10px 0;
    text-align: center;
    border: 1px solid #ddd; /* Light border */
}

.video-content img {
    width: auto;
    height: auto;
    border-radius: 5px;
}

.video-content h3 {
    color: #2c2f37;
    font-size: 18px;
    margin: 10px 0;
    text-align: left;
}

.video-content p {
    color: #555;
    font-size: 14px;
    text-align: left;
}

/* Form input fields */
input[type="text"], input[type="password"], textarea {
    background-color: #ffffff;
    color: #2c2f37;
    border: 1px solid #ccc;
    padding: 10px;
    width: 100%;
    margin-bottom: 10px;
    border-radius: 5px;
}

input[type="checkbox"] {
    margin-right: 10px;
}

/* Video Detail Page */
.video-detail {
    text-align: center;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    text-align: left;
    border: 1px solid #ddd;
}

.video-detail video {
    width: 100%;
    max-width: 800px;
    height: auto;
    display: block;
    margin: 0 auto;
}

/* Navbar styling */
nav {
    background-color: #ffffff;
    overflow-x: auto;
    white-space: nowrap;
    padding: 16px 0;
    text-align: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    border-bottom: 2px solid #ddd;
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
    color: #2c2f37;
    font-size: 18px;
    font-weight: bold;
    padding: 5px 10px;
    transition: color 0.3s ease, background-color 0.3s ease;
}

nav ul li a:hover {
    color: #0078d4;
    background-color: #e6f0fa;
    border-radius: 5px;
}

/* Scrollbar styling */
nav::-webkit-scrollbar {
    height: 6px;
}

nav::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
    background-color: #999;
}

/* Add padding to the body to prevent content from being hidden under fixed navbar */
body {
    padding-top: 50px;
}

/* Custom styles for light color palette */
.video-item {
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    transition: background-color 0.3s ease;
    border: 1px solid #ddd;
}

.video-item:hover {
    background-color: #f0f0f0;
}

/* Subscribe button */
.subscribe-button {
    background-color: #ff4747;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.subscribe-button:hover {
    background-color: #d93636;
}

/* Video grid */
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
    color: #2c2f37;
    text-decoration: none;
    background-color: #ffffff;
    border-radius: 8px;
    padding: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: left;
    border: 1px solid #ddd;
}

.videos img {
    width: 100%;
    height: auto;
    aspect-ratio: 16/9;
    object-fit: cover;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.videos h3, .video-info {
    text-align: left;
    margin: 5px 0;
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
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
}

/* Video Info */
.video-info {
    font-size: 14px;
    color: #555;
    text-align: left;
}
</style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	
    
        <hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
    
    <h2> Other Videos </h2>
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
    $videos = array_slice($videos, 0, 3);
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


<a href="index.php" ><button> More... </button></a>