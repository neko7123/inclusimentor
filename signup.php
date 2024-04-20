<?php
session_start();
include("connection.php");
include("function.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Something was posted
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $bio = $_POST['bio'];
    $email = $_POST['email'];
    $profile_pic = $_FILES['profile_pic']['name'];
    $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];
    $gender = $_POST['gender'];
    $pronouns = $_POST['pronouns'];
    $age = $_POST['age'];
    $college = $_POST['college'];
    $password = $_POST['password'];
    $ed_field = $_POST['ed_field'];
    $mentorship_field = $_POST['mentorship_field'];

    $full_name = mysqli_real_escape_string($con, $full_name);
    $username = mysqli_real_escape_string($con, $username);
    $bio = mysqli_real_escape_string($con, $bio);
    $email = mysqli_real_escape_string($con, $email);
    $gender = mysqli_real_escape_string($con, $gender);
    $pronouns = mysqli_real_escape_string($con, $pronouns);
    $age = mysqli_real_escape_string($con, $age);
    $college = mysqli_real_escape_string($con, $college);
    $password = mysqli_real_escape_string($con, $password);
    $ed_field = mysqli_real_escape_string($con, $ed_field);
    $mentorship_field = mysqli_real_escape_string($con, $mentorship_field);
    
    if (!empty($username) && !empty($password) && !empty($email) && !empty($profile_pic)) {
        // Check if the username already exists in either mentee or mentor tables
        $check_username_query = "SELECT username FROM mentee WHERE username = '$username' UNION SELECT username FROM mentor WHERE username = '$username'";
        $check_username_result = mysqli_query($con, $check_username_query);

        // Check if the email already exists in either mentee or mentor tables
        $check_email_query = "SELECT email FROM mentee WHERE email = '$email' UNION SELECT email FROM mentor WHERE email = '$email'";
        $check_email_result = mysqli_query($con, $check_email_query);

        if ($check_username_result && mysqli_num_rows($check_username_result) > 0) {
            echo "Username already exists. Please choose a different one.";
        } elseif ($check_email_result && mysqli_num_rows($check_email_result) > 0) {
            echo "Email already exists. Please use a different one.";
        } else {
            $mentee_id = random_num(5, $con, 'mentee');// Update to 5 digits as per your requirement

            // Save profile picture to a directory on the server
            $profile_pic = $_FILES['profile_pic']['name'];
            $profile_pic_tmp = $_FILES['profile_pic']['tmp_name'];
            $profile_pic_path = "images/" . $profile_pic;
            move_uploaded_file($profile_pic_tmp, $profile_pic_path);

            $query = "INSERT INTO mentee (mentee_id, full_name, username, bio, email, password, profile_pic, gender, pronouns, age, college, ed_field, mentorship_field) VALUES ('$mentee_id', '$full_name', '$username', '$bio', '$email', '$password', '$profile_pic_path', '$gender', '$pronouns', '$age', '$college','$ed_field','$mentorship_field')";

            mysqli_query($con, $query);

            // Call the function to find and store matches after mentee signup
            findAndStoreMatches($con);

            // Redirect to login page for login as usual
            header("Location: login.php");
            die;
        }
    } else {
        echo "Enter valid information!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mentee Signup Page as Mentee</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div id="header" class="animated slideInDown" style="animation-delay:1.8s;">
        <div id="title">Signup to InclusiMentor as Mentee</div><br>
        <div id="links">
            <a href="index.php#skills" class="nav-link">About Us</a>
            <a href="index.php#work" class="nav-link" style="margin:0px 60px;">Why Us?</a>
            <a href="index.php" class="nav-link" style="margin:0px 60px;">Home</a>
            <a href="login.php" class="nav-link">Login</a>
        </div>

    <center>
        <div id="middle">
            <div id="tagline" class="animated zoomIn" style="animation-delay:1.8s;">
                <form method="post" enctype="multipart/form-data">
                    <input type="text" name="full_name" placeholder="Full Name" required class="form-input">
                    <input type="text" name="username" placeholder="Username" required class="form-input">
                    <input type="text" name="bio" placeholder="Bio" required class="form-input">
                    <input type="file" name="profile_pic" accept="image/*" placeholder="Profile Picture" required class="form-input">
                    <input type="text" name="gender" placeholder="Gender" required class="form-input">
                    <input type="text" name="pronouns" placeholder="Pronouns" required class="form-input">
                    <input type="number" name="age" placeholder="Age" required class="form-input">
                    <input type="text" name="college" placeholder="Educational Institution Name" required class="form-input">
                    <input type="text" name="ed_field" placeholder="Field of Education" required class="form-input">
                    <select name="mentorship_field" required class="form-input">
                        <option value="" disabled selected>Interested Field to be Mentored:</option>
                        <option value="" disabled>--|Engineering|--</option>
                        <option value="Civil Engineering">Civil Engineering</option>
                        <option value="Mechanical Engineering">Mechanical Engineering</option>
                        <option value="Electrical Engineering">Electrical Engineering</option>
                        <option value="Chemical Engineering">Chemical Engineering</option>
                        <option value="Aerospace Engineering">Aerospace Engineering</option>
                        <option value="Computer Engineering">Computer Engineering</option>
                        <option value="Biomedical Engineering">Biomedical Engineering</option>
                        <option value="Environmental Engineering">Environmental Engineering</option>
                        <option value="Industrial Engineering">Industrial Engineering</option>
                        <option value="Structural Engineering">Structural Engineering</option>
                        <option value="" disabled>--|Finance and Banking|--</option>
                        <option value="Financial Analysis">Financial Analysis</option>
                        <option value="Investment Banking">Investment Banking</option>
                        <option value="Accounting">Accounting</option>
                        <option value="Actuarial Science">Actuarial Science</option>
                        <option value="Risk Management">Risk Management</option>
                        <option value="Financial Advisory">Financial Advisory</option>
                        <option value="Credit Analysis">Credit Analysis</option>
                        <option value="Hedge Fund Management">Hedge Fund Management</option>
                        <option value="Loan Origination">Loan Origination</option>
                        <option value="Stock Trading">Stock Trading</option>
                        <option value="" disabled>--|Law|--</option>
                        <option value="Legal Practice">Legal Practice</option>
                        <option value="Judiciary">Judiciary</option>
                        <option value="Legal Consulting">Legal Consulting</option>
                        <option value="Corporate Law">Corporate Law</option>
                        <option value="Public Defense">Public Defense</option>
                        <option value="Prosecution">Prosecution</option>
                        <option value="Intellectual Property Law">Intellectual Property Law</option>
                        <option value="Environmental Law">Environmental Law</option>
                        <option value="Family Law">Family Law</option>
                        <option value="Immigration Law">Immigration Law</option>
                        <option value="" disabled>--|Commerce|--</option>
                        <option value="Business Analysis">Business Analysis</option>
                        <option value="Marketing Management">Marketing Management</option>
                        <option value="Sales">Sales</option>
                        <option value="Human Resources Management">Human Resources Management</option>
                        <option value="Supply Chain Management">Supply Chain Management</option>
                        <option value="Management Consulting">Management Consulting</option>
                        <option value="Operations Management">Operations Management</option>
                        <option value="Retail Management">Retail Management</option>
                        <option value="Business Development">Business Development</option>
                        <option value="Entrepreneurship">Entrepreneurship</option>
                        <option value="" disabled>--|Arts|--</option>
                        <option value="Graphic Design">Graphic Design</option>
                        <option value="Writing/Authorship">Writing/Authorship</option>
                        <option value="Acting">Acting</option>
                        <option value="Music">Music</option>
                        <option value="Photography">Photography</option>
                        <option value="Film Directing">Film Directing</option>
                        <option value="Art Direction">Art Direction</option>
                        <option value="Animation">Animation</option>
                        <option value="Interior Design">Interior Design</option>
                        <option value="Curatorial Skills">Curatorial Skills</option>
                    </select>
                    <input type="email" name="email" placeholder="Email ID" required class="form-input">
                    <input type="password" name="password" placeholder="Password" required class="form-input" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{1,8}$" title="Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 special character, 1 number, and be a maximum of 8 characters">
                    <font size="2">Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 special character, 1 number, and be a maximum of 8 characters. <pr>By Signing up for this program you accept the terms and conditions of InclusiMentor.</pr></font>
                    <input type="submit" value = "Signup" class="btn_one">
                </form>
            </div>
        <div id="footer">
        Unlock Your Potential in an Inclusive Community <br><br> 
        <a href="https://imfunniee.github.io" class="footer-link">Made as a part of YCFP 2023 Program</a>
        </div>
        </div>
    </center>
    <script src="index.js" type="text/javascript"></script>
</body>
</html>
