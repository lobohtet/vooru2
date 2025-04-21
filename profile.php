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
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<p style='text-align:center; font-size:20px;'>You must be logged in to view your profile.</p>";
    exit;
}

$username = $_SESSION['username'];
$user_directory = 'tbusersdat/';
$user_data_file = $user_directory . $username . '.json';

if (!file_exists($user_data_file)) {
    die("User data not found.");
}

// Load user data
$user_data = json_decode(file_get_contents($user_data_file), true);
$subscribers = $user_data['subscribers'] ?? [];
$profile_pic = $user_data['pfp'] ?? 'default.png'; // Default pfp if not set

// Save new profile picture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_pfp'])) {
    $new_pfp = trim($_POST['new_pfp']);
    if (!empty($new_pfp)) {
        $user_data['pfp'] = $new_pfp;
        file_put_contents($user_data_file, json_encode($user_data, JSON_PRETTY_PRINT));
        $profile_pic = $new_pfp;
    }
}
?>
	
<?php
// Assuming the user data is already loaded into $user_data
if (isset($user_data['veri']) && $user_data['veri'] === true) {
    $verification_status = "Verified ✔️";
} else {
    $verification_status = "Not Verified ❌";
}
?>


<!-- Display User Profile -->
<div class="user-profile">
		<div style="position: relative; width: 100%; height: 10px;">
    <p style="position: absolute; top: 10px; right: 10px; font-size: 15px; color: #32a4ff;">
        <?php echo $verification_status; ?>
    </p>
</div>
    <div class="profile-header">
    
        <!-- Clickable Profile Picture -->
     <img id="profilePic" src="<?php echo htmlspecialchars($profile_pic ?: 'https://files.catbox.moe/0apv3v.png'); ?>" alt="Profile Picture" class="profile-picture">
        
        <!-- Username -->
        <h2><?php echo htmlspecialchars($username); ?></h2>
    </div>

    

    <p style="font-size: 20px;">Subscribers: <strong><?php echo count($subscribers); ?></strong></p>
    
   
    <hr>
    
    <!-- Profile Picture Modal -->
    <div id="pfpModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Change Profile Picture</h3>
            <form method="post">
                <input type="text" name="new_pfp" placeholder="Enter image URL..." required>
                <button type="submit" class="save-button">Save</button>
            </form>
        </div>
    </div>

    <!-- Subscribers Modal -->
    <div id="subscribersModal" class="modal">
        <div class="modal-content">
            <a href="profile.php">Back </a>
            <span class="close">&times;</span>
            <h3>Subscribers of <?php echo htmlspecialchars($username); ?>:</h3>
            <ul>
                <?php
                if (!empty($subscribers)) {
                    foreach ($subscribers as $subscriber) {
                        echo "<li>" . htmlspecialchars($subscriber) . "</li>";
                    }
                } else {
                    echo "<li>No subscribers found.</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <hr>

    <!-- User Uploaded Videos -->
    <div class="user-videos">
        <h2>Your Uploaded Videos</h2>
        <?php
        $directory = 'tbvidsdat/';
        $user_videos = [];

        foreach (glob($directory . "*.json") as $file) {
            $video_data = json_decode(file_get_contents($file), true);
            if ($video_data['username'] == $username) {
                $user_videos[] = $video_data;
            }
        }

        if (!empty($user_videos)) {
            foreach ($user_videos as $video) {
                $video_filename = basename($video['video_title']) . '.json';
                echo "<div class='video-item'>";
                echo "<a href='view_video.php?video=" . urlencode($video_filename) . "'>";
                echo "<img src='" . htmlspecialchars($video['thumbnail_url']) . "' alt='Thumbnail'>";
                echo "<h3>" . htmlspecialchars($video['video_title']) . "</h3>";
                echo "</a>";

                echo "<form action='delete.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this video?\");'>";
                echo "<input type='hidden' name='video_title' value='" . htmlspecialchars($video['video_title']) . "'>";
                echo "<button type='submit' class='delete-button'>Delete</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No videos uploaded yet.</p>";
        }
        ?>
    </div>
</div>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Styles -->
<style>
    /* Body */
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light background */
    color: #212529; /* Dark text */
    margin: 0;
    padding: 0;
}

/* Header */
header {
    background-color: #ffffff; /* White header */
    color: #212529;
    padding: 15px;
    text-align: center;
    font-size: 22px;
    border-bottom: 2px solid #dee2e6;
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
    color: #007bff;
    font-size: 18px;
    font-weight: bold;
    padding: 5px 10px;
    transition: color 0.3s ease, background-color 0.3s ease;
}

nav ul li a:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-radius: 5px;
}

/* Scrollbar styling */
nav::-webkit-scrollbar {
    height: 6px;
}

nav::-webkit-scrollbar-thumb {
    background-color: #dee2e6;
    border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
    background-color: #007bff;
}

/* Prevent content from being hidden under the fixed navbar */
body {
    padding-top: 50px;
}

/* Dividers */
hr {
    border: none;
    border-top: 2px solid #ccc;
    margin: 20px 0;
}

/* User Profile */
.user-profile {
    max-width: 800px;
    margin: 20px auto;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #dee2e6;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

/* Subscribers Button */
.subscribers-button {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.subscribers-button:hover {
    background-color: #0056b3;
}

/* Video List */
.video-item {
    display: flex;
    align-items: center;
    background-color: #f8f9fa;
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.video-item img {
    width: 150px;
    height: auto;
    margin-right: 15px;
    border-radius: 5px;
}

.video-item h3 {
    font-size: 22px;
    color: #212529;
    margin: 0;
}

/* Delete Button */
.delete-button {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    margin-right: auto;
}

.delete-button:hover {
    background-color: #c82333;
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
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #007bff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Hover effect */
.profile-picture:hover {
    opacity: 0.7;
}

/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    background-color: #ffffff;
    color: #212529;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    border: 1px solid #dee2e6;
}

/* Close Button */
.close {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 25px;
    cursor: pointer;
}

.close:hover {
    color: #007bff;
}

/* Save Button */
.save-button {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
}

.save-button:hover {
    background-color: #0056b3;
}
</style>

<!-- JavaScript -->
<script>
    var pfpModal = document.getElementById("pfpModal");
    var profilePic = document.getElementById("profilePic");
    var closeButtons = document.getElementsByClassName("close");

    profilePic.onclick = function() {
        pfpModal.style.display = "block";
    }

    for (let btn of closeButtons) {
        btn.onclick = function() {
            pfpModal.style.display = "none";
        }
    }

    window.onclick = function(event) {
        if (event.target == pfpModal) {
            pfpModal.style.display = "none";
        }
    }
</script>