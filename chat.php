<?php
session_start();
include("connection.php");
include("function.php");

// Debug: Display session variables
echo "Session ID: " . session_id() . "<br>";
echo "User ID: " . $_SESSION['user_id'] . "<br>";
echo "User Type: " . $_SESSION['user_type'] . "<br>";

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$user_data = check_login($con);

// Debug: Display user data
var_dump($user_data);

// Fetch additional profile data from the database based on user_id and user_type
$user_query = ""; // Initialize $user_query
if ($user_type == 'mentee') {
    $user_query = "SELECT * FROM mentee WHERE mentee_id = '$user_id'";
} elseif ($user_type == 'mentor') {
    $user_query = "SELECT * FROM mentor WHERE mentor_id = '$user_id'";
}

// Debug: Display $user_query
echo "User Query: $user_query <br>";

$result = mysqli_query($con, $user_query);

// Debug: Display SQL error if any
if (!$result) {
    echo "MySQL Error: " . mysqli_error($con) . "<br>";
}

// Check if query was successful
if ($result && mysqli_num_rows($result) > 0) {
    $profile_data = mysqli_fetch_assoc($result);
} else {
    // Handle error if unable to fetch profile data
    $profile_data = array();
}

// Debug: Display profile data
var_dump($profile_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat with Mentor/Mentee</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div id="header" class="animated slideInDown" style="animation-delay:1.8s;">
        <div id="title">Chat with <?php echo $user_type === 'mentor' ? 'Mentee' : 'Mentor'; ?></div><br>
        <div id="links">
            <a href="index.php#skills" class="nav-link">About Us</a>
            <a href="index.php#work" class="nav-link" style="margin:0px 60px;">Why Us?</a>
            <a href="index.php" class="nav-link" style="margin:0px 60px;">Home</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </div>
    </div>

    <div id="chat-container">
        <div id="chat-header"><?php echo $user_name; ?></div>
        <div id="chat-messages">
            <!-- Display chat messages here -->
        </div>
        <input type="text" id="message-input" placeholder="Type your message...">
        <button id="send-button">Send</button>
        <button id="back-button" onclick="window.location.href='profile.php'">Back to Profile</button>
    </div>

    <div id="footer">
        Unlock Your Potential in an Inclusive Community <br><br> 
        <a href="https://imfunniee.github.io" class="footer-link">Made as a part of YCFP 2023 Program</a>
    </div>

    <script src="index.js" type="text/javascript"></script>
    <script src="chat.js" type="text/javascript"></script>
</body>
</html>
