<?php
session_start();
include("connection.php");
include("function.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

function matchMentee($con, $mentee_id) {
    // Fetch the mentee's information
    $query_mentee = "SELECT * FROM mentee WHERE mentee_id = '$mentee_id' LIMIT 1";
    $result_mentee = mysqli_query($con, $query_mentee);

    if ($result_mentee && mysqli_num_rows($result_mentee) > 0) {
        $mentee_data = mysqli_fetch_assoc($result_mentee);

        // Check if the mentee is not already matched or if the value is 0
        if ($mentee_data['matched_mentor'] == 0) {
            // Call the function to find and store matches
            findAndStoreMatches($con);
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // something was posted
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // read mentor database
        $query_mentor = "SELECT * FROM mentor WHERE username = '$username' LIMIT 1";
        $result_mentor = mysqli_query($con, $query_mentor);

        if ($result_mentor && mysqli_num_rows($result_mentor) > 0) {
            $mentor_data = mysqli_fetch_assoc($result_mentor);

            if ($mentor_data['password'] == $password) {
                $_SESSION['mentor_id'] = $mentor_data['mentor_id'];
                header("Location: profile_mentor.php");
                die;
            } else {
                // Display a general error message for incorrect credentials
                echo "Wrong username or password";
            }
        } 

        // read mentee database
        $query_mentee = "SELECT * FROM mentee WHERE username = '$username' LIMIT 1";
        $result_mentee = mysqli_query($con, $query_mentee);

        if ($result_mentee && mysqli_num_rows($result_mentee) > 0) {
            $mentee_data = mysqli_fetch_assoc($result_mentee);

            if ($mentee_data['password'] == $password) {
                $_SESSION['mentee_id'] = $mentee_data['mentee_id'];

                // Call the function to match the mentee if not already matched
                matchMentee($con, $_SESSION['mentee_id']);

                header("Location: profile.php");
                die;
            } else {
                // Display a general error message for incorrect credentials
                echo "Wrong username or password";
            }
        } 

        // Display a general error message if no match is found
        echo "Wrong username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div id="header" class="animated slideInDown" style="animation-delay:1.8s;">
        <div id="title">Login to InclusiMentor</div><br>
        <div id="links">
            <a href="index.php" class="nav-link">Home</a>
            <a href="index.php#skills" class="nav-link">About Us</a>
            <a href="index.php#work" class="nav-link" style="margin:0px 60px;">Why Us?</a>
            <a href="signup.php" class="nav-link">Sign Up(Mentee)</a>
            <a href="signup_mentor.php" class="nav-link">Sign Up(Mentor)</a>
        </div>
    </div>
    <div></div>
    <div></div>
    <center>
        <div id="middle">
            <div id="tagline" class="animated zoomIn" style="animation-delay:1.8s;">
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required class="form-input">
                    <input type="password" name="password" placeholder="Password" required class="form-input"><br>
                    <input type="submit" value = "Login" class="btn_one">
                </form>
            </div>
        </div>
    </center>

    <div id="footer">
        Unlock Your Potential in an Inclusive Community <br><br> 
        <a href="https://imfunniee.github.io" class="footer-link">Made as a part of YCFP 2023 Program</a>
    </div>

    <script src="index.js" type="text/javascript"></script>
</body>
</html>
