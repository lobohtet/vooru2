<?php
session_start();

// Handle the search query when the form is submitted
$search_term = "";
$search_results_videos = [];
$search_results_profiles = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $search_term = trim($_POST['search']);

    // Search for videos in the 'tbvidsdat' directory
    if (!empty($search_term)) {
        $directory = 'tbvidsdat/';
        foreach (glob($directory . "*.json") as $file) {
            $video_data = json_decode(file_get_contents($file), true);
            if (strpos(strtolower($video_data['video_title']), strtolower($search_term)) !== false || 
                strpos(strtolower($video_data['video_description']), strtolower($search_term)) !== false) {
                $search_results_videos[] = $video_data;
            }
        }
    }

    // Search for profiles in the 'tbusersdat' directory
    $directory_profiles = 'tbusersdat/';
    foreach (glob($directory_profiles . "*.json") as $file) {
        $user_data = json_decode(file_get_contents($file), true);
        if (strpos(strtolower($user_data['username']), strtolower($search_term)) !== false) {
            $search_results_profiles[] = $user_data;
        }
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLATFORM - Search</title>
<style>
    /* General body styling */
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light background */
    color: #212529; /* Dark text */
    margin: 0;
    padding: 0;
    text-align: left; /* Align all text to the left */
}

/* Header */
header {
    background-color: #ffffff; /* White background */
    color: #212529; /* Dark text */
    padding: 10px;
    position: relative;
    border-bottom: 1px solid #dee2e6; /* Light border */
}

header h1 {
    margin: 0;
    font-size: 24px;
    text-align: left;
    display: inline-block;
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
    background-color: #e9ecef; /* Light grey */
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

/* Add padding to the body to prevent content from being hidden under fixed navbar */
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
    font-size: 18px;
    text-align: left;
    margin: 5px 0;
    color: #212529;
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

/* Profile Item */
.profile-item {
    background-color: #ffffff;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #dee2e6;
}

.profile-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.profile-item h3 {
    font-size: 18px;
    margin: 10px 0;
    color: #212529;
}

/* Search Container */
.search-container {
    padding: 20px;
}

.search-results {
    margin-top: 20px;
}
</style>
</head>
<body>

<div class="search-container">
    <h1>Search Videos and Profiles</h1>

    <form method="POST" action="search.php">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Search..." required>
        <button type="submit">Search 🔍</button>
    </form>

    <div class="search-results">
        <h2>Video Results 🎬</h2>
        <?php if (count($search_results_videos) > 0): ?>
            <?php foreach ($search_results_videos as $video): ?>
                <div class="video-item">
                    <a href="view_video.php?video=<?php echo urlencode(basename($video['video_title']) . '.json'); ?>">
                        <img src="<?php echo htmlspecialchars($video['thumbnail_url']); ?>" alt="Thumbnail">
                        <h3><?php echo htmlspecialchars($video['video_title']); ?></h3>
                    </a>
                    <p><?php echo htmlspecialchars($video['video_description']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No videos found matching your search.</p>
        <?php endif; ?>

        <h2>Profile Results 👤</h2>
        <?php if (count($search_results_profiles) > 0): ?>
            <?php foreach ($search_results_profiles as $profile): ?>
         <div class="profile-item">
    <a href="public.php?user=<?php echo urlencode(basename($profile['username']) . '.json'); ?>">
        <?php 
            $pfp = !empty($profile['pfp']) ? $profile['pfp'] : "https://files.catbox.moe/0apv3v.png";
            $verified_status = isset($profile['veri']) && $profile['veri'] === true ? "(Verified)" : "(Not Verified)";
        ?>
        <img src="<?php echo htmlspecialchars($pfp); ?>" alt="Profile Picture">
        <h3><?php echo htmlspecialchars($profile['username']) . " " . $verified_status; ?></h3>
    </a>
</div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No profiles found matching your search.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
