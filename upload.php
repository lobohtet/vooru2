<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload'])) {
    // Check if the user is logged in
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $video_url = $_POST['video_url'];
        $video_title = $_POST['video_title'];
        $video_description = $_POST['video_description'];
        $thumbnail_url = $_POST['thumbnail_url'];
        $captcha = isset($_POST['captcha']) ? $_POST['captcha'] : false;
        $no_copyright = isset($_POST['no_copyright']) ? $_POST['no_copyright'] : false;

        // Server-side validation
        if (!$captcha || !$no_copyright) {
            echo "Please verify you're not a robot and confirm no copyright issues.";
            exit;
        }

        if (!preg_match('/\.(mp4|avi|mov|wmv|mkv|flv|webm)$/i', $video_url)) {
            echo "Invalid video URL format. Must end with a valid video extension.";
            exit;
        }

        if (!preg_match('/\.(jpg|jpeg|png|gif|bmp)$/i', $thumbnail_url)) {
            echo "Invalid thumbnail URL format. Must end with a valid image extension.";
            exit;
        }

        if (empty($video_title) || empty($video_description)) {
            echo "Title and description cannot be empty.";
            exit;
        }

        if (strlen($video_title) > 96) {
            echo "Title exceeds maximum character limit of 96.";
            exit;
        }

        if (strlen($video_description) > 2000) {
            echo "Description exceeds maximum character limit of 2000.";
            exit;
        }

        // Create video data array
        $video_data = [
            'username' => $username,
            'video_url' => $video_url,
            'video_title' => $video_title,
            'video_description' => $video_description,
            'thumbnail_url' => $thumbnail_url,
            'upload_date' => date('Y-m-d'),
            'views' => 0  // Initialize views count
        ];

        // Create the filename based on the video title
        $video_file = 'tbvidsdat/' . basename($video_title) . '.json';

        // Check if the file already exists
        if (file_exists($video_file)) {
            echo "<script>alert('Video With This Title Exists, Please Edit!'); window.history.back();</script>";
            exit;
        } else {
            // Save video data to individual JSON file
            file_put_contents($video_file, json_encode($video_data, JSON_PRETTY_PRINT));

            // Redirect to home page after successful upload
            header('Location: index.php');
            exit;
        }
    } else {
        echo "You must be logged in to upload a video.";
    }
}
?>


<div>
<?php if (isset($_SESSION['username'])): ?>
    <div>    <nav>
        <ul>
            <li><a href="index.php">Home 🏠</a></li>
            <li><a href="upload.php">Upload 📤</a></li>
            <li><a href="profile.php">Me 👤</a></li>
            <li><a href="search.php">Search 🔍</a></li>
                <li><a href="chat.php">Public Chat 💬</a></li>

                <li><a href="subbed.php">Subscriptions 👥</a></li>
    <li><a href="more.php">More ⚙️</a></li>
</ul>
    </nav></div>
<?php endif; ?>
    </div>

<form method="POST" id="uploadForm">
    <hr style="border: 0; height: 1px; background: #444; opacity: 0.6; margin: 20px 0;">
    <h2> Video Upload Form 📤 </h2>
   <a href="https://catbox.moe" target="_blank">
 <img src="https://files.catbox.moe/ppmhl9.png" alt="Go to Catbox" style="border: none; width: 100px; height: auto;">
</a>
    <hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
    <label for="video_url">Video URL:</label>
    <input type="text" name="video_url" id="video_url" placeholder="e.g. example.com/video.mp4" required><br>

    <label for="video_title">Video Title:</label>
    <input type="text" name="video_title" id="video_title" maxlength="96" required><br>

    <label for="video_description">Short Description:</label>
    <textarea name="video_description" id="video_description" maxlength="2000" required></textarea><br>

    <label for="thumbnail_url">Thumbnail URL:</label>
    <input type="text" name="thumbnail_url" id="thumbnail_url" placeholder="e.g. example.com/thumbnail.jpg" required><br>
<hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
    <label for="captcha">My Content Do Not Contain Copyright Material.:</label>
    <input type="checkbox" name="captcha" id="captcha" required><br>

    <label for="no_copyright">My Content is Safe for +13 Users:</label>
    <input type="checkbox" name="no_copyright" id="no_copyright" required><br>
<hr style="border: 0; border-top: 1px solid #ccc; margin: 20px 0;">
    <button type="submit" name="upload" id="uploadBtn" disabled>Upload Video</button>
</form>

<script>
    const videoUrlInput = document.getElementById('video_url');
    const thumbnailUrlInput = document.getElementById('thumbnail_url');
    const videoTitleInput = document.getElementById('video_title');
    const videoDescriptionInput = document.getElementById('video_description');
    const captchaCheckbox = document.getElementById('captcha');
    const noCopyrightCheckbox = document.getElementById('no_copyright');
    const uploadBtn = document.getElementById('uploadBtn');

    function validateForm() {
        const videoUrlValid = /\.(mp4|avi|mov|wmv|mkv|flv|webm)$/i.test(videoUrlInput.value);
        const thumbnailUrlValid = /\.(jpg|jpeg|png|gif|bmp)$/i.test(thumbnailUrlInput.value);
        const titleNotEmpty = videoTitleInput.value.trim().length > 0 && videoTitleInput.value.length <= 96;
        const descriptionNotEmpty = videoDescriptionInput.value.trim().length > 0 && videoDescriptionInput.value.length <= 2000;
        const captchaChecked = captchaCheckbox.checked;
        const noCopyrightChecked = noCopyrightCheckbox.checked;

        // Enable button only if all validations pass
        uploadBtn.disabled = !(videoUrlValid && thumbnailUrlValid && titleNotEmpty && descriptionNotEmpty && captchaChecked && noCopyrightChecked);
    }

    // Add event listeners for real-time validation
    [videoUrlInput, thumbnailUrlInput, videoTitleInput, videoDescriptionInput, captchaCheckbox, noCopyrightCheckbox].forEach(element => {
        element.addEventListener('input', validateForm);
        element.addEventListener('change', validateForm);
    });
</script>

<style>
    /* General body styling */
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light grey background */
    color: #212529; /* Dark text color */
    margin: 0;
    padding: 0;
}

/* Header */
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

/* Links in header */
a {
    color: #007bff; /* Blue for links */
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Footer */
footer {
    background-color: #ffffff; /* Light footer */
    color: #212529;
    padding: 10px;
    text-align: center;
    position: fixed;
    bottom: 0;
    width: 100%;
    border-top: 1px solid #dee2e6;
}

/* Button Styling */
button {
    background-color: #007bff; /* Blue background for buttons */
    color: #fff;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3; /* Darker blue when hovered */
}

/* Video content styling */
.video-content {
    background-color: #ffffff; /* White background */
    border-radius: 8px;
    padding: 15px;
    margin: 10px 0;
    text-align: center;
    border: 1px solid #dee2e6;
}

.video-content img {
    width: 100px;
    height: 100px;
    border-radius: 5px;
}

.video-content h3 {
    color: #212529;
    font-size: 18px;
    margin: 10px 0;
}

.video-content p {
    color: #495057; /* Dark grey text */
    font-size: 14px;
}

/* Form input fields */
input[type="text"], input[type="password"], textarea {
    background-color: #ffffff;
    color: #212529;
    border: 1px solid #ced4da;
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
    border: 1px solid #dee2e6;
}

.video-detail video {
    width: 80%;
    max-width: 700px;
    margin-bottom: 20px;
}

.video-detail h1 {
    color: #212529;
}

.video-detail p {
    color: #495057;
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
    color: #007bff; /* Blue text color */
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

/* Add padding to the body to prevent content from being hidden under fixed navbar */
body {
    padding-top: 50px; /* Adjust based on navbar height */
}

/* Fix for footer */
footer {
    z-index: 999;
}
</style>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">