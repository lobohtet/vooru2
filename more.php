  <?php
session_start();
?>
	
<style>
	body {
    background-color: #f8f9fa; /* Light background */
    color: #212529; /* Dark text */
    padding-top: 50px; /* Adjust based on navbar height */
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
    border-bottom: 1px solid #dee2e6; /* Light border for contrast */
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
    color: #007bff; /* Blue text for links */
    font-size: 18px;
    font-weight: bold;
    padding: 5px 10px;
    transition: color 0.3s ease, background-color 0.3s ease;
}

nav ul li a:hover {
    color: #0056b3; /* Darker blue for hover */
    background-color: #e9ecef; /* Light hover background */
    border-radius: 5px;
}

/* Scrollbar styling */
nav::-webkit-scrollbar {
    height: 6px;
}

nav::-webkit-scrollbar-thumb {
    background-color: #dee2e6; /* Light scrollbar thumb */
    border-radius: 3px;
}

nav::-webkit-scrollbar-thumb:hover {
    background-color: #007bff; /* Blue scrollbar thumb on hover */
}

/* Button Styling */
.button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff; /* Standard blue button */
    color: white;
    text-decoration: none;
    border-radius: 4px;
    text-align: center;
    width: auto;
    transition: background-color 0.3s ease;
}

.button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}
</style>

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
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
<h1> More </h1>

<a href="tap.html" class="button" target="_blank" >TOS and Policy 🚨</a> 
<hr style="border: 0; height: 1px; background: #444; opacity: 0.6; margin: 20px 0;">
<a href="veri.php" class="button">Verify Form 🧾</a>
<hr style="border: 0; height: 1px; background: #444; opacity: 0.6; margin: 20px 0;">
<a href="report.php" class="button" target="_blank">Report Form ⚠️</a>
<hr style="border: 0; height: 1px; background: #444; opacity: 0.6; margin: 20px 0;">
   <a onclick="return confirm('Are you sure you want to Log Out?')" class="button" href="logout.php">Log Out 🚪</a>
   <hr style="border: 0; height: 1px; background: #444; opacity: 0.6; margin: 20px 0;">
   <a onclick="return confirm('Are you sure you want to Log Out?')" href="">Download Lobo's VOORU SDK</a>
<hr style="border: 0; height: 1px; background: #444; opacity: 0.6; margin: 20px 0;">
<style>
    /* Hide the link on desktop and larger devices */
    @media (min-width: 769px) {
        .bun {
            display: none;
        }
    }
</style>

<a onclick="return confirm('Are you sure you want to Report a Tech Problem?');" href="mailto:blazegameplay3@gmail.com?subject=Teck%20Report&body=Username:(REQUIRED)%20%0D%0AProblem:(Please_Explain_Presicely)%20" style="color:red;">
    Report Technical Problem
</a>


<p> Contact Founder via <a href="mailto:blazegameplay3@gmail.com" style="color:#FF4342;">EMail</a> or<a href="https://t.me/lobohtet" style="color:#4B9BFF;">Telegram</a>.</p>
<footer> 
	2025–Present | PLATFORM</footer>