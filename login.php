<h2> Log in/Sign up to PLATFORM 🧩</h2>

<?php
session_start();

// Set session and cookie duration (1 month)
$session_lifetime = 2592000; // 30 days in seconds

// Extend session lifetime
ini_set('session.gc_maxlifetime', $session_lifetime);
ini_set('session.cookie_lifetime', $session_lifetime);
session_set_cookie_params($session_lifetime);

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Define the file path for the user's JSON data
    $userFile = 'tbusersdat/' . $username . '.json';

    // Check if the user file exists
    if (file_exists($userFile)) {
        // Get the user data from the file
        $userData = json_decode(file_get_contents($userFile), true);

        // Check if the password matches
        if ($userData['password'] == $password) {
            $_SESSION['username'] = $username;

            // Create a persistent login cookie for 1 month
            $token = base64_encode($username . ':' . hash('sha256', $password));
            setcookie('user_token', $token, time() + $session_lifetime, "/");

            header("Location: index.php");
            exit;
        } else {
            echo "Invalid login credentials!";
        }
    } else {
        echo "User does not exist!";
    }
}

// Handle registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Define the file path for the user's JSON data
    $userFile = 'tbusersdat/' . $username . '.json';

    // Check if the user file already exists
    if (file_exists($userFile)) {
        echo "Username already exists!";
        exit;
    }

    // Create user data array
    $userData = [
        'username' => $username,
        'password' => $password,
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Save the user data to the file
    if (file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT))) {
        echo "User registered successfully! You can now log in.";
    } else {
        echo "Error registering user.";
    }
}
?>



	

<!-- HTML for Login/Registration -->

<form method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit" name="login">Log In 🧩</button>
    <button type="submit" name="register">Sign Up ➕</button>
    <p> You Have to Agree to <a href="tap.html" style="color:lightblue">TOS and Policy</a> before Account Creation!
</form>

<style>
    /* General body styling */
    body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa; /* Light background */
    color: #212529; /* Dark text */
    margin: 0;
    padding: 0;
}

/* Header */
header {
    background-color: #ffffff; /* Light header */
    color: #000000;
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #dee2e6; /* Subtle border */
}

header h1 {
    margin: 0;
    font-size: 24px;
}

/* Links in header */
a {
    color: #007bff; /* Standard blue for links */
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
    z-index: 999;
}

/* Button Styling */
button {
    background-color: #007bff; /* Blue for buttons */
    color: #fff;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

button:hover {
    background-color: #0056b3; /* Darker blue */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

/* Video content styling */
.video-content {
    background-color: #ffffff; /* Light background */
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
    color: #000000;
    font-size: 18px;
    margin: 10px 0;
}

.video-content p {
    color: #6c757d; /* Muted text color */
    font-size: 14px;
}

/* Form input fields */
input[type="text"], input[type="password"], textarea {
    background-color: #ffffff; /* Light input */
    color: #212529;
    border: 1px solid #007bff; /* Blue border */
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
    background-color: #ffffff; /* Light background */
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #dee2e6;
}

.video-detail video {
    width: 80%;
    max-width: 700px;
    margin-bottom: 20px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.video-detail h1 {
    color: #000000;
}

.video-detail p {
    color: #6c757d;
}

/* Navbar styles */
nav {
    background-color: #ffffff; /* Light navbar */
    padding: 10px 0;
    text-align: center;
    border-bottom: 1px solid #dee2e6;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
}

nav ul li {
    margin: 0 15px;
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

/* Center the form elements */
form {
    max-width: 500px;
    margin: 30px auto;
    padding: 20px;
    background-color: #ffffff; /* Light background */
    border-radius: 10px;
    border: 1px solid #dee2e6;
}

form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #212529;
}

form input[type="text"], form input[type="password"], form textarea {
    margin-bottom: 15px;
}
</style>
    <img id="catImage" src="" alt="Random Cat" style="width: 150px; height: 150px; border-radius: 15px; object-fit: cover;">
</div>
	<script>
		function fetchCatImage() {
    fetch('https://api.thecatapi.com/v1/images/search')
        .then(response => response.json())
        .then(data => {
            const catImageUrl = data[0].url;
            document.getElementById('catImage').src = catImageUrl;
        })
        .catch(error => console.error('Error fetching cat image:', error));
}

// Call the function to fetch a random cat image on page load
window.onload = () => {
    fetchCatImage();  // Load the first cat image
    setInterval(fetchCatImage, 3000);  // Update every 3 seconds
};
		</script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    	