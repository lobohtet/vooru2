<?php
session_start();

// Check if a user is specified in the URL
if (!isset($_GET['user'])) {
    echo "User not specified.";
    exit;
}

// Get the username from the URL (for example, public.php?user=jack.json)
$username_file = $_GET['user'];

// Assuming the username is stored in the filename without the .json extension
$username = basename($username_file, ".json");

// Path to the user data in the tbusersdat folder
$user_data_file = 'tbusersdat/' . $username_file;

// Check if the user's data file exists
if (!file_exists($user_data_file)) {
    echo "User data not found.";
    exit;
}

// Read user data from the JSON file
$user_data = json_decode(file_get_contents($user_data_file), true);

// If the user data is not valid, show an error
if (!$user_data) {
    echo "Invalid user data.";
    exit;
}

// Get the username from the data (use the "username" key here)
$user_name = isset($user_data['username']) ? htmlspecialchars($user_data['username']) : 'User Name Not Found';

// Get the subscribers list (initialize as empty array if not present)
$subscribers = isset($user_data['subscribers']) ? $user_data['subscribers'] : [];

// Check if the user is logged in and handle subscription
if (isset($_SESSION['username'])) {
    $logged_in_user = $_SESSION['username'];

    // Check if the user is already subscribed
    if (isset($_POST['subscribe'])) {
        // Subscribe the logged-in user to this user
        if (!in_array($logged_in_user, $subscribers)) {
            $subscribers[] = $logged_in_user; // Add to the subscriber list
            $user_data['subscribers'] = $subscribers; // Update the user's data

            // Save the updated user data back to the file
            file_put_contents($user_data_file, json_encode($user_data, JSON_PRETTY_PRINT));
        }
    }
}

// Directory where video JSON files are stored
$directory = 'tbvidsdat/';

// Find all videos uploaded by this user
$user_videos = [];

// Loop through all video files and check if the username matches
foreach (glob($directory . "*.json") as $file) {
    $video_data = json_decode(file_get_contents($file), true);
    if ($video_data['username'] == $username) {
        $user_videos[] = $video_data;
    }
}
?>
	
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
// Assuming the user data is already loaded into $user_data
if (isset($user_data['veri']) && $user_data['veri'] === true) {
    $verification_status = "Verified ✔️";
} else {
    $verification_status = "Not Verified ❌";
}
?>
	
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user_name; ?></title>
  <style>
   body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light background */
    color: #212529; /* Dark text for readability */
    margin: 0;
    padding: 0;
    text-align: left; /* Align all text to the left */
}

/* Header Styling */
header {
    background-color: #ffffff; /* Light header background */
    color: #000000;
    padding: 10px;
    border-bottom: 1px solid #dee2e6; /* Subtle border */
}

header h1 {
    margin: 0;
    font-size: 24px;
}

/* Video Section */
.user-videos {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center; /* Ensures items are centered */
    padding: 20px;
}

.video-item {
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    text-align: left;
    border: 1px solid #dee2e6; /* Light border */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Lighter shadow */
}

.video-item img {
    width: 100%;
    height: auto;
    aspect-ratio: 16/9; /* Forces consistent ratio */
    object-fit: cover;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.video-item h3 {
    text-align: left;
    margin: 5px 0;
    color: #000000;
}

/* Subscribe Button */
.subscribe-button {
    background-color: #FF3038;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.subscribe-button:hover {
    background-color: #56a29a;
}

/* Navbar */
nav {
    background-color: #ffffff;
    overflow-x: auto;
    white-space: nowrap;
    padding: 16px 0;
    text-align: center;
    position: fixed; /* Fix the navbar */
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
    background-color: #adb5bd;
    border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
    background-color: #6c757d;
}

/* Add padding to prevent content from being hidden under fixed navbar */
body {
    padding-top: 50px;
}

/* Profile Header */
.profile-header {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
}

/* Circular Profile Picture */
.profile-picture {
    width: 80px; /* Adjust size */
    height: 80px;
    border-radius: 50%; /* Makes it circular */
    object-fit: cover;
    border: 3px solid #dee2e6;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
</head>
<body>
			
				<div style="position: relative; width: 100%; height: 10px;">
    <p style="position: absolute; top: 10px; right: 10px; font-size: 15px; color: #32a4ff;">
        <?php echo $verification_status; ?>
    </p>
</div>
<!-- Profile Header -->
<div class="profile-header">

    <!-- Profile Picture (Circular) -->
    <img id="profilePicture" class="profile-picture" src="<?php echo htmlspecialchars($user_data['pfp'] ?? 'https://files.catbox.moe/0apv3v.png'); ?>" alt="Profile Picture">
    
    <!-- Username -->
    <h1><?php echo $user_name; ?></h1>
</div>
   
     <p style="font-size: 20px;">Subscribers: <strong><?php echo count($subscribers); ?> </strong></p>

    <?php if (isset($_SESSION['username'])): ?>
        <form method="post">
            <button onclick="return confirm('Are you sure you want to Subscribe? You cannot unsubscribe again.')" class="subscribe-button" type="submit" name="subscribe">Subscribe ➕</button>
            
        </form>
    <?php else: ?>
        <p><a href="login.php">Log in</a> to subscribe.</p>
    <?php endif; ?>
    	<button id='copyLinkButton'>Copy Link 📋</button>
<hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
    <h2>Uploaded Videos</h2>
    <div class="user-videos">
        <?php
        if (count($user_videos) > 0) {
            foreach ($user_videos as $video) {
                $video_filename = basename($video['video_title']) . '.json';
                echo "<div class='video-item'>";
                echo "<a href='view_video.php?video=" . urlencode($video_filename) . "'>";
                echo "<img src='" . htmlspecialchars($video['thumbnail_url']) . "' alt='Thumbnail' width='100'>";
                echo "<h3>" . htmlspecialchars($video['video_title']) . "</h3>";
                echo "</a>";
                echo "</div>";
            }
        } else {
            echo "<p>This user has not uploaded any videos yet.</p>";
        }
        ?>
    </div>
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
</body>
</html>