<?php
session_start();
include("connection.php");
include("function.php");

$user_data = check_login($con);

// Fetch additional profile data from the database based on mentor_id
$mentor_id = $_SESSION['mentor_id'];
$query = "SELECT * FROM mentor WHERE mentor_id = '$mentor_id'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $profile_data = mysqli_fetch_assoc($result);
} else {
    // Handle error if unable to fetch profile data
    $profile_data = array();
}

// Fetch matched mentee information
$matched_mentee_id = getMatchedMentee($con, $mentor_id);
$matched_mentee_info = array();

if ($matched_mentee_id) {
    $query_matched_mentee = "SELECT * FROM mentee WHERE mentee_id = '$matched_mentee_id'";
    $result_matched_mentee = mysqli_query($con, $query_matched_mentee);

    if ($result_matched_mentee && mysqli_num_rows($result_matched_mentee) > 0) {
        $matched_mentee_info = mysqli_fetch_assoc($result_matched_mentee);
    } else {
        // If matched mentee data is not found in mentee table
        clearMatchedMentee($con, $mentor_id);
    }
}
?>

<?php

// Function to clear matched_mentee field, set it to zero, and move data to matching_history
function clearMatchedMentee($con, $mentor_id) {
    // Fetch the data from matched_mentee and move it to matching_history in mentor table
    $fetch_query = "SELECT matched_mentee FROM mentor WHERE mentor_id = '$mentor_id'";
    $fetch_result = mysqli_query($con, $fetch_query);

    if ($fetch_result && mysqli_num_rows($fetch_result) > 0) {
        $row = mysqli_fetch_assoc($fetch_result);
        $matched_mentee_id = $row['matched_mentee'];

        // Clear matched_mentee field
        $update_query_clear = "UPDATE mentor SET matched_mentee = 0 WHERE mentor_id = '$mentor_id'";
        mysqli_query($con, $update_query_clear);

        // Move data to matching_history
        $update_query_history = "UPDATE mentor SET matching_history = '$matched_mentee_id' WHERE mentor_id = '$mentor_id'";
        mysqli_query($con, $update_query_history);
    }
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

        #logout {
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
            <p><strong>Mentee ID:</strong> <?php echo $profile_data['mentor_id']; ?></p>
            <p><strong>Full Name:</strong> <?php echo $profile_data['full_name']; ?></p>
            <p><strong>Username:</strong> <?php echo $profile_data['username']; ?></p>
            <p><strong>Bio:</strong> <?php echo $profile_data['bio']; ?></p>
            <p><strong>Email:</strong> <?php echo $profile_data['email']; ?></p>
            <p><strong>Gender:</strong> <?php echo $profile_data['gender']; ?></p>
            <p><strong>Pronouns:</strong> <?php echo $profile_data['pronouns']; ?></p>
            <p><strong>Age:</strong> <?php echo $profile_data['age']; ?></p>
            <p><strong>Field of Education: </strong> <?php echo $profile_data['ed_field']; ?></p>
            <p><strong>Working Organisation: </strong> <?php echo $profile_data['mentor_org']; ?></p>
            <p><strong>Field of Work: </strong> <?php echo $profile_data['work_field']; ?></p>
            <p><strong>Email ID:</strong> <?php echo $profile_data['email']; ?> </p>
            <p><div id="logout"><a href="logout.php" class="btn_one">Logout</a></div></P>
            <p><div id="logout"><a onclick="confirmDelete()" class="btn_one">Delete Account</a></div></p>
            <p><div id="logout"><a href="index.php#contact" class="btn_one">Report Issue</a></div></p>
        </div>
    </div>

    <div id="match-dashboard">
    <div id="profile-picture">
        <h2>Mentee's Profile Picture</h2>
        <?php if ($matched_mentee_info): ?>
            <img src="<?php echo $matched_mentee_info['profile_pic']; ?>" alt="Mentee's Profile Picture">
        <?php else: ?>
            <p>No match yet</p>
        <?php endif; ?>
    </div>

    <div id="Mentor-Mentee-info">
        <h2>Mentee's Info</h2>
        <?php if ($matched_mentee_info): ?>
            <!-- Display matched mentee information -->
            <p><strong>Full Name:</strong> <?php echo $matched_mentee_info['full_name']; ?></p>
            <p><strong>Bio:</strong> <?php echo $matched_mentee_info['bio']; ?></p>
            <p><strong>Email:</strong> <?php echo $matched_mentee_info['email']; ?></p>
            <p><strong>Gender:</strong> <?php echo $matched_mentee_info['gender']; ?></p>
            <p><strong>Pronouns:</strong> <?php echo $matched_mentee_info['pronouns']; ?></p>
            <p><strong>Age:</strong> <?php echo $matched_mentee_info['age']; ?></p>
            <p><strong>College:</strong> <?php echo $matched_mentee_info['college']; ?></p>
            <p><strong>Field of Education:</strong> <?php echo $matched_mentee_info['ed_field']; ?></p>
            <p><strong>Mentorship Field:</strong> <?php echo $matched_mentee_info['mentorship_field']; ?></p>
            <p><strong>Mentee Applied For Mentor Change: </strong><?php echo $matched_mentee_info['change_req'] === 'YES' ? 'on ' . $matched_mentee_info['change_req_date'] : 'NO';?></p>
            <p><div id="logout"><a href="#" class="btn_one">Chat</a></div></p>
        <?php else: ?>
            <!-- Display a message if no match is found -->
            <p>No match yet</p>
        <?php endif; ?>
    </div>
</div>


    <div id="footer">
        Unlock Your Potential in an Inclusive Community <br><br> 
        <a href="https://imfunniee.github.io" class="footer-link">Made as a part of YCFP 2023 Program</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="index.js" type="text/javascript"></script>
    <script>
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
            window.location.href = "mentor_delete_account.php";
        }
        }
    </script>
</body>
</html>
