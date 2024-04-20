<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($con);

// Fetch additional profile data from the database based on mentee_id
$mentee_id = $_SESSION['mentee_id'];
$query = "SELECT * FROM mentee WHERE mentee_id = '$mentee_id'";
$fetch_query = "SELECT change_req, change_req_date FROM mentee WHERE mentee_id = '$mentee_id'";
$fetch_result = mysqli_query($con, $fetch_query);
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $profile_data = mysqli_fetch_assoc($result);

    // Initialize matched mentor data to be blank
    $matchedMentorData = array('profile_pic' => 'placeholder_image.jpg', 'full_name' => '', 'bio' => '', 'gender' => '', 'pronouns' => '', 'age' => '', 'organisation' => '', 'email' => '');

    // Check if the mentee is matched with a mentor
    if (!empty($profile_data['matched_mentor'])) {
        // Fetch details of the matched mentor
        $matchedMentorID = $profile_data['matched_mentor'];
        $mentorQuery = "SELECT * FROM mentor WHERE mentor_id = '$matchedMentorID'";
        $mentorResult = mysqli_query($con, $mentorQuery);

        if ($mentorResult && mysqli_num_rows($mentorResult) > 0) {
            $matchedMentorData = mysqli_fetch_assoc($mentorResult);
        } else {
            // If matched mentor data is not found in mentor table
            clearMatchedMentor($con, $mentee_id);
        }
    }
} else {
    // Handle error if unable to fetch profile data
    $profile_data = array();
    $matchedMentorData = array();
}

// Fetch matched mentor information
$matched_mentor_id = getMatchedMentor($con, $mentee_id);
$matched_mentor_info = array();

if ($matched_mentor_id) {
    $query_matched_mentor = "SELECT * FROM mentor WHERE mentor_id = '$matched_mentor_id'";
    $result_matched_mentor = mysqli_query($con, $query_matched_mentor);

    if ($result_matched_mentor && mysqli_num_rows($result_matched_mentor) > 0) {
        $matched_mentor_info = mysqli_fetch_assoc($result_matched_mentor);
    }
}

// Function to clear matched_mentor field, set it to zero, and move data to matching_history
function clearMatchedMentor($con, $mentee_id) {
    // Fetch the data from matched_mentor and move it to matching_history in mentee table
    $fetch_query = "SELECT matched_mentor FROM mentee WHERE mentee_id = '$mentee_id'";
    $fetch_result = mysqli_query($con, $fetch_query);

    if ($fetch_result && mysqli_num_rows($fetch_result) > 0) {
        $row = mysqli_fetch_assoc($fetch_result);
        $matched_mentor_id = $row['matched_mentor'];

        // Clear matched_mentor field
        $update_query_clear = "UPDATE mentee SET matched_mentor = 0 WHERE mentee_id = '$mentee_id'";
        mysqli_query($con, $update_query_clear);

        // Move data to matching_history
        $update_query_history = "UPDATE mentee SET matching_history = '$matched_mentor_id' WHERE mentee_id = '$mentee_id'";
        mysqli_query($con, $update_query_history);
    }
}

