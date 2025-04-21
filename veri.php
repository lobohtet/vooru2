<?php
session_start();

// Assuming the username is stored in the session after login
if (!isset($_SESSION['username'])) {
    echo "User not logged in.";
    exit;
}

// Get the current logged-in username
$username = $_SESSION['username'];

// Define the file path for the user's data JSON file
$user_data_file = 'tbusersdat/' . $username . '.json';

// Check if the user's data file exists
if (!file_exists($user_data_file)) {
    echo "User data not found.";
    exit;
}

$user_data = json_decode(file_get_contents($user_data_file), true);

// If user is already verified, show message and exit
if (!empty($user_data['veri']) && $user_data['veri'] === true) {
    echo '<button id="backButton" onclick="window.history.back()" class="button">Back</button>';
    echo '<h1>You are already Verified!</h1>';
    exit;
}

// Verify if the password is correct
$password_correct = false;
$email_correct = false;
$math_correct = false;
$slider_correct = false;

// Default verification status
$verification_status = "Verification Pending";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check password
    if (isset($_POST['password']) && $_POST['password'] === $user_data['password']) {
        $password_correct = true;
    }

    // Check math problem
    if (isset($_POST['math_answer']) && $_POST['math_answer'] == 16) {
        $math_correct = true;
    }

    // Check slider
    if (isset($_POST['slider']) && $_POST['slider'] == 100) {
        $slider_correct = true;
    }

    // If all fields are correct
    if ($password_correct && $math_correct && $slider_correct) {
        // Validate email
        if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            // Add "veri": true to the user data when password is correct
            $user_data['veri'] = true;
            $user_data['email'] = $_POST['email']; // Save email

            // Save the updated data back to the user's JSON file
            file_put_contents($user_data_file, json_encode($user_data, JSON_PRETTY_PRINT));

            $verification_status = "Do not close the page. Your account will be verified in 15 seconds and will automatically send you back to your profile.";

            // Redirect after a delay (using JavaScript)
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'profile.php';
                }, 15000);
            </script>";
        } else {
            $verification_status = "Please enter a valid email.";
        }
    } else {
        $verification_status = "Verification Failed. Please check all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLATFORM User Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="password"],
        input[type="email"],
        input[type="number"],
        input[type="range"] {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        button {
            padding: 10px 15px;
            background-color: #32a4ff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        .status {
            font-size: 14px;
            margin-top: 5px;
        }

        .status.correct {
            color: green;
        }

        .status.incorrect {
            color: red;
        }

        .submit-btn-container {
            text-align: center;
        }

        #verificationStatus {
            text-align: center;
            font-size: 16px;
            margin-top: 20px;
        }

        #backButton {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 10px;
            background-color: #ddd;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <button id="backButton" onclick="window.history.back()">Back</button>

    <h1>PLATFORM User Verification</h1>

    <div class="container">
        <form method="POST">
        	
        	    <div class="form-group">
                <label for="email">Add Your Email:</label>
                <input type="email" id="email" name="email" required>
                <p id="emailStatus" class="status"></p>
            </div>
            
            <div class="form-group">
                <label for="password">Verify Your Password:</label>
                <input type="password" id="password" name="password" required>
                <p id="passwordStatus" class="status"></p>
            </div>

        

            <div class="form-group">
                <label for="math_answer">Solve the math problem: 2 × (5 + 3) = ?</label>
                <input type="number" id="math_answer" name="math_answer" required>
                <button type="button" id="checkMathButton" onclick="checkMath()">Check</button>
                <p id="mathStatus" class="status"></p>
            </div>

            <div class="form-group">
                <label for="slider">Drag the slider to the end:</label>
                <input type="range" id="slider" name="slider" min="0" max="100" value="0" step="1" required>
                <p id="sliderStatus" class="status"></p>
            </div>

            <div class="submit-btn-container">
                <button type="submit" id="submitBtn" disabled>Submit</button>
            </div>
        </form>

        <p id="verificationStatus"><?php echo $verification_status; ?></p>
    </div>

    <script>
        function checkMath() {
            const mathAnswer = document.getElementById('math_answer').value;
            const mathStatus = document.getElementById('mathStatus');

            if (mathAnswer == 16) {
                mathStatus.textContent = "Correct!";
                mathStatus.classList.add("correct");
                mathStatus.classList.remove("incorrect");
            } else {
                mathStatus.textContent = "Incorrect!";
                mathStatus.classList.add("incorrect");
                mathStatus.classList.remove("correct");
            }

            enableSubmitButton();
        }

        function enableSubmitButton() {
            const password = document.getElementById('password').value !== '';
            const email = document.getElementById('email').value !== '';
            const mathCorrect = document.getElementById('mathStatus').classList.contains('correct');
            const slider = document.getElementById('slider').value == 100;

            if (password && email && mathCorrect && slider) {
                document.getElementById('submitBtn').disabled = false;
            }
        }

        document.getElementById('slider').addEventListener('input', enableSubmitButton);
    </script>
</body>
</html>