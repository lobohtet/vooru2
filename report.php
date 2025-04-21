<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $url = $_POST['url'];
    $reason = $_POST['reason'];

    // Prepare the report text
    $report = "---\n";
    $report .= "Report#1\n";
    $report .= "URL: " . $url . "\n";
    $report .= "Reason: " . $reason . "\n";
    $report .= "---\n\n";

    // Save to 'rep.txt' file
    file_put_contents('rep.txt', $report, FILE_APPEND);

    echo "Report submitted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Form</title>
    <style>
        /* Dark Mode Styling */
        body {
    background-color: #f8f9fa; /* Light background */
    color: #212529; /* Dark text */
    font-family: Arial, sans-serif;
}

h2 {
    color: #007bff; /* Blue heading */
}

label {
    font-size: 1.2em;
    color: #495057; /* Darker text for label */
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ced4da; /* Light border */
    border-radius: 5px;
    background-color: #ffffff; /* White background */
    color: #212529; /* Dark text */
}

input[type="text"]:focus {
    outline: none;
    border-color: #007bff; /* Blue border on focus */
}

button {
    background-color: #007bff; /* Blue button */
    color: #ffffff; /* White text */
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
}

button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

.container {
    width: 50%;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff; /* White background */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Lighter shadow */
}

.success-message {
    color: #28a745; /* Green success message */
    font-weight: bold;
}
    </style>
</head>
<body>
    <div class="container">
        <h2>Report Form</h2>
        <form method="POST">
            <label for="url">Video or User URL:</label><br>
            <input type="text" id="url" name="url" placeholder="Video or User URL" required><br><br>
            
            <label for="reason">Reason for the violation:</label><br>
            <input type="text" id="reason" name="reason" placeholder="Reason the violation" required><br><br>
            
            <button type="submit">Submit</button>
        </form>

        <?php
        // Display success message
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<p class='success-message'>Report submitted successfully!</p>";
        }
        ?>
    </div>
    <a href="index.php" ><button> HOME 🚪 </button></a>
</body>
</html>