if(isset($_SESSION['mentee_id'])) {
    $mentee_id = mysqli_real_escape_string($con, $_SESSION['mentee_id']);

    // Fetch the change_req value from the mentee table
    $fetch_query = "SELECT change_req, change_req_date FROM mentee WHERE mentee_id = '$mentee_id'";
    $fetch_result = mysqli_query($con, $fetch_query);

    if ($fetch_result && mysqli_num_rows($fetch_result) > 0) {
        $row = mysqli_fetch_assoc($fetch_result);
        $change_req = $row['change_req'];
        $change_req_date = $row['change_req_date'];
    } else {
        // If the mentee record is not found
        $change_req = '';
        $change_req_date = '';
    }
} else {
    // If mentee_id is not set in the session
    $change_req = '';
    $change_req_date = '';
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Dashboard</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body, html {
            padding: 0;
            margin: 0;
            font-family: 'Comfortaa', cursive;
            font-weight: bold;
            color: #fff;
            background: rgb(0, 0, 0);
            scroll-behavior: smooth;
        }

        #header {
            text-align: center;
            width: 100%;
            padding: 4% 0;
            position: fixed;
            background: #000;
            z-index: 999;
            transition: 0.6s ease-in-out;
            top: 0; /* Make sure the header is at the top initially */
        }

        /* Add a class to shrink the header */
        #header.shrink {
            padding: 2% 0; /* Adjust the padding as needed */
        }

        #title {
            font-size: 40px;
            background-image: linear-gradient(to left, #FF416C 0%, #FF4B2B 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 500% 500% !important;
            -webkit-animation: Gradient 20s ease infinite;
            -moz-animation: Gradient 20s ease infinite;
            -o-animation: Gradient 20s ease infinite;
            animation: Gradient 20s ease infinite;
        }

        #dashboard {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
            padding-top: 250px; /* Adjusted padding to prevent overlap */
        }

        #match-dashboard {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
            padding-top: 50px;
        }

        #profile-info,
        #profile-picture {
            width: 40%;
            padding: 20px;
            border: 2px solid #fff;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        #profile-picture {
            text-align: center;
        }

        #profile-picture img {
            max-width: 100%;
            height: auto;
            border-radius: 50%; /* Circular format */
        }

        #profile-info h2,
        #profile-picture h2, #Mentor-Mentee-info h2 {
            font-size: 40px;
            background-image: linear-gradient(to left, #FF416C 0%, #FF4B2B 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 500% 500% !important;
            -webkit-animation: Gradient 20s ease infinite;
            -moz-animation: Gradient 20s ease infinite;
            -o-animation: Gradient 20s ease infinite;
            animation: Gradient 20s ease infinite;
        }
        
        #Mentor-Mentee-info {
            width: 41%;
            padding: 10px;
            border: 2px solid #fff;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        #footer {
            width: 100%;
            padding: 10vh 0;
            text-align: center;
            font-weight: bold;
            color: #fff;
        }

        #footer a {
            font-size: 18px;
            background-image: linear-gradient(to left, #FF416C 0%, #FF4B2B 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 500% 500% !important;
            -webkit-animation: Gradient 20s ease infinite;
            -moz-animation: Gradient 20s ease infinite;
            -o-animation: Gradient 20s ease infinite;
            animation: Gradient 20s ease infinite;
            text-decoration: none;
        }

        #logout , #cancelChangeButton , #requestChangeButton , #chat {
            margin: auto;
            width: 50%;
            padding: 10px;
        }

        ::placeholder {
            color: #fff;
        }

        @media (max-width: 900px) {
            #header {
                width: 94%;
                padding: 15% 3%;
            }

            #title {
                font-size: 30px;
            }

            #dashboard , #match-dashboard {
                flex-direction: column;
                align-items: center;
            }

            #profile-info,
            #profile-picture, #Mentor-Mentee-info {
                width: 90%;
                margin-bottom: 20px;
            }
        }

        @-webkit-keyframes Gradient {
            0% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }

            100% {
                background-position: 0% 50%
            }
        }

        @-moz-keyframes Gradient {
            0% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }

            100% {
                background-position: 0% 50%
            }
        }

        @keyframes Gradient {
            0% {
                background-position: 0% 50%
            }

            50% {
                background-position: 100% 50%
            }

            100% {
                background-position: 0% 50%
            }
        }
    </style>
</head>
<body>

    <div id="header" class="animated slideInDown" style="animation-delay:1.8s;">
        <div id="title">Welcome to Your Dashboard <?php echo $profile_data['full_name']; ?></div>
        <div id="links">
            <a href="index.php#skills" class="nav-link">About Us</a>
            <a href="index.php#work" class="nav-link" style="margin:0px 60px;">Why Us?</a>
            <a href="index.php#contact" class="nav-link" style="margin:0px 60px;">Contact</a> 
        </div>
    </div>

    <div id="dashboard">
        <div id="profile-picture">
            <h2>Your Profile Picture</h2>
            <img src="<?php echo $profile_data['profile_pic']; ?>" alt="Profile Picture">
        </div>

        <div id="profile-info">
            <h2>Your Profile Information</h2>
            <p><strong>Mentee ID:</strong> <?php echo $profile_data['mentee_id']; ?></p>
            <p><strong>Full Name:</strong> <?php echo $profile_data['full_name']; ?></p>
            <p><strong>Username:</strong> <?php echo $profile_data['username']; ?></p>
            <p><strong>Bio:</strong> <?php echo $profile_data['bio']; ?></p>
            <p><strong>Email:</strong> <?php echo $profile_data['email']; ?></p>
            <p><strong>Gender:</strong> <?php echo $profile_data['gender']; ?></p>
            <p><strong>Pronouns:</strong> <?php echo $profile_data['pronouns']; ?></p>
            <p><strong>Age:</strong> <?php echo $profile_data['age']; ?></p>
            <p><strong>Educational Institution: </strong> <?php echo $profile_data['college']; ?></p>
            <p><strong>Field of Education: </strong> <?php echo $profile_data['ed_field']; ?></p>
            <p><strong>Field to be Mentored: </strong> <?php echo $profile_data['mentorship_field']; ?></p>
            <p><strong>Email ID:</strong> <?php echo $profile_data['email']; ?> </p>
            <p><strong>Applied for Mentor Change: </strong> <?php echo $profile_data['change_req']; ?> </p>
            <p><div id="logout"><a href="logout.php" class="btn_one">Logout</a></div></P>
            <p><div id="logout"><a onclick="confirmDelete()" class="btn_one">Delete Account</a></div></p>
            <p><div id="logout"><a href="index.php#contact" class="btn_one">Report Issue</a></div></p>
        </div>
    </div>

    <div id="match-dashboard">
        <div id="profile-picture">
            <h2>Mentor's Profile Picture</h2>
            <?php if ($matched_mentor_info): ?>
            <img src="<?php echo $matchedMentorData['profile_pic']; ?>" alt="Mentor's Profile Picture">
            <?php else: ?>
            <!-- Display a message if no match is found -->
            <p>No match yet</p>
        <?php endif; ?>
        </div>

        <div id="Mentor-Mentee-info">
            <h2>Mentor's info</h2>
        <?php if ($matched_mentor_info): ?>
            <p><strong>Full Name:</strong> <?php echo $matchedMentorData['full_name']; ?></p>
            <p><strong>Bio:</strong> <?php echo $matchedMentorData['bio']; ?></p>
            <p><strong>Gender:</strong> <?php echo $matchedMentorData['gender']; ?></p>
            <p><strong>Pronouns:</strong> <?php echo $matchedMentorData['pronouns']; ?></p>
            <p><strong>Age:</strong> <?php echo $matchedMentorData['age']; ?></p>
            <p><strong>Organisation:</strong> <?php echo isset($matchedMentorData['mentor_org']) ? $matchedMentorData['mentor_org'] : 'N/A'; ?></p>
            <p><strong>Email ID:</strong> <?php echo $matchedMentorData['email']; ?></p>
            <p id="changeRequestMessage" style="display: <?php echo ($change_req === 'YES') ? 'block' : 'none'; ?>;">Requested for mentor change on <?php echo $change_req_date; ?></p>
            <p><div id="requestChangeButton" style="display: <?php echo ($change_req === 'NO') ? 'block' : 'none'; ?>;"><a onclick="requestMentorChange()" class="btn_one">Change Mentor</a></div></p>
            <p><div id="cancelChangeButton" style="display: <?php echo ($change_req === 'YES') ? 'block' : 'none'; ?>;"><a onclick="cancelMentorChange()" class="btn_one">Cancel Change</a></div></p>
            <p><div id="chat"><a href="chat.php" class="btn_one">Chat</a></div></p>
        <?php else: ?>
            <!-- Display a message if no match is  -->
            <p>No match yet</p>
        <?php endif; ?>
        </div>
    </div>

    <div id="footer">
        Unlock Your Potential in an Inclusive Community <br><br> 
        <a href="https://imfunniee.github.io" class="footer-link">Made as a part of YCFP 2023 Program</a>
    </div>

    <script src="index.js" type="text/javascript"></script>
    <script>

var change_req = "<?php echo $change_req; ?>";

        window.addEventListener("scroll", function() {
            var header = document.getElementById("header");

            // Add the 'shrink' class when the scroll position is greater than 0
            if (window.scrollY > 0) {
                header.classList.add("shrink");
            } else {
                header.classList.remove("shrink");
            }
        });

        function confirmDelete() {
        var confirmation = confirm("Are you sure you want to delete your account? This action is irreversible.");

        if (confirmation) {
            window.location.href = "delete_account.php";
        }
        }

        function requestMentorChange() {
    var confirmation = confirm("You will get a 7-day notice period to reconsider your decision. Your mentor will be notified about the change request. Do you want to proceed?");

    if (confirmation) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.responseText);
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            }
        };
        xhttp.open("POST", "change_req.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
    }
}



function cancelMentorChange() {
    var cancelConfirmation = confirm("Are you sure you want to cancel the mentor change?");
    
    if (cancelConfirmation) {
        // Use AJAX to send a request to cancel_change_req.php
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.responseText);
                if (response.status === 'success') {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert(response.message);
                }
            }
        };
        // Send a POST request to cancel_change_req.php
        xhttp.open("POST", "cancel_change_req.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
    }
}
    </script>
</body>
</html>